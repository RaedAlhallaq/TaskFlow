# Technical Notes — TaskFlow

## 1. Biggest Docker problem faced

_To be completed after running `docker build` / `docker compose up` on a machine with Docker.
This section must describe a REAL problem encountered and how it was solved (not invented)._

Candidate issues to watch (to be verified during testing):
- `GO_API_URL=http://127.0.0.1:8080` works for local dev but NOT between containers; Compose overrides it to `http://go-backend:8080`.
- Laravel needs `storage/` and `bootstrap/cache/` writable by the runtime user.
- SQLite file must be created before `php artisan migrate` runs in the image.
- PHP extensions `pdo_sqlite` and `zip` must be installed in the Alpine base image.

## 2. Most important Git/GitHub lesson learned

Keeping the repository clean from the start (proper `.gitignore` for `vendor/`, `.env`, and build artifacts) prevents large or secret files from ever entering history. Meaningful, scoped commits (feat/chore/docker/docs/fix) make the project's progress readable and reviewable, and a feature branch + Pull Request (Bonus G) keeps `main` stable.
