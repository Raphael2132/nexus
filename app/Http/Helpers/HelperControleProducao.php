<?php

namespace App\Http\Helpers;
use Illuminate\Support\Facades\DB;
use stdClass;
use DateTime;
use DateTimeZone;

class HelperControleProducao
{
    //Gera a span de intervalo/almoço do prestador do painel de produção dos prestadores
    public static function geraSpanIntPrt($horario, $prestador, $data, $empresa)
    {  

        // Inicializa o background
        $backgroundInt = '';
        $leftInt = '';
        $rightInt = '';
        $span = '';

        //Busca o código do dia da semana pesquisado 
        $dateTime = new DateTime($data);
        $codigoDiaSemana = $dateTime->format('N'); // 'N' retorna um número de 1 (segunda-feira) a 7 (domingo)

        $dadosPrt = DB::table('cadastro_prestadores')->where('prestador_codigo', $prestador)->where('prestador_empresa',$empresa)->first();
        $dadosParGerEmpresa = DB::table('parametros_ger_empresas')->where('parger_emp', $empresa)->first();

        if($dadosPrt->prestador_tur_cod == '1'){

            if($codigoDiaSemana == '1' || $codigoDiaSemana == '2' || $codigoDiaSemana == '3' || $codigoDiaSemana == '4' || $codigoDiaSemana == '5'){

                if($dadosPrt->prestador_int_srv == 'S'){
                    $inicioInt = strtotime(Helper::formataHoraMinuto($dadosPrt->prestador_hr_ini_int));
                    $finInt = strtotime(Helper::formataHoraMinuto($dadosPrt->prestador_hr_fin_int));
                }else{
                    return $span;
                }

            }elseif($codigoDiaSemana == '6'){

                if($dadosPrt->prestador_int_srv_sab == 'S'){
                    $inicioInt = strtotime(Helper::formataHoraMinuto($dadosPrt->prestador_hr_ini_int_sab));
                    $finInt = strtotime(Helper::formataHoraMinuto($dadosPrt->prestador_hr_fin_int_sab));
                }else{
                    return $span;
                }
                
            }else{

                if($dadosPrt->prestador_int_srv_dom == 'S'){
                    $inicioInt = strtotime(Helper::formataHoraMinuto($dadosPrt->prestador_hr_ini_int_dom));
                    $finInt = strtotime(Helper::formataHoraMinuto($dadosPrt->prestador_hr_fin_int_dom));
                }else{
                    return $span;
                }
            }

        }else{
            $dadosTurno = DB::table('parametros_ger_turnos')->where('partur_emp', $empresa)->where('partur_cod', $prestador_tur_cod)->first();
            
            if(($codigoDiaSemana == '1' || $codigoDiaSemana == '2' || $codigoDiaSemana == '3' || $codigoDiaSemana == '4' || $codigoDiaSemana == '5')){

                if($dadosPrt->prestador_int_srv == 'S'){
                    $inicioInt = strtotime(Helper::formataHoraMinuto($dadosPrt->prestador_hr_ini_int));
                    $finInt = strtotime(Helper::formataHoraMinuto($dadosPrt->prestador_hr_fin_int));
                }else{
                    return $span;
                }

            }elseif($codigoDiaSemana == '6' && ($dadosTurno->partur_dia == '2' || $dadosTurno->partur_dia == '3')){

                if($dadosPrt->prestador_int_srv_sab == 'S'){
                    $inicioInt = strtotime(Helper::formataHoraMinuto($dadosPrt->prestador_hr_ini_int_sab));
                    $finInt = strtotime(Helper::formataHoraMinuto($dadosPrt->prestador_hr_fin_int_sab));
                }else{
                    return $span;
                }
                
            }elseif($codigoDiaSemana == '7' && $dadosTurno->partur_dia == '3'){

                if($dadosPrt->prestador_int_srv_dom == 'S'){
                    $inicioInt = strtotime(Helper::formataHoraMinuto($dadosPrt->prestador_hr_ini_int_dom));
                    $finInt = strtotime(Helper::formataHoraMinuto($dadosPrt->prestador_hr_fin_int_dom));
                }else{
                    return $span;
                }

            }else{
                return $span;
            }
        }

        // Horário da coluna
        $horaColunaIni = strtotime($horario);
        $horaColunaFin = strtotime('+29 minutes', $horaColunaIni);

        // Verifica se a coluna está dentro do intervalo do prestador
        if ($horaColunaFin < $inicioInt || $horaColunaIni > $finInt) {
            // Se a coluna está fora do intervalo, não aplica background
            $backgroundInt = '';
        } else {
            // Verifica se o intervalo do prestador cobre totalmente a coluna
            if ($inicioInt <= $horaColunaIni && $finInt >= $horaColunaFin) {

                $backgroundInt = 'int'; // Coluna inteira
                $widthPercent = 100;
            }
            // Verifica se o intervalo do prestador começa antes e termina dentro da coluna
            elseif ($inicioInt <= $horaColunaIni && $finInt > $horaColunaIni && $finInt <= $horaColunaFin) {

                $difMin = ($finInt - $horaColunaIni) / 60;
                $fracaoCol = (100 / 30) * $difMin;
                $backgroundInt = 'dir'; // Metade direita da coluna
                $widthPercent = $fracaoCol;
            }
            // Verifica se o intervalo do prestador começa dentro da coluna e termina depois
            elseif ($inicioInt >= $horaColunaIni && $finInt >= $horaColunaFin && $inicioInt < $horaColunaFin) {

                $difMin = ($horaColunaFin - $inicioInt) / 60;
                $fracaoCol = (100 / 30) * $difMin;
                $backgroundInt = 'esq'; // Metade esquerda da coluna
                $widthPercent = $fracaoCol;
            }
            // Verifica se o intervalo do prestador começa dentro da coluna e termina depois
            elseif ($inicioInt >= $horaColunaIni && $finInt <= $horaColunaFin) {

                $difMinIni = ($inicioInt - $horaColunaIni) / 60;
                $difMinFin = ($finInt - $horaColunaIni) / 60;
                $totMin = $difMinFin - $difMinIni;
                $fracaoCol = (100 / 30) * $totMin;
                $backgroundInt = 'center';
                $widthPercent = $fracaoCol;
                $leftInt = ($difMinIni * (100 / 30)) . '%'; // Conversão para percentual
                $rightInt = (($horaColunaFin - $finInt) / 60) * (100 / 30) . '%'; // Conversão para percentual
            }
        }

        if($backgroundInt == 'int'){
            $span = '<span class="event-lunch-int" style="width: '.$widthPercent.'%;">Intervalo</span>';
        }elseif($backgroundInt == 'esq'){
            $span = '<span class="event-lunch-esq" style="width: '.$widthPercent.'%;">Intervalo</span>';
        }elseif($backgroundInt == 'dir'){
            $span = '<span class="event-lunch-dir" style="width: '.$widthPercent.'%;">Intervalo</span>';
        }elseif($backgroundInt == 'center'){
            $span = '<span class="event-lunch-center" style="left: '.$leftInt.'; right: '.$rightInt.'; width: '.$widthPercent.'%;">Intervalo</span>';
        }

        return $span;
    }

