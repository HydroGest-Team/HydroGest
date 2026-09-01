<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_usuarios', function (Blueprint $table) {
    $table->id();
    $table->string('name', 100);
    $table->string('email', 50)->unique();
    $table->string('password', 200);
    $table->foreignId('role_id')->constrained('tb_roles')->onDelete('restrict');
    $table->timestamps();
    });
    }
    public function down(): void
    {
        Schema::dropIfExists('tb_usuarios');
    }
};