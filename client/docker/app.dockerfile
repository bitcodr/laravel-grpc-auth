FROM composer:latest as vendor

WORKDIR /app

COPY dockerfiles .

RUN composer install --no-ansi --no-dev --no-interaction --no-plugins --no-progress --no-scripts --no-suggest --optimize-autoloader

FROM alpine:latest as grpc

RUN apk add --update --no-cache --repository=http://dl-cdn.alpinelinux.org/alpine/edge/main --repository=http://dl-cdn.alpinelinux.org/alpine/edge/community grpc

WORKDIR /app

COPY --from=vendor /app .

RUN protoc --plugin=protoc-gen-grpc=/usr/bin/grpc_php_plugin --grpc_out=./protocolBuffer/auth/compiled  --php_out=./protocolBuffer/auth/compiled protocolBuffer/**/*.proto

FROM php:7.4.9-fpm-alpine

RUN apk add --update --no-cache --virtual .build-deps curl autoconf gcc make g++ zlib-dev

RUN apk add --update --no-cache libstdc++

WORKDIR /app

RUN pecl install grpc protobuf

RUN docker-php-ext-install pdo_mysql bcmath

RUN docker-php-ext-enable grpc protobuf

COPY --from=grpc /app .

RUN apk del .build-deps
