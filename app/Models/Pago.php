<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'tb_pagos';

    protected $fillable = [
        'lecturas_id',
        'monto_pago',
        'fecha_pago',
        'metodo_pago',
        'estado_pago',
        'usuario_id',
    ];

    protected $casts = [
        'fecha_pago' => 'datetime',
        'monto_pago' => 'decimal:2',
    ];

    public function lectura()
    {
        return $this->belongsTo(Lectura::class, 'lecturas_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}