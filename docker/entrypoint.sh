#!/bin/sh

set -e

APP_DIR="/var/www"
cd "$APP_DIR"

# ------------------------------------------------------------------
# 1. Ensure a .env is present and has an application key
# ------------------------------------------------------------------
if [ ! -f ".env" ]; then
    cp .env.example .env
fi

# Ensure an APP_KEY exists (required for sessions/cookies)
if ! grep -q "^APP_KEY=.\{20,\}" .env 2>/dev/null; then
    echo "APP_KEY not set - generating a new one..."
    php artisan key:generate --force
fi
# Push the container's real environment (DB_HOST=db, APP_URL, ...) into .env.
# Matters because FPM workers may not inherit env vars, and it lets dotenv
# turn literals like APP_DEBUG=false into real booleans.
php docker/rewrite-env.php /var/www/.env


# ------------------------------------------------------------------
# 2. Storage + runtime caches
# ------------------------------------------------------------------
mkdir -p storage/app storage/framework/cache storage/framework/sessions storage/framework/views storage/logs
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Public storage symlink (public/storage -> storage/app/public)
if [ ! -d "public/storage" ]; then
    php artisan storage:link
fi

# Compile the configuration, routes, and views (idempotent)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ------------------------------------------------------------------
# 3. Run database migrations
#    MySQL may still be booting; retry for up to ~60 seconds.
# ------------------------------------------------------------------
echo "Waiting for the database to become available..."
attempt=0
while [ "$attempt" -lt 30 ]; do
    if php artisan migrate --force 2>/dev/null; then
        break
    fi
    attempt=$((attempt + 1))
    sleep 2
done

# Seed the database ONLY if it is empty (e.g. first boot). This prevents the
# demo "Test User" from being injected into migrated/existing data.
# (A plain php -r bootstrap is used instead of `tinker --execute`, whose exit()
#  throws a Pesysh BreakException that returns a misleading exit code.)
if php -r '
    require "vendor/autoload.php";
    $app = require "bootstrap/app.php";
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    exit(App\Models\User::query()->exists() ? 0 : 1);
' >/dev/null 2>&1; then
    echo "Users already exist - skipping seeder."
else
    php artisan db:seed --force || echo "db:seed skipped (nothing to seed or error above)"
fi

# ------------------------------------------------------------------
# 4. Start services (nginx in background, PHP-FPM in foreground)
# ------------------------------------------------------------------
echo "Starting Nginx + PHP-FPM..."
nginx
# Explicitly launch PHP-FPM through the image's official entrypoint wrapper and
# stay in the foreground as PID 1 (captures signals, keeps the container alive).
exec docker-php-entrypoint php-fpm