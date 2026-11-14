// Books REST API written in Go using only the standard library.
//
//	GET    /health         health check
//	GET    /api/books      list books
//	GET    /api/books/{id} one book
//	POST   /api/books      create a book (JSON body)
//	PUT    /api/books/{id} update a book (JSON body)
//	DELETE /api/books/{id} delete a book
//
// Run with: go run .
// The port defaults to 8080 and can be changed with the PORT variable.
package main

import (
	"encoding/json"
	"errors"
	"log"
	"net/http"
	"os"
	"strconv"
	"strings"
	"sync"
	"time"
)

// Book is the JSON shape shared with the Laravel frontend.
type Book struct {
	ID        int    `json:"id"`
	Title     string `json:"title"`
	Author    string `json:"author"`
	Year      int    `json:"year"`
	CreatedAt string `json:"created_at"`
}

// BookInput is the body accepted by POST and PUT.
type BookInput struct {
	Title  string `json:"title"`
	Author string `json:"author"`
	Year   int    `json:"year"`
}

// Store keeps the books in memory. The mutex protects concurrent access.
type Store struct {
	mu     sync.RWMutex
	books  map[int]Book
	nextID int
}

func NewStore() *Store {
	s := &Store{books: make(map[int]Book), nextID: 1}

	// A few books so the frontend has data on the first run.
	s.Create(BookInput{Title: "The Go Programming Language", Author: "Alan Donovan", Year: 2015})
	s.Create(BookInput{Title: "Clean Code", Author: "Robert C. Martin", Year: 2008})
	s.Create(BookInput{Title: "Laravel: Up & Running", Author: "Matt Stauffer", Year: 2019})

	return s
}

func (s *Store) List() []Book {
	s.mu.RLock()
	defer s.mu.RUnlock()

	// Return a copy ordered by ID.
	out := make([]Book, 0, len(s.books))
	for id := 1; id < s.nextID; id++ {
		if b, ok := s.books[id]; ok {
			out = append(out, b)
		}
	}

	return out
}

func (s *Store) Get(id int) (Book, bool) {
	s.mu.RLock()
	defer s.mu.RUnlock()

	b, ok := s.books[id]
	return b, ok
}

func (s *Store) Create(in BookInput) Book {
	s.mu.Lock()
	defer s.mu.Unlock()

	b := Book{
		ID:        s.nextID,
		Title:     in.Title,
		Author:    in.Author,
		Year:      in.Year,
		CreatedAt: time.Now().UTC().Format(time.RFC3339),
	}

	s.books[b.ID] = b
	s.nextID++

	return b
}

func (s *Store) Update(id int, in BookInput) (Book, bool) {
	s.mu.Lock()
	defer s.mu.Unlock()

	b, ok := s.books[id]
	if !ok {
		return Book{}, false
	}

	b.Title, b.Author, b.Year = in.Title, in.Author, in.Year
	s.books[id] = b

	return b, true
}

func (s *Store) Delete(id int) bool {
	s.mu.Lock()
	defer s.mu.Unlock()

	if _, ok := s.books[id]; !ok {
		return false
	}

	delete(s.books, id)

	return true
}

// Server connects the store to the HTTP handlers.
type Server struct {
	store *Store
}

func main() {
	port := os.Getenv("PORT")
	if port == "" {
		port = "8080"
	}

	srv := &Server{store: NewStore()}

	log.Printf("Go Books API listening on http://localhost:%s", port)
	if err := http.ListenAndServe(":"+port, srv.routes()); err != nil {
		log.Fatal(err)
	}
}

// routes is separate from main so the tests can build the same handler.
func (s *Server) routes() http.Handler {
	mux := http.NewServeMux()
	mux.HandleFunc("/health", s.health)
	mux.HandleFunc("/api/books", s.booksCollection)
	mux.HandleFunc("/api/books/", s.bookItem)

	return withCORS(withLogging(mux))
}

func (s *Server) health(w http.ResponseWriter, r *http.Request) {
	writeJSON(w, http.StatusOK, map[string]string{"status": "ok", "service": "go-books-api"})
}

