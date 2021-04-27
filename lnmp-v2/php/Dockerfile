FROM php:7.2-fpm

# redis扩展最新版本是5.0.2，redis镜像最新版本是5.0.5
# 代码最新去github，docker镜像最新去hub
ENV PHPREDIS_VERSION 5.0.2
ENV SWOOLE_VERSION 4.4.3

RUN cd ~ && apt-get update && apt-get install -y --no-install-recommends apt-utils

RUN cd / \
    && mkdir -m 775 www \
    && useradd --create-home --no-log-init --shell /bin/bash git \
    && cd ~ \
    # Libs
    && apt-get update \
    && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        curl \
        wget \
        libz-dev \
        libssl-dev \
        libnghttp2-dev \
        libpcre3-dev \
    # 方式一：docker-php-ext-install 使用docker提供的直接安装PHP扩展
    # 安装php扩展，可以直接安装的，pdo_mysql mysqli pcntl gd
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j $(nproc) gd \
    && docker-php-ext-install pdo_mysql mysqli pcntl \
    # 方式二：用 pecl install 安装扩展，然后再用官方提供的 docker-php-ext-enable 快捷脚本来启用扩展
    # 安装扩展，无法直接安装，可通过pecl安装 redis swoole
    # You should add "extension=redis.so" to php.ini
    # You should add "extension=swoole.so" to php.ini
#    && pecl install redis-$PHPREDIS_VERSION \
#    && pecl install swoole-$SWOOLE_VERSION \
#    && docker-php-ext-enable redis swoole \
    # 方式三：下载扩展源码包，解压之后，直接将扩展包放到容器php-ext目录里，然后docker-php-ext-install
    # redis
    && cd ~ \
    && wget https://github.com/phpredis/phpredis/archive/$PHPREDIS_VERSION.tar.gz \
    && tar -zxvf $PHPREDIS_VERSION.tar.gz \
    && mkdir -p /usr/src/php/ext \
    && mv phpredis-$PHPREDIS_VERSION /usr/src/php/ext/redis \
    && docker-php-ext-install redis \
    # swoole
    && cd ~ \
    && wget https://github.com/swoole/swoole-src/archive/v$SWOOLE_VERSION.tar.gz \
    && tar -zxvf v$SWOOLE_VERSION.tar.gz \
    && mkdir -p /usr/src/php/ext \
    && mv swoole-src-$SWOOLE_VERSION /usr/src/php/ext/swoole \
    && docker-php-ext-install swoole \
    # 安装 Composer
    && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && composer self-update --clean-backups \