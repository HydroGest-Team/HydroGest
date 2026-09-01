<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ContadorController;
use Illuminate\Support\Facades\Route;

Route::resource('clientes', ClienteController::class);
Route::resource('contadores', ContadorController::class)->parameters(['contadores' => 'contador']);
Route::patch('contadores/{contador}/toggle', [ContadorController::class, 'toggleActivo'])->name('contadores.toggle');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// ruta temporal para el login
Route::get('/login', function () {
    return view('auth.login');
});

// TODO: temporal para preview de diseño — I1 reemplazará $role por auth()->user()->role al conectar el login real
Route::get('/bienvenida', function () {
    $role = request('rol', 'admin');
    return view('bienvenida', compact('role'));
});
