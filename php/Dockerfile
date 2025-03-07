FROM php:8.1-fpm-alpine


ENV composer_allow_super_user=1
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

COPY ./app/composer.* ./

RUN composer install --prefer-dist --no-dev --no-scripts --no-interaction --no-progress

COPY ./app .

RUN composer dump-autoload --optimize


CMD ["php-fpm"]
