#!/bin/sh
set -e

echo "Vidage des caches de config (au cas où une ancienne image aurait laissé un cache invalide)..."
php artisan config:clear

echo "Lien de stockage public..."
php artisan storage:link || true

echo "Migrations..."
php artisan migrate --force

echo "Mise en cache config/routes/vues..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Démarrage du serveur sur le port ${PORT:-8080}..."
exec php artisan serve --host 0.0.0.0 --port "${PORT:-8080}"
