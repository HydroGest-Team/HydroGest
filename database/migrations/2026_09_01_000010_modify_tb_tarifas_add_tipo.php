<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_tarifas', function (Blueprint $table) {
            $table->decimal('cantidad_paja', 4, 1)->nullable();
            $table->foreignId('tipo_tarifa_id')
                  ->constrained('tb_tipo_tarifa')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('tb_tarifas', function (Blueprint $table) {
            $table->dropForeign(['tipo_tarifa_id']);
            $table->dropColumn(['tipo_tarifa_id', 'cantidad_paja']);
        });
    }
};