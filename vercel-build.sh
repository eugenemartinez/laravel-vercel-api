#!/bin/bash
# Exit immediately if a command exits with a non-zero status.
set -e

echo "vercel-build.sh: Starting Vercel build process..."

echo "vercel-build.sh: Building Vue.js frontend..."
# Check if the frontend directory exists before trying to build
if [ -d "frontend" ]; then
  cd frontend
  echo "vercel-build.sh: Installing frontend dependencies..."
  npm install
  echo "vercel-build.sh: Running frontend build..."
  npm run build
  cd .. # Go back to the project root
  echo "vercel-build.sh: Frontend build complete. Output in frontend/dist/"
else
  echo "vercel-build.sh: 'frontend' directory not found. Skipping Vue build."
fi

echo "vercel-build.sh: Caching Laravel backend..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache # Add any other caching commands you need
echo "vercel-build.sh: Laravel caching complete."

echo "vercel-build.sh: Build script finished."
