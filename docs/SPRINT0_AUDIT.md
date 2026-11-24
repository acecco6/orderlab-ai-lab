# Sprint 0 — auditoría y preparación

Fecha: 2026-11-24. Alcance: etapas A, B y C del primer pedido; sin código de Catalog, Inventory, Orders ni Payments.

## Estado encontrado

- Laravel 13.43.0; PHP CLI local 8.5.12. La imagen Docker usa PHP 8.4. `composer.json` exige `^8.3`.
- Starter Vue 3 + Inertia 3 + Fortify, Vite Plus/Tailwind 4; rutas web y pantalla de autenticación ya existen. Pest 5 sobre PHPUnit, `RefreshDatabase` en Feature.
- Pint y Larastan ya estaban instalados; `phpstan.neon` nivel 7. GitHub Actions existía con PHP 8.5 y `composer setup` (incluía migración). No había Dockerfile, Compose, Makefile ni README.
- `.env.example`, `.env` local y `phpunit.xml` apuntaban a SQLite. No apareció un servicio PostgreSQL; `config/database.php` conserva solo el driver `pgsql` estándar de Laravel, sin uso activo encontrado.
- No existe `.git` en esta carpeta, por lo que no se pudo obtener estado, diff ni commit local. `.gitignore` excluía AGENTS y directorios Codex; se corrigió para que puedan versionarse al inicializar Git por decisión del propietario.
- Diferencias con el documento maestro: ADR-006 se confirma en Docker pero el host corre PHP 8.5; ADR-011 es Fortify, ADR-012 es Pest y ADR-013 es Vue/Inertia. El apartado 21 pedía inicialmente solo planificación, pero el pedido actual autorizó implementar las etapas A–C.

## Cambios realizados

- `AGENTS.md` concentra reglas generales; las dos skills Codex se acortaron y sus reglas temáticas se ajustaron. [Fuentes y comparación](SKILL_SOURCES.md). El agente QA TOML existente fue válido y se invocó realmente; no requirió modificación.
- Docker construye PHP-FPM 8.4 con `pdo_mysql`, Redis, `intl`, `bcmath`, `gd` y `zip`; Nginx sirve el build Vue. Compose contiene app, web, Redis y worker opcional, sin MySQL. Build/frontend regeneran Wayfinder con PHP disponible.
- `.env.example` documenta MySQL de desarrollo y `.env.testing.example` una base de pruebas separada. `phpunit.xml` conserva SQLite en memoria para el starter; `phpunit.mysql.xml` y `scripts/check-test-target.php` preparan la ruta InnoDB. `make migrate` valida base, host, puerto, usuario, socket, URL y entorno efectivos antes de mutar.
- `composer setup` ya no ejecuta migraciones. CI usa PHP 8.4, instala dependencias con locks y ejecuta auditoría de Composer. El límite de memoria de PHPStan se hizo explícito.

## Verificaciones reales

| Comando                                                           | Resultado                                                                                                                                                                     |
| ----------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `composer validate --strict`                                      | Aprobado.                                                                                                                                                                     |
| `composer audit --locked`                                         | Sin avisos de vulnerabilidad.                                                                                                                                                 |
| `composer outdated --direct --format=json`                        | Ninguna dependencia PHP directa atrasada según Composer.                                                                                                                      |
| `npm audit --omit=dev --audit-level=moderate`                     | 0 vulnerabilidades reportadas.                                                                                                                                                |
| `npm outdated --json`                                             | Existen versiones mayores recientes de `@vueuse/core`, TypeScript y Vite Plus, y una versión más reciente de Rollup opcional; no se actualizaron por estar fuera del alcance. |
| `composer ci:check`                                               | Aprobado localmente: formato/lint frontend, tipos Vue, Pint, Larastan y Pest. Pest: 28 tests, 25 aprobados, 3 omitidos, 72 aserciones.                                        |
| `docker compose build app web`                                    | Aprobado; imágenes app y web construidas.                                                                                                                                     |
| `docker compose config --quiet`                                   | Aprobado; ninguna base de datos en Compose.                                                                                                                                   |
| `docker compose up -d`, `GET /up`, `redis-cli ping`               | App y Redis healthy; HTTP 200 y Redis PONG. Se detuvieron contenedores con `docker compose down`, sin `-v`.                                                                   |
| `docker compose exec -T app php artisan db:show --no-interaction` | Falló 1045: usuario placeholder `orderlab_app` sin contraseña. Confirma que se alcanzó un MySQL, pero no autentica ni valida la base.                                         |
| `php scripts/check-test-target.php`                               | Rechazó ejecución porque falta `.env.testing`; no hubo migraciones ni tests MySQL.                                                                                            |

El validador Python incluido con `skill-creator` no pudo ejecutarse porque falta PyYAML en el host; se validó el frontmatter de ambas skills con el parser YAML de Symfony instalado y se comprobaron nombre y descripción. La configuración TOML de QA se parseó con `tomllib`. La CI remota no se ejecutó.

## QA y pendientes

El subagente `qa-engineer` se invocó realmente y realizó auditoría independiente de solo lectura. Detectó y se corrigieron el build frontend sin PHP/artisan, la primera guarda insuficiente de migración y la falta de alternativas Windows a GNU Make. Cierre QA: sin hallazgos nuevos; HTTP/Redis verificados y aceptación MySQL pendiente de credenciales externas. No modificó código ni ejecutó migraciones.

El propietario debe provisionar/confirmar host, puerto, versión de MySQL, TLS si aplica, `orderlab_dev` y `orderlab_test` con usuarios distintos y permisos mínimos. Luego completar `.env`/`.env.testing`, confirmar el destino, autorizar migraciones y ejecutar pruebas MySQL. Hasta entonces no se puede afirmar conectividad autenticada, aislamiento efectivo ni CI de integración verde.

## Propuesta para Sprint 1: Catalog

No se implementó. Orden sugerido para una historia aprobada:

1. Confirmar semántica de SKU (normalización, collation/case), moneda ARS y unidades menores, y decidir si la API pública convivirá con las rutas web existentes.
2. Registrar ADR breve para estructura `app/Modules/Catalog`, namespaces `App\Modules\Catalog\*` y límites con Fortify/Identity; crear solo carpetas necesarias.
3. Implementar migración `products` con SKU único, `price_minor` entero, estado y constraints compatibles con la versión MySQL real; factory y modelo Eloquent.
4. Implementar lectura pública paginada de activos, orden estable y filtros permitidos. Medir consultas antes de agregar índices secundarios.
5. Implementar creación/edición/activación/desactivación administrativas mediante Form Requests, Policy y acciones de negocio solo donde la regla lo requiera; no borrar productos con historial.
6. Cubrir CAT-01 a CAT-08 con Pest: 401/403, SKU duplicado y carrera real MySQL, precio inválido, inactivos invisibles, transición de estados y no alteración de snapshots cuando exista Orders. Los casos dependientes de Orders se reservan para ese sprint.
7. Ejecutar Pint, Larastan, suite Feature e integración MySQL aislada, auditoría QA independiente y revisión de contrato API. No iniciar Inventory automáticamente.
