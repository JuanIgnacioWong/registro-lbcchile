# Deploy con GitHub en cPanel (Guia Practica)

Esta guia prepara el proyecto para desplegar desde GitHub usando **Git Version Control** de cPanel.

Ya incluido en este repo:
- `.cpanel.yml` (dispara el deploy desde cPanel)
- `scripts/cpanel_deploy.sh` (ejecuta Composer + Artisan)

## 1) Modelo de deploy recomendado

Flujo:
1. Haces `git push` a GitHub.
2. En cPanel: `Update from Remote`.
3. En cPanel: `Deploy HEAD Commit`.
4. cPanel ejecuta `.cpanel.yml` y corre `scripts/cpanel_deploy.sh`.

## 2) Preparar acceso SSH de cPanel a GitHub

En cPanel:
1. Ve a `SSH Access` y genera una llave SSH si no existe.
2. Copia la **Public Key**.

En GitHub:
1. Repositorio `Settings` -> `Deploy keys`.
2. `Add deploy key`.
3. Pega la llave publica.
4. Marca `Allow write access` solo si realmente necesitas push desde servidor.

## 3) Clonar el repo desde cPanel

En cPanel:
1. Abre `Git Version Control`.
2. Click `Create`.
3. En `Clone URL` usa URL SSH de GitHub (ejemplo: `git@github.com:ORG/REPO.git`).
4. Define ruta destino (ejemplo: `/home/USUARIO/registro-lbc`).
5. Crea el repositorio.

## 4) Configurar subdominio/document root

Configura `registro.lbcchile.com` apuntando a:
- `/home/USUARIO/registro-lbc/public`

Nunca apuntes al root del proyecto.

## 5) Configurar .env en servidor (una sola vez)

En `/home/USUARIO/registro-lbc/.env`:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://registro.lbcchile.com`
- Credenciales MySQL reales
- SMTP real

Tambien deja:
- `SESSION_DRIVER=database`
- `CACHE_STORE=database`
- `QUEUE_CONNECTION=database`

## 6) Primer deploy

Desde cPanel -> `Git Version Control` -> `Manage`:
1. `Update from Remote`
2. `Deploy HEAD Commit`

El deploy ejecuta:
- `composer install --no-dev`
- `php artisan key:generate` (solo si falta APP_KEY)
- `php artisan migrate --force`
- `php artisan storage:link`
- `php artisan optimize:clear`
- `php artisan config:cache`
- `php artisan route:cache`
- `php artisan view:cache`

## 7) Seeders en produccion (solo cuando corresponda)

El script **no** corre seeders automaticamente para no pisar configuracion productiva.

Solo en primera carga de datos base, ejecuta por terminal:

```bash
cd /home/USUARIO/registro-lbc
RUN_SEEDERS=1 /bin/bash scripts/cpanel_deploy.sh
```

Esto crea el admin inicial y catalogos base.

## 8) Deploys siguientes

Cada vez que publiques cambios:
1. `git push` a GitHub.
2. cPanel -> `Update from Remote`.
3. cPanel -> `Deploy HEAD Commit`.

## 9) Ver logs si falla

Logs utiles:
- Laravel: `storage/logs/laravel.log`
- cPanel deploy logs: `~/.cpanel/logs/` (archivos `vc_*_git_deploy.log`)

## 10) Archivos de este setup

- `.cpanel.yml` llama al script de deploy.
- `scripts/cpanel_deploy.sh` contiene todos los pasos de despliegue.

Si tu hosting usa rutas de PHP/Composer no estandar, ajusta `scripts/cpanel_deploy.sh`.
