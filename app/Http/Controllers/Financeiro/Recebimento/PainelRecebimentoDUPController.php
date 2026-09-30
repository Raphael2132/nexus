<?php

namespace App\Http\Controllers\Financeiro\Recebimento;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Financeiro\Recebimento\FinanceiroRecebimentoHeaderController;
use App\Http\Controllers\Financeiro\Recebimento\FinanceiroRecebimentoDuplicatasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\Helper;
use App\Http\Helpers\HelperFinanceiro;

class PainelRecebimentoDUPController extends Controller
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
    | Método de execução do filtro inicial do Recebimento de Duplicatas
    |----------------------------------------------------------------------------------------------------
    */
    public function filtroRecDUP()
    {    
        //Inicia a variavel de sessão do processo
        Session::put('glo_recebimento_cliente', '');
        Session::put('glo_recebimento_id', '');
        Session::put('glo_recebimento_empresa', '');
        Session::put('glo_recebimento_origem', '');

        return view('/financeiro/recebimento/fin_cnt002_HomeDUP');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de execução da Consulta de Duplicatas em Abertro
    |----------------------------------------------------------------------------------------------------
    */
    public function dupAbertas()
    {    
        
        $dadosDUP = DB::table('financeiro_contas_receber_clientes')->where('conrec_situacao', 'A')->get();
        
        return view('/financeiro/recebimento/fin_cns002_DupAberta', [
            'duplicatas' => $dadosDUP
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Inicia o Processo de Recebimento de Adiantamento de Clientes - Botão Pesquisar
    |----------------------------------------------------------------------------------------------------
    */
    public function iniciaRecDUP(Request $request)
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
        Session::put('glo_recebimento_origem', 'DUP');

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
        $idRecebimento = FinanceiroRecebimentoHeaderController::insert($request->empresa, 'DUP');

        //Gera a variavel do ID do recebimento aberto
        Session::put('glo_recebimento_id', $idRecebimento);

        //se for inclusão não monta o array por não ter os dados ainda
        $dados = [];

        return view('/financeiro/recebimento/fin_pnl002_PainelDUP', [
            'clienteREC' => $cliente,
            'empresaREC' => $request->empresa,
            'idRecebimento' => $idRecebimento,
            'estagio_app' => 'PAINEL_PRINCIPAL',
            'dupSelecionadas' => $dados,
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Inicia o Processo de Recebimento de Adiantamento de Clientes - Consulta de Duplicatas em Aberto
    |----------------------------------------------------------------------------------------------------
    */
    public function iniciaDUPSel($empresa, $cliente, $numDUP, $seqDUP)
    {    
        //Gera a variavel do cliente do recebimento de AC
        Session::put('glo_recebimento_cliente', $cliente);
        Session::put('glo_recebimento_empresa', $empresa);
        Session::put('glo_recebimento_origem', 'DUP');

        // Chama o método que faz a exclusão de todos os recebimentos em aberto com data menor que a do dia atual
        HelperFinanceiro::limpaRecebimentoAberto();

        // Chama o método que faz a exclusão de todos os recebimentos em aberto do usuário
        //No dia o usuário pode ter entrado e saido sem finalizar algum recebimento então vamos excluir ele também
        //Se um dia for precisar manter o recebimento em aberto durante o dia para o usuario voltar e terminar depois esse trecho deve ser refeito
        HelperFinanceiro::limpaRecebimentoUsuario(Auth::user()->usuario_codigo);

        //Verificar se o Usuario do recebimento tem Razão Associado
        $usuario = Auth::user()->usuario_codigo;
        $dadosUsuFin = DB::table('financeiro_tab_usuarios')->where('tabusu_usuario', $usuario)->where('tabusu_empresa', $empresa)->first();

        if(empty($dadosUsuFin->tabusu_razao)){
            return redirect()->back()->with('info', 'Usuario do logado no sistema não tem razão associado!');
        }

        //Insere o Header da tabela de recebimentos
        $idRecebimento = FinanceiroRecebimentoHeaderController::insert($empresa, 'DUP');

        //Gera a variavel do ID do recebimento aberto
        Session::put('glo_recebimento_id', $idRecebimento);

        //Insere a Duplicata Selecionada na tabela de recebimentos
        FinanceiroRecebimentoDuplicatasController::insert($empresa, $cliente, $numDUP, $seqDUP);

        $dados = DB::table('financeiro_recebimento_duplicatas')
        ->where('recdup_emp', $empresa)
        ->where('recdup_cod_rec', $idRecebimento)
        ->get();

        return view('/financeiro/recebimento/fin_pnl002_PainelDUP', [
            'clienteREC' => $cliente,
            'empresaREC' => $empresa,
            'idRecebimento' => $idRecebimento,
            'estagio_app' => 'PAINEL_PRINCIPAL',
            'dupSelecionadas' => $dados,
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa o painel principal do recebimento de duplicata
    |----------------------------------------------------------------------------------------------------
    */
    public function painelPrincipalDUP($empresa, $cliente, $idRecebimento)
    {            
        $dados = DB::table('financeiro_recebimento_duplicatas')
        ->where('recdup_emp', $empresa)
        ->where('recdup_cod_rec', $idRecebimento)
        ->get();
        
        return view('/financeiro/recebimento/fin_pnl002_PainelDUP', [
            'clienteREC' => $cliente,
            'empresaREC' => $empresa,
            'idRecebimento' => $idRecebimento,
            'estagio_app' => 'PAINEL_PRINCIPAL',
            'dupSelecionadas' => $dados,
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa o painel de seleção de nova duplicata para recebimento
    |----------------------------------------------------------------------------------------------------
    */
    public function selecionarDUP($empresa, $cliente, $idRecebimento)
    {        
        // Busca as duplicatas em aberto do cliente que não estão selecionas no recebimento
        $dados = DB::table('financeiro_contas_receber_clientes as cr')
        ->where('cr.conrec_situacao', 'A')
        ->where('cr.conrec_empresa', $empresa)
        ->where('cr.conrec_cliente', $cliente)
        ->whereNotExists(function ($query) use ($idRecebimento) {
            $query->select(DB::raw(1))
                ->from('financeiro_recebimento_duplicatas as rd')
                ->whereRaw('rd.recdup_emp = cr.conrec_empresa')
                ->where('rd.recdup_cod_rec', $idRecebimento)
                ->whereRaw('rd.recdup_cli = cr.conrec_cliente')
                ->whereRaw('rd.recdup_cod = cr.conrec_codigo')
                ->whereRaw('rd.recdup_seq = cr.conrec_sequencia');
        })
        ->get();

        return view('/financeiro/recebimento/fin_pnl002_PainelDUP', [
            'clienteREC' => $cliente,
            'empresaREC' => $empresa,
            'idRecebimento' => $idRecebimento,
            'estagio_app' => 'PAINEL_SEL_DUP',
            'duplicatasAbertas' => $dados,
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa o modal de inclusão / manutenção de duplicata do recebimento
    |----------------------------------------------------------------------------------------------------
    */
    public function carregarDadosModalIncManDuplicata($empresa, $cliente, $numDUP, $seqDUP, $origem)
    {
        $idRecebimento = Session::get('glo_recebimento_id');

        $dadosDUP = DB::table('financeiro_contas_receber_clientes')
        ->where('conrec_empresa', $empresa)
        ->where('conrec_codigo', $numDUP)
        ->where('conrec_sequencia', $seqDUP)
        ->where('conrec_cliente', $cliente)
        ->first();

        if( $origem == 'MAN' ){

            $dados = DB::table('financeiro_recebimento_duplicatas')
            ->where('recdup_emp', $empresa)
            ->where('recdup_cod_rec', $idRecebimento)
            ->where('recdup_cod', $numDUP)
            ->where('recdup_seq', $seqDUP)
            ->first();

        }else{
            //se for inclusão não monta o array por não ter os dados ainda
            $dados = [];
        }
        
        return view('/financeiro/recebimento/fin_mod002_ModalIncManDUP', [
            'clienteREC' => $cliente,
            'empresaREC' => $empresa,
            'idRecebimento' => $idRecebimento,
            'dadosCRC' => $dadosDUP,
            'dadosREC' => $dados,
            'origemAPP' => $origem
        ])->render();
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Reabre a seleção de duplicatas para recebimento
    |----------------------------------------------------------------------------------------------------
    */
    public function reabreSelecao($empresa, $cliente, $idRecebimento)
    {    

        // Chama o método que limpa os dados dos valores do recebimento
        HelperFinanceiro::limpaValoresRecebimento($empresa, $idRecebimento);

        $dados = DB::table('financeiro_recebimento_duplicatas')
        ->where('recdup_emp', $empresa)
        ->where('recdup_cod_rec', $idRecebimento)
        ->get();
        
        return view('/financeiro/recebimento/fin_pnl002_PainelDUP', [
            'clienteREC' => $cliente,
            'empresaREC' => $empresa,
            'idRecebimento' => $idRecebimento,
            'estagio_app' => 'PAINEL_PRINCIPAL',
            'dupSelecionadas' => $dados,
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Finaliza o recebimento de Duplicatas
    |----------------------------------------------------------------------------------------------------
    */
    public function finalizarRecDUP(Request $request, $empresa, $cliente)
    {    
        $idRecebimento = Session::get('glo_recebimento_id');

        //Verifica se realmente tem duplicatas selecionadas
        $cntDuplicatas = DB::table('financeiro_recebimento_duplicatas')
        ->where('recdup_emp', $empresa)
        ->where('recdup_cod_rec', $idRecebimento)
        ->count();

        if($cntDuplicatas == 0){
            return redirect()->back()->with('info', 'Não há duplicatas selecionadas para recebimento!');
        }

        $valRecebido = DB::table('financeiro_recebimento_valores')->where('recval_emp',$empresa)->where('recval_cod_rec',$idRecebimento)->where('recval_tipo', '<>', 'OUT')->sum('recval_valor');
        
        if($valRecebido == 0){
            return redirect()->back()->with('info', 'O valor recebido não pode ser zero!');
        }

        //Soma o valor total a ser recebido das duplicatas
        $totalRecebimento = DB::table('financeiro_recebimento_duplicatas')
        ->selectRaw("
            SUM(
                CASE 
                    WHEN recdup_tip_opr = 'M' THEN recdup_vlr_bxa + recdup_vlr_jmt
                    WHEN recdup_tip_opr IN ('F', 'R') THEN recdup_vlr_bxa - recdup_vlr_des
                    ELSE 0
                END
            ) as total_recebimentos
        ")
        ->where('recdup_emp', $empresa)
        ->where('recdup_cod_rec', $idRecebimento)
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


        // Atualiza a observação do recebimento
        DB::table('financeiro_recebimento_headers')
        ->where('rechdr_emp', $empresa)
        ->where('rechdr_cod_rec', $idRecebimento)
        ->update(['rechdr_obs' => $request->observacao]);

        //Inicia o Database Transaction
        DB::beginTransaction();

        $data = date('Y-m-d');
        $usuario = Auth::user()->usuario_codigo;
        $app = "fin_pnl002_PainelDUP";

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

        return redirect()->route('home.filtroRecDUP')->with('success2', 'Recebimento '.$idRecebimento.' realizado com sucesso!');
    }
}
