FROM php:8.2-apache

RUN docker-php-ext-install mysqli

# Copy semua file
COPY . /var/www/html/

# Ubah DocumentRoot Apache ke folder main/
RUN echo "DocumentRoot /var/www/html/main" > /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html/main

EXPOSE 80

CMD ["apache2-foreground"]
