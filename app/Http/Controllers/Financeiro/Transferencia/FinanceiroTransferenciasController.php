<?php

namespace App\Http\Controllers\Financeiro\Transferencia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use stdClass;
use App\Http\Helpers\Helper;
use App\Models\Financeiro\Transferencia\FinanceiroTransferencias;

class FinanceiroTransferenciasController extends Controller
{
    protected $headerTransferencia;
    
    public function __construct(FinanceiroTransferencias $headerTransferencia)
    {
        $this->headerTransferencia = $headerTransferencia;
    }

    public static function insert($tipOri, $codOri, $tipDest, $codDest, $tipoTransf){

        $dados = [
            'transf_situacao' => 'M',
            'transf_usuario' => Auth::user()->usuario_codigo,
            'transf_tipo' => $tipoTransf,
            'transf_tipo_ori' => $tipOri,
            'transf_codigo_ori' => $codOri,
            'transf_tipo_dest' => $tipDest,
            'transf_codigo_dest' => $codDest,
            'transf_data' => date('Y-m-d')
        ];
        
        // Cria o registro e obtém a instância do modelo
        $transf = FinanceiroTransferencias::create($dados);
        
        // Retorna o ID do registro criado
        return $transf->transf_id;
    }
}
