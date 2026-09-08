<?php

namespace App\Http\Controllers;

use App\Http\Requests\PagoRequest;
use App\Models\Lectura;

class PagoController extends Controller
{
    /**
     * Muestra el formulario para registrar un pago de una lectura específica.
     */
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

    /**
     * Registra un pago para una lectura. Setea estado_pago = 'PAGADO'.
     *
     * Decisión I1/I2: el pago se crea con estado 'PAGADO' porque la
     * Secretaria lo registra cuando el cliente ya pagó en ese momento.
     */
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
