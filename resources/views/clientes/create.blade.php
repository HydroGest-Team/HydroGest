@extends('layouts.app')

@section('title', 'Nuevo Cliente - HidroGest')

@section('content')
<h1 class="mt-4">Nuevo Cliente</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Clientes</a></li>
    <li class="breadcrumb-item active">Nuevo</li>
</ol>

<div class="card mb-4">
    <div class="card-body">
        <form method="POST" action="{{ route('clientes.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">DPI</label>
                <input type="text" class="form-control" name="dpi_cliente" maxlength="13" value="{{ old('dpi_cliente') }}" required>
            </div>
            <div class="row">
                <div class="col mb-3">
                    <label class="form-label">Primer nombre</label>
                    <input type="text" class="form-control" name="nombre1_cliente" value="{{ old('nombre1_cliente') }}" required>
                </div>
                <div class="col mb-3">
                    <label class="form-label">Segundo nombre</label>
                    <input type="text" class="form-control" name="nombre2_cliente" value="{{ old('nombre2_cliente') }}">
                </div>
            </div>
            <div class="row">
                <div class="col mb-3">
                    <label class="form-label">Primer apellido</label>
                    <input type="text" class="form-control" name="apellido1_cliente" value="{{ old('apellido1_cliente') }}" required>
                </div>
                <div class="col mb-3">
                    <label class="form-label">Segundo apellido</label>
                    <input type="text" class="form-control" name="apellido2_cliente" value="{{ old('apellido2_cliente') }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Teléfono</label>
                <input type="text" class="form-control" name="telefono_cliente" maxlength="8" value="{{ old('telefono_cliente') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Dirección</label>
                <input type="text" class="form-control" name="direccion_cliente" value="{{ old('direccion_cliente') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Número de cuenta</label>
                <input type="text" class="form-control" name="numero_cuenta_cliente" value="{{ old('numero_cuenta_cliente') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Estado</label>
                <select class="form-select" name="activo_cliente">
                    <option value="ACTIVO" {{ old('activo_cliente') === 'ACTIVO' ? 'selected' : '' }}>Activo</option>
                    <option value="NO ACTIVO" {{ old('activo_cliente') === 'NO ACTIVO' ? 'selected' : '' }}>No activo</option>
                </select>
            </div>
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
</div>
@endsection