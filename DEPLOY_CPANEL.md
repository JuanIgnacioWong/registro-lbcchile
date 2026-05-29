# Deploy en cPanel (clubes.lbcchile.com)

Guía operativa para publicar esta app Laravel en cPanel, sin depender de WordPress.

## 1) Requisitos
- Subdominio: `clubes.lbcchile.com`
- PHP 8.3+
- MySQL/MariaDB
- Acceso a `Git Version Control` o FTP
- Terminal SSH (ideal)

## 2) Estructura recomendada
- App: `/home/USUARIO/registro-lbc`
- Public web: `/home/USUARIO/public_html/clubes.lbcchile.com`

## 3) Variables de entorno
Crear `.env` en servidor (no versionar), tomando como base `.env.cpanel.example`.

Mínimo obligatorio:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://clubes.lbcchile.com`
- `DB_*` correctos

## 4) Deploy automático (recomendado)
`.cpanel.yml` ejecuta:
```bash
chmod +x scripts/cpanel_deploy.sh
/bin/bash scripts/cpanel_deploy.sh
```

El script:
- valida `.env`
- instala dependencias PHP (`composer install --no-dev`) cuando es posible
- ejecuta `php artisan migrate --force` solo si `RUN_MIGRATIONS=1`
- opcional: `php artisan db:seed --force` si `RUN_SEEDERS=1`
- limpia/cachea (`optimize:clear`, `config:cache`, `route:cache`, `view:cache`)
- publica carpeta `public/` al document root si es necesario

Opcional frontend en servidor:
- `RUN_NPM_BUILD=1` para ejecutar `npm ci` + `npm run build` (solo si cPanel tiene Node/NPM)

Para reutilizar la base actual sin tocar esquema en primer deploy:
- `RUN_MIGRATIONS=0`
- `RUN_SEEDERS=0`

## 5) Primera verificación
1. Abrir `https://clubes.lbcchile.com/inscripcion`
2. Validar login admin en `/login`
3. Revisar módulo `/admin`
4. Probar creación de temporada/división/club
5. Probar carga de inscripción y descarga admin

## 6) Comandos útiles
```bash
php artisan optimize:clear
php artisan migrate --force
php artisan test
```
