<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_usuarios', function (Blueprint $table){
            $table->id();
            $table->string('nombre1_usuario', 20);
            $table->string('nombre2_usuario', 20)->nullable();
            $table->string('nombre3_usuario', 20)->nullable();
            $table->string('apellido1_usuario', 20);
            $table->string('apellido2_usuario', 20)->nullable();
            $table->string('apellido3_usuario', 20)->nullable();
            $table->string('telefono_usuario', 8)->nullable();
            $table->string('email_usuario', 50)->unique();
            $table->string('direccion_usuario', 50)->nullable();
            $table->string('password_hash_usuario', 200);
            $table->foreignId('rol_id')->constrained('tb_roles')->onDelete('restrict');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('tb_usuarios');
    }
};