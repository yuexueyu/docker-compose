# 命令行 + no-cache 可以缩小点儿镜像大小
# docker build -t laravel-one:7.4.25-fpm-alpine3.14-laravel-one .

FROM php:7.4.25-fpm-alpine3.14

# Add Repositories
RUN mv /etc/apk/repositories /etc/apk/repositories.bak
ADD ./repositories /etc/apk/

# Add Build Dependencies
RUN apk update && apk add --no-cache --virtual .build-deps  \
    zlib-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libxml2-dev \
    bzip2-dev \
    zip

# Add Production Dependencies
RUN apk add --update --no-cache \
    jpegoptim \
    pngquant \
    optipng \
    nano \
    icu-dev \
    tzdata \
    freetype-dev

# Add Production Dependencies
RUN apk add --update --no-cache \
    jpegoptim \
    pngquant \
    optipng \
    supervisor \
    nano \
    icu-dev \
    freetype-dev \
    nginx \
    libzip-dev

# Configure & Install Extension
RUN docker-php-ext-configure \
    opcache --enable-opcache && \
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
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="./vendor/bin:$PATH"
ENV TZ=Asia/Shanghai

## 配置文件，本地的时候可以使用docker-compose编排，生产环境k8s的时候，可以将配置加到镜像中
COPY opcache.ini $PHP_INI_DIR/conf.d/
COPY php.ini $PHP_INI_DIR/
COPY php-fpm.conf $PHP_INI_DIR/conf.d/
COPY nginx.conf /etc/nginx/
COPY default.conf /etc/nginx/conf.d/

# Setup Crond and Supervisor by default
RUN echo '*  *  *  *  * /usr/local/bin/php  /var/www/artisan schedule:run >> /dev/null 2>&1' > /etc/crontabs/root && mkdir /etc/supervisor.d
COPY master.ini /etc/supervisor.d/

# Remove Build Dependencies
RUN apk del -f .build-deps && rm -rf /var/cache/apk/*
# Setup Working Dir
WORKDIR /var/www

CMD ["/usr/bin/supervisord"]

# image description
LABEL Author="Jack" Version="2021.12.07"