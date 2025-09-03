
FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

# --- System Dependencies --
RUN apk add --no-cache \
    git \
    icu-dev \
    icu-data-full \
    zip \
    unzip \
    bash

# --- PHP Extensions ---
RUN docker-php-ext-install pdo_mysql intl opcache


# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY backend/composer.json backend/composer.lock ./

RUN composer install --prefer-dist --no-scripts --no-progress --no-interaction

COPY backend/. .


RUN composer dump-autoload --optimize && \
    composer run-script post-install-cmd

RUN chown -R www-data:www-data .

EXPOSE 9000

# The command to run the application
CMD ["php-fpm"]