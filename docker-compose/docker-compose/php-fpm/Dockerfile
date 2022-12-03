FROM php:8.0.7-fpm-alpine
LABEL maintainer="Danila Shvets <danila.schvets@gmail.com>"

RUN apk add --no-cache coreutils \
    git \
    postgresql-dev \
    rabbitmq-c-dev


RUN git clone https://github.com/php-amqp/php-amqp.git /usr/src/php/ext/amqp \
    && docker-php-ext-configure /usr/src/php/ext/amqp \
    && docker-php-ext-install /usr/src/php/ext/amqp \
    && rm -rf /usr/src/php/ext/amqp \
    && docker-php-ext-install sockets

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