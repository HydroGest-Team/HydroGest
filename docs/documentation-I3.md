# Bitácora — Integrante 3 (Backend Dev: Lecturas y Pagos)

**Autor:** Integrante 3 (I3) — Backend Dev (Lecturas y Pagos) · HydroGest
**Rol en el equipo:** Módulos de Lecturas y Pagos (controladores, requests, rutas, lógica de negocio y pruebas).

Esta bitácora resume el trabajo realizado en los Sprints 1, 2 y 3, incluyendo las
complicaciones enfrentadas, las decisiones tomadas con el equipo y el estado actual
del módulo.

---

## Sprint 1 — Fundamentos y preparación

### Alcance
| # | Actividad | Puntos | Estado |
|---|-----------|--------|--------|
| 1 | Estudiar el patrón MVC de Laravel: CRUD de práctica simple (entidad de ejemplo, no del proyecto). Enfocar cómo el controlador pasa variables a la vista Blade con `compact()` o `with()`. | 2 | ✅ Completado |
| 2 | Leer y entender la lógica de tarifa vigente que se usará en Sprint 2: cómo consultar la tarifa activa a una fecha dada con Eloquent. | 0.5 | ✅ Completado |

### Qué se hizo
- **CRUD de práctica (MVC):** se creó un CRUD sobre una entidad de ejemplo (fuera del
  alcance del proyecto) para dominar el ciclo request → controlador → vista → response.
  Se practicó el paso de variables a las vistas Blade con `compact()` y `with()`,
  así como las validaciones y el retorno de respuestas de éxito/error.
- **Lógica de tarifa vigente:** se estudió cómo consultar con Eloquent la tarifa
  activa a una fecha dada (`vigente_desde <= fecha AND (vigente_hasta IS NULL OR vigente_hasta >= fecha)`).
  Como resultado se creó un trait auxiliar `BuscaTarifaVigente` (**SCRUM-13, commit `9160c65`**)
  para no bloquear el avance del Sprint 2 mientras I1 entregaba su motor oficial.

### Entregables / artefactos
- Trait `app/Traits/BuscaTarifaVigente.php` (creado, luego quedó SUSPENDIDO — ver Sprint 2).
- `app/Traits/README.md` con la documentación del trait.
- Rama `SCRUM-13-tarifa-vigente-trait`.

### Complicaciones
- **Push rechazado (403):** el primer push de SCRUM-13 falló por un token de `gh`
  desactualizado. Se resolvió regenerando las credenciales con `gh` (helper global
  `!gh auth git-credential`) y re-pusheando. La rama quedó luego `[gone]` localmente
  porque el flujo del equipo integra vía PRs a `develop`.

---

## Sprint 2 — CRUD de Lecturas (index + store)

### Alcance
| # | Actividad | Puntos | Estado |
|---|-----------|--------|--------|
| 1 | `LecturaController::index()`: listar contadores pendientes de lectura del período actual con su última lectura registrada (o 0 si es la primera). | 1.5 | ✅ Completado |
| 2 | `LecturaController::store()`: validar `lectura_actual > lectura_anterior`, calcular consumo, buscar tarifa vigente (motor de I1), calcular monto y guardar Lectura con estado "pendiente". | 2 | ✅ Completado |

### Decisiones de negocio confirmadas (P1–P4)
Estas preguntas se elevaron al equipo (I1/I2) antes de implementar, para evitar
trabajar sobre supuestos incorrectos:

| ID | Pregunta | Respuesta | Quién |
|----|----------|-----------|-------|
| P1 | Valor del enum `estado_periodo` | `'ACTIVO'` / `'CERRADO'`, default `'ACTIVO'` (ya unificado en migración) | I1/I2 |
| P2 | ¿Qué tabla de usuarios usar? | `tb_usuarios` (esquema simplificado para Breeze: `id, name, email, password, role_id`); `usuario_id` de Lectura apunta ahí | I2 |
| P3 | ¿Qué tarifa aplica a cada contador? | Un solo tipo de tarifa activo a la vez; `tipo_tarifa_id` en `tb_tarifas` es solo catálogo. `tb_contadores` NO tendrá `tipo_tarifa_id` (fuera de alcance hasta post Demo Day) | I1/I2 |
| P4 | ¿Existe columna `estado` en Lectura? | NO. El estado "pendiente/pagada" se deriva de la existencia de un Pago (`tb_pagos.Estado_Pago`) | I1/I2 |

