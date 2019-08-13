FROM php:7.0-apache

RUN apt-get update && \
    apt-get clean
RUN docker-php-ext-install mysqli

COPY index.php /var/www/html/
COPY action_page.php /var/www/html/
COPY config.php /var/www/html/
COPY refresh_dttm.php /var/www/html/
