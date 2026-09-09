# HidroGest — Resumen de Backend/BD (Integrante 2)

## Rol en el proyecto

Diseño de base de datos, modelos Eloquent y seeders. Soporte técnico al equipo
en bugs de esquema, relaciones y controladores.

## Sprint 1 — Esquema y modelos base (26-28 ago)

**Migraciones:** diseño y creación de las tablas del sistema:
`tb_roles`, `tb_usuarios`, `tb_clientes`, `tb_contadores`, `tb_tarifas`,
`tb_tipo_tarifa`, `tb_periodos`, `tb_lecturas`, `tb_pagos`.

**Modelos Eloquent creados:**
- `Cliente.php`, `Contador.php`, `Tarifa.php`, `TipoTarifa.php`
- `Lectura.php`: incluye accessor `getNumeroReciboAttribute()` y `getEstadoPagoAttribute()`
- `Pago.php`: relaciones con `Lectura` y `User`

**Decisiones de esquema:**
- `consumo` en `tb_lecturas` como columna generada (`storedAs`) — no se inserta manualmente
- `estado` de pago se deriva de la existencia del registro en `tb_pagos`, no es columna
- Una tarifa activa a la vez por tipo; al crear una nueva se cierra la anterior automáticamente

## Sprint 2 — Correcciones y soporte (29 ago – 4 sep)

**Bugs corregidos en modelos:**
- `Contador.php`: typo `$cast` → `$casts`; fillable actualizado para coincidir con migración
- `TipoTarifa.php`: archivo faltante (PSR-4: renombrado de `Tipo_Tarifa.php` a `TipoTarifa.php`)
- `Lectura.php`: campos incorrectos en `$fillable` (`fecha` → `fecha_lectura`, agregado `periodo_id`, `numero_recibo`; removido `consumo` y `estado`)
- `Pago.php`: FK corregida a `lecturas_id`; campos renombrados (`monto_pago`, `metodo_pago`, `estado_pago`, `usuario_id`)

**Corrección en controlador:**
- `TarifaController@index()`: agregado `$tipos` al `compact()` para que la vista reciba el listado de tipos de tarifa

## Sprint 3 — Seeders y soporte al equipo (5-9 sep)

**Seeders creados:**
- `RoleSeeder`: 3 roles (Administrador, Secretaria, Empleado)
- `TipoTarifaSeeder`: tipo Residencial
- `TarifaSeeder`: 2 tarifas históricas (Q2.50 ene-jun 2026, Q3.00 jul 2026 en adelante)
- `UserSeeder`: 3 usuarios de prueba con roles asignados
- `ClienteSeeder`: 5 clientes de ejemplo
- `ContadorSeeder`: 5 contadores activos asociados a clientes
- `PeriodoSeeder`: 3 períodos (jul/ago cerrados, sep activo)
- `LecturaSeeder`: 10 lecturas en 2 períodos con recibos REC-000001 al REC-000010

**Soporte al equipo (fuera de scope propio):**
- `DashboardController@index()`: lógica de estado Al día/Pendiente por cliente
- `PagoController@index()`: filtros por cliente, estado_pago, fecha_desde y fecha_hasta
- Migración `tb_usuarios`: agregado `remember_token` para el checkbox "Recordar sesión"

## Bugs encontrados y resueltos

| Bug | Módulo | Estado |
|---|---|---|
| `TipoTarifa.php` inexistente (PSR-4 naming) | Tarifas | Corregido |
| `$cast` → `$casts` en `Contador.php` | Contadores | Corregido |
| `Lectura.php` con campos incorrectos en `$fillable` | Lecturas | Corregido |
| `Pago.php` FK y columnas no coincidían con migración | Pagos | Corregido |
| `TarifaController@index()` no pasaba `$tipos` a la vista | Tarifas | Corregido |
| `remember_token` faltaba en migración `tb_usuarios` | Login | Corregido |
| `pago()` usaba `lectura_id` en vez de `lecturas_id` | Lecturas/Pagos | Corregido |
| Estado del dashboard siempre mostraba "0" (encoding mismatch) | Dashboard | Corregido |

## Convenciones aplicadas

- Modelos en `app/Models/`, un archivo por entidad, nombre en PascalCase
- `$fillable` siempre alineado con la migración real (no campos generados ni derivados)
- Seeders en orden de dependencias: Roles → TipoTarifa → Tarifas → Usuarios → Clientes → Contadores → Períodos → Lecturas
- FK nombradas según la migración, no según convención automática de Laravel