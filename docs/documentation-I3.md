# Bitácora — Integrante 3 (Backend Dev: Lecturas y Pagos)

## Sprint 3

### Alcance
- **SCRUM-33** — `PagoController::store()`: registrar Pago (monto, fecha, método) para una lectura y setear `estado_pago = 'PAGADO'`.
- **Integración del flujo**: guardar lectura exitosa → redirigir a vista de recibo (`lecturas.show`) → enlace a registrar pago (`pagos.create`).
- Coordinación con I4: los nombres de rutas quedan bajo el recurso `lecturas` (show = recibo) y `pagos` (create/store), respetando el formato existente.
- Pruebas manuales con datos del seeder (primera lectura anterior=0, lectura normal, consumo=0).

### Decisiones de negocio (Sprint 3, confirmadas por I2)
| ID | Pregunta | Respuesta | Quién |
|----|----------|-----------|-------|
| S3-P1 | ¿`estado_pago` al crear? | Se setea `'PAGADO'` al crear el pago, porque la Secretaria lo registra cuando el cliente ya pagó. No hay flujo de "pago pendiente". Fuente de verdad = registro en `tb_pagos`. | I2 |
| S3-P2 | Ruta del recibo | Se mantiene bajo el recurso `lecturas` (`lecturas.show`), sin ruta dedicada `recibos/` para no romper el formato. | I4 |
| S3-P3 | Monto en el form | Pre-llenado desde `lectura->monto`, pero editable (abono, redondeo, etc.). | I2 |
| S3-P4 | Fecha de pago | La selecciona el usuario (viene del `$request`), default hoy en el input. | I2 |
| S3-P5 | Roles de pago | Solo `Administrador` y `Secretaria` registran pagos (middleware). | I3/USER |

### Archivos (Sprint 3)
- `app/Http/Requests/PagoRequest.php` — nuevo: validación `lecturas_id` (exists + unique en `tb_pagos`), `monto_pago` (numeric ≥ 0), `fecha_pago` (date), `metodo_pago` (in Efectivo/Credito/Debito), mensajes en español.
- `app/Http/Controllers/PagoController.php` — nuevo: `create()` (form con lectura pre-cargada, rechaza si ya tiene pago) y `store()` (crea pago con `estado_pago='PAGADO'` y redirige a `lecturas.show`).
- `app/Http/Controllers/LecturaController.php` — agrega `show(Lectura $lectura)`: carga relaciones y devuelve la vista recibo `lecturas.show`. `store()` ahora redirige a `lecturas.show` en lugar de `lecturas.index`.
- `routes/web.php` — se agrega `show` al recurso `lecturas`, y nuevo recurso `pagos` (`only(['create','store'])`) con middleware `auth + role:Administrador,Secretaria`.
- `database/seeders/PagoSeeder.php` — nuevo: 2 pagos de prueba (lecturas 1 y 6, períodos cerrados).
- `database/seeders/DatabaseSeeder.php` — incluye `PagoSeeder`.

### Implementación

**PagoController::create()**
1. Recibe `lectura_id` por query string, carga la lectura con `contador`, `periodo` y `tarifa`.
2. Si la lectura ya tiene pago, redirige a `lecturas.show` con error.

**PagoController::store()**
1. Valida con `PagoRequest` (lectura existe, única, monto ≥ 0, fecha, método).
2. Si la lectura ya tiene pago, vuelve atrás con error (doble chequeo).
3. Crea el pago con `estado_pago = 'PAGADO'` vía `$lectura->pago()->create(...)`.
4. Redirige a `lecturas.show` con mensaje de éxito.

**LecturaController::show()** — carga `contador`, `periodo`, `tarifa`, `pago` y devuelve `lecturas.show`.

### Pendientes / dependencias (Sprint 3)
- **I4:** crear las vistas `resources/views/lecturas/show.blade.php` (recibo) y `resources/views/pagos/create.blade.php` (form).
  - Recibo: muestra datos de la lectura (número recibo, cliente, contador, consumo, monto, estado) y enlace a `pagos.create?lectura_id={id}`.
  - Form pago: `monto_pago` pre-llenado y editable, `fecha_pago` default hoy, `metodo_pago` select Efectivo/Credito/Débito, POST a `pagos.store`.
- **I2/I4:** actualizar el sidebar (`Lecturas` → `lecturas.index`, `Pagos` → por definir, probablemente `lecturas.index` de lecturas pendientes o un listado de recibos).

---

## Sprint 2

### Alcance
- **SCRUM-25** — `LecturaController::index()`: listar contadores pendientes de lectura del período actual con su última lectura registrada (o 0 si es la primera).
- **SCRUM-26** — `LecturaController::store()`: validar `lectura_actual > lectura_anterior`, calcular consumo, usar `Tarifa::vigenteEn()` (decisión I1/I2 tras el bloqueo), calcular monto y guardar la lectura.
- Documentación (this bitácora + actualización del README del trait).

