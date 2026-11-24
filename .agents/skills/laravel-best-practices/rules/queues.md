# Jobs, Redis, eventos y caché

- Redis puede vivir en Docker; MySQL no.
- Despachar jobs/eventos de efectos secundarios después del commit de MySQL.
- Jobs idempotentes incluso si Redis o el worker entregan dos veces.
- `ShouldBeUnique` puede reducir duplicados en despacho, pero su lock de caché no reemplaza idempotencia ni constraints de base.
- Configurar reintentos, `backoff`, límite de intentos, `timeout`, `failed()` y tracking de correlation ID.
- Para drivers de cola aplicables, `retry_after` debe superar el timeout del worker/job con margen; probar reinicios.
- No enviar emails ficticios o llamadas HTTP durante locks de inventario.
- Cache de lectura nunca fuente de verdad. Definir invalidación y TTL.
- Considerar Horizon solo cuando haya carga/operación suficiente y proteger su UI.
- Logs de jobs sin payload sensible. El mismo caso de uso idempotente procesa reintentos y callbacks duplicados.
