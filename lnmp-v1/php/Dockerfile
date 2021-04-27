FROM daocloud.io/library/php:7.3-fpm-alpine

ENV PHPREDIS_VERSION 5.0.2
ENV SWOOLE_VERSION 4.4.2

RUN cd / \
    && mkdir -m 775 www \
#    && chown nginx:nginx www \
    && cd ~ \
    && apk add curl wget \
    #&& useradd --create-home --no-log-init --shell /bin/bash git \
    && addgroup -g 99 -S git \
    && adduser -u 99 -D -S -G git git \
    && wget https://github.com/phpredis/phpredis/archive/$PHPREDIS_VERSION.tar.gz \
    && tar -zxvf $PHPREDIS_VERSION.tar.gz \
    && mkdir -p /usr/src/php/ext \
    && mv phpredis-$PHPREDIS_VERSION /usr/src/php/ext/redis \
    && docker-php-ext-install redis \
    ###
    && apk add libstdc++ libpng-dev \
    && cd ~ \
    && wget https://github.com/swoole/swoole-src/archive/v$SWOOLE_VERSION.tar.gz \
    && tar -zxvf v$SWOOLE_VERSION.tar.gz \
    && mkdir -p /usr/src/php/ext \
    && mv swoole-src-$SWOOLE_VERSION /usr/src/php/ext/swoole \
    && docker-php-ext-install swoole \
    ###
    && docker-php-ext-install pdo_mysql mysqli pcntl gd\
    ###
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer \
