<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'tb_clientes';

    protected $fillable = [
        'dpi_cliente',
        'nombre1_cliente', 'nombre2_cliente', 'nombre3_cliente',
        'apellido1_cliente', 'apellido2_cliente', 'apellido3_cliente',
        'telefono_cliente', 'direccion_cliente',
        'numero_cuenta_cliente', 'activo_cliente',
    ];

    public function contadores()
    {
        return $this->hasMany(Contador::class, 'cliente_id');
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim(
            $this->nombre1_cliente . ' ' .
            $this->nombre2_cliente . ' ' .
            $this->apellido1_cliente . ' ' .
            $this->apellido2_cliente
        );
    }
}