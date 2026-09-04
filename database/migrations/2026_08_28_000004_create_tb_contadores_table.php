<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_contadores', function(Blueprint $table){
            $table->id();
            $table->string('codigo_contador', 20);
            $table->string('sector_contador', 50)->nullable();
            $table->enum('activo_contador', ['ACTIVO', 'NO ACTIVO'])->default('ACTIVO');
            $table->dateTime('fecha_instalacion')->nullable();
            $table->foreignId('cliente_id')->constrained('tb_clientes')->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_contadores');
    }
};