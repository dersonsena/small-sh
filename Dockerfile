FROM php:8.0.9-alpine3.14
LABEL maintainer="dersonsena@gmail.com"

RUN apk update && apk add --no-cache --update \
    nano \
    g++ \
    gcc \
    curl \
    curl-dev \
    zip \
    unzip \
    wget \
    make \
    bash \
    git \
    icu-dev \
    oniguruma-dev \
    tzdata \
    libzip-dev \
    libmcrypt-dev \
    autoconf

RUN docker-php-ext-install pdo \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install intl \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install zip \
    && docker-php-ext-install curl \
    && php -m

# Installing Mcrypt extension
RUN pecl install -o -f mcrypt && docker-php-ext-enable mcrypt

RUN rm -rf /var/cache/apk/*

COPY --from=composer /usr/bin/composer /usr/bin/composer

EXPOSE 80

WORKDIR /usr/src/app