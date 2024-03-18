<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LancamentoSrvOsServico;
use Illuminate\Support\Facades\Auth;
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

        if(!empty($request->forTerceiroTMO)){
            $forTerceiroTMO = substr($request->forTerceiroTMO, 0, 10);
        }else{
            $forTerceiroTMO = null;
        }

        if(!empty($request->dataNfTerceiroTMO)){
            $dataNfTerceiroTMO = substr($request->dataNfTerceiroTMO,-4).'-'.substr($request->dataNfTerceiroTMO,3,2).'-'.substr($request->dataNfTerceiroTMO,0,2);;
        }else{
            $dataNfTerceiroTMO = null;
        }

        if(!empty($request->qtdHrTMO)){
            $qtdHrTMO = str_replace(",",".",$request->qtdHrTMO);
        }else{
            $qtdHrTMO = '0.00';
        }
        
        if(!empty($request->valUniHrTMO)){
            $valUniHrTMO = str_replace(".","",$request->valUniHrTMO);
            $valUniHrTMO = str_replace(",",".",$valUniHrTMO);
        }else{
            $valUniHrTMO = '0.00';
        }

        if(!empty($request->valTotHrTMO)){
            $valTotHrTMO = str_replace(".","",$request->valTotHrTMO);
            $valTotHrTMO = str_replace(",",".",$valTotHrTMO);
        }else{
            $valTotHrTMO = '0.00';
        }

        if(!empty($request->valCustoTMO)){
            $valCustoTMO = str_replace(".","",$request->valCustoTMO);
            $valCustoTMO = str_replace(",",".",$valCustoTMO);
        }else{
            $valCustoTMO = '0.00';
        }

        if(!empty($request->perCustoTMO)){
            $perCustoTMO = str_replace(".","",$request->perCustoTMO);
            $perCustoTMO = str_replace(",",".",$perCustoTMO);
        }else{
            $perCustoTMO = '0.00';
        }

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
            'srv_qhr' => $qtdHrTMO,
            'srv_vhr' => $valUniHrTMO,
            'srv_vts' => $valTotHrTMO,
            'srv_dti' => $data_inc,
            'srv_hri' => $hora_inc,
            'srv_for' => $forTerceiroTMO,
            'srv_nft' => $request->numNfTerceiroTMO,
            'srv_srt' => $request->serNfTerceiroTMO,
            'srv_dtt' => $dataNfTerceiroTMO,
            'srv_tcg' => $request->tipCustoTMO,
            'srv_pcg' => $perCustoTMO,
            'srv_vcg' => $valCustoTMO
        ];
        
        LancamentoSrvOsServico::create($dados);

        $sum_servicos = DB::table('lancamento_srv_os_servicos')->where('srv_emp',$empresa)->where('srv_nos',$numOS)->where('srv_req',$requisicao)->wherein('srv_sts',['A','E','F'])->sum('srv_vts');

        $updValReq = DB::table('lancamento_srv_os_requisicoes')
            ->where('req_emp', $empresa)
            ->where('req_nos', $numOS)
            ->where('req_seq', $requisicao)
            ->update(['req_vlr' => $sum_servicos,
                'req_vls' => $sum_servicos]);  

        return redirect(route('painelOS.consultaRequisicao', ['empresa' => $empresa, 'nos' => $numOS, 'estagioAPP' => 'CONSULTA_REQUISICAO', 'requisicao' => $requisicao]))->with('success', 'TMO adicionada com sucesso!');
    }

    //Metodo de atualização dos dados do serviço da requisição
    public function update(Request $request, $empresa, $numOS, $requisicao, $sequencia, $codTMO, $estagioAPP)
    {        
        if(!empty($request->forTerceiroTMO)){
            $forTerceiroTMO = substr($request->forTerceiroTMO, 0, 10);
        }else{
            $forTerceiroTMO = '';
        }

        if(!empty($request->dataNfTerceiroTMO)){
            $dataNfTerceiroTMO = substr($request->dataNfTerceiroTMO,-4).'-'.substr($request->dataNfTerceiroTMO,3,2).'-'.substr($request->dataNfTerceiroTMO,0,2);;
        }else{
            $dataNfTerceiroTMO = null;
        }

        if(!empty($request->qtdHrTMO)){
            $qtdHrTMO = str_replace(",",".",$request->qtdHrTMO);
        }else{
            $qtdHrTMO = '0.00';
        }
        
        if(!empty($request->valUniHrTMO)){
            $valUniHrTMO = str_replace(".","",$request->valUniHrTMO);
            $valUniHrTMO = str_replace(",",".",$valUniHrTMO);
        }else{
            $valUniHrTMO = '0.00';
        }

        if(!empty($request->valTotHrTMO)){
            $valTotHrTMO = str_replace(".","",$request->valTotHrTMO);
            $valTotHrTMO = str_replace(",",".",$valTotHrTMO);
        }else{
            $valTotHrTMO = '0.00';
        }

        if(!empty($request->valCustoTMO)){
            $valCustoTMO = str_replace(".","",$request->valCustoTMO);
            $valCustoTMO = str_replace(",",".",$valCustoTMO);
        }else{
            $valCustoTMO = '0.00';
        }

        if(!empty($request->perCustoTMO)){
            $perCustoTMO = str_replace(".","",$request->perCustoTMO);
            $perCustoTMO = str_replace(",",".",$perCustoTMO);
        }else{
            $perCustoTMO = '0.00';
        }

        $atualiaServico = DB::table('lancamento_srv_os_servicos')
            ->where('srv_emp', $empresa)
            ->where('srv_nos', $numOS)
            ->where('srv_req', $requisicao)
            ->where('srv_seq', $sequencia)
            ->where('srv_tmo', $codTMO)
            ->update(['srv_prt' => $request->prestadorTMO,
                'srv_cmp' => $request->complementoTMO,
                'srv_qhr' => $qtdHrTMO,
                'srv_vhr' => $valUniHrTMO,
                'srv_vts' => $valTotHrTMO,
                'srv_for' => $forTerceiroTMO,
                'srv_nft' => $request->numNfTerceiroTMO,
                'srv_srt' => $request->serNfTerceiroTMO,
                'srv_dtt' => $dataNfTerceiroTMO,
                'srv_tcg' => $request->tipCustoTMO,
                'srv_pcg' => $valCustoTMO,
                'srv_vcg' => $perCustoTMO]);   

        $sum_servicos = DB::table('lancamento_srv_os_servicos')->where('srv_emp',$empresa)->where('srv_nos',$numOS)->where('srv_req',$requisicao)->wherein('srv_sts',['A','E','F'])->sum('srv_vts');

        $updValReq = DB::table('lancamento_srv_os_requisicoes')
            ->where('req_emp', $empresa)
            ->where('req_nos', $numOS)
            ->where('req_seq', $requisicao)
            ->update(['req_vlr' => $sum_servicos,
                'req_vls' => $sum_servicos]);  
        
        return redirect(route('painelOS.consultaRequisicao', ['empresa' => $empresa, 'nos' => $numOS, 'estagioAPP' => 'CONSULTA_REQUISICAO', 'requisicao' => $requisicao]))->with('success', 'TMO atualizada com sucesso!');
    }

    //Metodo de atualização dos dados do serviço da requisição
    public function aprovaServico($empresa, $numOS, $requisicao, $sequencia, $codTMO)
    {        
        $data = date('Y-m-d H:i:s');

        $usuario = Auth::user()->usuario_codigo;

        $atualiaServico = DB::table('lancamento_srv_os_servicos')
            ->where('srv_emp', $empresa)
            ->where('srv_nos', $numOS)
            ->where('srv_req', $requisicao)
            ->where('srv_seq', $sequencia)
            ->where('srv_tmo', $codTMO)
            ->update(['srv_flg_apr' => 'S',
                'srv_res_apr' => $usuario,
                'srv_dh_apr' => $data]);   
        
        return redirect(route('painelOS.consultaRequisicao', ['empresa' => $empresa, 'nos' => $numOS, 'estagioAPP' => 'CONSULTA_REQUISICAO', 'requisicao' => $requisicao]))->with('success', 'TMO aprovada com sucesso!');
    }

    //Metodo de inicialização dos serviços em espera
    public function iniciarServico($empresa, $numOS, $requisicao)
    {    
        $cnt_apr = $this->servicoOS->where('srv_nos', $numOS)->where('srv_emp', $empresa)->where('srv_req', $requisicao)->where('srv_flg_apr', 'N')->count();
        if($cnt_apr > 0){
            return redirect()->back()->with('info', 'A Requisição contém servico(s) que ainda não foram aprovados!');
        }

        $cnt_espera = $this->servicoOS->where('srv_nos', $numOS)->where('srv_emp', $empresa)->where('srv_req', $requisicao)->where('srv_sts', 'E')->count();
        if($cnt_espera == 0){
            return redirect()->back()->with('info', 'Não existe TMO em espera para ser iniciada!');
        }

        $data = date('Y-m-d');
        $hora = date('Hi');

        $atualiaServico = DB::table('lancamento_srv_os_servicos')
            ->where('srv_emp', $empresa)
            ->where('srv_nos', $numOS)
            ->where('srv_req', $requisicao)
            ->where('srv_sts', 'E')
            ->update(['srv_sts' => 'A',
                'srv_dti' => $data,
                'srv_hri' => $hora]);   
        
        return redirect(route('painelOS.consultaRequisicao', ['empresa' => $empresa, 'nos' => $numOS, 'estagioAPP' => 'CONSULTA_REQUISICAO', 'requisicao' => $requisicao]))->with('success', 'Serviço(s) em espera iniciado(s)!');
    }

    //Metodo de finalização dos serviços em andamento
    public function finalizarServico($empresa, $numOS, $requisicao)
    {        
        $cnt_andamento = $this->servicoOS->where('srv_nos', $numOS)->where('srv_emp', $empresa)->where('srv_req', $requisicao)->where('srv_sts', 'A')->count();
        if($cnt_andamento == 0){
            return redirect()->back()->with('info', 'Não existe TMO em andamento para ser finalizada!');
        }

        $data = date('Y-m-d');
        $hora = date('Hi');

        $atualiaServico = DB::table('lancamento_srv_os_servicos')
            ->where('srv_emp', $empresa)
            ->where('srv_nos', $numOS)
            ->where('srv_req', $requisicao)
            ->where('srv_sts', 'A')
            ->update(['srv_sts' => 'F',
                'srv_dtf' => $data,
                'srv_hrf' => $hora]);   
        
        return redirect(route('painelOS.consultaRequisicao', ['empresa' => $empresa, 'nos' => $numOS, 'estagioAPP' => 'CONSULTA_REQUISICAO', 'requisicao' => $requisicao]))->with('success', 'Serviço(s) em andamento finalizados(s)!');
    }

    //Metodo de suspender tmo
    public function suspenderServico($empresa, $numOS, $requisicao, $sequencia, $codTMO)
    {        
        $servico = $this->servicoOS->where('srv_nos', $numOS)->where('srv_emp', $empresa)->where('srv_req', $requisicao)->where('srv_seq', $sequencia)->where('srv_tmo', $codTMO)->get();
       
        if($servico[0]->srv_sts == 'F'){
            return redirect()->back()->with('info', 'TMO selecionada já foi finalizada!');
        }

        $atualiaServico = DB::table('lancamento_srv_os_servicos')
            ->where('srv_emp', $empresa)
            ->where('srv_nos', $numOS)
            ->where('srv_req', $requisicao)
            ->where('srv_seq', $sequencia)
            ->where('srv_tmo', $codTMO)
            ->update(['srv_sts' => 'S']);   

        $sum_servicos = DB::table('lancamento_srv_os_servicos')->where('srv_emp',$empresa)->where('srv_nos',$numOS)->where('srv_req',$requisicao)->wherein('srv_sts',['A','E','F'])->sum('srv_vts');

        $updValReq = DB::table('lancamento_srv_os_requisicoes')
            ->where('req_emp', $empresa)
            ->where('req_nos', $numOS)
            ->where('req_seq', $requisicao)
            ->update(['req_vlr' => $sum_servicos,
                'req_vls' => $sum_servicos]);  
        
        return redirect(route('painelOS.consultaRequisicao', ['empresa' => $empresa, 'nos' => $numOS, 'estagioAPP' => 'CONSULTA_REQUISICAO', 'requisicao' => $requisicao]))->with('success', 'Serviço suspenso com sucesso!');
    }

    //Metodo de cancelar tmo
    public function cancelarServico($empresa, $numOS, $requisicao, $sequencia, $codTMO)
    {       
        $atualiaServico = DB::table('lancamento_srv_os_servicos')
            ->where('srv_emp', $empresa)
            ->where('srv_nos', $numOS)
            ->where('srv_req', $requisicao)
            ->where('srv_seq', $sequencia)
            ->where('srv_tmo', $codTMO)
            ->update(['srv_sts' => 'C']);  
        
        $sum_servicos = DB::table('lancamento_srv_os_servicos')->where('srv_emp',$empresa)->where('srv_nos',$numOS)->where('srv_req',$requisicao)->wherein('srv_sts',['A','E','F'])->sum('srv_vts');

        $updValReq = DB::table('lancamento_srv_os_requisicoes')
            ->where('req_emp', $empresa)
            ->where('req_nos', $numOS)
            ->where('req_seq', $requisicao)
            ->update(['req_vlr' => $sum_servicos,
                'req_vls' => $sum_servicos]); 
        
        return redirect(route('painelOS.consultaRequisicao', ['empresa' => $empresa, 'nos' => $numOS, 'estagioAPP' => 'CONSULTA_REQUISICAO', 'requisicao' => $requisicao]))->with('success', 'Serviço cancelado com sucesso!');
    }

    //Metodo de reabrir tmo
    public function reabrirServico($empresa, $numOS, $requisicao, $sequencia, $codTMO)
    {       
        $atualiaServico = DB::table('lancamento_srv_os_servicos')
            ->where('srv_emp', $empresa)
            ->where('srv_nos', $numOS)
            ->where('srv_req', $requisicao)
            ->where('srv_seq', $sequencia)
            ->where('srv_tmo', $codTMO)
            ->update(['srv_sts' => 'E']);  
        
        return redirect(route('painelOS.consultaRequisicao', ['empresa' => $empresa, 'nos' => $numOS, 'estagioAPP' => 'CONSULTA_REQUISICAO', 'requisicao' => $requisicao]))->with('success', 'Serviço reaberto com sucesso!');
    }

    //Metodo de excluir serviço
    public function destroy(LancamentoSrvOsServico $servicoOS, $empresa, $numOS, $requisicao){

        $servicoOS->delete();
        
        return redirect(route('painelOS.consultaRequisicao', ['empresa' => $empresa, 'nos' => $numOS, 'estagioAPP' => 'CONSULTA_REQUISICAO', 'requisicao' => $requisicao]))->with('success', 'Serviço excluído com sucesso!');
    }
}
