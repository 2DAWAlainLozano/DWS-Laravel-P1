#!/bin/bash
# Copiamos el .env si no existe
cp -n .env.example .env

# Descargamos las dependencias usando un mini-contenedor de Docker
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs

# Levantamos el proyecto
./vendor/bin/sail up -d

# Configuramos Laravel
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate