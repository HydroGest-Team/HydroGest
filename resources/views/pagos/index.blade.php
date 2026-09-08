@extends('layouts.app')

@section('title', 'Pagos - HidroGest')

@section('content')
<h1 class="mt-4">Pagos</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Pagos</li>
</ol>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('pagos.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Cliente</label>
                <input type="text" class="form-control" name="cliente" value="{{ request('cliente') }}" placeholder="Buscar por nombre...">
            </div>
            <div class="col-md-2">
                <label class="form-label">Estado</label>
                <select class="form-select" name="estado_pago">
                    <option value="">Todos</option>
                    <option value="PAGADO" {{ request('estado_pago') === 'PAGADO' ? 'selected' : '' }}>Pagado</option>
                    <option value="NO PAGADO" {{ request('estado_pago') === 'NO PAGADO' ? 'selected' : '' }}>No pagado</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Desde</label>
                <input type="date" class="form-control" name="fecha_desde" value="{{ request('fecha_desde') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Hasta</label>
                <input type="date" class="form-control" name="fecha_hasta" value="{{ request('fecha_hasta') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary">Limpiar</a>
            </div>
        </form>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Contador</th>
                    <th>Monto</th>
                    <th>Fecha de pago</th>
                    <th>Método</th>
                    <th>Estado</th>
                    <th>Recibo</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pagos as $pago)
                <tr>
                    <td>{{ $pago->lectura->contador->cliente->nombre_completo ?? '—' }}</td>
                    <td>{{ $pago->lectura->contador->codigo_contador ?? '—' }}</td>
                    <td>Q{{ number_format($pago->monto_pago, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
                    <td>{{ $pago->metodo_pago }}</td>
                    <td>
                        @if ($pago->estado_pago === 'PAGADO')
                        <span class="badge bg-success">Pagado</span>
                        @else
                        <span class="badge bg-warning text-dark">No pagado</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('lecturas.show', $pago->lectura_id ?? $pago->lecturas_id) }}" class="btn btn-sm btn-outline-primary">Ver recibo</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No hay pagos registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $pagos->appends(request()->query())->links() }}
    </div>
</div>
@endsection