# Bitácora — Integrante 3 (Backend Dev: Lecturas y Pagos)

## Sprint 2

### Alcance
- **SCRUM-25** — `LecturaController::index()`: listar contadores pendientes de lectura del período actual con su última lectura registrada (o 0 si es la primera).
- **SCRUM-26** — `LecturaController::store()`: validar `lectura_actual > lectura_anterior`, calcular consumo, usar el motor de I1, calcular monto y guardar la lectura.
- Documentación (this bitácora + actualización del README del trait).

### Decisiones de negocio confirmadas
| ID | Pregunta | Respuesta | Quién |
|----|----------|-----------|-------|
| P1 | Valor del enum `estado_periodo` | `'ACTIVO'` / `'CERRADO'`, default `'ACTIVO'` (ya unificado en migración) | I1/I2 |
| P2 | Tabla de usuarios | `tb_usuarios` (esquema simplificado para Breeze: `id, name, email, password, role_id`); `usuario_id` de Lectura apunta ahí | I2 |
| P3 | Qué tarifa aplica a cada contador | Un solo tipo de tarifa activo a la vez; `tipo_tarifa_id` en `tb_tarifas` solo es catálogo. `tb_contadores` NO tendrá `tipo_tarifa_id` (fuera de alcance hasta post Demo Day) | I1/I2 |
| P4 | ¿Columna `estado` en Lectura? | NO. El estado "pendiente/pagada" se deriva de la existencia de un Pago (`tb_pagos.Estado_Pago`) | I1/I2 |

### Archivos (Sprint 2)
- `app/Models/Lectura.php` — reescrito: tabla `tb_lecturas`, fillable sin `consumo` (storedAs) y sin `estado`; relaciones `contador`, `tarifa`, `usuario`, `periodo`, `pago`; conserva `calcularMonto()` de I1.
- `app/Models/Periodo.php` — nuevo: tabla `tb_periodos`, `scopeActivo()` → `estado_periodo = 'ACTIVO'`.
- `app/Http/Requests/LecturaRequest.php` — nuevo: validación de `contador_id`, `periodo_id`, `lectura_actual` con mensajes en español.
- `app/Http/Controllers/LecturaController.php` — nuevo: `index()` y `store()`.
- `routes/web.php` — `Route::resource('lecturas', ...)->only(['index','store'])` con middleware `auth` + `role:Administrador,Secretaria,Empleado`.
- `app/Traits/README.md` — se marca `BuscaTarifaVigente` como SUSPENDIDO/fallback (reemplazado por el motor de I1).

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
4. Llama al motor de I1 `Lectura::calcularMonto($lecturaAnterior, $request->lectura_actual, now())` (si no hay tarifa vigente, excepción → error de validación amigable).
5. Genera `numero_recibo` (`REC-YYYYMMDD-NNNNN`), guarda con `usuario_id = auth()->id()`.
6. NO escribe `consumo` (lo calcula la BD, storedAs) ni `estado` (P4).

### Pruebas (bd local, migraciones 13/13)
Ejecutadas con Tinker sobre MariaDB (instancia portable puerto 3307):
- `Periodo::activo()` → devuelve el período ACTIVO ✅
- `Lectura::calcularMonto(0, 15, now())` → consumo 15, monto 82.50, tarifa_id 1 ✅
- Alta de lectura → `consumo` calculado por la BD (15.00 tras `refresh()`), recibo `REC-20260907-00001`, unique `(contador_id, periodo_id)` OK ✅
- `index()` → contador con lectura en el período se excluye; contador sin lectura aparece con anterior=0 ✅

### Pendientes / dependencias
- **I4:** crear la vista `resources/views/lecturas/index.blade.php` (recibe `$periodo` y `$contadores`).
- **I2:** confirmar/actualizar el modelo `Pago` (Sprint 3): la migración `tb_pagos` usa `monto_pago`, `fecha_pago`, `metodo_pago`, `estado_pago`, `lecturas_id` (único), `usuario_id`.
- **Entorno:** la BD de XAMPP estaba corrupta (assert de InnoDB); se levantó una instancia MariaDB portable en `C:\Users\PREDATOR\AppData\Local\Temp\opencode\mariadb-data` con el `.env` apuntando al puerto 3307.

### Próximo sprint (3 — Pagos)
- `PagoController::store()`: crear Pago para una Lectura (`$lectura->pago()->create(...)`), sin tocar `estado` de Lectura (P4).
- Estado derivado: lectura sin Pago → pendiente; con Pago `estado_pago = 'PAGADO'` → pagada.