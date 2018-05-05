FROM php:7-fpm-alpine

ARG COMPOSER_ALLOW_SUPERUSER 
ARG STORAGE_PASSWORD 
ARG STORAGE_DB_NAME 
ARG STORAGE_PORT 
ARG STORAGE_HOST 
ARG STORAGE_USERNAME 
ARG PASSWORD_KEYS_API 
ARG PORT_HTTP 
ARG PORT_HTTPS 
ARG SYMFONY_ENV 
ARG STORAGE_BROKER_HOST 
ARG STORAGE_BROKER_PORT 
ARG STORAGE_BROKER_SCHEME 
ARG STORAGE_BROKER_DB 
ARG STORAGE_BROKER_PASSWORD 
ARG BROKER_HOST 
ARG BROKER_PORT 
ARG BROKER_NAME 
ARG BROKER_PASS 
ARG BROKER_CLIENT_NAME 
ARG BROKER_TOPIC_NAME

ENV COMPOSER_ALLOW_SUPERUSER ${COMPOSER_ALLOW_SUPERUSER}
ENV STORAGE_PASSWORD ${STORAGE_PASSWORD}
ENV STORAGE_DB_NAME ${STORAGE_DB_NAME}
ENV STORAGE_PORT ${STORAGE_PORT}
ENV STORAGE_HOST ${STORAGE_HOST}
ENV STORAGE_USERNAME ${STORAGE_USERNAME}
ENV PASSWORD_KEYS_API ${PASSWORD_KEYS_API}
ENV PORT_HTTP ${PORT_HTTP}
ENV PORT_HTTPS ${PORT_HTTPS}
ENV SYMFONY_ENV ${SYMFONY_ENV}
ENV STORAGE_BROKER_HOST ${STORAGE_BROKER_HOST}
ENV STORAGE_BROKER_PORT ${STORAGE_BROKER_PORT}
ENV STORAGE_BROKER_SCHEME ${STORAGE_BROKER_SCHEME}
ENV STORAGE_BROKER_DB ${STORAGE_BROKER_DB}
ENV STORAGE_BROKER_PASSWORD ${STORAGE_BROKER_PASSWORD}
ENV BROKER_HOST ${BROKER_HOST}
ENV BROKER_PORT ${BROKER_PORT}
ENV BROKER_NAME ${BROKER_NAME}
ENV BROKER_PASS ${BROKER_PASS}
ENV BROKER_CLIENT_NAME ${BROKER_CLIENT_NAME}
ENV BROKER_TOPIC_NAME ${BROKER_TOPIC_NAME}

ENV TZ "Europe/Kiev"
ENV APP_PATH "/var/app"

# set time
RUN apk add --update tzdata && \
    cp /usr/share/zoneinfo/${TZ} /etc/localtime && \
    echo ${TZ} > /etc/timezone

RUN apk add --no-cache --virtual .persistent-deps \
        git \
        icu-libs \
        acl \
        zlib

RUN set -xe \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        icu-dev \
        zlib-dev \
        openssl \
        pcre-dev \
    && docker-php-ext-install \
        intl \
        mbstring \
        pdo_mysql \
        pdo \
        zip \
    && pecl install \
    && apk del .build-deps

RUN rm -rf /var/cache/apk/* && rm -rf /tmp/*

# install lib for post mosquitto 
RUN apk add --no-cache --virtual .build-deps build-base && \
    apk add --no-cache --virtual .build-deps mosquitto-dev && \
    apk add --update --virtual autoconf && \
    yes '' | pecl install -f Mosquitto-alpha

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
# RUN apk add --no-cache --repository http://dl-3.alpinelinux.org/alpine/edge/testing gnu-libiconv
# ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so php

# USER www-data

RUN mkdir -p ${APP_PATH}

ADD ./src ${APP_PATH}
RUN cd ${APP_PATH}

RUN composer install -n -d /var/app

RUN chown -R www-data:www-data ${APP_PATH}

WORKDIR ${APP_PATH}

RUN HTTPDUSER=$(ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1)
RUN setfacl -dR -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX var/cache var/logs
RUN setfacl -R -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX var/cache var/logs

RUN mkdir -p var/jwt

# generate keys for api auth
RUN openssl genrsa -out var/jwt/private.pem -aes256 -passout pass:${PASSWORD_KEYS_API} 4096
RUN openssl rsa -pubout -in var/jwt/private.pem -out var/jwt/public.pem -passin pass:${PASSWORD_KEYS_API}

ADD ./deploy/php/php-fpm.conf /usr/local/etc/php-fpm.conf
ADD ./deploy/php/php.ini /usr/local/etc/php/php.ini

# RUN ln -sf /dev/stdout ./var/logs/prod.log

# openrc for nginx
CMD php-fpm -F
