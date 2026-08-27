# Traits — HidroGest

Esta carpeta contiene traits reutilizables que encapsulan lógica de negocio
compartida entre controladores.

## BuscaTarifaVigente.php

**Autor:** Integrante 3 (Backend Dev — Lecturas y Pagos)
**Sprint:** 1 (creado) / usado desde Sprint 2 en `LecturaController`
**Estado:** ⚠️ Provisional

### Qué hace

Busca la tarifa activa en una fecha dada, aplicando la regla de negocio:

```
vigente_desde <= fecha AND (vigente_hasta IS NULL OR vigente_hasta >= fecha)
```

### Por qué es "provisional"

Este trait fue creado por I3 para no bloquearse mientras espera el "motor de
cálculo" oficial que debe entregar I1 (Backend Lead), según la división de
tareas del equipo. Es funcional y probado, pero cuando I1 entregue su
versión (probablemente un `Service` o método en el modelo `Tarifa`), se debe:

1. Comparar ambas implementaciones.
2. Decidir en equipo cuál queda como fuente de verdad (evitar lógica duplicada).
3. Si se reemplaza, actualizar `LecturaController` para usar la versión de I1,
   y este archivo se puede eliminar o dejar como fallback documentado.

### Cómo usarlo

```php
use App\Traits\BuscaTarifaVigente;

class LecturaController extends Controller
{
    use BuscaTarifaVigente;

    public function store(Request $request)
    {
        $tarifa = $this->tarifaVigente(); // vigente hoy
        $tarifa = $this->tarifaVigente($request->fecha); // vigente a una fecha específica

        if (!$tarifa) {
            // No hay tarifa configurada para esa fecha — no se puede cerrar la lectura
            return back()->withErrors(['tarifa' => 'No hay tarifa vigente configurada para esta fecha.']);
        }
    }
}
```

### Cómo probarlo (Tinker)

```bash
php artisan tinker
```

```php
use App\Models\Tarifa;

// Crear una tarifa de prueba
Tarifa::create([
    'monto_por_unidad' => 5.50,
    'vigente_desde' => '2026-01-01',
    'vigente_hasta' => null,
]);

// Probar el trait de forma aislada
$t = new class { use App\Traits\BuscaTarifaVigente; };

$t->tarifaVigente();              // debe devolver la tarifa creada (hoy está dentro del rango)
$t->tarifaVigente('2025-01-01');  // debe devolver null (antes de vigente_desde)
```

### Dependencias

- Requiere que exista el modelo `App\Models\Tarifa` con los campos
  `monto_por_unidad`, `vigente_desde`, `vigente_hasta` (ya definidos por I2
  en `app/Models/Tarifa.php`).
- Si I2 cambia el esquema de tarifas (por ejemplo, a un modelo de tarifas por
  tipo de servicio + exceso), este trait deberá actualizarse en consecuencia.
