FROM node:22-slim AS node-base

FROM php:8.2-fpm

# ---------------------------------------------------------------------------
# System dependencies (Nginx web server + Node.js for the Vite frontend build)
# ---------------------------------------------------------------------------
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nginx

# Give apt a few automatic retries (the mirror can be flaky) and clean the
# package lists now that all apt installs are done.
RUN echo 'Acquire::Retries "5";' > /etc/apt/apt.conf.d/80retries \
    && apt-get install -y --no-install-recommends libsqlite3-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd fileinfo

# Node.js + npm copied from the official Node image (instead of installing via
# a third-party apt repo). Faster, cached better, far fewer network failures.
COPY --from=node-base /usr/local/bin/node /usr/local/bin/node
COPY --from=node-base /usr/local/lib/node_modules /usr/local/lib/node_modules
RUN ln -sf /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm \
    && ln -sf /usr/local/lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx

# Add Composer to the image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ---------------------------------------------------------------------------
# Application files
# ---------------------------------------------------------------------------
WORKDIR /var/www
COPY . /var/www

# Install PHP dependencies (no dev tools in production images)
# Faker is brought back explicitly: the DatabaseSeeder/UserFactory need it.
RUN composer install --optimize-autoloader --no-dev --ignore-platform-reqs \
    && composer require fakerphp/faker:^1.23 --no-interaction --optimize-autoloader --ignore-platform-reqs

# Build frontend assets (Tailwind / Vite -> public/build)
RUN npm install
RUN npm run build

# Ensure writable runtime directories exist
RUN touch database/database.sqlite \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs \
    && chown -R www-data:www-data bootstrap storage database database/database.sqlite \
    && chmod -R 775 storage bootstrap/cache

# ---------------------------------------------------------------------------
# Nginx configuration (replace the Debian default vhost on port 80)
# ---------------------------------------------------------------------------
COPY docker/nginx-microblog.conf /etc/nginx/conf.d/microblog.conf
RUN rm -f /etc/nginx/sites-enabled/default

# Boot: prepare app state, then run Nginx + PHP-FPM
COPY docker/entrypoint.sh /docker/entrypoint.sh
COPY docker/rewrite-env.php /docker/rewrite-env.php
RUN chmod +x /docker/entrypoint.sh

ENTRYPOINT ["/docker/entrypoint.sh"]