@extends('layouts.app')

@section('title', 'Contadores - HidroGest')

@section('content')
<h1 class="mt-4">Contadores</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Contadores</li>
</ol>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<button class="btn btn-primary mb-3" id="btnNuevoContador" data-bs-toggle="modal" data-bs-target="#contadorModal">
    <i class="fas fa-plus"></i> Nuevo Contador
</button>

<div class="card mb-4">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Sector</th>
                    <th>Cliente asignado</th>
                    <th>Fecha instalación</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($contadores as $contador)
                <tr>
                    <td>{{ $contador->codigo_contador }}</td>
                    <td>{{ $contador->sector_contador }}</td>
                    <td>{{ $contador->cliente->nombre_completo ?? '—' }}</td>
                    <td>{{ $contador->fecha_instalacion?->format('d/m/Y') ?? '—' }}</td>
                    <td>
                        @if ($contador->activo_contador === 'ACTIVO')
                        <span class="badge bg-success">Activo</span>
                        @else
                        <span class="badge bg-secondary">No activo</span>
                        @endif
                    </td>
                    <td>
                        <button
                            class="btn btn-sm btn-outline-primary btn-editar-contador"
                            data-bs-toggle="modal"
                            data-bs-target="#contadorModal"
                            data-id="{{ $contador->id }}"
                            data-codigo="{{ $contador->codigo_contador }}"
                            data-sector="{{ $contador->sector_contador }}"
                            data-fecha="{{ $contador->fecha_instalacion?->format('Y-m-d') }}"
                            data-cliente="{{ $contador->cliente_id }}">
                            Editar
                        </button>
                        <form action="{{ route('contadores.toggle', $contador->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-outline-warning">
                                {{ $contador->activo_contador === 'ACTIVO' ? 'Desactivar' : 'Activar' }}
                            </button>
                        </form>
                        <form action="{{ route('contadores.destroy', $contador->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este contador?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No hay contadores registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $contadores->links() }}
    </div>
</div>

{{-- Modal compartido para crear y editar --}}
<div class="modal fade" id="contadorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="contadorForm" method="POST">
                @csrf
                <div id="contadorMethodField"></div>
                <div class="modal-header">
                    <h5 class="modal-title" id="contadorModalTitle">Nuevo Contador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Código</label>
                        <input type="text" class="form-control" name="codigo_contador" id="contador_codigo" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sector</label>
                        <input type="text" class="form-control" name="sector_contador" id="contador_sector">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha de instalación</label>
                        <input type="date" class="form-control" name="fecha_instalacion" id="contador_fecha">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cliente</label>
                        <select class="form-select" name="cliente_id" id="contador_cliente" required>
                            <option value="">Seleccione un cliente...</option>
                            @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->nombre_completo }}</option>
                            @endforeach
                        </select>
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

@push('scripts')
<script>
    document.getElementById('btnNuevoContador').addEventListener('click', function() {
        document.getElementById('contadorModalTitle').innerText = 'Nuevo Contador';
        document.getElementById('contadorForm').action = "{{ route('contadores.store') }}";
        document.getElementById('contadorMethodField').innerHTML = '';
        document.getElementById('contadorForm').reset();
    });

    document.querySelectorAll('.btn-editar-contador').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const d = btn.dataset;
            document.getElementById('contadorModalTitle').innerText = 'Editar Contador';
            document.getElementById('contadorForm').action = "{{ url('contadores') }}/" + d.id;
            document.getElementById('contadorMethodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('contador_codigo').value = d.codigo;
            document.getElementById('contador_sector').value = d.sector;
            document.getElementById('contador_fecha').value = d.fecha;
            document.getElementById('contador_cliente').value = d.cliente;
        });
    });
</script>
@endpush
@endsection