> **Consecuencia de P4:** el ticket decía "guardar Lectura con estado 'pendiente'",
> pero como no existe la columna `estado`, la lectura se guarda sin estado y
> `getEstadoPagoAttribute()` lo deriva dinámicamente (`PAGADO`/`PENDIENTE`).

### Implementación
- `app/Http/Controllers/LecturaController.php` — `index()` y `store()`.
- `app/Http/Requests/LecturaRequest.php` — validación con mensajes en español.
- `app/Models/Periodo.php` — nuevo, con `scopeActivo()` → `estado_periodo = 'ACTIVO'`.
- `app/Models/Lectura.php` — alineado con la versión final de develop (ver incidencia).
- `routes/web.php` — `Route::resource('lecturas', ...)->only(['index','store'])`.
- `app/Traits/README.md` — trait marcado como SUSPENDIDO/fallback.

**index():**
1. `Periodo::activo()->latest('fecha_apertura')->first()`; si no hay período activo, listado vacío.
2. Filtra contadores `activo_contador = 'ACTIVO'` con su cliente y sus lecturas (última primero).
3. Descarta los que ya tienen lectura en el período actual.
4. Mapea `contador_id`, `codigo_contador`, `cliente` y `lectura_anterior` (última lectura_actual o 0).

**store():**
1. Valida con `LecturaRequest`.
2. Busca la última lectura del contador → `lecturaAnterior` (0 si no hay).
3. Fuerza `lectura_actual > lectura_anterior` (si no, `ValidationException`).
4. Calcula consumo y monto con `Tarifa::vigenteEn(now())`; si no hay tarifa, error de validación amigable.
5. Genera `numero_recibo` (`REC-YYYYMMDD-NNNNN`), guarda con `usuario_id = auth()->id()`.
6. NO escribe `consumo` (columna generada en BD, storedAs) ni `estado` (P4).

### Incidencias / complicaciones (las importantes)

1. **Bloqueo de integración entre I1 e I2 por `Lectura::calcularMonto()`.**
   - I1 confirmó inicialmente que `Lectura::calcularMonto()` era el motor oficial de
     cálculo. El `store()` se escribió contra ese método.
   - Al preparar el merge, `origin/develop` (reescrito por I2/I4) **ya no tenía ese método**;
     solo existía `Tarifa::vigenteEn()`. Los PRs #11 y #12 quedaron en conflicto.
   - **Resolución:** se consultó al grupo por WhatsApp. **I2 respondió:** no se restaura
     `calcularMonto()`; el `store()` debe usar `Tarifa::vigenteEn()` directamente.
     Se adaptó el controlador y se registró la decisión en `app/Traits/README.md`.

2. **`numero_recibo`: ¿columna o accessor?**
   - I2/I4 lo habían implementado como accessor (`REC-` + id) y fuera del `$fillable`;
     el `store()` lo generaba y guardaba como columna (`REC-YYYYMMDD-NNNNN`).
   - **Resolución:** se usa la **columna** (agregada al `$fillable` por I2). El accessor
     queda como respaldo para vistas que no carguen la relación. Se mantuvo el formato de I3.

3. **FK `pago()`: `lectura_id` vs `lecturas_id`.**
   - La migración `tb_pagos` define la FK como **`lecturas_id`** (única). Un error en
     `Lectura.php` la apuntaba a `lectura_id`.
   - **Resolución:** corregido en `develop` por I2 (`pago()` y `Pago.php` usan `lecturas_id`).

4. **Conflictos de merge en los PRs #11 y #12.**
   - Se resolvió haciendo `git merge origin/develop` en cada rama. En `Lectura.php` se
     tomó la versión final de `develop` (I2/I4) como fuente de verdad.
   - Ambos PRs quedaron `MERGEABLE` y se mergearon en orden: **#11 (SCRUM-25)** y
     **#12 (SCRUM-26)**. `develop` local se sincronizó (`b072143`, `df21d6d`).

5. **Entorno: BD corrupta.**
   - La BD de XAMPP estaba dañada (assert de InnoDB). Se levantó una instancia **MariaDB
     portable en el puerto 3307** (`C:\Users\PREDATOR\AppData\Local\Temp\opencode\mariadb-data`)
     y el `.env` apunta a `DB_PORT=3307`. Migraciones 13/13 aplicadas correctamente.

