# Utiliser l'image PHP officielle
FROM php:8.2-apache

# Installer les extensions PHP couramment utilisées
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_mysql mysqli mbstring exif pcntl bcmath gd zip

# Activer le module rewrite d'Apache
RUN a2enmod rewrite

# Définir le répertoire de travail
WORKDIR /var/www/html

# Exposer le port 80
EXPOSE 80

# Commande par défaut pour démarrer Apache en premier plan
CMD ["apache2-foreground"]