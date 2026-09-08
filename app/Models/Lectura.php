<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lectura extends Model
{
    use HasFactory;

    protected $table = 'tb_lecturas';

    protected $fillable = [
        'numero_recibo',
        'lectura_anterior',
        'lectura_actual',
        // 'consumo' NO va aquí: es columna generada (storedAs) en la migración.
        'monto',
        'fecha_lectura',
        'tarifa_id',
        'usuario_id',
        'contador_id',
        'periodo_id',
    ];

    protected $casts = [
        'lectura_anterior' => 'decimal:2',
        'lectura_actual'   => 'decimal:2',
        'consumo'          => 'decimal:2',
        'monto'            => 'decimal:2',
        'fecha_lectura'    => 'datetime',
    ];

    public function contador()
    {
        return $this->belongsTo(Contador::class, 'contador_id');
    }

    public function tarifa()
    {
        return $this->belongsTo(Tarifa::class, 'tarifa_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'periodo_id');
    }

    public function pago()
    {
        return $this->hasOne(Pago::class, 'lecturas_id');
    }

    public static function calcularMonto($lecturaAnterior, $lecturaActual, $fecha)
    {
        $consumo = $lecturaActual - $lecturaAnterior;

        $tarifa = Tarifa::vigenteEn($fecha);

        if (!$tarifa) {
            throw new \Exception('No existe una tarifa vigente para la fecha indicada.');
        }

        $monto = $consumo * $tarifa->monto_por_unidad;

        return [
            'consumo' => $consumo,
            'monto' => $monto,
            'tarifa_id' => $tarifa->id,
        ];
    }
}