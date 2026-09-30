<?php

namespace App\Http\Controllers\Financeiro\Recebimento;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Financeiro\Recebimento\FinanceiroRecebimentoOutros;

class FinanceiroRecebimentoOutrosController extends Controller
{
    protected $outRecebimento;
    
    public function __construct(FinanceiroRecebimentoOutros $outRecebimento)
    {
        $this->outRecebimento = $outRecebimento;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Insere os dados do recebimento "Outros" na tabela
    |----------------------------------------------------------------------------------------------------
    */
    public static function insert($empresa, $valor, $tipoCred){

        $idRecebimento = Session::get('glo_recebimento_id');
        $data = date('Y-m-d');

        $dados = [
            'recout_emp' => $empresa,
            'recout_cod_rec' => $idRecebimento,
            'recout_tip_cre' => $tipoCred,
            'recout_vlr' => $valor,
            'recout_dtp' => $data,
        ];
        
        // Cria o registro e obtém a instância do modelo
        FinanceiroRecebimentoOutros::create($dados);
        
        return;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Atualiza os dados do recebimento "Outros" na tabela
    |----------------------------------------------------------------------------------------------------
    */
    public static function update($empresa, $cliente, $dataComp, $numDoc, $complemento){

        $idRecebimento = Session::get('glo_recebimento_id');

        $dados = [
            'recout_cli' => $cliente,
            'recout_num_com' => $numDoc,
            'recout_cmp' => $complemento,
            'recout_dtc' => $dataComp,
        ];
        
        // Cria o registro e obtém a instância do modelo
        FinanceiroRecebimentoOutros::where('recout_emp', $empresa)->where('recout_cod_rec', $idRecebimento)->update($dados);
        
        return;
    }
}
