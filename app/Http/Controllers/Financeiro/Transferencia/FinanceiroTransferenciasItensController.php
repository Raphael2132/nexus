<?php

namespace App\Http\Controllers\Financeiro\Transferencia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use App\Http\Helpers\Helper;
use App\Models\Financeiro\Transferencia\FinanceiroTransferenciasItens;

class FinanceiroTransferenciasItensController extends Controller
{
    protected $itensTransferencia;
    
    public function __construct(FinanceiroTransferenciasItens $itensTransferencia)
    {
        $this->itensTransferencia = $itensTransferencia;
    }

    public static function insert($tipOri, $codOri, $idTransf)
    {
        $ano = date('Y');

        $sql = "SELECT 
                    ".$idTransf.",
                    row_number() OVER (ORDER BY itm_tipo DESC, itm_tcc) AS seq,
                    'T',
                    itm_tipo,
                    itm_tcc, 
                    itm_re1, 
                    itm_ncc, 
                    itm_vlr
                FROM (                                     
                    SELECT  
                        CASE 
                            WHEN conta_tipo = 'CH' THEN 1
                            WHEN razao_tip_card = 'D' THEN 2
                            WHEN razao_tip_card = 'C' THEN 3
                            ELSE 0
                        END AS itm_tipo,
                        conta_tipo AS itm_tcc, 
                        conta_responsavel AS itm_re1, 
                        conta_num_conta AS itm_ncc, 
                        conta_valor - conta_val_rec AS itm_vlr 
                    FROM financeiro_contas_correntes 
                    LEFT JOIN financeiro_razoes 
                        ON razao_tipo = conta_tipo 
                        AND razao_codigo = conta_responsavel 
                        AND razao_ano = EXTRACT(YEAR FROM conta_dt_emissao) 
                    WHERE 
                        conta_tipo IN ('CH','CC') 
                        AND conta_res_trans = '".$codOri."' 
                        AND conta_situacao IN ('A', 'P', 'D', 'R', 'I')
                        AND NOT EXISTS (
                            SELECT 1 
                            FROM financeiro_transferencias_itens itm
                            JOIN financeiro_transferencias tr 
                                ON tr.transf_id = itm.tranitm_cod_trans 
                            WHERE 
                                tr.transf_situacao = 'M' 
                                AND itm.tranitm_cc_tipo = conta_tipo 
                                AND itm.tranitm_cc_res = conta_responsavel 
                                AND itm.tranitm_cc_cod = conta_num_conta
                        )
                    UNION ALL
                    SELECT 
                        0 AS itm_tipo, 
                        '' AS itm_tcc, 
                        '' AS itm_re1, 
                        '' AS itm_ncc, 
                        razao_saldo_din AS itm_vlr 
                    FROM financeiro_razoes 
                    WHERE 
                        razao_tipo = '".$tipOri."' 
                        AND razao_codigo = '".$codOri."'
                        AND razao_ano = ".$ano."
                )
        ";

        DB::transaction(function () use ($sql) {
            DB::statement("
                INSERT INTO financeiro_transferencias_itens (
                    tranitm_cod_trans,
                    tranitm_sequencia,
                    tranitm_situacao,
                    tranitm_tipo,
                    tranitm_cc_tipo,
                    tranitm_cc_res,
                    tranitm_cc_cod,
                    tranitm_valor
                )
                $sql
            ");
        });

        return;
    }
}
