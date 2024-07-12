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
        Schema::create('faturamento_nfs', function (Blueprint $table) {
            $table->id('nfs_id');
            $table->enum('nfs_sts',['G','E','C','I'])->default('I');//Status da NFS G - Gerada, E - Erro, C - Cancelada, I - Iniciada
            $table->string('nfs_emp',6);//Empresa emitente da nfs
            $table->decimal('nfs_nnfs',9,0);//Número da nota fiscal de serviços
            $table->string('nfs_snfs',5);//Série da nota fiscal de serviços
            $table->integer('nfs_nfhdr_num');//Número de controle da NF -> faturamento_nf_headers.nfhdr_num
            $table->integer('nfs_nfhdr_num_ped');//Número do pedido / OS -> faturamento_nf_headers.nfhdr_num_ped
            $table->string('nfs_cli',10);//Cliente da nfs
            $table->date('nfs_dt_emi')->nullable();//Data da Emissão da NFS
            $table->decimal('nfs_hr_emi',4,0)->default(0);//Hora da Emissão da NFS
            $table->decimal('nfs_vlr_srv',15,2)->default(0);// Valor dos Servicos
            $table->decimal('nfs_vlr_dsc',15,2)->default(0);// Valor das Descontos 
            $table->decimal('nfs_vlr_ded',15,2)->default(0);// Valor das Deducoes 
            $table->decimal('nfs_vlr_tot',15,2)->default(0);// Valor total da nota
            $table->integer('nfs_cod_srv')->default(0);// Codigo do Servico Prestado
            $table->decimal('nfs_vlr_iss',15,2)->default(0);// Valor do ISS
            $table->decimal('nfs_alq_nfs',5,2)->default(0);// Aliquota
            $table->enum('nfs_iss_ret',['1','2'])->default('2');// ISS Retido: (1 - ISS Retido / 2 - Sem ISS Retido)
            $table->enum('nfs_tip_tom',['F','J'])->default('F');// Tipo de cadastro do cliente tomador (F - Fisica / J - Juridica)
            $table->string('nfs_cpf_cnpj_tom',14)->nullable();// CPF/ CNPJ do Tomador
            $table->string('nfs_ins_mun_tom',15)->nullable();// Inscricao Municipal do Tomador
            $table->string('nfs_ins_est_tom',14)->nullable();// Inscricao Estadual do Tomador
            $table->string('nfs_nom_tom',80);// Nome Razao Social do Tomador 
            $table->string('nfs_end_tom',100)->nullable();// Endereco do Tomador
            $table->string('nfs_num_end_tom',5)->nullable();// Numero do Endereco do Tomador
            $table->string('nfs_com_end_tom',60)->nullable();// Complemento do Endereco do Tomador
            $table->string('nfs_bai_tom',60)->nullable();// Bairro do Tomador
            $table->string('nfs_cid_tom',80)->nullable();// Cidade do Tomador
            $table->integer('nfs_ibge_cod_mun_tom')->nullable();//Código IBGE do Municipio do Tomador
            $table->string('nfs_uf_tom',2)->nullable();// UF do Tomador
            $table->string('nfs_cep_tom',8)->nullable();// CEP do Tomador
            $table->string('nfs_email_tom',80)->nullable();// EMAIL do Tomador
            $table->string('nfs_tel_res_tom',10)->nullable();//telefone residencial tomador
            $table->string('nfs_tel_cel_tom',11)->nullable();//telefone celular tomador
            $table->string('nfs_tel_com_tom',10)->nullable();//telefone comercial tomador
            $table->integer('nfs_qtd_itm')->default(0);// Qtd de items
            $table->decimal('nfs_nro_nfe',9,0)->default(0);// Numero da Nota Fiscal Eletronica
            $table->string('nfs_cod_ver',8)->nullable();// Codigo Verificacao Nota Fiscal Eletronica
            $table->decimal('nfs_vlr_des_iss_inc',15,2)->default(0);// Valor do desconto iss incentivado
            $table->string('nfs_obs',255)->nullable(); // Observacões da NF
            $table->string('nfs_pais_tom',40)->nullable();// País do Tomador
            $table->timestamps();
            $table->unique(['nfs_emp','nfs_nfhdr_num'], 'ak_faturamento_nfs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faturamento_nfs');
    }
};
