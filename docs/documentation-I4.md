# HidroGest — Resumen de Frontend (Integrante 4)

## Sprint 1 — Layout base y autenticación (26-28 ago)

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
- Dashboard: *(pendiente / en progreso)*.

## Bugs encontrados y su estado

| Bug | Módulo | Estado |
|---|---|---|
| `$clienteId` capturaba el objeto `Cliente` completo en vez de `->id` en `ClienteRequest` | Clientes | Corregido por I2 |
| ENUM real `activo_cliente` es `['Activo','Inactivo']`, no `'ACTIVO'/'NO ACTIVO'` | Clientes | Corregido en vistas y en `ClienteRequest` |
| `$fillable`/`$casts` de `Contador.php` no coincidían con la migración | Contadores | Corregido por I2 |
| `ContadorController` filtraba clientes con `'ACTIVO'` en vez de `'Activo'` | Contadores | Corregido |
| `TipoTarifa.php` no existía | Tarifas | Creado |
| `TarifaController@index()` no pasa `$tipos` al `compact()` | Tarifas | Pendiente |
| `Tarifa::vigenteEn()` vs `vigente()` — mismatch de nombre | Lecturas | Pendiente |
| Falta columna `remember_token` en `tb_usuarios` | Login | Pendiente |
| Error crudo de SQL al reenviar lectura duplicada | Lecturas | Mejora sugerida |

## Convenciones del proyecto

- Ramas: `SCRUM-XX-descripcion-corta`
- Commits: `SCRUM-XX: feat/fix/chore descripción`
- Rutas y vistas en plural (`clientes`, `contadores`, `tarifas`, `lecturas`, `pagos`), siguiendo `Route::resource`
- Frontend nunca escribe queries ni toca migraciones — solo consume datos ya preparados por los controladores

## Pendiente de Sprint 3

- Dashboard con tabla de clientes filtrable (Al día/Pendiente)
- Despliegue en AWS EC2
