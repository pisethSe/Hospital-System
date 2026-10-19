#!/bin/sh
# Hospital System API container entrypoint.
# Prepares storage, waits for MySQL, migrates, caches config, starts Apache.

set -e

echo "==> preparing storage directories"
mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/framework/testing \
    storage/logs \
    storage/app/public/users

echo "==> waiting for the database"
i=0
until php -r '
try {
    new PDO(
        sprintf("mysql:host=%s;port=%s", getenv("DB_HOST") ?: "127.0.0.1", getenv("DB_PORT") ?: "3306"),
        getenv("DB_USERNAME"),
        getenv("DB_PASSWORD")
    );
    exit(0);
} catch (Throwable $e) {
    exit(1);
}' >/dev/null 2>&1; do
    i=$((i + 1))
    if [ "$i" -ge 30 ]; then
        echo "Database not reachable after 30 attempts — continuing (migrations will retry)."
        break
    fi
    echo "    database not ready yet (attempt $i/30)"
    sleep 2
done

echo "==> running migrations"
php artisan migrate --force

echo "==> seeding demo accounts (skipped automatically when data exists)"
php artisan db:seed --force

echo "==> linking public storage"
php artisan storage:link || true

echo "==> caching config, routes and views"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> fixing permissions"
chown -R www-data:www-data storage bootstrap/cache

# Render expects the service to listen on $PORT (default 10000).
LISTEN_PORT="${PORT:-80}"
echo "==> starting Apache on port $LISTEN_PORT"
sed -i "s/Listen 80/Listen ${LISTEN_PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${LISTEN_PORT}>/" /etc/apache2/sites-available/000-default.conf

exec apache2-foreground
