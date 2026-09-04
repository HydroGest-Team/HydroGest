@extends('layouts.app')

@section('title', 'Editar Contador - HidroGest')

@section('content')
<h1 class="mt-4">Editar Contador</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="{{ route('contadores.index') }}">Contadores</a></li>
    <li class="breadcrumb-item active">Editar</li>
</ol>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card mb-4">
    <div class="card-body">
        <form method="POST" action="{{ route('contadores.update', $contador->id) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Código</label>
                <input type="text" class="form-control" name="codigo_contador" value="{{ old('codigo_contador', $contador->codigo_contador) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Sector</label>
                <input type="text" class="form-control" name="sector_contador" value="{{ old('sector_contador', $contador->sector_contador) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Fecha de instalación</label>
                <input type="date" class="form-control" name="fecha_instalacion" value="{{ old('fecha_instalacion', $contador->fecha_instalacion?->format('Y-m-d')) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Cliente</label>
                <select class="form-select" name="cliente_id" required>
                    @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}" {{ old('cliente_id', $contador->cliente_id) == $cliente->id ? 'selected' : '' }}>
                        {{ $cliente->nombre_completo }}
                    </option>
                    @endforeach
                </select>
            </div>
            <a href="{{ route('contadores.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </form>
    </div>
</div>
@endsection