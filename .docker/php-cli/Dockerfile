FROM php:7.2-cli-stretch

RUN docker-php-ext-install pdo_mysql pcntl

COPY . /code
WORKDIR /code
