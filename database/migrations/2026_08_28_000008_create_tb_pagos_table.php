<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_pagos', function (Blueprint $table) {
            $table->id('pago_id');
            $table->decimal('monto_pago', 10, 2);
            $table->dateTime('fecha_pago');
            $table->enum('metodo_pago', ['Efectivo', 'Credito', 'Debito']);
            $table->enum('estado_pago', ['PAGADO', 'NO PAGADO'])->default('NO PAGADO');

            $table->foreignId('lecturas_id')
                  ->unique()
                  ->constrained('tb_lecturas', 'lecturas_id')
                  ->restrictOnDelete();

            $table->foreignId('usuario_id')
                  ->constrained('tb_usuarios', 'usuario_id')
                  ->restrictOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_pagos');
    }
};