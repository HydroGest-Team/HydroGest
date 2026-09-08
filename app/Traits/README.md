# Traits — HidroGest

Esta carpeta contiene traits reutilizables que encapsulan lógica de negocio
compartida entre controladores.

## BuscaTarifaVigente.php

**Autor:** Integrante 3 (Backend Dev — Lecturas y Pagos)
**Sprint:** 1 (creado) · Sprint 2 (usado como plan de contingencia)
**Estado:** ✅ SUSPENDIDO — reemplazado por el motor oficial de I1

### Estado actual (actualizado Sprint 2 — resolución I1/I2)

La fuente de verdad para el cálculo de lecturas es:

- `App\Models\Tarifa::vigenteEn($fecha)` — busca la tarifa vigente a una fecha.

`LecturaController::store()` (Sprint 2) usa **directamente** `Tarifa::vigenteEn()`
para calcular consumo/monto. El método `Lectura::calcularMonto()` se propuso
inicialmente como motor (I1) pero **no se implementó** en `develop`: I2/I4
mantienen el cálculo en el controlador con `Tarifa::vigenteEn()`. Este trait
**ya no se usa**; se conserva documentado como fallback en caso de que algún día
se quiera cambiar el motor y para dejar trazabilidad de la decisión.

Decisión de negocio (confirmada por I1/I2, P3): para el prototipo se asume **un solo
tipo de tarifa activo a la vez**; `tipo_tarifa_id` en `tb_tarifas` es solo
clasificación/catálogo y no participa en el cálculo. `tb_contadores` no tendrá
`tipo_tarifa_id` por ahora (fuera de alcance hasta después del Demo Day).

### Qué hacía

Buscaba la tarifa activa en una fecha dada aplicando la regla:

```
vigente_desde <= fecha AND (vigente_hasta IS NULL OR vigente_hasta >= fecha)
```

### Cómo se usaba (referencia histórica)

```php
$tarifa = $this->tarifaVigente();                 // vigente hoy
$tarifa = $this->tarifaVigente('2026-06-15');     // vigente a una fecha específica
```

### Historial de decisión

1. Sprint 1: I3 crea el trait para no bloquearse mientras no existía el motor de I1.
2. Sprint 2: I1/I2 definen `Tarifa::vigenteEn()` como fuente de verdad.
3. Sprint 2: se decide **no** crear `Lectura::calcularMonto()`; `LecturaController::store()`
   usa `Tarifa::vigenteEn()` directo (resolución I1/I2 del bloqueo de integración).
   El trait queda como fallback documentado. No hay lógica duplicada en producción.

### Dependencias (del motor oficial, no del trait)

- `App\Models\Tarifa` con `monto_por_unidad`, `vigente_desde`, `vigente_hasta`.
- `App\Models\TipoTarifa` (catálogo) unido con `tipo_tarifa_id` en `tb_tarifas`.