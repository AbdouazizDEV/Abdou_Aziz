FROM php:8.2-fpm

# Installer les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libpq-dev

# Copier le code source dans le conteneur
COPY . /var/www/html

# Changer les permissions du répertoire
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Changer le répertoire de travail
WORKDIR /var/www/html

# Installer les dépendances PHP avec Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Créer un répertoire pour votre application
RUN mkdir -p /var/www/html

# Exposer le port 80
EXPOSE 80

# Démarrer Nginx et PHP-FPM
CMD service nginx start && php-fpm
