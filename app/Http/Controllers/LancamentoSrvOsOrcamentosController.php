<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use App\Http\Helpers\Helper;

class LancamentoSrvOsOrcamentosController extends Controller
{
    //Chama a consulta dos orçamentos emitidas
    public function consultaOrcamentoOS(Request $request)
    {        
        $dadosOrc = DB::table('lancamento_srv_os_orcamentos')->get();

        return view('/lancamentos/servico/consultaOrcamentoOS',['dadosOrc' => $dadosOrc]);
    }
}
