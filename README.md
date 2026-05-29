# Plataforma Clubes LBC Chile

Plataforma independiente para `https://clubes.lbcchile.com` orientada a recepción, revisión y administración de antecedentes deportivos de clubes.

## Stack
- Laravel 12
- PHP 8.3+
- MySQL/MariaDB
- Blade + TailwindCSS + Alpine.js
- Autenticación Blade (Breeze)

## Módulos principales
- Público:
  - `GET /inscripcion`
  - `GET|POST /correcciones/{year}/{division}/{club}/{token}`
- Admin:
  - `GET /admin`
  - temporadas, divisiones, clubes, antecedentes, pagos, correcciones, historial, configuración, usuarios

## Reglas clave
- Historial por versiones (`submission_versions`) sin reemplazo automático de archivos previos.
- Cupo base por club/temporada/división: 2 envíos; ampliable por admin hasta 4.
- Enlaces de corrección con token seguro, activación/desactivación y expiración.
- Archivos en storage privado (`storage/app/private`) y descargas protegidas por controlador.

## Desarrollo local
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
npm install
npm run dev
```

## Build producción
```bash
npm run build
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## QA mínimo
```bash
php artisan test
php artisan route:list
```

## Deploy cPanel
- Script principal: `scripts/cpanel_deploy.sh`
- Trigger cPanel Git: `.cpanel.yml`
- Referencias:
  - `DEPLOY_CPANEL.md`
  - `DEPLOY_GITHUB_CPANEL.md`
  - `DEPLOY_FTP_CLUBES.md`
