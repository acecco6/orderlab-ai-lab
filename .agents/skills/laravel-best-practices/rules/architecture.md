# Arquitectura modular

- Monolito Laravel con módulos `Catalog`, `Inventory`, `Orders`, `Payments`; otros solo cuando haya historias concretas.
- Mantener controladores delgados: FormRequest, Policy, Action/servicio, Resource.
- Acciones pequeñas de negocio con nombre de intención (`PlaceOrder`, `ReserveStock`, `ConfirmPayment`).
- Módulos tienen límites de responsabilidad; no mutar tablas privadas de otros saltándose el caso de uso.
- Usar Eloquent nativo antes de crear repositorios genéricos; introducir contratos en límites verdaderos (simulador de pagos/HTTP externo).
- No montar un árbol de carpetas vacío para hacer parecer la arquitectura más sofisticada.
- Inyección explícita de dependencias; `app()` solo en infraestructura legítima o convenciones existentes.
- No crear front nuevo ni reemplazar Vite/TypeScript que ya exista en el repositorio.
- Cada cambio arquitectónico no trivial genera ADR explicando contexto, opciones, consecuencias.
