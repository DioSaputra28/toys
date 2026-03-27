#!/usr/bin/env bash
set -euo pipefail

APP_DIR="/var/www/html"

mkdir -p \
    "${APP_DIR}/storage/app" \
    "${APP_DIR}/storage/framework/cache" \
    "${APP_DIR}/storage/framework/sessions" \
    "${APP_DIR}/storage/framework/views" \
    "${APP_DIR}/storage/logs" \
    "${APP_DIR}/bootstrap/cache"

chown -R www-data:www-data "${APP_DIR}/storage" "${APP_DIR}/bootstrap/cache"

if [ ! -L "${APP_DIR}/public/storage" ]; then
    /usr/local/bin/php "${APP_DIR}/artisan" storage:link || true
fi

exec "$@"
