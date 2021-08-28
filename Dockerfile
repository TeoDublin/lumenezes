FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

WORKDIR /app
COPY composer.json .
COPY . .

CMD php artisan serve --host=0.0.0.0 --port 80