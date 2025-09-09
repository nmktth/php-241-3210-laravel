FROM ubuntu:22.04

# Делаем установку non-interactive и задаём часовой пояс (MSK)
ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=Europe/Moscow

# Установка необходимых пакетов (PHP 8.1 для Laravel 10, curl и другие зависимости)
RUN apt-get update && apt-get install -y \
    software-properties-common \
    && add-apt-repository ppa:ondrej/php \
    && apt-get update \
    && apt-get install -y \
        php8.1 \
        php8.1-cli \
        php8.1-common \
        php8.1-mbstring \
        php8.1-xml \
        php8.1-zip \
        php8.1-bcmath \
        php8.1-gd \
        php8.1-curl \
        unzip \
        git \
        curl \
    && apt-get clean

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && chmod +x /usr/local/bin/composer


WORKDIR /app


COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Открытие порта
EXPOSE 80

# Запуск скрипта
ENTRYPOINT ["/entrypoint.sh"]
