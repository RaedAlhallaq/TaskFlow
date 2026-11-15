# TaskFlow — Laravel + Livewire + Go

**Repository:** https://github.com/RaedAlhallaq/TaskFlow

TaskFlow is a small full-stack web application with two parts:

1. **Task Manager (Laravel + Livewire)** — create, edit, delete, change status, search and filter tasks without page reloads.
2. **Books (Laravel frontend + Go REST API)** — list, add and delete books through HTTP requests and JSON exchange.

This repository was created for **OS Lab – Assignment #2 (Docker & GitHub)**. The application is original work (not based on a third-party project), so no external attribution is required.

## Tech Stack

| Layer | Technology |
|---|---|
| Web app | Laravel 13, PHP 8.3, Livewire 4 |
| Database | SQLite (file-based, seeded with sample tasks) |
| REST API | Go 1.22 (standard library only) |
| Container | Docker, Docker Compose |
| CI | GitHub Actions |

## Project Structure

```text
TaskFlow/
├── go-backend/          # Go REST API (Books)
│   ├── main.go
│   ├── main_test.go
│   ├── go.mod
│   └── Dockerfile
├── laravel-app/         # Laravel + Livewire web app
│   ├── app/
│   ├── resources/
│   ├── routes/
│   ├── database/
│   └── ...
├── docs/
│   ├── notes.md
│   └── screenshots/
├── .github/workflows/   # CI (Docker build)
├── Dockerfile           # Laravel web app image
├── .dockerignore
├── .gitignore
├── docker-compose.yml   # Run both services (Bonus A)
├── Makefile             # Helper commands (Bonus E)
└── README.md
```

## Prerequisites

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) (or Docker Engine + Compose plugin)
- (Optional, for local non-Docker dev) PHP 8.3 + Composer, Go 1.22+

## Quick Start with Docker (recommended)

Build and run both services with one command:

```bash
docker compose up --build -d
```

Then open:

- Web app: http://localhost:8000
- Tasks: http://localhost:8000/tasks
- Books: http://localhost:8000/books
- Go API health: http://localhost:8080/health
- Laravel health: http://localhost:8000/up

Stop and clean up:

```bash
docker compose down
# remove images/volumes too:
docker compose down -v --rmi local
```

## Build & Run with Docker (manual)

Build images:

```bash
# Go API
docker build -t taskflow-go-backend ./go-backend

# Laravel web app
docker build -t taskflow-laravel .
```

Run containers:

```bash
# Go API (port 8080)
docker run -d -p 8080:8080 --name taskflow-go taskflow-go-backend

# Laravel web app (port 8000). Point it at the Go container:
docker run -d -p 8000:8000 --name taskflow-web \
  -e GO_API_URL=http://host.docker.internal:8080 \
  taskflow-laravel
```

> On Linux, replace `host.docker.internal` with the host IP (e.g. `172.17.0.1`) or run both via Compose.

Stop and remove:

```bash
docker stop taskflow-web taskflow-go
docker rm taskflow-web taskflow-go
docker rmi taskflow-laravel taskflow-go-backend
```

## Ports

| Service | Container port | Host port |
|---|---|---|
| Laravel web app | 8000 | 8000 |
| Go REST API | 8080 | 8080 |

## Configuration (Environment Variables)

**Laravel (`laravel-app/.env` / container env):**

| Variable | Required | Default | Purpose |
|---|---|---|---|
| `APP_KEY` | ✅ (generated in image) | — | Laravel app key |
| `APP_URL` | — | `http://127.0.0.1:8000` | Public URL |
| `DB_CONNECTION` | — | `sqlite` | Database driver |
| `GO_API_URL` | — | `http://127.0.0.1:8080` | Go Books API base URL |

**Go API:**

| Variable | Required | Default | Purpose |
|---|---|---|---|
| `PORT` | — | `8080` | Listen port |

In Docker Compose, `GO_API_URL` is set to `http://go-backend:8080` automatically.

## How to Test / Use

1. Open http://localhost:8000 (home page).
2. Go to `/tasks` → add/edit/delete tasks, change status, search/filter (Livewire, no reload).
3. Go to `/books` → books loaded from Go API; `/books/create` → add a book; delete from list.
4. API check: `curl http://localhost:8080/health` → `{"status":"ok","service":"go-books-api"}`.
5. Laravel health: `curl http://localhost:8000/up`.

### Run tests locally

```bash
# Go
cd go-backend
go test ./...

# Laravel
cd laravel-app
composer install
php artisan key:generate
php artisan migrate --seed --force
php artisan test
```

## Makefile helpers (Bonus E)

```bash
make build   # build all images
make run     # start with compose (detached)
make stop    # stop and remove
make logs    # follow logs
make clean   # remove containers, volumes, images
make test    # run Go + Laravel tests
```

## CI (Bonus B)

GitHub Actions workflow (`.github/workflows/docker.yml`) builds both Docker images on every push/pull request to `main`, and runs the Go test suite.

## Git Workflow (summary)

Professional commits were used, reflecting real progress:

```text
feat: add initial project structure
chore: add gitignore and dockerignore
docker: add Dockerfile for Laravel and Go services
docs: add Docker run instructions to README
fix: correct container startup command and env wiring
docs: add technical notes
```

A feature branch + Pull Request workflow (Bonus G) is used for documentation changes.

## Troubleshooting

- **`composer install` fails with "Could not open input file: artisan"**: the `post-autoload-dump` script needs `artisan`. Run `composer install --no-scripts` first, copy the app source, then `composer dump-autoload --optimize`. (Already handled in the Dockerfile.)
- **Books page shows "تعذّر الاتصال بخادم Go"**: ensure the Go container/service is running and `GO_API_URL` points to it (`http://go-backend:8080` in Compose, `http://host.docker.internal:8080` for a standalone Laravel container).
- **Laravel container unhealthy / 500 on `/up`**: check that `storage/` and `bootstrap/cache/` are writable and that migrations ran (`php artisan migrate --force`).
- **Port already in use**: change host ports in `docker-compose.yml` (e.g. `"8001:8000"`).

## Attribution

TaskFlow is original work by the student (Raed Alhallaq, student ID 120220871). No third-party project was used as a base, so no external attribution is required.

## License

This project is provided for educational purposes (OS Lab Assignment #2).
