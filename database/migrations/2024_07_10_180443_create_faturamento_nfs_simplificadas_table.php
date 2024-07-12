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
        Schema::create('faturamento_nfs_simplificadas', function (Blueprint $table) {
            $table->id('nfssim_id');
            $table->string('nfssim_emp',6);//Empresa
            $table->integer('nfssim_num');//Número da Emissão Simplificada
            $table->date('nfssim_data_emi');//Data da Emissão
            $table->enum('nfssim_sts',['G','C','F'])->default('G');//Status Gerado Cancelado Finalizado
            $table->string('nfssim_cli',10);//Cliente
            $table->integer('nfssim_cli_end');//Cliente endereço
            $table->integer('nfssim_srv_grp');//Grupo do Serviço
            $table->integer('nfssim_srv_cod');//Código do Serviço
            $table->string('nfssim_srv_desc',255);//Descrição do Serviço
            $table->string('nfssim_inf_com',255)->nullable();//Informações Complementares
            $table->decimal('nfssim_vlr_nfs',15,2)->default(0);//Valor da NFS
            $table->decimal('nfssim_vlr_base',15,2)->default(0);//Valor da Base de Calculo da NFS
            $table->decimal('nfssim_alq_iss',5,2)->default(0);//Aliquota de ISS
            $table->decimal('nfssim_vlr_inss_ret',15,2)->default(0);//Valor do INSS retido
            $table->decimal('nfssim_vlr_ir_ret',15,2)->default(0);//Valor do IR retido
            $table->decimal('nfssim_vlr_csll_ret',15,2)->default(0);//Valor do CSLL retido
            $table->decimal('nfssim_vlr_pis_ret',15,2)->default(0);//Valor do PIS retido
            $table->decimal('nfssim_vlr_cofins_ret',15,2)->default(0);//Valor do COFINS retido
            $table->decimal('nfssim_vlr_out_ret',15,2)->default(0);//Valor de Outras retenções
            $table->decimal('nfssim_vlr_imp_rec',15,2)->default(0);//Valor do Imposto a recolher
            $table->enum('nfssim_loc_srv',['E','C','O'])->default('E');//Endereço do local da prestação do serviço E - Empresa, C - Cliente, O - Outro
            $table->string('nfssim_loc_srv_cep',8)->nullable();
            $table->string('nfssim_loc_srv_logradouro', 100)->nullable();
            $table->string('nfssim_loc_srv_numero',5)->nullable();
            $table->string('nfssim_loc_srv_complemento', 60)->nullable();
            $table->string('nfssim_loc_srv_bairro', 60)->nullable();
            $table->string('nfssim_loc_srv_cidade', 80)->nullable();
            $table->string('nfssim_loc_srv_uf', 2)->nullable();
            $table->string('nfssim_loc_srv_pais', 40)->nullable();
            $table->integer('nfssim_loc_srv_ibge_cod_mun')->nullable();//Código IBGE do Municipio
            $table->string('nfssim_usu_emi',6);//Usuario da Emissão
            $table->timestamps();
            $table->unique(['nfssim_emp', 'nfssim_num'], 'ak_faturamento_nfs_simplificadas');
        });
    }

    /** 
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faturamento_nfs_simplificadas');
    }
};
