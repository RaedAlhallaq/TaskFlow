# Technical Notes — TaskFlow

## 1. Biggest Docker problem faced

**Problem:** The Laravel image build failed during `composer install` with:
```
> @php artisan package:discover --ansi
Could not open input file: artisan
Script @php artisan package:discover --ansi handling the post-autoload-dump event returned with error code 1
```

**Cause:** I copied only `composer.json` and `composer.lock` before running `composer install` (for better layer caching). Laravel's `composer.json` has a `post-autoload-dump` script that runs `php artisan package:discover`, which needs the `artisan` file — but `artisan` (and the rest of the app) had not been copied into the image yet.

**How we discovered it:** `docker compose up --build` stopped at the Laravel build step with the error above; the Go image built fine, so the failure was Laravel-specific.

**How we solved it:** Split dependency installation into two steps:
1. `composer install --no-dev ... --no-scripts` (dependencies only, no artisan needed).
2. Copy the full application source (`COPY laravel-app/ ./`).
3. `composer dump-autoload --optimize` (now `artisan` exists, so `package:discover` succeeds).

This keeps the dependency layer cacheable while fixing the missing-file error. The final image builds successfully and both containers report healthy (`taskflow-go` and `taskflow-web`).

## 2. Most important Git/GitHub lesson learned

Keeping the repository clean from the start (proper `.gitignore` for `vendor/`, `.env`, and build artifacts such as compiled Blade views) prevents large or secret files from ever entering history. Meaningful, scoped commits (feat/chore/docker/docs/fix/ci) make the project's progress readable and reviewable. A feature branch + Pull Request (Bonus G) keeps `main` stable while changes are reviewed.
