FROM php:8.2-apache

# Librería de sistema que necesita mbstring para compilar (oniguruma)
RUN apt-get update && apt-get install -y --no-install-recommends libonig-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mysqli mbstring

# Habilitar mod_rewrite por si luego lo necesitas para el rediseño
RUN a2enmod rewrite

# Composer (para phpmailer)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# El DocumentRoot apunta a la raíz del proyecto, que se monta como volumen
WORKDIR /var/www/html

# Permisos: el usuario www-data debe poder leer/escribir si hace falta
RUN chown -R www-data:www-data /var/www/html