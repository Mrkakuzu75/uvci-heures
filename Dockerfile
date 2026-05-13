FROM php:8.2-fpm

RUN apt-get update && apt-get install -y nginx curl unzip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html
COPY . .

# Créer le fichier .env
RUN echo "APP_ENV=production" > .env && \
    echo "APP_DEBUG=true" >> .env && \
    echo "APP_KEY=base64:l2jEYpAmPohXyobBZucU/GEpPI5kqNvd00lgSS3Cm0Q=" >> .env && \
    echo "APP_URL=https://uvci-heures.onrender.com" >> .env && \
    echo "DB_CONNECTION=sqlite" >> .env && \
    echo "LOG_CHANNEL=stderr" >> .env && \
    echo "CACHE_DRIVER=array" >> .env && \
    echo "SESSION_DRIVER=array" >> .env

RUN composer install --optimize-autoloader --no-dev

# Cache Laravel
RUN php artisan key:generate --force
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

CMD php artisan serve --host=0.0.0.0 --port=10000
