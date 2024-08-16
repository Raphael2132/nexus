<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use App\Http\Helpers\Helper;
use DateTime;

class PainelProducaoController extends Controller
{
    public function abrePainel(Request $request)
    {

        //Tipo do Painel por Prestadores
        if($request->tipoPainel == 'PR'){

            $dadosEmp = DB::table('cadastro_empresas')->where('empresa_codigo', $request->empresa)->first();
            $dadosEmpEnd = DB::table('cadastro_empresa_enderecos')->where('endereco_empresa_codigo', $request->empresa)->where('endereco_principal', 'S')->first();
            $dadosPrt = DB::table('cadastro_prestadores')->where('prestador_empresa', $request->empresa)->where('prestador_set', $request->setor)->where('prestador_status', 'A')->orderby('prestador_codigo')->get();
            $dadosSet = DB::table('parametros_srv_setores')->where('setor_empresa', $request->empresa)->where('setor_codigo', $request->setor)->first();
            $dadosAre = DB::table('parametros_sistema_areas')->where('area_codigo', $dadosSet->setor_area)->first();

            //Monta o horario de inicio e final de expediente do dia
            $diaSemana = date('D', strtotime(Helper::limpaData($request->dataPainel)));
            $dadosGerEmp = DB::table('parametros_ger_empresas')->where('parger_emp', $request->empresa)->first();

            if($diaSemana == 'Fri' || $diaSemana == 'Thu' || $diaSemana == 'Wed' || $diaSemana == 'Tue' || $diaSemana == 'Mon'){
                $horaIniEx = Helper::formataHoraMinuto($dadosGerEmp->parger_hr_ini_fun);
                $horaFinEx = Helper::formataHoraMinuto($dadosGerEmp->parger_hr_fin_fun);
            }elseif($diaSemana == 'Sat'){
                if($dadosGerEmp->parger_hr_ini_fun == '1'){
                    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese'); 
                    $dia = ucfirst(strftime("%A", strtotime(Helper::limpaData($request->dataPainel))));
                    return redirect()->back()->with('error', 'Não há expediente aos '.$dia.'s na empresa '.$dadosEmp->empresa_codigo.' - '.$dadosEmp->empresa_nome.'!');
                }else{
                    if($dadosGerEmp->parger_hr_alt_sab == 'S'){
                        $horaIniEx = Helper::formataHoraMinuto($dadosGerEmp->parger_hr_ini_sab);
                        $horaFinEx = Helper::formataHoraMinuto($dadosGerEmp->parger_hr_fin_sab);
                    }else{
                        $horaIniEx = Helper::formataHoraMinuto($dadosGerEmp->parger_hr_ini_fun);
                        $horaFinEx = Helper::formataHoraMinuto($dadosGerEmp->parger_hr_fin_fun);
                    }
                }
            }else{
                if($dadosGerEmp->parger_hr_ini_fun != '3'){
                    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese'); 
                    $dia = ucfirst(strftime("%A", strtotime(Helper::limpaData($request->dataPainel))));
                    return redirect()->back()->with('error', 'Não há expediente aos '.$dia.'s na empresa '.$dadosEmp->empresa_codigo.' - '.$dadosEmp->empresa_nome.'!');
                }else{
                    if($dadosGerEmp->parger_hr_alt_dom == 'S'){
                        $horaIniEx = Helper::formataHoraMinuto($dadosGerEmp->parger_hr_ini_dom);
                        $horaFinEx = Helper::formataHoraMinuto($dadosGerEmp->parger_hr_fin_dom);
                    }else{
                        $horaIniEx = Helper::formataHoraMinuto($dadosGerEmp->parger_hr_ini_fun);
                        $horaFinEx = Helper::formataHoraMinuto($dadosGerEmp->parger_hr_fin_fun);
                    }
                }
            }

            $expedienteInicio = new \DateTime($horaIniEx); // Hora de início do expediente
            $expedienteFim = new \DateTime($horaFinEx); // Hora de fim do expediente
            $expedienteFim->sub(new \DateInterval('PT30M')); // Subtrai 30 minutos do horário de fim do expediente
            $intervalo = new \DateInterval('PT30M'); // Intervalo de 30 minutos
            $horarios = [];

            while ($expedienteInicio <= $expedienteFim) {
                $horarios[] = $expedienteInicio->format('H:i');
                $expedienteInicio->add($intervalo);
            }

            //Pega a quantidade de horas entre o inicio e o final do expediente
            // Cria objetos DateTime para os horários de início e fim
            $inicio = new DateTime($horaIniEx);
            $fim = new DateTime($horaFinEx);

            // Calcula a diferença entre os dois horários
            $intervalo = $inicio->diff($fim);

            // Calcula a diferença em horas e minutos
            $horas = $intervalo->h;
            $minutos = $intervalo->i;

            // Se houver minutos, apenas considera a parte inteira das horas
            $horaExpediente = $horas + floor($minutos / 60);

            //Transforma os segundos em milissegundos
            $milissegundosPagina = $request->tempoPagina * 1000;

            return view('/lancamentos/producao/painelProducaoPrestador', 
                [
                    'glo_painel_empresa' => $request->empresa, 
                    'glo_painel_setor' => $request->setor,
                    'glo_painel_data' => Helper::limpaData($request->dataPainel), 
                    'dadosEmp' => $dadosEmp, 
                    'dadosEmpEnd' => $dadosEmpEnd,
                    'dadosPrt' => $dadosPrt, 
                    'dadosSet' => $dadosSet, 
                    'dadosAre' => $dadosAre,
                    'horarios' => $horarios,
                    'horaIniEx' => $horaIniEx,
                    'horaFinEx' => $horaFinEx,
                    'horaExpediente' => $horaExpediente,
                    'milissegundosPagina' => $milissegundosPagina,
                    'linhaPagina' => $request->linhaPagina,
                ]
            );
        }else{

            $dadosEmp = DB::table('cadastro_empresas')->where('empresa_codigo', $request->empresa)->first();
            $dadosEmpEnd = DB::table('cadastro_empresa_enderecos')->where('endereco_empresa_codigo', $request->empresa)->where('endereco_principal', 'S')->first();
            $dadosPrt = DB::table('cadastro_prestadores')->where('prestador_empresa', $request->empresa)->where('prestador_set', $request->setor)->where('prestador_status', 'A')->orderby('prestador_codigo')->get();
            $dadosSet = DB::table('parametros_srv_setores')->where('setor_empresa', $request->empresa)->where('setor_codigo', $request->setor)->first();
            $dadosAre = DB::table('parametros_sistema_areas')->where('area_codigo', $dadosSet->setor_area)->first();
            $dadosOS = DB::table('vi_lancamento_os_painel_os')
            ->where('empresa', $request->empresa)
            ->where(function ($query) use ($request) {
                $data = Helper::limpaData($request->dataPainel);
                $query->whereDate('dt_hr_fechamento', $data)
                      ->orWhereNull('dt_hr_fechamento');
            })
            ->get();

            //Monta o horario de inicio e final de expediente do dia
            $diaSemana = date('D', strtotime(Helper::limpaData($request->dataPainel)));
            $dadosGerEmp = DB::table('parametros_ger_empresas')->where('parger_emp', $request->empresa)->first();

            if($diaSemana == 'Fri' || $diaSemana == 'Thu' || $diaSemana == 'Wed' || $diaSemana == 'Tue' || $diaSemana == 'Mon'){
                $horaIniEx = Helper::formataHoraMinuto($dadosGerEmp->parger_hr_ini_fun);
                $horaFinEx = Helper::formataHoraMinuto($dadosGerEmp->parger_hr_fin_fun);
            }elseif($diaSemana == 'Sat'){
                if($dadosGerEmp->parger_hr_ini_fun == '1'){
                    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese'); 
                    $dia = ucfirst(strftime("%A", strtotime(Helper::limpaData($request->dataPainel))));
                    return redirect()->back()->with('error', 'Não há expediente aos '.$dia.'s na empresa '.$dadosEmp->empresa_codigo.' - '.$dadosEmp->empresa_nome.'!');
                }else{
                    if($dadosGerEmp->parger_hr_alt_sab == 'S'){
                        $horaIniEx = Helper::formataHoraMinuto($dadosGerEmp->parger_hr_ini_sab);
                        $horaFinEx = Helper::formataHoraMinuto($dadosGerEmp->parger_hr_fin_sab);
                    }else{
                        $horaIniEx = Helper::formataHoraMinuto($dadosGerEmp->parger_hr_ini_fun);
                        $horaFinEx = Helper::formataHoraMinuto($dadosGerEmp->parger_hr_fin_fun);
                    }
                }
            }else{
                if($dadosGerEmp->parger_hr_ini_fun != '3'){
                    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese'); 
                    $dia = ucfirst(strftime("%A", strtotime(Helper::limpaData($request->dataPainel))));
                    return redirect()->back()->with('error', 'Não há expediente aos '.$dia.'s na empresa '.$dadosEmp->empresa_codigo.' - '.$dadosEmp->empresa_nome.'!');
                }else{
                    if($dadosGerEmp->parger_hr_alt_dom == 'S'){
                        $horaIniEx = Helper::formataHoraMinuto($dadosGerEmp->parger_hr_ini_dom);
                        $horaFinEx = Helper::formataHoraMinuto($dadosGerEmp->parger_hr_fin_dom);
                    }else{
                        $horaIniEx = Helper::formataHoraMinuto($dadosGerEmp->parger_hr_ini_fun);
                        $horaFinEx = Helper::formataHoraMinuto($dadosGerEmp->parger_hr_fin_fun);
                    }
                }
            }

            //Pega a quantidade de horas entre o inicio e o final do expediente
            // Cria objetos DateTime para os horários de início e fim
            $inicio = new DateTime($horaIniEx);
            $fim = new DateTime($horaFinEx);

            // Calcula a diferença entre os dois horários
            $intervalo = $inicio->diff($fim);

            // Calcula a diferença em horas e minutos
            $horas = $intervalo->h;
            $minutos = $intervalo->i;

            // Se houver minutos, apenas considera a parte inteira das horas
            $horaExpediente = $horas + floor($minutos / 60);

            //Transforma os segundos em milissegundos
            $milissegundosPagina = $request->tempoPagina * 1000;

            return view('/lancamentos/producao/painelProducaoOS', 
                [
                    'glo_painel_empresa' => $request->empresa, 
                    'glo_painel_setor' => $request->setor,
                    'glo_painel_data' => Helper::limpaData($request->dataPainel), 
                    'dadosEmp' => $dadosEmp, 
                    'dadosEmpEnd' => $dadosEmpEnd,
                    'dadosPrt' => $dadosPrt, 
                    'dadosSet' => $dadosSet, 
                    'dadosAre' => $dadosAre,
                    'horaIniEx' => $horaIniEx,
                    'horaFinEx' => $horaFinEx,
                    'horaExpediente' => $horaExpediente,
                    'dadosOS' => $dadosOS,
                    'linhaPagina' => $request->linhaPagina,
                    'milissegundosPagina' => $milissegundosPagina,
                ]
            );
        }
    }

