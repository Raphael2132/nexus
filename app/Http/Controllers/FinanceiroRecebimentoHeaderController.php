<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use stdClass;
use App\Http\Helpers\Helper;
use App\Models\FinanceiroRecebimentoHeader;

class FinanceiroRecebimentoHeaderController extends Controller
{
    protected $headerRecebimento;
    
    public function __construct(FinanceiroRecebimentoHeader $headerRecebimento)
    {
        $this->headerRecebimento = $headerRecebimento;
    }

    //Insere o Header do recebimento
    static function insert($empresa, $origem){

        $usuario = Auth::user()->usuario_codigo;
        $data = date('Y-m-d');

        $nextval = DB::select("SELECT nextval('sq_financeiro_recebimento_num')")[0]->nextval;

        $dados = [
            'rechdr_emp' => $empresa,
            'rechdr_cod_rec' => $nextval,
            'rechdr_sts' => 'A',
            'rechdr_ori' => $origem,
            'rechdr_dti' => $data,
            'rechdr_usu' => $usuario,
        ];
        
        // Cria o registro e obtém a instância do modelo
        $header = FinanceiroRecebimentoHeader::create($dados);
        
        // Retorna o ID do registro criado
        return $header->rechdr_cod_rec;
    }
}
