FROM php:8.0-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libxml2-dev \
    libzip-dev \
    zlib1g-dev \
    git \
    unzip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && apt-get clean


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


COPY . /var/www/html

WORKDIR /var/www/html


RUN chown -R www-data:www-data /var/www/html