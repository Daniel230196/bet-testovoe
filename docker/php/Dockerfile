FROM php:8.1-fpm

RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    git \
    ssh \
    && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*


ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions @composer gd memcache zip pdo_mysql opcache xdebug sockets soap sysvsem bcmath

#DEV ONLY
COPY docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d
#END DEV ONLY


COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /app

ENTRYPOINT entrypoint.sh