### Pruebas (Tinker sobre BD local)
- `Periodo::activo()` → devuelve el período ACTIVO ✅
- `Tarifa::vigenteEn(now())` → tarifa vigente (monto_por_unidad) ✅
- Alta de lectura → `consumo` calculado por la BD (15.00 tras `refresh()`), recibo
  `REC-20260907-00001`, unique `(contador_id, periodo_id)` respetado ✅
- `index()` → contador con lectura en el período se excluye; contador sin lectura aparece con anterior=0 ✅

### Pendientes heredados
- **I4:** crear la vista `resources/views/lecturas/index.blade.php` (recibe `$periodo` y `$contadores`).

---

## Sprint 3 — Pagos (PagoController + flujo de recibo)

### Alcance
| # | Actividad | Puntos | Estado |
|---|-----------|--------|--------|
| 1 | `PagoController::store()`: recibir `lectura_id`, registrar Pago (monto, fecha, método) y actualizar lectura a "pagada". | 1.5 | ✅ Completado |
| 2 | Integrar el flujo: guardar lectura exitosa → redirigir a vista de recibo → enlace a registrar pago. Coordinar con I4 los nombres de las rutas. | 1 | ✅ En backend (vistas dependen de I4) |
| 3 | Pruebas manuales del módulo con datos del seeder: primera lectura (anterior=0), lectura normal, consumo=0. | — | ⏳ Parcial (backend validado; navegador requiere vistas de I4) |

### Decisiones de negocio confirmadas (S3-P1 a S3-P5)
| ID | Pregunta | Respuesta | Quién |
|----|----------|-----------|-------|
| S3-P1 | ¿`estado_pago` al crear? | Se setea `'PAGADO'` al crear, porque la Secretaria lo registra cuando el cliente ya pagó. No hay flujo de "pago pendiente". Fuente de verdad = registro en `tb_pagos`. | I2 |
| S3-P2 | ¿Ruta del recibo? | Se mantiene bajo el recurso `lecturas` (`lecturas.show`), sin ruta dedicada `recibos/` para no romper el formato existente. | I4 |
| S3-P3 | ¿Monto en el form? | Pre-llenado desde `lectura->monto`, pero editable (abono, redondeo, etc.). | I2 |
| S3-P4 | ¿Fecha de pago? | La selecciona el usuario (viene del `$request`), default hoy en el input. | I2 |
| S3-P5 | ¿Roles que registran pagos? | Solo `Administrador` y `Secretaria` (middleware). | I3 |

> **Aclaración de alcance (lectura.estado):** el ticket pedía "actualizar
> `lectura.estado` fi 'pagada'". Como `tb_lecturas` NO tiene columna `estado` (P4),
> la resolución del equipo fue modelar el estado en el Pago: al crearlo se setea
> `estado_pago = 'PAGADO'`, y el accessor de Lectura lo refleja automáticamente.

### Implementación
- `app/Http/Controllers/PagoController.php` — nuevo: `create()` y `store()`.
- `app/Http/Requests/PagoRequest.php` — validación con mensajes en español.
- `app/Http/Controllers/LecturaController.php` — agrega `show(Lectura $lectura)` (recibo);
  `store()` ahora redirige a `lecturas.show`.
- `routes/web.php` — `show` en el recurso `lecturas`; recurso `pagos` con
  `only(['create','store'])` y middleware `auth,role:Administrador,Secretaria`.
- `database/seeders/PagoSeeder.php` — nuevo: 2 pagos de prueba (lecturas 1 y 6, períodos cerrados).
- `database/seeders/DatabaseSeeder.php` — incluye `PagoSeeder`.
- `docs/documentation-I3.md` — bitácora Sprint 3.

**PagoController::create()**
1. Recibe `lectura_id` por query string; carga lectura con `contador`, `periodo`, `tarifa`.
2. Si la lectura ya tiene pago, redirige a `lecturas.show` con error.

**PagoController::store()**
1. Valida con `PagoRequest` (lectura existe, única, monto ≥ 0, fecha, método).
2. Doble chequeo: si la lectura ya tiene pago, vuelve atrás con error.
3. Crea el pago vía `$lectura->pago()->create(...)` con `estado_pago = 'PAGADO'`.
4. Redirige a `lecturas.show` con mensaje de éxito.

**LecturaController::show()** — carga `contador`, `periodo`, `tarifa`, `pago` y devuelve `lecturas.show`.

### Incidencias / complicaciones (las importantes)