### Decisiones de negocio confirmadas
| ID | Pregunta | Respuesta | Quién |
|----|----------|-----------|-------|
| P1 | Valor del enum `estado_periodo` | `'ACTIVO'` / `'CERRADO'`, default `'ACTIVO'` (ya unificado en migración) | I1/I2 |
| P2 | Tabla de usuarios | `tb_usuarios` (esquema simplificado para Breeze: `id, name, email, password, role_id`); `usuario_id` de Lectura apunta ahí | I2 |
| P3 | Qué tarifa aplica a cada contador | Un solo tipo de tarifa activo a la vez; `tipo_tarifa_id` en `tb_tarifas` solo es catálogo. `tb_contadores` NO tendrá `tipo_tarifa_id` (fuera de alcance hasta post Demo Day) | I1/I2 |
| P4 | ¿Columna `estado` en Lectura? | NO. El estado "pendiente/pagada" se deriva de la existencia de un Pago (`tb_pagos.Estado_Pago`) | I1/I2 |

### Archivos (Sprint 2)
- `app/Models/Lectura.php` — reescrito en develop por I2/I4: tabla `tb_lecturas`, fillable con `numero_recibo` y sin `consumo` (storedAs) ni `estado`; relaciones `contador`, `tarifa`, `usuario`, `periodo`, `pago` (FK `lecturas_id`); accessors `numero_recibo` y `estado_pago`. **No** tiene `calcularMonto()` (decisión I1/I2).
- `app/Models/Periodo.php` — nuevo: tabla `tb_periodos`, `scopeActivo()` → `estado_periodo = 'ACTIVO'`.
- `app/Http/Requests/LecturaRequest.php` — nuevo: validación de `contador_id`, `periodo_id`, `lectura_actual` con mensajes en español.
- `app/Http/Controllers/LecturaController.php` — nuevo: `index()` y `store()`.
- `routes/web.php` — `Route::resource('lecturas', ...)->only(['index','store'])` con middleware `auth` + `role:Administrador,Secretaria,Empleado`.
- `app/Traits/README.md` — se marca `BuscaTarifaVigente` como SUSPENDIDO/fallback (reemplazado por `Tarifa::vigenteEn()` directo en el controller).

### Implementación

**index()**
1. Toma `Periodo::activo()->latest('fecha_apertura')->first()`; si no hay período activo devuelve listado vacío.
2. Filtra contadores `activo_contador = 'ACTIVO'` con su cliente y sus lecturas (última primero).
3. Descarta los que ya tienen lectura en el período actual (filtro por `periodo_id`).
4. Mapea `contador_id`, `codigo_contador`, `cliente` (via `nombre_completo`) y `lectura_anterior` (última lectura_actual o 0).

**store()**
1. Valida con `LecturaRequest` (exists en tablas + lectura_actual numérica ≥ 0).
2. Busca la última lectura del contador → `lecturaAnterior` (0 si no hay).
3. Fuerza `lectura_actual > lectura_anterior` (si no, `ValidationException`).
4. Calcula consumo (`lectura_actual - lectura_anterior`) y monto con la tarifa vigente: `Tarifa::vigenteEn(now())`; si no hay tarifa, error de validación amigable (`tarifa`).
5. Genera `numero_recibo` (`REC-YYYYMMDD-NNNNN`) y lo guarda en la columna (ya en `$fillable`, decisión I2), con `usuario_id = auth()->id()`.
6. NO escribe `consumo` (lo calcula la BD, storedAs) ni `estado` (P4).

### Pruebas (bd local, migraciones 13/13)
Ejecutadas con Tinker sobre MariaDB (instancia portable puerto 3307):
- `Periodo::activo()` → devuelve el período ACTIVO ✅
- `Tarifa::vigenteEn(now())` → tarifa vigente (monto_por_unidad) ✅
- Alta de lectura → `consumo` calculado por la BD (15.00 tras `refresh()`), recibo `REC-20260907-00001`, unique `(contador_id, periodo_id)` OK ✅
- `index()` → contador con lectura en el período se excluye; contador sin lectura aparece con anterior=0 ✅

### Pendientes / dependencias
- **I4:** crear la vista `resources/views/lecturas/index.blade.php` (recibe `$periodo` y `$contadores`).
- **I2:** confirmar/actualizar el modelo `Pago` (Sprint 3): la migración `tb_pagos` usa `monto_pago`, `fecha_pago`, `metodo_pago`, `estado_pago`, `lecturas_id` (único), `usuario_id`.
- **Entorno:** la BD de XAMPP estaba corrupta (assert de InnoDB); se levantó una instancia MariaDB portable en `C:\Users\PREDATOR\AppData\Local\Temp\opencode\mariadb-data` con el `.env` apuntando al puerto 3307.

### Próximo sprint (3 — Pagos)
- `PagoController::store()`: crear Pago para una Lectura (`$lectura->pago()->create(...)`), sin tocar `estado` de Lectura (P4).
- Estado derivado: lectura sin Pago → pendiente; con Pago `estado_pago = 'PAGADO'` → pagada.