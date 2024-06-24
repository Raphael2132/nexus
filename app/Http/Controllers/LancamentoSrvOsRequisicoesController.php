<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LancamentoSrvOsRequisicoes;
use stdClass;
use App\Http\Controllers\PainelAberturaOSController;
use App\Http\Helpers\Helper;

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
            'req_vlt' => 0,
            'req_per_des' => 0,
            'req_val_des' => 0

        ];
        
        $novaReq = LancamentoSrvOsRequisicoes::create($dados);

        return redirect(route('painelOS.consultaRequisicao', ['empresa' => $empresa, 'nos' => $numOS, 'estagioAPP' => 'CONSULTA_REQUISICAO', 'requisicao' => $sequencia]))->with('success', 'Requisição aberta com sucesso!');
    }

    //Metodo de finalização da requisição
    public function finalizarRequisicao($empresa, $numOS, $requisicao)
    {        
        $cnt_srv_aberto = DB::table('lancamento_srv_os_servicos')->where('srv_emp',$empresa)->where('srv_nos',$numOS)->where('srv_req',$requisicao)->wherein('srv_sts',['A','E'])->count();
        if($cnt_srv_aberto > 0){
            return redirect()->back()->with('info', 'A requisição contém serviço(s) em aberto e ela não pode ser finalizada!');
        }

        $cnt_srv_susp = DB::table('lancamento_srv_os_servicos')->where('srv_emp',$empresa)->where('srv_nos',$numOS)->where('srv_req',$requisicao)->where('srv_sts','S')->count();
        if($cnt_srv_susp > 0){
            return redirect()->back()->with('info', 'A requisição contém serviço(s) suspenso(s), primeiro finalize ou cancele o serviço para continuar!');
        }

        $dataHora = date('Y-m-d H:i:s');

        $atualiaServico = DB::table('lancamento_srv_os_requisicoes')
            ->where('req_emp', $empresa)
            ->where('req_nos', $numOS)
            ->where('req_seq', $requisicao)
            ->update(['req_sts' => 'F',
                'req_dhf' => $dataHora]);   
        
        return redirect(route('painelOS.consultaRequisicao', ['empresa' => $empresa, 'nos' => $numOS, 'estagioAPP' => 'CONSULTA_REQUISICAO', 'requisicao' => $requisicao]))->with('success', 'Requisição finalizada com sucesso!');
    }

    //Metodo de reabertura da requisição
    public function reabrirRequisicao($empresa, $numOS, $requisicao)
    {
        $dataHora = date('Y-m-d H:i:s');

        $atualiaServico = DB::table('lancamento_srv_os_requisicoes')
            ->where('req_emp', $empresa)
            ->where('req_nos', $numOS)
            ->where('req_seq', $requisicao)
            ->update(['req_sts' => 'A',
                'req_dhf' => null]);   
        
        return redirect(route('painelOS.consultaRequisicao', ['empresa' => $empresa, 'nos' => $numOS, 'estagioAPP' => 'CONSULTA_REQUISICAO', 'requisicao' => $requisicao]))->with('success', 'Requisição reaberta com sucesso!');
    }

    //Metodo de excluir requisição
    public function destroy(LancamentoSrvOsRequisicoes $requisicaoOS, $empresa, $cliente, $numOS){

        //$desconto_req = DB::table('lancamento_srv_os_requisicoes')->select('req_val_des')->where('req_emp',$empresa)->where('req_nos',$numOS)->where('req_seq',$sequencia)->get();

        //$desconto_os = DB::table('lancamento_srv_os')->select('os_val_des')->where('os_emp',$empresa)->where('os_nos',$numOS)->get();

        //$desconto = $desconto_os - $desconto_req;

        $requisicaoOS->delete();
        
        PainelAberturaOSController::atualizaValorOS($empresa, $numOS);

        PainelAberturaOSController::atualizaPrevEntrega($empresa, $numOS);
        
        return redirect(route('situacaoOS.carregaOS', ['empresa' => $empresa, 'cliente' => $cliente, 'nos' => $numOS, 'estagioAPP' => 'PRINCIPAL']))->with('success', 'Requisição excluída com sucesso!');
    }
}
