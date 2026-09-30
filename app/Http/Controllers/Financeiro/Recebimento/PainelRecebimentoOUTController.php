<?php

namespace App\Http\Controllers\Financeiro\Recebimento;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Financeiro\Recebimento\FinanceiroRecebimentoHeaderController;
use App\Http\Controllers\Financeiro\Recebimento\FinanceiroRecebimentoOutrosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\Helper;
use App\Http\Helpers\HelperFinanceiro;

class PainelRecebimentoOUTController extends Controller
{
    /* *****
    |*****************************************************************************************************************************
    |*****************************************************************************************************************************
    |
    | Outros Recebimentos
    |
    |*****************************************************************************************************************************
    |*****************************************************************************************************************************
    ***** */

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de execução do filtro inicial de Outros Recebimentos
    |----------------------------------------------------------------------------------------------------
    */
    public function filtroRecOUT()
    {    
        //Inicia a variavel de sessão do processo
        Session::put('glo_recebimento_cliente', '');
        Session::put('glo_recebimento_id', '');
        Session::put('glo_recebimento_empresa', '');
        Session::put('glo_recebimento_origem', '');

        return view('/financeiro/recebimento/fin_cnt003_HomeOUT');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Inicia o Processo de Outros Recebimentos
    |----------------------------------------------------------------------------------------------------
    */
    public function iniciaRecOUT(Request $request)
    {    
        //Gera a variavel da empresa e origem
        Session::put('glo_recebimento_empresa', $request->empresa);
        Session::put('glo_recebimento_origem', 'OUT');

        // Chama o método que faz a exclusão de todos os recebimentos em aberto com data menor que a do dia atual
        HelperFinanceiro::limpaRecebimentoAberto();

        // Chama o método que faz a exclusão de todos os recebimentos em aberto do usuário
        //No dia o usuário pode ter entrado e saido sem finalizar algum recebimento então vamos excluir ele também
        //Se um dia for precisar manter o recebimento em aberto durante o dia para o usuario voltar e terminar OUTois esse trecho deve ser refeito
        HelperFinanceiro::limpaRecebimentoUsuario(Auth::user()->usuario_codigo);

        //Verificar se o Usuario do recebimento tem Razão Associado
        $usuario = Auth::user()->usuario_codigo;
        $dadosUsuFin = DB::table('financeiro_tab_usuarios')->where('tabusu_usuario', $usuario)->where('tabusu_empresa', $request->empresa)->first();

        if(empty($dadosUsuFin->tabusu_razao)){
            return redirect()->back()->with('info', 'Usuario do logado no sistema não tem razão associado!');
        }

        $valor = Helper::limpaValorMonetario($request->valorRecebimento);

        if($valor == 0){
            return redirect()->back()->with('info', 'O Valor a Receber deve ser informado!');
        }

        //Insere o Header da tabela de recebimentos
        $idRecebimento = FinanceiroRecebimentoHeaderController::insert($request->empresa, 'OUT');

        //Gera a variavel do ID do recebimento aberto
        Session::put('glo_recebimento_id', $idRecebimento);

        //Faz o insert inicial dos dados do recebimento
        FinanceiroRecebimentoOutrosController::insert($request->empresa, $valor, $request->tipCred);

        //se for inclusão não monta o array por não ter os dados ainda
        $dados = DB::table('financeiro_recebimento_outros')->where('recout_emp', $request->empresa)->where('recout_cod_rec', $idRecebimento)->first();

        return view('/financeiro/recebimento/fin_pnl003_PainelOUT', [
            'empresaREC' => $request->empresa,
            'idRecebimento' => $idRecebimento,
            'estagio_app' => 'PAINEL_PRINCIPAL',
            'subEstagio' => 'NEW',
            'dadosOUT' => $dados,
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Salva a atualização dos dados do recebimento
    |----------------------------------------------------------------------------------------------------
    */
    public function atualizaRec(Request $request, $empresa, $subEstagio)
    {    
        //Cliente
        if(!empty($request->cliente)){

            $cliente = substr($request->cliente, 0, 10);

            if(strlen($cliente) < 10){
                return redirect()->route('recebimentoOUT.abreFormulario',[
                    'empresa' => $empresa,
                    'subEstagio' => $subEstagio,
                ])->with('info', 'Código do cliente '.$cliente.' é inválido!');
            }

            //Verifica se o cliente existe
            $cnt_cli = DB::table('cadastro_clientes')->where('cliente_codigo', $cliente)->count();

            if($cnt_cli == 0){
                return redirect()->route('recebimentoOUT.abreFormulario',[
                    'empresa' => $empresa,
                    'subEstagio' => $subEstagio,
                ])->with('info', 'Cliente '.$cliente.' não encontrado!');
            }
        }else{
            return redirect()->route('recebimentoOUT.abreFormulario',[
                'empresa' => $empresa,
                'subEstagio' => $subEstagio,
            ])->with('info', 'Cliente não informado!!');
        }

        $dataComprovante = Helper::limpaData($request->dataComp);

        //Gera a variavel do cliente do recebimento
        Session::put('glo_recebimento_cliente', $cliente);

        $idRecebimento = Session::get('glo_recebimento_id');

        //Faz o insert inicial dos dados do recebimento
        FinanceiroRecebimentoOutrosController::update($empresa, $cliente, $dataComprovante, $request->numDocOUT, $request->compOUT);

        //se for inclusão não monta o array por não ter os dados ainda
        $dados = DB::table('financeiro_recebimento_outros')->where('recout_emp', $empresa)->where('recout_cod_rec', $idRecebimento)->first();;

        return view('/financeiro/recebimento/fin_pnl003_PainelOUT', [
            'empresaREC' => $empresa,
            'clienteREC' => $cliente,
            'idRecebimento' => $idRecebimento,
            'estagio_app' => 'PAINEL_CONFIRMACAO',
            'dadosOUT' => $dados,
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Abre o formulario de atualização dos dados do recebimento
    |----------------------------------------------------------------------------------------------------
    */
    public function abreFormulario($empresa, $subEstagio)
    {    
        $idRecebimento = Session::get('glo_recebimento_id');

        // Chama o método que limpa os dados dos valores do recebimento
        HelperFinanceiro::limpaValoresRecebimento($empresa, $idRecebimento);

        //se for inclusão não monta o array por não ter os dados ainda
        $dados = DB::table('financeiro_recebimento_outros')->where('recout_emp', $empresa)->where('recout_cod_rec', $idRecebimento)->first();

        return view('/financeiro/recebimento/fin_pnl003_PainelOUT', [
            'empresaREC' => $empresa,
            'idRecebimento' => $idRecebimento,
            'estagio_app' => 'PAINEL_PRINCIPAL',
            'subEstagio' => $subEstagio,
            'dadosOUT' => $dados,
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Abre a confirmação dos dados do recebimento
    |----------------------------------------------------------------------------------------------------
    */
    public function abreConfirmacao($empresa)
    {    
        $idRecebimento = Session::get('glo_recebimento_id');
        $cliente = Session::get('glo_recebimento_cliente');

        //se for inclusão não monta o array por não ter os dados ainda
        $dados = DB::table('financeiro_recebimento_outros')->where('recout_emp', $empresa)->where('recout_cod_rec', $idRecebimento)->first();

        return view('/financeiro/recebimento/fin_pnl003_PainelOUT', [
            'empresaREC' => $empresa,
            'clienteREC' => $cliente,
            'idRecebimento' => $idRecebimento,
            'estagio_app' => 'PAINEL_CONFIRMACAO',
            'dadosOUT' => $dados,
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Finaliza o recebimento
    |----------------------------------------------------------------------------------------------------
    */
    public function finalizarRecOUT(Request $request, $empresa, $cliente)
    {    
        $idRecebimento = Session::get('glo_recebimento_id');

        $valRecebido = DB::table('financeiro_recebimento_valores')->where('recval_emp',$empresa)->where('recval_cod_rec',$idRecebimento)->where('recval_tipo', '<>', 'OUT')->sum('recval_valor');
        
        if($valRecebido == 0){
            return redirect()->back()->with('info', 'O valor recebido não pode ser zero!');
        }

        //Soma o valor total a ser recebido
        $totalRecebimento = DB::table('financeiro_recebimento_outros')
        ->where('recout_emp', $empresa)
        ->where('recout_cod_rec', $idRecebimento)
        ->value('recout_vlr');

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
        $app = "fin_pnl003_PainelOUT";

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

        return redirect()->route('home.filtroRecOUT')->with('success2', 'Recebimento '.$idRecebimento.' realizado com sucesso!');
    }
}
