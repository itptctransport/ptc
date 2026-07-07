FROM dunglas/frankenphp

WORKDIR /app

COPY . .

RUN install-php-extensions pdo_mysql zip intl gd bcmath opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
