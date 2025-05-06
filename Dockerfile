FROM php:8.1-apache

# Enable required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Copy your project into the container
COPY . /var/www/html/

# Enable mod_rewrite for clean URLs
RUN a2enmod rewrite
