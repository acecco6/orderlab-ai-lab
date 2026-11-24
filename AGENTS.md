# OrderLab: reglas de trabajo para Codex

Este directorio ya contiene Laravel 13, el starter Vue/Inertia/Fortify y tests Pest. Leer `docs/ORDERLAB_MASTER.md` y contrastarlo con el código antes de cambiar una parte del sistema. El documento maestro define el producto; registrar cualquier diferencia con la implementación real.

## Alcance y arquitectura

- Conservar la aplicación y el frontend existentes. Usar un monolito modular bajo `app/Modules/<Modulo>` cuando se implemente una historia; no crear árboles vacíos. Catalog, Inventory, Orders y Payments son límites de responsabilidad, no paquetes independientes.
- Mantener `App\` y las convenciones nativas de Laravel. Rutas y migraciones pueden permanecer en `routes/` y `database/migrations/`. Introducir contratos solo ante límites reales; no añadir capas, paquetes o dependencias por anticipación.
- Antes de implementar, identificar módulo dueño, reglas del documento maestro, interfaces afectadas y pruebas. Explicar la decisión que agregue una abstracción o cambie una regla de negocio.

## Skills y agentes

- Aplicar `.agents/skills/laravel-best-practices/SKILL.md` al escribir o revisar backend Laravel y leer solo las reglas pertinentes.
- Aplicar `.agents/skills/testing-best-practices/SKILL.md` al diseñar, ejecutar o auditar pruebas.
- Delegar trabajo acotado cuando aporte valor; comunicar alcance y archivos permitidos. El agente principal integra y verifica. Solicitar a `qa-engineer` una auditoría independiente de cambios relevantes. QA informa hallazgos y no modifica código durante la auditoría.

## Datos y seguridad

- MySQL pertenece al propietario y está fuera de Docker. No crear bases, cambiar grants ni ejecutar migraciones sin destino confirmado y autorización. Nunca ejecutar `migrate:fresh`, `db:wipe`, rollback o seeders destructivos sobre una base no verificada como aislada de testing.
- Separar credenciales y bases de desarrollo y testing. No usar producción ni desarrollo para pruebas. No leer, copiar ni publicar secretos de `.env`; usar ejemplos sin credenciales reales.
- Validar entrada, autorizar en servidor mediante Policies/Gates cuando corresponda, limitar mass assignment y evitar SQL concatenado. Proteger invariantes monetarias y de stock con constraints y transacciones cuando la operación lo requiera.
- No hacer commits ni despliegues sin autorización. No eliminar datos, volúmenes o implementaciones existentes sin autorización.

## Calidad y cierre

- Preferir tests de comportamiento, incluidos errores, autorización y efectos persistidos. Usar MySQL aislado para verificar locks, FK y transacciones; SQLite de memoria solo sirve para los tests existentes que no prueban esas garantías.
- Antes de cerrar: ejecutar las pruebas seguras pertinentes, Pint en modo check, PHPStan/Larastan y auditoría de dependencias cuando estén disponibles; revisar el cambio y la configuración Docker. Si un gate no puede ejecutarse, informar por qué, sin marcarlo como aprobado.
- Informar archivos cambiados, comandos y resultados reales, riesgos pendientes y revisión QA cuando se haya ejecutado. No avanzar al módulo siguiente sin pedido del propietario.
