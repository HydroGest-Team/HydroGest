<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_role', function (Blueprint $table){
            $table->id('rol_id');
            $table->enum('nombre_rol', ['Administrador', 'Secretaria', 'Empleado'])->unique();
            $table->string('descripcion_rol', 100)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('tb_roles');
    }
};