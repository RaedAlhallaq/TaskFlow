package main

import (
	"bytes"
	"encoding/json"
	"net/http"
	"net/http/httptest"
	"testing"
)

func newTestServer() *Server {
	return &Server{store: NewStore()}
}

func TestListBooks(t *testing.T) {
	srv := newTestServer()
	rec := httptest.NewRecorder()
	srv.routes().ServeHTTP(rec, httptest.NewRequest(http.MethodGet, "/api/books", nil))

	if rec.Code != http.StatusOK {
		t.Fatalf("expected 200, got %d", rec.Code)
	}

	var body struct {
		Data  []Book `json:"data"`
		Count int    `json:"count"`
	}
	if err := json.Unmarshal(rec.Body.Bytes(), &body); err != nil {
		t.Fatalf("invalid JSON: %v", err)
	}
	if body.Count != 3 || len(body.Data) != 3 {
		t.Fatalf("expected 3 seeded books, got %d", body.Count)
	}
}

func TestCreateBook(t *testing.T) {
	srv := newTestServer()
	payload := []byte(`{"title":"Domain-Driven Design","author":"Eric Evans","year":2003}`)
	rec := httptest.NewRecorder()
	req := httptest.NewRequest(http.MethodPost, "/api/books", bytes.NewReader(payload))
	req.Header.Set("Content-Type", "application/json")
	srv.routes().ServeHTTP(rec, req)

	if rec.Code != http.StatusCreated {
		t.Fatalf("expected 201, got %d (%s)", rec.Code, rec.Body.String())
	}

	var body struct {
		Data Book `json:"data"`
	}
	if err := json.Unmarshal(rec.Body.Bytes(), &body); err != nil {
		t.Fatalf("invalid JSON: %v", err)
	}
	if body.Data.ID != 4 || body.Data.Title != "Domain-Driven Design" {
		t.Fatalf("unexpected created book: %+v", body.Data)
	}
}

func TestCreateBookValidation(t *testing.T) {
	srv := newTestServer()
	payload := []byte(`{"title":"","author":"Someone","year":2020}`)
	rec := httptest.NewRecorder()
	req := httptest.NewRequest(http.MethodPost, "/api/books", bytes.NewReader(payload))
	srv.routes().ServeHTTP(rec, req)

	if rec.Code != http.StatusUnprocessableEntity {
		t.Fatalf("expected 422, got %d", rec.Code)
	}
}

func TestCreateBookTrimsWhitespace(t *testing.T) {
	srv := newTestServer()
	payload := []byte(`{"title":"  Refactoring  ","author":"  Martin Fowler  ","year":2018}`)
	rec := httptest.NewRecorder()
	req := httptest.NewRequest(http.MethodPost, "/api/books", bytes.NewReader(payload))
	srv.routes().ServeHTTP(rec, req)

	if rec.Code != http.StatusCreated {
		t.Fatalf("expected 201, got %d (%s)", rec.Code, rec.Body.String())
	}

	var body struct {
		Data Book `json:"data"`
	}
	if err := json.Unmarshal(rec.Body.Bytes(), &body); err != nil {
		t.Fatalf("invalid JSON: %v", err)
	}
	if body.Data.Title != "Refactoring" || body.Data.Author != "Martin Fowler" {
		t.Fatalf("expected trimmed values, got %+v", body.Data)
	}
}

func TestUpdateBook(t *testing.T) {
	srv := newTestServer()
	payload := []byte(`{"title":"Clean Architecture","author":"Robert C. Martin","year":2017}`)
	rec := httptest.NewRecorder()
	req := httptest.NewRequest(http.MethodPut, "/api/books/2", bytes.NewReader(payload))
	srv.routes().ServeHTTP(rec, req)

	if rec.Code != http.StatusOK {
		t.Fatalf("expected 200, got %d (%s)", rec.Code, rec.Body.String())
	}

	book, ok := srv.store.Get(2)
	if !ok || book.Title != "Clean Architecture" {
		t.Fatalf("book was not updated: %+v", book)
	}
}

func TestDeleteBook(t *testing.T) {
	srv := newTestServer()
	rec := httptest.NewRecorder()
	srv.routes().ServeHTTP(rec, httptest.NewRequest(http.MethodDelete, "/api/books/1", nil))

	if rec.Code != http.StatusOK {
		t.Fatalf("expected 200, got %d", rec.Code)
	}
	if _, ok := srv.store.Get(1); ok {
		t.Fatal("book 1 should be deleted")
	}

	// Deleting again must return 404.
	rec = httptest.NewRecorder()
	srv.routes().ServeHTTP(rec, httptest.NewRequest(http.MethodDelete, "/api/books/1", nil))
	if rec.Code != http.StatusNotFound {
		t.Fatalf("expected 404, got %d", rec.Code)
	}
}
