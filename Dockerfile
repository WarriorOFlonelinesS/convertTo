FROM php:8.1-fpm-alpine

COPY ./config/php/php/php.ini /usr/local/etc/php/php.ini

ENV composer_allow_super_user=1
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

COPY ./app/composer.* ./

# Install build dependencies
RUN apk add --no-cache \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    build-base \
    autoconf \
    automake \
    libtool \
    cmake \
    git \
    pkgconfig \
    yasm \
    nasm \
    zlib-dev \
    lame-dev \
    opus-dev \
    libogg-dev \
    libvorbis-dev \
    x264-dev \
    x265-dev \
    libvpx-dev \
    libtheora-dev \
    ttf-dejavu \
    fontconfig \
    oniguruma-dev \
    icu-dev

# Install PHP extensions for better UTF-8 and multibyte support
RUN docker-php-ext-install mbstring intl

# Install fdk-aac
RUN cd /tmp && \
    git clone --depth 1 https://github.com/mstorsjo/fdk-aac.git && \
    cd fdk-aac && \
    autoreconf -fiv && \
    ./configure --prefix=/usr && \
    make -j$(nproc) && \
    make install

# Build FFmpeg with libfdk_aac
RUN cd /tmp && \
    git clone --depth 1 https://git.ffmpeg.org/ffmpeg.git && \
    cd ffmpeg && \
    ./configure \
    --prefix=/usr \
    --enable-gpl \
    --enable-nonfree \
    --enable-libfdk-aac \
    --enable-libmp3lame \
    --enable-libopus \
    --enable-libvorbis \
    --enable-libvpx \
    --enable-libx264 \
    --enable-libx265 && \
    make -j$(nproc) && \
    make install && \
    # Cleanup to reduce image size
    cd / && \
    rm -rf /tmp/* && \
    apk del build-base autoconf automake cmake git pkgconfig

RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
 && docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-install zip

RUN composer install --prefer-dist --no-dev --no-scripts --no-interaction --no-progress

COPY ./app .

RUN composer dump-autoload --optimize

# Update library path to include newly built libraries
RUN echo "/usr/local/lib" >> /etc/ld.so.conf.d/local.conf && ldconfig || true

CMD ["php-fpm"]
