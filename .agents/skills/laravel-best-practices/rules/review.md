# Revisión antes de cerrar una tarea

1. ¿La implementación respeta las reglas de negocio por ID y la ownership de módulo?
2. ¿Los cambios de comportamiento tienen tests de las decisiones y fallos de mayor riesgo?
3. ¿Hay transacción/índice/constraint para la carrera potencial o está justificada su ausencia?
4. ¿Hay autorización real y contrato API estable?
5. ¿Se consultó estado real del repo, versiones, tests y frontend antes de cambiarlo?
6. ¿Se evitó instalar paquetes y capas solo por estética arquitectónica?
7. ¿Se ejecutó Pint y análisis estático disponible? ¿Qué falló y dónde?
8. ¿El README, contrato/API, migrations y ADR reflejan el cambio?
9. ¿MySQL está fuera de Docker, y el test usa una base aislada?
10. Si el cambio es relevante, ¿el subagente QA hizo una revisión independiente comprobada?

No afirmar aprobación si faltan pruebas obligatorias. Reportar riesgos pendientes.
