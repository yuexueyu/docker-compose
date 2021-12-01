# 命令行缩短 + no-cache 可以缩小点儿镜像大小
# docker build -t php:7.4.25-fpm-alpine3.14-laravel-min .

FROM php:7.4.25-fpm-alpine3.14

# Add Repositories
RUN mv /etc/apk/repositories /etc/apk/repositories.bak
ADD ./repositories /etc/apk/
RUN apk update

# Add Build Dependencies
RUN apk add --no-cache --virtual .build-deps  \
    zlib-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libxml2-dev \
    bzip2-dev \
    zip \
    libzip-dev

# Add Production Dependencies
RUN apk add --update --no-cache \
    jpegoptim \
    pngquant \
    optipng \
    nano \
    icu-dev \
    tzdata \
    freetype-dev

# Configure & Install Extension
RUN docker-php-ext-configure \
    opcache --enable-opcache &&\
    docker-php-ext-configure gd --with-jpeg=/usr/include/ --with-freetype=/usr/include/ && \
    docker-php-ext-install \
    opcache \
    mysqli \
    pdo \
    pdo_mysql \
    sockets \
    json \
    intl \
    gd \
    xml \
    bz2 \
    pcntl \
    bcmath \
    zip

# Add Composer
RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer && \
    composer config -g repo.packagist composer https://packagist.phpcomposer.com

ENV TZ=Asia/Shanghai
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="./vendor/bin:$PATH"

ADD opcache.ini $PHP_INI_DIR/conf.d/
# ADD php.ini $PHP_INI_DIR/conf.d/
# ADD php-fpm.conf $PHP_INI_DIR/conf.d/

# Remove Build Dependencies
RUN apk del -f .build-deps && rm -rf /var/cache/apk/*
# Setup Working Dir
WORKDIR /var/www

# image description
LABEL Author="Jack" Version="2021.12.01"