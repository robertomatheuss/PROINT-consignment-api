FROM php:8.2-fpm

# Instala dependências de sistema
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \   
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensões PHP
RUN docker-php-ext-install pdo_pgsql pdo_mysql mbstring exif pcntl bcmath gd zip

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Criar diretório de logs
RUN mkdir -p /var/log/php-fpm/

# Copiar configurações
COPY ./docker/nginx/default.conf /etc/nginx/sites-available/default
COPY ./docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /var/www/html

# Copia o código do projeto
COPY . .

# Instala dependências do Laravel
RUN composer install --no-interaction --prefer-dist

# Garante permissões básicas de storage/bootstrap
RUN mkdir -p storage bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# Redirecionar logs do Nginx para stdout/stderr
RUN ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

# Instalar dependências do Laravel
RUN composer install --optimize-autoloader --no-dev

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
