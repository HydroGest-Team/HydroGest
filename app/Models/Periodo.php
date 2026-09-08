<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    protected $table = 'tb_periodos';

    protected $fillable = [
        'fecha_apertura',
        'fecha_cierre',
        'estado_periodo',
    ];

    protected $casts = [
        'fecha_apertura' => 'datetime',
        'fecha_cierre'   => 'datetime',
    ];

    public function lecturas()
    {
        return $this->hasMany(Lectura::class, 'periodo_id');
    }

    /**
     * Scope para obtener el período actualmente abierto/activo.
     * El enum quedó unificado a 'ACTIVO' / 'CERRADO' en la migración.
     */
    public function scopeActivo($query)
    {
        return $query->where('estado_periodo', 'ACTIVO');
    }
}