#!/usr/bin/env bash
set -euo pipefail
command -v composer >/dev/null || { echo 'Composer 2 is required'; exit 1; }
command -v npm >/dev/null || { echo 'Node.js / npm is required'; exit 1; }
php -r 'foreach (["mbstring","pdo_sqlite"] as $ext) if (!extension_loaded($ext)) {fwrite(STDERR, "Missing PHP extension: $ext\n"); exit(1);}'
[ -f .env ] || cp .env.example .env
mkdir -p database storage/framework/{cache/data,sessions,views} storage/logs
[ -f database/database.sqlite ] || touch database/database.sqlite
composer install
if grep -q '^APP_KEY=$' .env; then php artisan key:generate --ansi; fi
php artisan migrate --force
npm install
npm run build
echo
printf 'Setup complete. Start using: php artisan serve\nFor live CSS updates use: npm run dev\n'
