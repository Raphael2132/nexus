<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
    |--------------------------------------------------------------------------
    | Setores
    |--------------------------------------------------------------------------
    |
    |
    |
    */
    public function up(): void
    {
        Schema::create('parametros_srv_setores', function (Blueprint $table) {
            $table->id('setor_id');
            $table->string('setor_codigo', 6);
            $table->string('setor_empresa',6)->unsigned();
            $table->string('setor_area', 3);
            $table->string('setor_desc',40);
            $table->timestamps();
            $table->unique(['setor_codigo','setor_empresa','setor_area'], 'ak_parametros_srv_setores');
            $table->foreign('setor_empresa', 'fk_parametros_srv_setores')->references('empresa_codigo')->on('cadastro_empresas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros_srv_setores');
    }
};
