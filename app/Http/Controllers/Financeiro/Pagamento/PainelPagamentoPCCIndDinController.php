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

class PainelPagamentoPCCIndDinController extends Controller
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
    public function homePCCIndDin()
    {    
        //Inicia a variavel de sessão do processo
        Session::put('glo_pagamento_cliente', '');
        Session::put('glo_pagamento_id', '');
        Session::put('glo_pagamento_empresa', '');
        Session::put('glo_pagamento_origem', '');
        Session::put('glo_pagamento_pcc_tipo_cc', '');

        return view('/financeiro/pagamento/fin_cnt005_HomePCC_InDin');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de execução do inicio do processo de pagamento
    |----------------------------------------------------------------------------------------------------
    */
    public function iniciaPCCDin(Request $request)
    {    
        //Cliente
        if(!empty($request->cliente)){

            $cliente = substr($request->cliente, 0, 10);

            if(strlen($cliente) < 10){
                return redirect()->back()->with('info', 'Código do beneficário '.$cliente.' é inválido!');
            }else{
                $cntCli = DB::table('cadastro_clientes')->where('cliente_codigo', $cliente)->count();

                if($cntCli == 0){
                    return redirect()->back()->with('info', 'Código do beneficário '.$cliente.' não foi encontrado!');
                }
            }
        }

        //Gera a variavel da empresa e origem
        Session::put('glo_pagamento_empresa', $request->empresa);
        Session::put('glo_pagamento_origem', 'PCC');
        Session::put('glo_pagamento_cliente', $cliente);
        Session::put('glo_pagamento_pcc_tipo_cc', $request->tipCC);

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

        //Busca os dados das contas em aberto
        $dadosContasPag = DB::table('financeiro_contas_correntes')
        ->where('conta_tipo', $request->tipCC)
        ->where('conta_empresa', $request->empresa)
        ->where('conta_responsavel', $cliente)
        ->where('conta_situacao', 'A')
        ->get();
        
        return view('/financeiro/pagamento/fin_cns005_ContasPagamento', [
            'clientePAG' => $cliente,
            'empresaPAG' => $request->empresa,
            'idPagamento' => $idPagamento,
            'dadosContasPag' => $dadosContasPag,
            'tipoConta' => $request->tipCC
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de abertura do formulario
    |----------------------------------------------------------------------------------------------------
    */
    public function abreFormularioPagInDin ($numCC)
    {    
        $idPagamento = Session::get('glo_pagamento_id');
        $tipoCC = Session::get('glo_pagamento_pcc_tipo_cc');
        $empresa = Session::get('glo_pagamento_empresa');
        $cliente = Session::get('glo_pagamento_cliente');

        //Busca os dados da conta selecionada
        $dadosContaPag = DB::table('financeiro_contas_correntes')
        ->where('conta_tipo', $tipoCC)
        ->where('conta_empresa', $empresa)
        ->where('conta_responsavel', $cliente)
        ->where('conta_num_conta', $numCC)
        ->first();
        
        return view('/financeiro/pagamento/fin_frm005_FormularioPagDin', [
            'clientePAG' => $cliente,
            'empresaPAG' => $empresa,
            'idPagamento' => $idPagamento,
            'dadosContaPag' => $dadosContaPag
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de execução da exclusão pagamento iniciado
    |----------------------------------------------------------------------------------------------------
    */
    public function excluiPCCDin($empresa, $idPagamento)
    {    
        DB::table('financeiro_pagamento_headers')
        ->where('paghdr_cod_pag', $idPagamento)
        ->where('paghdr_emp', $empresa)
        ->delete();

        DB::table('financeiro_pagamento_contas_correntes')
        ->where('pagcct_emp', $empresa)
        ->where('pagcct_cod_pag', $idPagamento)
        ->delete();

        // Redireciona de volta para a página principal
        return redirect()->route('home.homePCCIndDin')->with('success', 'Pagamento cancelado com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de abertura da consulta de contas
    |----------------------------------------------------------------------------------------------------
    */
    public function consultaContas()
    {    
        $idPagamento = Session::get('glo_pagamento_id');
        $tipoCC = Session::get('glo_pagamento_pcc_tipo_cc');
        $empresa = Session::get('glo_pagamento_empresa');
        $cliente = Session::get('glo_pagamento_cliente');

        //Busca os dados das contas em aberto
        $dadosContasPag = DB::table('financeiro_contas_correntes')
        ->where('conta_tipo', $tipoCC)
        ->where('conta_empresa', $empresa)
        ->where('conta_responsavel', $cliente)
        ->where('conta_situacao', 'A')
        ->get();
        
        return view('/financeiro/pagamento/fin_cns005_ContasPagamento', [
            'clientePAG' => $cliente,
            'empresaPAG' => $empresa,
            'idPagamento' => $idPagamento,
            'dadosContasPag' => $dadosContasPag,
            'tipoConta' => $tipoCC
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Finalizar o pagamento individual em dinheiro
    |----------------------------------------------------------------------------------------------------
    */
    public function finalizarPCC(Request $request)
    {    
        //Gera a variavel do pagamento
        $idPagamento = Session::get('glo_pagamento_id');
        $empresa = Session::get('glo_pagamento_empresa');
        $cliente = Session::get('glo_pagamento_cliente');

        $valorPago = Helper::limpaValorMonetario($request->valorPag);

        if($valorPago == 0){
            return redirect()->back()->with('info', 'O Valor do Pagamento não pode ser zero!');
        }

        // Atualiza a observação do pagamento
        DB::table('financeiro_pagamento_headers')
        ->where('paghdr_emp', $empresa)
        ->where('paghdr_cod_pag', $idPagamento)
        ->update(['paghdr_obs' => $request->observacao]);

        //Inicia o Database Transaction
        DB::beginTransaction();
        
        //Insere os dados do Pagamento
        FinanceiroPagamentoContasCorrentesController::insert($empresa, $idPagamento, $cliente, 'I', $request);

        $data = date('Y-m-d');
        $usuario = Auth::user()->usuario_codigo;
        $app = "fin_frm005_FormularioPagDin";

        //DB::commit();
        //echo $empresa."','".$data."',".$idPagamento.",'".$cliente."','".$usuario."','".$app;exit;
        

        $exec_fn = DB::select("select ret_sts_pag, ret_msg_pag from fn_financeiro_pagamento('".$empresa."','".$data."',".$idPagamento.",'".$cliente."','".$usuario."','".$app."');");

        if($exec_fn[0]->ret_sts_pag == '*'){
            //Falha, desfaz as alterações no banco de dados
            DB::rollBack();

            return redirect()->route('pagamentoPCC.abreFormularioPagInDin',['numCC' => $request->numConta])->with('error', $exec_fn[0]->ret_msg_pag);
        }else{

            // Atualiza o status do recebimento
            DB::table('financeiro_pagamento_headers')
            ->where('paghdr_emp', $empresa)
            ->where('paghdr_cod_pag', $idPagamento)
            ->update(['paghdr_sts' => 'F']);
            
            //Grava as alterações do banco
            DB::commit();
        }

        // Redireciona de volta para a página principal
        return redirect()->route('home.homePCCIndDin')->with('success', 'Pagamento '.$idPagamento.' realizado com sucesso!');
    }
}
