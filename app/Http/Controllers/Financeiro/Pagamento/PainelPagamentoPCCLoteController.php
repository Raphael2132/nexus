<?php

namespace App\Http\Controllers\Financeiro\Pagamento;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Financeiro\Pagamento\FinanceiroPagamentoHeaderController;
use App\Http\Controllers\Financeiro\Pagamento\FinanceiroPagamentoContasCorrentesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\Helper;
use App\Http\Helpers\HelperFinanceiro;

class PainelPagamentoPCCLoteController extends Controller
{
    /* *****
    |*****************************************************************************************************************************
    |*****************************************************************************************************************************
    |
    | Pagamento de Contas Correntes (Individual em Dinheiro)
    |
    |*****************************************************************************************************************************
    |*****************************************************************************************************************************
    ***** */

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de execução do formulário inicial do Pagamento de Contas Correntes (Individual em Dinheiro)
    |----------------------------------------------------------------------------------------------------
    */
    public function homePCCLote($origem)
    {    
        //Inicia a variavel de sessão do processo
        Session::put('glo_pagamento_cliente', '');
        Session::put('glo_pagamento_id', '');
        Session::put('glo_pagamento_empresa', '');
        Session::put('glo_pagamento_origem', $origem);
        Session::put('glo_pagamento_where_cc_lote', '');

        return view('/financeiro/pagamento/fin_cnt006_HomePCC_Lote',['appOrigem' => $origem]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de execução do inicio do processo de pagamento
    |----------------------------------------------------------------------------------------------------
    */
    public function iniciaPCCLote($appOrigem, Request $request)
    {    
        // Cliente / Razão
        if (!empty($request->cliente)) {

            // pega somente o código antes do primeiro espaço
            $cliente = trim(strtok($request->cliente, ' '));

            if (strlen($cliente) == 10) {

                // CLIENTE / FORNECEDOR
                $cnt = DB::table('cadastro_clientes')
                    ->where('cliente_codigo', $cliente)
                    ->count();

                if ($cnt == 0) {
                    return redirect()->back()
                        ->with('info', 'Código do beneficiário '.$cliente.' não foi encontrado!');
                }

            } elseif (strlen($cliente) == 6) {

                // RAZÃO (CC / CO) — busca sem filtrar empresa
                $razao = DB::table('financeiro_razoes')
                    ->select('razao_empresa')
                    ->where('razao_codigo', $cliente)
                    ->where('razao_ano', date('Y'))
                    ->first();

                if (!$razao) {
                    return redirect()->back()
                        ->with('info', 'Código do beneficiário '.$cliente.' não foi encontrado!');
                }

                // valida empresa
                if ($razao->razao_empresa != $request->empresa) {
                    return redirect()->back()
                        ->with(
                            'info',
                            'O cartão corporativo '.$cliente.' não pertence à empresa selecionada!'
                        );
                }

            } else {
                return redirect()->back()
                    ->with('info', 'Código do beneficiário '.$cliente.' é inválido!');
            }
        }

        //Gera a variavel da empresa e origem
        Session::put('glo_pagamento_empresa', $request->empresa);
        Session::put('glo_pagamento_cliente', $cliente);        

        // Chama o método que faz a exclusão de todos os Pagamentos em aberto com data menor que a do dia atual
        HelperFinanceiro::limpaPagamentoAberto();

        // Chama o método que faz a exclusão de todos os Pagamentos em aberto do usuário
        //No dia o usuário pode ter entrado e saido sem finalizar algum Pagamento então vamos excluir ele também
        //Se um dia for precisar manter o Pagamento em aberto durante o dia para o usuario voltar e terminar depois esse trecho deve ser refeito
        HelperFinanceiro::limpaPagamentoUsuario(Auth::user()->usuario_codigo);

        //Verificar se o Usuario do pagamento tem Razão Associado
        $usuario = Auth::user()->usuario_codigo;
        $dadosUsuFin = DB::table('financeiro_tab_usuarios')->where('tabusu_usuario', $usuario)->where('tabusu_empresa', $request->empresa)->first();

        if(empty($dadosUsuFin->tabusu_razao)){
            return redirect()->back()->with('info', 'Usuario do logado no sistema não tem razão associado!');
        }

        //Insere o Header da tabela de pagamentos
        $idPagamento = FinanceiroPagamentoHeaderController::insert($request->empresa, 'PCC');

        //Gera a variavel do ID do pagamento aberto
        Session::put('glo_pagamento_id', $idPagamento);

        $where = "";

        //Realiza o where sobre o tipo da conta
        if($appOrigem == 'LOTE'){

            if (!empty($request->selBsConta) && is_array($request->selBsConta)) {
                // Gera uma lista de valores entre aspas simples, ex: 'AC','CO','CP'
                $tipos = array_map(function ($v) {
                    return "'" . addslashes($v) . "'";
                }, $request->selBsConta);

                $where = " AND conta_tipo IN (" . implode(',', $tipos) . ")";
            }else{
                $where = " AND conta_tipo IN ('GD', 'NF', 'NP', 'CP', 'DV', 'AC', 'IS', 'ID', 'CO', 'VU', 'GV', 'VC')";
            }

        }elseif($appOrigem == 'PAG_AF'){

            $where = " AND conta_tipo = 'AF' AND conta_fluxo = 'N' ";
        }
        
        //Realiza o where sobre a data de vencimento da conta
        if(!empty($request->dtIni) && !empty($request->dtFin)){
           
            $dataIni = Helper::limpaData($request->dtIni);
            $dataFin = Helper::limpaData($request->dtFin);

            $where .= " AND conta_dt_vencimento BETWEEN '".$dataIni."' AND '".$dataFin."' ";

        }elseif(empty($request->dtIni) && !empty($request->dtFin)){
            
            $dataFin = Helper::limpaData($request->dtFin);

            $where .= " AND conta_dt_vencimento <= '".$dataFin."' ";

        }elseif(!empty($request->dtIni) && empty($request->dtFin)){

            $dataIni = Helper::limpaData($request->dtIni);

            $where .= " AND conta_dt_vencimento >= '".$dataIni."' ";
        }

        //Realiza o where sobre o valor da conta
        if(!empty($request->vlrIni) && !empty($request->vlrFin)){
           
            $vlrIni = Helper::limpaValorMonetario($request->vlrIni);
            $vlrFin = Helper::limpaValorMonetario($request->vlrFin);

            $where .= " AND conta_valor BETWEEN '".$vlrIni."' AND '".$vlrFin."' ";

        }elseif(empty($request->vlrIni) && !empty($request->vlrFin)){
            
            $vlrFin = Helper::limpaValorMonetario($request->vlrFin);

            $where .= " AND conta_valor <= '".$vlrFin."' ";

        }elseif(!empty($request->vlrIni) && empty($request->vlrFin)){

            $vlrIni = Helper::limpaValorMonetario($request->vlrIni);

            $where .= " AND conta_valor >= '".$vlrIni."' ";
        }

        Session::put('glo_pagamento_where_cc_lote', $where);

        // Monta o SQL final manualmente
        $sql = "
            SELECT *
            FROM financeiro_contas_correntes
            WHERE conta_empresa = ?
            AND conta_responsavel = ?
            AND conta_situacao not in('L','C') 
            AND trim(conta_num_conta) <> ''
            {$where}
        ";

        // Executa a query
        $dadosContasSel = DB::select($sql, [$request->empresa, $cliente]);
        
        return view('/financeiro/pagamento/fin_cns006_SelecaoContas', [
            'clienteLote' => $cliente,
            'empresaLote' => $request->empresa,
            'idPagamento' => $idPagamento,
            'dadosContasSel' => $dadosContasSel,
            'appOrigem' => $appOrigem
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de abertura da app de seleção da conta corrente do lote
    |----------------------------------------------------------------------------------------------------
    */
    public function selecaoCCLote($appOrigem)
    {   
        $idPagamento = Session::get('glo_pagamento_id');
        $empresa = Session::get('glo_pagamento_empresa');
        $cliente = Session::get('glo_pagamento_cliente');
        $where = Session::get('glo_pagamento_where_cc_lote');

        // Monta o SQL final manualmente
        $sql = "
            SELECT *
            FROM financeiro_contas_correntes
            WHERE conta_empresa = ?
            AND conta_responsavel = ?
            AND conta_situacao not in('L','C') 
            AND trim(conta_num_conta) <> ''
            {$where}
        ";

        // Executa a query
        $dadosContasSel = DB::select($sql, [$empresa, $cliente]);

        //Garante não ter valores informados - Pode vir do painel do lote
        DB::table('financeiro_pagamento_valores')
        ->where('pagval_emp', $empresa)
        ->where('pagval_cod_pag', $idPagamento)
        ->delete();
        
        return view('/financeiro/pagamento/fin_cns006_SelecaoContas', [
            'clienteLote' => $cliente,
            'empresaLote' => $empresa,
            'idPagamento' => $idPagamento,
            'dadosContasSel' => $dadosContasSel,
            'appOrigem' => $appOrigem
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de inclusão da conta corrente no lote
    |----------------------------------------------------------------------------------------------------
    */
    public function insertCCLote($appOrigem, $numCC, $tipoCC, $saldoCC)
    {   
        $idPagamento = Session::get('glo_pagamento_id');
        $empresa = Session::get('glo_pagamento_empresa');
        $cliente = Session::get('glo_pagamento_cliente');
        $where = Session::get('glo_pagamento_where_cc_lote');

        // Monta um array com os dados mínimos
        $dados = [
            'tipoConta' => $tipoCC,
            'numConta'  => $numCC,
            'dataPag'   => date('Y-m-d'),
            'valorPag'  => $saldoCC
        ];

        if($appOrigem == 'LOTE'){
            $origem = 'L';
        }else{
            $origem = 'A';
        }

        //Insere os dados do Pagamento
        FinanceiroPagamentoContasCorrentesController::insert($empresa, $idPagamento, $cliente, $origem, $dados);

        // Monta o SQL final manualmente
        $sql = "
            SELECT *
            FROM financeiro_contas_correntes
            WHERE conta_empresa = ?
            AND conta_responsavel = ?
            AND conta_situacao not in('L','C') 
            AND trim(conta_num_conta) <> ''
            {$where}
        ";

        // Executa a query
        $dadosContasSel = DB::select($sql, [$empresa, $cliente]);
        
        return view('/financeiro/pagamento/fin_cns006_SelecaoContas', [
            'clienteLote' => $cliente,
            'empresaLote' => $empresa,
            'idPagamento' => $idPagamento,
            'dadosContasSel' => $dadosContasSel,
            'appOrigem' => $appOrigem
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de exclusão da conta corrente no lote
    |----------------------------------------------------------------------------------------------------
    */
    public function deleteCCLote($appOrigem, $numCC, $tipoCC)
    {   

        $idPagamento = Session::get('glo_pagamento_id');
        $empresa = Session::get('glo_pagamento_empresa');
        $cliente = Session::get('glo_pagamento_cliente');
        $where = Session::get('glo_pagamento_where_cc_lote');

        if($appOrigem == 'LOTE'){
            $origem = 'L';
        }else{
            $origem = 'A';
        }

        //Deleta o registro da CC
        DB::table('financeiro_pagamento_contas_correntes')
        ->where('pagcct_emp', $empresa)
        ->where('pagcct_cod_pag', $idPagamento)
        ->where('pagcct_cli', $cliente)
        ->where('pagcct_tip_cct', $tipoCC)
        ->where('pagcct_num_cct', $numCC)
        ->where('pagcct_tip_opr', $origem)
        ->delete();

        // Monta o SQL final manualmente
        $sql = "
            SELECT *
            FROM financeiro_contas_correntes
            WHERE conta_empresa = ?
            AND conta_responsavel = ?
            AND conta_situacao not in('L','C') 
            AND trim(conta_num_conta) <> ''
            {$where}
        ";

        // Executa a query
        $dadosContasSel = DB::select($sql, [$empresa, $cliente]);
        
        return view('/financeiro/pagamento/fin_cns006_SelecaoContas', [
            'clienteLote' => $cliente,
            'empresaLote' => $empresa,
            'idPagamento' => $idPagamento,
            'dadosContasSel' => $dadosContasSel,
            'appOrigem' => $appOrigem
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de fechamento do lote para pagamento
    |----------------------------------------------------------------------------------------------------
    */
    public function fecharLote($appOrigem)
    {
        $idPagamento = Session::get('glo_pagamento_id');
        $empresa = Session::get('glo_pagamento_empresa');
        $cliente = Session::get('glo_pagamento_cliente');

        //Busca os dados das contas em aberto
        $dadosContasLote = DB::table('financeiro_pagamento_contas_correntes')
        ->where('pagcct_emp', $empresa)
        ->where('pagcct_cod_pag', $idPagamento)
        ->get();
        
        return view('/financeiro/pagamento/fin_pnl006_PainelLote', [
            'clienteLote' => $cliente,
            'empresaLote' => $empresa,
            'idPagamento' => $idPagamento,
            'estagio_app' => 'PAINEL_PRINCIPAL',
            'dadosContasLote' => $dadosContasLote,
            'appOrigem' => $appOrigem
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de edição da Conta do lote de pagamento
    |----------------------------------------------------------------------------------------------------
    */
    public function loteEditarCC($appOrigem,$numCC,$tipoCC)
    {
        $idPagamento = Session::get('glo_pagamento_id');
        $empresa = Session::get('glo_pagamento_empresa');
        $cliente = Session::get('glo_pagamento_cliente');

        //Busca os dados da conta corrente
        $dadosCC = DB::table('financeiro_contas_correntes')
        ->where('conta_empresa', $empresa)
        ->where('conta_tipo', $tipoCC)
        ->where('conta_responsavel', $cliente)
        ->where('conta_num_conta', $numCC)
        ->first();

        $dadosCCLote = DB::table('financeiro_pagamento_contas_correntes')
        ->where('pagcct_emp', $empresa)
        ->where('pagcct_cod_pag', $idPagamento)
        ->where('pagcct_cli', $cliente)
        ->where('pagcct_tip_cct', $tipoCC)
        ->where('pagcct_num_cct', $numCC)
        ->first();
        
        return view('/financeiro/pagamento/fin_pnl006_PainelLote', [
            'clienteLote' => $cliente,
            'empresaLote' => $empresa,
            'idPagamento' => $idPagamento,
            'estagio_app' => 'EDITAR_CONTA',
            'dadosCC' => $dadosCC,
            'dadosCCLote' => $dadosCCLote,
            'appOrigem' => $appOrigem
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de atualização dos dados editados da Conta do lote de pagamento
    |----------------------------------------------------------------------------------------------------
    */
    public function loteSalvarEdicaoCC($appOrigem, Request $request)
    {
        $idPagamento = Session::get('glo_pagamento_id');
        $empresa = Session::get('glo_pagamento_empresa');
        $cliente = Session::get('glo_pagamento_cliente');

        //Insere os dados do Pagamento
        FinanceiroPagamentoContasCorrentesController::update($empresa, $idPagamento, $cliente, $request);
        
        return redirect()->route('pagamentoPCC.fecharLote',['appOrigem' => $appOrigem])->with('success', 'Conta atualizada com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de exclusão da Conta do lote de pagamento
    |----------------------------------------------------------------------------------------------------
    */
    public function loteDeletarCC($appOrigem, $numCC, $tipoCC)
    {
        $idPagamento = Session::get('glo_pagamento_id');
        $empresa = Session::get('glo_pagamento_empresa');
        $cliente = Session::get('glo_pagamento_cliente');

        //Insere os dados do Pagamento
        FinanceiroPagamentoContasCorrentesController::delete($empresa, $idPagamento, $cliente, $numCC, $tipoCC);
        
        return redirect()->route('pagamentoPCC.fecharLote',['appOrigem' => $appOrigem])->with('success', 'Conta excluída com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de abertura do painel de pagamento do lote
    |----------------------------------------------------------------------------------------------------
    */
    public function pagarLote($appOrigem)
    {

        $idPagamento = Session::get('glo_pagamento_id');
        $empresa = Session::get('glo_pagamento_empresa');
        $cliente = Session::get('glo_pagamento_cliente');
        
        //Busca os dados das contas do lote
        $cntCC = DB::table('financeiro_pagamento_contas_correntes')
        ->where('pagcct_emp', $empresa)
        ->where('pagcct_cod_pag', $idPagamento)
        ->count();

        //Cliente
        if($cntCC == 0){

            return redirect()->back()->with('info', 'Não existe Conta Corrente selecionada para pagamento!');
            
        }

        //Busca os dados das contas do lote
        $dadosContasLote = DB::table('financeiro_pagamento_contas_correntes')
        ->where('pagcct_emp', $empresa)
        ->where('pagcct_cod_pag', $idPagamento)
        ->get();

        $dadosPagamento = DB::table('financeiro_pagamento_valores')
        ->where('pagval_emp', $empresa)
        ->where('pagval_cod_pag', $idPagamento)
        ->get();
        
        $totalPagamento = $dadosContasLote->sum('pagcct_vlr_tot');
        $totalPago = $dadosPagamento->sum('pagval_valor');

        $saldoRestante = $totalPagamento - $totalPago;
        
        return view('/financeiro/pagamento/fin_pnl006_PainelLote', [
            'clienteLote' => $cliente,
            'empresaLote' => $empresa,
            'idPagamento' => $idPagamento,
            'estagio_app' => 'PAINEL_PAGAMENTO',
            'dadosContasLote' => $dadosContasLote,
            'dadosPagamento' => $dadosPagamento,
            'appOrigem' => $appOrigem,
            'saldoRestante' => $saldoRestante,
            'observacoes' => '',
            'valorPagamento' => $totalPagamento,
            'valorPago' => $totalPago,
            'appOrigem' => $appOrigem
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de reabertura do painel de edição das contas correntes
    |----------------------------------------------------------------------------------------------------
    */
    public function reabreLote($appOrigem)
    {
        $idPagamento = Session::get('glo_pagamento_id');
        $empresa = Session::get('glo_pagamento_empresa');
        $cliente = Session::get('glo_pagamento_cliente');

        //Busca os dados das contas do lote
        $dadosContasLote = DB::table('financeiro_pagamento_contas_correntes')
        ->where('pagcct_emp', $empresa)
        ->where('pagcct_cod_pag', $idPagamento)
        ->get();
        
        DB::table('financeiro_pagamento_valores')
        ->where('pagval_emp', $empresa)
        ->where('pagval_cod_pag', $idPagamento)
        ->delete();
        
        return view('/financeiro/pagamento/fin_pnl006_PainelLote', [
            'clienteLote' => $cliente,
            'empresaLote' => $empresa,
            'idPagamento' => $idPagamento,
            'estagio_app' => 'PAINEL_PRINCIPAL',
            'dadosContasLote' => $dadosContasLote,
            'appOrigem' => $appOrigem
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de finalização do pagamento
    |----------------------------------------------------------------------------------------------------
    */
    public function finalizaPagamentoLote($appOrigem, Request $request)
    {
        $idPagamento = Session::get('glo_pagamento_id');
        $empresa = Session::get('glo_pagamento_empresa');
        $cliente = Session::get('glo_pagamento_cliente');

        //Como acabamos de criar o pagamento passamos um array vazio
        $cntContas = DB::table('financeiro_pagamento_contas_correntes')
        ->where('pagcct_emp', $empresa)
        ->where('pagcct_cod_pag', $idPagamento)
        ->count();

        if($cntContas == 0){
            return redirect()->back()->with('info', 'Não há contas selecionadas para pagamento!');
        }

        $valPago = DB::table('financeiro_pagamento_valores')->where('pagval_emp',$empresa)->where('pagval_cod_pag',$idPagamento)->sum('pagval_valor');
        
        if($valPago == 0){
            return redirect()->back()->with('info', 'O valor pago não pode ser zero!');
        }

        if($request->valorPagamento < $request->valorPago){
            return redirect()->back()->with('info', 'O valor pago é maior que o valor de pagamento!');
        }

        if($request->valorPagamento > $request->valorPago){
            return redirect()->back()->with('info', 'Ainda existe saldo restante a ser pago!');
        }

        if(!empty($request->observacao)){
            
            // Atualiza a observação do pagamento
            DB::table('financeiro_pagamento_headers')
            ->where('paghdr_emp', $empresa)
            ->where('paghdr_cod_pag', $idPagamento)
            ->update(['paghdr_obs' => $request->observacao]);
        }

        //Inicia o Database Transaction
        DB::beginTransaction();

        $data = date('Y-m-d');
        $usuario = Auth::user()->usuario_codigo;
        $app = "fin_pnl006_PainelLote";

        //echo $appOrigem." select ret_sts_pag, ret_msg_pag from fn_financeiro_pagamento('".$empresa."','".$data."',".$idPagamento.",'".$cliente."','".$usuario."','".$app."');";exit;
        
        $exec_fn = DB::select("select ret_sts_pag, ret_msg_pag from fn_financeiro_pagamento('".$empresa."','".$data."',".$idPagamento.",'".$cliente."','".$usuario."','".$app."');");

        if($exec_fn[0]->ret_sts_pag == '*'){
            //Falha, desfaz as alterações no banco de dados
            DB::rollBack();

            return redirect()->back()->with('error', $exec_fn[0]->ret_msg_pag);
        }else{

            // Atualiza o status do pagamento
            DB::table('financeiro_pagamento_headers')
            ->where('paghdr_emp', $empresa)
            ->where('paghdr_cod_pag', $idPagamento)
            ->update(['paghdr_sts' => 'F']);
            
            //Grava as alterações do banco
            DB::commit();
        }

        return redirect()->route('home.homePCCLote',['appOrigem' => $appOrigem])->with('success2', 'Pagamento '.$idPagamento.' realizado com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de execução do filtro inicial da consulta de lotes gerados
    |----------------------------------------------------------------------------------------------------
    */
    public function homePCCLoteConsulta()
    {    
        Session::put('glo_pagamento_where_lote_cons', '');

        return view('/financeiro/pagamento/fin_cnt008_HomeLoteConsulta');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de execução da da consulta de lotes gerados
    |----------------------------------------------------------------------------------------------------
    */
    public function consultaLote()
    {

        $dadosLote = DB::table('financeiro_pagamento_headers')
        ->where('paghdr_ori', 'PCC')
        ->get();

        return view('/financeiro/pagamento/fin_cns008_ConsultaLote',[
            'dadosLote' => $dadosLote
        ]);
    }
}
