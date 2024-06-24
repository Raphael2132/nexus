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
        $procedure = "
        CREATE OR REPLACE FUNCTION fn_lancamento_encerra_os (
            ent_empresa IN varchar,
            ent_num_os IN integer, 
            ent_responsavel IN varchar,
            ent_dt_operacao IN date,
            ret_sts OUT varchar, 
            ret_msg OUT varchar)
        LANGUAGE plpgsql
        AS $$
        DECLARE
            v_versao_sp varchar(100) = 'fn_lancamento_encerra_os vrs 1.0 21/05/2024';
            v_linha integer;
            v_num_controle integer;
            v_data_os date;
            v_usuario_os varchar(6);
            v_cliente_os varchar(10);
            v_vlr_liquido_os numeric(15,2);
            v_vlr_bruto_os numeric(15,2);
            v_vlr_servicos numeric(15,2);
            v_vlr_tot_srv_terceiros numeric(15,2);
            v_vlr_desconto_os numeric(15,2);
            v_per_desconto_os numeric(5,2);
            v_vlr_desconto_srv numeric(15,2);
            v_per_desconto_srv numeric(5,2);
            v_vlr_liquido_srv numeric(15,2);
            v_num_orcamento integer;
            v_cliente_nome varchar(80);
            v_cliente_tipo char(1);
            v_cliente_cpf_cnpj varchar(14);
            v_cliente_rg varchar(11);
            v_cliente_tel_residencial varchar(10);
            v_cliente_tel_celular varchar(11);
            v_cliente_tel_comercial varchar(10);
            v_cliente_endreco_seq integer;
            v_cliente_endereco_cep varchar(8);
            v_cliente_endereco_logradouro varchar(100);
            v_cliente_endereco_numero integer;
            v_cliente_endereco_complemento varchar(60);
            v_cliente_endereco_bairro varchar(60);
            v_cliente_endereco_cidade varchar(80);
            v_cliente_endereco_uf varchar(2);
            v_cliente_insc_estadual varchar(14);
            v_cliente_insc_municipal varchar(15);
            itens_os record;
            v_seq_nf_itens integer;
            v_req_tos varchar(2);
            v_req_cat char(1);
            v_vlr_tot_liq_srv numeric(15,2);
            v_tot_desc_os numeric(15,2);
            v_insc_est_emp varchar(14);
            v_insc_mun_emp varchar(15);
        BEGIN
            ret_sts := '0';
            ret_msg := 'teste';
            v_linha := 0;
            v_seq_nf_itens := 0;
        
            select 
                date(os_dha) as data_os,
                os_res_abr,
                os_cli,
                os_vlt,
                os_vlr,
                os_vls,
                os_val_des,
                os_per_des,
                os_num_orc,
                os_cli_end,
                os_val_des_srv
            into 
                v_data_os,
                v_usuario_os,
                v_cliente_os,
                v_vlr_liquido_os,
                v_vlr_bruto_os,
                v_vlr_servicos,
                v_vlr_desconto_os,
                v_per_desconto_os,
                v_num_orcamento,
                v_cliente_endreco_seq,
                v_vlr_desconto_srv
            from lancamento_srv_os 
            where 
                os_nos = ent_num_os and 
                os_emp = ent_empresa; 
        
            select 
                coalesce(sum(srv_vts),0)
            into 
                v_vlr_tot_srv_terceiros
            from lancamento_srv_os_servicos 
            where 
                srv_nos = ent_num_os and 
                srv_emp = ent_empresa and 
                srv_ths = 'T'; 
        
            v_per_desconto_srv := (v_vlr_desconto_srv / v_vlr_servicos) * 100;
            v_vlr_liquido_srv := v_vlr_servicos - v_vlr_desconto_srv;
                                                                                                    v_linha := 100;
        
            SELECT nextval('sq_cad_prestadores') into v_num_controle;
        
            insert into faturamento_nf_headers (
                nfhdr_emp, --Empresa da NF
                nfhdr_num, --Numero de Controle -> sq_faturamento_nf_num
                nfhdr_sts, -- Status da NF ( C - Cancelado, E - Erro, G - Gerado, A - Aberto )
                nfhdr_tip_reg, -- Tipo de Registro ( H - Header )
                nfhdr_num_ped, -- Numero do Pedido / OS
                nfhdr_nop, -- Numero de Operação
                nfhdr_cme, -- Codigo do CME
                nfhdr_dt_ped, -- Data do Pedido / OS
                nfhdr_dt_fec_ped, -- Data do Fechamento do Pedido / OS 
                nfhdr_usu, -- Usuario / Vendedor do Pedido / OS
                nfhdr_ori, -- Origem da NF - sera implementado no futuro e vai ter uma tabela para isso
                nfhdr_tor, -- Tipo da Origem da NF ( P - Produtos, S - Serviços )
                nfhdr_cli, -- Cliente do Pedido / OS 
                nfhdr_cpg, -- Condição de Pagamento - Será implementado no futuro e virá de uma tabela
                nfhdr_vlr_itm_pro, -- Valor de itens em promoção
                nfhdr_vlr_itm_s_dsc, -- valor Itens sem desconto
                nfhdr_vlr_itm_c_dsc, -- valor Itens com desconto
                nfhdr_vlr_itm, -- Valor das mercadorias bruto
                nfhdr_vlr_alq_des, -- Alíquota de desconto os / pedido
                nfhdr_vlr_dsc, -- Valor do Desconto os / pedido
                nfhdr_vlr_srv, -- Valor de mão de Obra (Serviços) bruto
                nfhdr_vlr_srv_ter, -- Valor de Serviços de terceiros
                nfhdr_vlr_alq_dsc_srv, -- Aliquota de desconto sobre serviços
                nfhdr_vlr_dsc_srv, -- Valor de desconto sobre serviços
                nfhdr_vlr_dac, -- Valor de Desp.Acessorias
                nfhdr_vlr_lqm, -- Valor Liquido de mercadorias
                nfhdr_vlr_lqs, -- Valor Liquido de serviços
                nfhdr_vlr_sbt, -- Valor da Substituicao Tributária
                nfhdr_vlr_ipi, -- Valor de IPI
                nfhdr_vlr_tot, -- Valor Total da NF
                nfhdr_vlr_bc_icms, -- Valor da Base de ICMS
                nfhdr_vlr_alq_icms, -- Valor da Aliquota de ICMS
                nfhdr_vlr_icms, -- Valor de ICMS
                nfhdr_vlr_qtd_ppg, -- Quantidade de Parcelas do Pagamento
                nfhdr_vlr_ent, -- Valor da entrada do pagamento
                nfhdr_nfb, -- Numero da Fatura do Boleto
                nfhdr_sfb, -- Situação da Fatura do boleto (PG - Pago, AP - Aguardando Pagamento)
                nfhdr_dfb, -- Data do Pagamento da Fatura do Boleto
                nfhdr_vlr_alq_ir,-- aliquota do ir
                nfhdr_vlr_ir,-- valor do IR 
                nfhdr_vlr_arr,-- Arredondamento na Nota Fiscal 
                nfhdr_orc, -- Numero do orcamento 
                nfhdr_vlr_pis_ret, -- PIS retido 
                nfhdr_vlr_cof_ret, -- COFINS retido 
                nfhdr_vlr_csl_ret, -- CSLL retido 
                nfhdr_vlr_ir_ret, -- IR retido 
                nfhdr_vlr_inss_ret, -- INSS retido
                nfhdr_vlr_bc_pis, -- Base do PIS              
                nfhdr_vlr_alq_pis, -- Aliquota pis  
                nfhdr_vlr_pis, -- Valor do PIS      
                nfhdr_vlr_bc_cof, -- Base do COFINS       
                nfhdr_vlr_alq_cof, -- Aliquota COFINS  
                nfhdr_vlr_cof, -- Valor do COFINS    
                nfhdr_vlr_bc_pis_dac, -- Base do PIS DAC     
                nfhdr_vlr_alq_pis_dac, -- Aliquota pis DAC  
                nfhdr_vlr_pis_dac, -- Valor do PIS DAC   
                nfhdr_vlr_bc_cof_dac, -- Base do COFINS DAC 
                nfhdr_vlr_alq_cof_dac, -- Aliquota COFINS DAC
                nfhdr_vlr_cof_dac, -- Valor do COFINS DAC
                nfhdr_vlr_alq_aic_dac, -- Aliquota icms do dac 
                nfhdr_vlr_fcp_uf_dest, -- Valor total ICMS Fundo Combate Pobreza para UF destino 
                nfhdr_vlr_icms_uf_dest, -- Valor total do ICMS Interestadual para a UF de destino 
                nfhdr_vlr_icms_uf_remet, -- Valor total do ICMS Interestadual para a UF do remetente 
                nfhdr_pres_comp, -- Indicador da presenca do comprador ***** Fazer uma tabela para isso quando for usar ********
                nfhdr_vlr_fcp, -- Valor Total do FCP (Fundo de Combate a Pobreza)
                nfhdr_vlr_fcp_st, -- Valor Total do FCP retido por substituiçao tributaria                      
                nfhdr_ind_con_final, -- Indicador operacao com Consumidor final  
                nfhdr_vlr_icms_deson -- Valor do ICMS desonerado 
            ) 
            values (
                ent_empresa,
                v_num_controle,
                'A',
                'H',
                ent_num_os,
                5933, -- nop verificar como vai montar depois
                210,  -- cme verificar como vai montar depois
                v_data_os,
                ent_dt_operacao,
                v_usuario_os,
                '00', -- Origem da NF - sera implementado no futuro e vai ter uma tabela para isso
                'S',
                v_cliente_os,
                '00', -- Condição de Pagamento - Será implementado no futuro e virá de uma tabela
                0, -- Valor de itens em promoção / montar quando tiver itens
                0, -- valor Itens sem desconto / montar quando tiver itens
                0, -- valor Itens com desconto / montar quando tiver itens
                0, -- Valor das mercadorias / montar quando tiver itens
                v_per_desconto_os,
                v_vlr_desconto_os,
                v_vlr_servicos,
                v_vlr_tot_srv_terceiros,
                v_per_desconto_srv,
                v_vlr_desconto_srv,
                0,-- Valor de Desp.Acessorias / montar quando tiver itens
                0,-- Valor Liquido de mercadorias / montar quando tiver itens
                v_vlr_liquido_srv,
                0,-- Valor da Substituicao Tributária / montar quando tiver itens
                0,-- Valor de IPI / montar quando tiver itens
                v_vlr_liquido_os,
                0,-- Valor da Base de ICMS / montar quando tiver itens
                0,-- Valor da Aliquota de ICMS / montar quando tiver itens
                0,-- Valor de ICMS / montar quando tiver itens
                0,-- Quantidade de Parcelas do Pagamento / montar quando tiver pagamento
                0,-- Valor da entrada do pagamento / montar quando tiver pagamento
                0,-- Numero da Fatura do Boleto / montar quando tiver pagamento
                '',-- Situação da Fatura do boleto (PG - Pago, AP - Aguardando Pagamento) / montar quando tiver pagamento
                null,-- Data do Pagamento da Fatura do Boleto / montar quando tiver pagamento
                0,-- aliquota do ir
                0,-- valor do IR 
                0,-- Arredondamento na Nota Fiscal 
                v_num_orcamento,
                0, -- PIS retido 
                0, -- COFINS retido 
                0, -- CSLL retido 
                0, -- IR retido 
                0, -- INSS retido
                0, -- Base do PIS              
                0, -- Aliquota pis  
                0, -- Valor do PIS      
                0, -- Base do COFINS       
                0, -- Aliquota COFINS  
                0, -- Valor do COFINS    
                0, -- Base do PIS DAC     
                0, -- Aliquota pis DAC  
                0, -- Valor do PIS DAC   
                0, -- Base do COFINS DAC 
                0, -- Aliquota COFINS DAC
                0, -- Valor do COFINS DAC
                0, -- Aliquota icms do dac 
                0, -- Valor total ICMS Fundo Combate Pobreza para UF destino 
                0, -- Valor total do ICMS Interestadual para a UF de destino 
                0, -- Valor total do ICMS Interestadual para a UF do remetente 
                1, -- Indicador da presenca do comprador ***** Fazer uma tabela para isso quando for usar ******** ver depois
                0, -- Valor Total do FCP (Fundo de Combate a Pobreza)
                0, -- Valor Total do FCP retido por substituiçao tributaria     
                1, -- Indicador operacao com Consumidor final  
                0 -- Valor do ICMS desonerado 
            );
                                                                                                    v_linha := 200;
        
            select 
                cliente_nome,
                cliente_tipo_pessoa,
                cliente_cpf_cnpj,
                cliente_rg,
                cliente_tel_residencial,
                cliente_tel_celular,
                cliente_tel_comercial,
                cliente_insc_estadual,
                cliente_insc_municipal
            into 
                v_cliente_nome,
                v_cliente_tipo,
                v_cliente_cpf_cnpj,
                v_cliente_rg,
                v_cliente_tel_residencial,
                v_cliente_tel_celular,
                v_cliente_tel_comercial,
                v_cliente_insc_estadual,
                v_cliente_insc_municipal
            from cadastro_clientes
            where
                cliente_codigo = v_cliente_os;
        
            select 
                endereco_cep,
                endereco_logradouro,
                endereco_numero,
                endereco_complemento,
                endereco_bairro,
                endereco_cidade,
                endereco_uf
            into 
                v_cliente_endereco_cep,
                v_cliente_endereco_logradouro,
                v_cliente_endereco_numero,
                v_cliente_endereco_complemento,
                v_cliente_endereco_bairro,
                v_cliente_endereco_cidade,
                v_cliente_endereco_uf
            from cadastro_cliente_enderecos
            where
                endereco_cliente_codigo = v_cliente_os and 
                endereco_seq = v_cliente_endreco_seq;
                                                                                                    v_linha := 300;
        
            insert into faturamento_nf_clientes (
                nfcli_emp,
                nfcli_num,
                nfcli_tip_reg,
                nfcli_cod,
                nfcli_nom,
                nfcli_tps,
                nfcli_cpf_cnpj,
                nfcli_rg,
                nfcli_tel_res,
                nfcli_tel_cel,
                nfcli_tel_com,
                nfcli_cep,
                nfcli_logradouro,
                nfcli_numero,
                nfcli_complemento,
                nfcli_bai,
                nfcli_cid,
                nfcli_uf,
                nfcli_mun,
                nfcli_cod_mun_ibge,
                nfcli_vlr_bc_sbt, -- base de substituicao tributaria 
                nfcli_vlr_alq_sbt, -- aliquota de substituicao tributaria 
                nfcli_bc_iss, -- base do ISS 
                nfcli_ins_est,
                nfcli_ins_mun )
            values (
                ent_empresa,
                v_num_controle,
                'C',
                v_cliente_os,
                v_cliente_nome,
                v_cliente_tipo,
                v_cliente_cpf_cnpj,
                v_cliente_rg,
                v_cliente_tel_residencial,
                v_cliente_tel_celular,
                v_cliente_tel_comercial,
                v_cliente_endereco_cep,
                v_cliente_endereco_logradouro,
                v_cliente_endereco_numero,
                v_cliente_endereco_complemento,
                v_cliente_endereco_bairro,
                v_cliente_endereco_cidade,
                v_cliente_endereco_uf,
                0,-- codigo do municipio IBGE (Futuramente tem q arrumar criar a tabela com os dados do IBGE) ver quando tiver tabela ibge
                0,-- Cod. Municipio IBGE ver quando tiver tabela ibge  
                0,-- base de substituicao tributaria ainda não existe ver depois
                0,-- aliquota de substituicao tributaria ainda não existe ver depois
                0,-- base do ISS ainda não existe ver depois
                v_cliente_insc_estadual,
                v_cliente_insc_municipal );
                                                                                                    v_linha := 400;
        
            for itens_os in 
                select 
                    srv_req,
                    srv_seq,
                    srv_tmo,
                    srv_are,
                    srv_set,
                    srv_prt,
                    srv_qhr,
                    srv_vhr,
                    srv_vts,
                    srv_vcg,
                    srv_per_des,
                    srv_val_des,
                    srv_vtl
                from lancamento_srv_os_servicos
                where
                    srv_emp = ent_empresa and 
                    srv_nos = ent_num_os and 
                    srv_sts = 'F'
                order by srv_req,srv_seq
            loop 
                                                                                                    v_linha := 500;
        
                v_seq_nf_itens := v_seq_nf_itens + 1;
        
                select 
                    req_tos,
                    req_cat
                into 
                    v_req_tos,
                    v_req_cat
                from lancamento_srv_os_requisicoes
                where
                    req_emp = ent_empresa and 
                    req_nos = ent_num_os and 
                    req_seq = itens_os.srv_req;
                                                                                                    v_linha := 550;
        
                insert into faturamento_nf_itens (
                    nfitm_emp,
                    nfitm_num,
                    nfitm_seq,
                    nfitm_tip_reg,
                    nfitm_num_ped,
                    nfitm_pro,
                    nfitm_dsc,
                    nfitm_num_req,
                    nfitm_seq_req,
                    nfitm_tmo,
                    nfitm_are,
                    nfitm_set,
                    nfitm_tos,
                    nfitm_cat,
                    nfitm_prt,
                    nfitm_cif,
                    nfitm_aif,
                    nfitm_nop,
                    nfitm_cme,
                    nfitm_ori,
                    nfitm_tor,
                    nfitm_cpg,
                    nfitm_tipo,
                    nfitm_qtd,
                    nfitm_qth,
                    nfitm_vlr_hr,
                    nfitm_vlr_uni,
                    nfitm_vlr_alq_desc,
                    nfitm_vlr_desc,
                    nfitm_vlr_uni_liq,
                    nfitm_vlr_tot,
                    nfitm_vlr_ipi,
                    nfitm_vlr_cst,
                    nfitm_vlr_tot_liq,
                    nfitm_vlr_bc_icms,
                    nfitm_vlr_alq_icms,
                    nfitm_vlr_icms,
                    nfitm_itm_prm,              
                    nfitm_vlr_adi,
                    nfitm_vlr_tdi,            
                    nfitm_vlr_cst_con,
                    nfitm_vlr_adf,
                    nfitm_ctb,
                    nfitm_vlr_alq_red,
                    nfitm_vlr_alq_red_icms,
                    nfitm_vlr_dac,
                    nfitm_vlr_bc_icms_dac,
                    nfitm_vlr_alq_icms_dac,
                    nfitm_vlr_icms_dac,
                    nfitm_vlr_frt,
                    nfitm_vlr_bc_icms_frt,
                    nfitm_vlr_alq_icms_frt,
                    nfitm_vlr_icms_frt,
                    nfitm_vlr_bc_sbt,
                    nfitm_vlr_sbt,
                    nfitm_vlr_bc_pis,
                    nfitm_vlr_alq_pis,
                    nfitm_vlr_pis,
                    nfitm_vlr_bc_cof,
                    nfitm_vlr_alq_cof,
                    nfitm_vlr_cof,
                    nfitm_vlr_bc_pis_dac,
                    nfitm_vlr_alq_pis_dac,
                    nfitm_vlr_pis_dac,
                    nfitm_vlr_bc_cof_dac,
                    nfitm_vlr_alq_cof_dac,
                    nfitm_vlr_cof_dac
                )
                values (
                    ent_empresa,
                    v_num_controle,
                    v_seq_nf_itens,
                    'S',
                    ent_num_os,
                    '',
                    '',
                    itens_os.srv_req,
                    itens_os.srv_seq,
                    itens_os.srv_tmo,
                    itens_os.srv_are,
                    itens_os.srv_set,
                    v_req_tos,
                    v_req_cat,
                    itens_os.srv_prt,
                    0,
                    0,
                    5933,--nop verificar depois o que vai ser
                    210,--cme verificar depopis o que vai ser
                    '00',--Origem da NF - sera implementado no futuro e vai ter uma tabela para isso ver depois
                    'S',
                    '00',--Condição de Pagamento - Será implementado no futuro e virá de uma tabela ver quando fizer pagamento
                    '09',
                    1,
                    itens_os.srv_qhr,
                    itens_os.srv_vhr,
                    itens_os.srv_vts,
                    itens_os.srv_per_des,
                    itens_os.srv_val_des,
                    itens_os.srv_vtl,
                    itens_os.srv_vts,
                    0, --IPI
                    itens_os.srv_vcg,
                    itens_os.srv_vtl,
                    0,-- Base de ICMS 
                    0,-- Aliquota de ICMS 
                    0,-- Valor de ICMS  
                    'N',
                    itens_os.srv_per_des,
                    itens_os.srv_val_des,
                    0,-- Custo Contabil
                    0,-- Acrescimo Desconto Financeiro no item 
                    '',-- Codigo de tributacao 
                    0,-- Aliquota de reducao 
                    0,-- Aliquota de Reducao valor ICMS 
                    0,-- Despesa acessoria 
                    0,-- base de icms despessas acessorias                                
                    0,-- aliquota de icms despessas acessorias
                    0,-- valor do icms despessas acessorias 
                    0,-- valor frete
                    0,-- base de icms frete                                                             
                    0,-- aliquota de icms frete
                    0,-- valor do icms frete  
                    0,-- Base substituicao tributaria-frete/desp.acess.
                    0,-- valor substituicao tributaria-frete/desp.acess.
                    0,-- Base do PIS         
                    0,-- Aliquota pis    
                    0,-- Valor do PIS      
                    0,-- Base do COFINS          
                    0,-- Aliquota COFINS  
                    0,-- Valor do COFINS    
                    0,-- Base do PIS DAC       
                    0,-- Aliquota pis DAC  
                    0,-- Valor do PIS DAC    
                    0,-- Base do COFINS DAC  
                    0,-- Aliquota COFINS DAC 
                    0-- Valor do COFINS DAC 
                );
        
            end loop; -- Final do For na tabela de serviços
                                                                                                    v_linha := 600;
        
            select 
                sum(srv_vtl)
            into 
                v_vlr_tot_liq_srv
            from lancamento_srv_os_servicos 
            where 
                srv_nos = ent_num_os and 
                srv_emp = ent_empresa; 
        
            select 
                os_val_des + os_val_des_srv as tot_desc
            into 
                v_tot_desc_os
            from lancamento_srv_os 
            where 
                os_nos = ent_num_os and 
                os_emp = ent_empresa;
        
            select 
                empresa_insc_estadual,
                empresa_insc_municipal
            into 
                v_insc_est_emp,
                v_insc_mun_emp
            from cadastro_empresas
            where
                empresa_codigo = ent_empresa;
                                                                                                    v_linha := 700;
        
            insert into faturamento_nf_totais (
                nftot_emp,
                nftot_num,
                nftot_tip_reg,
                nftot_num_ped,
                nftot_vlr_frt,
                nftot_vlr_mer,
                nftot_vlr_liq_mer,
                nftot_vlr_srv,
                nftot_vlr_liq_srv,
                nftot_vlr_ipi,
                nftot_vlr_sbt,
                nftot_vlr_tot,
                nftot_vlr_bc_icms,
                nftot_vlr_alq_icms,
                nftot_vlr_icms,
                nftot_vlr_alq_iss,
                nftot_vlr_iss,
                nftot_plq,
                nftot_vlr_dsc_iss,
                nftot_vlr_tot_dsc,
                nftot_ins_est,
                nftot_ins_mun,
                nftot_nat
            )
            values (
                ent_empresa,
                v_num_controle,
                'T',
                ent_num_os,
                0,-- Valor total do Frete 
                0,-- Valor bruto das mercadorias (peças)
                0,-- Valor líquido das mercadorias (peças)
                v_vlr_servicos,
                v_vlr_tot_liq_srv,
                0,-- Valor de IPI 
                0,-- Valor de Subst.Tributaria 
                v_vlr_liquido_os,
                0,-- Base de ICMS 
                0,-- Aliquota de ICMS 
                0,-- Valor de ICMS 
                0,-- Aliquota de ISS 
                0,-- Valor de ISS 
                0,-- Peso liquido 
                0,-- desconto iss incentivado
                v_tot_desc_os,
                v_insc_est_emp,
                v_insc_mun_emp,
                'Prestacao de Servicos'-- Natureza de Operacao - verificar depois se vai ficar fixo ou vir de algun lugar
            );
                                                                                                    v_linha = 800;
        
            update lancamento_srv_os set 
                os_sts = 'F',
                os_dhf = ent_dt_operacao,
                os_res_fec = ent_responsavel
            where
                os_emp = ent_empresa and 
                os_nos = ent_num_os;
        
            exception
            when others then
                ret_sts = '*';
                ret_msg = 'Erro na execucao da procedure [' || v_versao_sp || '] [ SQL state: ' || sqlstate || ' / Linha do Erro: ' || v_linha ||' ]';
                return;
        
        END
        $$";

        DB::unprepared("DROP function IF EXISTS fn_lancamento_encerra_os");
        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP function fn_lancamento_encerra_os');
    }
};