    //Calcula a duração real do serviço com base nas datas e horas de inicio e termino da TMO - Retorna a qtd. de horas no formato centesimal
    public static function calculaDuracaoServico($startDateTime, $endDateTime, $businessHours, $lunchBreaks)
    {
        $totalDuration = 0;

        $currentDateTime = clone $startDateTime;

        while ($currentDateTime < $endDateTime) {
            $currentDayOfWeek = $currentDateTime->format('w'); // 0 (domingo) até 6 (sábado)
            $currentBusinessHours = $businessHours[$currentDayOfWeek] ?? null;
            $currentLunchBreak = $lunchBreaks[$currentDayOfWeek] ?? null;

            $usaSemana = $lunchBreaks['usa_semana'] && ($currentDayOfWeek >= 1 && $currentDayOfWeek <= 5);
            $usaSabado = $businessHours['usa_sabado'] && $currentDayOfWeek == 6;
            $usaDomingo = $businessHours['usa_domingo'] && $currentDayOfWeek == 0;

            if ($currentBusinessHours && ($usaSemana || $usaSabado || $usaDomingo)) {

                // Definindo início e fim do expediente do dia atual
                $workDayStart = clone $currentDateTime;
                $workDayStart->setTime(
                    (int) explode(':', $currentBusinessHours['start'])[0],
                    (int) explode(':', $currentBusinessHours['start'])[1],
                    0
                );

                $workDayEnd = clone $currentDateTime;
                $workDayEnd->setTime(
                    (int) explode(':', $currentBusinessHours['end'])[0],
                    (int) explode(':', $currentBusinessHours['end'])[1],
                    0
                );

                // Verifica se o serviço termina antes do início do próximo expediente
                if ($endDateTime <= $workDayStart) {
                    break; // O serviço terminou antes do expediente começar, nenhum tempo deve ser adicionado
                }

                // Ajusta o horário de início para o início do expediente se necessário
                if ($currentDateTime < $workDayStart) {
                    $currentDateTime = clone $workDayStart;
                }

                // Calcula a duração do trabalho considerando o intervalo de almoço
                if ($currentDateTime < $workDayEnd) {
                    $endOfCurrentInterval = min($endDateTime, $workDayEnd);

                    // Exclui o tempo de almoço se aplicável
                    if (isset($currentLunchBreak) && ($currentDateTime < $currentLunchBreak['end'] && $endOfCurrentInterval > $currentLunchBreak['start'])) {
                        $lunchStart = new DateTime($currentLunchBreak['start']);
                        $lunchEnd = new DateTime($currentLunchBreak['end']);

                        if ($currentDateTime < $lunchStart && $endOfCurrentInterval > $lunchEnd) {
                            // O período de almoço ocorre completamente dentro do intervalo atual
                            $totalDuration += ($lunchStart->getTimestamp() - $currentDateTime->getTimestamp());
                            $totalDuration += ($endOfCurrentInterval->getTimestamp() - $lunchEnd->getTimestamp());
                        } elseif ($currentDateTime < $lunchStart) {
                            // O serviço começa antes do almoço e termina durante ou depois do almoço
                            $totalDuration += ($lunchStart->getTimestamp() - $currentDateTime->getTimestamp());
                        } elseif ($currentDateTime < $lunchEnd) {
                            // O serviço começa durante o almoço
                            $totalDuration += ($endOfCurrentInterval->getTimestamp() - $lunchEnd->getTimestamp());
                        } else {
                            // O serviço começa depois do almoço
                            $totalDuration += ($endOfCurrentInterval->getTimestamp() - $currentDateTime->getTimestamp());
                        }
                    } else {
                        // Se não houver intervalo de almoço aplicável
                        $totalDuration += ($endOfCurrentInterval->getTimestamp() - $currentDateTime->getTimestamp());
                    }
                }

                $currentDateTime->modify('+1 day')->setTime(0, 0, 0);
            } else {
                // Se for um dia fora do expediente, avança para o próximo dia
                $currentDateTime->modify('+1 day')->setTime(0, 0, 0);
            }
        }

        // Converte a duração total de segundos para horas centesimais
        $totalDurationHours = $totalDuration / 3600;
        $hours = floor($totalDurationHours);
        $minutes = ($totalDurationHours - $hours) * 60;
        $totalDurationHours = $hours + round($minutes / 60, 2);

        return number_format($totalDurationHours, 2, '.', '');
    }

