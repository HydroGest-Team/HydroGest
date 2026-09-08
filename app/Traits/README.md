# Traits — HidroGest

Esta carpeta contiene traits reutilizables que encapsulan lógica de negocio
compartida entre controladores.

## BuscaTarifaVigente.php

**Autor:** Integrante 3 (Backend Dev — Lecturas y Pagos)
**Sprint:** 1 (creado) · Sprint 2 (usado como plan de contingencia)
**Estado:** ✅ SUSPENDIDO — reemplazado por el motor oficial de I1

### Estado actual (actualizado Sprint 2)

I1 entregó su motor de cálculo oficial y quedó como fuente de verdad:

- `App\Models\Tarifa::vigenteEn($fecha)` — busca la tarifa vigente a una fecha.
- `App\Models\Lectura::calcularMonto($lecturaAnterior, $lecturaActual, $fecha)` —
  calcula `consumo`, `monto` y `tarifa_id`.

`LecturaController` (Sprint 2) usa directamente `Lectura::calcularMonto()`, por lo
que este trait **ya no se usa**. Se conserva documentado como fallback en caso de
que algún día se quiera cambiar el motor y para dejar trazabilidad de la decisión.

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
2. Sprint 2: I1 entrega `Tarifa::vigenteEn()` + `Lectura::calcularMonto()`.
3. Sprint 2: se compara y se adopta el motor de I1 como fuente de verdad.
   `LecturaController` se escribe contra ese motor; el trait queda como fallback
   documentado. No hay lógica duplicada en producción.

### Dependencias (del motor oficial, no del trait)

- `App\Models\Tarifa` con `monto_por_unidad`, `vigente_desde`, `vigente_hasta`.
- `App\Models\TipoTarifa` (catálogo) unido con `tipo_tarifa_id` en `tb_tarifas`.