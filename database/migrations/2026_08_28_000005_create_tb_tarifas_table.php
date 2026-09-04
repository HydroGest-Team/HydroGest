<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_tarifas', function(Blueprint $table){
            $table->id();
            $table->decimal('monto_por_unidad', 10, 2);
            $table->datetime('vigente_desde');
            $table->datetime('vigente_hasta')->nullable();
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('tb_tarifas');
    }

};