<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use App\Http\Controllers\Nfs\Core\Nfsxml;
use File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\EmailNFS;
use App\Http\Controllers\Faturamento\Nfe\FaturamentoNfeInfoPagamentosController;
use App\Http\Controllers\Faturamento\Nota\FaturamentoNfTipoNotasController;

class FaturamentoGeracaoNfController extends Controller
{
    //private $nfsxml;

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa o Faturamento de Notas e realiza a Emissão da NF
    |----------------------------------------------------------------------------------------------------
    */
    public function emissaoNF($origem, $empresa)
    {
        //Variaveis globais do Faturamento
        $idRecebimento = Session::get('glo_recebimento_id');
        $where = Session::get('glo_emissao_nf_where_completo');
        $where_semi = Session::get('glo_emissao_nf_where_semi');
        $where_reemissao = '';

        // Inicia/Zera a variável de sessão
        Session::put('glo_fat_pnl001_EmissaoNF_erro_geracao_nf', '');

        //Inicia o Database Transaction
        DB::beginTransaction();

        DB::table('financeiro_recebimento_headers')
        ->where('rechdr_cod_rec', $idRecebimento)
        ->where('rechdr_emp', $empresa)
        ->update(['rechdr_sts' => 'F']);

        //Grava as alterações do banco
        DB::commit();
        
        //Busca as NF-e do recebimento realizado
        //As NFS-e aqui são ignoradas para montagem após envio das NF-e
        $sql = "SELECT 
                    recnf_cod_rec, 
                    recnf_num,
                    recnf_vlr_tot
                FROM financeiro_recebimento_notas
                INNER JOIN faturamento_nf_headers ON 
                    nfhdr_emp = recnf_emp AND 
                    nfhdr_num = recnf_num
                WHERE
                    recnf_cod_rec = ".$idRecebimento." AND
                    nfhdr_vlr_lqs = 0 AND
                    nfhdr_vlr_srv = 0 AND
                    nfhdr_vlr_srv_ter = 0 AND
                    ((nfhdr_cme <> '215' AND nfhdr_vlr_lqm <> 0) OR (nfhdr_cme = '215' AND nfhdr_vlr_lqm = 0)) AND 
                    CASE
                        WHEN (SELECT COALESCE(nfelot_sts, 0) 
                            FROM faturamento_nfe_chaves 
                            LEFT JOIN faturamento_nfe_lotes ON nfechv_key = nfelot_key 
                            WHERE nfechv_num = nfhdr_num) = 100 THEN 'N' 
                        ELSE 'S'  
                    END = 'S'";
        $notasReceb = DB::select($sql);

        // Executa a o método de rateio da forma de pagamento
        $this->rateioPgto($notasReceb, $empresa, $idRecebimento);

        //Grava CST PIS/COFINS das notas que enviadas ao SEFAZ.
        //Ainda não sei se vai fazer mas se fizer vamos criar uma function que será executada aqui
        
        //Criação dos XML's das NF-e a serem enviadas
        foreach($notasReceb as $nota){
            
            //O tipo da nota por hora por hora é  1 mas depois temos que ver como fazer isso, talvez no botão gerar DANFE passe o valor como parametro que nesse caso seria 1
            //Quando tiver o Botão de NFC-e funcionando seria 2

            // Executa a o método de gravação do tipo da Nota
            $this->gravaTipoNota($notas->recnf_num, $empresa, '1');

            //Aqui vamos fazer o método de criação do xml da nf-e
            //Por hora não temos e fica para depois
        }
        
        //Envio das Notas para o SEFAZ
        foreach($notasReceb as $nota){

            //Ainda não temos a NF-e e NFC-e então por hora não faremos nada aqui
            
        }

        //Verifica se todas as NF-e/NFC-e do recebimento foram emitidas e validadas na SEFAZ com sucesso
        //Testar esse select se ele esta funcionando corretamente quando tiver nfe
        $nfs = "SELECT 
                    count(*) as nf_erro 
                FROM financeiro_recebimento_notas 
                INNER JOIN faturamento_nf_headers ON 
                    nfhdr_emp = recnf_emp AND 
                    nfhdr_num = recnf_num
                LEFT JOIN faturamento_nfe_chaves ON 
                    nfechv_emp = recnf_emp AND
                    nfechv_num = recnf_num
                LEFT JOIN faturamento_nfe_lotes ON 
                    nfelot_emp = nfechv_emp AND
                    nfelot_key = nfechv_key
                WHERE 
                    recnf_cod_rec = ".$idRecebimento." AND 
                    coalesce(nfelot_sts, 0) <> 100 AND 
                    nfhdr_sts <> 'G' AND
                    nfhdr_vlr_lqs  = 0 AND 
                    nfhdr_vlr_srv = 0 AND 
                    nfhdr_vlr_srv_ter = 0 AND 
                    nfhdr_vlr_lqm <> 0";
        $nfSrv = DB::select($nfs);
        
        //Se  todas as notas foram validadas emite as NFS-e/RPS
        if($nfSrv[0]->nf_erro == 0){

            // Executa a o método de geração das NFS-e
            $this->geraNFSe($idRecebimento, $empresa);
        }
        
        //Se usar junção de boleto vamos unir a fatura de Produto/Serviço em uma única fatura
        $par_jbl = DB::table('parametros_fat_empresas')->where('parfat_emp', $empresa)->first();

        if($par_jbl->parfat_jun_bol == 'S'){

            //Primeiro select busca as notas que na mesma OS tem NFe e RPS ou só NFe
            // Esse select tem que ser testado quando tiver peças
            $sql_ped = "SELECT DISTINCT 
                            recnf_num_ped 
                        FROM financeiro_recebimento_notas
                        LEFT JOIN faturamento_nf_headers ON 
                            recnf_num = nfhdr_num AND 
                            recnf_num_ped = nfhdr_num_ped 
                        LEFT JOIN faturamento_nfe_chaves ON 
                            nfechv_num = nfhdr_num AND
                            nfechv_emp = nfhdr_emp
                        LEFT JOIN faturamento_nfe_lotes ON 
                            nfelot_emp = nfechv_emp AND
                            nfelot_key = nfechv_key
                        WHERE 
                            recnf_cod_rec = ".$idRecebimento." AND 
                            nfhdr_fpg = 2 AND
                            nfhdr_cpg <> '00' AND 
                            nfelot_sts = 100
                        ORDER BY recnf_num_ped";
            $pedidos = DB::select($sql_ped);

            foreach($pedidos as $pedido){

                // Executa a o método de junção de boletos
                $this->geraJuncaoBoleto($idRecebimento, $empresa, $pedido->recnf_num_ped);
            }

            //Busca as notas da OS que tem apenas RPS e que ainda não foram lançadas no faturamento_fatura_headers
            // Esse select tem que ser testado quando tiver peças
            $sql_nfs = "SELECT DISTINCT 
                            recnf_num_ped 
                        FROM financeiro_recebimento_notas 
                        LEFT JOIN faturamento_nf_headers ON 
                            recnf_num = nfhdr_num AND 
                            recnf_num_ped = nfhdr_num_ped
                        WHERE
                            recnf_cod_rec = ".$idRecebimento." AND 
                            nfhdr_fpg = 2 AND
                            nfhdr_cpg <> '00' AND
                            nfhdr_nfb = 0 AND
                            (nfhdr_vlr_lqs <> 0 AND nfhdr_vlr_srv <> 0 OR nfhdr_vlr_srv_ter <> 0) AND 
                            nfhdr_vlr_lqm = 0";
            $servicos = DB::select($sql_nfs);

            foreach($servicos as $servico){

                // Executa a o método de junção de boletos
                $this->geraJuncaoBoleto($idRecebimento, $empresa, $servico->recnf_num_ped);
            }

        }
        
        //Vamos gerar a procedure de recebimento que cria os movimentos financeiros sobre as notas
        //Isso acontece quando as notas foram enviadas com sucesso ao SEFAZ quando existe NF-e no recebimento
        //Quando entrar NFe tem que entrar verificação se é nota espelho para gerar o financeiro
        if($nfSrv[0]->nf_erro == 0){

            // Executa a o método de geração do financeiro do recebimento das notas
            $this->geraFinanceiro($idRecebimento, $empresa);
        }

        // Montagem do array das notas recebidas no faturamento
        
        //Busca as notas do recebimento realizado
        $notasReceb = DB::table('financeiro_recebimento_notas')->where('recnf_cod_rec', $idRecebimento)->where('recnf_emp', $empresa)->orderby('recnf_num')->get();

        //Gera um array das NF para utilizar no controleGeracaoNF
        $where_hdr = [];

        foreach($notasReceb as $nota){
            
            // Executa o método de atualização do estagio
            $this->atualizaEstagio($empresa, $nota->recnf_num, 7);

            $where_hdr[] = $nota->recnf_num;
        }

        return view('/faturamento/notas/fat_cnt001_GeracaoNF', [
            'empresa' => $empresa, 
            'origem' => $origem,
            'where_hdr' => $where_hdr,
            'where_completo' => $where, 
            'where_semi' => $where_semi,
            'glo_where_reemissao_nf' => $where_reemissao
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Função de Rateio das Formas de Pagamento utilizadas no Faturamento da Nota Fiscal
    |----------------------------------------------------------------------------------------------------
    */
    public function rateioPgto($notas, $empresa, $idRecebimento)
    {
        $valor_total_notas = 0;

        foreach($notas as $nota){
            $valor_total_notas += $nota[2];
        }
        
        //Busca os dados dos parâmetros de faturamento
        $parFat = DB::table('parametros_fat_empresas')->where('parfat_emp', $empresa)->first();

        if(!empty($parFat->fazer_cmp)){
            $par_forma_pag = $parFat->fazer_cmp;
        }else{
            $par_forma_pag = 2;
        }
        
        //Busca o valor das notas de Serviço
        $nfServ = DB::table('financeiro_recebimento_notas as rec')
        ->join('faturamento_nf_headers as nfh', function ($join) {
            $join->on('nfh.nfhdr_emp', '=', 'rec.recnf_emp')
                ->on('nfh.nfhdr_num', '=', 'rec.recnf_num');
        })
        ->where('rec.recnf_cod_rec', $idRecebimento)
        ->where('nfh.nfhdr_vlr_lqs', '<>', 0)
        ->where('nfh.nfhdr_vlr_lqm', '=', 0)
        ->select('rec.recnf_num', 'rec.recnf_vlr_tot')
        ->get();

        $valor_total_rps = 0;

        // Verifica se encontrou nota de serviço e se encontrou faz a soma dos valores
        if (!$nfServ->isEmpty()) {
            foreach ($nfServ as $rps) {
                $valor_total_rps += $nfServ[0]->recnf_vlr_tot;
            }
        }
        
        $valor_total_pg = 0;
        $count_form_pg = 0;

        //Buscamos as formas de pagamento da nota menos OUT e TRC
        //TRC - Troco não deve ser considerada
        //OUT - Outras é sobre notas a prazo então ela será criada como do tipo 15 na tabela de pagamentos da nfe
        $fomasPgt = DB::table('financeiro_recebimento_valores')->where('recval_emp',$empresa)->where('recval_cod_rec',$idRecebimento)->whereNotIn('recval_tipo', ['OUT', 'TRC'])->get();
        
        if(!$fomasPgt->isEmpty()){
            
            foreach($fomasPgt as $pagamento){
                $valor_total_pg += $pagamento->recval_valor;
                $count_form_pg += 1;
            }

            $troco = 0;

            if(($valor_total_pg - ($valor_total_notas + $valor_total_rps)) > 0){
                $troco = ($valor_total_pg - ($valor_total_notas + $valor_total_rps)) / $count_form_pg;
            }

            foreach($fomasPgt as $pagamento){	

                foreach($notas as $nota){
                    
                    //Verifica se a nota é a prazo e não possui sinal, Caso ela não possua sinal não entra no rateio
                    $sql = "SELECT 
                                CASE 
                                    WHEN nfhdr_vlr_ent = 0 AND nfhdr_cme in(140, 240) THEN 'S' 
                                    WHEN nfhdr_cpg <> '00' AND nfhdr_vlr_ent = 0 and nfhdr_cme in(110, 120, 125, 130, 140, 150, 155, 156, 157, 170, 175, 180, 190, 199, 211, 220, 225, 230, 240, 250, 255, 256, 257, 270, 275, 280, 290, 299, 300) THEN 'S' 
                                    ELSE 'N' END as sinal, 
                                nfhdr_cme 
                            FROM faturamento_nf_headers 
                            WHERE 
                                nfhdr_emp = '".$empresa."' AND 
                                nfhdr_num = ".$nota[1];
                    $verifica_prazo = DB::select($sql);
                    
                    $cmes = array(110, 120, 125, 130, 140, 150, 155, 156, 157, 170, 175, 180, 190, 199, 211, 220, 225, 230, 240, 250, 255, 256, 257, 270, 275, 280, 290, 299, 300);
            
                    //Rateio do Pagamento
                    $total_os = $valor_total_notas + $valor_total_rps;
                    $porcentagem = ($valor_total_notas/$total_os)*100;
                    $valor_final = (($pagamento[2] - $troco)*$porcentagem)/100;

                    $valor = ($nota[2]/$valor_total_notas)*$valor_final;

                    if($pagamento->recval_tipo == 'DIN'){
                        $tipo_pag = '01';
                    }elseif($pagamento->recval_tipo == 'CHQ'){
                        $tipo_pag = '02';
                    }elseif($pagamento->recval_tipo == 'CCT'){
                        $tipo_pag = '03';
                    }elseif($pagamento->recval_tipo == 'CDB'){
                        $tipo_pag = '04';
                    }elseif($pagamento->recval_tipo == 'CCT'){
                        $tipo_pag = '05';
                    }elseif($pagamento->recval_tipo == 'PIX'){
                        $tipo_pag = '17';
                    }else{
                        $tipo_pag = '99';
                    }

                    /* Tratamento para a forma de pagemento para o CMEs */
                    if(in_array($verifica_prazo->nfhdr_cme, $cmes)){
                        $tipo_pag = 90;
                        $valor = 0;
                    }else{

                        if($verifica_prazo->sinal == 'N'){
                            
                            if($par_forma_pag != 2){
                                if($par_forma_pag == 1){
                                    $tipo_pag = '01';
                                }else{
                                    if($tipo_pag != 90){
                                        $tipo_pag = '99';
                                    }
                                }
                            }
                        }
                    }

                    $cnt_pag = DB::table('faturamento_nfe_info_pagamentos')->where('nfepag_num',$nota[1])->where('nfepag_seq',$pagamento->recval_seq)->count();

                    if($cnt_pag == 0){		

                        $infoPG = array();

                        if($tipo_pag == '03' || $tipo_pag == '04'){
                            $info_cartao = m_get_info_cartao($formaPag[3], $formaPag[4]);

                            $infoPG['nfepag_tip_int'] = 1;
                            $infoPG['nfepag_cod_aut'] = $pagamento->recval_crt_com;
                            $infoPG['nfepag_tip_band'] = $pagamento->recval_crt_ban;
                            //Aqui temos um problema, precisa do cnpj da credenciadora do cartão, a ideia é criar uma nova tabela de cadastro apenas de adm de cartão que terá os dados dela como cnpj, 
                            //e quando for cadastrar um razão CC pegaremos dela o codigo pronto para utilizar na tabela de razão onde a mesma não montará mais o codigo
                            //como ainda não vamos envia nf-e vamos deixar assim
                            $infoPG['nfepag_cnpj'] = '';

                        }else{
                            $infoPG['nfepag_tip_int'] = 0;
                            $infoPG['nfepag_cnpj'] = '';
                            $infoPG['nfepag_tip_band'] = null;
                            $infoPG['nfepag_cod_aut'] = '';
                        }

                        $infoPG['nfepag_seq'] = $pagamento->recval_seq;
                        $infoPG['nfepag_tip_pgt'] = $tipo_pag;
                        $infoPG['nfepag_val_pgt'] = $valor;
                        $infoPG['nfepag_val_trc'] = 0;

                        //Insere os dados da informação de pagamento
                        FaturamentoNfeInfoPagamentosController::insert($empresa, $nota[1], $infoPG);
                    }
                }
            }
            
            //Correção no saldo do IPAG
            /* **** Algumas situações com mais de uma nota selecionada para recebimento pode calcular errado o rateio
                    Somando o valor por tipo de recebimento fica OK, Somando por nota uma ficava com 1 centavo a mais e outra com 1 centavo a menos
                    Na Danfe o SEFAZ não faz validação, Porém alguns casos como o SAT é feito a verificação e não é aceito notas com o valor do pagamento menor que o total da nota
                    O problema acontece nesse calcúlo: $valor = ($nota[2]/$valor_total_notas)*$valor_final;
                    Alguns valores quebrados são arredondados de forma que gera problemas de diferença de saldo
            ***** */
            $diferenca = 0;
            $dif_menor = 0;
            
            foreach($notas as $nota){

                $sum = DB::table('faturamento_nfe_info_pagamentos')->where('nfepag_emi', $empresa)->where('nfepag_num', $nota[1])->sum('nfepag_val_pgt');
                
                if($sum != $nota[2]){
                    
                    if ($sum > $nota[2]) {
                        $diferenca = $sum - $nota[2];
                    
                        // Se existe saldo a mais, esse saldo é retirado na SEQ = 1
                        DB::table('faturamento_nfe_info_pagamentos')
                            ->where('nfepag_emi', $empresa)
                            ->where('nfepag_num', $nota[1])
                            ->where('nfepag_seq', 1)
                            ->decrement('nfepag_val_pgt', $diferenca);
                    }
                    
                    if ($sum < $nota[2]) {
                        $dif_menor = $nota[2] - $sum;
                    
                        // Se existe saldo a menos, esse saldo é somado na SEQ = 1
                        DB::table('faturamento_nfe_info_pagamentos')
                            ->where('nfepag_emi', $empresa)
                            ->where('nfepag_num', $nota[1])
                            ->where('nfepag_seq', 1)
                            ->increment('nfepag_val_pgt', $dif_menor);
                    }
                }
            }
        }
        
        $sql = "SELECT  
                    nfhdr_num, 
                    (nfhdr_vlr_tot_nf - nfhdr_vlr_ent) AS saldo,
                    nfhdr_cme  
                FROM faturamento_nf_headers 
                INNER JOIN financeiro_recebimento_notas ON 
                    nfhdr_emp = recnf_emp 
                    AND nfhdr_num = recnf_num
                WHERE 
                    recnf_cod_rec = 98 AND 
                    (
                        nfhdr_cpg <> '00' 
                        OR CASE 
                            WHEN (nfhdr_tor IN ('P', 'S') 
                                AND nfhdr_cme >= 200 
                                AND nfhdr_cme NOT IN (217, 211, 213, 220, 225, 230, 240, 250, 265, 270, 280, 271, 290, 299) 
                                AND CASE 
                                        WHEN nfhdr_cme = 300 AND nfhdr_cfop IN (5949, 6949) THEN 'N'
                                        WHEN nfhdr_cme IN (300, 301, 310, 311, 312) AND nfhdr_cfop NOT IN (512, 612, 591, 691, 5102, 6102, 5551, 6551, 5949, 6949, 5933, 6933) THEN 'N'
                                        ELSE 'S' 
                                    END = 'S' 
                                AND CASE 
                                        WHEN nfhdr_cpg::INTEGER > 0 AND nfhdr_cpg::INTEGER < 99 AND nfhdr_qtd_ppg = 0 THEN 'N'
                                        ELSE 'S' 
                                    END = 'S'
                            ) 
                            THEN 'S' 
                            ELSE 'N' 
                        END = 'N'
                    )";
        $notas_prazo = DB::select($sql);
        
        foreach($notas_prazo as $nota_prazo){

            $cnt_pag_prz = DB::table('faturamento_nfe_info_pagamentos')->where('nfepag_emi',$empresa)->where('nfepag_num',$nota_prazo[0])->where('nfepag_tip_pgt','15')->count();
            
            if($cnt_pag_prz == 0){

                $max_seq = DB::table('faturamento_nfe_info_pagamentos')->where('nfepag_emi',$empresa)->where('nfepag_num',$nota_prazo[0])->max('nfepag_seq');

                // Se não houver registros, começa em 1
                $next_seq = $max_seq ? $max_seq + 1 : 1;

                $tipo_pag = 15;
                $valor = $nota_prazo[1];
                $cmes = array(110, 120, 125, 130, 140, 150, 155, 156, 157, 170, 175, 180, 190, 199, 211, 220, 225, 230, 240, 250, 255, 256, 257, 270, 275, 280, 290, 299, 300);
                
                if(in_array($nota_prazo[2], $cmes)){
                    $tipo_pag = 90;
                    $valor = 0;
                }

                $infoPG = array();

                $infoPG['nfepag_tip_int'] = 0;
                $infoPG['nfepag_cnpj'] = '';
                $infoPG['nfepag_tip_band'] = null;
                $infoPG['nfepag_cod_aut'] = '';
                $infoPG['nfepag_seq'] = $next_seq;
                $infoPG['nfepag_tip_pgt'] = $tipo_pag;
                $infoPG['nfepag_val_pgt'] = $valor;
                $infoPG['nfepag_val_trc'] = 0;

                //Insere os dados da informação de pagamento
                FaturamentoNfeInfoPagamentosController::insert($empresa, $nota_prazo[0], $infoPG);
            }
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Função de gravação do tipo da Nota Faturada
    |----------------------------------------------------------------------------------------------------
    */
    public function gravaTipoNota($nota, $empresa, $tipo)
    {

        $cnt_tip = DB::table('faturamento_nf_tipo_notas')->where('nftnt_emp', $empresa)->where('nftnt_num', $nota)->count();

        //Quando for funcionar fazer o update no else desse if
        if($cnt_tip == 0){

            //Insere os dados do tipo da nota
            FaturamentoNfTipoNotasController::insert($empresa, $nota, $infoPG);
        }
        
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Função de geração da NFS-e/RPS
    |----------------------------------------------------------------------------------------------------
    */
    public function geraNFSe($idRecebimento, $empresa)
    {
        //Busca os parametros da NFS-e/RPS
        $parNFSe = DB::table('parametros_fat_nfs')->where('parnfs_empresa', $empresa)->first();

        //Se a Empresa não emite NFS-e Sai do método
        if($parNFSe->parnfs_utiliza_nfs != 'S'){
            return;
        }
        
        //Busca os pedidos que são de NFS-e (Serviço)
        //Quando tiver NF-e testar esse select se não tras pedido sem serviço
        $pedidos = DB::table('faturamento_nf_headers as hdr')
        ->distinct()
        ->join('financeiro_recebimento_notas as rec', function ($join) {
            $join->on('hdr.nfhdr_emp', '=', 'rec.recnf_emp')
                ->on('hdr.nfhdr_num', '=', 'rec.recnf_num');
        })
        ->where('hdr.nfhdr_emp', $empresa)
        ->where('rec.recnf_cod_rec', $idRecebimento)
        ->where('hdr.nfhdr_vlr_lqs', '<>', 0)
        ->where('hdr.nfhdr_vlr_lqm', 0)
        ->pluck('hdr.nfhdr_num_ped');
        
        if(!empty($pedidos)){

            foreach($pedidos as $pedido){
                
                $notasSrv = DB::table('faturamento_nf_headers as hdr')
                ->select('hdr.nfhdr_num')
                ->join('financeiro_recebimento_notas as rec', function ($join) {
                    $join->on('hdr.nfhdr_emp', '=', 'rec.recnf_emp')
                        ->on('hdr.nfhdr_num', '=', 'rec.recnf_num');
                })
                ->where('hdr.nfhdr_emp', $empresa)
                ->where('hdr.nfhdr_num_ped', $pedido)
                ->where('rec.recnf_cod_rec', $idRecebimento)
                ->where(function ($query) {
                    $query->where('hdr.nfhdr_vlr_lqs', '<>', 0)
                        ->where('hdr.nfhdr_vlr_srv', '<>', 0)
                        ->orWhere(function ($q) {
                            $q->where('hdr.nfhdr_vlr_srv_ter', '<>', 0)
                                ->whereIn('hdr.nfhdr_tor', ['S'])
                                ->where('hdr.nfhdr_vlr_lqm', 0);
                        });
                })
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('faturamento_nfs as nfs')
                        ->whereColumn('nfs.nfs_emp', 'hdr.nfhdr_emp')
                        ->whereColumn('nfs.nfs_nfhdr_num', 'hdr.nfhdr_num');
                })
                ->get();

                // Executa o método de atualização da numeração da NFS-e
                $this->atualizaNumNFSe($idRecebimento, $empresa, $notasSrv[0]->nfhdr_num);

                // Executa o método de atualização do estagio
                $this->atualizaEstagio($empresa, $notasSrv[0]->nfhdr_num, 0);

                //Inicia o Database Transaction
                DB::beginTransaction();

                //Gera da tabela de nota fiscal de serviço
                $exec_fn = DB::select("select ret_sts, ret_msg from fn_faturamento_gera_nfs('".$empresa."',".$notasSrv[0]->nfhdr_num.");");

                // Aqui temos que forçar um erro nessa procedure e ver como proceder, hoje da erro nela gera a global e continua mas não pode continuar

                if($exec_fn[0]->ret_sts == '*'){
                    //Falha, desfaz as alterações no banco de dados
                    DB::rollBack();

                    $erroAtu = Session::get('glo_fat_pnl001_EmissaoNF_erro_geracao_nf');
                    $erro = ($erroAtu ? "</br></br>" : "") . $exec_fn[0]->ret_msg;
                    Session::put('glo_fat_pnl001_EmissaoNF_erro_geracao_nf', $erro);
                }else{

                    //Atualiza os dados da NF para Geração I - Iniciada
                    DB::table('faturamento_nf_headers')
                    ->where('nfhdr_emp', $empresa)
                    ->where('nfhdr_num', $notasSrv[0]->nfhdr_num)
                    ->update(['nfhdr_sts' => 'I']);

                    // Executa o método de atualização do estagio
                    $this->atualizaEstagio($empresa, $notasSrv[0]->nfhdr_num, 1);

                    //Grava as alterações do banco
                    DB::commit();
                }
                
                //Gera e envia o xml da NFS-e
                $nfsxml = new Nfsxml($empresa, $notasSrv[0]->nfhdr_num);
                $nfsxml->emitirNFS();

                $dadosNF = DB::table('faturamento_nf_headers')->where('nfhdr_emp', $empresa)->where('nfhdr_num', $notasSrv[0]->nfhdr_num)->get();

                $dataGeracaoNF = date('Y-m-d');
                $horaGeracaoNF = date('Hi');

                $stsEnvio = DB::table('faturamento_nfs_xml_envios')->where('nfsenv_emp', $empresa)->where('nfsenv_nfhdr_num', $notasSrv[0]->nfhdr_num)->get();

                if($stsEnvio[0]->nfsenv_sts == 3){
                    $status = 'G';

                    // Executa o método de atualização do estagio
                    $this->atualizaEstagio($empresa, $notasSrv[0]->nfhdr_num, 2);

                }else{
                    $status = 'E';
                }

                // Atualiza os dados da NFS-e
                DB::table('faturamento_nf_headers')
                        ->where('nfhdr_emp', $empresa)
                        ->where('nfhdr_num', $notasSrv[0]->nfhdr_num)
                        ->update(['nfhdr_sts' => $status,
                        'nfhdr_dt_nf' => $dataGeracaoNF,
                        'nfhdr_hr_nf' => $horaGeracaoNF]);
                
                DB::table('faturamento_nfs')
                ->where('nfs_emp', $empresa)
                ->where('nfs_nfhdr_num', $notasSrv[0]->nfhdr_num)
                ->update(['nfs_sts' => $status,
                    'nfs_dt_emi' => $dataGeracaoNF,
                    'nfs_hr_emi' => $horaGeracaoNF]);

                // Envio do email automático para o cliente com a NFS-e
                if($stsEnvio[0]->nfsenv_sts == 3){

                    //Verifica se envia a NFS-e no email do cliente
                    $parFat = DB::table('parametros_fat_empresas')->where('parfat_emp', $empresa)->first();
                    $dadosNfs = DB::table('faturamento_nfs')->where('nfs_emp', $empresa)->where('nfs_nfhdr_num', $notasSrv[0]->nfhdr_num)->first();
                    $dadosCli = DB::table('cadastro_clientes')->where('cliente_codigo', $dadosNfs->nfs_cli)->first();
                    $dadosEmp = DB::table('cadastro_empresas')->where('empresa_codigo', $empresa)->first();
        
                    if($parFat->parfat_env_nfs_email == 'S' && !empty($dadosCli->cliente_email) && !empty($dadosEmp->empresa_smtp_host)){
                    
                        $filePath = FaturamentoNotasImpressaoController::nfseGerarPDF($empresa, $notasSrv[0]->nfhdr_num, 'EMAIL');
        
                        // Configuração dinâmica do SMTP para o envio pela empresa
                        config([
                            'mail.mailers.smtp_cliente.host' => $dadosEmp->empresa_smtp_host,
                            'mail.mailers.smtp_cliente.port' => $dadosEmp->empresa_smtp_port,
                            'mail.mailers.smtp_cliente.encryption' => $dadosEmp->empresa_smtp_encryption,
                            'mail.mailers.smtp_cliente.username' => $dadosEmp->empresa_smtp_username,
                            'mail.mailers.smtp_cliente.password' => $dadosEmp->empresa_smtp_password,
                        ]);
        
                        // Usar o mailer específico da empresa
                        Mail::mailer('smtp_cliente')->to($dadosCli->cliente_email)->send(new EmailNFS($dadosNfs, $dadosEmp->empresa_smtp_from_address, $filePath));
        
                        // Atualiza o status para indicar que o e-mail foi enviado
                        DB::table('faturamento_nfs')
                        ->where('nfs_emp', $empresa)
                        ->where('nfs_nfhdr_num', $notasSrv[0]->nfhdr_num)
                        ->update(['nfs_nfs_env_email' => 'S']);                
        
                    }
                }

                // Gera os documentos e atualiza a nota fiscal após envio para o SEFAZ / Prefeitura
                $this->atualizaDocumentos($empresa, $idRecebimento, $notasSrv[0]->nfhdr_num);

            }
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Função de atualização da numeração da NFS-e/RPS
    |----------------------------------------------------------------------------------------------------
    */
    public function atualizaNumNFSe($idRecebimento, $empresa, $nfhdr_num)
    {
        //Busca a numeração da NFS-e
        $parNFSe = DB::table('parametros_fat_nfs')->where('parnfs_empresa', $empresa)->first();

        DB::table('faturamento_nf_headers')
        ->where('nfhdr_emp', $empresa)
        ->where('nfhdr_num', $nfhdr_num)
        ->update([
            'nfhdr_num_nf' => $parNFSe->parnfs_numeracao,
            'nfhdr_ser_nf' => $parNFSe->parnfs_serie
        ]);

        DB::table('financeiro_recebimento_notas')
        ->where('recnf_emp', $empresa)
        ->where('recnf_cod_rec', $idRecebimento)
        ->where('recnf_num', $nfhdr_num)
        ->update([
            'recnf_num_nf' => $parNFSe->parnfs_numeracao,
            'recnf_ser_nf' => $parNFSe->parnfs_serie
        ]);

        DB::table('financeiro_recebimento_valores')
        ->where('recval_emp', $empresa)
        ->where('recval_cod_rec', $idRecebimento)
        ->where('recval_nf_num', $nfhdr_num)
        ->where('recval_tipo', 'OUT')
        ->update([
            'recval_nf_nnf' => $parNFSe->parnfs_numeracao,
            'recval_nf_nsr' => $parNFSe->parnfs_serie
        ]);

        // Incrementa a numeração em 1
        DB::table('parametros_fat_nfs')->where('parnfs_empresa', $empresa)->increment('parnfs_numeracao', 1);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Função de atualização do estágio executado
    |----------------------------------------------------------------------------------------------------
    */
    public function atualizaEstagio($empresa, $nfhdr_num, $estagio)
    {
        //Busca a numeração da NFS-e
        $cntEtg = DB::table('faturamento_nf_estagios')->where('nfetg_emp', $empresa)->where('nfetg_num', $nfhdr_num)->exists();

        if(!$cntEtg) {

            DB::table('faturamento_nf_estagios')
            ->insert([
                'nfetg_emp' => $empresa,
                'nfetg_num' => $nfhdr_num,
                'nfetg_cod' => $estagio
            ]);

        }else{

            DB::table('faturamento_nf_estagios')
            ->where('nfetg_emp', $empresa)
            ->where('nfetg_num', $nfhdr_num)
            ->update([
                'nfetg_cod' => $estagio
            ]);
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Função de atualização da nota fiscal e geração dos documentos
    |----------------------------------------------------------------------------------------------------
    */
    public function atualizaDocumentos($empresa, $idRecebimento, $nfhdr_num)
    {
        $dadosFatHDR = DB::table('faturamento_nf_headers')->where('nfhdr_emp', $empresa)->where('nfhdr_num', $nfhdr_num)->first();

        //Inicia o Database Transaction
        DB::beginTransaction();

        // Gera a tabela de documentos e atualiza a nota fiscal
        // Ainda não temos a tabela de documentos, ela será feita quando fizer produtos
        // Porem se precisar antes da tabela aqui já deve ser criado

        //$exec_fn = DB::select("select ret_sts, ret_msg from fn_produtos_gera_documentos();");
        $ret_teste = "";

        //if($exec_fn[0]->ret_sts == '*'){
        if($ret_teste == '*'){

            //Falha, desfaz as alterações no banco de dados
            DB::rollBack();

            //$erroAtu = Session::get('glo_fat_pnl001_EmissaoNF_erro_geracao_nf');
            //$erro = ($erroAtu ? "</br></br>" : "") . $exec_fn[0]->ret_msg;
            //Session::put('glo_fat_pnl001_EmissaoNF_erro_geracao_nf', $erro);
        }else{

            //Grava as alterações do banco
            DB::commit();

            // Executa o método de atualização do estagio
            $this->atualizaEstagio($empresa, $nfhdr_num, 3);

            //Executa a function apenas para notas a prazo e se não for cupom (espelho)
            if($dadosFatHDR->nfhdr_cpg != '00' && $dadosFatHDR->nfhdr_cme != 410){

                // Vamos Atualiza/Gerar os dados da Fatura a prazo
                $this->atualizaFatura($empresa, $idRecebimento, $nfhdr_num);
            }
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Função de atualização dos dados da Fatura a prazo
    |----------------------------------------------------------------------------------------------------
    */
    public function atualizaFatura($empresa, $idRecebimento, $nfhdr_num)
    {
        $dadosFatHDR = DB::table('faturamento_nf_headers')->where('nfhdr_emp', $empresa)->where('nfhdr_num', $nfhdr_num)->first();
        
        if(!empty($dadosFatHDR->nfhdr_nfb) && $dadosFatHDR->nfhdr_nf > 0){

            // Geração do Movimento Financeiro e Contas a Receber de Clientes
            $this->geraMovimentoCRC($empresa, $dadosFatHDR->nfhdr_nfb);

        }else{

            // Executa a function de geração da Fatura de venda a prazo
            $this->geraFatura($empresa, $nfhdr_num);
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Função de geração da fatura de vendas a prazo
    |----------------------------------------------------------------------------------------------------
    */
    public function geraFatura($empresa, $nfhdr_num)
    {
        //Inicia o Database Transaction
        DB::beginTransaction();
       
        // Executa a function de geração da Fatura
        $exec_fn = DB::select("select ret_sts, ret_msg, ret_num_fat from fn_faturamento_gera_fatura('".$empresa."', ".$nfhdr_num.");");

        if($exec_fn[0]->ret_sts == '*'){

            //Falha, desfaz as alterações no banco de dados
            DB::rollBack();

            $erroAtu = Session::get('glo_fat_pnl001_EmissaoNF_erro_geracao_nf');
            $erro = ($erroAtu ? "</br></br>" : "") . $exec_fn[0]->ret_msg;
            Session::put('glo_fat_pnl001_EmissaoNF_erro_geracao_nf', $erro);
        }else{

            //Grava as alterações do banco
            DB::commit();

            // Executa o método de atualização do estagio
            $this->atualizaEstagio($empresa, $nfhdr_num, 4);

            // Geração do Movimento Financeiro e Contas a Receber de Clientes
            $this->geraMovimentoCRC($empresa, $exec_fn[0]->ret_num_fat);
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Função de Geração do Movimento Financeiro e Contas a Receber de Clientes
    |----------------------------------------------------------------------------------------------------
    */
    public function geraMovimentoCRC($empresa, $fatura)
    {
        //Inicia o Database Transaction
        DB::beginTransaction();

        $data = date('Y-m-d');
        $app = "fat_pnl001_EmissaoNF";
        $usuario = Auth::user()->usuario_codigo;

        // Executa a function de geração da Fatura
        $exec_fn = DB::select("select ret_sts_pro003, ret_msg_pro003, ret_exe_pro003 from fn_financeiro_pro003('".$empresa."', ".$fatura.",'".$data."','".$usuario."','".$app."');");

        if($exec_fn[0]->ret_sts_pro003 == '*'){

            //Falha, desfaz as alterações no banco de dados
            DB::rollBack();

            $erroAtu = Session::get('glo_fat_pnl001_EmissaoNF_erro_geracao_nf');
            $erro = ($erroAtu ? "</br></br>" : "") . $exec_fn[0]->ret_msg_pro003;
            Session::put('glo_fat_pnl001_EmissaoNF_erro_geracao_nf', $erro);
        }else{

            //Grava as alterações do banco
            DB::commit();

            // Verificamos se o processo foi executado
            // Se utiliza junção de boleto só vai gerar o movimento e a conta a receber depois de executar a function fn_faturamento_fatura_jun_bol
            // Se ainda não gerou a junção de boleto se a empresa utilizar o esse processo os dados estarão na tabela temporária e não será gerado os dados e não devemos atualizar o estagio ainda
            if($exec_fn[0]->ret_exe_pro003 == 'S'){

                $dadosFatura = DB::table('faturamento_fatura_notas')->where('ftrnf_emp',$empresa)->where('ftrnf_nfb',$fatura)->get();

                //Utilizando a junção de boleto podemos ter a nota de produto e a de serviço e ambas devem ter o estágio atualizado
                foreach($dadosFatura as $nota){

                    // Executa o método de atualização do estagio
                    $this->atualizaEstagio($empresa, $nota->ftrnf_num, 5);
                }

            }
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Função de Geração da Junção de Boletos do Faturamento a Prazo
    |----------------------------------------------------------------------------------------------------
    */
    public function geraJuncaoBoleto($idRecebimento, $empresa, $nfhdr_num_ped)
    {
        $erroAtu = Session::get('glo_fat_pnl001_EmissaoNF_erro_geracao_nf');

        if(!empty($erroAtu)){
            return;
        }

        //Inicia o Database Transaction
        DB::beginTransaction();

        $sql = "SELECT DISTINCT 
                    nfhdr_num_ped,
                    nfhdr_cli
                FROM faturamento_nf_headers 
                INNER JOIN financeiro_recebimento_notas ON 
                    recnf_emp = nfhdr_emp AND 
                    recnf_num = nfhdr_num 
                WHERE 
                    nfhdr_emp =  '".$empresa."' AND 
                    recnf_cod_rec = ".$idRecebimento." AND 
                    nfhdr_num_ped = ".$nfhdr_num_ped;
        $pedido = DB::select($sql);

        $ped_os = $pedido[0]->nfhdr_num_ped;
        $cliente = $pedido[0]->nfhdr_cli;

        // Executa a function de geração da Fatura
        $exec_fn = DB::select("select ret_sts, ret_msg, ret_fatura from fn_faturamento_fatura_jun_bol('".$empresa."', ".$ped_os.", '".$cliente."');");

        if($exec_fn[0]->ret_sts == '*'){

            //Falha, desfaz as alterações no banco de dados
            DB::rollBack();

            $erroAtu = Session::get('glo_fat_pnl001_EmissaoNF_erro_geracao_nf');
            $erro = ($erroAtu ? "</br></br>" : "") . $exec_fn[0]->ret_msg;
            Session::put('glo_fat_pnl001_EmissaoNF_erro_geracao_nf', $erro);
        }else{

            //Grava as alterações do banco
            DB::commit();

            // Geração do Movimento Financeiro e Contas a Receber de Clientes
            $this->geraMovimentoCRC($empresa, $exec_fn[0]->ret_fatura);
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Função de geração dos movimentos financeiros
    |----------------------------------------------------------------------------------------------------
    */
    public function geraFinanceiro($idRecebimento, $empresa)
    {

        $dadosNota = DB::table('financeiro_recebimento_notas')
        ->distinct()
        ->where('recnf_cod_rec', $idRecebimento)
        ->where('recnf_emp', $empresa)
        ->select('recnf_cli')
        ->get();

        $clienteFN = $dadosNota[0]->recnf_cli;
        $appFN = "fat_pnl001_EmissaoNF";
        $dataFN = date('Y-m-d');
        $usuarioFN = Auth::user()->usuario_codigo;


        //Inicia o Database Transaction
        DB::beginTransaction();
        
        $exec_fn = DB::select("select ret_sts_rec, ret_msg_rec from fn_financeiro_recebimento('".$empresa."','".$dataFN."',".$idRecebimento.",'".$clienteFN."','".$usuarioFN."','".$appFN."');");

        if($exec_fn[0]->ret_sts_rec == '*'){
            //Falha, desfaz as alterações no banco de dados
            DB::rollBack();

            $erroAtu = Session::get('glo_fat_pnl001_EmissaoNF_erro_geracao_nf');
            $erro = ($erroAtu ? "</br></br>" : "") . $exec_fn[0]->ret_msg_rec;
            Session::put('glo_fat_pnl001_EmissaoNF_erro_geracao_nf', $erro);
        }else{

            //Busca as notas do recebimento realizado
            $notasReceb = DB::table('financeiro_recebimento_notas')->where('recnf_cod_rec', $idRecebimento)->where('recnf_emp', $empresa)->orderby('recnf_num')->get();

            foreach($notasReceb as $nota){

                // Executa o método de atualização do estagio
                $this->atualizaEstagio($empresa, $nota->recnf_num, 6);
            }
            
            //Grava as alterações do banco
            DB::commit();
        }
    }
    
    public function gerarNF($origem, $empresa, $nfReemissao)
    {

        if($origem == 'EMISSAO'){//Gera dados da Emissão de Notas

            $idRecebimento = session('glo_recebimento_id');
            $where = session('glo_emissao_nf_where_completo');
            $where_semi = session('glo_emissao_nf_where_semi');
            $where_reemissao = '';

            //Busca as notas do recebimento realizado
            $notasReceb = DB::table('financeiro_recebimento_notas')->where('recnf_cod_rec', $idRecebimento)->where('recnf_emp', $empresa)->orderby('recnf_num')->get();

            //Gera um array das NF para utilizar no controleGeracaoNF
            $where_hdr = [];

            foreach($notasReceb as $nota){
                $where_hdr[] = $nota->recnf_num;
            }

        }elseif($origem == 'REEMISSAO' || $origem == 'REEMISSAO_SIMP'){//Gera dados da Reemissão de Notas

            $idRecebimento = '';
            $where = '';
            $where_semi = '';
            $where_reemissao = session('glo_where_reemissao_nf');

            //Gera um array das NF para utilizar no controleGeracaoNF
            $where_hdr = [];
            $where_hdr[] = $nfReemissao;
        }

        $notasHDR = DB::table('faturamento_nf_headers')->where('nfhdr_emp', $empresa)->wherein('nfhdr_num', $where_hdr)->orderby('nfhdr_num')->get();
        
        foreach($notasHDR as $nota){

            //Inicia o Database Transaction
            DB::beginTransaction();

            //Gera da tabela de nota fiscal de serviço
            $exec_fn = DB::select("select ret_sts, ret_msg from fn_faturamento_gera_nfs('".$empresa."',".$nota->nfhdr_num.");");

            if($exec_fn[0]->ret_sts == '*'){
                //Falha, desfaz as alterações no banco de dados
                DB::rollBack();
                return redirect()->back()->with('error', $exec_fn[0]->ret_msg);
            }else{

                //Atualiza os dados da NF para Geração I - Iniciada
                DB::table('faturamento_nf_headers')
                ->where('nfhdr_emp', $empresa)
                ->where('nfhdr_num', $nota->nfhdr_num)
                ->update(['nfhdr_sts' => 'I']);

                //Grava as alterações do banco
                DB::commit();
            }

            //Gera o xml de envio
            $nfsxml = new Nfsxml($empresa, $nota->nfhdr_num);
            $nfsxml->emitirNFS();

            $dadosNF = DB::table('faturamento_nf_headers')->where('nfhdr_emp', $empresa)->where('nfhdr_num', $nota->nfhdr_num)->get();

            $dataGeracaoNF = date('Y-m-d');
            $horaGeracaoNF = date('Hi');

            $stsEnvio = DB::table('faturamento_nfs_xml_envios')->where('nfsenv_emp', $empresa)->where('nfsenv_nfhdr_num', $nota->nfhdr_num)->get();

            if($stsEnvio[0]->nfsenv_sts == 3){
                $status = 'G';
            }else{
                $status = 'E';
            }

            //Atualiza os dados da NF
            DB::table('faturamento_nf_headers')
                    ->where('nfhdr_emp', $empresa)
                    ->where('nfhdr_num', $nota->nfhdr_num)
                    ->update(['nfhdr_sts' => $status,
                    'nfhdr_dt_nf' => $dataGeracaoNF,
                    'nfhdr_hr_nf' => $horaGeracaoNF]);
            
            DB::table('faturamento_nfs')
            ->where('nfs_emp', $empresa)
            ->where('nfs_nfhdr_num', $nota->nfhdr_num)
            ->update(['nfs_sts' => $status,
                'nfs_dt_emi' => $dataGeracaoNF,
                'nfs_hr_emi' => $horaGeracaoNF]);

            //Verifica a Origem da NF
            if($origem == 'EMISSAO'){
    
                //Busca a nota do faturamento para pegar o numero e serie da NF
                $notaHDR = DB::table('faturamento_nf_headers')
                ->where('nfhdr_emp', $empresa)
                ->where('nfhdr_num', $nota->nfhdr_num)
                ->first();

                DB::table('financeiro_recebimento_notas')
                ->where('recnf_cod_rec', $idRecebimento)
                ->where('recnf_emp', $empresa)
                ->where('recnf_num', $nota->nfhdr_num)
                ->update(['recnf_num_nf' => $notaHDR->nfhdr_num_nf,
                    'recnf_ser_nf' => $notaHDR->nfhdr_ser_nf,
                    'recnf_dt_nf' => $notaHDR->nfhdr_dt_nf]);
            }
        }

        //Verifica a Origem da NF
        if($origem == 'EMISSAO'){

            DB::table('financeiro_recebimento_headers')
            ->where('rechdr_id', $idRecebimento)
            ->where('rechdr_emp', $empresa)
            ->update(['rechdr_sts' => 'F']);
        }

        if($stsEnvio[0]->nfsenv_sts == 3){

            //Verifica se envia a NFS-e no email do cliente
            $parFat = DB::table('parametros_fat_empresas')->where('parfat_emp', $empresa)->first();
            $dadosNfs = DB::table('faturamento_nfs')->where('nfs_emp', $empresa)->where('nfs_nfhdr_num', $nota->nfhdr_num)->first();
            $dadosCli = DB::table('cadastro_clientes')->where('cliente_codigo', $dadosNfs->nfs_cli)->first();
            $dadosEmp = DB::table('cadastro_empresas')->where('empresa_codigo', $empresa)->first();

            if($parFat->parfat_env_nfs_email == 'S' && !empty($dadosCli->cliente_email) && !empty($dadosEmp->empresa_smtp_host)){
            
                $filePath = FaturamentoNotasImpressaoController::nfseGerarPDF($empresa, $nota->nfhdr_num, 'EMAIL');

                // Configuração dinâmica do SMTP para o envio pela empresa
                config([
                    'mail.mailers.smtp_cliente.host' => $dadosEmp->empresa_smtp_host,
                    'mail.mailers.smtp_cliente.port' => $dadosEmp->empresa_smtp_port,
                    'mail.mailers.smtp_cliente.encryption' => $dadosEmp->empresa_smtp_encryption,
                    'mail.mailers.smtp_cliente.username' => $dadosEmp->empresa_smtp_username,
                    'mail.mailers.smtp_cliente.password' => $dadosEmp->empresa_smtp_password,
                ]);

                // Usar o mailer específico da empresa
                Mail::mailer('smtp_cliente')->to($dadosCli->cliente_email)->send(new EmailNFS($dadosNfs, $dadosEmp->empresa_smtp_from_address, $filePath));

                // Atualiza o status para indicar que o e-mail foi enviado
                DB::table('faturamento_nfs')
                ->where('nfs_emp', $empresa)
                ->where('nfs_nfhdr_num', $nota->nfhdr_num)
                ->update(['nfs_nfs_env_email' => 'S']);                

            }
        }
        
        return view('/faturamento/notas/fat_cnt001_GeracaoNF', [
            'empresa' => $empresa, 
            'origem' => $origem,
            'where_hdr' => $where_hdr,
            'where_completo' => $where, 
            'where_semi' => $where_semi,
            'glo_where_reemissao_nf' => $where_reemissao
        ]);
    }

    public function abrirXml($xml,$nf,$empresa)
    {
        $xmlContent = base64_decode($xml);

        // Crie o nome do arquivo
        $fileName = 'xml_'.$empresa .'_'.$nf.'.xml';

        // Crie o cabeçalho para a resposta
        $headers = [
            'Content-Type' => 'application/xml',
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
        ];

        // Retorne o XML como resposta
        return response($xmlContent, 200)->withHeaders($headers);
    }
}
