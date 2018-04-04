FROM ubuntu
MAINTAINER hong.li hong.li@geneegroup.com

RUN apt-get update \
    && apt-get install -y vim \
    && apt-get install -y php7.0-fpm \
    && sed -i 's/^listen\s*=.*$/listen = 0.0.0.0:9000/' /etc/php/7.0/fpm/pool.d/www.conf \
    && apt-get install -y php7.0-cli php7.0-mysql php7.0-curl php7.0-mcrypt php7.0-mbstring php-redis redis-server php7.0-dev libyaml-dev php7.0-gd \
    && apt-get install -y composer unzip git \
    && apt-get clean

EXPOSE 9000

ENV GINI_MODULE_BASE_PATH="/data/gini-modules"

WORKDIR /data/gini-modules
