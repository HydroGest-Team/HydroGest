<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoTarifa extends Model
{
    protected $table = 'tb_tipo_tarifa';

    protected $fillable = [
        'nombre_tipo',
        'descripcion',
    ];

    public function tarifas()
    {
        return $this->hasMany(Tarifa::class, 'tipo_tarifa_id');
    }
}