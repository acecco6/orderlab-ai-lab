# Auditoría QA independiente

- Inspeccionar diff, código afectado, tests existentes, migraciones y dependencias.
- Test de comportamiento: explicitar el defecto que capturaría cada uno.
- Probar una condición por decisión; evitar pruebas duplicadas sin valor adicional.
- Los permisos y aislamiento entre usuarios necesitan pruebas negativas.
- El test de DB debe correr en un esquema de testing independiente y comprobado.
- `Http::fake()`, Queue/Event fakes u otros dobles deben tener aserciones sobre el efecto esperado; no simular toda la arquitectura.
- El QA no aprueba su propio cambio ni corrige directamente producción si su rol es read-only.
- Entregar severidad, archivo/línea, reproducción, comando, exit code, evidencia, riesgo pendiente y resultado `PASS`, `PASS_WITH_RISKS` o `BLOCK`.
- Conservar tests que detectan defectos distintos; cambiar o eliminar pruebas existentes solo dentro del alcance de la tarea y con motivo verificable.
