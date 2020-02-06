FROM php:7.3-fpm

RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        supervisor \
        nginx \
        libcurl4-gnutls-dev \
        libsqlite3-0 \
        curl && \
    rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    pdo \
    curl \
    json

COPY ./docker/api/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY ./docker/api/nginx.conf /etc/nginx/nginx.conf
COPY . /opt

EXPOSE 80
WORKDIR /opt
CMD ["/usr/bin/supervisord"]
