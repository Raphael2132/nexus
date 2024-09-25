<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parametros_sis_servico_grupos', function (Blueprint $table) {
            $table->id('grupo_id');
            $table->integer('grupo_codigo')->unique();
            $table->string('grupo_desc',800);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros_sis_servico_grupos');
    }
};
