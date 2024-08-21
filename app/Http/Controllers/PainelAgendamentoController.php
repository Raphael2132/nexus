<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use App\Http\Helpers\Helper;
use App\Http\Helpers\HelperControleProducao;
use DateTime;
use DateTimeZone;

class PainelAgendamentoController extends Controller
{
    //Redireciona a app para o calendario do prestador
    public function agendaPrestador(Request $request)
    {    
        if(!empty($request->prestador)){
            $prestador = substr($request->prestador, 0, 6);

            if(strlen($prestador) < 6){
                return redirect()->back()->with('error', 'Código do prestador '.$prestador.' é inválido!');
            }

            $cnt_prt = DB::table('cadastro_prestadores')->where('prestador_codigo', $prestador)->count();
        
            if($cnt_prt == 0){
                return redirect()->back()->with('error', 'Código do prestador '.$prestador.' não existe!');
            }
        
        }else{
            return redirect()->back()->with('error', 'É obrigátorio informar o prestador!');
        }

        $dataIni = Helper::limpaData($request->dtIniAge);
        $dataFin = Helper::limpaData($request->dtFinAge);
        
        //por bug no fullcalendar a data final usada nele tem que ser acrecida de +1 dia, sem isso ele gera um dia a menos
        $dataFinCalendar = date('Y-m-d', strtotime($dataFin.' +1 day'));

        if($dataIni > $dataFin){
            return redirect()->back()->with('error', 'A Data Inicial não pode ser maior que a Data Final!');
        }

        //Busca o horário de funcionamento da empresa e caso dependendo do dia tenha um horario diferente busca a menor data de inicio e a maior de encerramento
        $hrFuncioncionamento = DB::select("SELECT 
            CASE 
                WHEN parger_dia_fun = '1' THEN parger_hr_ini_fun
                WHEN parger_dia_fun = '2' and parger_hr_alt_sab = 'N' THEN parger_hr_ini_fun
                WHEN parger_dia_fun = '2' and parger_hr_alt_sab = 'S' THEN LEAST(parger_hr_ini_fun, parger_hr_ini_sab)
                WHEN parger_dia_fun = '3' and parger_hr_alt_sab = 'N' and parger_hr_alt_dom = 'N' THEN parger_hr_ini_fun
                WHEN parger_dia_fun = '3' and parger_hr_alt_sab = 'N' and parger_hr_alt_dom = 'S' THEN LEAST(parger_hr_ini_fun, parger_hr_ini_dom)
                WHEN parger_dia_fun = '3' and parger_hr_alt_sab = 'S' and parger_hr_alt_dom = 'S' THEN LEAST(parger_hr_ini_fun, parger_hr_ini_sab, parger_hr_ini_dom)
                WHEN parger_dia_fun = '3' and parger_hr_alt_sab = 'S' and parger_hr_alt_dom = 'N' THEN LEAST(parger_hr_ini_fun, parger_hr_ini_sab)
                ELSE LEAST(parger_hr_ini_fun, parger_hr_ini_sab, parger_hr_ini_dom)
            END AS hora_ini,
            CASE 
                WHEN parger_dia_fun = '1' THEN parger_hr_fin_fun
                WHEN parger_dia_fun = '2' and parger_hr_alt_sab = 'N' THEN parger_hr_fin_fun
                WHEN parger_dia_fun = '2' and parger_hr_alt_sab = 'S' THEN GREATEST(parger_hr_fin_fun, parger_hr_fin_sab)
                WHEN parger_dia_fun = '3' and parger_hr_alt_sab = 'N' and parger_hr_alt_dom = 'N' THEN parger_hr_fin_fun
                WHEN parger_dia_fun = '3' and parger_hr_alt_sab = 'N' and parger_hr_alt_dom = 'S' THEN GREATEST(parger_hr_fin_fun, parger_hr_fin_dom)
                WHEN parger_dia_fun = '3' and parger_hr_alt_sab = 'S' and parger_hr_alt_dom = 'S' THEN GREATEST(parger_hr_fin_fun, parger_hr_fin_sab, parger_hr_fin_dom)
                WHEN parger_dia_fun = '3' and parger_hr_alt_sab = 'S' and parger_hr_alt_dom = 'N' THEN GREATEST(parger_hr_fin_fun, parger_hr_fin_sab)
                ELSE GREATEST(parger_hr_fin_fun, parger_hr_fin_sab, parger_hr_fin_dom)
            END AS hora_fin
        FROM parametros_ger_empresas 
        WHERE parger_emp = '".$request->empresa."'");

        //Hora minima e maxima de exibição do calendario de agendamento
        $horaMinima = Helper::formataHoraMinuto($hrFuncioncionamento[0]->hora_ini);
        $horaMaxima = Helper::formataHoraMinuto($hrFuncioncionamento[0]->hora_fin);

        $dadosEmpresa = DB::table('cadastro_empresas')->where('empresa_codigo', $request->empresa)->get();
        $dadosPrestador = DB::table('cadastro_prestadores')->where('prestador_codigo', $prestador)->where('prestador_empresa', $request->empresa)->get();
        $dadosParGerEmpresa = DB::table('parametros_ger_empresas')->where('parger_emp', $request->empresa)->get();
        $dadosParSrvEmpresa = DB::table('parametros_srv_empresas')->where('parsrv_emp', $request->empresa)->get();

        //Gera as horas de expediente do funcionário
        if($dadosPrestador[0]->prestador_tur_cod == 1){

            if($dadosParGerEmpresa[0]->parger_dia_fun == 1){

                $diaSemStart = Helper::formataHoraMinuto($dadosParGerEmpresa[0]->parger_hr_ini_fun);
                $diaSemEnd = Helper::formataHoraMinuto($dadosParGerEmpresa[0]->parger_hr_fin_fun);

                $usaSabado = false;
                $usaDomingo = false;
                $sabadoStart = '00:00';
                $sabadoEnd = '00:00';
                $domingoStart = '00:00';
                $domingoEnd = '00:00';

            }elseif($dadosParGerEmpresa[0]->parger_dia_fun == 2){

                $diaSemStart = Helper::formataHoraMinuto($dadosParGerEmpresa[0]->parger_hr_ini_fun);
                $diaSemEnd = Helper::formataHoraMinuto($dadosParGerEmpresa[0]->parger_hr_fin_fun);

                $usaSabado = true;

                if($dadosParGerEmpresa[0]->parger_hr_alt_sab == 'S'){
                
                    $sabadoStart = Helper::formataHoraMinuto($dadosParGerEmpresa[0]->parger_hr_ini_sab);
                    $sabadoEnd = Helper::formataHoraMinuto($dadosParGerEmpresa[0]->parger_hr_fin_sab);
                }else{
                    $sabadoStart = Helper::formataHoraMinuto($dadosParGerEmpresa[0]->parger_hr_ini_fun);
                    $sabadoEnd = Helper::formataHoraMinuto($dadosParGerEmpresa[0]->parger_hr_fin_fun);
                }

                $usaDomingo = false;
                $domingoStart = '00:00';
                $domingoEnd = '00:00';

            }else{
                $diaSemStart = Helper::formataHoraMinuto($dadosParGerEmpresa[0]->parger_hr_ini_fun);
                $diaSemEnd = Helper::formataHoraMinuto($dadosParGerEmpresa[0]->parger_hr_fin_fun);

                $usaSabado = true;

                if($dadosParGerEmpresa[0]->parger_hr_alt_sab == 'S'){
                
                    $sabadoStart = Helper::formataHoraMinuto($dadosParGerEmpresa[0]->parger_hr_ini_sab);
                    $sabadoEnd = Helper::formataHoraMinuto($dadosParGerEmpresa[0]->parger_hr_fin_sab);
                }else{
                    $sabadoStart = Helper::formataHoraMinuto($dadosParGerEmpresa[0]->parger_hr_ini_fun);
                    $sabadoEnd = Helper::formataHoraMinuto($dadosParGerEmpresa[0]->parger_hr_fin_fun);
                }

                $usaDomingo = true;

                if($dadosParGerEmpresa[0]->parger_hr_alt_dom == 'S'){
                
                    $domingoStart = Helper::formataHoraMinuto($dadosParGerEmpresa[0]->parger_hr_ini_dom);
                    $domingoEnd = Helper::formataHoraMinuto($dadosParGerEmpresa[0]->parger_hr_fin_dom);
                }else{
                    $domingoStart = Helper::formataHoraMinuto($dadosParGerEmpresa[0]->parger_hr_ini_fun);
                    $domingoEnd = Helper::formataHoraMinuto($dadosParGerEmpresa[0]->parger_hr_fin_fun);
                }
            }
        }else{

            $dadosTurno = DB::table('parametros_ger_turnos')->where('partur_emp', $request->empresa)->where('partur_cod', $dadosPrestador[0]->prestador_tur_cod)->get();

            if($dadosTurno[0]->partur_cod == 1){

                $diaSemStart = Helper::formataHoraMinuto($dadosTurno[0]->partur_hr_ini);
                $diaSemEnd = Helper::formataHoraMinuto($dadosTurno[0]->partur_hr_fin);

                $usaSabado = false;
                $usaDomingo = false;
                $sabadoStart = '00:00';
                $sabadoEnd = '00:00'; 
                $domingoStart = '00:00';
                $domingoEnd = '00:00';
            }elseif($dadosTurno[0]->partur_cod == 2){ 
                
                $diaSemStart = Helper::formataHoraMinuto($dadosTurno[0]->partur_hr_ini);
                $diaSemEnd = Helper::formataHoraMinuto($dadosTurno[0]->partur_hr_fin);

                $usaSabado = true;
                $sabadoStart = Helper::formataHoraMinuto($dadosTurno[0]->partur_hr_ini);
                $sabadoEnd = Helper::formataHoraMinuto($dadosTurno[0]->partur_hr_fin);

                $usaDomingo = false;
                $domingoStart = '00:00';
                $domingoEnd = '00:00';

            }else{
                $diaSemStart = Helper::formataHoraMinuto($dadosTurno[0]->partur_hr_ini);
                $diaSemEnd = Helper::formataHoraMinuto($dadosTurno[0]->partur_hr_fin);

                $usaSabado = true;
                $sabadoStart = Helper::formataHoraMinuto($dadosTurno[0]->partur_hr_ini);
                $sabadoEnd = Helper::formataHoraMinuto($dadosTurno[0]->partur_hr_fin);

                $usaDomingo = false;
                $domingoStart = Helper::formataHoraMinuto($dadosTurno[0]->partur_hr_ini);
                $domingoEnd = Helper::formataHoraMinuto($dadosTurno[0]->partur_hr_fin);
            }
        }

        //Gera as horas de almoço do funcionario
        if($dadosPrestador[0]->prestador_int_srv == 'S'){
            $usaIntSemana = true;
            $diaSemIntStart = Helper::formataHoraMinuto($dadosPrestador[0]->prestador_hr_ini_int);
            $diaSemIntEnd = Helper::formataHoraMinuto($dadosPrestador[0]->prestador_hr_fin_int);
        }else{
            $usaIntSemana = false;
            $diaSemIntStart = '00:00';
            $diaSemIntEnd = '00:00';
        }

        if($dadosPrestador[0]->prestador_int_srv_sab == 'S'){
            $usaIntSabado = true;
            $sabadoIntStart = Helper::formataHoraMinuto($dadosPrestador[0]->prestador_hr_ini_int_sab);
            $sabadoIntEnd = Helper::formataHoraMinuto($dadosPrestador[0]->prestador_hr_fin_int_sab);
        }else{
            $usaIntSabado = false;
            $sabadoIntStart = '00:00';
            $sabadoIntEnd = '00:00';
        }

        if($dadosPrestador[0]->prestador_int_srv_dom == 'S'){
            $usaIntDomingo = true;
            $domingoIntStart = Helper::formataHoraMinuto($dadosPrestador[0]->prestador_hr_ini_int_dom);
            $domingoIntEnd = Helper::formataHoraMinuto($dadosPrestador[0]->prestador_hr_fin_int_dom);
        }else{
            $usaIntDomingo = false;
            $domingoIntStart = '00:00';
            $domingoIntEnd = '00:00';
        }

        //Monta variavel dos dias e horas de serviço do calendário
        $businessHours = [
            'diasSemana' => [
                'start' => $diaSemStart,
                'end' => $diaSemEnd,
            ],
            'sabado' => [
                'start' => $sabadoStart,
                'end' => $sabadoEnd,
            ],
            'domingo' => [
                'start' => $domingoStart,
                'end' => $domingoEnd,
            ],
            'usa_sabado' => $usaSabado,
            'usa_domingo' => $usaDomingo,
        ];

        //Monta variavel dos intervalos dos dias de serviço
        $lunchBreaks = [
            'diasSemana' => [
                'start' => $diaSemIntStart,
                'end' => $diaSemIntEnd,
            ],
            'sabado' => [
                'start' => $sabadoIntStart,
                'end' => $sabadoIntEnd,
            ],
            'domingo' => [
                'start' => $domingoIntStart,
                'end' => $domingoIntEnd,
            ],
            'usa_semana' => $usaIntSemana,
            'usa_sabado' => $usaIntSabado,
            'usa_domingo' => $usaIntDomingo,
        ];

        //Busca pela view os dados de serviços relacionados ao prestador durante o periodo pesquisado
        $dadosTMO = DB::table('vi_lancamento_os_agenda_prt')
        ->where('empresa', $request->empresa)
        ->where('prestador', $prestador)
        ->where(function ($query) use ($dataIni, $dataFin) {
            $query->whereBetween('data_ini_servico', [$dataIni, $dataFin])
                  ->orWhereBetween('data_fin_servico', [$dataIni, $dataFin]);
        })
        ->orderBy('num_os')
        ->orderBy('requisicao')
        ->orderBy('sequencia')
        ->get();
        
        $eventosPrt = [];

        foreach ($dadosTMO as $tmo) {

            if($tmo->situacao == 'S'){
                $color = '#ffc107';
                $sts = 'Suspenso';
            }elseif($tmo->situacao == 'C'){
                $color = '#dc3545';
                $sts = 'Cancelado';
            }elseif($tmo->situacao == 'F'){
                $color = '#28a745';
                $sts = 'Finalizado';
            }elseif($tmo->situacao == 'A'){
                $color = '#17a2b8';
                $sts = 'Em Andamento';
            }else{
                $color = '#007bff';
                $sts = 'Em Espera';
            }
            
            $start = \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', Helper::formataDataHora($tmo->data_ini_servico.' '.Helper::formataHoraMinuto($tmo->hora_ini_servico).':00', 'America/Sao_Paulo'))->toIso8601String();
            $end = \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', Helper::formataDataHora($tmo->data_fin_servico.' '.Helper::formataHoraMinuto($tmo->hora_fin_servico).':00', 'America/Sao_Paulo'))->toIso8601String();

            if(empty($tmo->cmp_servico)){
                $complemento = ' ';
            }else{
                $complemento = $tmo->cmp_servico;
            }

            $eventosPrt[] = [
                'title' => $tmo->cod_servico.' - '.$tmo->desc_servico."\n".'OS: '.$tmo->num_os.' Tempo: '.Helper::convertHrCentToHrSexa($tmo->qtd_hora_servico),
                'start' => $start,
                'end' => $end,
                'empresa' => $tmo->empresa,
                'os' => $tmo->num_os,
                'req' => $tmo->requisicao,
                'seq' => $tmo->sequencia,
                'prestador' => $tmo->prestador,
                'empresaNome' => $dadosEmpresa[0]->empresa_nome,
                'prestadorNome'=> $dadosPrestador[0]->prestador_nome,
                'tmo' => $tmo->cod_servico.' - '.$tmo->desc_servico,
                'complemento' => $complemento,
                'duracao' => Helper::convertHrCentToHrSexa($tmo->qtd_hora_servico),
                'status' => $sts,
                'area' => $tmo->area,
                'setor' => $tmo->setor,
                'allDay' => false,
                'backgroundColor' => $color,
                'borderColor' => $color
            ];
        }

        //Busca as TMO em que prestador é auxiliar
        $dadosTMO = DB::table('vi_lancamento_os_agenda_prt_aux')
        ->where('empresa', $request->empresa)
        ->where('prestador', $prestador)
        ->where(function ($query) use ($dataIni, $dataFin) {
            $query->whereBetween('data_ini_servico', [$dataIni, $dataFin])
                  ->orWhereBetween('data_fin_servico', [$dataIni, $dataFin]);
        })
        ->orderBy('num_os')
        ->orderBy('requisicao')
        ->orderBy('sequencia')
        ->get();

        foreach ($dadosTMO as $tmo) {

            if($tmo->situacao == 'S'){
                $color = '#ffc107';
                $sts = 'Suspenso';
            }elseif($tmo->situacao == 'C'){
                $color = '#dc3545';
                $sts = 'Cancelado';
            }elseif($tmo->situacao == 'F'){
                $color = '#28a745';
                $sts = 'Finalizado';
            }elseif($tmo->situacao == 'A'){
                $color = '#17a2b8';
                $sts = 'Em Andamento';
            }else{
                $color = '#007bff';
                $sts = 'Em Espera';
            }
            
            $start = \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', Helper::formataDataHora($tmo->data_ini_servico.' '.Helper::formataHoraMinuto($tmo->hora_ini_servico).':00', 'America/Sao_Paulo'))->toIso8601String();
            $end = \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', Helper::formataDataHora($tmo->data_fin_servico.' '.Helper::formataHoraMinuto($tmo->hora_fin_servico).':00', 'America/Sao_Paulo'))->toIso8601String();

            if(empty($tmo->cmp_servico)){
                $complemento = ' ';
            }else{
                $complemento = $tmo->cmp_servico;
            }

            $eventosPrt[] = [
                'title' => $tmo->cod_servico.' - '.$tmo->desc_servico."\n".'OS: '.$tmo->num_os.' Tempo: '.Helper::convertHrCentToHrSexa($tmo->qtd_hora_servico),
                'start' => $start,
                'end' => $end,
                'empresa' => $tmo->empresa,
                'os' => $tmo->num_os,
                'req' => $tmo->requisicao,
                'seq' => $tmo->sequencia,
                'prestador' => $tmo->prestador,
                'empresaNome' => $dadosEmpresa[0]->empresa_nome,
                'prestadorNome'=> $dadosPrestador[0]->prestador_nome,
                'tmo' => $tmo->cod_servico.' - '.$tmo->desc_servico,
                'complemento' => $complemento,
                'duracao' => Helper::convertHrCentToHrSexa($tmo->qtd_hora_servico),
                'status' => $sts,
                'area' => $tmo->area,
                'setor' => $tmo->setor,
                'allDay' => false,
                'backgroundColor' => $color,
                'borderColor' => $color
            ];
        }

        return view('/lancamentos/producao/calendarioAgendamentoPrestador',[
            'empresa' => $request->empresa, 
            'prestador' => $prestador, 
            'dadosPrestador' => $dadosPrestador, 
            'dadosEmpresa' => $dadosEmpresa, 
            'dadosParGerEmpresa' => $dadosParGerEmpresa,
            'dadosParSrvEmpresa' => $dadosParSrvEmpresa,
            'dataIniAge' => $dataIni,
            'dataFinAge' => $dataFin,
            'dataFinAgeCalendar' => $dataFinCalendar,
            'horaMinima' => $horaMinima,
            'horaMaxima' => $horaMaxima,
            'eventosPrt' => $eventosPrt], 
            compact('businessHours','lunchBreaks','eventosPrt'));
    }
}
