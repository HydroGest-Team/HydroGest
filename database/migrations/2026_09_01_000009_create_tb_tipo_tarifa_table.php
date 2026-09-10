<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_tipo_tarifa', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_tipo', 50)->unique();
            $table->string('descripcion', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_tipo_tarifa');
    }
};