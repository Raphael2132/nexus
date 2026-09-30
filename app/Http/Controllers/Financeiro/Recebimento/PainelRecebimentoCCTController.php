<?php

namespace App\Http\Controllers\Financeiro\Recebimento;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Financeiro\Recebimento\FinanceiroRecebimentoHeaderController;
use App\Http\Controllers\Financeiro\Recebimento\FinanceiroRecebimentoContasCorrentesController;
use Illuminate\Http\Request;
use stdClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\Helper;
use App\Http\Helpers\HelperFinanceiro;

class PainelRecebimentoCCTController extends Controller
{
    /* *****
    |*****************************************************************************************************************************
    |*****************************************************************************************************************************
    |
    | Recebimento de Contas Correntes
    |
    |*****************************************************************************************************************************
    |*****************************************************************************************************************************
    ***** */

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de execução do filtro inicial do Recebimento de Adiantamento de Clientes
    |----------------------------------------------------------------------------------------------------
    */
    public function filtroRecCCT()
    {    
        //Inicia a variavel de sessão do processo
        Session::put('glo_recebimento_cliente', '');
        Session::put('glo_recebimento_id', '');
        Session::put('glo_recebimento_empresa', '');
        Session::put('glo_recebimento_origem', '');

        return view('/financeiro/recebimento/fin_cnt001_HomeCCT');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Inicia o Processo de Recebimento de Adiantamento de Clientes
    |----------------------------------------------------------------------------------------------------
    */
    public function iniciaRecCCT(Request $request)
    {    
        //Cliente
        if(!empty($request->cliente)){

            $cliente = substr($request->cliente, 0, 10);

            if(strlen($cliente) < 10){
                return redirect()->back()->with('info', 'Código do cliente '.$cliente.' é inválido!');
            }
        }

        //Gera a variavel do cliente do recebimento de AC
        Session::put('glo_recebimento_cliente', $cliente);
        Session::put('glo_recebimento_empresa', $request->empresa);
        Session::put('glo_recebimento_origem', 'CCT');

        // Chama o método que faz a exclusão de todos os recebimentos em aberto com data menor que a do dia atual
        HelperFinanceiro::limpaRecebimentoAberto();

        // Chama o método que faz a exclusão de todos os recebimentos em aberto do usuário
        //No dia o usuário pode ter entrado e saido sem finalizar algum recebimento então vamos excluir ele também
        //Se um dia for precisar manter o recebimento em aberto durante o dia para o usuario voltar e terminar depois esse trecho deve ser refeito
        HelperFinanceiro::limpaRecebimentoUsuario(Auth::user()->usuario_codigo);

        //Verificar se o Usuario do recebimento tem Razão Associado
        $usuario = Auth::user()->usuario_codigo;
        $dadosUsuFin = DB::table('financeiro_tab_usuarios')->where('tabusu_usuario', $usuario)->where('tabusu_empresa', $request->empresa)->first();

        if(empty($dadosUsuFin->tabusu_razao)){
            return redirect()->back()->with('info', 'Usuario do logado no sistema não tem razão associado!');
        }

        //Insere o Header da tabela de recebimentos
        $idRecebimento = FinanceiroRecebimentoHeaderController::insert($request->empresa, 'CCT');

        //Gera a variavel do ID do recebimento aberto
        Session::put('glo_recebimento_id', $idRecebimento);
        
        //Como acabamos de criar o recebimento passamos um array vazio
        $dados = [];

        return view('/financeiro/recebimento/fin_pnl001_PainelCCT', [
            'clienteREC' => $cliente,
            'empresaREC' => $request->empresa,
            'idRecebimento' => $idRecebimento,
            'estagio_app' => 'PAINEL_PRINCIPAL',
            'contasRecebidas' => $dados,
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa o painel principal do recebimento de conta corrente
    |----------------------------------------------------------------------------------------------------
    */
    public function painelPrincipalCCT($empresa, $cliente, $idRecebimento)
    {            
        //Como acabamos de criar o recebimento passamos um array vazio
        $dados = DB::table('financeiro_recebimento_contas_correntes')
        ->where('reccct_emp', $empresa)
        ->where('reccct_cod_rec', $idRecebimento)
        ->get();

        return view('/financeiro/recebimento/fin_pnl001_PainelCCT', [
            'clienteREC' => $cliente,
            'empresaREC' => $empresa,
            'idRecebimento' => $idRecebimento,
            'estagio_app' => 'PAINEL_PRINCIPAL',
            'contasRecebidas' => $dados,
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Abre a consulta de contas correntes abertas para recebimento
    |----------------------------------------------------------------------------------------------------
    */
    public function consultaCCTAberta($empresa, $cliente, $idRecebimento)
    {    
        //Busca as contas em aberto do cliente que ainda não estão selecionadas para recebimento
        $dadosCCR = DB::table('financeiro_contas_correntes as fcc')
        ->where('fcc.conta_empresa', $empresa)
        ->whereIn('fcc.conta_tipo', ['AC', 'AF', 'DC', 'RD'])
        ->where('fcc.conta_responsavel', $cliente)
        ->where('fcc.conta_situacao', 'A')
        ->whereNotExists(function ($query) use ($idRecebimento) {
            $query->select(DB::raw(1))
                ->from('financeiro_recebimento_contas_correntes as frcc')
                ->whereRaw('frcc.reccct_tcc = fcc.conta_tipo')
                ->whereRaw('frcc.reccct_res = fcc.conta_responsavel')
                ->whereRaw('frcc.reccct_ncc = fcc.conta_num_conta')
                ->whereRaw('frcc.reccct_emp = fcc.conta_empresa')
                ->where('frcc.reccct_cod_rec', $idRecebimento);
        })
        ->orderBy('fcc.conta_tipo', 'asc')
        ->orderBy('fcc.conta_num_conta', 'asc')
        ->get();

        return view('/financeiro/recebimento/fin_pnl001_PainelCCT', [
            'clienteREC' => $cliente,
            'empresaREC' => $empresa,
            'idRecebimento' => $idRecebimento,
            'estagio_app' => 'SEL_CONTAS_ABERTAS',
            'contasAbertas' => $dadosCCR,
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Abre o formulário de inclusão de nova conta corrente para recebimento
    |----------------------------------------------------------------------------------------------------
    */
    public function novaCCT($empresa, $cliente, $idRecebimento)
    {    
        return view('/financeiro/recebimento/fin_pnl001_PainelCCT', [
            'clienteREC' => $cliente,
            'empresaREC' => $empresa,
            'idRecebimento' => $idRecebimento,
            'estagio_app' => 'NOVA_CONTA_CORRENTE'
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Abre o formulario de confirmação dos dados da conta corrente selecionada para inclusão
    |----------------------------------------------------------------------------------------------------
    */
    public function contaSelecionada($empresa, $cliente, $idRecebimento, $tipoCCR, $numCCR)
    { 
        $dadosCCR = DB::table('financeiro_contas_correntes')
        ->where('conta_empresa', $empresa)
        ->where('conta_tipo', $tipoCCR)
        ->where('conta_responsavel', $cliente)
        ->where('conta_num_conta', $numCCR)
        ->first();

        return view('/financeiro/recebimento/fin_pnl001_PainelCCT', [
            'clienteREC' => $cliente,
            'empresaREC' => $empresa,
            'idRecebimento' => $idRecebimento,
            'estagio_app' => 'CONTA_SELECIONADA',
            'contaSelecionada' => $dadosCCR,
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Insere a Conta Corrente no Recebimento
    |----------------------------------------------------------------------------------------------------
    */
    public function inserirCCT(Request $request, $empresa, $cliente, $idRecebimento)
    {    
        if($request->tipoCCR != 'AC'){

            $dadosCCR = DB::table('financeiro_contas_correntes')
            ->where('conta_empresa', $empresa)
            ->where('conta_tipo', $request->tipoCCR)
            ->where('conta_responsavel', $cliente)
            ->where('conta_num_conta', $request->numCCR)
            ->first();

            $valorRec = Helper::limpaValormonetario($request->valCCT);

            if($valorRec > $dadosCCR->conta_valor){

                return redirect()->back()->with('info', 'O Valor do Recebimento não pode ser maior que o Valor da Conta!');
            }
        }

        if($request->tipoCCR == 'AC'){
            $operacao = 'A';
        }else{
            $operacao = 'B';
        }

        //Insere a conta corrente em aberto no recebimento
        FinanceiroRecebimentoContasCorrentesController::insert($empresa, $cliente, $idRecebimento, $operacao, $request);

        return redirect()->route('recebimentoCCT.painelPrincipalCCT', [
            'cliente' => $cliente,
            'empresa' => $empresa,
            'idRecebimento' => $idRecebimento
        ])->with('success', 'Conta adicionada com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Insere a Nova Conta Corrente no Recebimento
    |----------------------------------------------------------------------------------------------------
    */
    public function inserirNovaCCT(Request $request, $empresa, $cliente, $idRecebimento)
    {    
        if($request->valCCT == 0){

            return redirect()->back()->with('info', 'O Valor do Recebimento não pode ser zero!');
        }

        $dataEmi = Helper::limpaData($request->dataEmiCCR);
        $dataVct = Helper::limpaData($request->dataVctCCR);
        $data = date('Y-m-d');

        if($dataEmi > $dataVct){
            return redirect()->back()->with('info', 'A Data de Emissão não pode ser maior que a Data de Vencimento!');
        }

        if($dataEmi > $data){
            return redirect()->back()->with('info', 'A Data de Emissão não pode ser maior que a Data de Hoje!');
        }

        //Insere a conta corrente em aberto no recebimento
        FinanceiroRecebimentoContasCorrentesController::insert($empresa, $cliente, $idRecebimento, 'N', $request);

        return redirect()->route('recebimentoCCT.painelPrincipalCCT', [
            'cliente' => $cliente,
            'empresa' => $empresa,
            'idRecebimento' => $idRecebimento
        ])->with('success', 'Conta adicionada com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Exclui a Conta Corrente do Recebimento
    |----------------------------------------------------------------------------------------------------
    */
    public function excluirCCT($empresa, $cliente, $idRecebimento, $tipoCCR, $numCCR)
    {    
        //Insere a conta corrente em aberto no recebimento
        FinanceiroRecebimentoContasCorrentesController::delete($empresa, $cliente, $idRecebimento, $tipoCCR, $numCCR);

        return redirect()->route('recebimentoCCT.painelPrincipalCCT', [
            'cliente' => $cliente,
            'empresa' => $empresa,
            'idRecebimento' => $idRecebimento
        ])->with('success', 'Conta excluída com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Finaliza o recebimento de contas correntes
    |----------------------------------------------------------------------------------------------------
    */
    public function finalizarRecCCT(Request $request, $empresa, $cliente)
    {    
        $idRecebimento = Session::get('glo_recebimento_id');
        $cliente = Session::get('glo_recebimento_cliente');

        //Como acabamos de criar o recebimento passamos um array vazio
        $cntContas = DB::table('financeiro_recebimento_contas_correntes')
        ->where('reccct_emp', $empresa)
        ->where('reccct_cod_rec', $idRecebimento)
        ->count();

        if($cntContas == 0){
            return redirect()->back()->with('info', 'Não há contas selecionadas para recebimento!');
        }

        $valRecebido = DB::table('financeiro_recebimento_valores')->where('recval_emp',$empresa)->where('recval_cod_rec',$idRecebimento)->where('recval_tipo', '<>', 'OUT')->sum('recval_valor');
        
        if($valRecebido == 0){
            return redirect()->back()->with('info', 'O valor recebido não pode ser zero!');
        }

        //Soma o valor total a ser recebido das contas correntes
        $totalRecebimento = DB::table('financeiro_recebimento_contas_correntes')
        ->selectRaw("
            SUM(
                CASE 
                    WHEN reccct_tcc = 'AC' THEN reccct_valor_rec
                    WHEN reccct_tcc IN ('AF', 'DC') THEN reccct_valor_rec + reccct_acrescimo
                    WHEN reccct_tcc = 'RD' THEN (reccct_valor_rec + reccct_acrescimo) - reccct_valor_iss - reccct_valor_irrf - reccct_desp_banc
                    ELSE 0
                END
            ) as total_recebimentos
        ")
        ->where('reccct_emp', $empresa)
        ->where('reccct_cod_rec', $idRecebimento)
        ->value('total_recebimentos');

        if($valRecebido < $totalRecebimento){
            return redirect()->back()->with('info', 'O valor a recebido é menor que o valor total a receber!');
        }

        //Verifica se o valor a receber for maior que o do recebimento criamos o troco
        if($valRecebido > $totalRecebimento ){
	
            $troco = $valRecebido - $totalRecebimento;
            
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

        if(!empty($request->observacao)){
            
            // Atualiza a observação do recebimento
            DB::table('financeiro_recebimento_headers')
            ->where('rechdr_emp', $empresa)
            ->where('rechdr_cod_rec', $idRecebimento)
            ->update(['rechdr_obs' => $request->observacao]);
        }

        //Inicia o Database Transaction
        DB::beginTransaction();

        $data = date('Y-m-d');
        $usuario = Auth::user()->usuario_codigo;
        $app = "fin_pnl001_PainelCCT";
        
        $exec_fn = DB::select("select ret_sts_rec, ret_msg_rec from fn_financeiro_recebimento('".$empresa."','".$data."',".$idRecebimento.",'".$cliente."','".$usuario."','".$app."');");

        if($exec_fn[0]->ret_sts_rec == '*'){
            //Falha, desfaz as alterações no banco de dados
            DB::rollBack();

            return redirect()->back()->with('error', $exec_fn[0]->ret_msg_rec);
        }else{

            // Atualiza o status do recebimento
            DB::table('financeiro_recebimento_headers')
            ->where('rechdr_emp', $empresa)
            ->where('rechdr_cod_rec', $idRecebimento)
            ->update(['rechdr_sts' => 'F']);
            
            //Grava as alterações do banco
            DB::commit();
        }

        return redirect()->route('home.filtroRecCCT')->with('success2', 'Recebimento '.$idRecebimento.' realizado com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Reabre a seleção de contas corrente para recebimento
    |----------------------------------------------------------------------------------------------------
    */
    public function reabreSelecao($empresa, $cliente, $idRecebimento)
    {    

        // Chama o método que limpa os dados dos valores do recebimento
        HelperFinanceiro::limpaValoresRecebimento($empresa, $idRecebimento);

        //Como acabamos de criar o recebimento passamos um array vazio
        $dados = DB::table('financeiro_recebimento_contas_correntes')
        ->where('reccct_emp', $empresa)
        ->where('reccct_cod_rec', $idRecebimento)
        ->get();

        return view('/financeiro/recebimento/fin_pnl001_PainelCCT', [
            'clienteREC' => $cliente,
            'empresaREC' => $empresa,
            'idRecebimento' => $idRecebimento,
            'estagio_app' => 'PAINEL_PRINCIPAL',
            'contasRecebidas' => $dados,
        ]);
    }
}
