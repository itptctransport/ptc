FROM dunglas/frankenphp:1-php8.2-bookworm

WORKDIR /app

# Install PHP extensions first so they are cached by Docker and not rebuilt on every code change
RUN install-php-extensions pdo_mysql zip intl gd bcmath opcache redis

# Copy the application files
COPY . .

# Copy Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Ensure .env exists to prevent Laravel boot failures during package discovery, then run composer
RUN cp -n .env.example .env || true \
    && composer install --no-dev --optimize-autoloader --no-interaction

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
