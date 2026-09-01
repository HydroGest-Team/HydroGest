<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipo_Tarifa extends Model
{
    protected $table = 'tb_tipo_tarifas';

    protected $fillable = [
        'nombre_tipo_tarifa',
        'descripcion_tipo_tarifa',
    ];

    public function tarifas()
    {
        return $this->hasMany(Tarifa::class, 'tipo_tarifa_id');
    }
}