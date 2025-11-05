FROM php:8.2-fpm-alpine

ARG USER=umanni
ARG UID=1000

RUN apk add --no-cache \
    build-base \
    linux-headers \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libzip-dev \
    libwebp-dev \
    mysql-client \
    nginx \
    supervisor \
    nodejs \
    npm \
    git \
    autoconf \
    make

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp

RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    xml \
    zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN addgroup -g $UID $USER \
    && adduser -u $UID -G $USER -s /bin/sh -D $USER

WORKDIR /var/www

USER $USER

EXPOSE 9000
CMD ["php-fpm"]

