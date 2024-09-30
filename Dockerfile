# Utiliser l'image PHP avec FPM
FROM php:8.2-fpm

# Installer les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    nginx \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Copier la configuration de Nginx
COPY ./nginx.conf /etc/nginx/nginx.conf

# Créer un répertoire pour votre application
RUN mkdir -p /var/www/html

# Exposer le port 80
EXPOSE 80

# Démarrer Nginx et PHP-FPM
CMD service nginx start && php-fpm
