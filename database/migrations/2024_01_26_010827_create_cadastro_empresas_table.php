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
        Schema::create('cadastro_empresas', function (Blueprint $table) {
            $table->id('empresa_id');
            $table->string('empresa_codigo',6);
            $table->string('empresa_nome',80);
            $table->string('empresa_cnpj',14);
            $table->string('empresa_email', 80)->nullable();
            $table->string('empresa_tel_celular',11)->nullable();
            $table->string('empresa_tel_comercial',10)->nullable();
            $table->string('empresa_insc_estadual',14)->nullable();
            $table->string('empresa_insc_municipal', 15)->nullable();
            $table->timestamps();
            $table->unique('empresa_codigo', 'uk_cadastro_empresas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cadastro_empresas');
    }
};