    //Gera os arrays com os horarios de expediente do Prestador
    public static function geraBusinessHoursPHP($empresa, $prestador)
    {
        $dadosPrestador = DB::table('cadastro_prestadores')->where('prestador_codigo', $prestador)->where('prestador_empresa', $empresa)->get();
        $dadosParGerEmpresa = DB::table('parametros_ger_empresas')->where('parger_emp', $empresa)->get();

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

        $businessHours = [
            0 => ['start' => $domingoStart, 'end' => $domingoEnd], // Domingo
            1 => ['start' => $diaSemStart, 'end' => $diaSemEnd], // Segunda-feira
            2 => ['start' => $diaSemStart, 'end' => $diaSemEnd], // Terça-feira
            3 => ['start' => $diaSemStart, 'end' => $diaSemEnd], // Quarta-feira
            4 => ['start' => $diaSemStart, 'end' => $diaSemEnd], // Quinta-feira
            5 => ['start' => $diaSemStart, 'end' => $diaSemEnd], // Sexta-feira
            6 => ['start' => $sabadoStart, 'end' => $sabadoEnd], // Sabado
            'usa_sabado' => $usaSabado,
            'usa_domingo' => $usaDomingo,
        ];

        return $businessHours;
    }

    //Gera os arrays com os horarios de expediente da Empresa
    public static function geraBusinessHoursEmpPHP($empresa)
    {
        $dadosParGerEmpresa = DB::table('parametros_ger_empresas')->where('parger_emp', $empresa)->get();

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

        $businessHours = [
            0 => ['start' => $domingoStart, 'end' => $domingoEnd], // Domingo
            1 => ['start' => $diaSemStart, 'end' => $diaSemEnd], // Segunda-feira
            2 => ['start' => $diaSemStart, 'end' => $diaSemEnd], // Terça-feira
            3 => ['start' => $diaSemStart, 'end' => $diaSemEnd], // Quarta-feira
            4 => ['start' => $diaSemStart, 'end' => $diaSemEnd], // Quinta-feira
            5 => ['start' => $diaSemStart, 'end' => $diaSemEnd], // Sexta-feira
            6 => ['start' => $sabadoStart, 'end' => $sabadoEnd], // Sabado
            'usa_sabado' => $usaSabado,
            'usa_domingo' => $usaDomingo,
        ];

        return $businessHours;
    }