    //Carrega o setor por AJAX
    public function carregaSetAjax($empresa)
    {  
        $setores = DB::table('parametros_srv_setores')->select('setor_codigo', 'setor_desc')->where('setor_empresa', $empresa)->orderby('setor_codigo', 'asc')->get();

        if(!empty($setores[0])){

            foreach($setores as $setor) {
                $setores_ajax[] = array(
                    'id'	=> $setor->setor_codigo,
                    'cod_setor' => $setor->setor_codigo.' - '.$setor->setor_desc,
                );
            }  

            return response()->json(['success' => true, 'setores_ajax' => $setores_ajax, 'setores_ajax_existe' => 'S']);

        }else{

            return response()->json(['success' => true, 'setores_ajax' => null, 'setores_ajax_existe' => 'N']);
        }
    }

    //Carrega o setor por AJAX
    public function carregaEventosTMO($empresa, $setor, $data)
    {  
        $dadosPrt = DB::table('cadastro_prestadores')->where('prestador_empresa', $empresa)->where('prestador_set', $setor)->where('prestador_status', 'A')->orderby('prestador_codigo')->get();

        foreach($dadosPrt as $prestador){

            //$tmo = DB::table('lancamento_srv_exe_tarefas')->where('exetrf_emp', $empresa)->where('exetrf_prt', $prestador->prestador_codigo)->where('exetrf_dt_age_tmo', $data)->orderby('exetrf_nos')->orderby('exetrf_req')->orderby('exetrf_seq')->get();
        
            //Busca pela view os dados de serviços relacionados ao prestador durante o periodo pesquisado
            $tmo = DB::table('vi_lancamento_os_agenda_prt')
            ->where('empresa', $empresa)
            ->where('prestador', $prestador->prestador_codigo)
            ->where(function ($query) use ($data) {
                $query->where('data_ini_servico', $data)
                    ->orWhere('data_fin_servico', $data);
            })
            ->orderBy('num_os')
            ->orderBy('requisicao')
            ->orderBy('sequencia')
            ->get();

            // Adiciona cada evento ao array de eventos
            foreach ($tmo as $item) {

                $eventos[] = [
                    'prestadorId' => $prestador->prestador_codigo,
                    'dtInicio' => $item->data_ini_servico,
                    'inicio' => Helper::formataHoraMinuto($item->hora_ini_servico),
                    'dtFim' => $item->data_fin_servico,
                    'fim' => Helper::formataHoraMinuto($item->hora_fin_servico),
                    'osNumero' => $item->num_os,
                    'tempo' => Helper::convertHrCentToHrSexa($item->qtd_hora_servico),
                    'codTmo' => $item->cod_servico,
                    'situacao' => $item->situacao
                ];
            }

            /*echo '<pre>';
            print_r($eventos);
            echo '</pre>';*/
        }
    
        return response()->json($eventos);
    }

