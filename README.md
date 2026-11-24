# OrderLab

Aplicación Laravel 13 existente con Vue 3, Inertia, Fortify y Pest. La base MySQL es externa y administrada por el propietario. [Documento maestro](docs/ORDERLAB_MASTER.md) · [reglas para Codex](AGENTS.md).

## Entorno local

Requisitos: Docker Desktop o Docker Engine con Compose, GNU Make (en Windows, WSL/Git Bash) y un servidor MySQL **fuera de Docker**. El propietario crea `orderlab_dev`, un usuario de desarrollo y los permisos necesarios. Copiar `.env.example` a `.env`, generar `APP_KEY` y completar las credenciales locales sin versionarlas. Para MySQL en el host Windows, Compose usa `host.docker.internal`; para un servidor remoto, definir `DOCKER_DB_HOST` con su DNS/IP. `DB_HOST` en `.env` sigue siendo el host para PHP ejecutado fuera del contenedor.

```sh
cp .env.example .env
php artisan key:generate
make up
make health
```

`make up` inicia PHP-FPM, Nginx y Redis; no crea MySQL ni ejecuta migraciones. HTTP queda en `http://localhost:8080/up` (cambiar con `WEB_PORT`). Redis sirve a caché/cola en Docker; el perfil opcional `docker compose --profile workers up -d worker` incorpora el worker cuando existan jobs. `make health` comprueba estado de servicios y lectura de la conexión MySQL externa. Si falla, revisar host, firewall, bind address, grants y TLS del servidor sin mostrar contraseñas. `make down` detiene los servicios sin borrar datos de MySQL.

En PowerShell, donde GNU Make puede no estar instalado, usar `docker compose up --build -d`, `docker compose ps`, `docker compose exec -T redis redis-cli ping`, `docker compose exec -T app php artisan db:show` y `docker compose down`. `php artisan test` y `composer lint:check`/`composer types:check` son equivalentes locales de los targets de calidad.

`make migrate CONFIRM_DB_DATABASE=orderlab_dev CONFIRM_DB_HOST=<host-confirmado> CONFIRM_DB_PORT=<puerto-confirmado> CONFIRM_DB_USERNAME=<usuario-confirmado>` ejecuta migraciones solamente tras confirmar la base, host, puerto y usuario efectivos y autorizar la operación. Nunca usar esta orden contra una base ajena o de producción.

## Pruebas y calidad

`make test` ejecuta la suite existente con SQLite en memoria según `phpunit.xml`; sirve para verificar el starter, no la semántica de InnoDB. `make lint` ejecuta Pint en modo check y PHPStan/Larastan. `composer audit --locked` revisa dependencias PHP. El CI existente ejecuta estos checks con PHP 8.4 y Node 22; su suite SQLite no sustituye la integración MySQL.

Para integración real, el propietario provisiona **otra base y otro usuario**, `orderlab_test`, fuera de Docker. Copiar `.env.testing.example` a `.env.testing`, generar una clave de prueba y completar sus credenciales. `make test-mysql ALLOW_TEST_MIGRATIONS=1` requiere autorización para las migraciones que ejecuta `RefreshDatabase` y verifica entorno, nombre de base y usuario antes de correr la suite. Ejecutarlo solo después de confirmar el destino; no está habilitado automáticamente en CI.

No se ha creado ni modificado ninguna base externa mediante esta configuración. Las migraciones de dominio se incorporarán en sus historias, empezando por Catalog cuando se solicite.
