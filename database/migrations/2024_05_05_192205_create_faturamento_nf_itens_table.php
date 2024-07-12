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
        Schema::create('faturamento_nf_itens', function (Blueprint $table) {
            $table->id('nfitm_id');
            $table->string('nfitm_emp',6); // Empresa emitente 
            $table->integer('nfitm_num'); // Numero de controle
            $table->integer('nfitm_seq'); // Sequencia do item na NF
            $table->enum('nfitm_tip_reg', ['P', 'S'])->default('S'); // Tipo de Registro ( P - Produtos, S - Serviços )
            $table->integer('nfitm_num_ped')->default(0); // Numero do Pedido / OS
            $table->string('nfitm_pro',21)->nullable(); // Código do Produto 
            $table->string('nfitm_dsc',80)->nullable();// Descricao do item 

            $table->enum('nfitm_emi_simp_srv', ['S', 'N'])->default('N'); // Emissão Simplificada de Serviço
            $table->string('nfitm_srv_desc',255)->nullable(); // descrição do serviço
            $table->string('nfitm_inf_com',255)->nullable(); // Informações Complementares
            $table->integer('nfitm_cod_srv')->default(0);// Código do Serviço 
            $table->integer('nfitm_num_req')->default(0);// Numero da Requisição 
            $table->string('nfitm_req_dsc',80)->nullable(); // descrição da requisição
            $table->integer('nfitm_seq_req')->default(0);// Sequencia do item na Requisição
            $table->string('nfitm_tmo',15)->nullable(); // Código da Tarefa (Serviço)
            $table->string('nfitm_tmo_dsc',40)->nullable();//descriçao da tmo
            $table->string('nfitm_are',3)->nullable(); // Area
            $table->string('nfitm_set',6)->nullable(); // Setor
            $table->string('nfitm_tos',2)->nullable(); // Tipo de serviço
            $table->string('nfitm_cat',1)->nullable();// Categoria do Tipo de serviço
            $table->string('nfitm_prt',6)->nullable(); // Prestador responsavel
            
            $table->string('nfitm_lin',2)->nullable(); // Linha do produto 
            $table->string('nfitm_ptl',10)->nullable();// Prateleira
            $table->string('nfitm_ced',23)->nullable();// Codigo do Produto Editado 
            $table->string('nfitm_und',2)->nullable(); // Unidade
            $table->decimal('nfitm_plq',7,3)->default(0); // Peso liquido do item
            $table->string('nfitm_ori_pro',1)->nullable();// Origem do Produto   

            $table->decimal('nfitm_cif',1,0)->default(0); // Indicativo Fiscal
            $table->decimal('nfitm_aif',3,1)->default(0); // Aliquota Indicativo Fiscal (verificar depois se vai usar isso seria o mesmo do campo acima)  
            $table->decimal('nfitm_cfop',4,0)->default(0);// Código Fiscal de Operações e de Prestações
            $table->decimal('nfitm_cme',3,0)->nullable(); // CME
            $table->string('nfitm_ori',2)->nullable(); // Origem da NF - sera implementado no futuro e vai ter uma tabela para isso
            $table->enum('nfitm_tor', ['P', 'S']); // Tipo da Origem da NF ( P - Produtos, S - Serviços )
            $table->string('nfitm_cpg',2)->default(0); // Condição de Pagamento - Será implementado no futuro e virá de uma tabela
            $table->string('nfitm_obs',80)->nullable();// Observaçoes
            $table->enum('nfitm_tipo', ['00','01','02','03','04','05','06','07','08','09','10','99'])->default('99'); /* Tipo do item                                      */ 
                                /* 00 - Mercadoria para Revenda;            
                                    01 - Materia-Prima;
                                    02 - Embalagem;
                                    03 - Produto em Processo;
                                    04 - Produto Acabado;
                                    05 - Subproduto;
                                    06 - Produto Intermediario;
                                    07 - Material de Uso e Consumo;
                                    08 - Ativo Imobilizado;
                                    09 - Servicos;
                                    10 - Outros insumos;
                                    99 - Outras
                                */	
            $table->string('nfitm_emi_ref',6)->default(''); // Emitente do Documento Fiscal referencia 
            $table->decimal('nfitm_num_ref',9,0)->default(0); // Numero do Documento Fiscal referencia 
            $table->string('nfitm_ser_ref',5)->default(''); // Serie do Documento Fiscal referencia 

            $table->decimal('nfitm_qtd',9,4)->default(0); // Quantidade do item
            $table->decimal('nfitm_qth',5,2)->default(0); // Quantidade de Horas (Serviços)
            $table->decimal('nfitm_vlr_uni',15,2)->default(0); // Valor unitario --------(para serviço é o valor da hora x qtd. hora)
            $table->decimal('nfitm_vlr_alq_desc',5,2)->default(0); // Aliquota de Desconto ---------(peças é desconto pela unidade)
            $table->decimal('nfitm_vlr_desc',15,2)->default(0); // Valor de descontos --------(peças é desconto pela unidade)
            $table->decimal('nfitm_vlr_uni_liq',15,2)->default(0); // Valor Unitário Liquido (peças vlr uni - desconto) (serviços vlr uni)
            $table->decimal('nfitm_vlr_tot',15,2)->default(0); // Valor total Bruto (peças vlr uni x qtd) (serviços vlr uni x qth)
            $table->decimal('nfitm_vlr_ipi',15,2)->default(0); // IPI 
            $table->decimal('nfitm_vlr_cus_itm',15,2)->default(0); // Custo do item
            $table->decimal('nfitm_vlr_tot_liq',15,2)->default(0); // Valor total Liquido (total - desconto)
            $table->decimal('nfitm_vlr_bc_icms',15,2)->default(0); // Base de ICMS 
            $table->decimal('nfitm_vlr_alq_icms',5,2)->default(0); // Aliquota de ICMS 
            $table->decimal('nfitm_vlr_icms',15,2)->default(0); // Valor de ICMS    
            $table->decimal('nfitm_vlr_bc_iss',15,2)->default(0); // Base de iss 
            $table->decimal('nfitm_vlr_alq_iss',5,2)->default(0); // Aliquota de iss 
            $table->decimal('nfitm_vlr_iss',15,2)->default(0); // Valor de iss  
            $table->enum('nfitm_itm_prm', ['N', 'S'])->default('N');// Item de promoção    
              
            $table->decimal('nfitm_vlr_adi',5,2)->default(0);// Aliquota de desconto do item 
            $table->decimal('nfitm_vlr_tdi',11,2)->default(0);// Valor Total de desconto no item        
            
            $table->decimal('nfitm_vlr_cus_con',15,2)->default(0);// Custo Contabil
            $table->decimal('nfitm_vlr_adf',15,2)->default(0);// Acrescimo Desconto Financeiro no item 
            $table->string('nfitm_cst',2)->nullable();// Codigo de situação tributaria  

            $table->decimal('nfitm_vlr_alq_red',6,4)->default(0);// Aliquota de reducao 
            $table->decimal('nfitm_vlr_alq_red_icms',6,4)->default(0);// Aliquota de Reducao valor ICMS 

            $table->decimal('nfitm_vlr_dac',15,2)->default(0);// Despesa acessoria 
            $table->decimal('nfitm_vlr_bc_icms_dac',15,2)->default(0); // base de icms despessas acessorias                                
            $table->decimal('nfitm_vlr_alq_icms_dac',5,2)->default(0); // aliquota de icms despessas acessorias
            $table->decimal('nfitm_vlr_icms_dac',15,2)->default(0); // valor do icms despessas acessorias 
            $table->decimal('nfitm_vlr_frt',15,2)->default(0); // valor frete
            $table->decimal('nfitm_vlr_bc_icms_frt',15,2)->default(0); // base de icms frete                                                             
            $table->decimal('nfitm_vlr_alq_icms_frt',5,2)->default(0); // aliquota de icms frete
            $table->decimal('nfitm_vlr_icms_frt',15,2)->default(0); // valor do icms frete  
            $table->decimal('nfitm_vlr_bc_sbt',15,2)->default(0);// Base substituicao tributaria-frete/desp.acess.
            $table->decimal('nfitm_vlr_sbt',15,2)->default(0);// valor substituicao tributaria-frete/desp.acess.
            $table->decimal('nfitm_vlr_bc_pis',15,2)->default(0);// Base do PIS         
            $table->decimal('nfitm_vlr_alq_pis',5,2)->default(0);// Aliquota pis    
            $table->decimal('nfitm_vlr_pis',15,2)->default(0);// Valor do PIS      
            $table->decimal('nfitm_vlr_bc_cof',15,2)->default(0);// Base do COFINS          
            $table->decimal('nfitm_vlr_alq_cof',5,2)->default(0);// Aliquota COFINS  
            $table->decimal('nfitm_vlr_cof',15,2)->default(0);// Valor do COFINS    
            $table->decimal('nfitm_vlr_bc_pis_dac',15,2)->default(0);// Base do PIS DAC       
            $table->decimal('nfitm_vlr_alq_pis_dac',5,2)->default(0);// Aliquota pis DAC  
            $table->decimal('nfitm_vlr_pis_dac',15,2)->default(0);// Valor do PIS DAC    
            $table->decimal('nfitm_vlr_bc_cof_dac',15,2)->default(0);// Base do COFINS DAC  
            $table->decimal('nfitm_vlr_alq_cof_dac',5,2)->default(0);// Aliquota COFINS DAC 
            $table->decimal('nfitm_vlr_cof_dac',15,2)->default(0);// Valor do COFINS DAC 
            
            $table->string('nfitm_num_doc_imp',15)->nullable();// Numero do Documento de Importacão DI/DSI/DA        
            $table->date('nfitm_dt_doc_imp')->nullable(); // Data de Registro da DI/DSI/DA
            $table->string('nfitm_loc_da',60)->nullable(); // Local de desembaraço Aduaneiro
            $table->string('nfitm_uf_loc_da',2)->nullable(); // Sigla da UF onde ocorreu o Desembaraço Aduaneiro
            $table->date('nfitm_dt_da')->nullable();// Data do Desembaraço Aduaneiro
            $table->string('nfitm_cod_exp',60)->nullable(); // Código do exportador
            $table->decimal('nfitm_nad',1)->default(0); // Numero da adição
            $table->decimal('nfitm_sad',1)->default(0); // Numero seqüencial do item dentro da adição        
            $table->string('nfitm_cod_fab',60)->nullable(); // Código do fabricante estrangeiro                  
            $table->decimal('nfitm_vlr_ddi',15,2)->default(0); // Valor do desconto do item da DI – adição          
            $table->decimal('nfitm_vlr_bc_iim',15,2)->default(0); // Valor da BC do Imposto de Importação              
            $table->decimal('nfitm_vlr_dps_iim',15,2)->default(0); // Valor das despesas aduaneiras                     
            $table->decimal('nfitm_vlr_iim',15,2)->default(0); // Valor do Imposto de Importação                    
            $table->decimal('nfitm_vlr_imp_opf',15,2)->default(0); // Valor do Imposto sobre Operações Financeiras      
                                       
            $table->string('nfitm_cst_pis',2)->default(0); // cst de pis do item         
            $table->string('nfitm_cst_cof',2)->default(0); // cst de cofins do item         
            $table->string('nfitm_nat_rec',3)->default(0); // natureza da receita pis/cofins 
            $table->string('nfitm_ccf',10)->default(''); // Codigo de classificacao fiscal      
            
            $table->string('nfitm_csosn',3)->nullable(); // Codigo de Situacao da Operacao–Simples Nacional
            $table->decimal('nfitm_vlr_alq_icms_sup_sim',5,2)->default(0); // aliquota de icms super simples        
            $table->decimal('nfitm_vlr_icms_sup_sim',15,2)->default(0); // valor de icms super simples             
            $table->decimal('nfitm_cod_anp',9,0)->default(0); // Codigo de produto da ANP 
            $table->decimal('nfitm_vlr_icms_s_dif',15,2)->default(0); // valor do icms sem diferimento
            $table->decimal('nfitm_vlr_per_dif',7,4)->default(0); // Percentual do diferimento 
            $table->decimal('nfitm_vlr_icms_c_dif',15,2)->default(0); // valor do icms com diferimento 
            $table->decimal('nfitm_via_trans_di',2,0)->default(0); // Via transporte internacional informada na (DI)  
            $table->string('nfitm_cod_esp_sbt',7)->nullable(); // Codigo Especificador Substituicao Tributaria
            $table->decimal('nfitm_vlr_bc_icms_uf_dest',15,2)->default(0); // Valor da Base de Calculo do ICMS na UF de destino 
            $table->decimal('nfitm_vlr_per_icms_fcp_uf_dest',7,4)->default(0); // Percentual ICMS Fundo Combate Pobreza UF destino  
            $table->decimal('nfitm_vlr_alq_icms_int_uf_dest',7,4)->default(0); // Aliquota ICMS interna da UF de destino                 
            $table->decimal('nfitm_vlr_alq_icms_interest',7,4)->default(0); // Aliquota ICMS interestadual das UF envolvidas                 
            $table->decimal('nfitm_vlr_ppp_icms_interest',7,4)->default(0); // Percentual provisorio partilha ICMS Interestadual        
            $table->decimal('nfitm_vlr_icms_fcp_uf_dest',15,2)->default(0); // Valor ICMS Fundo Combate Pobreza UF destino       
            $table->decimal('nfitm_vlr_icms_interest_uf_dest',15,2)->default(0); // Valor do ICMS Interestadual para a UF de destino  
            $table->decimal('nfitm_vlr_icms_interest_uf_rem',15,2)->default(0); // Valor do ICMS Interestadual p/ a UF do remetente  
            	
            // campos para nfe 4.00
            $table->decimal('nfitm_vlr_per_fcp',7,4)->default(0); // Percentual do Fundo de Combate a Pobreza (FCP) 
            $table->decimal('nfitm_vlr_bc_fcp',15,2)->default(0); // Valor da Base de Calculo do FCP 
            $table->decimal('nfitm_vlr_fcp',15,2)->default(0); // Valor do Fundo de Combate a Pobreza (FCP) 
            $table->decimal('nfitm_vlr_bc_fcp_st',15,2)->default(0); // Valor da Base de Calculo do FCP retido por Substituicao Tributaria 
            $table->decimal('nfitm_vlr_per_fcp_st',7,4)->default(0); // Percentual do FCP retido por Substituicao Tributaria 
            $table->decimal('nfitm_vlr_fcp_st',15,2)->default(0); // Valor do FCP retido por Substituicao Tributaria 
            $table->decimal('nfitm_vlr_per_fcp_st_ret',7,4)->default(0); // Percentual do FCP retido anteriormente por Substituicao Tributaria 
            $table->decimal('nfitm_vlr_fcp_st_ret',15,2)->default(0); // Valor do FCP retido por Substituicao Tributaria 
            $table->decimal('nfitm_vlr_alq_sup_cf',7,4)->default(0); // Aliquota suportada pelo Consumidor Final 
            $table->decimal('nfitm_vlr_bc_fcp_uf_dest',15,2)->default(0); // Valor da BC FCP na UF de destino 
            //campos cst 60
            $table->decimal('nfitm_vlr_bc_st_ret',15,2)->default(0); // Valor da BC do ICMS ST retido 
            $table->decimal('nfitm_vlr_icms_sub',15,2)->default(0); // Valor do ICMS proprio do Substituto cobrado em operacao anterior 
            $table->decimal('nfitm_vlr_icms_st_ret',15,2)->default(0); // Valor do ICMS ST retido 
            $table->decimal('nfitm_vlr_per_red_bc_efet',7,4)->default(0); // Percentual de reducao da base de calculo efetiva 
            $table->decimal('nfitm_vlr_bc_efet',15,2)->default(0); // Valor da base de calculo efetiva 
            $table->decimal('nfitm_vlr_alq_icms_efet',7,4)->default(0); // Aliquota do ICMS efetiva 
            $table->decimal('nfitm_vlr_icms_efet',15,2)->default(0); // Valor do ICMS efetivo 
            // desoneracao
            $table->decimal('nfitm_vlr_per_red_base_deson',7,4)->default(0); // Percentual da Reducao de Base de desoneracao 
            $table->decimal('nfitm_vlr_alq_icms_deson',7,4)->default(0); // aliquota  icms de desoneracao 
            $table->string('nfitm_cod_e115_deson',15)->nullable(); // Codigo beneficio do registro E115 Sped Fiscal 
            $table->string('nfitm_motivo_deson',2)->nullable(); // Motivo da desoneracao do ICMS 
            $table->decimal('nfitm_vlr_icms_deson',15,2)->default(0); // Valor do ICMS desonerado 
            $table->decimal('nfitm_vlr_per_mva',7,4)->default(0); // Percentual margem de valor agregado 
            $table->unique(['nfitm_emp','nfitm_num','nfitm_seq'], 'ak_faturamento_nf_itens');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faturamento_nf_itens');
    }
};
