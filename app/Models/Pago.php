<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = ['lectura_id', 'monto', 'fecha_pago', 'metodo', 'registrado_por'];

    public function lectura()
    {
        return $this->belongsTo(Lectura::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }
}
