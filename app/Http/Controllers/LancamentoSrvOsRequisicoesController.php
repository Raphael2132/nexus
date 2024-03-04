<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LancamentoSrvOsRequisicoes;
use stdClass;

class LancamentoSrvOsRequisicoesController extends Controller
{
    protected $requisicaoOS;
    
    public function __construct(LancamentoSrvOsRequisicoes $requisicaoOS)
    {
        $this->requisicaoOS = $requisicaoOS;
    }

    //Insere a nova requisição na OS
    public function inserir(Request $request, $empresa, $numOS, $glo_eat_cod)
    {
        $cnt_sequencia = $this->requisicaoOS->where('req_nos', $numOS)->where('req_emp', $empresa)->max('req_seq');
        $sequencia = $cnt_sequencia +1;

        $data_inc = date('Y-m-d H:i:s');

        $dados = [
            'req_emp' => $empresa,
            'req_nos' => $numOS,
            'req_seq' => $sequencia,
            'req_dsc' => $request->descricaoReq,
            'req_tos' => $request->tipoServicoReq,
            'req_cat' => $request->categoriaReq,
            'req_set' => $request->setorReq,
            'req_are' => $request->areaReq,
            'req_eat' => $glo_eat_cod,
            'req_dhi' => $data_inc,
            'req_dhc' => null,
            'req_dt_apr' => null,
            'req_res_apr' => null,
            'req_vlr' => 0,
            'req_vls' => 0,
            'req_vlp' => 0,
            'req_per_des' => 0,
            'req_val_des' => 0

        ];
        
        $novaReq = LancamentoSrvOsRequisicoes::create($dados);

        return redirect(route('painelOS.consultaRequisicao', ['empresa' => $empresa, 'nos' => $numOS, 'estagioAPP' => 'CONSULTA_REQUISICAO', 'requisicao' => $sequencia]))->with('success', 'Requisição aberta com sucesso!');
    }
}
