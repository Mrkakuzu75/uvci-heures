FROM php:8.2-fpm

RUN apt-get update && apt-get install -y nginx curl unzip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html
COPY . .

RUN composer install --optimize-autoloader --no-dev

RUN php artisan key:generate --force

CMD php artisan serve --host=0.0.0.0 --port=10000
