FROM php:8.3.7-fpm-alpine3.18 as php

ARG uid

ENV uid=${uid:-1000}

RUN adduser --disabled-password --uid ${uid} app

RUN mkdir "/app" && chown -R app:app /app

WORKDIR /app

RUN apk add --no-cache --update postgresql-dev

RUN docker-php-ext-install pdo pdo_pgsql

USER app

FROM php as php-dev

USER root

RUN apk add --no-cache --update linux-headers $PHPIZE_DEPS yaml-dev git

RUN pecl install xdebug-3.3.2 yaml \
  && docker-php-ext-enable xdebug yaml

COPY ./etc/xdebug/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

USER app
