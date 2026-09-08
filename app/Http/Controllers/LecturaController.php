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

    /**
     * Registra una nueva lectura: valida, calcula consumo y monto con el
     * motor de I1 (Lectura::calcularMonto) y guarda.
     *
     * NOTA (P4 confirmado): tb_lecturas NO tiene columna estado. El estado
     * "pendiente/pagada" se deriva de la existencia de un Pago asociado.
     */
    public function store(LecturaRequest $request)
    {
        $contador = Contador::findOrFail($request->contador_id);

        $ultimaLectura = Lectura::where('contador_id', $contador->id)
            ->latest('fecha_lectura')
            ->first();

        $lecturaAnterior = $ultimaLectura->lectura_actual ?? 0;

        if ($request->lectura_actual <= $lecturaAnterior) {
            throw ValidationException::withMessages([
                'lectura_actual' => "La lectura actual ({$request->lectura_actual}) debe ser mayor que la anterior ({$lecturaAnterior}).",
            ]);
        }

        try {
            $resultado = Lectura::calcularMonto($lecturaAnterior, $request->lectura_actual, now());
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'tarifa' => 'No hay una tarifa vigente configurada para esta fecha. Contacta al Administrador.',
            ]);
        }

        $lectura = Lectura::create([
            'numero_recibo'    => $this->generarNumeroRecibo(),
            'lectura_anterior' => $lecturaAnterior,
            'lectura_actual'   => $request->lectura_actual,
            'monto'            => $resultado['monto'],
            'fecha_lectura'    => now(),
            'tarifa_id'        => $resultado['tarifa_id'],
            'usuario_id'       => auth()->id(),
            'contador_id'      => $contador->id,
            'periodo_id'       => $request->periodo_id,
        ]);

        return redirect()->route('lecturas.index')
            ->with('success', "Lectura registrada. Consumo: {$resultado['consumo']}, Monto: Q{$resultado['monto']}.");
    }

    private function generarNumeroRecibo(): string
    {
        return 'REC-' . now()->format('Ymd') . '-' . str_pad((Lectura::max('id') + 1) ?? 1, 5, '0', STR_PAD_LEFT);
    }
}