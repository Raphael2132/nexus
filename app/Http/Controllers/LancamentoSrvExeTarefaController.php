<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LancamentoSrvExeTarefa;
use stdClass;
use App\Http\Helpers\Helper;
use App\Http\Helpers\HelperControleProducao;
use DateTime;
use DateTimeZone;
use App\Http\Controllers\LancamentoSrvPrtAuxiliaresController;
use App\Http\Controllers\Lancamentos\Servico\PainelAberturaOSController;
use Illuminate\Support\Facades\Auth;

class LancamentoSrvExeTarefaController extends Controller
{
    protected $exeTarefa;
    
    public function __construct(LancamentoSrvExeTarefa $exeTarefa)
    {
        $this->exeTarefa = $exeTarefa;
    }

    //Insere a a tarefa
    static function insert($empresa,$numOS,$requisicao,$servico){

        $dadosSrv = DB::table('lancamento_srv_os_servicos')->where('srv_emp', $empresa)->where('srv_nos', $numOS)->where('srv_req', $requisicao)->where('srv_seq', $servico)->first();

        $dadosOS = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->where('os_nos', $numOS)->first();

        $dados = [
            'exetrf_emp' => $empresa,
            'exetrf_nos' => $numOS,
            'exetrf_req' => $requisicao,
            'exetrf_seq' => $servico,
            'exetrf_tmo' => $dadosSrv->srv_tmo,
            'exetrf_desc' => $dadosSrv->srv_dsc,
            'exetrf_cmp' => $dadosSrv->srv_cmp,
            'exetrf_are' => $dadosSrv->srv_are,
            'exetrf_set' => $dadosSrv->srv_set,
            'exetrf_ths' => $dadosSrv->srv_ths,
            'exetrf_qhr' => $dadosSrv->srv_qhr,
            'exetrf_prt' => $dadosSrv->srv_prt,
            'exetrf_sts' => $dadosSrv->srv_sts,
            'exetrf_dt_inc' => $dadosSrv->srv_dt_inc,
            'exetrf_usu' => $dadosOS->os_res_abr,
            'exetrf_dt_prev_ent' => $dadosOS->os_dpe,
            'exetrf_hr_prev_ent' => $dadosOS->os_hpe
        ];
        
        LancamentoSrvExeTarefa::create($dados);
        
        return;
    }

    public function updateAgeAjax(Request $request)
    {
        $start = $request->start;
        $end = $request->end;
        $empresa = $request->empresa;
        $os = $request->os;
        $req = $request->req;
        $seq = $request->seq;
        $prestador = $request->prestador;

        // Criar objetos DateTime com o fuso horário UTC
        $dateStart = new DateTime($start, new DateTimeZone('UTC'));
        $dateEnd = new DateTime($end, new DateTimeZone('UTC'));

        // Definir o fuso horário local desejado
        $timezone = new DateTimeZone('America/Sao_Paulo');

        // Converter para o fuso horário local
        $dateStart->setTimezone($timezone);
        $dateEnd->setTimezone($timezone);

        // Formatar a data no formato Y-m-d
        $dataIni = $dateStart->format('Y-m-d');
        $dataFin = $dateEnd->format('Y-m-d');

        // Formatar a hora no formato Hi (sem o separador ":")
        $horaIni = $dateStart->format('Hi');
        $horaFin = $dateEnd->format('Hi');

        DB::table('lancamento_srv_exe_tarefas')
        ->where('exetrf_emp', $empresa)
        ->where('exetrf_nos', $os)
        ->where('exetrf_req', $req)
        ->where('exetrf_seq', $seq)
        ->update(['exetrf_age' => 'S',
        'exetrf_dt_age_tmo' => $dataIni,
        'exetrf_hr_age_tmo' => $horaIni,
        'exetrf_dt_age_fin_tmo' => $dataFin,
        'exetrf_hr_age_fin_tmo' => $horaFin,
        'exetrf_prt' => $prestador]);

        DB::table('lancamento_srv_os_servicos')
        ->where('srv_emp', $empresa)
        ->where('srv_nos', $os)
        ->where('srv_req', $req)
        ->where('srv_seq', $seq)
        ->update(['srv_prt' => $prestador]);

        return response()->json(['success' => true]);
    }

