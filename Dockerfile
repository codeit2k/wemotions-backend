FROM php:8.2-cli
RUN apt-get update && apt-get install -y git unzip libzip-dev libonig-dev libpng-dev ffmpeg zip     && docker-php-ext-install pdo_mysql
WORKDIR /var/www/html
COPY . .
CMD php -S 0.0.0.0:8000 -t public
