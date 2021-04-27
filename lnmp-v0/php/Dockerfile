#
# Docker中php扩展安装
# 方式一：核心扩展：主要用到的是官方提供的 docker-php-ext-configure 和 docker-php-ext-install 快捷脚本。注意：这里的 docker-php-ext-configure 和 docker-php-ext-install 已经包含了 docker-php-source 的操作，所有不需要再手动去执行。
# 方式二：PECL扩展：因为一些扩展并不包含在 PHP 源码文件中，所有需要使用 PECL（PHP 的扩展库仓库，通过 PEAR 打包）。用 pecl install 安装扩展，然后再用官方提供的 docker-php-ext-enable 快捷脚本来启用扩展
# 方式三：其它扩展：一些既不在 PHP 源码包，也不再 PECL 扩展仓库中的扩展，可以通过下载扩展程序源码，编译安装的方式安装。下载扩展源码包，解压之后，直接将扩展包放到容器php-ext目录里，然后docker-php-ext-enable
# https://hub.docker.com/_/php/#how-to-install-more-php-extensions
# docker镜像最新去hub，php扩展最新去pecl，源代码最新去github
#
FROM php:7.2-fpm

WORKDIR /www
# 更新为国内镜像
RUN mv /etc/apt/sources.list /etc/apt/sources.list.bak
ADD ./sources.list /etc/apt/
RUN apt-get update
# 抑制错误debconf: delaying package configuration, since apt-utils is not installed
ARG DEBIAN_FRONTEND=noninteractive
RUN apt-get install -y --no-install-recommends --assume-yes apt-utils
RUN apt-get install -y --no-install-recommends \
        # 相关依赖必须手动安装
        dialog \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        libxml2-dev \
        libtidy-dev \
        libxslt1-dev \
        libc-client-dev \
        libkrb5-dev \
        librabbitmq-dev \
        imagemagick \
        libmagick++-dev \
        libicu-dev \
        libzip-dev \
        unzip \
        wget \
        git \
        g++
    # 方式一：核心扩展：主要用到的是官方提供的 docker-php-ext-configure 和 docker-php-ext-install 快捷脚本。注意：这里的 docker-php-ext-configure 和 docker-php-ext-install 已经包含了 docker-php-source 的操作，所以不需要再手动去执行。
    # 现在支持的：bcmath bz2 calendar ctype curl dba dom enchant exif ffi fileinfo filter ftp gd gettext gmp hash iconv imap intl json ldap mbstring mysqli oci8 odbc opcache pcntl pdo pdo_dblib pdo_firebird pdo_mysql pdo_oci pdo_odbc pdo_pgsql pdo_sqlite pgsql phar posix pspell readline reflection session shmop simplexml snmp soap sockets sodium spl standard sysvmsg sysvsem sysvshm tidy tokenizer xml xmlreader xmlrpc xmlwriter xsl zend_test zip
RUN docker-php-ext-install -j$(nproc) sockets bcmath mysqli pcntl pdo_mysql intl zip soap xmlrpc \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install -j$(nproc) imap
    # 方式二：PECL扩展：因为一些扩展并不包含在 PHP 源码文件中，所有需要使用 PECL（PHP 的扩展库仓库，通过 PEAR 打包）。用 pecl install 安装扩展，然后再用官方提供的 docker-php-ext-enable 快捷脚本来启用扩展
RUN pecl install -o -f redis mcrypt imagick oauth amqp xdebug swoole apcu \
    && docker-php-ext-enable redis mcrypt imagick oauth amqp xdebug swoole apcu
    # 方式三：其它扩展：一些既不在 PHP 源码包，也不再 PECL 扩展仓库中的扩展，可以通过下载扩展程序源码，编译安装的方式安装。下载扩展源码包，解压之后，直接将扩展包放到容器php-ext目录里，然后docker-php-ext-enable
    # 安装 Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && composer self-update --clean-backups \
    && chmod +x /usr/local/bin/composer \
    && composer config -g repo.packagist composer https://packagist.phpcomposer.com \
    && composer global require "fxp/composer-asset-plugin:^1.4.0"
    # 清除
RUN apt-get purge -y g++ \
    && apt-get autoremove -y \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# 镜像信息
LABEL Author="WU"
LABEL Version="2020.02.24"
LABEL Description="nginx:latest+php:7.2-fpm+mysql:5.6 开发环境镜像."

EXPOSE 9000

CMD ["php-fpm"]