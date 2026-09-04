<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contador extends Model
{
    protected $table = 'tb_contadores';

    protected $fillable = [
        'numero_contador',
        'tipo_contador',
        'marca_contador',
        'modelo_contador',
        'cliente_id',
    ];

    protected $cast = [
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
