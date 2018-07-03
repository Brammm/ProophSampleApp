FROM php:7.2-cli-stretch

RUN apt-get update \
    && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_pgsql pcntl

COPY . /code
WORKDIR /code
