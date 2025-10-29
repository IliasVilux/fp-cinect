# Stage 1 - Build Frontend (Vite)
FROM node:18 AS frontend
WORKDIR /app

# Copiar los archivos necesarios
COPY package*.json ./
COPY composer.json composer.lock ./
RUN npm install

# Instalar Composer y dependencias PHP necesarias para Ziggy
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN apt-get update && apt-get install -y git unzip libzip-dev libonig-dev && rm -rf /var/lib/apt/lists/*
RUN composer install --no-dev

# Copiar todo el código
COPY . .

# Compilar assets
RUN npm run build


# Stage 2 - Backend (Laravel + PHP + Composer)
FROM php:8.2-fpm AS backend

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libonig-dev libzip-dev zip \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copiar código de la app
COPY . .

# Copiar frontend compilado desde la etapa anterior
COPY --from=frontend /app/public/dist ./public/dist

# Instalar dependencias PHP finales
RUN composer install --no-dev --optimize-autoloader

# Limpiar caches de Laravel
RUN php artisan config:clear && \
    php artisan route:clear && \
    php artisan view:clear

# Cambia php-fpm por artisan serve para que Render detecte el puerto
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
