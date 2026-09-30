<?php

namespace App\Http\Controllers\Financeiro\Pagamento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Helpers\Helper;
use App\Models\Financeiro\Pagamento\FinanceiroPagamentoContasCorrentes;

class FinanceiroPagamentoContasCorrentesController extends Controller
{
    protected $pagConCorr;
    
    public function __construct(FinanceiroPagamentoContasCorrentes $pagConCorr)
    {
        $this->pagConCorr = $pagConCorr;
    }

    //Insere a conta corrente
    static function insert($empresa, $idPagamento, $cliente, $tipoOPR, $dadosInput){

        // Permite tanto Request quanto array
        $get = is_array($dadosInput) ? $dadosInput : $dadosInput->all();

        if ($tipoOPR == 'I') {

            $tipoCCT = $get['tipoConta'];
            $numCCT = $get['numConta'];
            $dtPag = Helper::limpaData($get['dataPag']);
            $valorPag = !empty($get['valorPag']) ? Helper::limpaValorMonetario($get['valorPag']) : 0;
            $valorAcre = !empty($get['valorAcre']) ? Helper::limpaValorMonetario($get['valorAcre']) : 0;
            $valorDesc = !empty($get['valorDes']) ? Helper::limpaValorMonetario($get['valorDes']) : 0;
            $valortotal = !empty($get['valorTotpag']) ? Helper::limpaValorMonetario($get['valorTotpag']) : 0;
            $valorMulta = 0;
            $valorVariacao = 0;

        } elseif ($tipoOPR == 'L' || $tipoOPR == 'A') {

            $tipoCCT = $get['tipoConta'];
            $numCCT = $get['numConta'];
            $dtPag = $get['dataPag'];
            $valorPag = !empty($get['valorPag']) ? $get['valorPag'] : 0;
            $valorAcre = 0;
            $valorDesc = 0;
            $valortotal = $valorPag;
            $valorMulta = 0;
            $valorVariacao = 0;
        }

        //Busca os dados da conta selecionada
        $dadosContaPag = DB::table('financeiro_contas_correntes')
        ->where('conta_tipo', $tipoCCT)
        ->where('conta_empresa', $empresa)
        ->where('conta_responsavel', $cliente)
        ->where('conta_num_conta', $numCCT)
        ->first();

        $dados = [
            'pagcct_emp' => $empresa,
            'pagcct_cod_pag' => $idPagamento,
            'pagcct_cli' => $cliente,
            'pagcct_tip_cct' => $tipoCCT,
            'pagcct_num_cct' => $numCCT,
            'pagcct_tip_opr' => $tipoOPR,
            'pagcct_dte' => $dadosContaPag->conta_dt_emissao,
            'pagcct_dtp' => $dtPag,
            'pagcct_dtv' => $dadosContaPag->conta_dt_vencimento,
            'pagcct_vlr_cct' => $dadosContaPag->conta_valor,
            'pagcct_vlr_acr' => $valorAcre,
            'pagcct_vlr_des' => $valorDesc,
            'pagcct_vlr_mul' => $valorMulta,
            'pagcct_vlr_pag' => $valorPag,
            'pagcct_vlr_vrm' => $valorVariacao,
            'pagcct_vlr_tot' => $valortotal,
            'pagcct_cmp' => $dadosContaPag->conta_complemento,    
            'pagcct_obs' => $dadosContaPag->conta_obs
        ];
        
        // Cria o registro
        FinanceiroPagamentoContasCorrentes::create($dados);
        
        return;
    }

    //Atualiza a conta corrente
    static function update($empresa, $idPagamento, $cliente, $request)
    {
        // Normaliza os valores — se não existirem, assume 0
        $valorAcres = isset($request->valorAcres) ? Helper::limpaValorMonetario($request->valorAcres) : 0;
        $valorDesc = isset($request->valorDesc)  ? Helper::limpaValorMonetario($request->valorDesc)  : 0;
        $valorPag = isset($request->valorPagamento) ? Helper::limpaValorMonetario($request->valorPagamento) : 0;
        $valorMulta = isset($request->valorMulta) ? Helper::limpaValorMonetario($request->valorMulta) : 0;
        $variacaoMonet = isset($request->variacaoMonet) ? Helper::limpaValorMonetario($request->variacaoMonet) : 0;
        $valorTot = isset($request->valorTotal) ? Helper::limpaValorMonetario($request->valorTotal) : 0;

        // Atualiza a tabela
        DB::table('financeiro_pagamento_contas_correntes')
        ->where('pagcct_emp', $empresa)
        ->where('pagcct_cod_pag', $idPagamento)
        ->where('pagcct_cli', $cliente)
        ->where('pagcct_tip_cct', $request->tipoCC)
        ->where('pagcct_num_cct', $request->numCC)
        ->update([
            'pagcct_vlr_acr' => $valorAcres,
            'pagcct_vlr_des' => $valorDesc,
            'pagcct_vlr_pag' => $valorPag,
            'pagcct_vlr_mul' => $valorMulta,
            'pagcct_vlr_vrm' => $variacaoMonet,
            'pagcct_vlr_tot' => $valorTot,
        ]);

        return;
    }

    //Exclui a conta corrente
    static function delete($empresa, $idPagamento, $cliente, $numCC, $tipoCC)
    {
        // Atualiza a tabela
        DB::table('financeiro_pagamento_contas_correntes')
        ->where('pagcct_emp', $empresa)
        ->where('pagcct_cod_pag', $idPagamento)
        ->where('pagcct_cli', $cliente)
        ->where('pagcct_tip_cct', $tipoCC)
        ->where('pagcct_num_cct', $numCC)
        ->delete();

        return;
    }
}
