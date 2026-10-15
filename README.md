# ACTIVIDAD STICH — TrayectIA

Aplicación Laravel 12 construida a partir del diseño de Google Stitch **"TrayectIA Logistics Web App"**: una plataforma para transportistas independientes (*hombre-camión*) del corredor Mérida – Progreso – Umán.

## Vistas

| Pantalla de Stitch | Ruta | Vista Blade |
|---|---|---|
| Iniciar sesión | `/login` | `trayectia/auth/iniciar-sesion` |
| Crear cuenta | `/registro` | `trayectia/auth/crear-cuenta` |
| Editar perfil | `/perfil` | `trayectia/perfil` |
| Panel de inicio | `/panel` | `trayectia/panel` |
| Nuevo viaje y cotizador | `/viajes/nuevo` | `trayectia/viajes/nuevo` |
| Expediente de viaje | `/viajes/{id}` | `trayectia/viajes/expediente` |
| Link de aprobación cliente | `/aprobar/{token}` | `trayectia/aprobaciones/cliente` |
| Cobros y métricas | `/cobros` | `trayectia/cobros` |
| Cerrar sesión | `/cerrar-sesion` | `trayectia/auth/cerrar-sesion` |

Las vistas están en `resources/views/trayectia/`. El material original de Stitch (capturas y design system) está en `stitch/`.

## Instalación

Requisitos: PHP 8.2+, Composer y MySQL/MariaDB (por ejemplo, XAMPP).

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Crea la base de datos y configura la conexión en `.env`:

```sql
CREATE DATABASE trayectia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

```env
APP_LOCALE=es
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=trayectia
DB_USERNAME=root
DB_PASSWORD=
```

Crea las tablas, carga los datos de demostración y enlaza la carpeta de fotos de perfil:

```bash
php artisan migrate
php artisan db:seed --class=TrayectiaSeeder
php artisan storage:link
php artisan serve
```

Abre http://localhost:8000 y pulsa **"Entrar como demo"**, o inicia sesión con `donjorge@transportes.mx` / `trayectia2026`.

## Pruebas

```bash
php artisan test
```