1. **Ambigüedad "actualizar lectura.estado":** se preguntó al grupo si se setea
   `estado_pago = 'PAGADO'` al crear o si el estado se deriva solo de la existencia del
   registro. **I2:** se setea `'PAGADO'` al crear — no existe flujo de "pago pendiente
   de confirmar" en el prototipo. El accessor `getEstadoPagoAttribute()` queda como
   respaldo para vistas que no carguen la relación.

2. **Falla del `PagoSeeder` local (violación de FK):** al correr el seeder directo, la
   BD local solo tenía la lectura de prueba del Sprint 2 (id 1), así que el pago del
   `lecturas_id = 6` no encontraba su lectura.
   - **Resolución:** `php artisan migrate:fresh --seed` para poblar la BD con el seed
     completo de I2 (lecturas 1–10) + `PagoSeeder`. Migraciones 13/13 sin errores.

3. **Vistas inexistentes (dependencia de I4):** `lecturas/index`, `lecturas/show` y
   `pagos/create` no existen aún, por lo que el flujo no es navegable en el navegador.
   El backend está listo y validado; la prueba visual queda pendiente de las vistas de I4.

### Pruebas (Tinker sobre BD real, seed 13/13 + PagoSeeder)
- `migrate:fresh --seed` completo sin errores (incluye PagoSeeder) ✅
- L1 (con pago del seeder): `estado_pago = PAGADO` ✅
- L3 (sin pago): `estado_pago = PENDIENTE` ✅
- `$lectura->pago()->create([... estado_pago='PAGADO'])` → estado cambia a PAGADO ✅
- Validación `unique` en `lecturas_id`: rechaza pago duplicado ("Esta lectura ya tiene un pago registrado.") ✅
- Validación `monto_pago` negativo → rechazado ("El monto no puede ser negativo.") ✅
- Validación `metodo_pago` inválido (Cheque) → rechazado ("El método de pago debe ser Efectivo, Crédito o Débito.") ✅
- Validación `lecturas_id` inexistente → rechazado ("La lectura seleccionada no existe.") ✅
- `route:list` → rutas `lecturas.show`, `pagos.create`, `pagos.store` registradas ✅

### Pruebas manuales pendientes (requieren vistas de I4)
- Flujo en navegador: registrar lectura → recibo (`lecturas.show`) → enlace a
  `pagos.create?lectura_id={id}` → `pagos.store`.
- Primera lectura (anterior = 0), lectura normal, consumo = 0 (este último rechazado
  por la validación `lectura_actual > lectura_anterior`).

### Pendientes / dependencias
- **I4:** crear las vistas:
  - `resources/views/lecturas/show.blade.php` (recibo): número de recibo, cliente,
    contador, consumo, monto, estado y enlace a `pagos.create?lectura_id={id}`.
  - `resources/views/pagos/create.blade.php` (form): `monto_pago` pre-llenado y editable,
    `fecha_pago` default hoy, `metodo_pago` select (Efectivo/Credito/Débito), POST a `pagos.store`.
  - `resources/views/lecturas/index.blade.php` (Sprint 2, pendiente).
- **I2/I4:** actualizar el sidebar (`Lecturas` → `lecturas.index`, `Pagos` → por definir).

---

## Estado final del módulo (post-merge)

- **Línea base:** `develop` en `e997f1e` (Merge PR #13).
- **PRs mergeados:**
  - #11 — SCRUM-25 (LecturaController index) → `b072143`.
  - #12 — SCRUM-26 (LecturaController store) → `df21d6d`.
  - #13 — SCRUM-33 (PagoController store) → `e997f1e`.
- **Rutas activas:** `lecturas.index`, `lecturas.store`, `lecturas.show`,
  `pagos.create`, `pagos.store`.
- **Próximo sprint (4):** listado de recibos/pagos (index de pagos), y resolver con I4/I2
  las vistas pendientes y el sidebar.

---

## Notas de entorno (referencia rápida)

- BD: MariaDB portable, puerto **3307** (`C:\Users\PREDATOR\AppData\Local\Temp\opencode\mariadb-data`).
- Comandos útiles: `php artisan serve` (servidor), `php artisan tinker` (pruebas),
  `php artisan migrate:fresh --seed` (reset BD + seed).
- Usuarios del seeder (password `password`): `admin@hidrogest.test`,
  `secretaria@hidrogest.test`, `lector@hidrogest.test`.
- Flujo Git/Jira del equipo: rama `SCRUM-XX-descripcion-corta` + commit `SCRUM-XX: feat/fix/chore (descripción)`,
  integración vía PR a `develop` (protección: 1 review aprobando; admin hace bypass).