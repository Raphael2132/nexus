<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LancamentoSrvOsServico;
use stdClass;

class LancamentoSrvOsServicoController extends Controller
{
    protected $servicoOS;
    
    public function __construct(LancamentoSrvOsServico $servicoOS)
    {
        $this->servicoOS = $servicoOS;
    }

    //Metodo de controle de recarregamento da app de abertura de OS de acordo com a função executada
    public function inserir(Request $request, $empresa, $numOS, $requisicao, $codTMO, $estagioAPP)
    {        
        $data_inc = date('Y-m-d');
        $hora_inc = date('Hi');

        $cnt_sequencia = $this->servicoOS->where('srv_nos', $numOS)->where('srv_emp', $empresa)->where('srv_req', $requisicao)->max('srv_seq');
        $sequencia = $cnt_sequencia +1;

        $requisicaoSel = session('glo_os_dadosRequisicoes');

        $dados = [
            'srv_emp' => $empresa,
            'srv_nos' => $numOS,
            'srv_req' => $requisicao,
            'srv_seq' => $sequencia,
            'srv_prt' => $request->prestadorTMO,
            'srv_set' => $requisicaoSel[0]->req_set,
            'srv_are' => $requisicaoSel[0]->req_are,
            'srv_tmo' => $codTMO,
            'srv_dsc' => $request->descricaoTMO,
            'srv_cmp' => $request->complementoTMO,
            'srv_ths' => $request->tipoTMO,
            'srv_qhr' => $request->qtdHrTMO,
            'srv_vhr' => $request->valUniHrTMO,
            'srv_vts' => $request->valTotHrTMO,
            'srv_dti' => $data_inc,
            'srv_hri' => $hora_inc,
            'srv_for' => $request->forTerceiroTMO,
            'srv_nft' => $request->numNfTerceiroTMO,
            'srv_srt' => $request->serNfTerceiroTMO,
            'srv_dtt' => $request->dataNfTerceiroTMO,
            'srv_tcg' => $request->tipCustoTMO,
            'srv_pcg' => $request->valCustoTMO,
            'srv_vcg' => $request->perCustoTMO
        ];
        
        LancamentoSrvOsServico::create($dados);

        return redirect(route('painelOS.consultaRequisicao', ['empresa' => $empresa, 'nos' => $numOS, 'estagioAPP' => 'CONSULTA_REQUISICAO', 'requisicao' => $requisicao]))->with('success', 'TMO adicionada com sucesso!');
    }

    //Metodo de atualização dos dados do serviço da requisição
    public function update(Request $request, $empresa, $numOS, $requisicao, $sequencia, $codTMO, $estagioAPP)
    {        
        $atualiaServico = DB::table('lancamento_srv_os_servicos')
            ->where('srv_emp', $empresa)
            ->where('srv_nos', $numOS)
            ->where('srv_req', $requisicao)
            ->where('srv_seq', $sequencia)
            ->where('srv_tmo', $codTMO)
            ->update(['srv_prt' => $request->prestadorTMO,
                'srv_cmp' => $request->complementoTMO,
                'srv_qhr' => $request->qtdHrTMO,
                'srv_vhr' => $request->valUniHrTMO,
                'srv_vts' => $request->valTotHrTMO,
                'srv_for' => $request->forTerceiroTMO,
                'srv_nft' => $request->numNfTerceiroTMO,
                'srv_srt' => $request->serNfTerceiroTMO,
                'srv_dtt' => $request->dataNfTerceiroTMO,
                'srv_tcg' => $request->tipCustoTMO,
                'srv_pcg' => $request->valCustoTMO,
                'srv_vcg' => $request->perCustoTMO]);   
        
        return redirect(route('painelOS.consultaRequisicao', ['empresa' => $empresa, 'nos' => $numOS, 'estagioAPP' => 'CONSULTA_REQUISICAO', 'requisicao' => $requisicao]))->with('success', 'TMO atualizada com sucesso!');
    }
}
