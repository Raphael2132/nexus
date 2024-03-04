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
        Schema::create('parametros_sistema_modulos', function (Blueprint $table) {
            $table->id('modulo_id');
            $table->string('modulo_empresa_codigo',6)->unsigned()->unique();
            $table->enum('modulo_emissao_nfs', ['S', 'N'])->default('N');
            $table->enum('modulo_emissao_nfe', ['S', 'N'])->default('N');
            $table->timestamps();
            $table->foreign('modulo_empresa_codigo', 'fk_parametros_sistema_modulos')->references('empresa_codigo')->on('cadastro_empresas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros_sistema_modulos');
    }
};
