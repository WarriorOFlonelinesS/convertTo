FROM php:8.1-fpm-alpine

COPY ./config/php/php/php.ini /usr/local/etc/php/php.ini

ENV composer_allow_super_user=1
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

COPY ./app/composer.* ./
RUN apk add --no-cache freetype-dev libjpeg-turbo-dev libpng-dev

# Устанавливаем и конфигурируем расширение GD
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
 && docker-php-ext-install -j$(nproc) gd

RUN composer install --prefer-dist --no-dev --no-scripts --no-interaction --no-progress

COPY ./app .

RUN composer dump-autoload --optimize


CMD ["php-fpm"]
