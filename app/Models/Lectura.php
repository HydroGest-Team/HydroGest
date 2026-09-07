<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lectura extends Model
{
    protected $table = 'tb_lecturas';

    protected $fillable = [
        'contador_id',
        'periodo_id',
        'lectura_anterior',
        'lectura_actual',
        'tarifa_id',
        'monto',
        'fecha_lectura',
        'usuario_id',
    ];

    protected $casts = [
        'fecha_lectura'    => 'datetime',
        'lectura_anterior' => 'decimal:2',
        'lectura_actual'   => 'decimal:2',
        'consumo'          => 'decimal:2',
        'monto'            => 'decimal:2',
    ];

    public function contador()     { return $this->belongsTo(Contador::class, 'contador_id'); }
    public function periodo()      { return $this->belongsTo(Periodo::class, 'periodo_id'); }
    public function tarifa()       { return $this->belongsTo(Tarifa::class, 'tarifa_id'); }
    public function usuario()      { return $this->belongsTo(User::class, 'usuario_id'); }
    public function pago()         { return $this->hasOne(Pago::class, 'lectura_id'); }

    public function getNumeroReciboAttribute(): string
    {
        return 'REC-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    public function getEstadoPagoAttribute(): string
    {
        return $this->pago ? 'PAGADO' : 'PENDIENTE';
    }
}