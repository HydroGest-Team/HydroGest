<?php

namespace App\Http\Controllers;

use App\Models\Tarifa;
use App\Models\TipoTarifa;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TarifaController extends Controller
{
    public function index()
    {
        $tarifas = Tarifa::with('tipoTarifa')
            ->orderByDesc('vigente_desde')
            ->paginate(15);
        $tipos   = TipoTarifa::orderBy('nombre_tipo')->get();
        return view('tarifas.index', compact('tarifas', 'tipos'));
    }

    public function create()
    {
        $tipos = TipoTarifa::orderBy('nombre_tipo')->get();
        return view('tarifas.create', compact('tipos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'monto_por_unidad' => 'required|numeric|min:0',
            'cantidad_paja'    => 'nullable|numeric|min:0',
            'tipo_tarifa_id'   => 'required|exists:tb_tipo_tarifa,id',
            'vigente_desde'    => 'required|date',
        ]);

        // Cierra la tarifa anterior del mismo tipo
        Tarifa::where('tipo_tarifa_id', $request->tipo_tarifa_id)
            ->whereNull('vigente_hasta')
            ->update(['vigente_hasta' => Carbon::today()]);

        Tarifa::create([
            'monto_por_unidad' => $request->monto_por_unidad,
            'cantidad_paja'    => $request->cantidad_paja,
            'tipo_tarifa_id'   => $request->tipo_tarifa_id,
            'vigente_desde'    => $request->vigente_desde,
            'vigente_hasta'    => null,
        ]);

        return redirect()->route('tarifas.index')
            ->with('success', 'Tarifa registrada. La tarifa anterior fue cerrada automáticamente.');
    }

    public function show(Tarifa $tarifa)
    {
        $tarifa->load('tipoTarifa');
        return view('tarifas.show', compact('tarifa'));
    }
}

//funcion para exportar tarifas

public function export()
{
    $tarifas = Tarifa::with('tipoTarifa')->orderByDesc('vigente_desde')->get();
    $filename = 'tarifas_' . now()->format('Ymd_His') . '.csv';

    return response()->streamDownload(function () use ($tarifas) {
        $handle = fopen('php://output', 'w');
        fputs($handle, "\xEF\xBB\xBF");
        fputcsv($handle, ['Tipo', 'Monto por unidad', 'Cantidad paja', 'Vigente desde', 'Vigente hasta', 'Estado']);

        foreach ($tarifas as $t) {
            fputcsv($handle, [
                $t->tipoTarifa->nombre_tipo ?? '—',
                number_format($t->monto_por_unidad, 2),
                $t->cantidad_paja,
                $t->vigente_desde->format('d/m/Y'),
                $t->vigente_hasta?->format('d/m/Y'),
                is_null($t->vigente_hasta) ? 'Vigente' : 'Vencida',
            ]);
        }
        fclose($handle);
    }, $filename, ['Content-Type' => 'text/csv']);
}