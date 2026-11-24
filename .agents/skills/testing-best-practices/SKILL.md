---
name: testing-best-practices
description: Aplicar al diseñar, ejecutar o auditar tests Laravel/Pest de OrderLab, especialmente endpoints, permisos, aislamiento MySQL, concurrencia, idempotencia y valor real de las aserciones.
license: MIT
metadata:
    author: OrderLab (adaptación de coollabsio/coolify)
---

# Testing en OrderLab

Leer el código, las reglas por ID en `docs/ORDERLAB_MASTER.md` y los tests vecinos. El runner instalado es Pest sobre PHPUnit; preservar su estilo. Escribir primero pruebas Feature para contratos visibles y pruebas Unit para lógica pura. Cada test debe detectar un defecto distinto; cubrir decisiones nuevas y fallos probables sin repetir internals del framework.

Usar factories y fakes para efectos externos, con aserciones sobre sus efectos. Mantener reales las consultas del código bajo prueba. Controlar reloj, aleatoriedad y HTTP cuando afecten el resultado. Para locks, índices únicos, FK, transacciones y carreras, usar MySQL externo con base y credenciales **exclusivas de testing**; SQLite en memoria no valida esas garantías. Verificar destino y `APP_ENV=testing` antes de comandos que escriben datos. No ejecutar migraciones ni tests MySQL si no se puede comprobar el aislamiento.

| Trabajo                       | Leer                                   |
| ----------------------------- | -------------------------------------- |
| casos del dominio y cobertura | [test-matrix.md](rules/test-matrix.md) |
| valor de tests y revisión QA  | [review.md](rules/review.md)           |

Ejecutar el test más acotado y luego la suite pertinente. Registrar comando, entorno, exit code y resultado; no afirmar éxito de una prueba no ejecutada. Durante auditoría independiente, QA no edita código.

Fuente y alcance de la adaptación: [docs/SKILL_SOURCES.md](../../../docs/SKILL_SOURCES.md).
