#!/bin/sh
set -e

# Se não existir composer.json, cria um novo projeto Laravel
if [ ! -f /var/www/composer.json ]; then
    echo "==> Nenhum projeto encontrado. Criando projeto Laravel..."
    composer create-project laravel/laravel /tmp/laravel --no-interaction --prefer-dist
    cp -r /tmp/laravel/. /var/www/
    rm -rf /tmp/laravel
    echo "==> Projeto Laravel criado com sucesso."
else
    echo "==> Projeto existente detectado. Rodando composer install..."
    cd /var/www && composer install --optimize-autoloader --no-interaction
fi

# Ajusta permissões
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true

# Gera a APP_KEY se o .env existir mas a chave estiver vazia
if [ -f /var/www/.env ] && ! grep -q "^APP_KEY=base64:" /var/www/.env; then
    echo "==> Gerando APP_KEY..."
    cd /var/www && php artisan key:generate --force
fi

exec "$@"