func (s *Server) booksCollection(w http.ResponseWriter, r *http.Request) {
	switch r.Method {
	case http.MethodGet:
		books := s.store.List()
		writeJSON(w, http.StatusOK, map[string]any{"data": books, "count": len(books)})

	case http.MethodPost:
		var in BookInput
		if err := decodeJSON(w, r, &in); err != nil {
			writeError(w, http.StatusBadRequest, "صيغة JSON غير صحيحة: "+err.Error())
			return
		}

		in, msg := validate(in)
		if msg != "" {
			writeError(w, http.StatusUnprocessableEntity, msg)
			return
		}

		book := s.store.Create(in)
		writeJSON(w, http.StatusCreated, map[string]any{"data": book, "message": "تمت إضافة الكتاب بنجاح"})

	default:
		w.Header().Set("Allow", "GET, POST")
		writeError(w, http.StatusMethodNotAllowed, "method not allowed")
	}
}

func (s *Server) bookItem(w http.ResponseWriter, r *http.Request) {
	id, err := parseID(r.URL.Path)
	if err != nil {
		writeError(w, http.StatusBadRequest, "معرّف الكتاب غير صالح")
		return
	}

	switch r.Method {
	case http.MethodGet:
		book, ok := s.store.Get(id)
		if !ok {
			writeError(w, http.StatusNotFound, "الكتاب غير موجود")
			return
		}
		writeJSON(w, http.StatusOK, map[string]any{"data": book})

	case http.MethodPut:
		var in BookInput
		if err := decodeJSON(w, r, &in); err != nil {
			writeError(w, http.StatusBadRequest, "صيغة JSON غير صحيحة: "+err.Error())
			return
		}

		in, msg := validate(in)
		if msg != "" {
			writeError(w, http.StatusUnprocessableEntity, msg)
			return
		}

		book, ok := s.store.Update(id, in)
		if !ok {
			writeError(w, http.StatusNotFound, "الكتاب غير موجود")
			return
		}
		writeJSON(w, http.StatusOK, map[string]any{"data": book, "message": "تم تحديث الكتاب"})

	case http.MethodDelete:
		if !s.store.Delete(id) {
			writeError(w, http.StatusNotFound, "الكتاب غير موجود")
			return
		}
		writeJSON(w, http.StatusOK, map[string]string{"message": "تم حذف الكتاب"})

	default:
		w.Header().Set("Allow", "GET, PUT, DELETE")
		writeError(w, http.StatusMethodNotAllowed, "method not allowed")
	}
}

func decodeJSON(w http.ResponseWriter, r *http.Request, dst any) error {
	defer r.Body.Close()

	dec := json.NewDecoder(http.MaxBytesReader(w, r.Body, 1<<20))
	dec.DisallowUnknownFields()

	if err := dec.Decode(dst); err != nil {
		return err
	}
	if dec.More() {
		return errors.New("unexpected data after JSON object")
	}

	return nil
}

// validate also trims the text fields and returns the cleaned input.
func validate(in BookInput) (BookInput, string) {
	in.Title = strings.TrimSpace(in.Title)
	in.Author = strings.TrimSpace(in.Author)

	switch {
	case in.Title == "":
		return in, "عنوان الكتاب مطلوب"
	case in.Author == "":
		return in, "اسم المؤلف مطلوب"
	case in.Year < 1000 || in.Year > time.Now().Year()+1:
		return in, "سنة النشر غير صالحة"
	}

	return in, ""
}

func parseID(path string) (int, error) {
	raw := strings.TrimPrefix(path, "/api/books/")
	if raw == "" || strings.Contains(raw, "/") {
		return 0, errors.New("invalid id")
	}

	return strconv.Atoi(raw)
}

func writeJSON(w http.ResponseWriter, status int, payload any) {
	w.Header().Set("Content-Type", "application/json; charset=utf-8")
	w.WriteHeader(status)

	if err := json.NewEncoder(w).Encode(payload); err != nil {
		log.Printf("encode response: %v", err)
	}
}

func writeError(w http.ResponseWriter, status int, message string) {
	writeJSON(w, status, map[string]string{"error": message})
}

// withLogging prints one line per request.
func withLogging(next http.Handler) http.Handler {
	return http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
		start := time.Now()
		next.ServeHTTP(w, r)
		log.Printf("%s %s (%s)", r.Method, r.URL.Path, time.Since(start))
	})
}

// withCORS allows requests from a browser on another origin.
func withCORS(next http.Handler) http.Handler {
	return http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
		w.Header().Set("Access-Control-Allow-Origin", "*")
		w.Header().Set("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE, OPTIONS")
		w.Header().Set("Access-Control-Allow-Headers", "Content-Type, Accept")

		if r.Method == http.MethodOptions {
			w.WriteHeader(http.StatusNoContent)
			return
		}

		next.ServeHTTP(w, r)
	})
}
