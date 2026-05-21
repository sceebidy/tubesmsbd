FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libxml2-dev libzip-dev \
    libonig-dev libfreetype6-dev libjpeg62-turbo-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring xml ctype fileinfo gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN sed -i 's/return $port + $this->portOffset;/return (int) $port + $this->portOffset;/' vendor/laravel/framework/src/Illuminate/Foundation/Console/ServeCommand.php

EXPOSE 8000

CMD bash -c "php artisan migrate --force && php artisan storage:link && php artisan serve --host=0.0.0.0 --port=8000"