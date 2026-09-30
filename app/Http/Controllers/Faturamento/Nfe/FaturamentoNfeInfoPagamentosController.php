<?php

namespace App\Http\Controllers\Faturamento\Nfe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use App\Http\Helpers\Helper;
use App\Models\Faturamento\Nfe\FaturamentoNfeInfoPagamentos;

class FaturamentoNfeInfoPagamentosController extends Controller
{
    protected $infoPagamento;
    
    public function __construct(FaturamentoNfeInfoPagamentos $infoPagamento)
    {
        $this->infoPagamento = $infoPagamento;
    }

    //Insere a Informação de Pagamento
    static function insert($empresa, $numNF, $campos){

        $dados = [
            'nfepag_emi' => $empresa,
            'nfepag_num' => $numNF,
            'nfepag_seq' => $campos['nfepag_seq'],
            'nfepag_tip_pgt' => $campos['nfepag_tip_pgt'],
            'nfepag_val_pgt' => $campos['nfepag_val_pgt'],
            'nfepag_tip_int' => $campos['nfepag_tip_int'],
            'nfepag_cnpj' => $campos['nfepag_cnpj'],
            'nfepag_tip_band' => $campos['nfepag_tip_band'],
            'nfepag_cod_aut' => $campos['nfepag_cod_aut'],
            'nfepag_val_trc' => $campos['nfepag_val_trc']
        ];
        
        // Cria o registro
        FaturamentoNfeInfoPagamentos::create($dados);
        
        return;
    }
}