    public function iniciarTMO(Request $request, $empresa, $numOS, $requisicao, $servico)
    {

        //Vamos passar como variavel no redirect por que depois de 2 redirect elas são destruidas
        $where = session('where_consulta_painelOperador'); 
        $empresa = session('empresaOS_consulta_painelOperador'); 

        $prtTMO = DB::table('lancamento_srv_exe_tarefas')
        ->where('exetrf_emp', $empresa)
        ->where('exetrf_nos', $numOS)
        ->where('exetrf_req', $requisicao)
        ->where('exetrf_seq', $servico)
        ->first();

        if(empty($prtTMO->exetrf_prt)){
            return redirect()->route('painelOperacao.consultaPainelGET',['empresa' => $empresa, 'where' => $where])
            ->with('error', 'TMO selecionada não tem Prestador alocado para ser iniciada!');
        }
        
        $data = date('Y-m-d');
        $tempoTMO = Helper::convertHrCentToHrSexa($prtTMO->exetrf_qhr);

        //Monta os arrays de horario de expediente e almoço do prestador
        $businessHours = HelperControleProducao::geraBusinessHoursPHP($empresa, $prtTMO->exetrf_prt);
        $lunchBreaks = HelperControleProducao::geraLunchHoursPHP($empresa, $prtTMO->exetrf_prt);

        $dataTermino = HelperControleProducao::calculaPrevTerminoSrv($data, $request->horaIniSrv, $tempoTMO, $businessHours, $lunchBreaks);

        $dtPrvTer = date('Y-m-d', strtotime($dataTermino));
        $hrPrvTer = date('Hi', strtotime($dataTermino));

        DB::table('lancamento_srv_exe_tarefas')
        ->where('exetrf_emp', $empresa)
        ->where('exetrf_nos', $numOS)
        ->where('exetrf_req', $requisicao)
        ->where('exetrf_seq', $servico)
        ->update([
            'exetrf_sts' => 'A',
            'exetrf_dt_ini_srv' => $data,
            'exetrf_hr_ini_srv' => Helper::limpaHoraMinuto($request->horaIniSrv),
            'exetrf_dt_prv_fin_srv' => $dtPrvTer,
            'exetrf_hr_prv_fin_srv' => $hrPrvTer
        ]);

        DB::table('lancamento_srv_os_servicos')
        ->where('srv_emp', $empresa)
        ->where('srv_nos', $numOS)
        ->where('srv_req', $requisicao)
        ->where('srv_seq', $servico)
        ->update([
            'srv_sts' => 'A',
            'srv_dti' => $data,
            'srv_hri' => Helper::limpaHoraMinuto($request->horaIniSrv)
        ]);

        // Redireciona de volta para a página principal
        return redirect()->route('painelOperacao.consultaPainelGET',['empresa' => $empresa, 'where' => $where])
        ->with('success', 'TMO iniciada com sucesso!');
    }

