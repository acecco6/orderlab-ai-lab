# Matriz mínima de tests

| Área      | Happy path                     | Fallo/seguridad                                | Concurrencia/retry                          |
| --------- | ------------------------------ | ---------------------------------------------- | ------------------------------------------- |
| Catalog   | producto activo listado        | SKU duplicado, precio inválido, permisos       | SKU único bajo carrera                      |
| Inventory | ajuste, reserva y consumo      | cantidad negativa, violación de reservas       | múltiples checkouts contra últimas unidades |
| Orders    | checkout, historial propio     | producto inactivo, otro usuario, sin stock     | idempotencia por key y cancelación vs pago  |
| Payments  | aprobado/rechazado fake        | timeout deja estado desconocido; monto falso   | callback/job duplicado, pago tardío         |
| Jobs      | notificación post-commit       | error transitorio / fallo final                | reinicio del worker, retry-after            |
| Seguridad | admin puede gestionar catálogo | IDOR, rate limit, inyección, logs sin secretos | carrera de permisos si aplica               |
| Ops       | trace entre request-job        | FaultLab no opera en prod                      | métricas y logs correctos con carga         |

Cada test de escritura verifica respuesta, estado persistido y efectos secundarios. El valor esperado viene del requisito, no de recalcular el mismo algoritmo probado.
