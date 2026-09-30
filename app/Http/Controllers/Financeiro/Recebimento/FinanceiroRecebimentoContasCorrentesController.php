<?php

namespace App\Http\Controllers\Financeiro\Recebimento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use App\Http\Helpers\Helper;
use App\Models\Financeiro\Recebimento\FinanceiroRecebimentoContasCorrentes; 

class FinanceiroRecebimentoContasCorrentesController extends Controller
{
    protected $notaRecebimento;
    
    public function __construct(FinanceiroRecebimentoContasCorrentes $contaCorrente)
    {
        $this->contaCorrente = $contaCorrente;
    }

    //Insere a conta corrente no recebimento
    public static function insert($empresa, $cliente, $idRecebimento, $tipoInc, $dados){

        if($tipoInc == 'A' || $tipoInc == 'B'){
            
            $dadosCCT = DB::table('financeiro_contas_correntes')
            ->where('conta_empresa', $empresa)
            ->where('conta_tipo', $dados->tipoCCR)
            ->where('conta_responsavel', $cliente)
            ->where('conta_num_conta', $dados->numCCR)
            ->first();

            $valorRec = Helper::limpaValorMonetario($dados->valCCT);

            if(!empty($dados->valAcreDesc)){
                $valorAcre = Helper::limpaValorMonetario($dados->valAcreDesc);
            }else{
                $valorAcre = 0;
            }

            if(!empty($dados->valISS)){
                $valorISS = Helper::limpaValorMonetario($dados->valISS);
            }else{
                $valorISS = 0;
            }

            if(!empty($dados->valIRRF)){
                $valorIRRF = Helper::limpaValorMonetario($dados->valIRRF);
            }else{
                $valorIRRF = 0;
            }

            if(!empty($dados->valDepBan)){
                $valorDepBan = Helper::limpaValorMonetario($dados->valDepBan);
            }else{
                $valorDepBan = 0;
            }

            $data = date('Y-m-d');

            $valorCCT = $dadosCCT->conta_valor - $dadosCCT->conta_val_rec;

            $dados = [
                'reccct_emp' => $empresa,           
                'reccct_cod_rec' => $idRecebimento,       
                'reccct_tcc' => $dados->tipoCCR,           
                'reccct_res' => $cliente,           
                'reccct_ncc' => $dados->numCCR,           
                'reccct_emi' => $dadosCCT->conta_empresa,           
                'reccct_ori' => $dadosCCT->conta_origem,           
                'reccct_scc' => $dadosCCT->conta_subtipo,           
                'reccct_valor' => $valorCCT,         
                'reccct_valor_rec' => $valorRec,     
                'reccct_acrescimo' => $valorAcre,     
                'reccct_valor_iss' => $valorISS,     
                'reccct_valor_irrf' => $valorIRRF,    
                'reccct_desp_banc' => $valorDepBan,     
                'reccct_dt_emissao' => $dadosCCT->conta_dt_emissao,    
                'reccct_dt_recebimento' => $data,
                'reccct_dt_vencimento' => $dadosCCT->conta_dt_vencimento, 
                'reccct_observacao' => $dados->observacao,    
                'reccct_complemento' => $dadosCCT->conta_complemento,   
                'reccct_flag' => $tipoInc
            ];

        }else{
            
            if($dados->tipoCCR == 'AC'){

                $tabConta = DB::table('financeiro_tab_contas')
                ->where('tabcon_codigo', $dados->tipoCCR)
                ->first();

                $numCCR = $tabConta->tabcon_num_conta + 1;
                
                DB::table('financeiro_tab_contas')
                ->where('tabcon_codigo', $dados->tipoCCR)
                ->update(['tabcon_num_conta' => $numCCR]);

                $numCCR = str_pad($numCCR, 9, '0', STR_PAD_LEFT);
            }else{

                $numCCR = '000000000';
            }

            $valorRec = Helper::limpaValorMonetario($dados->valCCT);
            $dataEmi = Helper::limpaData($dados->dataEmiCCR);
            $dataVct = Helper::limpaData($dados->dataVctCCR);

            $data = date('Y-m-d');

            $dados = [
                'reccct_emp' => $empresa,           
                'reccct_cod_rec' => $idRecebimento,       
                'reccct_tcc' => $dados->tipoCCR,           
                'reccct_res' => $dados->resCCR,           
                'reccct_ncc' => $numCCR,           
                'reccct_emi' => $dados->empCCR,           
                'reccct_ori' => $dados->oriCCR,           
                'reccct_scc' => $dados->sccCCR,           
                'reccct_valor' => 0,         
                'reccct_valor_rec' => $valorRec,     
                'reccct_acrescimo' => 0,     
                'reccct_valor_iss' => 0,     
                'reccct_valor_irrf' => 0,    
                'reccct_desp_banc' => 0,     
                'reccct_dt_emissao' => $dataEmi,    
                'reccct_dt_recebimento' => $data,
                'reccct_dt_vencimento' => $dataVct, 
                'reccct_observacao' => $dados->observacao,    
                'reccct_complemento' => $dados->compCCR,   
                'reccct_flag' => 'N'
            ];
        }
        
        // Cria o registro
        FinanceiroRecebimentoContasCorrentes::create($dados);
        
        return;
    }

    //Exclui a conta do recebimento
    public static function delete($empresa, $cliente, $idRecebimento, $tipoCCR, $numCCR){

        // Deleta os registros encontrados
        FinanceiroRecebimentoContasCorrentes::where('reccct_emp', $empresa)
        ->where('reccct_cod_rec', $idRecebimento)
        ->where('reccct_tcc', $tipoCCR)
        ->where('reccct_res', $cliente)
        ->where('reccct_ncc', $numCCR)
        ->delete();

        return;
    }
}
