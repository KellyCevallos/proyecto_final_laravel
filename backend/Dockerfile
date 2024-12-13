FROM php:8.2-apache

# Instalar dependencias y extensiones necesarias
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

# Establecer la configuración de Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Crear los directorios de almacenamiento si no existen
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache

# Copiar los archivos del proyecto Laravel
COPY . /var/www/html/

# Establecer permisos adecuados para el proyecto Laravel
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html/storage && \
    chmod -R 755 /var/www/html/bootstrap/cache

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Configurar DocumentRoot al directorio public de Laravel
RUN sed -i 's#/var/www/html#/var/www/html/public#' /etc/apache2/sites-available/000-default.conf

# Instalar dependencias de Laravel y generar APP_KEY
RUN composer install --prefer-dist --no-dev --optimize-autoloader \
    && cp .env.example .env \
    && php artisan key:generate

# Exponer el puerto 80
EXPOSE 80

# Iniciar Apache en primer plano
CMD ["apache2-foreground"]
