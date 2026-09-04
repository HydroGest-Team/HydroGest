@extends('layouts.app')

@section('title', 'Clientes - HidroGest')

@section('content')
<h1 class="mt-4">Clientes</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Clientes</li>
</ol>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<button class="btn btn-primary mb-3" id="btnNuevoCliente" data-bs-toggle="modal" data-bs-target="#clienteModal">
    <i class="fas fa-plus"></i> Nuevo Cliente
</button>

<div class="card mb-4">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nombre completo</th>
                    <th>DPI</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($clientes as $cliente)
                <tr>
                    <td>{{ $cliente->nombre_completo }}</td>
                    <td>{{ $cliente->dpi_cliente }}</td>
                    <td>{{ $cliente->telefono_cliente }}</td>
                    <td>
                        @if ($cliente->activo_cliente === 'ACTIVO')
                        <span class="badge bg-success">Activo</span>
                        @else
                        <span class="badge bg-secondary">No activo</span>
                        @endif
                    </td>
                    <td>
                        <button
                            class="btn btn-sm btn-outline-primary btn-editar-cliente"
                            data-bs-toggle="modal"
                            data-bs-target="#clienteModal"
                            data-id="{{ $cliente->id }}"
                            data-dpi="{{ $cliente->dpi_cliente }}"
                            data-nombre1="{{ $cliente->nombre1_cliente }}"
                            data-nombre2="{{ $cliente->nombre2_cliente }}"
                            data-nombre3="{{ $cliente->nombre3_cliente }}"
                            data-apellido1="{{ $cliente->apellido1_cliente }}"
                            data-apellido2="{{ $cliente->apellido2_cliente }}"
                            data-apellido3="{{ $cliente->apellido3_cliente }}"
                            data-telefono="{{ $cliente->telefono_cliente }}"
                            data-direccion="{{ $cliente->direccion_cliente }}"
                            data-cuenta="{{ $cliente->numero_cuenta_cliente }}"
                            data-activo="{{ $cliente->activo_cliente }}">
                            Editar
                        </button>
                        <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este cliente?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">No hay clientes registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $clientes->links() }}
    </div>
</div>

{{-- Modal compartido para crear y editar --}}
<div class="modal fade" id="clienteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="clienteForm" method="POST">
                @csrf
                <div id="clienteMethodField"></div>
                <div class="modal-header">
                    <h5 class="modal-title" id="clienteModalTitle">Nuevo Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">DPI</label>
                        <input type="text" class="form-control" name="dpi_cliente" id="cliente_dpi" maxlength="13" required>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label">Primer nombre</label>
                            <input type="text" class="form-control" name="nombre1_cliente" id="cliente_nombre1" required>
                        </div>
                        <div class="col mb-3">
                            <label class="form-label">Segundo nombre</label>
                            <input type="text" class="form-control" name="nombre2_cliente" id="cliente_nombre2">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label">Primer apellido</label>
                            <input type="text" class="form-control" name="apellido1_cliente" id="cliente_apellido1" required>
                        </div>
                        <div class="col mb-3">
                            <label class="form-label">Segundo apellido</label>
                            <input type="text" class="form-control" name="apellido2_cliente" id="cliente_apellido2">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" class="form-control" name="telefono_cliente" id="cliente_telefono" maxlength="8" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <input type="text" class="form-control" name="direccion_cliente" id="cliente_direccion">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Número de cuenta</label>
                        <input type="text" class="form-control" name="numero_cuenta_cliente" id="cliente_cuenta">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <select class="form-select" name="activo_cliente" id="cliente_activo">
                            <option value="ACTIVO">Activo</option>
                            <option value="NO ACTIVO">No activo</option>
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
    document.getElementById('btnNuevoCliente').addEventListener('click', function() {
        document.getElementById('clienteModalTitle').innerText = 'Nuevo Cliente';
        document.getElementById('clienteForm').action = "{{ route('clientes.store') }}";
        document.getElementById('clienteMethodField').innerHTML = '';
        document.getElementById('clienteForm').reset();
    });

    document.querySelectorAll('.btn-editar-cliente').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const d = btn.dataset;
            document.getElementById('clienteModalTitle').innerText = 'Editar Cliente';
            document.getElementById('clienteForm').action = "{{ url('clientes') }}/" + d.id;
            document.getElementById('clienteMethodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('cliente_dpi').value = d.dpi;
            document.getElementById('cliente_nombre1').value = d.nombre1;
            document.getElementById('cliente_nombre2').value = d.nombre2;
            document.getElementById('cliente_apellido1').value = d.apellido1;
            document.getElementById('cliente_apellido2').value = d.apellido2;
            document.getElementById('cliente_telefono').value = d.telefono;
            document.getElementById('cliente_direccion').value = d.direccion;
            document.getElementById('cliente_cuenta').value = d.cuenta;
            document.getElementById('cliente_activo').value = d.activo;
        });
    });
</script>
@endpush
@endsection