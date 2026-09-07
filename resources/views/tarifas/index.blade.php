@extends('layouts.app')

@section('title', 'Tarifas - HidroGest')

@section('content')
<h1 class="mt-4">Tarifas</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Tarifas</li>
</ol>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tarifaModal">
    <i class="fas fa-plus"></i> Nueva Tarifa
</button>

<div class="card mb-4">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Monto por unidad</th>
                    <th>Cantidad paja</th>
                    <th>Vigente desde</th>
                    <th>Vigente hasta</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tarifas as $tarifa)
                <tr>
                    <td>{{ $tarifa->tipoTarifa->nombre_tipo ?? '—' }}</td>
                    <td>Q{{ number_format($tarifa->monto_por_unidad, 2) }}</td>
                    <td>{{ $tarifa->cantidad_paja ?? '—' }}</td>
                    <td>{{ $tarifa->vigente_desde->format('d/m/Y') }}</td>
                    <td>{{ $tarifa->vigente_hasta?->format('d/m/Y') ?? '—' }}</td>
                    <td>
                        @if (is_null($tarifa->vigente_hasta))
                        <span class="badge bg-success">Vigente</span>
                        @else
                        <span class="badge bg-secondary">Vencida</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No hay tarifas registradas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $tarifas->links() }}
    </div>
</div>

{{-- Modal de nueva tarifa (sin edición: las tarifas son histórico inmutable) --}}
<div class="modal fade" id="tarifaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('tarifas.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Tarifa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning small">
                        Al guardar, la tarifa vigente actual de este tipo se cerrará automáticamente.
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo de tarifa</label>
                        <select class="form-select" name="tipo_tarifa_id" required>
                            <option value="">Seleccione un tipo...</option>
                            @foreach ($tipos as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->nombre_tipo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Monto por unidad (Q)</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="monto_por_unidad" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cantidad paja</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="cantidad_paja">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Vigente desde</label>
                        <input type="date" class="form-control" name="vigente_desde" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection