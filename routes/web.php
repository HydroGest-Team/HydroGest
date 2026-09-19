<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ContadorController;
use App\Http\Controllers\LecturaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\TarifaController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'role:Administrador,Secretaria,Empleado'])
    ->name('dashboard');

Route::get('/bienvenida', function () {
    $role = auth()->user()->role->nombre_rol;
    return view('bienvenida', compact('role'));
})->middleware('auth')->name('bienvenida');

Route::middleware(['auth', 'role:Administrador,Secretaria'])->group(function () {
    Route::get('clientes/export', [ClienteController::class, 'export'])->name('clientes.export');
    Route::resource('clientes', ClienteController::class);
});

Route::middleware(['auth', 'role:Administrador,Secretaria,Empleado'])->group(function () {
    Route::get('contadores/export', [ContadorController::class, 'export'])->name('contadores.export');
    Route::resource('contadores', ContadorController::class)->parameters(['contadores' => 'contador']);
    Route::patch('contadores/{contador}/toggle', [ContadorController::class, 'toggleActivo'])->name('contadores.toggle');
});

Route::middleware(['auth', 'role:Administrador,Empleado'])->group(function () {
    Route::get('tarifas/export', [TarifaController::class, 'export'])->name('tarifas.export');
    Route::resource('tarifas', TarifaController::class)->only(['index', 'create', 'store', 'show']);
});

Route::middleware(['auth', 'role:Administrador,Secretaria,Empleado'])->group(function () {
    Route::get('lecturas/export', [LecturaController::class, 'export'])->name('lecturas.export');
    Route::resource('lecturas', LecturaController::class)->only(['index', 'store', 'show']);
});

Route::middleware(['auth', 'role:Administrador,Secretaria'])->group(function () {
    Route::get('pagos/export', [PagoController::class, 'export'])->name('pagos.export');
    Route::resource('pagos', PagoController::class)->only(['index', 'create', 'store']);
});

require __DIR__.'/auth.php';