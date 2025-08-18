FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip gd

COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/symfony
