@extends('layouts.app')

@section('title', 'Recibo - HidroGest')

@push('styles')
<style>
    @media print {

        #layoutSidenav_nav,
        nav.sb-topnav,
        #layoutAuthentication_footer,
        footer,
        .d-print-none {
            display: none !important;
        }

        #layoutSidenav_content {
            margin: 0 !important;
        }

        .container-fluid {
            padding: 0 !important;
        }
    }
</style>
@endpush

@section('content')
<div class="d-print-none d-flex justify-content-between align-items-center mt-4 mb-4">
    <h1>Recibo</h1>
    <div>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Imprimir
        </button>
        @if (!$lectura->pago)
        <a href="{{ route('pagos.create', ['lectura_id' => $lectura->id]) }}" class="btn btn-success">
            Registrar Pago
        </a>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <div class="text-center mb-4">
            <h3>HidroGest</h3>
            <p class="text-muted mb-0">Sistema de Gestión de Agua Potable</p>
            <hr>
        </div>

        <div class="row mb-3">
            <div class="col-6">
                <strong>Recibo No:</strong> {{ $lectura->numero_recibo }}
            </div>
            <div class="col-6 text-end">
                <strong>Fecha:</strong> {{ $lectura->fecha_lectura->format('d/m/Y') }}
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <strong>Cliente:</strong> {{ $lectura->contador->cliente->nombre_completo ?? '—' }}
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-6">
                <strong>Contador:</strong> {{ $lectura->contador->codigo_contador }}
            </div>
            <div class="col-6 text-end">
                <strong>Período:</strong> {{ $lectura->periodo->fecha_apertura->format('d/m/Y') }}
                @if ($lectura->periodo->fecha_cierre)
                – {{ $lectura->periodo->fecha_cierre->format('d/m/Y') }}
                @endif
            </div>
        </div>

        <table class="table table-bordered mt-4">
            <thead class="table-light">
                <tr>
                    <th>Lectura anterior</th>
                    <th>Lectura actual</th>
                    <th>Consumo</th>
                    <th>Tarifa aplicada</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $lectura->lectura_anterior }}</td>
                    <td>{{ $lectura->lectura_actual }}</td>
                    <td>{{ $lectura->consumo }}</td>
                    <td>Q{{ number_format($lectura->tarifa->monto_por_unidad, 2) }} / unidad</td>
                    <td>Q{{ number_format($lectura->monto, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="row mt-4">
            <div class="col-6">
                <strong>Estado:</strong>
                @if ($lectura->estado_pago === 'PAGADO')
                <span class="badge bg-success">Pagado</span>
                @else
                <span class="badge bg-warning text-dark">Pendiente</span>
                @endif
            </div>
            <div class="col-6 text-end">
                <h5>Total: Q{{ number_format($lectura->monto, 2) }}</h5>
            </div>
        </div>

        @if ($lectura->pago)
        <hr>
        <div class="row">
            <div class="col-12">
                <small class="text-muted">
                    Pagado el {{ \Carbon\Carbon::parse($lectura->pago->fecha_pago)->format('d/m/Y') }}
                    vía {{ $lectura->pago->metodo_pago }}.
                </small>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection