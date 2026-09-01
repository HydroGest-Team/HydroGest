<?php

use Illuminate\Support\Facades\Route;

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
