@extends('layouts.app')

@section('title', 'Registrar Pago - HidroGest')

@section('content')
<h1 class="mt-4">Registrar Pago</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="{{ route('lecturas.show', $lectura) }}">Recibo {{ $lectura->numero_recibo }}</a></li>
    <li class="breadcrumb-item active">Registrar Pago</li>
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
        <h5 class="card-title mb-3">Resumen del recibo</h5>
        <div class="row mb-2">
            <div class="col-6"><strong>Cliente:</strong> {{ $lectura->contador->cliente->nombre_completo ?? '—' }}</div>
            <div class="col-6"><strong>Contador:</strong> {{ $lectura->contador->codigo_contador }}</div>
        </div>
        <div class="row mb-4">
            <div class="col-6"><strong>Consumo:</strong> {{ $lectura->consumo }}</div>
            <div class="col-6"><strong>Monto a pagar:</strong> Q{{ number_format($lectura->monto, 2) }}</div>
        </div>

        <hr>

        <form method="POST" action="{{ route('pagos.store') }}">
            @csrf
            <input type="hidden" name="lecturas_id" value="{{ $lectura->id }}">
            <div class="mb-3">
                <label class="form-label">Monto pagado (Q)</label>
                <input type="number" step="0.01" min="0" class="form-control" name="monto_pago" value="{{ old('monto_pago', $lectura->monto) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Fecha de pago</label>
                <input type="date" class="form-control" name="fecha_pago" value="{{ old('fecha_pago', date('Y-m-d')) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Método de pago</label>
                <select class="form-select" name="metodo_pago" required>
                    <option value="">Seleccione un método...</option>
                    <option value="Efectivo" {{ old('metodo_pago') === 'Efectivo' ? 'selected' : '' }}>Efectivo</option>
                    <option value="Credito" {{ old('metodo_pago') === 'Credito' ? 'selected' : '' }}>Crédito</option>
                    <option value="Debito" {{ old('metodo_pago') === 'Debito' ? 'selected' : '' }}>Débito</option>
                </select>
            </div>
            <a href="{{ route('lecturas.show', $lectura) }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Confirmar Pago</button>
        </form>
    </div>
</div>
@endsection