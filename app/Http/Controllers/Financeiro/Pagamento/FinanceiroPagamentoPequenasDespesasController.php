<?php

namespace App\Http\Controllers\Financeiro\Pagamento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Helpers\Helper;
use App\Models\Financeiro\Pagamento\FinanceiroPagamentoPequenasDespesas;

class FinanceiroPagamentoPequenasDespesasController extends Controller
{
    protected $pagPeqDesp;
    
    public function __construct(FinanceiroPagamentoPequenasDespesas $pagPeqDesp)
    {
        $this->pagPeqDesp = $pagPeqDesp;
    }

    //Insere a despesa
    static function insert($empresa, $idPagamento, $cliente, $request){

        $data = Helper::limpaData($request->dataPag);
        $valor = Helper::limpaValorMonetario($request->valor);

        $dados = [
            'pagpqd_emp' => $empresa,
            'pagpqd_cod_pag' => $idPagamento,
            'pagpqd_tip_opr' => $request->operacao,
            'pagpqd_cod_des' => $request->despesa,
            'pagpqd_cli' => $cliente,
            'pagpqd_num_com' => $request->numComprovante,
            'pagpqd_cmp' => $request->complemento,
            'pagpqd_dtp' => $data,
            'pagpqd_vlr' => $valor,
            'pagpqd_bco' => $request->banco
        ];
        
        // Cria o registro
        FinanceiroPagamentoPequenasDespesas::create($dados);
        
        return;
    }
}
