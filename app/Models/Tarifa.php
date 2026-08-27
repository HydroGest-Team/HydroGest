<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarifa extends Model
{
    use HasFactory;

    protected $fillable = ['monto_por_unidad', 'vigente_desde', 'vigente_hasta'];

    public function lecturas()
    {
        return $this->hasMany(Lectura::class);
    }
}
