FROM composer AS builder

ARG COMPOSER_ARGS="-o --no-dev --ignore-platform-reqs --no-scripts"

COPY . /application
WORKDIR /application

RUN rm -rf /application/.git \
    && cp -f /application/.env.dist /application/.env \
    && composer install $COMPOSER_ARGS

FROM php:7.4.6-fpm

RUN apt-get update \
    && docker-php-ext-install mysqli pdo pdo_mysql

RUN apt-get update \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

WORKDIR "/application/"

COPY docker/php-fpm/config/override.conf /etc/php/7.4/etc/php-fpm.d/z-overrides.conf
COPY docker/php-fpm/php-ini-overrides.ini  /usr/local/etc/php/conf.d/99-overrides.ini
COPY docker/php-fpm/entrypoint.sh /vitta-entrypoint

COPY --from=builder /application /application

WORKDIR /application

ENTRYPOINT /vitta-entrypoint
