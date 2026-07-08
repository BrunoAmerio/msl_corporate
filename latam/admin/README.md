# Admin de Tarifarios

URL (producción): `https://<dominio>/latam/admin/`

## Setup

1. Copiar `.env.example` a `.env` en la raíz del repo.
2. Generar hash: `php latam/admin/tools/generate-password-hash.php 'PASSWORD'`
3. Completar `ADMIN_USER`, `ADMIN_PASSWORD_HASH` y variables FTP.
4. `FTP_REMOTE_PATH` debe ser la carpeta remota equivalente a `/tarifarios`.

## Seguridad

- No versionar `.env`.
- No linkear el admin desde el sitio público.
- Extensión FTP de PHP debe estar habilitada en Hostinger.
- Los directorios `src/`, `tools/` y `tests/` no son accesibles vía HTTP (bloqueados con `.htaccess` en Apache/Hostinger). Solo deben cargarse desde PHP con `require`.
