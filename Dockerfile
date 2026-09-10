FROM php:8.3-apache-bookworm

RUN docker-php-ext-install pdo_sqlite

COPY . /var/www/html/

RUN mkdir -p /var/www/html/daten \
	&& chown -R www-data:www-data /var/www/html/daten \
	&& chmod 775 /var/www/html/daten
