FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=Europe/Moscow

RUN apt-get update && apt-get install -y \
    software-properties-common \
    curl \
    git \
    unzip

RUN add-apt-repository ppa:ondrej/php

RUN apt-get update && apt-get install -y \
    php8.1 \
    php8.1-cli \
    php8.1-common

RUN apt-get install -y \
    php8.1-mbstring \
    php8.1-xml \
    php8.1-zip \
    php8.1-bcmath

RUN apt-get install -y \
    php8.1-gd \
    php8.1-curl \
    php8.1-sqlite3 \
    sqlite3

RUN apt-get clean

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && chmod +x /usr/local/bin/composer

RUN composer require pusher/pusher-php-server

WORKDIR /app

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80
ENTRYPOINT ["/entrypoint.sh"]