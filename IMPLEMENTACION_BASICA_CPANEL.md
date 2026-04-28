# Implementacion Basica Completa (cPanel + GitHub)

Este documento es el runbook operativo minimo para publicar y mantener la app en cPanel.

## 1) Una sola vez (setup inicial)

1. Crear subdominio `registro.lbcchile.com`.
2. Document root: `/home/USUARIO/registro-lbc/public`.
3. Crear DB y usuario con permisos totales.
4. En `Git Version Control`, crear repo con URL:
   - Publico: `https://github.com/ORG/REPO.git`
   - Privado: `https://x-access-token:TOKEN@github.com/ORG/REPO.git`
5. Confirmar que existe `.cpanel.yml` en el root del repo.
6. Crear `.env` en servidor usando `.env.cpanel.example` como base.

## 2) Primer despliegue

1. En cPanel -> `Git Version Control` -> `Manage`.
2. Click `Update from Remote`.
3. Click `Deploy HEAD Commit`.
4. Revisar logs de deploy.
5. Si es primer deploy y necesitas datos base, ejecutar seeders con `RUN_SEEDERS=1`.

## 3) Despliegues normales (cada cambio)

1. `git push` a `main` en GitHub.
2. En cPanel: `Update from Remote`.
3. En cPanel: `Deploy HEAD Commit`.
4. Validar `/inscripcion` y login admin.

## 4) Que hace automaticamente el deploy

El script `scripts/cpanel_deploy.sh`:
- valida que `.env` exista y no este en modo local/debug
- instala dependencias PHP sin dev
- genera `APP_KEY` si falta
- corre migraciones
- recrea caches Laravel
- asegura `storage:link`

## 5) Cuando falla el deploy

1. Revisar `storage/logs/laravel.log`.
2. Revisar log de cPanel (`vc_*_git_deploy.log`).
3. Verificar `.env`:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - DB_* correctos
4. Reintentar deploy desde `Manage`.

## 6) Seguridad minima obligatoria

- No subir `.env` a GitHub.
- Token HTTPS solo lectura y con expiracion.
- Rotar token si se expone.
- Cambiar password del admin inicial tras primer login.
