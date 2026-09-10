# HidroGest — Resumen de Frontend (Integrante 4)

## Sprint 1 — Layout base y autenticación

**Plantilla:** SB Admin (Bootstrap 5), integrada en `layouts/app.blade.php` con navbar, sidebar y footer como partials reutilizables (`layouts/partials/`).

**Login y bienvenida:**
- `layouts/guest.blade.php`: layout separado para páginas de autenticación (sin sidebar), centrado vertical y horizontal en pantalla.
- `auth/login.blade.php`: vista de login en el path estándar que espera Laravel Breeze.
- `bienvenida.blade.php`: contenido condicional según rol del usuario (Administrador / Secretaria / Empleado, según el enum real de `tb_roles.nombre_rol`).

## Sprint 2 — CRUDs de Clientes, Contadores y Tarifas

**Patrón de diseño:** modal Bootstrap compartido para crear/editar en cada módulo (Clientes y Contadores), evitando duplicar formularios en páginas separadas. Tarifas usa modal solo de creación, sin edición (histórico inmutable).

- `clientes/index.blade.php`: tabla + modal, conectado a `ClienteController`.
- `contadores/index.blade.php`: tabla + modal, con botón de toggle activo/inactivo, conectado a `ContadorController`.
- `tarifas/index.blade.php`: tabla histórica con indicador Vigente/Vencida + modal de creación, conectado a `TarifaController`.

## Sprint 3 — Lecturas, recibo, pagos y dashboard

- `lecturas/index.blade.php`: registro de lectura mobile-first (una tarjeta por contador pendiente, teclado numérico, input grande).
- `lecturas/show.blade.php`: recibo imprimible con `@media print` (sin librerías externas, Ctrl+P nativo).
- `pagos/create.blade.php`: formulario de registro de pago con resumen del recibo.
- `pagos/index.blade.php`: listado de pagos con filtros (cliente, estado, rango de fechas) vía query string GET, enlace directo al recibo de cada pago. Conectado a `PagoController@index()`.
- `dashboard.blade.php`: tarjetas de resumen (total clientes, al día, pendientes, fecha) + tabla de clientes con filtro por nombre y estado, 100% client-side (JavaScript con data-attributes, sin recargar página). Conectado a `DashboardController@index()`.

## Fixes post-entrega

- **Sidebar filtrado por rol**: los `<a>` de cada módulo (Clientes, Contadores, Tarifas, Lecturas, Pagos) ahora están condicionados con `@if (in_array($rol, [...]))`, replicando los mismos roles que exige cada grupo de rutas en `web.php`. Antes el sidebar mostraba enlaces que el middleware bloqueaba con 403, generando confusión.
- **Tablas responsive**: `clientes/index.blade.php`, `contadores/index.blade.php`, `tarifas/index.blade.php` y `pagos/index.blade.php` envuelven su `<table>` en `<div class="table-responsive">`, evitando que el desborde horizontal empuje el layout completo en pantallas móviles. La paginación queda fuera del contenedor para no perderse en el scroll.

## Bugs encontrados y su estado

| Bug | Módulo | Estado |
|---|---|---|
| `$clienteId` capturaba el objeto `Cliente` completo en vez de `->id` en `ClienteRequest` | Clientes | Corregido |
| ENUM real `activo_cliente` es `['Activo','Inactivo']`, no `'ACTIVO'/'NO ACTIVO'` | Clientes | Corregido |
| `$fillable`/`$casts` de `Contador.php` no coincidían con la migración | Contadores | Corregido |
| `ContadorController` filtraba clientes con `'ACTIVO'` en vez de `'Activo'` | Contadores | Corregido |
| `TipoTarifa.php` no existía | Tarifas | Corregido |
| `TarifaController@index()` no pasa `$tipos` al `compact()` | Tarifas | Corregido |
| `Tarifa::vigenteEn()` vs `vigente()` — mismatch de nombre | Lecturas | Corregido |
| Falta columna `remember_token` en `tb_usuarios` | Login | Corregido |
| `DashboardController` no existía (ruta placeholder) | Dashboard | Corregido |
| `PagoController@index()` y ruta `pagos.index` no existían | Pagos | Corregido |
| Sidebar mostraba módulos no permitidos según rol | Sidebar | Corregido |
| Tablas sin scroll responsive en mobile | Clientes/Contadores/Tarifas/Pagos | Corregido |

## Convenciones del proyecto

- Ramas: `SCRUM-XX-descripcion-corta`
- Commits: `SCRUM-XX: feat/fix/chore descripción`
- Rutas y vistas en plural (`clientes`, `contadores`, `tarifas`, `lecturas`, `pagos`), siguiendo `Route::resource`
- Frontend nunca escribe queries ni toca migraciones — solo consume datos ya preparados por los controladores
