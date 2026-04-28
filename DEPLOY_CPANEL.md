# Deploy en cPanel (`registro.lbcchile.com`)

## 1) Requisitos servidor
- PHP 8.2+
- MySQL o MariaDB
- Composer disponible
- SSL activo

## 2) Clonar repo
```bash
git clone <REPO_URL> registro-lbc
cd registro-lbc
```

## 3) Configurar entorno
```bash
cp .env.example .env
php artisan key:generate
```

Configurar en `.env`:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://registro.lbcchile.com`
- credenciales DB
- mail SMTP

## 4) Instalar dependencias backend
```bash
composer install --no-dev --optimize-autoloader
```

## 5) Migrar y seed inicial
```bash
php artisan migrate --force
php artisan db:seed --force
```

## 6) Frontend build
El proyecto ya está preparado para build con Node moderno. Si en cPanel no tienes Node compatible, compila localmente y sube `public/build`.

Build local:
```bash
npm install
npm run build
```

## 7) Permisos y cache
```bash
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Asegurar escritura en:
- `storage/`
- `bootstrap/cache/`

## 8) Usuario admin inicial
Seeder crea:
- Email: `admin@lbcchile.com`
- Password: `Admin12345!`

Cambiar contraseña inmediatamente al primer ingreso.

## 9) Seguridad
- No exponer `.env`
- Mantener `APP_DEBUG=false`
- Forzar HTTPS en el subdominio
- Respaldos periódicos de DB y `storage/app/private`
