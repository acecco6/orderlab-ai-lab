---
name: laravel-best-practices
description: Aplicar al escribir o revisar backend Laravel de OrderLab, incluidos modelos, consultas, migraciones, Requests, Policies, jobs y arquitectura modular. Contrastar primero el repositorio y Laravel instalado.
license: MIT
metadata:
    author: OrderLab (adaptación de coollabsio/coolify)
---

# Laravel en OrderLab

Leer `AGENTS.md`, las reglas de negocio pertinentes en `docs/ORDERLAB_MASTER.md`, archivos vecinos y tests antes de editar. El starter real usa Laravel 13, Fortify, Inertia/Vue y Pest; verificar versiones actuales en cada entorno. Preferir convenciones existentes salvo defecto de corrección o seguridad. Usar documentación oficial de la versión instalada o el código de `vendor/` para APIs sensibles a versión. No asumir que una herramienta `search-docs` existe.

Implementar el cambio coherente más pequeño. Mantener controladores HTTP delgados y dependencias explícitas; usar Form Requests para entrada no trivial y Policies/Gates para autorización. Contratos y servicios separados solo cuando exista una frontera o regla compleja demostrable. No introducir un Repository genérico sobre Eloquent ni crear módulos vacíos.

## Reglas por tema

| Cambio                                          | Leer                                     |
| ----------------------------------------------- | ---------------------------------------- |
| límites de módulos, acciones, inyección         | [architecture.md](rules/architecture.md) |
| Eloquent, MySQL, migraciones, locks e índices   | [database.md](rules/database.md)         |
| autenticación, autorización, entrada y secretos | [security.md](rules/security.md)         |
| jobs, eventos, Redis y caché                    | [queues.md](rules/queues.md)             |
| revisión y entrega                              | [review.md](rules/review.md)             |

Para tests, aplicar además `testing-best-practices`. Ejecutar únicamente comprobaciones seguras para el entorno confirmado. Informar resultados observados y límites de la verificación.

Fuente y alcance de la adaptación: [docs/SKILL_SOURCES.md](../../../docs/SKILL_SOURCES.md).
