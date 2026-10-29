FROM php:8.3-apache
WORKDIR /var/www/html
COPY src/ ./src/
COPY index.php health.php ./
EXPOSE 80
