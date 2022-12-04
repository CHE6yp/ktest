FROM php:8.1.12-fpm-alpine

run apk update
RUN apk add --no-cache coreutils \
    git \
    postgresql-dev \
    bash \
    nano
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash
RUN apk add symfony-cli

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && docker-php-ext-install pdo pdo_pgsql pgsql


COPY --from=composer:2.1.3 /usr/bin/composer /usr/bin/composer

RUN addgroup -g 1000 www \
    && adduser --no-create-home --disabled-password --uid 1000 --home /var/www/backend --shell /bin/sh --ingroup www www

COPY symfony.ini /usr/local/etc/php/conf.d/
# COPY xdebug.ini /usr/local/etc/php/conf.d/

RUN rm /usr/local/etc/php-fpm.d/*.conf
COPY symfony.pool.conf /usr/local/etc/php-fpm.d/

CMD ["sh", "-c", "php-fpm -F"]

WORKDIR /usr/src/symfony
EXPOSE 9001