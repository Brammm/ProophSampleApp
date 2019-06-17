FROM php:7.3-fpm-stretch

RUN apt-get update \
    && apt-get install -y libicu-dev \
    && docker-php-ext-install pdo_mysql intl

COPY . /code
WORKDIR /code
