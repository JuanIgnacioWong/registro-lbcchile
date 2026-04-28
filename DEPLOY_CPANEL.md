# Manual Completo de Deploy en cPanel (Paso a Paso para Principiantes)

Este manual sirve para publicar la app **Registro LBC** en un hosting con **cPanel**, aunque nunca hayas hecho un deploy antes.

Objetivo final:
- Sitio público funcionando en `https://registro.lbcchile.com`
- Panel admin activo
- Base de datos conectada
- Archivos subidos y descargables funcionando

---

## 0) Qué tipo de proyecto es este

Este proyecto es:
- Laravel 13
- PHP requerido: **8.3 o superior**
- Base de datos MySQL/MariaDB
- Frontend compilado en `public/build` (Vite)

Importante:
- Nunca debe quedar pública la carpeta raíz del proyecto.
- El dominio/subdominio debe apuntar a la carpeta `public`.

---

## 1) Checklist previo (antes de tocar cPanel)

Ten a mano:
- Acceso a cPanel
- Dominio o subdominio (`registro.lbcchile.com`)
- Credenciales de base de datos (o permiso para crearla)
- Archivos del proyecto (zip o repositorio git)

Verifica que tu hosting tenga:
- PHP 8.3+
- Extensiones PHP comunes de Laravel (`mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`, `bcmath`)
- MySQL/MariaDB
- SSL activo

Opcional pero recomendado:
- SSH/Terminal en cPanel
- Composer disponible en servidor

---

## 2) Estructura recomendada en cPanel

Usaremos esta estructura (ejemplo):
- App completa: `/home/USUARIO/registro-lbc`
- Document root del subdominio: `/home/USUARIO/registro-lbc/public`

Si puedes elegir document root al crear el subdominio, deja configurado desde el inicio:
- Subdominio: `registro`
- Dominio: `lbcchile.com`
- Document Root: `registro-lbc/public`

---

## 3) Crear base de datos en cPanel

1. Entra a `MySQL Databases`.
2. Crea una base de datos (ejemplo: `usuariocpanel_registrolbc`).
3. Crea un usuario de DB (ejemplo: `usuariocpanel_lbcuser`).
4. Asigna contraseña segura.
5. Agrega el usuario a la base de datos con privilegios `ALL PRIVILEGES`.

Guarda estos 4 datos:
- `DB_HOST` (normalmente `localhost`)
- `DB_PORT` (normalmente `3306`)
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

---

## 4) Subir los archivos del proyecto

Puedes usar cualquiera de estas opciones:

### Opción A: Git (si tu cPanel tiene Git Version Control)
1. Clona el repositorio en `/home/USUARIO/registro-lbc`.

### Opción B: ZIP (la más común)
1. Comprime el proyecto localmente en `.zip`.
2. En `File Manager`, sube el zip al home (`/home/USUARIO/`).
3. Extrae y confirma que quedó `/home/USUARIO/registro-lbc`.

Importante:
- Deben quedar carpetas como `app`, `bootstrap`, `config`, `public`, `storage`, `vendor` (si subiste vendor), etc.

---

## 5) Configurar `.env` de producción

Dentro de `/home/USUARIO/registro-lbc`:
1. Duplica `.env.example` como `.env`.
2. Edita `.env` con estos valores mínimos:

```env
APP_NAME="Registro LBC"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://registro.lbcchile.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=TU_DB
DB_USERNAME=TU_USUARIO_DB
DB_PASSWORD=TU_PASSWORD_DB

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

FILESYSTEM_DISK=local
```

No dejes `APP_DEBUG=true` en producción.

---

## 6) Deploy con SSH/Terminal (recomendado)

Si tienes Terminal en cPanel, entra a:
`/home/USUARIO/registro-lbc`

Ejecuta:

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Sobre frontend:
- Si `public/build` ya viene en el proyecto, no necesitas Node en servidor.
- Si no viene, compila localmente (`npm install && npm run build`) y sube la carpeta `public/build`.

---

## 7) Deploy sin SSH (cuando cPanel no trae Terminal)

