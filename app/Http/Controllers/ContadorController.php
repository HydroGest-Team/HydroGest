<?php

namespace App\Http\Controllers;

use App\Models\Contador;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ContadorController extends Controller
{
    public function index()
    {
        $contadores = Contador::with('cliente')
            ->orderBy('codigo_contador')
            ->paginate(15);
        $clientes = Cliente::where('activo_cliente', 'ACTIVO')
            ->orderBy('apellido1_cliente')
            ->get();
        return view('contadores.index', compact('contadores', 'clientes'));
    }

    public function create()
    {
        $clientes = Cliente::where('activo_cliente', 'ACTIVO')
            ->orderBy('apellido1_cliente')
            ->get();
        return view('contadores.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo_contador'   => 'required|string|max:20|unique:tb_contadores,codigo_contador',
            'sector_contador'   => 'nullable|string|max:50',
            'fecha_instalacion' => 'nullable|date',
            'cliente_id'        => 'required|exists:tb_clientes,id',
        ]);

        Contador::create($request->all());

        return redirect()->route('contadores.index')
            ->with('success', 'Contador registrado correctamente.');
    }

    public function show(Contador $contador)
    {
        $contador->load('cliente', 'lecturas');
        return view('contadores.show', compact('contador'));
    }

    public function edit(Contador $contador)
    {
        $clientes = Cliente::where('activo_cliente', 'ACTIVO')
            ->orderBy('apellido1_cliente')
            ->get();
        return view('contadores.edit', compact('contador', 'clientes'));
    }

    public function update(Request $request, Contador $contador)
    {
        $request->validate([
            'codigo_contador'   => 'required|string|max:20|unique:tb_contadores,codigo_contador,' . $contador->id,
            'sector_contador'   => 'nullable|string|max:50',
            'fecha_instalacion' => 'nullable|date',
            'cliente_id'        => 'required|exists:tb_clientes,id',
        ]);

        $contador->update($request->all());

        return redirect()->route('contadores.index')
            ->with('success', 'Contador actualizado correctamente.');
    }

    public function toggleActivo(Contador $contador)
    {
        $contador->activo_contador = $contador->activo_contador === 'ACTIVO'
            ? 'NO ACTIVO'
            : 'ACTIVO';
        $contador->save();

        return redirect()->route('contadores.index')
            ->with('success', 'Estado del contador actualizado.');
    }

    public function destroy(Contador $contador)
    {
        if ($contador->lecturas()->exists()) {
            return redirect()->route('contadores.index')
                ->with('error', 'No se puede eliminar: el contador tiene lecturas registradas.');
        }

        $contador->delete();
        return redirect()->route('contadores.index')
            ->with('success', 'Contador eliminado correctamente.');
    }

    //funcion para exportar contadores
    public function export()
    {
        $contadores = Contador::with('cliente')->orderBy('codigo_contador')->get();
        $filename = 'contadores_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($contadores) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['Código', 'Sector', 'Cliente', 'Fecha instalación', 'Estado']);

            foreach ($contadores as $c) {
                fputcsv($handle, [
                    $c->codigo_contador,
                    $c->sector_contador,
                    $c->cliente->nombre_completo ?? '—',
                    $c->fecha_instalacion?->format('d/m/Y'),
                    $c->activo_contador,
                ]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
