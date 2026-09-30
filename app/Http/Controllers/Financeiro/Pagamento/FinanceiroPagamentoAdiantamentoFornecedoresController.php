<?php

namespace App\Http\Controllers\Financeiro\Pagamento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Helpers\Helper;
use App\Models\Financeiro\Pagamento\FinanceiroPagamentoAdiantamentoFornecedores;

class FinanceiroPagamentoAdiantamentoFornecedoresController extends Controller
{
    protected $incAf;
    
    public function __construct(FinanceiroPagamentoAdiantamentoFornecedores $incAf)
    {
        $this->incAf = $incAf;
    }

    //Insere a despesa
    static function insert($empresa, $cliente, $idPagamento, $request){

        $dataEmi = Helper::limpaData($request->dataEmi);
        $dataVct = Helper::limpaData($request->dataVct);
        $valor = Helper::limpaValorMonetario($request->valorAF);

        //Incrementa +1 ao numero da conta AF
        DB::table('financeiro_tab_contas')
            ->where('tabcon_codigo', 'AF')
            ->increment('tabcon_num_conta');

        //Busca o valor atualizado
        $numAF = DB::table('financeiro_tab_contas')
            ->where('tabcon_codigo', 'AF')
            ->value('tabcon_num_conta');

        $dados = [
            'pagaf_emp' => $empresa,
            'pagaf_cod_pag' => $idPagamento,
            'pagaf_for' => $cliente,
            'pagaf_tip_cct' => 'AF',
            'pagaf_num_cct' => $numAF,
            'pagaf_dte' => $dataEmi,
            'pagaf_dtv' => $dataVct,
            'pagaf_vlr' => $valor,
            'pagaf_cmp' => $request->compAF,
            'pagaf_scc' => $request->subTipAF,
            'pagaf_obs' => $request->observacao,
        ];
        
        // Cria o registro
        FinanceiroPagamentoAdiantamentoFornecedores::create($dados);
        
        return $numAF;
    }
}
