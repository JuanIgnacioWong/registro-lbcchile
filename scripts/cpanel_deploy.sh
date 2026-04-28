#!/usr/bin/env bash
set -euo pipefail

APP_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$APP_ROOT"

echo "[deploy] Iniciando deploy Laravel en cPanel"
echo "[deploy] APP_ROOT=$APP_ROOT"
echo "[deploy] Fecha: $(date '+%Y-%m-%d %H:%M:%S %z')"

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
elif [ -x "$APP_ROOT/composer.phar" ]; then
  COMPOSER_CMD=("$PHP_BIN" "$APP_ROOT/composer.phar")
else
  COMPOSER_CMD=()
fi

if [ ! -f .env ]; then
  echo "[deploy][warn] No existe .env, se crea desde .env.example"
  cp .env.example .env
  echo "[deploy][error] Debes editar .env en cPanel con datos reales de producción y volver a deploy."
  exit 1
fi

required_env_keys=(
  APP_ENV
  APP_URL
  DB_CONNECTION
  DB_HOST
  DB_PORT
  DB_DATABASE
  DB_USERNAME
  DB_PASSWORD
)

for key in "${required_env_keys[@]}"; do
  if ! grep -q "^${key}=" .env; then
    echo "[deploy][error] Falta ${key} en .env"
    exit 1
  fi
done

if grep -q '^APP_ENV=local' .env; then
  echo "[deploy][error] APP_ENV=local detectado. En producción debe ser APP_ENV=production."
  exit 1
fi

if grep -q '^APP_DEBUG=true' .env; then
  echo "[deploy][error] APP_DEBUG=true detectado. En producción debe ser APP_DEBUG=false."
  exit 1
fi

if [ "${#COMPOSER_CMD[@]}" -gt 0 ]; then
  echo "[deploy] Instalando dependencias PHP"
  "${COMPOSER_CMD[@]}" install --no-dev --optimize-autoloader --no-interaction --prefer-dist
else
  echo "[deploy][warn] Composer no disponible en servidor. Se usará vendor precompilado."
  if [ ! -f "$APP_ROOT/vendor/autoload.php" ]; then
    echo "[deploy][error] No existe vendor/autoload.php."
    echo "[deploy][hint] Sube la carpeta vendor/ al repositorio o al servidor y vuelve a desplegar."
    exit 1
  fi
fi

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
