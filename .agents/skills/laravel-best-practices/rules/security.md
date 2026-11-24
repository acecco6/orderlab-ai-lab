# Seguridad de OrderLab

- Aplicar Policies/Gates de recursos y ownership; no confiar en IDs, precios ni roles del request.
- FormRequest para validar y normalizar; recursos API no exponen atributos internos ni datos de terceros.
- Usar solo datos validados o transformados explícitamente para persistencia; revisar `$fillable`/`$guarded` y no pasar `$request->all()` a `create()`/`update()`.
- Leer `env()` en archivos de configuración; el código de aplicación usa `config()` para funcionar con configuración cacheada.
- Usar consultas parametrizadas/Eloquent. Ordenamientos dinámicos por allowlist.
- Mantener protección CSRF en web; autenticación adecuada a rutas API según starter kit; rate limiting para login/checkout/administración.
- Revisar mass assignment, operaciones de archivo y URLs externas solo si existen funcionalidades que lo requieren.
- No guardar contraseñas, tokens, secrets ni tarjetas reales en logs, tabla de pagos, trazas o fixtures.
- `APP_DEBUG=false` en todo entorno que no sea local seguro; errores públicos sin stack traces.
- `FaultLab` completamente bloqueado fuera de local + flag + autorización; negar por defecto.
- Configurar TLS, firewall y grants mínimos para MySQL remoto; no exponer Redis al exterior.
- Ejecutar `composer audit` y revisar diferencias de permisos/secretos como bloqueantes.
