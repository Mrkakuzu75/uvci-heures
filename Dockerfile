FROM php:8.2-apache

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev libzip-dev unzip git curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier tous les fichiers du projet
COPY . .

# Configurer Apache pour pointer vers le dossier public
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && a2enmod rewrite \
    && cp /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/000-default.conf.bak \
    && sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|/var/www/html|/var/www/html/public|' /etc/apache2/conf-available/docker-php.conf \
    && composer install --no-dev --optimize-autoloader

# Installer Node.js et compiler les assets (Tailwind, Alpine)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install \
    && npm run build

EXPOSE 80