    public function finalizarTMO(Request $request, $empresa, $numOS, $requisicao, $servico)
    {
        //Vamos passar como variavel no redirect por que depois de 2 redirect elas são destruidas
        $where = session('where_consulta_painelOperador'); 
        $empresa = session('empresaOS_consulta_painelOperador'); 

        $prtTMO = DB::table('lancamento_srv_exe_tarefas')
        ->where('exetrf_emp', $empresa)
        ->where('exetrf_nos', $numOS)
        ->where('exetrf_req', $requisicao)
        ->where('exetrf_seq', $servico)
        ->first();

        if(empty($prtTMO->exetrf_prt)){
            return redirect()->route('painelOperacao.consultaPainelGET',['empresa' => $empresa, 'where' => $where])
            ->with('error', 'TMO selecionada não tem Prestador alocado para ser finalizada!');
        }

        //Busca dados da tarefa finalizada
        $prtTMO = DB::table('lancamento_srv_exe_tarefas')
        ->where('exetrf_emp', $empresa)
        ->where('exetrf_nos', $numOS)
        ->where('exetrf_req', $requisicao)
        ->where('exetrf_seq', $servico)
        ->first();

        //echo $empresa.' '.$numOS.' '.$requisicao.' '.$servico;exit;

        //Monta os arrays de horario de expediente e almoço do prestador
        $businessHours = HelperControleProducao::geraBusinessHoursPHP($empresa, $prtTMO->exetrf_prt);
        $lunchBreaks = HelperControleProducao::geraLunchHoursPHP($empresa, $prtTMO->exetrf_prt);

        //Gera dia do fechamento
        $data = date('Y-m-d');

        //Monta variaveis de hora de inicio e final de serviço para calculo do tempo de serviço
        $startDateTime = new DateTime($prtTMO->exetrf_dt_ini_srv.' '.Helper::formataHoraMinuto($prtTMO->exetrf_hr_ini_srv).':00');
        $endDateTime = new DateTime($data.' '.$request->horaFinSrv.':00');

        //Teste de calculo
        //$startDateTime = new DateTime('2024-08-01 17:00:00');
        //$endDateTime = new DateTime('2024-08-05 14:38:00');

        //Calcula o tempo de serviço
        $tempoServico = HelperControleProducao::calculaDuracaoServico($startDateTime, $endDateTime, $businessHours, $lunchBreaks);

        //Calcula o saldo entre o tempo previsto e o tempo real
        $tempoSaldo = $prtTMO->exetrf_qhr - $tempoServico;
        $tempoSaldo =  number_format($tempoSaldo, 2, '.', '');

        DB::table('lancamento_srv_exe_tarefas')
        ->where('exetrf_emp', $empresa)
        ->where('exetrf_nos', $numOS)
        ->where('exetrf_req', $requisicao)
        ->where('exetrf_seq', $servico)
        ->update([
            'exetrf_sts' => 'F',
            'exetrf_dt_fin_srv' => $data,
            'exetrf_hr_fin_srv' => Helper::limpaHoraMinuto($request->horaFinSrv),
            'exetrf_qhr_real' => $tempoServico,
            'exetrf_qhr_saldo' => $tempoSaldo
        ]);

        DB::table('lancamento_srv_os_servicos')
        ->where('srv_emp', $empresa)
        ->where('srv_nos', $numOS)
        ->where('srv_req', $requisicao)
        ->where('srv_seq', $servico)
        ->update([
            'srv_sts' => 'F',
            'srv_dtf' => $data,
            'srv_hrf' => Helper::limpaHoraMinuto($request->horaFinSrv)
        ]);

        // Redireciona de volta para a página principal
        return redirect()->route('painelOperacao.consultaPainelGET',['empresa' => $empresa, 'where' => $where])
        ->with('success', 'TMO finalizada com sucesso!');
    }

    public function addChangePrtTMO(Request $request, $empresa, $numOS, $requisicao, $servico)
    {
        //Vamos passar como variavel no redirect por que depois de 2 redirect elas são destruidas
        $where = session('where_consulta_painelOperador'); 
        $empresa = session('empresaOS_consulta_painelOperador'); 

        //Busca dados da tarefa finalizada
        $dadosTMO = DB::table('lancamento_srv_exe_tarefas')
        ->where('exetrf_emp', $empresa)
        ->where('exetrf_nos', $numOS)
        ->where('exetrf_req', $requisicao)
        ->where('exetrf_seq', $servico)
        ->first();

        if($dadosTMO->exetrf_sts == 'F'){
            // Redireciona de volta para a página principal
            return redirect()->route('painelOperacao.consultaPainelGET',['empresa' => $empresa, 'where' => $where])
            ->with('error', 'Não é possivel trocar o prestador. TMO já foi finalizada!');
        }

        DB::table('lancamento_srv_exe_tarefas')
        ->where('exetrf_emp', $empresa)
        ->where('exetrf_nos', $numOS)
        ->where('exetrf_req', $requisicao)
        ->where('exetrf_seq', $servico)
        ->update([
            'exetrf_prt' => $request->selected_prt
        ]);

        DB::table('lancamento_srv_os_servicos')
        ->where('srv_emp', $empresa)
        ->where('srv_nos', $numOS)
        ->where('srv_req', $requisicao)
        ->where('srv_seq', $servico)
        ->update([
            'srv_prt' => $request->selected_prt
        ]);

        // Redireciona de volta para a página principal
        return redirect()->route('painelOperacao.consultaPainelGET',['empresa' => $empresa, 'where' => $where])
        ->with('success', 'Prestador alocado com sucesso!');
    }

