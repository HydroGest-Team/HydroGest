<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ContadorController;
use App\Http\Controllers\LecturaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\TarifaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'role:Administrador,Secretaria,Empleado'])->name('dashboard');

// TODO: reemplazar por esto cuando exista DashboardController:
// Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'role:Administrador,Secretaria,Empleado'])->name('dashboard');

Route::get('/bienvenida', function () {
    $role = auth()->user()->role->nombre_rol;
    return view('bienvenida', compact('role'));
})->middleware('auth')->name('bienvenida');

Route::middleware(['auth', 'role:Administrador,Secretaria'])->group(function () {
    Route::resource('clientes', ClienteController::class);
});

Route::middleware(['auth', 'role:Administrador,Secretaria,Empleado'])->group(function () {
    Route::resource('contadores', ContadorController::class)->parameters(['contadores' => 'contador']);
    Route::patch('contadores/{contador}/toggle', [ContadorController::class, 'toggleActivo'])->name('contadores.toggle');
});

Route::middleware(['auth', 'role:Administrador,Empleado'])->group(function () {
    Route::resource('tarifas', TarifaController::class)->only(['index', 'create', 'store', 'show']);
});

Route::middleware(['auth', 'role:Administrador,Secretaria,Empleado'])->group(function () {
    Route::resource('lecturas', LecturaController::class)->only(['index', 'store', 'show']);
});

Route::middleware(['auth', 'role:Administrador,Secretaria'])->group(function () {
    Route::resource('pagos', PagoController::class)->only(['create', 'store']);
});

require __DIR__ . '/auth.php';

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('pagos', PagoController::class)
    ->only(['index', 'create', 'store']);

require __DIR__.'/auth.php';