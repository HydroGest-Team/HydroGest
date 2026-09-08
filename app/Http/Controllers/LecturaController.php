<?php

namespace App\Http\Controllers;

use App\Http\Requests\LecturaRequest;
use App\Models\Contador;
use App\Models\Lectura;
use App\Models\Periodo;
use Illuminate\Validation\ValidationException;

class LecturaController extends Controller
{
    /**
     * Lista los contadores ACTIVOS pendientes de lectura en el período actual,
     * con su última lectura registrada (o 0 si es la primera vez).
     */
    public function index()
    {
        $periodo = Periodo::activo()->latest('fecha_apertura')->first();

        if (!$periodo) {
            return view('lecturas.index', [
                'periodo'    => null,
                'contadores' => collect(),
            ]);
        }

        $contadores = Contador::where('activo_contador', 'ACTIVO')
            ->with(['cliente', 'lecturas' => function ($query) {
                $query->latest('fecha_lectura');
            }])
            ->get()
            ->filter(function ($contador) use ($periodo) {
                // Pendiente = aún no tiene lectura en el período actual.
                return !$contador->lecturas->contains('periodo_id', $periodo->id);
            })
            ->map(function ($contador) {
                $ultimaLectura = $contador->lecturas->first();

                return [
                    'contador_id'      => $contador->id,
                    'codigo_contador'  => $contador->codigo_contador,
                    'cliente'          => $contador->cliente->nombre_completo ?? 'Sin cliente',
                    'lectura_anterior' => $ultimaLectura->lectura_actual ?? 0,
                ];
            })
            ->values();

        return view('lecturas.index', compact('periodo', 'contadores'));
    }
}