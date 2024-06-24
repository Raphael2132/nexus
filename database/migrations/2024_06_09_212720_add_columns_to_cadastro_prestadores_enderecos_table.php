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
        Schema::table('cadastro_prestadores_enderecos', function (Blueprint $table) {
            $table->integer('endereco_ibge_cod_mun')->nullable();//Código IBGE do Municipio
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cadastro_prestadores_enderecos', function (Blueprint $table) {
            $table->dropColumn('endereco_ibge_cod_mun');
        });
    }
};
