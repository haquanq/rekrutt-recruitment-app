#!/bin/sh
set -e

cat > .env << EOF
APP_KEY=generated
APP_ENV=${APP_ENV}
APP_DEBUG=${APP_DEBUG}
APP_NAME=${APP_NAME}
APP_URL=${APP_URL}

DB_HOST=db
DB_PORT=5432
DB_DATABASE=recruitment_db
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}
DB_CONNECTION=pgsql

CACHE_STORE=database
EOF

php artisan key:generate

exec "$@"
