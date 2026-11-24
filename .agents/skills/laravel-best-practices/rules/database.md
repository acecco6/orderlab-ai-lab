# MySQL externo, Eloquent y transacciones

- El motor de verdad es MySQL externo con InnoDB; bases de desarrollo y testing provisionadas por el propietario.
- Base de pruebas `orderlab_test` separada, nunca `orderlab_dev` ni producción; bloquear operaciones destructivas con comprobación de entorno/esquema.
- Las migraciones describen la estructura en Git aunque MySQL se administre fuera de Docker; no editar migraciones ya compartidas/desplegadas.
- `sku` único por índice real; `order.user_id + idempotency_key_hash` único; las restricciones no se reemplazan por validación de app.
- Almacenar importes en unidades menores enteras, sin floats. El precio de catálogo no altera los snapshots de líneas de pedido.
- `on_hand >= reserved >= 0`; stock en MySQL, no en Redis.
- Checkout y reserva dentro de `DB::transaction()`, con `lockForUpdate()` en filas afectadas o UPDATE condicional probado. Ordenar locks por `product_id`, evitar IO externo en la transacción.
- Reintentos de deadlock solo alrededor de operaciones idempotentes; pruebas de concurrencia con MySQL real.
- Evitar N+1, usar eager load cuando corresponda, `withCount` para contadores, paginación estable y `EXPLAIN` para justificar índices.
- Al limitar columnas en relaciones eager loaded, incluir PK/FK necesarias para que Eloquent pueda asociarlas. Para lotes que modifican registros, preferir `chunkById()`/`lazyById()` a paginación por offset.
- Diseñar migraciones reversibles cuando sea viable; no mezclar transformaciones masivas de datos con DDL sin plan explícito. No editar una migración ya aplicada en un entorno compartido.
- `DB_HOST` desde Docker apunta a `host.docker.internal` en Windows Desktop si MySQL corre en host, o DNS real si remoto, nunca asumir `localhost` del contenedor.
