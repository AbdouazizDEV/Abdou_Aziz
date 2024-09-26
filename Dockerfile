# Utiliser une image officielle de PHP avec FPM (FastCGI Process Manager)
FROM php:8.3.6-fpm

# Installer les dépendances du système et extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# Définir le répertoire de travail
WORKDIR /var/www

# Copier le contenu de l'application dans le conteneur
COPY . .

RUN chown -R www-data:www-data /var/www
# Installer les dépendances PHP avec Composer
RUN composer install --optimize-autoloader --no-dev

# Créer les répertoires nécessaires et donner les permissions adéquates
RUN mkdir -p /var/www/html/Gestion_Apprenant_laravel /var/www/html/Gestion_Apprenant_laravel/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/Gestion_Apprenant_laravel /var/www/html/Gestion_Apprenant_laravel/bootstrap/cache \
    && chmod -R 777 /var/www/html/Gestion_Apprenant_laravel /var/www/html/Gestion_Apprenant_laravel/bootstrap/cache

# Exposer le port utilisé par PHP-FPM
EXPOSE 9000

# Copier et exécuter le script de démarrage
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Lancer le script de démarrage lorsque le conteneur démarre
CMD ["sh", "/usr/local/bin/start.sh"]
