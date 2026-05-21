FROM php:8.4-cli

ARG CACHEBUST=9

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libxml2-dev libzip-dev \
    libonig-dev libfreetype6-dev libjpeg62-turbo-dev nodejs npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring xml ctype fileinfo gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --no-scripts --no-interaction
RUN composer dump-autoload --no-interaction

RUN sed -i 's/return $port + $this->portOffset;/return (int) $port + $this->portOffset;/' vendor/laravel/framework/src/Illuminate/Foundation/Console/ServeCommand.php

RUN npm install && npm run build

EXPOSE 8000