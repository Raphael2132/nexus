<?php

namespace App\Http\Controllers\Faturamento\Nota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use App\Http\Helpers\Helper;
use App\Models\Faturamento\Nota\FaturamentoNfTipoNotas;

class FaturamentoNfTipoNotasController extends Controller
{
    protected $tipoNota;
    
    public function __construct(FaturamentoNfTipoNotas $tipoNota)
    {
        $this->tipoNota = $tipoNota;
    }

    //Insere tipo da Nata do Faturamento
    static function insert($empresa, $numNF, $tipo){

        $dados = [
            'nftnt_emp' => $empresa,
            'nftnt_num' => $numNF,
            'nftnt_tipo' => $tipo,
        ];
        
        // Cria o registro
        FaturamentoNfTipoNotas::create($dados);
        
        return;
    }
}
