<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarifa extends Model
{
    protected $table = 'tb_tarifas';

    protected $fillable = [
        'monto_por_unidad', 
        'cantidad_paja', 
        'vigente_desde', 
        'vigente_hasta', 
        'tipo_tarifa_id',
    ];

    protected $casts = [
        'vigente_desde' => 'datetime',
        'vigente_hasta' => 'datetime',
    ];

    public function tipoTarifa()
    {
        return $this->belongsTo(TipoTarifa::class, 'tipo_tarifa_id');
    }

    public function lecturas()
    {
        return $this->hasMany(Lectura::class, 'tarifa_id');
    }

    public static function vigente()
    {
        return static::whereNull('vigente_hasta')->latest('vigente_hasta')->first();
    }
}
