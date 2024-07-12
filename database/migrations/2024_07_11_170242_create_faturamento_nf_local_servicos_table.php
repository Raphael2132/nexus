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
        Schema::create('faturamento_nf_local_servicos', function (Blueprint $table) {
            $table->id('nflocsrv_id');
            $table->string('nflocsrv_emp',6); // Empresa emitente 
            $table->integer('nflocsrv_num'); // Numero de controle
            $table->enum('nflocsrv_tip_reg', ['L'])->default('L'); // Tipo de Registro ( Local )
            $table->enum('nflocsrv_loc_srv',['E','C','O'])->default('E');//Endereço do local da prestação do serviço E - Empresa, C - Cliente, O - Outro
            $table->string('nflocsrv_cep',8)->nullable();
            $table->string('nflocsrv_logradouro', 100)->nullable();
            $table->string('nflocsrv_numero',5)->nullable();
            $table->string('nflocsrv_complemento', 60)->nullable();
            $table->string('nflocsrv_bairro', 60)->nullable();
            $table->string('nflocsrv_cidade', 80)->nullable();
            $table->string('nflocsrv_uf', 2)->nullable();
            $table->string('nflocsrv_pais', 40)->nullable();
            $table->integer('nflocsrv_ibge_cod_mun')->nullable();//Código IBGE do Municipio
            $table->timestamps();
            $table->unique(['nflocsrv_emp', 'nflocsrv_num'], 'ak_faturamento_nf_local_servicos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faturamento_nf_local_servicos');
    }
};
