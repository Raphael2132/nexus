<?php

namespace App\Http\Controllers\Faturamento\Nota\Emissao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Financeiro\Recebimento\FaturamentoNfHeader;
use stdClass;
use App\Http\Helpers\Helper;
use App\Http\Helpers\HelperFinanceiro;
use App\Http\Controllers\Financeiro\Recebimento\FinanceiroRecebimentoHeaderController;

class PainelEmissaoNfController extends Controller
{

    /* *****
    |*****************************************************************************************************************************
    |*****************************************************************************************************************************
    |
    | Emissão de NF
    |
    |*****************************************************************************************************************************
    |*****************************************************************************************************************************
    ***** */

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de execução do filtro inicial da Emissão de NF
    |----------------------------------------------------------------------------------------------------
    */
    public function emissaoNF()
    {    
        //Inicia as globais da emissão
        Session::put('glo_emissao_nf_where_completo', '');
        Session::put('glo_emissao_nf_where_semi', '');
        Session::put('glo_recebimento_empresa', '');
        Session::put('glo_recebimento_cliente', '');
        Session::put('glo_recebimento_id', '');
        Session::put('glo_recebimento_origem', '');

        return view('/faturamento/notas/fat_cnt001_EmissaoNF');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de execução da consulta de Clientes x NF em aberto para Faturamento pelo Filtro
    |----------------------------------------------------------------------------------------------------
    */
    public function filtroConsultaNF(Request $request)
    {
        $where = "";
        $where_semi = "";

        //Empresa
        if(!empty($request->empresa)){
            $where = " and nfhdr_emp = '".$request->empresa."' ";
        }

        //Cliente
        if(!empty($request->cliente)){
            $cliente = substr($request->cliente, 0, 10);

            if(strlen($cliente) < 10){
                return redirect()->back()->with('error', 'Código do cliente '.$cliente.' é inválido!');
            }

            $cnt_cli = DB::table('cadastro_clientes')->where('cliente_codigo', $cliente)->count();
        
            if($cnt_cli == 0){
                return redirect()->back()->with('error', 'Código do cliente '.$cliente.' não existe!');
            }else{
                $where .= " and nfhdr_cli = '".$cliente."' ";
            }
        }

        //Numero da OS/Pedido
        if(!empty($request->numOS)){
            $where .= " and nfhdr_num_ped = '".$request->numOS."' ";
            $where_semi .= " and nfhdr_num_ped = '".$request->numOS."' ";
        }

        //Data de Inicio / Final
        if(!empty($request->dtIniOS) && !empty($request->dtFinOS)){
            $dt_ini = Helper::limpaData($request->dtIniOS);
            $dt_fin = Helper::limpaData($request->dtFinOS);
            if($dt_ini > $dt_fin){
                return redirect()->back()->with('error', 'A Data Inicial não pode ser maior que a Data Final!');
            }
            $where .= " and nfhdr_dt_ped between '".$dt_ini."' and '".$dt_fin."' ";
            $where_semi .= " and nfhdr_dt_ped between '".$dt_ini."' and '".$dt_fin."' ";
        }elseif(empty($request->dtIniOS) && !empty($request->dtFinOS)){
            $dt_fin = Helper::limpaData($request->dtFinOS);
            $where .= " and nfhdr_dt_ped <= '".$dt_fin."' ";
            $where_semi .= " and nfhdr_dt_ped <= '".$dt_fin."' ";
        }elseif(!empty($request->dtIniOS) && empty($request->dtFinOS)){
            $dt_ini = Helper::limpaData($request->dtIniOS);
            $where .= " and nfhdr_dt_ped >= '".$dt_ini."' ";
            $where_semi .= " and nfhdr_dt_ped >= '".$dt_ini."' ";
        }

        //Valor de Inicio / Final
        if(!empty($request->vlrIniOS) && !empty($request->vlrFinOS)){
            $vlr_ini = Helper::limpaValorMonetario($request->vlrIniOS);
            $vlr_fin = Helper::limpaValorMonetario($request->vlrFinOS);
            if($vlr_ini > $vlr_fin){
                return redirect()->back()->with('error', 'O Valor Inicial não pode ser maior que o Valor Final!');
            }
            $where .= " and nfhdr_vlr_tot_nf between ".$vlr_ini." and ".$vlr_fin;
            $where_semi .= " and nfhdr_vlr_tot_nf between ".$vlr_ini." and ".$vlr_fin;
        }elseif(empty($request->vlrIniOS) && !empty($request->vlrFinOS)){
            $vlr_fin = Helper::limpaValorMonetario($request->vlrFinOS);
            $where .= " and nfhdr_vlr_tot_nf <= ".$vlr_fin;
            $where_semi .= " and nfhdr_vlr_tot_nf <= ".$vlr_fin;
        }elseif(!empty($request->vlrIniOS) && empty($request->vlrFinOS)){
            $vlr_ini = Helper::limpaValorMonetario($request->vlrIniOS);
            $where .= " and nfhdr_vlr_tot_nf >= ".$vlr_ini;
            $where_semi .= " and nfhdr_vlr_tot_nf >= ".$vlr_ini;
        }

        //Gera as globais sobre o filtro aplicado
        Session::put('glo_emissao_nf_where_completo', $where);
        Session::put('glo_emissao_nf_where_semi', $where_semi);

        $dados = DB::select("select nfhdr_emp, nfhdr_cli, count(nfhdr_num_ped) as qtd_reg, sum(nfhdr_vlr_tot_nf) as total_pedidos from faturamento_nf_headers where nfhdr_sts = 'A' and nfhdr_ori in('01') ".$where." group by nfhdr_emp, nfhdr_cli order by nfhdr_cli asc");

        return view('/faturamento/notas/fat_cns001_EmissaoNF',[
            'dadosCliente'=>$dados
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de execução da consulta de Clientes x NF em aberto para Faturamento pelo botão voltar e barra de navegação rápida
    |----------------------------------------------------------------------------------------------------
    */
    public function consultaNF()
    {
        $where = Session::get('glo_emissao_nf_where_completo');

        $dados = DB::select("select nfhdr_emp, nfhdr_cli, count(nfhdr_num_ped) as qtd_reg, sum(nfhdr_vlr_tot_nf) as total_pedidos from vi_faturamento_pnl001_sel_notas where nfhdr_sts = 'A' and nfhdr_ori in('01') ".$where." group by nfhdr_emp, nfhdr_cli order by nfhdr_cli asc");

        return view('/faturamento/notas/fat_cns001_EmissaoNF',[
            'dadosCliente'=>$dados
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de início do processo de Faturamento da NF gerando o Header e o ID do recebimento
    |----------------------------------------------------------------------------------------------------
    */
    public function painelNF($empresa, $cliente)
    {
        //Pega as variaveis globais da app
        $where_semi = Session::get('glo_emissao_nf_where_semi');

        //Gera as globais da empresa e cliente do faruramento
        Session::put('glo_recebimento_empresa', $empresa);
        Session::put('glo_recebimento_cliente', $cliente);
        Session::put('glo_recebimento_origem', 'NFV');

        // Chama o método que faz a exclusão de todos os recebimentos em aberto com data menor que a do dia atual
        HelperFinanceiro::limpaRecebimentoAberto();

        // Chama o método que faz a exclusão de todos os recebimentos em aberto do usuário
        HelperFinanceiro::limpaRecebimentoUsuario(Auth::user()->usuario_codigo);

        //Verificar se o Usuario do recebimento tem Razão Associado
        $usuario = Auth::user()->usuario_codigo;
        $dadosUsuFin = DB::table('financeiro_tab_usuarios')->where('tabusu_usuario', $usuario)->where('tabusu_empresa', $empresa)->first();

        if(empty($dadosUsuFin->tabusu_razao)){
            return redirect()->back()->with('info', 'Usuario do logado no sistema não tem razão associado!');
        }

        //Insere o Header da tabela de recebimentos
        $idRecebimento = FinanceiroRecebimentoHeaderController::insert($empresa, 'NFV');
        Session::put('glo_recebimento_id', $idRecebimento);

        $dados = DB::select("select * from vi_faturamento_pnl001_sel_notas where nfhdr_sts = 'A' and nfhdr_ori in('01') ".$where_semi." and nfhdr_emp = '".$empresa."' and nfhdr_cli = '".$cliente."' order by nfhdr_num_ped asc");

        return view('/faturamento/notas/fat_pnl001_EmissaoNF',[
            'dadosCliente'=>$dados, 
            'empresaREC' => $empresa, 
            'clienteREC' => $cliente, 
            'idRecebimento' => $idRecebimento, 
            'estagio_app' => 'SELECAO_NF'
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Retorna para a Seleção de NF após Inclusão/Exclusão de Notas
    |----------------------------------------------------------------------------------------------------
    */
    public function painelSelecaoNF($empresa, $cliente)
    {
        //Vamos passar como variavel no redirect por que depois de 2 redirect elas são destruidas
        $where_semi = Session::get('glo_emissao_nf_where_semi');
        $idRecebimento = Session::get('glo_recebimento_id');

        $dados = DB::select("select * from vi_faturamento_pnl001_sel_notas where nfhdr_sts = 'A' and nfhdr_ori in('01') ".$where_semi." and nfhdr_emp = '".$empresa."' and nfhdr_cli = '".$cliente."' order by nfhdr_num_ped asc");

        return view('/faturamento/notas/fat_pnl001_EmissaoNF',[
            'dadosCliente'=>$dados, 
            'empresaREC' => $empresa, 
            'clienteREC' => $cliente, 
            'idRecebimento' => $idRecebimento,
            'estagio_app' => 'SELECAO_NF'
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Reabre a seleção de NF para faturamento
    |----------------------------------------------------------------------------------------------------
    */
    public function painelReabreSelNF($empresa, $cliente)
    {
        //Vamos passar como variavel no redirect por que depois de 2 redirect elas são destruidas
        $where = Session::get('glo_emissao_nf_where_completo');
        $where_semi = Session::get('glo_emissao_nf_where_semi');
        $idRecebimento = Session::get('glo_recebimento_id');

        //Exclui todos os dados dos valores recebidos para reabertura da seleção de NF
        DB::table('financeiro_recebimento_valores')
        ->where('recval_emp', $empresa)
        ->where('recval_cod_rec', $idRecebimento)
        ->delete();

        $dados = DB::select("select * from vi_faturamento_pnl001_sel_notas where nfhdr_sts = 'A' and nfhdr_ori in('01') ".$where_semi." and nfhdr_emp = '".$empresa."' and nfhdr_cli = '".$cliente."' order by nfhdr_num_ped asc");

        return view('/faturamento/notas/fat_pnl001_EmissaoNF',[
            'dadosCliente'=>$dados, 
            'empresaREC' => $empresa, 
            'clienteREC' => $cliente, 
            'idRecebimento' => $idRecebimento, 
            'glo_empresa_recebimento' => $empresa, 
            'glo_cliente_recebimento' => $cliente, 
            'glo_emissao_nf_where_completo' => $where, 
            'glo_emissao_nf_where_semi' => $where_semi,
            'estagio_app' => 'SELECAO_NF'
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa o processo de finalização do Recebimento dos Valores das Notas Fiscais
    |----------------------------------------------------------------------------------------------------
    */
    public function finalizaRecebimento($empresa, $cliente, Request $request)
    {
        $idRecebimento = Session::get('glo_recebimento_id');

        // Deleta os Valores de TRC da tabela para garantir que não serão duplicados
        DB::table('financeiro_recebimento_valores')
        ->where('recval_emp', $empresa)
        ->where('recval_cod_rec', $idRecebimento)
        ->where('recval_tipo', 'TRC')
        ->delete();

        // Busca as notas e valores a prazo (OUT)
        $sql = "SELECT 
                    count(*)
                FROM financeiro_recebimento_notas 
                INNER JOIN faturamento_nf_headers ON 
                    nfhdr_emp = recnf_emp 
                    AND nfhdr_num = recnf_num
                WHERE 
                    recnf_cod_rec = ".$idRecebimento." AND 
                    ( nfhdr_cpg <> '00' OR 
                    CASE 
                        WHEN ( nfhdr_tor IN ('S', 'P') AND 
                            nfhdr_cme >= 200 AND 
                            nfhdr_cme NOT IN (217,211,213,220,225,230,240,250,265,270,280,271,290,299) AND 
                            CASE 
                                WHEN nfhdr_cme = 300 AND nfhdr_cfop IN (5949, 6949) THEN 'N'
                                WHEN nfhdr_cme IN (300,301,310,311,312) AND nfhdr_cfop NOT IN (512,612,591,691,5102,6102,5551,6551,5949,6949,5933,6933) THEN 'N'
                                ELSE 'S' 
                            END = 'S' AND 
                            CASE 
                                WHEN nfhdr_cpg::INTEGER > 0 AND nfhdr_cpg::INTEGER < 99 AND nfhdr_qtd_ppg = 0 THEN 'N'
                                ELSE 'S' 
                            END = 'S') THEN 'S' 
                        ELSE 'N' 
                    END = 'N' )";
        $cntNfPrazo = DB::select($sql);

        $exNotaPrazo = false;

        if($cntNfPrazo > 0){
            $exNotaPrazo = true;
        }

        // Busca os dados do header do Recebimento
        $hdrRec = DB::table('financeiro_recebimento_headers')->where('rechdr_emp', $empresa)->where('rechdr_cod_rec', $idRecebimento)->first();

        // Se for NFV busca o valor que será recebido/baixado
        // Por hora não é ctz que NDV e NDC passará por aqui e é por isso essa verificação
        if($hdrRec->rechdr_ori == 'NFV'){
            
            $valBaixa = DB::table('financeiro_recebimento_notas')->where('recnf_emp',$empresa)->where('recnf_cod_rec',$idRecebimento)->sum('recnf_vlr_sin');
        }else{

            $valBaixa = 0;
        }

        //Garantimos que se não tiver valor a prazo e o valor a baixar for 0 que tenha nota selecionada
        if($valBaixa == 0 && $exNotaPrazo == false){
	
            return redirect()->back()->with('error', 'Não existe valor a receber! Por favor selecione as notas para recebimento.');
        }

        // Se for NFV busca o valor que será que foi recebido
        // Por hora não é ctz que NDV e NDC passará por aqui e é por isso essa verificação
        if($hdrRec->rechdr_ori == 'NFV'){
            $valRecebido = DB::table('financeiro_recebimento_valores')->where('recval_emp',$empresa)->where('recval_cod_rec',$idRecebimento)->where('recval_tipo', '<>', 'OUT')->sum('recval_valor');
        }else{
            $valRecebido = DB::table('financeiro_recebimento_valores')->where('recval_emp',$empresa)->where('recval_cod_rec',$idRecebimento)->sum('recval_valor');
        }

        //Verifica se o valor a recer for maior o da baixa temos troco
        if($valRecebido > $valBaixa && $valBaixa != 0){
	
            $troco = $valRecebido - $valBaixa;
            
            //Pega a sequencia para o recebimento
            $seq = DB::table('financeiro_recebimento_valores')->where('recval_cod_rec', $idRecebimento)->where('recval_emp', $empresa)->max('recval_seq');
            $seq += 1;

            DB::table('financeiro_recebimento_valores')->insert([
                'recval_emp' => $empresa,
                'recval_cod_rec' => $idRecebimento,
                'recval_seq' => $seq,
                'recval_tipo' => 'TRC',
                'recval_valor' => $troco
            ]);            
        }

        //Verifica se o valor a receber ainda é menor que o da baixa
        if($valRecebido < $valBaixa){
	
            return redirect()->back()->with('info', 'O saldo restante a receber tem que ser zero!');         
        }

        // Atualiza a observação do recebimento
        DB::table('financeiro_recebimento_headers')
        ->where('rechdr_emp', $empresa)
        ->where('rechdr_cod_rec', $idRecebimento)
        ->update(['rechdr_obs' => $request->observacao]);

        //Redireciona para a rota que vai finalizar o recebimento e gerar a opção de Nota Fiscal gerada
        return redirect()->route('emissaoNF.opcaoNF', [
            'empresa' => $empresa,
            'cliente' => $cliente,
            'origemOpc' => 'VISTA'
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa o painel de Opções de Geração de NF
    |----------------------------------------------------------------------------------------------------
    */
    public function opcaoNF($empresa, $cliente, $origemOpc)
    {
        // Vamos passar como variavel no redirect por que depois de 2 redirect elas são destruidas
        $where = Session::get('glo_emissao_nf_where_completo');
        $where_semi = Session::get('glo_emissao_nf_where_semi');
        $idRecebimento = Session::get('glo_recebimento_id');

        // Deleta os Valores OUT da tabela para garantir que não serão duplicados
        DB::table('financeiro_recebimento_valores')
        ->where('recval_emp', $empresa)
        ->where('recval_cod_rec', $idRecebimento)
        ->where('recval_tipo', 'OUT')
        ->delete();

        // Busca as notas e valores a prazo (OUT)
        $sql = "SELECT 
                    recnf_num, 
                    recnf_emp, 
                    (recnf_vlr_tot - recnf_vlr_sin) AS valor_out, 
                    recnf_num_nf,
                    recnf_ser_nf,
                    recnf_cli,
                    recnf_num_ped 
                FROM financeiro_recebimento_notas 
                INNER JOIN faturamento_nf_headers ON 
                    nfhdr_emp = recnf_emp 
                    AND nfhdr_num = recnf_num
                WHERE 
                    recnf_cod_rec = ".$idRecebimento." AND 
                    ( nfhdr_cpg <> '00' OR 
                    CASE 
                        WHEN ( nfhdr_tor IN ('S', 'P') AND 
                            nfhdr_cme >= 200 AND 
                            nfhdr_cme NOT IN (217,211,213,220,225,230,240,250,265,270,280,271,290,299) AND 
                            CASE 
                                WHEN nfhdr_cme = 300 AND nfhdr_cfop IN (5949, 6949) THEN 'N'
                                WHEN nfhdr_cme IN (300,301,310,311,312) AND nfhdr_cfop NOT IN (512,612,591,691,5102,6102,5551,6551,5949,6949,5933,6933) THEN 'N'
                                ELSE 'S' 
                            END = 'S' AND 
                            CASE 
                                WHEN nfhdr_cpg::INTEGER > 0 AND nfhdr_cpg::INTEGER < 99 AND nfhdr_qtd_ppg = 0 THEN 'N'
                                ELSE 'S' 
                            END = 'S') THEN 'S' 
                        ELSE 'N' 
                    END = 'N' )";
        $notasOUT = DB::select($sql);

        foreach($notasOUT as $nota){
            
            //Pega a sequencia para o recebimento
            $seq = DB::table('financeiro_recebimento_valores')->where('recval_cod_rec', $idRecebimento)->where('recval_emp', $empresa)->max('recval_seq');
            $seq += 1;

            DB::table('financeiro_recebimento_valores')->insert([
                'recval_emp' => $empresa,
                'recval_cod_rec' => $idRecebimento,
                'recval_seq' => $seq,
                'recval_tipo' => 'OUT',
                'recval_nf_num_ped' => $nota->recnf_num_ped,
                'recval_nf_num' => $nota->recnf_num,
                'recval_nf_nnf' => $nota->recnf_num_nf,
                'recval_nf_nsr' => $nota->recnf_ser_nf,
                'recval_nf_cli' => $nota->recnf_cli,
                'recval_valor' => $nota->valor_out
            ]);
        }

        // Busca os dados da quantidade de notas recebidas
         $cntNf = DB::table('financeiro_recebimento_notas')->where('recnf_emp',$empresa)->where('recnf_cod_rec',$idRecebimento)->count();

        //Garantimos que se não tiver valor a prazo e o valor a baixar for 0 que tenha nota selecionada
        if($cntNf == 0){
	
            return redirect()->back()->with('error', 'Não existe notas a receber! Por favor selecione as notas para recebimento.');
        }

        //Gera dados das NF em aberto para seleção e os valores de recebimento informados
        $dados = DB::select("select * from vi_faturamento_pnl001_sel_notas where nfhdr_sts = 'A' and nfhdr_ori in('01') ".$where_semi." and nfhdr_emp = '".$empresa."' and nfhdr_cli = '".$cliente."' order by nfhdr_num_ped asc");
        $dadosRec = DB::table('financeiro_recebimento_valores')->where('recval_emp',$empresa)->where('recval_cod_rec',$idRecebimento)->orderby('recval_seq','asc')->get();

        return view('/faturamento/notas/fat_pnl001_EmissaoNF',[
            'dadosCliente'=>$dados, 
            'dadosRecebimento' => $dadosRec,
            'empresaREC' => $empresa, 
            'clienteREC' => $cliente, 
            'idRecebimento' => $idRecebimento, 
            'glo_empresa_recebimento' => $empresa, 
            'glo_cliente_recebimento' => $cliente, 
            'glo_emissao_nf_where_completo' => $where, 
            'glo_emissao_nf_where_semi' => $where_semi,
            'estagio_app' => 'GERACAO_NF',
            'origemOpc' => $origemOpc
        ]);
    }

    //Chama a consulta de reemissão vinda do Filtro
    public function consultaReemissaoNF(Request $request, $appOrigem)
    { 
        $where = "";

        if($appOrigem == "REEMISSAO"){

            //Empresa
            if(!empty($request->empresa)){
                $where = " and nfhdr_emp = '".$request->empresa."' ";
            }

            //Cliente
            if(!empty($request->cliente)){
                $cliente = substr($request->cliente, 0, 10);

                if(strlen($cliente) < 10){
                    return redirect()->back()->with('error', 'Código do cliente '.$cliente.' é inválido!');
                }

                $cnt_cli = DB::table('cadastro_clientes')->where('cliente_codigo', $cliente)->count();
            
                if($cnt_cli == 0){
                    return redirect()->back()->with('error', 'Código do cliente '.$cliente.' não existe!');
                }else{
                    $where .= " and nfhdr_cli = '".$cliente."' ";
                }
            }

            //Numero da OS / Pedido
            if(!empty($request->numOS)){
                $where .= " and nfhdr_num_ped = '".$request->numOS."' ";
            }

            //Data de Inicio / Final
            if(!empty($request->dtIniOS) && !empty($request->dtFinOS)){

                $dt_ini = Helper::limpaData($request->dtIniOS);
                $dt_fin = Helper::limpaData($request->dtFinOS);

                if($dt_ini > $dt_fin){
                    return redirect()->back()->with('error', 'A Data Inicial não pode ser maior que a Data Final!');
                }

                $where .= " and nfhdr_dt_nf between '".$dt_ini."' and '".$dt_fin."' ";
            }elseif(empty($request->dtIniOS) && !empty($request->dtFinOS)){
                $dt_fin = Helper::limpaData($request->dtFinOS);
                $where .= " and nfhdr_dt_nf <= '".$dt_fin."' ";
            }elseif(!empty($request->dtIniOS) && empty($request->dtFinOS)){
                $dt_ini = Helper::limpaData($request->dtIniOS);
                $where .= " and nfhdr_dt_nf >= '".$dt_ini."' ";
            }

            //Valor de Inicio / Final
            if(!empty($request->vlrIniOS) && !empty($request->vlrFinOS)){
                $vlr_ini = Helper::limpaValorMonetario($request->vlrIniOS);
                $vlr_fin = Helper::limpaValorMonetario($request->vlrFinOS);
                if($vlr_ini > $vlr_fin){
                    return redirect()->back()->with('error', 'O Valor Inicial não pode ser maior que o Valor Final!');
                }
                $where .= " and nfhdr_vlr_tot_nf between ".$vlr_ini." and ".$vlr_fin;
            }elseif(empty($request->vlrIniOS) && !empty($request->vlrFinOS)){
                $vlr_fin = Helper::limpaValorMonetario($request->vlrFinOS);
                $where .= " and nfhdr_vlr_tot_nf <= ".$vlr_fin;
            }elseif(!empty($request->vlrIniOS) && empty($request->vlrFinOS)){
                $vlr_ini = Helper::limpaValorMonetario($request->vlrIniOS);
                $where .= " and nfhdr_vlr_tot_nf >= ".$vlr_ini;
            }

        }elseif($appOrigem == 'HOME_MES'){

            //Vamos definir a empresa da visualização da Home
            $empresa = session('glo_empresa_exibicao_home');
            $data = date('Y-m-01');
            $where .= " and nfhdr_emp = '".$empresa."' and nfhdr_sts = 'G' and nfhdr_dt_nf >= '".$data."' ";
            
        }else{

            //Vamos definir a empresa da visualização da Home
            $empresa = session('glo_empresa_exibicao_home');
            $where .= " and nfhdr_emp = '".$empresa."' ";
        }

        $dados = DB::select("select * from faturamento_nf_headers where nfhdr_sts not in('A','C') and nfhdr_ori in('01') ".$where." order by nfhdr_dt_nf desc, nfhdr_num_nf desc");

        return view('/faturamento/notas/fat_cns001_ReemissaoNF',['dadosCliente'=>$dados, 'glo_where_reemissao_nf' => $where]);
    }

    //Chama a consulta vinda de redirs mantendo o where do filtro original
    public function redirConsultaReemissaoNF()
    { 
        //Vamos passar como variavel no redirect por que depois de 2 redirect elas são destruidas
        $where = session('glo_where_reemissao_nf');

        $dados = DB::select("select * from faturamento_nf_headers where nfhdr_sts not in('A','C') and nfhdr_ori in('01') ".$where." order by nfhdr_dt_nf desc, nfhdr_num_nf desc");

        return view('/faturamento/notas/fat_cns001_ReemissaoNF',['dadosCliente'=>$dados, 'glo_where_reemissao_nf' => $where]);
    }
}
