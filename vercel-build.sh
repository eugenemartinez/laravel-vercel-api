#!/bin/bash

echo "Running Laravel Vercel Build Script..."

# 1. Cache Laravel specific things
echo "Caching configuration..."
php artisan config:cache
echo "Caching routes..."
php artisan route:cache
echo "Caching views..."
php artisan view:cache
# echo "Caching events..." # Uncomment if you use event caching
# php artisan event:cache # Uncomment if you use event caching

# 2. Ensure /tmp directories exist and copy cached files
#    (Vercel's /tmp is writable at runtime, but we prepare paths during build)
#    The APP_*_CACHE variables in vercel.json will point here.
echo "Preparing /tmp directories and copying cache files..."
mkdir -p /tmp/bootstrap/cache

if [ -f bootstrap/cache/config.php ]; then
    cp bootstrap/cache/config.php /tmp/config.php
    echo "Copied config.php to /tmp/config.php"
else
    echo "bootstrap/cache/config.php not found."
fi

# Find the generated routes file (name can vary)
ROUTE_CACHE_FILE=$(find bootstrap/cache -name 'routes*.php' -print -quit || echo '')
if [ -f "$ROUTE_CACHE_FILE" ]; then
    cp "$ROUTE_CACHE_FILE" /tmp/routes.php
    echo "Copied $ROUTE_CACHE_FILE to /tmp/routes.php"
else
    echo "Routes cache file not found in bootstrap/cache."
fi

# if [ -f bootstrap/cache/events.php ]; then # Uncomment if you use event caching
#     cp bootstrap/cache/events.php /tmp/events.php # Uncomment if you use event caching
#     echo "Copied events.php to /tmp/events.php" # Uncomment if you use event caching
# else # Uncomment if you use event caching
#     echo "bootstrap/cache/events.php not found." # Uncomment if you use event caching
# fi

# For views, VIEW_COMPILED_PATH="/tmp/views" in vercel.json tells Laravel where to write at runtime.
# view:cache pre-compiles, but Laravel will still write to /tmp/views if it needs to compile on the fly.
# We just need to ensure /tmp/views is available.
mkdir -p /tmp/views
mkdir -p /tmp/framework/sessions
mkdir -p /tmp/framework/cache/data
mkdir -p /tmp/logs

echo "Build script finished."