Si no tienes SSH:

1. En tu computador local (donde sí puedes usar terminal), prepara el proyecto:

```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

2. Asegúrate de incluir al subir:
- `vendor/`
- `public/build/`

3. Sube todo a cPanel y extrae en `/home/USUARIO/registro-lbc`.
4. Crea `.env` manualmente desde `File Manager`.
5. Importa base de datos con `phpMyAdmin`:
- Usa `Export` desde local y `Import` en producción.

Nota:
- Sin SSH, comandos como `php artisan migrate` son más difíciles.
- Si tu cPanel trae interfaz para Artisan o terminal web, úsala para `key:generate`, `migrate`, `db:seed`, `storage:link`.

---

## 8) Permisos de carpetas

Laravel necesita escritura en:
- `storage/`
- `bootstrap/cache/`

En cPanel:
- Directorios: `755`
- Archivos: `644`

Si aparece error de permisos, valida propietario/grupo desde soporte del hosting.

---

## 9) Configurar dominio y SSL

1. Crea el subdominio `registro.lbcchile.com`.
2. Verifica document root apuntando a `registro-lbc/public`.
3. Activa SSL (AutoSSL o Let’s Encrypt del proveedor).
4. Fuerza HTTPS.

En producción, `APP_URL` debe ser exactamente:
`https://registro.lbcchile.com`

---

## 10) Primer ingreso al panel admin

Después de correr seeds, se crea:
- Email: `admin@lbcchile.com`
- Password: `Admin12345!`

Al ingresar por primera vez:
1. Entra al panel.
2. Cambia contraseña inmediatamente.
3. Revisa configuración de plataforma (`admin/configuracion`).

---

## 11) Pruebas básicas después del deploy

Haz estas validaciones:
1. Abre la portada pública (`/inscripcion`).
2. Envía una inscripción de prueba.
3. Inicia sesión con admin.
4. Revisa listado de antecedentes en admin.
5. Prueba descarga de archivos desde admin.
6. Verifica que no aparecen errores 500.

---

## 12) Errores típicos y solución rápida

### Error 500 al abrir sitio
- Revisa `storage/logs/laravel.log`.
- Verifica `.env` y credenciales DB.
- Revisa permisos en `storage` y `bootstrap/cache`.

### `No application encryption key has been specified`
- Falta ejecutar `php artisan key:generate` o falta `APP_KEY` en `.env`.

### Error de `Class not found` o `vendor` faltante
- Ejecuta `composer install --no-dev --optimize-autoloader`.
- Si no tienes SSH, sube carpeta `vendor` ya generada localmente.

### Archivos no cargan / no descargan
- Ejecuta `php artisan storage:link`.
- Revisa permisos en `storage/app` y `public/storage`.

### Página sin estilos (CSS roto)
- Falta carpeta `public/build`.
- Compila con `npm run build` y súbela.

---

## 13) Mantenimiento y buenas prácticas

En cada actualización:
1. Respaldar base de datos.
2. Respaldar `storage/app/private`.
3. Subir cambios de código.
4. Ejecutar migraciones nuevas (`php artisan migrate --force`).
5. Limpiar/cachear configuración:

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Seguridad mínima:
- `APP_DEBUG=false`
- Contraseñas fuertes
- SSL siempre activo
- No exponer `.env`

---

## 14) Comandos de referencia (copiar/pegar)

```bash
cd /home/USUARIO/registro-lbc
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 15) Resumen corto del flujo ideal

1. Crear subdominio apuntando a `registro-lbc/public`.
2. Crear DB y usuario.
3. Subir proyecto.
4. Configurar `.env`.
5. Ejecutar comandos Artisan/Composer.
6. Probar sitio público + admin.
7. Cambiar contraseña admin inicial.

---

## 16) Si vas a desplegar desde GitHub

Para flujo GitHub -> cPanel con `Git Version Control` (pull + deploy), revisa:
- `DEPLOY_GITHUB_CPANEL.md`

Ese flujo usa:
- `.cpanel.yml`
- `scripts/cpanel_deploy.sh`