    public function addPrtAuxTMO(Request $request, $empresa, $numOS, $requisicao, $servico)
    {
        //Vamos passar como variavel no redirect por que depois de 2 redirect elas são destruidas
        $where = session('where_consulta_painelOperador'); 
        $empresa = session('empresaOS_consulta_painelOperador'); 

        //Busca dados da tarefa finalizada
        $dadosTMO = DB::table('lancamento_srv_exe_tarefas')
        ->where('exetrf_emp', $empresa)
        ->where('exetrf_nos', $numOS)
        ->where('exetrf_req', $requisicao)
        ->where('exetrf_seq', $servico)
        ->first();

        if($dadosTMO->exetrf_sts == 'F'){
            // Redireciona de volta para a página principal
            return redirect()->route('painelOperacao.consultaPainelGET',['empresa' => $empresa, 'where' => $where])
            ->with('error', 'Não é possivel adicionar prestadores auxiliares. TMO já foi finalizada!');
        }

        $prtSel = explode(',', $request->selected_prtAux);

        //Gera o insert do auxiliar
        foreach($prtSel as $prestador){
            LancamentoSrvPrtAuxiliaresController::insert($empresa, $numOS, $requisicao, $servico, $prestador);
        }

        // Redireciona de volta para a página principal
        return redirect()->route('painelOperacao.consultaPainelGET',['empresa' => $empresa, 'where' => $where])
        ->with('success', 'Auxiliar adicionado com sucesso!');
    }

    public function cancelarTMO(Request $request, $empresa, $numOS, $requisicao, $servico)
    {
        //Vamos passar como variavel no redirect por que depois de 2 redirect elas são destruidas
        $where = session('where_consulta_painelOperador'); 
        $empresa = session('empresaOS_consulta_painelOperador'); 

        $data = date('Y-m-d');
        $hora = date('Hi');
        $dataHora = date('Y-m-d H:i:s');

        DB::table('lancamento_srv_exe_tarefas')
        ->where('exetrf_emp', $empresa)
        ->where('exetrf_nos', $numOS)
        ->where('exetrf_req', $requisicao)
        ->where('exetrf_seq', $servico)
        ->update([
            'exetrf_sts' => 'C',
            'exetrf_dt_can_srv' => $data,
            'exetrf_hr_can_srv' => $hora,
            'exetrf_res_can' => Auth::user()->usuario_codigo,
            'exetrf_mot_can_srv' => $request->motivoCan
        ]);

        DB::table('lancamento_srv_os_servicos')
        ->where('srv_emp', $empresa)
        ->where('srv_nos', $numOS)
        ->where('srv_req', $requisicao)
        ->where('srv_seq', $servico)
        ->update([
            'srv_sts' => 'C',
            'srv_dhc' => $dataHora,
            'srv_res_can' => Auth::user()->usuario_codigo,
            'srv_mot_can' => $request->motivoCan
        ]);

        PainelAberturaOSController::atualizaValorRequisicao($empresa, $numOS, $requisicao);

        PainelAberturaOSController::atualizaValorOS($empresa, $numOS); 

        PainelAberturaOSController::atualizaPrevEntrega($empresa, $numOS);

        // Redireciona de volta para a página principal
        return redirect()->route('painelOperacao.consultaPainelGET',['empresa' => $empresa, 'where' => $where])
        ->with('success', 'TMO cancelada com sucesso!');
    }

