@extends('layouts.app')

@section('title', 'Dashboard - HidroGest')

@section('content')
<h1 class="mt-4">Dashboard</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Dashboard</li>
</ol>

{{-- Tarjetas de resumen --}}
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col me-2">
                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Clientes</div>
                        <div class="h5 mb-0 fw-bold text-gray-800">{{ $clientes->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col me-2">
                        <div class="text-xs fw-bold text-success text-uppercase mb-1">Al día</div>
                        <div class="h5 mb-0 fw-bold text-gray-800">{{ $clientes->where('estado', 'Al día')->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col me-2">
                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">Pendientes</div>
                        <div class="h5 mb-0 fw-bold text-gray-800">{{ $clientes->where('estado', 'Pendiente')->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col me-2">
                        <div class="text-xs fw-bold text-info text-uppercase mb-1">Fecha</div>
                        <div class="h5 mb-0 fw-bold text-gray-800">{{ now()->format('d/m/Y') }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filtros + tabla --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span class="fw-bold">Clientes</span>
        <div class="d-flex gap-2">
            <input type="text" id="filtroNombre" class="form-control form-control-sm" placeholder="Buscar por nombre..." style="width: 220px;">
            <select id="filtroEstado" class="form-select form-select-sm" style="width: 160px;">
                <option value="">Todos los estados</option>
                <option value="Al día">Al día</option>
                <option value="Pendiente">Pendiente</option>
            </select>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-hover" id="tablaClientes">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($clientes as $cliente)
                <tr data-nombre="{{ strtolower($cliente->nombre_completo) }}" data-estado="{{ $cliente->estado }}">
                    <td>{{ $cliente->nombre_completo }}</td>
                    <td>{{ $cliente->telefono_cliente }}</td>
                    <td>
                        @if ($cliente->estado === 'Al día')
                        <span class="badge bg-success">Al día</span>
                        @else
                        <span class="badge bg-danger">Pendiente</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">No hay clientes registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <p id="sinResultados" class="text-center text-muted d-none">No se encontraron clientes con ese filtro.</p>
    </div>
</div>

@push('scripts')
<script>
    const filtroNombre = document.getElementById('filtroNombre');
    const filtroEstado = document.getElementById('filtroEstado');
    const filas = document.querySelectorAll('#tablaClientes tbody tr[data-nombre]');
    const sinResultados = document.getElementById('sinResultados');

    function aplicarFiltro() {
        const nombre = filtroNombre.value.toLowerCase();
        const estado = filtroEstado.value;
        let visibles = 0;

        filas.forEach(function(fila) {
            const coincideNombre = fila.dataset.nombre.includes(nombre);
            const coincideEstado = estado === '' || fila.dataset.estado === estado;
            const mostrar = coincideNombre && coincideEstado;
            fila.classList.toggle('d-none', !mostrar);
            if (mostrar) visibles++;
        });

        sinResultados.classList.toggle('d-none', visibles > 0);
    }

    filtroNombre.addEventListener('input', aplicarFiltro);
    filtroEstado.addEventListener('change', aplicarFiltro);
</script>
@endpush
@endsection