    //Gera os arrays com os horarios de intervalo/almoço do Prestador
    public static function geraLunchHoursPHP($empresa, $prestador)
    {
        $dadosPrestador = DB::table('cadastro_prestadores')->where('prestador_codigo', $prestador)->where('prestador_empresa', $empresa)->get();

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

        $lunchBreaks = [
            0 => ['start' => $domingoIntStart, 'end' => $domingoIntEnd], // Domingo
            1 => ['start' => $diaSemIntStart, 'end' => $diaSemIntEnd], // Segunda-feira
            2 => ['start' => $diaSemIntStart, 'end' => $diaSemIntEnd], // Terça-feira
            3 => ['start' => $diaSemIntStart, 'end' => $diaSemIntEnd], // Quarta-feira
            4 => ['start' => $diaSemIntStart, 'end' => $diaSemIntEnd], // Quinta-feira
            5 => ['start' => $diaSemIntStart, 'end' => $diaSemIntEnd], // Sexta-feira
            6 => ['start' => $sabadoIntStart, 'end' => $sabadoIntEnd], // Sabado
            'usa_semana' => $usaIntSemana,
            'usa_sabado' => $usaIntSabado,
            'usa_domingo' => $usaIntDomingo,
        ];

        return $lunchBreaks;
    }

    //Gera os arrays com os horarios de intervalo/almoço da Empresa
    public static function geraLunchHoursEmpPHP($empresa)
    {
        $dadosParGerEmpresa = DB::table('parametros_ger_empresas')->where('parger_emp', $empresa)->get();

        //Gera as horas de almoço do funcionario
        if($dadosParGerEmpresa[0]->parger_int_fun == 'S'){
            $usaIntSemana = true;
            $diaSemIntStart = Helper::formataHoraMinuto($dadosParGerEmpresa[0]->parger_hr_ini_int);
            $diaSemIntEnd = Helper::formataHoraMinuto($dadosParGerEmpresa[0]->parger_hr_fin_int);
        }else{
            $usaIntSemana = false;
            $diaSemIntStart = '00:00';
            $diaSemIntEnd = '00:00';
        }

        if($dadosParGerEmpresa[0]->parger_int_sab == 'S'){
            $usaIntSabado = true;
            $sabadoIntStart = Helper::formataHoraMinuto($dadosParGerEmpresa[0]->parger_hr_ini_int_sab);
            $sabadoIntEnd = Helper::formataHoraMinuto($dadosParGerEmpresa[0]->parger_hr_fin_int_sab);
        }else{
            $usaIntSabado = false;
            $sabadoIntStart = '00:00';
            $sabadoIntEnd = '00:00';
        }

        if($dadosParGerEmpresa[0]->parger_int_dom == 'S'){
            $usaIntDomingo = true;
            $domingoIntStart = Helper::formataHoraMinuto($dadosParGerEmpresa[0]->parger_hr_ini_int_dom);
            $domingoIntEnd = Helper::formataHoraMinuto($dadosParGerEmpresa[0]->parger_hr_fin_int_dom);
        }else{
            $usaIntDomingo = false;
            $domingoIntStart = '00:00';
            $domingoIntEnd = '00:00';
        }

        $lunchBreaks = [
            0 => ['start' => $domingoIntStart, 'end' => $domingoIntEnd], // Domingo
            1 => ['start' => $diaSemIntStart, 'end' => $diaSemIntEnd], // Segunda-feira
            2 => ['start' => $diaSemIntStart, 'end' => $diaSemIntEnd], // Terça-feira
            3 => ['start' => $diaSemIntStart, 'end' => $diaSemIntEnd], // Quarta-feira
            4 => ['start' => $diaSemIntStart, 'end' => $diaSemIntEnd], // Quinta-feira
            5 => ['start' => $diaSemIntStart, 'end' => $diaSemIntEnd], // Sexta-feira
            6 => ['start' => $sabadoIntStart, 'end' => $sabadoIntEnd], // Sabado
            'usa_semana' => $usaIntSemana,
            'usa_sabado' => $usaIntSabado,
            'usa_domingo' => $usaIntDomingo,
        ];

        return $lunchBreaks;
    }

