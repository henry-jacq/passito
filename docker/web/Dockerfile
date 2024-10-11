# Use an official PHP image with Apache
FROM php:8.2-apache

# Install necessary system packages and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    bash-completion \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    nodejs \
    npm \
    sass \
    dos2unix \
    openssl \
    locales \
    net-tools \
    && echo "en_US.UTF-8 UTF-8" > /etc/locale.gen \
    && locale-gen en_US.UTF-8 \
    && /usr/sbin/update-locale LANG=en_US.UTF-8 LC_ALL=en_US.UTF-8

# Set the environment variables for the locale
ENV LANG en_US.UTF-8
ENV LC_ALL en_US.UTF-8
ENV LANGUAGE en_US:en

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install necessary PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Enable required Apache modules
RUN a2enmod headers rewrite actions expires deflate socache_shmcb ssl

# Install grunt globally
RUN npm install -g grunt-cli

# Set Apache to serve from /var/www/html/public and configure the directory
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf \
    && echo "<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
    </Directory>" >> /etc/apache2/sites-available/000-default.conf

# Copy the application code
WORKDIR /var/www/html
COPY . /var/www/html

# Set ownership and permissions for Apache
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Restart Apache to apply changes
RUN service apache2 restart