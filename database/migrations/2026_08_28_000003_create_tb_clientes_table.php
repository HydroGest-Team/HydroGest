<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_clientes', function(Blueprint $table){
            $table->id('cliente_id');
            $table->string('dpi_cliente', 13)->unique();
            $table->string('nombre1_cliente', 20);
            $table->string('nombre2_cliente', 20)->nullable();
            $table->string('nombre3_cliente', 20)->nullable();
            $table->string('apellido1_cliente', 20);
            $table->string('apellido2_cliente', 20)->nullable();
            $table->string('apellido3_cliente', 20)->nullable();
            $table->string('telefono_cliente', 8)->nullable();
            $table->string('direccion_cliente', 50)->nullable();
            $table->string('numero_cuenta_cliente', 20)->nullable();
            $table->enum('activo_cliente', ['Activo', 'Inactivo'])->default('Activo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_clientes');
    }
};