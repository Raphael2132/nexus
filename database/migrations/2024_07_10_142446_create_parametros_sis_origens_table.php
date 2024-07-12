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
        Schema::create('parametros_sis_origens', function (Blueprint $table) {
            $table->id('origem_id');
            $table->string('origem_codigo',2);//Código da Origem
            $table->string('origem_desc',80);//Descrição da Origem
            $table->enum('origem_categoria', ['P','S','O']); //Categoria da Origem P - Produtos, S - Serviços , O - Outros
            $table->enum('origem_tipo', ['C','V']); //Tipo da Origem Compra/Venda C - Compra, V - Venda
            $table->enum('origem_tipo_prod', ['C','F','R','T']); //Tipo da Origem dos Produtos C = Coligadas, F = Fabricante, R = Rede, T = Terceiros
            $table->enum('origem_tipo_venda', ['A','V']); //Tipo da Venda A - Atacado, V - Varejo
            $table->unique('origem_codigo', 'ak_parametros_sis_origens');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros_sis_origens');
    }
};
