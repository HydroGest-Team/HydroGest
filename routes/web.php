<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// TODO: temporal para preview de diseño — reemplazar $role por auth()->user()->role
Route::get('/bienvenida', function () {
    $role = request('rol', 'Administrador'); // Administrador, Secretaria o Empleado
    return view('bienvenida', compact('role'));
});
