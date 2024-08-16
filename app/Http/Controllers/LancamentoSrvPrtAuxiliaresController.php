<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LancamentoSrvPrtAuxiliares;
use stdClass;
use App\Http\Helpers\Helper;

class LancamentoSrvPrtAuxiliaresController extends Controller
{
    protected $auxTarefa;
    
    public function __construct(LancamentoSrvPrtAuxiliares $auxTarefa)
    {
        $this->auxTarefa = $auxTarefa;
    }

    //Insere a o auxiliar
    static function insert($empresa,$numOS,$requisicao,$servico,$prestador){

        $dadosSrv = DB::table('lancamento_srv_prt_auxiliares')->where('prtaux_emp', $empresa)->where('prtaux_nos', $numOS)->where('prtaux_req', $requisicao)->where('prtaux_srv', $servico)->max('prtaux_seq');

        $sequencia = $dadosSrv +1;

        $data = date('Y-m-d');
        $hora = date('Hi');

        $dados = [
            'prtaux_emp' => $empresa,
            'prtaux_nos' => $numOS,
            'prtaux_req' => $requisicao,
            'prtaux_srv' => $servico,
            'prtaux_seq' => $sequencia,
            'prtaux_prt' => $prestador,
            'prtaux_dt_inc' => $data,
            'prtaux_hr_inc' => $hora
        ];
        
        LancamentoSrvPrtAuxiliares::create($dados);
        
        return;
    }
}
