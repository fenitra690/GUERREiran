FROM php:8.2-apache

# Installation des dépendances pour SQLite, la librairie d'images GD et autres
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    sqlite3 \
    libsqlite3-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Configuration et installation des extensions PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_sqlite

# Activation du module de réécriture d'URL (mod_rewrite) indispensable pour le projet
RUN a2enmod rewrite
# Activation de mod_headers et mod_deflate pour les optimisations SEO/Performance
RUN a2enmod headers deflate 

# Définition du répertoire de travail (l'application semble utiliser le chemin /rewriting3311/)
WORKDIR /var/www/html/rewriting3311

# Copier les fichiers du projet dans le conteneur
COPY . /var/www/html/rewriting3311/

# Assurer que le serveur web a les permissions d'écriture pour SQLite, le cache et les images
RUN chown -R www-data:www-data /var/www/html/rewriting3311 \
    && chmod -R 775 /var/www/html/rewriting3311

