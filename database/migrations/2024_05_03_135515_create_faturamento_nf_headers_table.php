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
        Schema::create('faturamento_nf_headers', function (Blueprint $table) {
            $table->id('nfhdr_id');
            $table->string('nfhdr_emp',6);// Empresa da NF
            $table->integer('nfhdr_num');// Numero de Controle -> sq_faturamento_nf_num
            $table->enum('nfhdr_sts', ['C', 'E', 'G', 'A', 'I'])->default('A');// Status da NF ( C - Cancelado, E - Erro, G - Gerado, A - Aberto, I - Iniciado )
            $table->enum('nfhdr_tip_reg', ['H'])->default('H');// Tipo de Registro ( H - Header )
            $table->integer('nfhdr_num_ped')->default(0);// Numero do Pedido / OS / Emissão Simplificada
            $table->decimal('nfhdr_num_nf',9,0)->default(0);// Numero da NF / Cupom 
            $table->string('nfhdr_ser_nf',5)->nullable();// Serie da NF / Cupom
            $table->date('nfhdr_dt_nf')->nullable();// Data da NF / Cupom
            $table->decimal('nfhdr_hr_nf',4,0)->default(0);// Hora da NF
            $table->decimal('nfhdr_cfop',4,0)->default(0);// Código Fiscal de Operações e de Prestações
            $table->decimal('nfhdr_cme',3,0)->default(0);// Codigo do CME
            $table->integer('nfhdr_cod_srv')->default(0);// Codigo do serviço
            $table->date('nfhdr_dt_ped');// Data do Pedido / OS
            $table->date('nfhdr_dt_fec_ped');// Data do Fechamento do Pedido / OS 
            $table->string('nfhdr_usu',6);// Usuario / Vendedor do Pedido / OS
            $table->string('nfhdr_ori',2);//Origem da NF 
            $table->enum('nfhdr_tor', ['P', 'S']);// Tipo da Origem da NF ( P - Produtos, S - Serviços )
            $table->string('nfhdr_cli',10);// Cliente do Pedido / OS 
            $table->string('nfhdr_cpg',2)->default(0);// Condição de Pagamento - Será implementado no futuro e virá de uma tabela
            $table->decimal('nfhdr_vlr_itm_pro',15,2)->default(0);// Valor de itens em promoção
            $table->decimal('nfhdr_vlr_itm_s_dsc',15,2)->default(0);// valor Itens sem desconto
            $table->decimal('nfhdr_vlr_itm_c_dsc',15,2)->default(0);// valor Itens com desconto
            $table->decimal('nfhdr_vlr_mrc',15,2)->default(0);// Valor das mercadorias bruto
            $table->decimal('nfhdr_vlr_alq_dsc_mrc',5,2)->default(0);// Alíquota de desconto sobre mercadorias
            $table->decimal('nfhdr_vlr_dsc_mrc',15,2)->default(0);// Valor do Desconto sobre mercadorias
            $table->decimal('nfhdr_vlr_srv',15,2)->default(0);// Valor de mão de Obra (Serviços) bruto
            $table->decimal('nfhdr_vlr_srv_ter',15,2)->default(0);// Valor de Serviços de terceiros
            $table->decimal('nfhdr_vlr_alq_dsc_srv',5,2)->default(0);// Aliquota de desconto sobre serviços
            $table->decimal('nfhdr_vlr_dsc_srv',15,2)->default(0);// Valor de desconto sobre serviços
            $table->decimal('nfhdr_vlr_dac',15,2)->default(0);// Valor de Desp.Acessorias
            $table->decimal('nfhdr_vlr_lqm',15,2)->default(0);// Valor Liquido de mercadorias
            $table->decimal('nfhdr_vlr_lqs',15,2)->default(0);// Valor Liquido de serviços
            $table->decimal('nfhdr_vlr_sbt',15,2)->default(0);// Valor da Substituicao Tributária
            $table->decimal('nfhdr_vlr_ipi',15,2)->default(0);// Valor de IPI
            $table->decimal('nfhdr_vlr_tot_nf',15,2)->default(0);// Valor Total da NF
            $table->decimal('nfhdr_vlr_bc_icms',15,2)->default(0);// Valor da Base de ICMS
            $table->decimal('nfhdr_vlr_alq_icms',5,2)->default(0);// Valor da Aliquota de ICMS
            $table->decimal('nfhdr_vlr_icms',15,2)->default(0);// Valor de ICMS
            $table->decimal('nfhdr_vlr_qtd_ppg',2,0)->default(0);// Quantidade de Parcelas do Pagamento
            $table->decimal('nfhdr_vlr_ent',15,2)->default(0);// Valor da entrada do pagamento
            $table->integer('nfhdr_nfb')->default(0);// Numero da Fatura do Boleto
            $table->enum('nfhdr_sfb',['PG', 'AP', ''])->default('');// Situação da Fatura do boleto (PG - Pago, AP - Aguardando Pagamento)
            $table->date('nfhdr_dfb')->nullable();// Data do Pagamento da Fatura do Boleto
            //imposto de renda
            $table->decimal('nfhdr_vlr_alq_ir',5,2)->default(0);// aliquota do ir
            $table->decimal('nfhdr_vlr_ir',15,2)->default(0);// valor do IR 
            $table->decimal('nfhdr_vlr_arr',5,2)->default(0);// Arredondamento na Nota Fiscal 
            $table->integer('nfhdr_orc')->default(0);// Numero do orcamento 
            $table->enum('nfhdr_nec', ['C', 'S', 'N'])->default('N');// ('E' - Nota Espelho de Cupom Fiscal , 'C' - Cupom ou 'N' - Nota Normal)
            $table->decimal('nfhdr_rnu',9,0)->default(0);// Numero Nota de venda qdo devolucao 
            $table->string('nfhdr_rse',2)->nullable();// Serie  Nota de venda qdo devolucao 
            $table->decimal('nfhdr_vlr_pis_ret',7,2)->default(0);// PIS retido 
            $table->decimal('nfhdr_vlr_cof_ret',7,2)->default(0);// COFINS retido 
            $table->decimal('nfhdr_vlr_csl_ret',7,2)->default(0);// CSLL retido 
            $table->decimal('nfhdr_vlr_ir_ret',7,2)->default(0);// IR retido 
            $table->decimal('nfhdr_vlr_inss_ret',7,2)->default(0);// INSS retido
            $table->decimal('nfhdr_vlr_bc_pis',15,2)->default(0); // Base do PIS              
            $table->decimal('nfhdr_vlr_alq_pis',5,2)->default(0); // Aliquota pis  
            $table->decimal('nfhdr_vlr_pis',15,2)->default(0); // Valor do PIS      
            $table->decimal('nfhdr_vlr_bc_cof',15,2)->default(0); // Base do COFINS       
            $table->decimal('nfhdr_vlr_alq_cof',5,2)->default(0); // Aliquota COFINS  
            $table->decimal('nfhdr_vlr_cof',15,2)->default(0); // Valor do COFINS    
            $table->decimal('nfhdr_vlr_bc_pis_dac',15,2)->default(0); // Base do PIS DAC     
            $table->decimal('nfhdr_vlr_alq_pis_dac',5,2)->default(0); // Aliquota pis DAC  
            $table->decimal('nfhdr_vlr_pis_dac',15,2)->default(0); // Valor do PIS DAC   
            $table->decimal('nfhdr_vlr_bc_cof_dac',15,2)->default(0); // Base do COFINS DAC 
            $table->decimal('nfhdr_vlr_alq_cof_dac',5,2)->default(0); // Aliquota COFINS DAC
            $table->decimal('nfhdr_vlr_cof_dac',15,2)->default(0); // Valor do COFINS DAC
            $table->decimal('nfhdr_vlr_alq_aic_dac',5,2)->default(0);// Aliquota icms do dac 
            $table->enum('nfhdr_cal_rat_dac', ['P', 'N'])->default('N');// Status calculo rateio do dac P-proporcional N - Não Calcular
            $table->decimal('nfhdr_vlr_fcp_uf_dest',15,2)->default(0);// Valor total ICMS Fundo Combate Pobreza para UF destino 
            $table->decimal('nfhdr_vlr_icms_uf_dest',15,2)->default(0);// Valor total do ICMS Interestadual para a UF de destino 
            $table->decimal('nfhdr_vlr_icms_uf_remet',15,2)->default(0);// Valor total do ICMS Interestadual para a UF do remetente 
            $table->decimal('nfhdr_pres_comp',2,0)->default(1); // Indicador da presenca do comprador ***** Fazer uma tabela para isso quando for usar ********
                                                                // 0 - Não Se Aplica (exemplo, Nota Fiscal complementar ou de Ajuste)
                                                                // 1 - Operação Presencial
                                                                // 2 - Operação Não Presencial (Internet)
                                                                // 3 - Operação Não Presencial (Telefone)
                                                                // 4 - NFC-e em Operação com Entrega a Domicilio
                                                                // 99 - Operação Não Presencial (Outros)
            //campos para nfe 4.00
            $table->decimal('nfhdr_vlr_fcp',15,2)->default(0);// Valor Total do FCP (Fundo de Combate a Pobreza)
            $table->decimal('nfhdr_vlr_fcp_st',15,2)->default(0);// Valor Total do FCP retido por substituiçao tributaria 
            // campos para nota de exportacao
            $table->string('nfhdr_uf_saida_pais',2)->nullable();// Sigla da UF de Embarque ou transposicao fronteira SP,MG
            $table->string('nfhdr_loc_exporta',60)->nullable();// Descricao do Local de Embarque ou transposicao fronteira
            $table->string('nfhdr_loc_despacho',60)->nullable();// Descricao do local de despacho                         

            $table->decimal('nfhdr_ind_con_final',1,0)->default(1);// Indicador operacao com Consumidor final  
            $table->decimal('nfhdr_vlr_icms_deson',15,2)->default(0); // Valor do ICMS desonerado  
            $table->unique(['nfhdr_emp','nfhdr_num'], 'ak_faturamento_nf_headers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faturamento_nf_headers');
    }
};
