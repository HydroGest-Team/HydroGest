@extends('layouts.app')

@section('title', 'Bienvenida - HidroGest')

@section('content')
<h1 class="mt-4">¡Bienvenido, {{ $role === 'admin' ? 'Administrador' : ($role === 'secretaria' ? 'Secretaria' : 'Lector') }}!</h1>

@if ($role === 'admin')
<p>Tienes acceso completo al sistema: clientes, contadores, tarifas, lecturas, pagos y dashboard.</p>
<a href="{{ route('dashboard') }}" class="btn btn-primary">Ir al Dashboard</a>
@elseif ($role === 'secretaria')
<p>Puedes gestionar clientes, contadores y registrar pagos.</p>
<a href="#" class="btn btn-primary">Ver Clientes</a>
@else
<p>Puedes registrar lecturas de los contadores asignados.</p>
<a href="#" class="btn btn-primary">Registrar Lectura</a>
@endif
@endsection