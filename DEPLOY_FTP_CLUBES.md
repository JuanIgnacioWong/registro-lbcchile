# Deploy por FTP para `clubes.lbcchile.com`

Guia practica para publicar este Laravel en un subdominio nuevo usando FTP (sin Git en cPanel).

## 1) Preparar el paquete local

Desde el root del proyecto:

```bash
cd /Users/ignaciowong/Documents/LBC-Documentos/registro-lbc
composer install --no-dev --optimize-autoloader
npm ci
npm run build
```

Esto deja listo:
- `vendor/` (dependencias PHP)
- `public/build/` (assets frontend)

## 2) Crear estructura en hosting

En cPanel crea:
- Subdominio: `clubes.lbcchile.com`
- Document root: `/home/USUARIO/public_html/clubes.lbcchile.com`

Sube el proyecto por FTP a una carpeta privada (fuera del docroot), por ejemplo:
- `/home/USUARIO/registro-lbc`

Importante:
- La carpeta completa Laravel debe quedar fuera de `public_html` si es posible.
- Solo el contenido de `public/` debe quedar expuesto al navegador.

## 3) Configurar `.env` de produccion

En `/home/USUARIO/registro-lbc/.env`:

```env
APP_NAME="Registro LBC"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://clubes.lbcchile.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=TU_DB
DB_USERNAME=TU_USUARIO
DB_PASSWORD=TU_PASSWORD
```

Si no existe `.env`, copia `.env.example` y luego edita.

## 4) Publicar carpeta web en el subdominio

Sube el contenido de `public/` a:
- `/home/USUARIO/public_html/clubes.lbcchile.com`

Luego reemplaza el `index.php` del subdominio para que cargue la app desde la carpeta privada:

```php
<?php

define('LARAVEL_START', microtime(true));

require '/home/USUARIO/registro-lbc/vendor/autoload.php';

$app = require_once '/home/USUARIO/registro-lbc/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
```

## 5) Ejecutar tareas Laravel (si tienes Terminal SSH)

```bash
cd /home/USUARIO/registro-lbc
/bin/bash scripts/cpanel_deploy.sh
```

Si necesitas sembrar datos iniciales:

```bash
cd /home/USUARIO/registro-lbc
RUN_SEEDERS=1 /bin/bash scripts/cpanel_deploy.sh
```

## 6) Si no tienes SSH

Debes pedir a soporte del hosting (o usar alguna interfaz Artisan del panel) para ejecutar:
- `php artisan key:generate --force`
- `php artisan migrate --force`
- `php artisan storage:link`
- `php artisan optimize:clear`
- `php artisan config:cache`

## 7) Checklist post deploy

1. Abrir `https://clubes.lbcchile.com/inscripcion`.
2. Probar login admin.
3. Confirmar que subidas de archivos funcionan.
4. Revisar `storage/logs/laravel.log` si aparece error 500.

## 8) Notas de seguridad

- Nunca subir `.env` al repositorio.
- Mantener `APP_DEBUG=false` en produccion.
- Rotar password de admin inicial al primer ingreso.
