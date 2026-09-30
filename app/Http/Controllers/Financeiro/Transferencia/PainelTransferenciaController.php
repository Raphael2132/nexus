<?php

namespace App\Http\Controllers\Financeiro\Transferencia;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Financeiro\Transferencia\FinanceiroTransferenciasController;
use App\Http\Controllers\Financeiro\Transferencia\FinanceiroTransferenciasItensController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\Helper;
use App\Http\Helpers\HelperFinanceiro;

class PainelTransferenciaController extends Controller
{
    /* *****
    |*****************************************************************************************************************************
    |*****************************************************************************************************************************
    |
    | Transferência entre Caixa / Tesouraria
    |
    |*****************************************************************************************************************************
    |*****************************************************************************************************************************
    ***** */

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de execução do formulário inicial do Reforço de Caixa
    |----------------------------------------------------------------------------------------------------
    */
    public function homeReforcoCaixa()
    {    
        Session::put('glo_transf_te_origem', '');
        Session::put('glo_transf_cx_destino', '');

        //Limpa as transferências em aberto de dias anteriores
        HelperFinanceiro::limpaTransferenciaAberto();

        return view('/financeiro/transferencia/fin_cnt009_HomeReforcoCaixa');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de execução do painel de Reforço de Caixa
    |----------------------------------------------------------------------------------------------------
    */
    public function painelReforcoCaixa(Request $request)
    {    
        Session::put('glo_transf_te_origem', $request->teOrigem);
        Session::put('glo_transf_cx_destino', $request->cxDestino);

        $ano = date('Y');

        $dadosTE = DB::table('financeiro_razoes')
        ->where('razao_codigo', $request->teOrigem)
        ->where('razao_ano', $ano)
        ->where('razao_tipo', 'TE')
        ->first();

        $dadosCX = DB::table('financeiro_razoes')
        ->where('razao_codigo', $request->cxDestino)
        ->where('razao_ano', $ano)
        ->where('razao_tipo', 'CX')
        ->first();

        return view('/financeiro/transferencia/fin_pnl009_PainelReforcoCaixa',[
            'origemTE' => $request->teOrigem,
            'destinoCX' => $request->cxDestino,
            'dadosTE' => $dadosTE,
            'dadosCX' => $dadosCX,
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de execução do formulário inicial da Transferência de Caixa
    |----------------------------------------------------------------------------------------------------
    */
    public function homeTransfCaixa()
    {    
        Session::put('glo_transf_cxte_destino', '');
        Session::put('glo_transf_cxte_origem', '');
        Session::put('glo_transf_id', '');

        //Limpa as transferências em aberto de dias anteriores
        HelperFinanceiro::limpaTransferenciaAberto();

        return view('/financeiro/transferencia/fin_cnt010_HomeTransferenciaCaixa');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de execução do início da Transferência de Caixa
    |----------------------------------------------------------------------------------------------------
    */
    public function iniciaTransfCaixa($opcao, Request $request)
    {  
        Session::put('glo_transf_cxte_destino', $request->cxteDestino);
        Session::put('glo_transf_cx_origem', $request->cxOrigem);

        $ano = date('Y');

        $dadosOrigem = DB::table('financeiro_razoes')
        ->where('razao_codigo', $request->cxOrigem)
        ->where('razao_ano', $ano)
        ->where('razao_tipo', 'CX')
        ->first();

        $dadosDestino = DB::table('financeiro_razoes')
        ->where('razao_codigo', $request->cxteDestino)
        ->where('razao_ano', $ano)
        ->whereIn('razao_tipo', ['CX','TE'])
        ->first();

        if($opcao == 'Editar'){

            $dadosEditar = DB::table('financeiro_transferencias')
            ->where('transf_tipo_ori', 'CX')
            ->where('transf_codigo_ori', $request->cxOrigem)
            ->where('transf_situacao','M')
            ->first();

            if (!$dadosEditar) {
                return redirect()->back()->with('erro', 'Nenhuma transferência em aberto encontrada para o caixa de origem.');
            }

            $idTransf = $dadosEditar->transf_id;
            Session::put('glo_transf_id', $dadosEditar->transf_id);

        }else{

            if($opcao == 'Excluir'){

                $dadosExcluir = DB::table('financeiro_transferencias')
                ->where('transf_tipo_ori', 'CX')
                ->where('transf_codigo_ori', $request->cxOrigem)
                ->where('transf_situacao','M')
                ->first();

                DB::transaction(function () use ($dadosExcluir) {

                    DB::table('financeiro_transferencias_itens')
                        ->where('tranitm_cod_trans', $dadosExcluir->transf_id)
                        ->delete();

                    DB::table('financeiro_transferencias')
                        ->where('transf_id', $dadosExcluir->transf_id)
                        ->delete();

                });
            }

            $idTransf = FinanceiroTransferenciasController::insert($dadosOrigem->razao_tipo, $dadosOrigem->razao_codigo, $dadosDestino->razao_tipo, $dadosDestino->razao_codigo, '1');
            Session::put('glo_transf_id', $idTransf);

            FinanceiroTransferenciasItensController::insert($dadosOrigem->razao_tipo, $dadosOrigem->razao_codigo, $idTransf);
        }

        $resumo = DB::selectOne("
            SELECT
                SUM(CASE WHEN tranitm_tipo = '0' THEN 1 ELSE 0 END) AS qtd_dinheiro,
                SUM(CASE WHEN tranitm_tipo = '0' THEN tranitm_valor ELSE 0 END) AS vlr_dinheiro,

                SUM(CASE WHEN tranitm_tipo = '1' THEN 1 ELSE 0 END) AS qtd_cheque,
                SUM(CASE WHEN tranitm_tipo = '1' THEN tranitm_valor ELSE 0 END) AS vlr_cheque,

                SUM(CASE WHEN tranitm_tipo = '2' THEN 1 ELSE 0 END) AS qtd_debito,
                SUM(CASE WHEN tranitm_tipo = '2' THEN tranitm_valor ELSE 0 END) AS vlr_debito,

                SUM(CASE WHEN tranitm_tipo = '3' THEN 1 ELSE 0 END) AS qtd_credito,
                SUM(CASE WHEN tranitm_tipo = '3' THEN tranitm_valor ELSE 0 END) AS vlr_credito
            FROM financeiro_transferencias_itens
            WHERE tranitm_situacao = 'T' AND tranitm_cod_trans = ?
        ", [$idTransf]);

        $vlrDinheiro = (float) $resumo->vlr_dinheiro;

        $qtdCheque   = (int)   $resumo->qtd_cheque;
        $vlrCheque   = (float) $resumo->vlr_cheque;

        $qtdDebito   = (int)   $resumo->qtd_debito;
        $vlrDebito   = (float) $resumo->vlr_debito;

        $qtdCredito  = (int)   $resumo->qtd_credito;
        $vlrCredito  = (float) $resumo->vlr_credito;

        $totalTrans = DB::table('financeiro_transferencias_itens')
        ->where('tranitm_cod_trans', $idTransf)
        ->where('tranitm_situacao', 'T')
        ->sum('tranitm_valor');

        return view('/financeiro/transferencia/fin_pnl010_PainelTransferenciaCaixa',[
            'origemCX' => $request->cxOrigem,
            'destinoCXTE' => $request->cxteDestino,
            'dadosOrigem' => $dadosOrigem,
            'dadosDestino' => $dadosDestino,
            'vlrDinheiro' => $vlrDinheiro,
            'qtdCheque' => $qtdCheque,
            'vlrCheque' => $vlrCheque,
            'qtdDebito' => $qtdDebito,
            'vlrDebito' => $vlrDebito,
            'qtdCredito' => $qtdCredito,
            'vlrCredito' => $vlrCredito,
            'totalTrans' => $totalTrans,
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de execução do painel de Transferência de Caixa
    |----------------------------------------------------------------------------------------------------
    */
    public function painelTransfCaixa()
    {    
        $cxteDestino = Session::get('glo_transf_cxte_destino');
        $cxOrigem = Session::get('glo_transf_cx_origem');
        $idTransf = Session::get('glo_transf_id');

        $ano = date('Y');

        $dadosOrigem = DB::table('financeiro_razoes')
        ->where('razao_codigo', $cxOrigem)
        ->where('razao_ano', $ano)
        ->where('razao_tipo', 'CX')
        ->first();

        $dadosDestino = DB::table('financeiro_razoes')
        ->where('razao_codigo', $cxteDestino)
        ->where('razao_ano', $ano)
        ->whereIn('razao_tipo', ['CX','TE'])
        ->first();

        $resumo = DB::selectOne("
            SELECT
                SUM(CASE WHEN tranitm_tipo = '0' THEN 1 ELSE 0 END) AS qtd_dinheiro,
                SUM(CASE WHEN tranitm_tipo = '0' THEN tranitm_valor ELSE 0 END) AS vlr_dinheiro,

                SUM(CASE WHEN tranitm_tipo = '1' THEN 1 ELSE 0 END) AS qtd_cheque,
                SUM(CASE WHEN tranitm_tipo = '1' THEN tranitm_valor ELSE 0 END) AS vlr_cheque,

                SUM(CASE WHEN tranitm_tipo = '2' THEN 1 ELSE 0 END) AS qtd_debito,
                SUM(CASE WHEN tranitm_tipo = '2' THEN tranitm_valor ELSE 0 END) AS vlr_debito,

                SUM(CASE WHEN tranitm_tipo = '3' THEN 1 ELSE 0 END) AS qtd_credito,
                SUM(CASE WHEN tranitm_tipo = '3' THEN tranitm_valor ELSE 0 END) AS vlr_credito
            FROM financeiro_transferencias_itens
            WHERE tranitm_situacao = 'T' AND tranitm_cod_trans = ?
        ", [$idTransf]);

        $vlrDinheiro = (float) $resumo->vlr_dinheiro;

        $qtdCheque   = (int)   $resumo->qtd_cheque;
        $vlrCheque   = (float) $resumo->vlr_cheque;

        $qtdDebito   = (int)   $resumo->qtd_debito;
        $vlrDebito   = (float) $resumo->vlr_debito;

        $qtdCredito  = (int)   $resumo->qtd_credito;
        $vlrCredito  = (float) $resumo->vlr_credito;

        $totalTrans = DB::table('financeiro_transferencias_itens')
        ->where('tranitm_cod_trans', $idTransf)
        ->where('tranitm_situacao', 'T')
        ->sum('tranitm_valor');

        return view('/financeiro/transferencia/fin_pnl010_PainelTransferenciaCaixa',[
            'origemCX' => $cxOrigem,
            'destinoCXTE' => $cxteDestino,
            'dadosOrigem' => $dadosOrigem,
            'dadosDestino' => $dadosDestino,
            'vlrDinheiro' => $vlrDinheiro,
            'qtdCheque' => $qtdCheque,
            'vlrCheque' => $vlrCheque,
            'qtdDebito' => $qtdDebito,
            'vlrDebito' => $vlrDebito,
            'qtdCredito' => $qtdCredito,
            'vlrCredito' => $vlrCredito,
            'totalTrans' => $totalTrans,
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de execução do update da atualização do valor em dinheiro da transferência de caixa
    |----------------------------------------------------------------------------------------------------
    */
    public function transfUpDinheiro(Request $request)
    {    
        $cxteDestino = Session::get('glo_transf_cxte_destino');
        $cxOrigem = Session::get('glo_transf_cx_origem');
        $idTransf = Session::get('glo_transf_id');

        DB::table('financeiro_transferencias_itens')
        ->where('tranitm_cod_trans', $idTransf)
        ->where('tranitm_tipo', '0')
        ->where('tranitm_situacao', 'T')
        ->update([
            'tranitm_valor' => Helper::limpaValorMonetario($request->valorDinheiro),
        ]);

        return redirect()->route('transferencia.painelTransfCaixa')->with('success', 'Valor da Transferência atualizado com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de execução do update da atualização das contas que serão transferidas na transferência de caixa
    |----------------------------------------------------------------------------------------------------
    */
    public function transfUpConta(Request $request, $origem)
    {    
        $cxteDestino = Session::get('glo_transf_cxte_destino');
        $cxOrigem = Session::get('glo_transf_cx_origem');
        $idTransf = Session::get('glo_transf_id');

        $opcao = $request->opcao;

        // Validação adicional no servidor
        $contasSel = $request->selected_contas;

        if (empty($contasSel)) {
            return redirect()->back()->with('error', 'Nenhuma conta foi selecionada.');
        }

        $cctSel = explode(',', $contasSel);

        foreach ($cctSel as $conta) {

            DB::table('financeiro_transferencias_itens')
            ->where('tranitm_cod_trans', $idTransf)
            ->where('tranitm_sequencia', $conta)
            ->where('tranitm_tipo', $origem)
            ->update([
                'tranitm_situacao' => $opcao,
            ]);
        }

        return redirect()->route('transferencia.consultaItensTransf',['tipo' => $origem])->with('success', 'Status da conta atualizado com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de execução do painel de Transferência de Caixa
    |----------------------------------------------------------------------------------------------------
    */
    public function consultaItensTransf($tipo)
    {    
        $cxteDestino = Session::get('glo_transf_cxte_destino');
        $cxOrigem = Session::get('glo_transf_cx_origem');
        $idTransf = Session::get('glo_transf_id');

        $itens = DB::table('financeiro_transferencias_itens')
        ->where('tranitm_cod_trans', $idTransf)
        ->where('tranitm_tipo', $tipo)
        ->get();

        return view('/financeiro/transferencia/fin_cns010_ItensTransferencia',[
            'origemCX' => $cxOrigem,
            'destinoCXTE' => $cxteDestino,
            'itens' => $itens,
            'origemConsulta' => $tipo,
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de execução do painel de Transferência de Caixa
    |----------------------------------------------------------------------------------------------------
    */
    public function finalizarTransf(Request $request)
    {    
        $idTransf = Session::get('glo_transf_id');

        //Inicia o Database Transaction
        DB::beginTransaction();

        if(!empty($request->observacao)){
            DB::table('financeiro_transferencias')
            ->where('transf_id', $idTransf)
            ->update([
                'transf_observacao' => $request->observacao
            ]);
        }

        $app = "fin_pnl010_PainelTransferenciaCaixa";
        
        $exec_fn = DB::select("select ret_sts_trans, ret_msg_trans from fn_financeiro_transferencias(".$idTransf.",'".$app."');");

        if($exec_fn[0]->ret_sts_trans == '*'){
            //Falha, desfaz as alterações no banco de dados
            DB::rollBack();

            return redirect()
            ->route('transferencia.painelTransfCaixa')
            ->withInput()
            ->with('error', $exec_fn[0]->ret_msg_trans);

        }else{
            
            //Grava as alterações do banco
            DB::commit();

            return redirect()->route('home.homeTransfCaixa')->with('success2', 'Transferência '.$idTransf.' realizada com sucesso!');
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de execução do formulário inicial da Consulta de Transferências realizadas
    |----------------------------------------------------------------------------------------------------
    */
    public function homeConsultaTransf()
    {    
        return view('/financeiro/transferencia/fin_cnt011_HomeConsultaTransferencias');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de execução da lista de Transferências realizadas
    |----------------------------------------------------------------------------------------------------
    */
    public function consultaTransfRealizadas(Request $request)
    {
        $query = DB::table('financeiro_transferencias')
        ->when($request->filled('transf'), function ($q) use ($request) {
            if ($request->transf == "T") {
                $q->where('transf_tipo', 1);
            } else {
                $q->where('transf_tipo', 2);
            }
        })
        ->when($request->razOrigem, fn($q) => $q->where('transf_codigo_ori', $request->razOrigem))
        ->when($request->razDestino, fn($q) => $q->where('transf_codigo_dest', $request->razDestino))
        ->when($request->dtIni && $request->dtFin, fn($q) => 
            $q->whereBetween('transf_data', [Helper::limpaData($request->dtIni), Helper::limpaData($request->dtFin)])
        )
        ->when($request->dtIni && !$request->dtFin, fn($q) =>
            $q->where('transf_data', '>=', Helper::limpaData($request->dtIni))
        )
        ->when(!$request->dtIni && $request->dtFin, fn($q) =>
            $q->where('transf_data', '<=', Helper::limpaData($request->dtFin))
        )
        ->where('transf_situacao', 'F');

        $dadosTransf = $query->get();

        return view('/financeiro/transferencia/fin_cns011_RelatorioTransferencias',[
            'dadosTransf' => $dadosTransf
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método de execução do resumo da transferência realizada
    |----------------------------------------------------------------------------------------------------
    */
    public function resumoTransfRealizada($idTransf)
    { 
        $dadosTransf = DB::table('financeiro_transferencias_itens as fti')
        ->leftJoin('financeiro_contas_correntes as fcc', function ($join) {
            $join->on('fcc.conta_num_conta', '=', 'fti.tranitm_cc_cod')
                ->on('fcc.conta_responsavel', '=', 'fti.tranitm_cc_res')
                ->on('fcc.conta_tipo', '=', 'fti.tranitm_cc_tipo');
        })
        ->select(
            'fti.tranitm_cod_trans',
            'fti.tranitm_sequencia',
            'fti.tranitm_situacao',
            'fti.tranitm_tipo',
            'fti.tranitm_cc_tipo',
            'fti.tranitm_cc_cod',
            'fti.tranitm_cc_res',
            'fti.tranitm_valor',
            'fcc.conta_dt_emissao',
            'fcc.conta_dt_vencimento',
            'fcc.conta_emitente',
            'fcc.conta_comprovante',
            'fcc.conta_ag_cheque',
            'fcc.conta_ag_dv_cheque',
            'fcc.conta_cc_cheque',
            'fcc.conta_cc_dv_cheque'
        )
        ->where('fti.tranitm_cod_trans', $idTransf)
        ->where('fti.tranitm_situacao', 'T')
        ->get();

        return view('/financeiro/transferencia/fin_cns011_ResumoTransferencia',[
            'dadosTransf' => $dadosTransf
        ]);
    }
}
