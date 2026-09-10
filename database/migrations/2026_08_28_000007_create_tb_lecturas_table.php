<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_lecturas', function(Blueprint $table){
            $table->id();
            $table->string('numero_recibo',30)->unique();
            $table->decimal('lectura_anterior', 10, 2)->default(0);
            $table->decimal('lectura_actual', 10, 2);
            $table->decimal('consumo', 10, 2)->storedAs('lectura_actual - lectura_anterior');
            $table->decimal('monto', 10, 2);
            $table->dateTime('fecha_lectura');
            $table->foreignId('tarifa_id')->constrained('tb_tarifas')->onDelete('restrict');
            $table->foreignId('usuario_id')->constrained('tb_usuarios')->onDelete('restrict');
            $table->foreignId('contador_id')->constrained('tb_contadores')->onDelete('restrict');
            $table->foreignId('periodo_id')->constrained('tb_periodos')->onDelete('restrict');
            $table->timestamps();
            $table->unique(['contador_id', 'periodo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_lecturas');
    }
};