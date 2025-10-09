FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=Europe/Moscow

# СЛОЙ 1: Базовая система - редко меняется
RUN apt-get update && apt-get install -y \
    software-properties-common \
    curl \
    git \
    unzip

# СЛОЙ 2: PPA - меняется редко
RUN add-apt-repository ppa:ondrej/php

# СЛОЙ 3: Основные пакеты PHP - кэшируется отдельно
RUN apt-get update && apt-get install -y \
    php8.1 \
    php8.1-cli \
    php8.1-common

# СЛОЙ 4: Расширения PHP - группируй по частоте изменений
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

# СЛОЙ 5: Composer - отдельно
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && chmod +x /usr/local/bin/composer

WORKDIR /app

# СЛОЙ 6: entrypoint - меняется часто, но он легкий
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80
ENTRYPOINT ["/entrypoint.sh"]