# Dockerfile for the Laravel + Livewire web application (TaskFlow)
# Build context: repository root (TaskFlow/)

FROM php:8.3-cli-alpine

# System dependencies + PHP extensions required by Laravel
RUN apk add --no-cache \
        git \
        unzip \
        libzip-dev \
        sqlite-dev \
    && docker-php-ext-install zip pdo pdo_sqlite

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Install PHP dependencies first (better layer caching)
COPY laravel-app/composer.json laravel-app/composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Copy application source
COPY laravel-app/ ./

# Prepare runtime environment
RUN cp .env.example .env \
    && php artisan key:generate --force \
    && rm -f database/database.sqlite \
    && touch database/database.sqlite \
    && php artisan migrate --seed --force

EXPOSE 8000

HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
    CMD php -r "file_get_contents('http://127.0.0.1:8000/up') !== false;" || exit 1

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
