# Deploy GitHub -> cPanel

Flujo recomendado para `clubes.lbcchile.com` con rama `main`.

## 1) Requisitos
- Repositorio: `https://github.com/JuanIgnacioWong/registro-lbcchile.git`
- Rama de deploy: `main`
- cPanel con `Git Version Control` habilitado
- Subdominio creado: `clubes.lbcchile.com`

## 2) Clonar repo en cPanel
1. cPanel -> `Git Version Control` -> `Create`.
2. Clone URL: `https://github.com/JuanIgnacioWong/registro-lbcchile.git`
3. Branch: `main`
4. Clone Path recomendado: `/home/USUARIOCPANEL/registro-lbc`

## 3) Configurar `.env` de producción
1. Copiar `.env.cpanel.example` a `.env` dentro de `/home/USUARIOCPANEL/registro-lbc`.
2. Completar credenciales reales `DB_*`, correo y `APP_KEY`.
3. Confirmar valores mínimos:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://clubes.lbcchile.com`
- `DEPLOY_PUBLIC_PATH=/home/USUARIOCPANEL/public_html/clubes.lbcchile.com`
- `RUN_NPM_BUILD=0` (o `1` si cPanel tiene Node/NPM)
- `RUN_MIGRATIONS=0` para reutilizar la base de datos existente sin alterar esquema
- `RUN_SEEDERS=0` (cambiar a `1` solo en primer deploy si aplica)

## 4) Ejecutar deploy
En cPanel -> `Git Version Control` -> `Manage`:
1. `Update from Remote`
2. `Deploy HEAD Commit`

`.cpanel.yml` ejecuta automáticamente `scripts/cpanel_deploy.sh`.

## 5) Flujo operativo desde GitHub
1. Hacer cambios en local.
2. `git push origin main`.
3. En cPanel: `Update from Remote` + `Deploy HEAD Commit`.

## 6) Verificación post deploy
- `https://clubes.lbcchile.com/inscripcion`
- `https://clubes.lbcchile.com/login`
- `https://clubes.lbcchile.com/admin`

## 7) Nota sobre CI en GitHub
Se agregó workflow en `.github/workflows/ci.yml` para validar en cada push/PR a `main`:
- `composer install`
- migraciones + tests
- `npm ci` + `npm run build`
