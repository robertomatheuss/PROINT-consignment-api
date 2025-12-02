FROM php:8.2-fpm

# Instala dependências de sistema
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql zip

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copia o código do projeto
COPY . .

# Instala dependências do Laravel
RUN composer install --no-interaction --prefer-dist

# Garante permissões básicas de storage/bootstrap
RUN mkdir -p storage bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
