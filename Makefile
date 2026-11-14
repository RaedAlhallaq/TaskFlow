# Makefile for TaskFlow (Docker helpers)

.PHONY: build run stop logs clean test build-go build-web

build:
	docker compose build

run:
	docker compose up -d

stop:
	docker compose down

logs:
	docker compose logs -f

clean:
	docker compose down -v --rmi local

test:
	cd go-backend && go test ./...
	cd laravel-app && php artisan test

build-go:
	docker build -t taskflow-go-backend:latest ./go-backend

build-web:
	docker build -t taskflow-laravel:latest .
