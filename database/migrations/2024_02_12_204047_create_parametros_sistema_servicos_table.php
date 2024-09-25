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
        Schema::create('parametros_sistema_servicos', function (Blueprint $table) {
            $table->id('servico_id');
            $table->integer('servico_grupo')->unsigned();
            $table->integer('servico_codigo');
            $table->string('servico_desc',800);
            $table->timestamps();
            $table->unique(['servico_grupo','servico_codigo'], 'ak_parametros_sistema_servicos');
            $table->foreign('servico_grupo', 'fk_parametros_sistema_servicos')->references('grupo_codigo')->on('parametros_sistema_servico_grupos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros_sistema_servicos');
    }
};
