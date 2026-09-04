<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lectura extends Model
{
    use HasFactory;

    protected $fillable = [
        'contador_id',
        'tarifa_id',
        'lectura_anterior',
        'lectura_actual',
        'consumo',
        'monto',
        'fecha',
        'registrado_por',
        'estado',
    ];

    public function contador()
    {
        return $this->belongsTo(Contador::class);
    }

    public function tarifa()
    {
        return $this->belongsTo(Tarifa::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    public function pago()
    {
        return $this->hasOne(Pago::class);
    }
}
