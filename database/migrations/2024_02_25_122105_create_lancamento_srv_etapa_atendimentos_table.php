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
        Schema::create('lancamento_srv_etapa_atendimentos', function (Blueprint $table) {
            $table->id('eat_id');
            $table->integer('eat_cod');
            $table->string('eat_emp', 6)->unsigned();
            $table->string('eat_nom', 80);
            $table->integer('eat_ord');
            //$table->string('eat_tos', 2);
            //$table->string('eat_set', 6);
            $table->string('eat_cat', 1);
            $table->string('eat_are', 3);
            $table->timestamps();
            $table->unique(['eat_cod','eat_emp'], 'ak_lancamento_srv_etapas');
            $table->foreign('eat_emp', 'fk_lancamento_srv_etapa_atendimentos')->references('empresa_codigo')->on('cadastro_empresas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lancamento_srv_etapa_atendimentos');
    }
};
