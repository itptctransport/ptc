FROM dunglas/frankenphp:1-php8.2

WORKDIR /app

COPY . .

RUN install-php-extensions pdo_mysql zip intl gd bcmath opcache redis

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader --no-interaction

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
