#!/usr/bin/env bash
set -euo pipefail

APP_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$APP_ROOT"

echo "[deploy] Iniciando deploy Laravel en cPanel"
echo "[deploy] APP_ROOT=$APP_ROOT"

PHP_BIN="${PHP_BIN:-php}"
if ! command -v "$PHP_BIN" >/dev/null 2>&1; then
  echo "[deploy][error] No se encontró PHP en PATH (PHP_BIN=$PHP_BIN)."
  exit 1
fi

if command -v composer >/dev/null 2>&1; then
  COMPOSER_CMD=(composer)
elif [ -x "/opt/cpanel/composer/bin/composer" ]; then
  COMPOSER_CMD=(/opt/cpanel/composer/bin/composer)
elif [ -x "$HOME/composer.phar" ]; then
  COMPOSER_CMD=("$PHP_BIN" "$HOME/composer.phar")
else
  echo "[deploy][error] Composer no está disponible."
  echo "[deploy][hint] Instala Composer o define una ruta válida en el script."
  exit 1
fi

if [ ! -f .env ]; then
  echo "[deploy] No existe .env, se crea desde .env.example"
  cp .env.example .env
fi

echo "[deploy] Instalando dependencias PHP"
"${COMPOSER_CMD[@]}" install --no-dev --optimize-autoloader --no-interaction --prefer-dist

if ! grep -q '^APP_KEY=base64:' .env; then
  echo "[deploy] APP_KEY ausente, generando clave"
  "$PHP_BIN" artisan key:generate --force
fi

echo "[deploy] Ejecutando migraciones"
"$PHP_BIN" artisan migrate --force

if [ "${RUN_SEEDERS:-0}" = "1" ]; then
  echo "[deploy] Ejecutando seeders (RUN_SEEDERS=1)"
  "$PHP_BIN" artisan db:seed --force
fi

echo "[deploy] Asegurando symlink de storage"
"$PHP_BIN" artisan storage:link || true

echo "[deploy] Limpiando y recacheando"
"$PHP_BIN" artisan optimize:clear
"$PHP_BIN" artisan config:cache
"$PHP_BIN" artisan route:cache
"$PHP_BIN" artisan view:cache

echo "[deploy] Deploy completado"
