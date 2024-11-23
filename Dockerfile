FROM php:8.2-cli-bullseye

# Install Composer from composer:2 image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install PHP extensions installer
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Make the installer executable
RUN chmod +x /usr/local/bin/install-php-extensions

# Install PHP extensions: mbstring, xml, json, zip, curl, tokenizer, openssl
RUN apt-get update && apt-get install -y \
    build-essential \
    locales \
    curl \
    libzip-dev \
    zip \
    unzip \
    && install-php-extensions mbstring xml json tokenizer openssl zip curl \
    && apt-get clean

COPY . /app/

WORKDIR /app

RUN composer install 