FROM php:7.2-fpm-stretch

RUN apt-get update \
    && apt-get install -y libicu-dev libpq-dev \
    && docker-php-ext-install pdo_pgsql intl

COPY . /code
WORKDIR /code
