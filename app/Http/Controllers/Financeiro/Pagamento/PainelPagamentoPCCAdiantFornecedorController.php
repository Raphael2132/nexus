<?php

namespace App\Http\Controllers\Financeiro\Pagamento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\Helper;
use App\Http\Helpers\HelperFinanceiro;
use App\Http\Controllers\Financeiro\Pagamento\FinanceiroPagamentoHeaderController;
use App\Http\Controllers\Financeiro\Pagamento\FinanceiroPagamentoAdiantamentoFornecedoresController;
use App\Http\Controllers\Financeiro\Pagamento\FinanceiroPagamentoContasCorrentesController;

class PainelPagamentoPCCAdiantFornecedorController extends Controller
{
    /* *****
    |*****************************************************************************************************************************
    |*****************************************************************************************************************************
    |
    | Pagamento de Contas Correntes (Inclusão de Adiantamento de Fornecedor)
    |
    |*****************************************************************************************************************************
    |*****************************************************************************************************************************
    ***** */

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de execução do formulário de Inclusão de Adiantamento de Fornecedor
    |----------------------------------------------------------------------------------------------------
    */
    public function homePCCIncAF()
    {    
        //Inicia a variavel de sessão do processo
        Session::put('glo_pagamento_cliente', '');
        Session::put('glo_pagamento_id', '');
        Session::put('glo_pagamento_empresa', '');
        Session::put('glo_pagamento_origem', '');

        return view('/financeiro/pagamento/fin_frm007_FormularioInclusaoAF');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de execução do inicio do processo de inclusão
    |----------------------------------------------------------------------------------------------------
    */
    public function iniciaPCCIncAF(Request $request)
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
        Session::put('glo_pagamento_origem', 'AF');
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

        //Inicia o Database Transaction
        DB::beginTransaction();

        //Insere o Header da tabela de pagamentos
        $idPagamento = FinanceiroPagamentoHeaderController::insert($request->empresa, 'PCC');

        //Gera a variavel do ID do pagamento aberto
        Session::put('glo_pagamento_id', $idPagamento);

        //Insere a conta do AF
        $numeroAF = FinanceiroPagamentoAdiantamentoFornecedoresController::insert($request->empresa, $cliente, $idPagamento, $request);
        $numeroAFFormatado = str_pad($numeroAF, 9, '0', STR_PAD_LEFT);

        $data = date('Y-m-d');
        $usuario = Auth::user()->usuario_codigo;
        $app = "fin_frm007_FormularioInclusaoAF";

        //DB::commit();
        //echo $request->empresa."','".$data."',".$idPagamento.",'".$cliente."','".$usuario."','".$app;exit;

        $exec_fn = DB::select("select ret_sts_pag, ret_msg_pag from fn_financeiro_pagamento('".$request->empresa."','".$data."',".$idPagamento.",'".$cliente."','".$usuario."','".$app."');");

        if($exec_fn[0]->ret_sts_pag == '*'){
            //Falha, desfaz as alterações no banco de dados
            DB::rollBack();

            return redirect()
            ->back()
            ->withInput() // mantém os dados do formulário
            ->with('error', $exec_fn[0]->ret_msg_pag);

        }else{

            // Atualiza o status do recebimento
            DB::table('financeiro_pagamento_headers')
            ->where('paghdr_emp', $request->empresa)
            ->where('paghdr_cod_pag', $idPagamento)
            ->update(['paghdr_sts' => 'F']);
            
            //Grava as alterações do banco
            DB::commit();
        }

        // Paga o AF incluído
        if ($request->pagar_agora === 'S') {

            //Inicia o Database Transaction
            DB::beginTransaction();

            //Insere o Header da tabela de pagamentos
            $idPagamento = FinanceiroPagamentoHeaderController::insert($request->empresa, 'PCC');

            //Gera a variavel do ID do pagamento aberto
            Session::put('glo_pagamento_id', $idPagamento);

            // Monta um array com os dados mínimos
            $dados = [
                'tipoConta' => 'AF',
                'numConta'  => $numeroAFFormatado,
                'dataPag'   => date('Y-m-d'),
                'valorPag'  => Helper::limpaValorMonetario($request->valorAF)
            ];

            //Insere os dados do Pagamento
            FinanceiroPagamentoContasCorrentesController::insert($request->empresa, $idPagamento, $cliente, 'A', $dados);

            //Grava as alterações do banco
            DB::commit();

            Session::put('glo_pagamento_origem', 'PAG_AF');

            return redirect()->route('pagamentoPCC.fecharLote',['appOrigem' => 'PAG_AF'])->with('success2', 'Adiantamento de Fornecedor registrado com sucesso.<br><br>Gerada a conta corrente AF nº <b>'.$numeroAFFormatado.'</b>');

        }

        return redirect()
        ->route('home.homePCCIncAF')
        ->with(
            'success2',
            'Adiantamento de Fornecedor registrado com sucesso.<br><br>Gerada a conta corrente AF nº <b>'.$numeroAFFormatado.'</b>'
        );

    }
}
