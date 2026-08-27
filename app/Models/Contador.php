<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contador extends Model
{
    use HasFactory;

    protected $fillable = ['cliente_id', 'codigo', 'sector', 'activo'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function lecturas()
    {
        return $this->hasMany(Lectura::class);
    }
}
