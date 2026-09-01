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
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


Route::get('/bienvenida', function () {
    $role = auth()->user()->role->nombre_rol;
    return view('bienvenida', compact('role'));
})->middleware('auth')->name('bienvenida');

require __DIR__.'/auth.php';
