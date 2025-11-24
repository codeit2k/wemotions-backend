# Use PHP 8.2 CLI image
FROM php:8.2-cli

# Set working directory
WORKDIR /var/www/html

# Install required packages
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libonig-dev libpng-dev ffmpeg zip libssl-dev \
    && docker-php-ext-install pdo_mysql

# Copy project files
COPY . .

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Install dependencies
RUN composer install --ignore-platform-reqs --no-dev --optimize-autoloader

# Create uploads directory
RUN mkdir -p public/uploads

# Start PHP built-in server
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
