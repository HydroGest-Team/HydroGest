<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contador extends Model
{
    protected $table = 'tb_contadores';

    protected $fillable = [
        'codigo_contador',
        'sector_contador',
        'activo_contador',
        'fecha_instalacion',
        'cliente_id',
    ];

    protected $casts = [
        'fecha_instalacion' => 'datetime',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function lecturas()
    {
        return $this->hasMany(Lectura::class, 'contador_id');
    }
}
