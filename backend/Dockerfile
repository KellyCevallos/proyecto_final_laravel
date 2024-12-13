# Usamos la imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instalamos dependencias necesarias para Laravel
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libpq-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zlib1g-dev \
    libbz2-dev \
    libcurl4-openssl-dev \
    openssl \
    curl \
    && docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd dom \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configuramos Apache para aceptar URL amigables de Laravel
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Instalamos Composer para la gestión de dependencias de PHP
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecemos el directorio de trabajo en /var/www/html
WORKDIR /var/www/html

# Copiamos todos los archivos de tu proyecto en el contenedor
COPY . .

# Establecemos permisos adecuados para los directorios de Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Habilitamos mod_rewrite para permitir las URL amigables en Laravel
RUN a2enmod rewrite

# Cambiamos el DocumentRoot para que apunte al directorio public de Laravel
RUN sed -i 's#/var/www/html#/var/www/html/public#' /etc/apache2/sites-available/000-default.conf

# Instalamos las dependencias de Laravel y generamos el APP_KEY
RUN composer install --prefer-dist --no-dev --optimize-autoloader \
    && cp .env.example .env \
    && php artisan key:generate

# Exponemos el puerto 80 para que Apache sirva la aplicación
EXPOSE 80

# Comando por defecto para iniciar Apache en primer plano
CMD ["apache2-foreground"]