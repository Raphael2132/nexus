<?php

namespace App\Http\Controllers\Financeiro\Pagamento;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Financeiro\Pagamento\FinanceiroPagamentoHeaderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\Helper;
use App\Http\Helpers\HelperFinanceiro;

class PainelPagamentoPPDController extends Controller
{
    /* *****
    |*****************************************************************************************************************************
    |*****************************************************************************************************************************
    |
    | Pagamento de Pequenas Despesas (Obrigações / Outros Débitos)
    |
    |*****************************************************************************************************************************
    |*****************************************************************************************************************************
    ***** */

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de execução do formulário inicial do Pagamento de Pequenas Despesas
    |----------------------------------------------------------------------------------------------------
    */
    public function homePPD()
    {    
        //Inicia a variavel de sessão do processo
        Session::put('glo_pagamento_cliente', '');
        Session::put('glo_pagamento_id', '');
        Session::put('glo_pagamento_empresa', '');
        Session::put('glo_pagamento_origem', '');

        return view('/financeiro/pagamento/fin_cnt004_HomePPD');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de execução do inicio do processo de pagamento
    |----------------------------------------------------------------------------------------------------
    */
    public function iniciaPPD(Request $request)
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
        Session::put('glo_pagamento_origem', 'PPD');
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
        $idPagamento = FinanceiroPagamentoHeaderController::insert($request->empresa, 'PPD');

        //Gera a variavel do ID do pagamento aberto
        Session::put('glo_pagamento_id', $idPagamento);

        //Como não tem dados ainda não gera a variavel
        $dadosPagamento = [];
        
        return view('/financeiro/pagamento/fin_frm004_FormularioPPD', [
            'clientePAG' => $cliente,
            'empresaPAG' => $request->empresa,
            'idPagamento' => $idPagamento,
            'dadosPagamento' => $dadosPagamento
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de execução da exclusão pagamento iniciado
    |----------------------------------------------------------------------------------------------------
    */
    public function excluiPPD($empresa, $idPagamento)
    {    
        DB::table('financeiro_pagamento_headers')
        ->where('paghdr_cod_pag', $idPagamento)
        ->where('paghdr_emp', $empresa)
        ->delete();

        DB::table('financeiro_pagamento_pequenas_despesas')
        ->where('pagpqd_emp', $empresa)
        ->where('pagpqd_cod_pag', $idPagamento)
        ->delete();

        // Redireciona de volta para a página principal
        return redirect()->route('home.homePPD')->with('success', 'Pagamento cancelado com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Chama o formulario com o header já criado
    |----------------------------------------------------------------------------------------------------
    */
    public function formularioPPD()
    {    
        //Gera a variavel do pagamento
        $idPagamento = Session::get('glo_pagamento_id');
        $empresa = Session::get('glo_pagamento_empresa');
        $cliente = Session::get('glo_pagamento_cliente');

        $dadosPagamento = DB::table('financeiro_pagamento_pequenas_despesas')->where('pagpqd_emp', $empresa)->where('pagpqd_cod_pag', $idPagamento)->first();

        return view('/financeiro/pagamento/fin_frm004_FormularioPPD', [
            'clientePAG' => $cliente,
            'empresaPAG' => $empresa,
            'idPagamento' => $idPagamento,
            'dadosPagamento' => $dadosPagamento
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Finalizar o pagamento de pequenas despesas
    |----------------------------------------------------------------------------------------------------
    */
    public function finalizarPPD(Request $request)
    {    
        //Gera a variavel do pagamento
        $idPagamento = Session::get('glo_pagamento_id');
        $empresa = Session::get('glo_pagamento_empresa');
        $cliente = Session::get('glo_pagamento_cliente');

        $valorPago = Helper::limpaValorMonetario($request->valor);

        if($valorPago == 0){
            return redirect()->route('pagamentoPPD.formularioPPD')->with('info', 'O Valor do Pagamento não pode ser zero!');
        }

        // Atualiza a observação do pagamento
        DB::table('financeiro_pagamento_headers')
        ->where('paghdr_emp', $empresa)
        ->where('paghdr_cod_pag', $idPagamento)
        ->update(['paghdr_obs' => $request->observacao]);

        // Vamos deletar o registro da tabela de pequenas despesas (tendo ou não registro)
        // Se deu erro na geração da function do pagamento vai retornar para o formulario e ao tentar executar novamente vai dar erro de chave primaria
        // Isso acontece porque mesmo com o erro ficou salvo o registro na tabela
        // Precisamos manter o registro para voltar para o formulario com os mesmos dados preenchidos
        // Assim é mais facil fazer esse delete agora do que fazer uma função de update no controler e assim apenas inserimos novamente resolvendo mais facil o problema
        DB::table('financeiro_pagamento_pequenas_despesas')
        ->where('pagpqd_emp', $empresa)
        ->where('pagpqd_cod_pag', $idPagamento)
        ->delete();

        //Insere os dados do Pagamento
        FinanceiroPagamentoPequenasDespesasController::insert($empresa, $idPagamento, $cliente, $request);

        //Inicia o Database Transaction
        DB::beginTransaction();

        $data = date('Y-m-d');
        $usuario = Auth::user()->usuario_codigo;
        $app = "fin_frm004_FormularioPPD";
        
        $exec_fn = DB::select("select ret_sts_pag, ret_msg_pag from fn_financeiro_pagamento('".$empresa."','".$data."',".$idPagamento.",'".$cliente."','".$usuario."','".$app."');");

        if($exec_fn[0]->ret_sts_pag == '*'){
            //Falha, desfaz as alterações no banco de dados
            DB::rollBack();

            return redirect()->route('pagamentoPPD.formularioPPD')->with('error', $exec_fn[0]->ret_msg_pag);
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
        return redirect()->route('home.homePPD')->with('success', 'Pagamento '.$idPagamento.' realizado com sucesso!');
    }
}
