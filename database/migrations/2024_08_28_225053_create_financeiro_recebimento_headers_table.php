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
        Schema::create('financeiro_recebimento_headers', function (Blueprint $table) {
            $table->id('rechdr_id');
            $table->string('rechdr_emp',6);//Empresa
            $table->integer('rechdr_cod_rec');// Codigo do Recebimento -> sq_financeiro_recebimento_num 
            $table->enum('rechdr_sts',['A','F']);//Status A - Aberto F - Finalizado
            $table->enum('rechdr_ori',['NFV']);//Origem do recebimento NFV - Emissão de Nota Fiscal de Venda
            $table->date('rechdr_dti');//Data de Inclusão
            $table->string('rechdr_usu',6);//Usuario do recebimento
            $table->timestamps();
            $table->unique(['rechdr_emp','rechdr_cod_rec'], 'ak_financeiro_recebimento_headers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financeiro_recebimento_headers');
    }
};
