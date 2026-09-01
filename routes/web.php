<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ContadorController;
use App\Http\Controllers\TarifaController;
use Illuminate\Support\Facades\Route;

Route::resource('clientes', ClienteController::class);
Route::resource('contadores', ContadorController::class)->parameters(['contadores' => 'contador']);
Route::patch('contadores/{contador}/toggle', [ContadorController::class, 'toggleActivo'])->name('contadores.toggle');
Route::resource('tarifas', TarifaController::class)->only(['index', 'create', 'store', 'show']);

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

Route::get('/bienvenida', function () {
    $role = auth()->user()->role->nombre;
    return view('bienvenida', compact('role'));
})->middleware('auth');

require __DIR__.'/auth.php';
