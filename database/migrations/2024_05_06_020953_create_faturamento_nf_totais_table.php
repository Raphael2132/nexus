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
        Schema::create('faturamento_nf_totais', function (Blueprint $table) {
            $table->id('nftot_id');
            $table->string('nftot_emp',6);// Empresa da NF
            $table->integer('nftot_num');// Numero de controle -> sq_faturamento_nf_num
            $table->enum('nftot_tip_reg', ['T']); // Tipo de Registro ( T - Totais )
            $table->integer('nftot_num_ped')->default(0); // Numero do Pedido / OS
            $table->decimal('nftot_vlr_frt',15,2)->default(0); // Valor total do Frete 
            $table->decimal('nftot_vlr_mer',15,2)->default(0); // Valor bruto das mercadorias (peças)
            $table->decimal('nftot_vlr_liq_mer',15,2)->default(0); // Valor líquido das mercadorias (peças)
            $table->decimal('nftot_vlr_srv',15,2)->default(0); // Valor bruto de serviços 
            $table->decimal('nftot_vlr_liq_srv',15,2)->default(0); // Valor líquido de serviços 
            $table->decimal('nftot_vlr_ipi',15,2)->default(0); // Valor de IPI 
            $table->decimal('nftot_vlr_sbt',15,2)->default(0); // Valor de Subst.Tributaria 
            $table->decimal('nftot_vlr_tot',15,2)->default(0); // Valor Total liquido da NF 
            $table->decimal('nftot_vlr_bc_icms',15,2)->default(0); // Base de ICMS 
            $table->decimal('nftot_vlr_alq_icms',5,2)->default(0); // Aliquota de ICMS 
            $table->decimal('nftot_vlr_icms',15,2)->default(0); // Valor de ICMS 
            $table->decimal('nftot_vlr_alq_iss',5,2)->default(0); // Aliquota de ISS 
            $table->decimal('nftot_vlr_iss',15,2)->default(0); // Valor de ISS 
            $table->decimal('nftot_plq',7,1)->default(0); // Peso liquido 
            $table->decimal('nftot_vlr_dsc_iss',15,2)->default(0); // desconto iss incentivado
            $table->decimal('nftot_vlr_tot_dsc',15,2)->default(0);  // Valor total de Descontos do Item
            $table->string('nftot_ins_est',14)->nullable(); // Inscr.Estadual do Emitente
            $table->string('nftot_ins_mun',15)->nullable(); // Inscr.Municipal do Emitente
            $table->string('nftot_nat',60); // Natureza de Operacao
            $table->string('nftot_cod_trp',10)->nullable(); // Código da Transportadora 
            $table->string('nftot_tpr',80)->nullable(); // Transportador  
            $table->string('nftot_cnpj_trp',14)->nullable(); // CNPJ da Transportadora
            $table->string('nftot_via',10)->nullable(); // Via de transporte
            $table->string('nftot_plc',7)->nullable(); // Placa
            $table->string('nftot_uf_plc',2)->nullable(); // UF da placa do veiculo do transportador
            $table->string('nftot_uf_trp',2)->nullable(); // UF da Transportadora 
            $table->string('nftot_cep_trp',8)->nullable(); // CEP da Transportadora
            $table->string('nftot_log_trp',60)->nullable(); // Endereco do transportador
            $table->string('nftot_mun_trp',40)->nullable(); // Municipio do transportador
            $table->string('nftot_ins_est_trp',15)->nullable(); // Inscricao estadual do transportador
            $table->decimal('nftot_vlr_icms_dfr',15,2)->default(0); // ICMS Diferido
            $table->decimal('nftot_vlr_dac',15,2)->default(0); // Outras Despesas Acessorias 
            $table->decimal('nftot_vlr_adf',15,2)->default(0); // Acrescimo Desconto Financeiro no item 
            $table->decimal('nftot_nop_dac',4,0)->default(0); // NOP Despesa Acessoria 
            $table->string('nftot_obs',255)->nullable(); // Observacões da NF
            $table->unique(['nftot_emp','nftot_num'], 'ak_faturamento_nf_totais');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faturamento_nf_totais');
    }
};
