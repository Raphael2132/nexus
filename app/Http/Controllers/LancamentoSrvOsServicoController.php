<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LancamentoSrvOsServico;
use Illuminate\Support\Facades\Auth;
use stdClass;
use DateTime;
use DateTimeZone;
use App\Http\Helpers\Helper;
use App\Http\Helpers\HelperControleProducao;
use App\Http\Controllers\PainelAberturaOSController;
use App\Http\Controllers\LancamentoSrvExeTarefaController;

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
            $dataNfTerceiroTMO = Helper::limpaData($request->dataNfTerceiroTMO);
        }else{
            $dataNfTerceiroTMO = null;
        }

        if(!empty($request->qtdHrTMO)){
            $qtdHrTMO = str_replace(",",".",$request->qtdHrTMO);
        }else{
            $qtdHrTMO = '0.00';
        }
        
        if(!empty($request->valUniHrTMO)){
            $valUniHrTMO = Helper::limpaValorMonetario($request->valUniHrTMO);
        }else{
            $valUniHrTMO = '0.00';
        }

        if(!empty($request->valTotHrTMO)){
            $valTotHrTMO = Helper::limpaValorMonetario($request->valTotHrTMO);
        }else{
            $valTotHrTMO = '0.00';
        }

        if(!empty($request->valCustoTMO)){
            $valCustoTMO = Helper::limpaValorMonetario($request->valCustoTMO);
        }else{
            $valCustoTMO = '0.00';
        }

        if(!empty($request->perCustoTMO)){
            $perCustoTMO = Helper::limpaPorcentagem($request->perCustoTMO);
        }else{
            $perCustoTMO = '0.00';
        }

        if(!empty($request->prestadorTMO)){
            $prestadorTMO = substr($request->prestadorTMO, 0, 6);

            if(strlen($prestadorTMO) < 6){
                return redirect()->back()->with('error', 'Código do prestador responsável '.$prestadorTMO.' é inválido!');
            }

            $cnt_prest = DB::table('cadastro_prestadores')->where('prestador_empresa', $empresa)->where('prestador_are', $requisicaoSel[0]->req_are)->where('prestador_set', $requisicaoSel[0]->req_set)->where('prestador_codigo', $prestadorTMO)->count();
        
            if($cnt_prest == 0){
                return redirect()->back()->with('error', 'Código do prestador responsável '.$prestadorTMO.' não existe no setor '.$requisicaoSel[0]->req_set.' da área '.$requisicaoSel[0]->req_are.'!');
            }
        }else{
            $prestadorTMO = null;
        }

        $dados = [
            'srv_emp' => $empresa,
            'srv_nos' => $numOS,
            'srv_req' => $requisicao,
            'srv_seq' => $sequencia,
            'srv_prt' => $prestadorTMO,
            'srv_set' => $requisicaoSel[0]->req_set,
            'srv_are' => $requisicaoSel[0]->req_are,
            'srv_tmo' => $codTMO,
            'srv_dsc' => $request->descricaoTMO,
            'srv_cmp' => $request->complementoTMO,
            'srv_ths' => $request->tipoTMO,
            'srv_qhr' => $qtdHrTMO,
            'srv_vhr' => $valUniHrTMO,
            'srv_vts' => $valTotHrTMO,
            'srv_for' => $forTerceiroTMO,
            'srv_nft' => $request->numNfTerceiroTMO,
            'srv_srt' => $request->serNfTerceiroTMO,
            'srv_dtt' => $dataNfTerceiroTMO,
            'srv_tcg' => $request->tipCustoTMO,
            'srv_pcg' => $perCustoTMO,
            'srv_vcg' => $valCustoTMO,
            'srv_vtl' => $valTotHrTMO,
            'srv_dt_inc' => $data_inc
        ];
        
        LancamentoSrvOsServico::create($dados);

        PainelAberturaOSController::atualizaValorRequisicao($empresa, $numOS, $requisicao);

        PainelAberturaOSController::atualizaValorOS($empresa, $numOS);

        PainelAberturaOSController::atualizaPrevEntrega($empresa, $numOS);

        return redirect(route('painelOS.consultaRequisicao', ['empresa' => $empresa, 'nos' => $numOS, 'estagioAPP' => 'CONSULTA_REQUISICAO', 'requisicao' => $requisicao]))->with('success', 'TMO adicionada com sucesso!');
    }

    //Metodo de atualização dos dados do serviço da requisição
    public function update(Request $request, $empresa, $numOS, $requisicao, $sequencia, $codTMO, $estagioAPP, $tos)
    {       
        if(!empty($request->valDesTMO)){ 
            $valDesconto = Helper::limpaValorMonetario($request->valDesTMO);
        }else{
            $valDesconto = 0;
        }

        if(!empty($request->perDesTMO)){ 
            $perDesconto = Helper::limpaPorcentagem($request->perDesTMO);
        }else{
            $perDesconto = 0;
        }

        if(!empty($request->valLiqTMO)){ 
            $valLiquido = Helper::limpaPorcentagem($request->valLiqTMO);
        }else{
            $valLiquido = 0;
        }

        if($perDesconto > 0 || $valDesconto > 0){

            $tosDados = DB::table('lancamento_srv_tipo_servicos')->where('tipsrv_emp', $empresa)->where('tipsrv_cod', $tos)->get();

            $srvDados = DB::table('lancamento_srv_os_servicos')->where('srv_emp', $empresa)->where('srv_nos', $numOS)->where('srv_req', $requisicao)->where('srv_seq', $sequencia)->where('srv_tmo', $codTMO)->get();

            if($srvDados[0]->srv_aut_desc == 'N'){
                if($tosDados[0]->tipsrv_pmt_des == 'N'){
                    return redirect()->back()->with('error', 'O Tipo de Serviço '.$tos.' - '.$tosDados[0]->tipsrv_nom.' não permite desconto!');
                }else{
                    if($tosDados[0]->tipsrv_vmd != 0 && $valDesconto > $tosDados[0]->tipsrv_vmd){
                        return redirect()->back()->with('error', 'O Tipo de Serviço '.$tos.' - '.$tosDados[0]->tipsrv_nom.' permite um valor máximo de desconto de R$'.Helper::formataValorMonetario($tosDados[0]->tipsrv_vmd));
                    }
                    if($tosDados[0]->tipsrv_pmd != 0 && $perDesconto > $tosDados[0]->tipsrv_pmd){
                        return redirect()->back()->with('error', 'O Tipo de Serviço '.$tos.' - '.$tosDados[0]->tipsrv_nom.' permite um percentual máximo de desconto de '.Helper::formataValorMonetario($tosDados[0]->tipsrv_pmd).'%');                
                    }
                }
            }
        }

        if(!empty($request->forTerceiroTMO)){
            $forTerceiroTMO = substr($request->forTerceiroTMO, 0, 10);
        }else{
            $forTerceiroTMO = '';
        }

        if(!empty($request->dataNfTerceiroTMO)){
            $dataNfTerceiroTMO = Helper::limpaData($request->dataNfTerceiroTMO);
        }else{
            $dataNfTerceiroTMO = null;
        }

        if(!empty($request->qtdHrTMO)){
            $qtdHrTMO = str_replace(",",".",$request->qtdHrTMO);
        }else{
            $qtdHrTMO = '0.00';
        }
        
        if(!empty($request->valUniHrTMO)){
            $valUniHrTMO = Helper::limpaValorMonetario($request->valUniHrTMO);
        }else{
            $valUniHrTMO = '0.00';
        }

        if(!empty($request->valTotHrTMO)){
            $valTotHrTMO = Helper::limpaValorMonetario($request->valTotHrTMO);
        }else{
            $valTotHrTMO = '0.00';
        }

        if(!empty($request->valCustoTMO)){
            $valCustoTMO = Helper::limpaValorMonetario($request->valCustoTMO);
        }else{
            $valCustoTMO = '0.00';
        }

        if(!empty($request->perCustoTMO)){
            $perCustoTMO = Helper::limpaPorcentagem($request->perCustoTMO);
        }else{
            $perCustoTMO = '0.00';
        }

        $requisicaoSel = session('glo_os_dadosRequisicoes');

        if(!empty($request->prestadorTMO)){
            $prestadorTMO = substr($request->prestadorTMO, 0, 6);

            if(strlen($prestadorTMO) < 6){
                return redirect()->back()->with('error', 'Código do prestador responsável '.$prestadorTMO.' é inválido!');
            }

            $cnt_prest = DB::table('cadastro_prestadores')->where('prestador_empresa', $empresa)->where('prestador_are', $requisicaoSel[0]->req_are)->where('prestador_set', $requisicaoSel[0]->req_set)->where('prestador_codigo', $prestadorTMO)->count();
        
            if($cnt_prest == 0){
                return redirect()->back()->with('error', 'Código do prestador responsável '.$prestadorTMO.' não existe no setor '.$requisicaoSel[0]->req_set.' da área '.$requisicaoSel[0]->req_are.'!');
            }

        }else{
            $prestadorTMO = null;
        }

        $atualiaServico = DB::table('lancamento_srv_os_servicos')
            ->where('srv_emp', $empresa)
            ->where('srv_nos', $numOS)
            ->where('srv_req', $requisicao)
            ->where('srv_seq', $sequencia)
            ->where('srv_tmo', $codTMO)
            ->update(['srv_prt' => $prestadorTMO,
                'srv_cmp' => $request->complementoTMO,
                'srv_qhr' => $qtdHrTMO,
                'srv_vhr' => $valUniHrTMO,
                'srv_vts' => $valTotHrTMO,
                'srv_for' => $forTerceiroTMO,
                'srv_nft' => $request->numNfTerceiroTMO,
                'srv_srt' => $request->serNfTerceiroTMO,
                'srv_dtt' => $dataNfTerceiroTMO,
                'srv_tcg' => $request->tipCustoTMO,
                'srv_pcg' => $perCustoTMO,
                'srv_vcg' => $valCustoTMO,
                'srv_per_des' => $perDesconto,
                'srv_val_des' => $valDesconto,
                'srv_vtl' => $valLiquido]);   

        PainelAberturaOSController::atualizaValorRequisicao($empresa, $numOS, $requisicao);

        PainelAberturaOSController::atualizaValorOS($empresa, $numOS);

        PainelAberturaOSController::atualizaPrevEntrega($empresa, $numOS);
        
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

        //Gera a tabela da execução da tarefa
        LancamentoSrvExeTarefaController::insert($empresa,$numOS,$requisicao,$sequencia);
        
        return redirect(route('painelOS.consultaRequisicao', ['empresa' => $empresa, 'nos' => $numOS, 'estagioAPP' => 'CONSULTA_REQUISICAO', 'requisicao' => $requisicao]))->with('success', 'TMO aprovada com sucesso!');
    }

    //Metodo de atualização dos dados do serviço da requisição do botão Aprovar
    public function aprovaServicoBtn(Request $request, $empresa, $numOS, $requisicao)
    {        
        // Validação adicional no servidor
        $selectedServices = $request->selected_srvApr;

        if (empty($selectedServices)) {
            return redirect()->back()->with('error', 'Nenhum serviço foi selecionado.');
        }

        $srvSel = explode(',', $selectedServices);

        foreach ($srvSel as $srv) {
            $data = date('Y-m-d H:i:s');

            $usuario = Auth::user()->usuario_codigo;

            $atualiaServico = DB::table('lancamento_srv_os_servicos')
                ->where('srv_emp', $empresa)
                ->where('srv_nos', $numOS)
                ->where('srv_req', $requisicao)
                ->where('srv_seq', $srv)
                ->update(['srv_flg_apr' => 'S',
                    'srv_res_apr' => $usuario,
                    'srv_dh_apr' => $data]);  
                    
            //Gera a tabela da execução da tarefa
            LancamentoSrvExeTarefaController::insert($empresa,$numOS,$requisicao,$srv);
        }
        
        return redirect(route('painelOS.consultaRequisicao', ['empresa' => $empresa, 'nos' => $numOS, 'estagioAPP' => 'CONSULTA_REQUISICAO', 'requisicao' => $requisicao]))->with('success', 'TMO aprovada com sucesso!');
    }

    //Metodo de inicialização dos serviços em espera
    public function iniciarServico(Request $request, $empresa, $numOS, $requisicao)
    {    
        /*
        $cnt_apr = $this->servicoOS->where('srv_nos', $numOS)->where('srv_emp', $empresa)->where('srv_req', $requisicao)->where('srv_flg_apr', 'N')->count();
        if($cnt_apr > 0){
            return redirect()->back()->with('info', 'A Requisição contém servico(s) que ainda não foram aprovados!');
        }

        $cnt_espera = $this->servicoOS->where('srv_nos', $numOS)->where('srv_emp', $empresa)->where('srv_req', $requisicao)->where('srv_sts', 'E')->count();
        if($cnt_espera == 0){
            return redirect()->back()->with('info', 'Não existe TMO em espera para ser iniciada!');
        }*/
        
        // Validação adicional no servidor
        $selectedServices = $request->selected_srvIni;

        if (empty($selectedServices)) {
            return redirect()->back()->with('error', 'Nenhum serviço foi selecionado.');
        }

        $srvSel = explode(',', $selectedServices);

        foreach ($srvSel as $srv) {
            
            $dadosTMO = $this->servicoOS->where('srv_nos', $numOS)->where('srv_emp', $empresa)->where('srv_req', $requisicao)->where('srv_seq', $srv)->first();

            //Verifica se existe ou não prestador para a tarefa e como vai buscar os dados do horario de expediente e intervalo
            if(!empty(($dadosTMO->srv_prt))){

                //Monta os arrays de horario de expediente e almoço do prestador
                $businessHours = HelperControleProducao::geraBusinessHoursPHP($empresa, $dadosTMO->srv_prt);
                $lunchBreaks = HelperControleProducao::geraLunchHoursPHP($empresa, $dadosTMO->srv_prt);

            }else{

                //Monta os arrays de horario de expediente e almoço da empresa
                $businessHours = HelperControleProducao::geraBusinessHoursEmpPHP($empresa);
                $lunchBreaks = HelperControleProducao::geraLunchHoursEmpPHP($empresa);
            }

            /*echo '<pre>';
            print_r($businessHours);
            echo '</pre>';

            echo '<pre>';
            print_r($lunchBreaks);
            echo '</pre>';*/

            $data = date('Y-m-d');
            $hora = date('Hi');
            $horaFormatada = date('H:i');
            $tempoTMO = Helper::convertHrCentToHrSexa($dadosTMO->srv_qhr);
            
            $dataTermino = HelperControleProducao::calculaPrevTerminoSrv($data, $horaFormatada, $tempoTMO, $businessHours, $lunchBreaks);

            $dtPrvTer = date('Y-m-d', strtotime($dataTermino));
            $hrPrvTer = date('Hi', strtotime($dataTermino));

            DB::table('lancamento_srv_os_servicos')
            ->where('srv_emp', $empresa)
            ->where('srv_nos', $numOS)
            ->where('srv_req', $requisicao)
            ->where('srv_seq', $srv)
            ->update(['srv_sts' => 'A',
                'srv_dti' => $data,
                'srv_hri' => $hora]);  
                    
            DB::table('lancamento_srv_exe_tarefas')
            ->where('exetrf_emp', $empresa)
            ->where('exetrf_nos', $numOS)
            ->where('exetrf_req', $requisicao)
            ->where('exetrf_seq', $srv)
            ->update(['exetrf_sts' => 'A',
                'exetrf_dt_ini_srv' => $data,
                'exetrf_hr_ini_srv' => $hora,
                'exetrf_dt_prv_fin_srv' => $dtPrvTer,
                'exetrf_hr_prv_fin_srv' => $hrPrvTer]);  
        }
        
        return redirect(route('painelOS.consultaRequisicao', ['empresa' => $empresa, 'nos' => $numOS, 'estagioAPP' => 'CONSULTA_REQUISICAO', 'requisicao' => $requisicao]))->with('success', 'Serviço(s) em espera iniciado(s)!');
    }

    //Metodo de finalização dos serviços em andamento
    public function finalizarServico(Request $request, $empresa, $numOS, $requisicao)
    {       
        // Validação adicional no servidor
        $selectedServices = $request->selected_srvFin;

        if (empty($selectedServices)) {
            return redirect()->back()->with('error', 'Nenhum serviço foi selecionado.');
        }

        $srvSel = explode(',', $selectedServices);

        foreach ($srvSel as $srv) {

            $data = date('Y-m-d');
            $hora = date('Hi');
            $dataHora = date('Y-m-d H:i:s');

            $atualiaServico = DB::table('lancamento_srv_os_servicos')
                ->where('srv_emp', $empresa)
                ->where('srv_nos', $numOS)
                ->where('srv_req', $requisicao)
                ->where('srv_seq', $srv)
                ->update(['srv_sts' => 'F',
                    'srv_dtf' => $data,
                    'srv_hrf' => $hora]);   
            
            $servico = $this->servicoOS->where('srv_nos', $numOS)->where('srv_emp', $empresa)->where('srv_req', $requisicao)->where('srv_seq', $srv)->first();

            if(!empty($servico->srv_prt)){
                //Monta os arrays de horario de expediente e almoço do prestador
                $businessHours = HelperControleProducao::geraBusinessHoursPHP($empresa, $servico->srv_prt);
                $lunchBreaks = HelperControleProducao::geraLunchHoursPHP($empresa, $servico->srv_prt);
            }else{
                //Monta os arrays de horario de expediente e almoço da empresa
                $businessHours = HelperControleProducao::geraBusinessHoursEmpPHP($empresa);
                $lunchBreaks = HelperControleProducao::geraLunchHoursEmpPHP($empresa);
            }

            //Monta variaveis de hora de inicio e final de serviço para calculo do tempo de serviço
            $startDateTime = new DateTime($servico->srv_dti.' '.Helper::formataHoraMinuto($servico->srv_hri).':00');
            $endDateTime = new DateTime($dataHora);

            //Calcula o tempo de serviço
            $tempoServico = HelperControleProducao::calculaDuracaoServico($startDateTime, $endDateTime, $businessHours, $lunchBreaks);

            //Calcula o saldo entre o tempo previsto e o tempo real
            $tempoSaldo = $servico->srv_qhr - $tempoServico;
            $tempoSaldo =  number_format($tempoSaldo, 2, '.', '');

            DB::table('lancamento_srv_exe_tarefas')
            ->where('exetrf_emp', $empresa)
            ->where('exetrf_nos', $numOS)
            ->where('exetrf_req', $requisicao)
            ->where('exetrf_seq', $srv)
            ->update([
                'exetrf_sts' => 'F',
                'exetrf_dt_fin_srv' => $data,
                'exetrf_hr_fin_srv' => $hora,
                'exetrf_qhr_real' => $tempoServico,
                'exetrf_qhr_saldo' => $tempoSaldo
            ]);
        }
        
        return redirect(route('painelOS.consultaRequisicao', ['empresa' => $empresa, 'nos' => $numOS, 'estagioAPP' => 'CONSULTA_REQUISICAO', 'requisicao' => $requisicao]))->with('success', 'Serviço(s) em andamento finalizados(s)!');
    }

    //Metodo de suspender tmo
    public function suspenderServico(Request $request, $empresa, $numOS, $requisicao, $sequencia, $codTMO)
    {        
        $servico = $this->servicoOS->where('srv_nos', $numOS)->where('srv_emp', $empresa)->where('srv_req', $requisicao)->where('srv_seq', $sequencia)->where('srv_tmo', $codTMO)->get();
       
        if($servico[0]->srv_sts == 'F'){
            return redirect()->back()->with('info', 'TMO selecionada já foi finalizada!');
        }

        $dataHora = date('Y-m-d H:i:s');
        $data = date('Y-m-d');
        $hora = date('Hi');

        DB::table('lancamento_srv_os_servicos')
        ->where('srv_emp', $empresa)
        ->where('srv_nos', $numOS)
        ->where('srv_req', $requisicao)
        ->where('srv_seq', $sequencia)
        ->where('srv_tmo', $codTMO)
        ->update([
            'srv_sts' => 'S',
            'srv_dhs' => $dataHora,
            'srv_res_sus' => Auth::user()->usuario_codigo,
            'srv_mot_sus' => $request->susMot
        ]);   
        
        DB::table('lancamento_srv_exe_tarefas')
        ->where('exetrf_emp', $empresa)
        ->where('exetrf_nos', $numOS)
        ->where('exetrf_req', $requisicao)
        ->where('exetrf_seq', $sequencia)
        ->update([
            'exetrf_sts' => 'S',
            'exetrf_dt_sus_srv' => $data,
            'exetrf_hr_sus_srv' => $hora,
            'exetrf_res_sus' => Auth::user()->usuario_codigo,
            'exetrf_mot_sus_srv' => $request->susMot
        ]);

        $sum_servicos = DB::table('lancamento_srv_os_servicos')->where('srv_emp',$empresa)->where('srv_nos',$numOS)->where('srv_req',$requisicao)->wherein('srv_sts',['A','E','F'])->sum('srv_vtl');

        PainelAberturaOSController::atualizaValorRequisicao($empresa, $numOS, $requisicao);

        PainelAberturaOSController::atualizaValorOS($empresa, $numOS); 

        PainelAberturaOSController::atualizaPrevEntrega($empresa, $numOS);
        
        return redirect(route('painelOS.consultaRequisicao', ['empresa' => $empresa, 'nos' => $numOS, 'estagioAPP' => 'CONSULTA_REQUISICAO', 'requisicao' => $requisicao]))->with('success', 'Serviço suspenso com sucesso!');
    }

    //Metodo de cancelar tmo
    public function cancelarServico(Request $request, $empresa, $numOS, $requisicao, $sequencia, $codTMO)
    {       
        $dataHora = date('Y-m-d H:i:s');
        $data = date('Y-m-d');
        $hora = date('Hi');
        
        DB::table('lancamento_srv_os_servicos')
        ->where('srv_emp', $empresa)
        ->where('srv_nos', $numOS)
        ->where('srv_req', $requisicao)
        ->where('srv_seq', $sequencia)
        ->where('srv_tmo', $codTMO)
        ->update([
            'srv_sts' => 'C',
            'srv_dhc' => $dataHora,
            'srv_res_can' => Auth::user()->usuario_codigo,
            'srv_mot_can' => $request->canMot
        ]);  

        DB::table('lancamento_srv_exe_tarefas')
        ->where('exetrf_emp', $empresa)
        ->where('exetrf_nos', $numOS)
        ->where('exetrf_req', $requisicao)
        ->where('exetrf_seq', $sequencia)
        ->update([
            'exetrf_sts' => 'C',
            'exetrf_dt_can_srv' => $data,
            'exetrf_hr_can_srv' => $hora,
            'exetrf_res_can' => Auth::user()->usuario_codigo,
            'exetrf_mot_can_srv' => $request->canMot
        ]);
    
        $sum_servicos = DB::table('lancamento_srv_os_servicos')->where('srv_emp',$empresa)->where('srv_nos',$numOS)->where('srv_req',$requisicao)->wherein('srv_sts',['A','E','F'])->sum('srv_vtl');

        PainelAberturaOSController::atualizaValorRequisicao($empresa, $numOS, $requisicao);

        PainelAberturaOSController::atualizaValorOS($empresa, $numOS); 

        PainelAberturaOSController::atualizaPrevEntrega($empresa, $numOS);
        
        return redirect(route('painelOS.consultaRequisicao', ['empresa' => $empresa, 'nos' => $numOS, 'estagioAPP' => 'CONSULTA_REQUISICAO', 'requisicao' => $requisicao]))->with('success', 'Serviço cancelado com sucesso!');
    }

    //Metodo de reabrir tmo
    public function reabrirServico($empresa, $numOS, $requisicao, $sequencia, $codTMO)
    {       
        $dadosSrv = DB::table('lancamento_srv_os_servicos')->where('srv_emp', $empresa)
            ->where('srv_nos', $numOS)
            ->where('srv_req', $requisicao)
            ->where('srv_seq', $sequencia)
            ->where('srv_tmo', $codTMO)
            ->first();

        if(!empty($dadosSrv->srv_dti)){
            $status = 'A';
        }else{
            $status = 'E';
        }

        DB::table('lancamento_srv_os_servicos')
        ->where('srv_emp', $empresa)
        ->where('srv_nos', $numOS)
        ->where('srv_req', $requisicao)
        ->where('srv_seq', $sequencia)
        ->where('srv_tmo', $codTMO)
        ->update([
            'srv_sts' => $status,
            'srv_dtf' => null,
            'srv_hrf' => 0,
            'srv_dhc' => null,
            'srv_res_can' => null,
            'srv_dhs' => null,
            'srv_res_sus' => null,
            'srv_mot_sus' => null,
            'srv_mot_can' => null
        ]);  

        DB::table('lancamento_srv_exe_tarefas')
        ->where('exetrf_emp', $empresa)
        ->where('exetrf_nos', $numOS)
        ->where('exetrf_req', $requisicao)
        ->where('exetrf_seq', $sequencia)
        ->update([
            'exetrf_sts' => $status,
            'exetrf_dt_fin_srv' => null,
            'exetrf_hr_fin_srv' => 0,
            'exetrf_qhr_real' => 0,
            'exetrf_qhr_saldo' => 0,
            'exetrf_dt_can_srv' => null,
            'exetrf_hr_can_srv' => 0,
            'exetrf_mot_can_srv' => null,
            'exetrf_dt_sus_srv' => null,
            'exetrf_hr_sus_srv' => 0,
            'exetrf_mot_sus_srv' => null,
            'exetrf_res_sus' => null,
            'exetrf_res_can' => null,
            'exetrf_dt_prv_fin_srv' => null,
            'exetrf_hr_prv_fin_srv' => 0
        ]);

        PainelAberturaOSController::atualizaValorRequisicao($empresa, $numOS, $requisicao);

        PainelAberturaOSController::atualizaValorOS($empresa, $numOS); 

        PainelAberturaOSController::atualizaPrevEntrega($empresa, $numOS);
        
        return redirect(route('painelOS.consultaRequisicao', ['empresa' => $empresa, 'nos' => $numOS, 'estagioAPP' => 'CONSULTA_REQUISICAO', 'requisicao' => $requisicao]))->with('success', 'Serviço reaberto com sucesso!');
    }

    //Metodo de excluir serviço
    public function destroy(LancamentoSrvOsServico $servicoOS, $empresa, $numOS, $requisicao){

        $servicoOS->delete();

        //Quando exclui um serviço zera o desconto da requisição
        $atualiaServico = DB::table('lancamento_srv_os_requisicoes')
            ->where('req_emp', $empresa)
            ->where('req_nos', $numOS)
            ->where('req_seq', $requisicao)
            ->update(['req_per_des' => 0,
                'req_val_des' => 0]); 

        PainelAberturaOSController::atualizaValorRequisicao($empresa, $numOS, $requisicao);

        PainelAberturaOSController::atualizaValorOS($empresa, $numOS); 

        PainelAberturaOSController::atualizaPrevEntrega($empresa, $numOS);
        
        return redirect(route('painelOS.consultaRequisicao', ['empresa' => $empresa, 'nos' => $numOS, 'estagioAPP' => 'CONSULTA_REQUISICAO', 'requisicao' => $requisicao]))->with('success', 'Serviço excluído com sucesso!');
    }

    //Autoriza o desconto da requisição
    public function autorizaDescontoTMO(Request $request, $empresa, $numOS, $requisicao, $sequencia, $codTMO)
    {        

        $usuario = DB::table('users')->where('usuario_codigo', $request->usuarioDesconto)->get();

        if(empty($usuario[0])){
            return redirect()->back()->with('error', 'O Usuário '.$request->usuarioDesconto.' não existe!');
        }else{

            if (password_verify($request->senhaDesconto, $usuario[0]->password)) {
                if($usuario[0]->usuario_aut_desc == 'N'){
                    return redirect()->back()->with('error', 'O Usuário '.$request->usuarioDesconto.' não tem permissão para autorizar o desconto!');
                }
            }else{
                return redirect()->back()->with('error', 'Senha informada é inválida!');
            }
        }

        if($request->liberaDesc == true){
            $autoriza = 'S';
            $msg = 'Desconto autorizado com sucesso';
        }else{
            $autoriza = 'N';
            $msg = 'Desconto não autorizado com sucesso';
        }

        $atualiaServico = DB::table('lancamento_srv_os_servicos')
            ->where('srv_emp', $empresa)
            ->where('srv_nos', $numOS)
            ->where('srv_req', $requisicao)
            ->where('srv_seq', $sequencia)
            ->where('srv_tmo', $codTMO)
            ->update(['srv_aut_desc' => $autoriza,
                'srv_usu_aut_desc' => $request->usuarioDesconto]);

        return redirect(route('painelOS.consultaServicoRequisicao', ['empresa' => $empresa, 'numOS' => $numOS, 'requisicao' => $requisicao, 'sequencia' => $sequencia, 'codTMO' => $codTMO, 'estagioAPP' => 'MANUTENCAO_SERVICO', 'subEstagioRequisicao' => 'TMO_SELECIONADA']))->with('success', $msg);
    }
}
