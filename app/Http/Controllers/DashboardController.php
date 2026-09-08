<?php

namespace App\Http\Controllers;

use App\Models\Cliente;

class DashboardController extends Controller
{
    public function index()
    {
        $clientes = Cliente::with(['contadores.lecturas.pago'])
            ->orderBy('apellido1_cliente')
            ->get()
            ->map(function ($cliente) {
                $pendientes = $cliente->contadores->flatMap->lecturas->filter(
                    fn($lectura) => is_null($lectura->pago)
                )->count();

                $cliente->estado     = $pendientes > 0 ? 'PENDIENTE' : 'AL DÍA';
                $cliente->pendientes = $pendientes;
                return $cliente;
            });

        return view('dashboard', compact('clientes'));
    }
}