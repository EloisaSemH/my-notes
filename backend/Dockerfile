FROM php:8.2-apache

# Instalar extensões necessárias
RUN apt-get update && apt-get install -y \
    libpq-dev unzip git && \
    docker-php-ext-install pdo pdo_pgsql

# Habilitar o mod_rewrite do Apache
RUN a2enmod rewrite

# Definir a pasta correta como raiz do site (DocumentRoot)
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Copiar código
COPY . /var/www/html

# Instalar Composer (se ainda não tiver)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html