    //Gera as OS em andamento do dia
    public function getOsEmAndamento($empresa, $setor, $data) {
        
        $dadosOS = DB::table('lancamento_srv_os')
        ->where('os_emp', $empresa)
        ->where('os_sts', 'A')
        ->whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('lancamento_srv_exe_tarefas')
                ->whereColumn('exetrf_nos', 'os_nos')
                ->whereColumn('exetrf_emp', 'os_emp')
                ->whereIn('exetrf_sts', ['A', 'F']);
        })
        ->orderBy('os_nos')
        ->get();

        $osEmAndamento = [];

        foreach($dadosOS as $os){

            $osEmAndamento[] = [
                'OS '.$os->os_nos
            ];
        }

        return response()->json($osEmAndamento);
    }
    
    //Gera as OS em finalizadas do dia
    public function getOsFinalizadas($empresa, $setor, $data) {
        
        $dadosOS = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->where('os_sts', 'F')->whereDate('os_dhf', $data)->orderby('os_nos')->get();

        $osFinalizadas = [];

        foreach($dadosOS as $os){

            $osFinalizadas[] = [
                'OS '.$os->os_nos
            ];
        }

        return response()->json($osFinalizadas);
    }
    
    //Gera as próximas OS a serem iniciadas do dia
    public function getProximasOs($empresa, $setor, $data) {
        
        $dadosOS = DB::table('lancamento_srv_os')
        ->where('os_emp', $empresa)
        ->where('os_sts', 'A')
        ->whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('lancamento_srv_exe_tarefas')
                ->whereColumn('exetrf_nos', 'os_nos')
                ->whereColumn('exetrf_emp', 'os_emp')
                ->whereIn('exetrf_sts', ['A', 'F']);
        })
        ->orderBy('os_nos')
        ->get();

        $proximasOs = [];

        foreach($dadosOS as $os){

            $proximasOs[] = [
                'OS '.$os->os_nos
            ];
        }

        return response()->json($proximasOs);
    }
}
