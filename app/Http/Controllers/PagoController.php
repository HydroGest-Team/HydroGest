<?php

namespace App\Http\Controllers;

use App\Http\Requests\PagoRequest;
use App\Models\Lectura;
use App\Models\Pago;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        $query = Pago::with(['lectura.contador.cliente'])
            ->orderByDesc('fecha_pago');

        if ($request->filled('cliente')) {
            $query->whereHas('lectura.contador.cliente', function ($q) use ($request) {
                $q->where('nombre1_cliente', 'like', '%' . $request->cliente . '%')
                  ->orWhere('apellido1_cliente', 'like', '%' . $request->cliente . '%');
            });
        }

        if ($request->filled('estado_pago')) {
            $query->where('estado_pago', $request->estado_pago);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_pago', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_pago', '<=', $request->fecha_hasta);
        }

        $pagos = $query->paginate(15)->withQueryString();

        return view('pagos.index', compact('pagos'));
    }

    public function create()
    {
        $lecturaId = request('lectura_id');

        $lectura = Lectura::with(['contador', 'periodo', 'tarifa'])
            ->findOrFail($lecturaId);

        if ($lectura->pago) {
            return redirect()->route('lecturas.show', $lectura)
                ->with('error', 'Esta lectura ya tiene un pago registrado.');
        }

        return view('pagos.create', compact('lectura'));
    }

    public function store(PagoRequest $request)
    {
        $lectura = Lectura::with('pago')->findOrFail($request->lecturas_id);

        if ($lectura->pago) {
            return back()->with('error', 'Esta lectura ya tiene un pago registrado.');
        }

        $lectura->pago()->create([
            'monto_pago'  => $request->monto_pago,
            'fecha_pago'  => $request->fecha_pago,
            'metodo_pago' => $request->metodo_pago,
            'estado_pago' => 'PAGADO',
            'usuario_id'  => auth()->id(),
        ]);

        return redirect()->route('lecturas.show', $lectura)
            ->with('success', 'Pago registrado correctamente.');
    }
}