    //Calcula a Data/Hora da previsão de término da TMO/OS pela Data/Hora e Duração da TMO/OS
    public static function calculaPrevTerminoSrv($startDate, $startHour, $duration, $businessHours, $lunchBreaks) {
        // Converte a data de início e hora de início em um objeto DateTime
        $startDateTime = new DateTime($startDate . ' ' . $startHour);
    
        // Converte a duração prevista em segundos
        $durationSeconds = strtotime($duration) - strtotime('TODAY');
    
        // Obtém o dia da semana (0 = Domingo, 6 = Sábado)
        $currentDay = $startDateTime->format('w');
    
        // Localiza os horários de expediente do dia atual
        $currentBusinessHours = $businessHours[$currentDay];
    
        // Define o início e o fim do expediente do dia atual
        $startOfDay = new DateTime($startDate);
        list($startHour, $startMinute) = explode(':', $currentBusinessHours['start']);
        $startOfDay->setTime($startHour, $startMinute, 0);
        
        $endOfDay = new DateTime($startDate);
        list($endHour, $endMinute) = explode(':', $currentBusinessHours['end']);
        $endOfDay->setTime($endHour, $endMinute, 0);
    
        // Se o serviço começar fora do expediente, ajusta o início para o próximo horário de expediente
        if ($startDateTime < $startOfDay) {
            $startDateTime = $startOfDay;
        } elseif ($startDateTime >= $endOfDay) {
            // Avança para o próximo dia útil
            do {
                $startDateTime->modify('+1 day');
                $currentDay = $startDateTime->format('w');
                $currentBusinessHours = $businessHours[$currentDay];
                list($startHour, $startMinute) = explode(':', $currentBusinessHours['start']);
                $startOfDay = clone $startDateTime;
                $startOfDay->setTime($startHour, $startMinute, 0);
                list($endHour, $endMinute) = explode(':', $currentBusinessHours['end']);
                $endOfDay = clone $startDateTime;
                $endOfDay->setTime($endHour, $endMinute, 0);
            } while ($startOfDay >= $endOfDay);  // Continua até encontrar um dia com expediente
            $startDateTime = $startOfDay;
        }
    
        // Calcula o horário de término considerando o almoço apenas se o evento atravessar o intervalo de almoço
        $currentLunchBreak = $lunchBreaks[$currentDay];
        if ($currentLunchBreak['start'] !== '00:00' && $currentLunchBreak['end'] !== '00:00') {
            $lunchStart = new DateTime($startDateTime->format('Y-m-d') . ' ' . $currentLunchBreak['start']);
            $lunchEnd = new DateTime($startDateTime->format('Y-m-d') . ' ' . $currentLunchBreak['end']);
    
            // Verifica se o evento atravessa o horário de almoço
            $eventEndDateTime = clone $startDateTime;
            $eventEndDateTime->modify("+{$durationSeconds} seconds");
    
            if ($startDateTime < $lunchEnd && $eventEndDateTime > $lunchStart) {
                // Calcula a duração do evento considerando o almoço
                $lunchDurationSeconds = $lunchEnd->getTimestamp() - $lunchStart->getTimestamp();
                $durationSeconds += $lunchDurationSeconds;
            }
        }
    
        // Define a data e hora final considerando a duração
        $endDateTime = clone $startDateTime;
        $endDateTime->modify("+{$durationSeconds} seconds");
    
        // Se o término for após o expediente, move para o próximo dia útil
        while ($endDateTime > $endOfDay) {
            $remainingDuration = $endDateTime->getTimestamp() - $endOfDay->getTimestamp();
    
            // Avança para o próximo dia útil
            do {
                $startDateTime->modify('+1 day');
                $currentDay = $startDateTime->format('w');
                $currentBusinessHours = $businessHours[$currentDay];
                list($startHour, $startMinute) = explode(':', $currentBusinessHours['start']);
                $startOfDay = clone $startDateTime;
                $startOfDay->setTime($startHour, $startMinute, 0);
                list($endHour, $endMinute) = explode(':', $currentBusinessHours['end']);
                $endOfDay = clone $startDateTime;
                $endOfDay->setTime($endHour, $endMinute, 0);
            } while ($startOfDay >= $endOfDay);  // Continua até encontrar um dia com expediente
    
            $endDateTime = $startOfDay->modify("+{$remainingDuration} seconds");
        }
    
        return $endDateTime->format('Y-m-d H:i:s');
    }
}