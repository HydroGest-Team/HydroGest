<?php

namespace App\Traits;

use App\Models\Tarifa;
use Carbon\Carbon;

/**
 * Trait provisional para calcular la tarifa vigente a una fecha dada.
 *
 * NOTA: Esta lógica debería integrarse (o ser reemplazada) por el
 * motor de cálculo oficial que entregará I1 (Backend Lead / Scrum Master).
 * Mientras tanto, este trait permite avanzar sin bloquear el desarrollo
 * de LecturaController (Sprint 2).
 *
 * Uso:
 *   use App\Traits\BuscaTarifaVigente;
 *
 *   class LecturaController extends Controller
 *   {
 *       use BuscaTarifaVigente;
 *
 *       public function store(Request $request)
 *       {
 *           $tarifa = $this->tarifaVigente(); // tarifa vigente hoy
 *           $tarifa = $this->tarifaVigente('2026-06-15'); // tarifa vigente a una fecha específica
 *       }
 *   }
 *
 * @author Integrante 3 (Backend Dev - Lecturas y Pagos)
 */
trait BuscaTarifaVigente
{
    /**
     * Busca la tarifa vigente a una fecha dada.
     *
     * Regla de negocio:
     *   vigente_desde <= fecha  AND  (vigente_hasta IS NULL OR vigente_hasta >= fecha)
     *
     * @param  string|null  $fecha  Fecha en formato Y-m-d (o cualquier formato que Carbon entienda).
     *                               Si es null, usa la fecha/hora actual.
     * @return \App\Models\Tarifa|null  Devuelve null si no hay tarifa vigente a esa fecha.
     */
    public function tarifaVigente(?string $fecha = null): ?Tarifa
    {
        $fecha = $fecha ? Carbon::parse($fecha) : Carbon::now();

        return Tarifa::where('vigente_desde', '<=', $fecha)
            ->where(function ($query) use ($fecha) {
                $query->whereNull('vigente_hasta')
                      ->orWhere('vigente_hasta', '>=', $fecha);
            })
            ->orderBy('vigente_desde', 'desc')
            ->first();
    }
}
