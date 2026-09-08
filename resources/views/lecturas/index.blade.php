@extends('layouts.app')

@section('title', 'Registrar Lectura - HidroGest')

@section('content')
<h1 class="mt-4">Registrar Lectura</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Lecturas</li>
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

@if (is_null($periodo))
<div class="alert alert-warning">
    No hay un período activo en este momento. Contacta al Administrador para abrir uno.
</div>
@elseif ($contadores->isEmpty())
<div class="alert alert-success">
    Todos los contadores ya tienen lectura registrada para este período.
</div>
@else
<p class="text-muted">Período activo: desde {{ $periodo->fecha_apertura->format('d/m/Y') }}</p>

<div class="row g-3">
    @foreach ($contadores as $contador)
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">{{ $contador['codigo_contador'] }}</h5>
                <p class="card-text text-muted mb-1">{{ $contador['cliente'] }}</p>
                <p class="card-text mb-3">
                    Lectura anterior: <strong>{{ $contador['lectura_anterior'] }}</strong>
                </p>
                <form method="POST" action="{{ route('lecturas.store') }}">
                    @csrf
                    <input type="hidden" name="contador_id" value="{{ $contador['contador_id'] }}">
                    <input type="hidden" name="periodo_id" value="{{ $periodo->id }}">
                    <div class="mb-3">
                        <label class="form-label">Lectura actual</label>
                        <input
                            type="number"
                            step="0.01"
                            class="form-control form-control-lg"
                            name="lectura_actual"
                            min="{{ $contador['lectura_anterior'] }}"
                            inputmode="decimal"
                            required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Registrar</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection