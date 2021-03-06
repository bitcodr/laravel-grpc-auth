FROM composer:latest as vendor

WORKDIR /app

COPY . .

RUN composer install --no-ansi --no-dev --no-interaction --no-plugins --no-progress --no-scripts --no-suggest --optimize-autoloader

FROM golang:1.13-alpine as golang

RUN apk --update --no-cache add bash

RUN apk --update --no-cache add --repository=http://dl-cdn.alpinelinux.org/alpine/edge/main --repository=http://dl-cdn.alpinelinux.org/alpine/edge/community grpc

WORKDIR /app

COPY --from=vendor /app .

RUN bash vendor/spiral/php-grpc/build.sh build Linux linux amd64
RUN bash vendor/spiral/php-grpc/build.sh build_protoc Linux linux amd64

RUN protoc --plugin=./vendor/spiral/php-grpc/protoc-gen-php-grpc --php_out=./protocolBuffer/auth/compiled --php-grpc_out=./protocolBuffer/auth/compiled protocolBuffer/**/*.proto

FROM php:7.4-zts-alpine

RUN apk add --update --no-cache --virtual .build-deps curl autoconf gcc make g++ zlib-dev

WORKDIR /app

RUN docker-php-ext-install pdo_mysql bcmath

RUN addgroup -S admin && adduser -S admin -G admin

COPY --from=golang /app .

RUN apk del .build-deps

RUN chown -R admin:admin /app
RUN chmod 755 -R /app
RUN chmod 755 -R /app/config
USER admin

EXPOSE 3000

ENTRYPOINT ["vendor/spiral/php-grpc/rr-grpc", "serve", "-v", "-d"]