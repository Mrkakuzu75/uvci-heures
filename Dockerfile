FROM php:8.2-fpm

RUN apt-get update && apt-get install -y nginx curl unzip sqlite3 libsqlite3-dev
RUN docker-php-ext-install pdo pdo_sqlite

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html
COPY . .

# Créer le fichier .env avec la bonne configuration de session
RUN echo "APP_ENV=production" > .env && \
    echo "APP_DEBUG=false" >> .env && \
    echo "APP_KEY=base64:l2jEYpAmPohXyobBZucU/GEpPI5kqNvd00lgSS3Cm0Q=" >> .env && \
    echo "APP_URL=https://uvci-heures.onrender.com" >> .env && \
    echo "DB_CONNECTION=sqlite" >> .env && \
    echo "LOG_CHANNEL=stderr" >> .env && \
    echo "CACHE_DRIVER=array" >> .env && \
    echo "SESSION_DRIVER=database" >> .env && \
    echo "SESSION_DOMAIN=.onrender.com" >> .env && \
    echo "TRUSTED_PROXIES=*" >> .env

# Créer le fichier de base de données SQLite
RUN touch database/database.sqlite && \
    chmod 666 database/database.sqlite

RUN composer install --optimize-autoloader --no-dev

# Nettoyer et recréer le cache
RUN php artisan key:generate --force
RUN php artisan config:clear
RUN php artisan config:cache
# RUN php artisan route:cache  (commenté pour éviter l'erreur de route)
RUN php artisan view:cache

# Créer la table des sessions si elle n'existe pas
RUN php artisan migrate --force

CMD php artisan serve --host=0.0.0.0 --port=10000
