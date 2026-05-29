# Runbook Básico cPanel

## Objetivo
Publicar y mantener la plataforma de clubes en `clubes.lbcchile.com`.

## Pasos mínimos
1. Crear subdominio `clubes.lbcchile.com`.
2. Crear base de datos y usuario MySQL.
3. Clonar repo en `/home/USUARIO/registro-lbc`.
4. Configurar `.env` de producción.
5. Ejecutar deploy (`Deploy HEAD Commit` o script manual).

## Script de deploy
```bash
/bin/bash scripts/cpanel_deploy.sh
```

## Qué hace
- valida entorno
- instala dependencias PHP si Composer está disponible
- corre migraciones
- aplica cachés Laravel
- sincroniza `public/` con document root

## Primer arranque
Si necesitas datos base:
```bash
RUN_SEEDERS=1 /bin/bash scripts/cpanel_deploy.sh
```

## Verificaciones
- `https://clubes.lbcchile.com/inscripcion`
- Login admin y navegación `/admin`
- Subida de antecedentes + revisión desde panel
