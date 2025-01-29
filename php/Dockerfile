FROM php:8.1-fpm-alpine

# RUN apk add --no-cache postgresql-dev

# RUN docker-php-ext-install pdo_pgsql

ENV composer_allow_super_user=1
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

COPY ./app/composer.* ./

RUN composer install --prefer-dist --no-dev --no-scripts --no-interaction --no-progress

COPY ./app .

RUN composer dump-autoload --optimize


CMD ["php-fpm"]
