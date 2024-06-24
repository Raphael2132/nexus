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
        Schema::create('faturamento_nf_clientes', function (Blueprint $table) {
            $table->id('nfcli_id');
            $table->string('nfcli_emp',6);
            $table->integer('nfcli_num');
            $table->enum('nfcli_tip_reg', ['C'])->default('C');// Tipo de Registro ( C - Cliente )
            $table->string('nfcli_cod',10);// Codigo do Cliente 
            $table->string('nfcli_nom',80);// Nome do Cliente 
            $table->enum('nfcli_tps', ['F', 'J']);// Tipo de Pessoa - Fisica ou Juridica 
            $table->string('nfcli_cpf_cnpj',14);// CNPJ / CPF do Cliente
            $table->string('nfcli_rg',11)->nullable();// Documento de Identidade 
            $table->string('nfcli_tel_res',10)->nullable();// Telefone residencial 
            $table->string('nfcli_tel_cel',11)->nullable();// Telefone celular 
            $table->string('nfcli_tel_com',10)->nullable();// Telefone comercial 
            $table->string('nfcli_cep',8)->nullable(); // CEP
            $table->string('nfcli_logradouro',100)->nullable(); // Logradouro
            $table->string('nfcli_numero',5)->nullable(); // Numero  
            $table->string('nfcli_complemento',60)->nullable(); // Complemento endereco  
            $table->string('nfcli_bai',60)->nullable();// Bairro
            $table->string('nfcli_cid',80)->nullable();// Nome da cidade 
            $table->string('nfcli_uf',2)->nullable(); // UF
            $table->integer('nfcli_cod_mun_ibge')->default(0); // Cod. Municipio IBGE              
            $table->decimal('nfcli_vlr_bc_sbt',15,2)->default(0);// base de substituicao tributaria 
            $table->decimal('nfcli_vlr_alq_sbt',5,2)->default(0);// aliquota de substituicao tributaria 
            $table->decimal('nfcli_bc_iss',15,2)->default(0); // base do ISS 
            $table->string('nfcli_ins_est',14)->nullable();// Inscr. Estadual 
            $table->string('nfcli_ins_mun',15)->nullable();// Inscr. Municipal 
            $table->string('nfcli_email',80)->nullable();// Email
            $table->unique(['nfcli_emp','nfcli_num'], 'ak_faturamento_nf_clientes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faturamento_nf_clientes');
    }
};
