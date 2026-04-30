# CRM de Juegos (Laravel + Inertia + React) A

Aplicacion CRM para gestionar y jugar juegos dentro de una plataforma con usuarios por rol:

- Administrador: gestiona usuarios y sistema.
- Gestor: CRUD de juegos y publicacion.
- Jugador: acceso solo a juegos publicados.

## Stack

- Laravel 12
- Inertia.js + React
- PostgreSQL (via Sail)
- Eloquent ORM

## Configuracion de entorno

1. Copia variables de entorno:
	 `cp .env.example .env`
2. Verifica que la base de datos use PostgreSQL:
	 - `DB_CONNECTION=pgsql`
	 - `DB_HOST=pgsql`
	 - `DB_PORT=5432`
	 - `DB_DATABASE=laravel`
	 - `DB_USERNAME=sail`
	 - `DB_PASSWORD=password`

## Arranque rapido

Con WSL Ubuntu (recomendado en este proyecto):

```bash
wsl -d Ubuntu bash -lc 'cd /mnt/c/DWS-Laravel-P1 && ./setup.sh'
```

El script [setup.sh](setup.sh) instala dependencias, levanta Sail, genera `APP_KEY` y ejecuta migraciones con seed.

## Arquitectura de rutas

- Web CRM (Inertia): [routes/web.php](routes/web.php)
- API JSON para cliente de juego: [routes/api.php](routes/api.php)

## Modelo de datos

- `roles`: tipos de usuario (`administrador`, `gestor`, `jugador`)
- `users.role_id`: relacion de usuario a rol
- `games`:
	- `title`
	- `description`
	- `is_published`
	- `path` (URL del juego)
	- `user_id` (creador)

Migraciones principales:

- [database/migrations/2026_03_24_152629_create_roles_table.php](database/migrations/2026_03_24_152629_create_roles_table.php)
- [database/migrations/2026_03_24_152634_create_games_table.php](database/migrations/2026_03_24_152634_create_games_table.php)
- [database/migrations/2026_03_24_153000_add_role_id_to_users_table.php](database/migrations/2026_03_24_153000_add_role_id_to_users_table.php)

## Seguridad y autorizacion

- Middleware de gestion: [app/Http/Middleware/EnsureUserIsAdminOrManager.php](app/Http/Middleware/EnsureUserIsAdminOrManager.php)
- Policy de juego: [app/Policies/GamePolicy.php](app/Policies/GamePolicy.php)
- Validacion de formularios:
	- [app/Http/Requests/StoreGameRequest.php](app/Http/Requests/StoreGameRequest.php)
	- [app/Http/Requests/UpdateGameRequest.php](app/Http/Requests/UpdateGameRequest.php)

## Credenciales iniciales (seed)

- `admin@example.com` / `password`
- `manager@example.com` / `password`
- `player@example.com` / `password`
