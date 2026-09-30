<?php

namespace App\Http\Controllers\Financeiro\Recebimento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use stdClass;
use App\Http\Helpers\Helper;
use App\Models\Financeiro\Recebimento\FinanceiroRecebimentoHeader;

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

        $dadosUsuFin = DB::table('financeiro_tab_usuarios')->where('tabusu_usuario', $usuario)->where('tabusu_empresa', $empresa)->first();

        $nextval = DB::select("SELECT nextval('sq_financeiro_recebimento_num')")[0]->nextval;

        $dados = [
            'rechdr_emp' => $empresa,
            'rechdr_cod_rec' => $nextval,
            'rechdr_sts' => 'A',
            'rechdr_ori' => $origem,
            'rechdr_dti' => $data,
            'rechdr_usu' => $usuario,
            'rechdr_tip_raz' => $dadosUsuFin->tabusu_tipo_razao,
            'rechdr_raz' => $dadosUsuFin->tabusu_razao,
        ];
        
        // Cria o registro e obtém a instância do modelo
        $header = FinanceiroRecebimentoHeader::create($dados);
        
        // Retorna o ID do registro criado
        return $header->rechdr_cod_rec;
    }
}