    public function suspenderTMO(Request $request, $empresa, $numOS, $requisicao, $servico)
    {
        //Vamos passar como variavel no redirect por que depois de 2 redirect elas são destruidas
        $where = session('where_consulta_painelOperador'); 
        $empresa = session('empresaOS_consulta_painelOperador'); 

        $data = date('Y-m-d');
        $hora = date('Hi');
        $dataHora = date('Y-m-d H:i:s');

        DB::table('lancamento_srv_exe_tarefas')
        ->where('exetrf_emp', $empresa)
        ->where('exetrf_nos', $numOS)
        ->where('exetrf_req', $requisicao)
        ->where('exetrf_seq', $servico)
        ->update([
            'exetrf_sts' => 'S',
            'exetrf_dt_sus_srv' => $data,
            'exetrf_hr_sus_srv' => $hora,
            'exetrf_res_sus' => Auth::user()->usuario_codigo,
            'exetrf_mot_sus_srv' => $request->motivoSus
        ]);

        DB::table('lancamento_srv_os_servicos')
        ->where('srv_emp', $empresa)
        ->where('srv_nos', $numOS)
        ->where('srv_req', $requisicao)
        ->where('srv_seq', $servico)
        ->update([
            'srv_sts' => 'S',
            'srv_dhs' => $dataHora,
            'srv_res_sus' => Auth::user()->usuario_codigo,
            'srv_mot_sus' => $request->motivoSus
        ]);

        PainelAberturaOSController::atualizaValorRequisicao($empresa, $numOS, $requisicao);

        PainelAberturaOSController::atualizaValorOS($empresa, $numOS); 

        PainelAberturaOSController::atualizaPrevEntrega($empresa, $numOS);

        // Redireciona de volta para a página principal
        return redirect()->route('painelOperacao.consultaPainelGET',['empresa' => $empresa, 'where' => $where])
        ->with('success', 'TMO suspensa com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Reabrir TMO que já foi Finalizada / Cancelada / Suspensa
    |----------------------------------------------------------------------------------------------------
    */
    public function reabrirTMO(Request $request, $empresa, $numOS, $requisicao, $servico)
    {
        //Vamos passar como variavel no redirect por que depois de 2 redirect elas são destruidas
        $where = session('where_consulta_painelOperador'); 
        $empresa = session('empresaOS_consulta_painelOperador'); 

        //Verifica se a OS já foi finalizada ou cancelada e não permite reabrir a TMO
        $dadosOS = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->where('os_nos', $numOS)->first();

        if($dadosOS->os_sts == 'C'){
            // Redireciona de volta para a página principal
            return redirect()->route('painelOperacao.consultaPainelGET',['empresa' => $empresa, 'where' => $where])
            ->with('info', 'Não é possível reabrir a TMO. A OS '.$numOS.' já foi cancelada!');
        }else if($dadosOS->os_sts == 'F'){
            // Redireciona de volta para a página principal
            return redirect()->route('painelOperacao.consultaPainelGET',['empresa' => $empresa, 'where' => $where])
            ->with('info', 'Não é possível reabrir a TMO. A OS '.$numOS.' já foi finalizada!');
        }

        $dadosSrv = DB::table('lancamento_srv_os_servicos')
        ->where('srv_emp', $empresa)
        ->where('srv_nos', $numOS)
        ->where('srv_req', $requisicao)
        ->where('srv_seq', $servico)
        ->first();

        if($dadosSrv->srv_sts == 'F'){
            $status = 'A';
        }else{
            if(!empty($dadosSrv->srv_dti)){
                $status = 'A';
            }else{
                $status = 'E';
            }
        }

        DB::table('lancamento_srv_exe_tarefas')
        ->where('exetrf_emp', $empresa)
        ->where('exetrf_nos', $numOS)
        ->where('exetrf_req', $requisicao)
        ->where('exetrf_seq', $servico)
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

        DB::table('lancamento_srv_os_servicos')
        ->where('srv_emp', $empresa)
        ->where('srv_nos', $numOS)
        ->where('srv_req', $requisicao)
        ->where('srv_seq', $servico)
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

        PainelAberturaOSController::atualizaValorRequisicao($empresa, $numOS, $requisicao);

        PainelAberturaOSController::atualizaValorOS($empresa, $numOS); 

        PainelAberturaOSController::atualizaPrevEntrega($empresa, $numOS);

        // Redireciona de volta para a página principal
        return redirect()->route('painelOperacao.consultaPainelGET',['empresa' => $empresa, 'where' => $where])
        ->with('success', 'TMO reaberta com sucesso!');
    }
}
