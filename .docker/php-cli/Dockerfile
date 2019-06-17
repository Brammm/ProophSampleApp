FROM php:7.3-cli-stretch

RUN docker-php-ext-install pdo_mysql pcntl

COPY . /code
WORKDIR /code
