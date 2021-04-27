# 构建easyswoole环境
# docker build -t easyswoole:latest .
# 启动容器
# wsh dc up
# 导出
# docker export 8074b8fbeb60 > php-easyswoole.tar

FROM centos:8

# 定义版本
ENV SWOOLE_VERSION 4.4.23
ENV EASYSWOOLE_VERSION 3.4.x

# 设置yum源
RUN yum install -y curl wget \
    && mv /etc/yum.repos.d/CentOS-Base.repo /etc/yum.repos.d/CentOS-Base.repo.bak \
    && wget -O /etc/yum.repos.d/CentOS-Base.repo https://mirrors.aliyun.com/repo/Centos-8.repo \
    && yum clean all && yum makecache

#设置epel源
RUN yum install -y epel-release \
    && mv /etc/yum.repos.d/epel.repo /etc/yum.repos.d/epel.repo.backup \
    && mv /etc/yum.repos.d/epel-testing.repo /etc/yum.repos.d/epel-testing.repo.backup \
    && yum install -y https://mirrors.aliyun.com/epel/epel-release-latest-8.noarch.rpm \
    && sed -i 's|^#baseurl=https://download.fedoraproject.org/pub|baseurl=https://mirrors.aliyun.com|' /etc/yum.repos.d/epel* \
    && sed -i 's|^metalink|#metalink|' /etc/yum.repos.d/epel* \
    && yum clean all && yum makecache

#安装依赖
RUN yum install -y curl wget zip unzip openssl-devel gcc-c++ make autoconf git

#安装PHP
RUN yum install -y php-devel php-openssl php-mbstring php-json php-xml php-mysqli php-bcmath

# 安装composer # 设置composer源
# 阿里云镜像 composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
# 中国全量镜像 composer config -g repo.packagist composer https://packagist.phpcomposer.com
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer \
    && composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/ \
    && composer self-update --clean-backups

# 安装swoole扩展
RUN wget https://github.com/swoole/swoole-src/archive/v${SWOOLE_VERSION}.tar.gz -O swoole.tar.gz \
    && mkdir -p swoole \
    && tar -xf swoole.tar.gz -C swoole --strip-components=1 \
    && rm swoole.tar.gz \
    && ( \
    cd swoole \
    && phpize \
    && ./configure --enable-openssl \
    && make \
    && make test \
    && make install \
    ) \
    && sed -i "2i extension=swoole.so" /etc/php.ini \
    && rm -r swoole

# 测试easyswoole使用
WORKDIR /easyswoole

## 安装框架
# cd /easyswoole \
#     && composer require easyswoole/easyswoole=3.4.x \
#     && php vendor/easyswoole/easyswoole/bin/easyswoole install && composer dump-autoload
## 安装框架依赖
# cd /easyswoole && \
# composer require easyswoole/swoole-ide-helper && \
# composer require easyswoole/mysqli && \
# composer require easyswoole/orm && \
# composer require easyswoole/redis && \
# composer require easyswoole/pool &&  \
# composer require easyswoole/redis-pool && \
# composer require easyswoole/fast-cache && \
# composer require easyswoole/http-client && \
# composer require easyswoole/component && \
# composer require easyswoole/hot-reload && \
# composer require easyswoole/trigger && \
# composer require easyswoole/socket && \
# composer require nette/php-generator && \
# composer require easyswoole/http-annotation && \
# composer require php-curl-class/php-curl-class && \
# composer require easyswoole/command && \
# composer require easyswoole/code-generation=2.x && \
# composer require easyswoole/task && \
# composer require easyswoole/annotation &&  \
# composer require easyswoole/phpunit && \
# composer require easyswoole/http && \
# composer require easyswoole/queue && \
# composer require easyswoole/template &&  \
# composer require easyswoole/spl && \
# composer require easyswoole/utility && \
# composer require easyswoole/pay && \
# composer require smarty/smarty && \
# composer dump-autoload
## 启动服务
# cd /easyswoole && php easyswoole server start -d -mode=dev

EXPOSE 9501