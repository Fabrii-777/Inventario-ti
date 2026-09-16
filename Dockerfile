# Usa una imagen oficial y ligera de PHP con servidor Apache
FROM php:8.2-apache

# Instala el soporte de PostgreSQL (PDO PGSQL) necesario para Supabase
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copia los archivos de tu repositorio adentro del servidor web
COPY . /var/www/html/

# Abre el puerto web estándar
EXPOSE 80
