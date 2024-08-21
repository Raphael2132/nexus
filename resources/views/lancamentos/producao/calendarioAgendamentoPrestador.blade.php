@extends('adminlte::page')

@section('title', 'Painel de Agendamento')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h4 style="margin-bottom: 0px !important;">Painel de Agendamento</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">
                    <a href="{{route('home.agendamentoPrt')}}">Filtro Agendamento</a>
                </li>
                <li class="breadcrumb-item active">Calendário Agendamento</li>
            </ol>
        </div>
    </div>
@stop


@section('content')
@php 
    $dadosArea = DB::table('parametros_sistema_areas')->where('area_codigo',$dadosPrestador[0]->prestador_are)->get();
    $dadosSetor = DB::table('parametros_srv_setores')->where('setor_empresa',$empresa)->where('setor_area',$dadosPrestador[0]->prestador_are)->where('setor_codigo',$dadosPrestador[0]->prestador_set)->get();
    $empresaNome = $dadosEmpresa[0]->empresa_nome;
    $prestadorNome = $dadosPrestador[0]->prestador_nome;
@endphp
<div class="row">
    <div class="col-md-3">
        <div class="sticky-top mb-3">

            <x-adminlte-card title="Dados do Prestador" theme="lightblue" theme-mode="outline" icon="fa-solid fa-user" header-class="text-uppercase rounded-bottom border-info" collapsible removable>
                <div class="text-muted">
                    <div class="row">
                        <p class="text-sm col-md-6">Empresa
                            <b class="d-block">{{$empresa.' - '.$dadosEmpresa[0]->empresa_nome}}</b>
                        </p>
                        <p class="text-sm col-md-6">Prestador
                            <b class="d-block">{{$prestador.' - '.$dadosPrestador[0]->prestador_nome}}</b>
                        </p>
                    </div>
                    <div class="row">
                        <p class="text-sm col-md-6">Área
                            <b class="d-block">{{$dadosPrestador[0]->prestador_are.' - '.$dadosArea[0]->area_desc}}</b>
                        </p>
                        <p class="text-sm col-md-6">Setor
                            <b class="d-block">{{$dadosPrestador[0]->prestador_set.' - '.$dadosSetor[0]->setor_desc}}</b>
                        </p>
                    </div>
                    <div class="row">
                        <p class="text-sm col-md-6">Data de Agendamento
                            <b class="d-block">de {{Helper::formataData($dataIniAge).' até '.Helper::formataData($dataFinAge)}}</b>
                        </p>
                    </div>
                </div>
            </x-adminlte-card>
            
            <x-adminlte-card title="TMO Alocadas ao Prestador" theme="lightblue" theme-mode="outline" icon="fa-solid fa-people-carry-box" header-class="text-uppercase rounded-bottom border-info" collapsible removable>
                <!-- the events -->
                <div id="external-events-prt">
                    @php
                        $dadosEvePrt = DB::table('lancamento_srv_exe_tarefas')
                                        ->where('exetrf_emp', $empresa)
                                        ->where('exetrf_prt', $prestador)
                                        ->where('exetrf_sts', 'E')
                                        ->where('exetrf_age', 'N')
                                        ->where('exetrf_ths', '<>', 'T')
                                        ->wherenull('exetrf_dt_ini_srv')
                                        ->wherenull('exetrf_dt_age_tmo')
                                        ->orderby('exetrf_nos', 'asc')
                                        ->orderby('exetrf_req', 'asc')
                                        ->orderby('exetrf_seq', 'asc')
                                        ->get();
                    @endphp

                    @foreach($dadosEvePrt as $tmo) 
                    @php 
                        if($tmo->exetrf_sts == 'S'){
                            $sts = 'Suspenso';
                        }elseif($tmo->exetrf_sts == 'C'){
                            $sts = 'Cancelado';
                        }elseif($tmo->exetrf_sts == 'F'){
                            $sts = 'Finalizado';
                        }elseif($tmo->exetrf_sts == 'A'){
                            $sts = 'Em Andamento';
                        }else{
                            $sts = 'Em Espera';
                        }

                        if(empty($tmo->exetrf_cmp)){
                            $complemento = ' ';
                        }else{
                            $complemento = $tmo->exetrf_cmp;
                        }
                    @endphp
                    <div class="external-event bg-olive" 
                        data-empresa="{{ $tmo->exetrf_emp }}" 
                        data-os="{{ $tmo->exetrf_nos }}" 
                        data-req="{{ $tmo->exetrf_req }}" 
                        data-seq="{{ $tmo->exetrf_seq }}"
                        data-prestador="{{ $prestador }}"
                        data-empresaNome="{{  $empresaNome }}"
                        data-prestadorNome="{{  $prestadorNome }}"
                        data-tmo="{{  $tmo->exetrf_tmo.' - '.$tmo->exetrf_desc }}"
                        data-complemento="{{  $complemento }}"
                        data-duracao="{{  Helper::convertHrCentToHrSexa($tmo->exetrf_qhr) }}"
                        data-status="{{  $sts }}"
                        data-area="{{  $tmo->exetrf_are }}"
                        data-setor="{{  $tmo->exetrf_set }}"
                        data-duration="{{ Helper::convertHrCentToHrSexa($tmo->exetrf_qhr) }}">
                        <span class="text-sm">{{ $tmo->exetrf_tmo.' - '.$tmo->exetrf_desc }}
                            <b class="d-block">{{ 'OS: '.$tmo->exetrf_nos }} - {{'Tempo: '.Helper::convertHrCentToHrSexa($tmo->exetrf_qhr) }}</b>
                        </span>
                    </div>
                    @endforeach
                </div>
            </x-adminlte-card>

            <x-adminlte-card title="TMO sem Prestador Alocado" theme="lightblue" theme-mode="outline" icon="fa-solid fa-user-slash" header-class="text-uppercase rounded-bottom border-info" collapsible removable>
                <!-- the events -->
                <div id="external-events-sem-prt">
                    @php
                        $dadosEve = DB::table('lancamento_srv_exe_tarefas')
                                        ->where('exetrf_emp', $empresa)
                                        ->wherenull('exetrf_prt')
                                        ->where('exetrf_sts', 'E')
                                        ->where('exetrf_age', 'N')
                                        ->where('exetrf_are', $dadosPrestador[0]->prestador_are)
                                        ->where('exetrf_set', $dadosPrestador[0]->prestador_set)
                                        ->where('exetrf_ths', '<>', 'T')
                                        ->wherenull('exetrf_dt_ini_srv')
                                        ->wherenull('exetrf_dt_age_tmo')
                                        ->orderby('exetrf_nos', 'asc')
                                        ->orderby('exetrf_req', 'asc')
                                        ->orderby('exetrf_seq', 'asc')
                                        ->get();
                    @endphp

                    @foreach($dadosEve as $tmo) 
                    @php 
                        if($tmo->exetrf_sts == 'S'){
                            $sts = 'Suspenso';
                        }elseif($tmo->exetrf_sts == 'C'){
                            $sts = 'Cancelado';
                        }elseif($tmo->exetrf_sts == 'F'){
                            $sts = 'Finalizado';
                        }elseif($tmo->exetrf_sts == 'A'){
                            $sts = 'Em Andamento';
                        }else{
                            $sts = 'Em Espera';
                        }

                        if(empty($tmo->exetrf_cmp)){
                            $complemento = ' ';
                        }else{
                            $complemento = $tmo->exetrf_cmp;
                        }
                    @endphp
                    <div class="external-event bg-purple" 
                        data-empresa="{{ $tmo->exetrf_emp }}" 
                        data-os="{{ $tmo->exetrf_nos }}" 
                        data-req="{{ $tmo->exetrf_req }}" 
                        data-seq="{{ $tmo->exetrf_seq }}"
                        data-prestador="{{ $prestador }}"
                        data-empresaNome="{{  $empresaNome }}"
                        data-prestadorNome="{{  $prestadorNome }}"
                        data-tmo="{{  $tmo->exetrf_tmo.' - '.$tmo->exetrf_desc }}"
                        data-complemento="{{  $complemento }}"
                        data-duracao="{{  Helper::convertHrCentToHrSexa($tmo->exetrf_qhr) }}"
                        data-status="{{  $sts }}"
                        data-area="{{  $tmo->exetrf_are }}"
                        data-setor="{{  $tmo->exetrf_set }}"
                        data-duration="{{ Helper::convertHrCentToHrSexa($tmo->exetrf_qhr) }}">
                        <span class="text-sm">{{$tmo->exetrf_tmo.' - '.$tmo->exetrf_desc}}
                            <b class="d-block">{{ 'OS: '.$tmo->exetrf_nos }} - {{'Tempo: '.Helper::convertHrCentToHrSexa($tmo->exetrf_qhr) }}</b>
                        </span>
                    </div>
                    @endforeach
                </div>
            </x-adminlte-card>

            <x-adminlte-card title="Legenda" theme="lightblue" theme-mode="outline" icon="fa-solid fa-closed-captioning" header-class="text-uppercase rounded-bottom border-info" removable>
                <div class="">
                    <h6 class="text-secondary font-weight-bold">TMO Agendadas</h6>
                </div>
                <div class="btn-group d-flex justify-content-center" style="width: 100%; margin-bottom: 10px;">
                    <ul class="fc-color-picker" id="color-chooser" style="list-style: none; padding: 0;">
                        <li style="display: inline-block; margin-right: 10px; text-align: center;">
                            <a class="text-primary" href="#"><i class="fas fa-square"></i></a>
                            <div style="font-size: 12px;">TMO Em espera</div>
                        </li>
                        <li style="display: inline-block; margin-right: 10px; text-align: center;">
                            <a class="text-warning" href="#"><i class="fas fa-square"></i></a>
                            <div style="font-size: 12px;">TMO Suspensa</div>
                        </li>
                        <li style="display: inline-block; margin-right: 10px; text-align: center;">
                            <a class="text-success" href="#"><i class="fas fa-square"></i></a>
                            <div style="font-size: 12px;">TMO Finalizada</div>
                        </li>
                        <li style="display: inline-block; margin-right: 10px; text-align: center;">
                            <a class="text-danger" href="#"><i class="fas fa-square"></i></a>
                            <div style="font-size: 12px;">TMO Cancelada</div>
                        </li>
                        <li style="display: inline-block; text-align: center;">
                            <a class="text-info" href="#"><i class="fas fa-square"></i></a>
                            <div style="font-size: 12px;">TMO Em Andamento</div>
                        </li>
                    </ul>
                </div>
                <div class="">
                    <h6 class="text-secondary font-weight-bold">Informações do Calendário</h6>
                </div>
                <div class="btn-group d-flex justify-content-center" style="width: 100%; margin-bottom: 10px;">
                    <ul class="fc-color-picker" id="color-chooser" style="list-style: none; padding: 0;">
                        <li style="display: inline-block; text-align: center;">
                            <a style="color: #ffc107;" href="#"><i class="fas fa-square"></i></a>
                            <div style="font-size: 12px;">Intervalo / Almoço</div>
                        </li>
                        <li style="display: inline-block; text-align: center;">
                            <a style="color: #f3f3f3;" href="#"><i class="fas fa-square"></i></a>
                            <div style="font-size: 12px;">Fora do Expediente</div>
                        </li>
                        <li style="display: inline-block; text-align: center;">
                            <a style="color: #fffadf;" href="#"><i class="fas fa-square"></i></a>
                            <div style="font-size: 12px;">Dia Atual</div>
                        </li>
                    </ul>
                </div>
            </x-adminlte-card>        
        </div>
    </div>
    <!-- /.col -->
    <div class="col-md-9">
        <x-adminlte-card>
            <!-- THE CALENDAR -->
            <div id="calendar"></div>
            </div>
            <!-- /.card-body -->
        </x-adminlte-card>

        <x-adminlte-modal id="eventModal" title="Detalhes da TMO Agendada" icon="fas fa-calendar-alt"  size="xl" theme="navy" v-centered scrollable>
            <div id="modalContent">
                <!-- Conteúdo do modal será carregado aqui -->
            </div>
            <x-slot name="footerSlot">
                <x-adminlte-button theme="info" label="Voltar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>

        <x-adminlte-button id="openModalButton" label="Open Modal" data-toggle="modal" data-target="#eventModal" class="d-none bg-teal"/>
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
@stop

@section('plugins.Fullcalendar', true)
@section('plugins.Moment', true)
@section('plugins.Jquery-ui', true)

@section('css')
<style>
    /* Ensure consistent height for all time slots */
    .fc-timegrid-slot {
        height: 2em !important;
    }
    .fc-bg-event {
        background-color: #ffc107 !important; /* Cor do evento de fundo */
        color: #fff !important; /* Cor do texto */
        text-align: center;
        line-height: 2em;
        font-size: 14pt;
        font-weight: bold;
    }
</style>
@stop

@section('js')
<!-- Page specific script -->
<script>

$(function () {

    /* ******************** Funções Auxiliares ******************** */

    // Dados de businessHours vindo do controller
    var eventosPrt = @json($eventosPrt);

    console.log(eventosPrt);

    // Dados de businessHours vindo do controller
    var businessHours = @json($businessHours);

    // Função para obter os businessHours configurados (Horarios de Expediente do Prestador)
    function getBusinessHours() {
        var hours = [
            {
                daysOfWeek: [1, 2, 3, 4, 5], // Segunda a sexta
                startTime: businessHours.diasSemana.start,
                endTime: businessHours.diasSemana.end
            }
        ];
        
        if (businessHours.usa_sabado) {
            hours.push({
                daysOfWeek: [6], // Sábado
                startTime: businessHours.sabado.start,
                endTime: businessHours.sabado.end
            });
        }

        if (businessHours.usa_domingo) {
            hours.push({
                daysOfWeek: [0], // Domingo
                startTime: businessHours.domingo.start,
                endTime: businessHours.domingo.end
            });
        }
        
        return hours;
    }

    // Dados de getLunchBreaks vindo do controller
    var lunchBreaks = @json($lunchBreaks);

    // Função para obter os lunchBreaks configurados ( Intervalo/Alomoço da Empresa/Prestador)
    function getLunchBreaks() {
        var lunch = [];

        if (lunchBreaks.usa_semana) {
            lunch.push({
                daysOfWeek: [1, 2, 3, 4, 5],
                startTime: lunchBreaks.diasSemana.start,
                endTime: lunchBreaks.diasSemana.end,
                display: 'background',
                rendering: 'background',
                color: '#ffc107',
                title: 'Intervalo / Almoço'
            });
        }
        
        if (lunchBreaks.usa_sabado) {
            lunch.push({
                daysOfWeek: [6],
                startTime: lunchBreaks.sabado.start,
                endTime: lunchBreaks.sabado.end,
                display: 'background',
                rendering: 'background',
                color: '#ffc107',
                title: 'Intervalo / Almoço'
            });
        }

        if (lunchBreaks.usa_domingo) {
            lunch.push({
                daysOfWeek: [0],
                startTime: lunchBreaks.domingo.start,
                endTime: lunchBreaks.domingo.end,
                display: 'background',
                rendering: 'background',
                color: '#ffc107',
                title: 'Intervalo / Almoço'
            });
        }

        return lunch;
    }

    // Função para ajustar o tempo final da TMO se adaptando ao horário de expediente/almoço do Prestador
    function adjustEventEnd(event) {

        var businessHours = getBusinessHours();
        var currentDay = event.start.getDay();
        var currentBusinessHours = businessHours.find(bh => bh.daysOfWeek.includes(currentDay));
        
        var endOfDay = new Date(event.start);
        var [endHour, endMinute] = currentBusinessHours.endTime.split(':').map(Number);
        endOfDay.setHours(endHour, endMinute, 0, 0);

        var lunchBreak = lunchBreaks.diasSemana;
        var lunchStart = new Date(event.start);
        var lunchEnd = new Date(event.start);
        lunchStart.setHours(lunchBreak.start.split(':')[0], lunchBreak.start.split(':')[1], 0, 0);
        lunchEnd.setHours(lunchBreak.end.split(':')[0], lunchBreak.end.split(':')[1], 0, 0);

        if (event.start < lunchEnd && event.end > lunchStart) {
            var lunchDuration = lunchEnd - lunchStart;
            event.setEnd(new Date(event.end.getTime() + lunchDuration));
        }

        if (event.end > endOfDay) {
            var remainingDuration = event.end - endOfDay;

            var startOfNextDay = new Date(endOfDay);
            while (true) {
                startOfNextDay.setDate(startOfNextDay.getDate() + 1);
                var nextDay = startOfNextDay.getDay();
                var nextBusinessHours = businessHours.find(bh => bh.daysOfWeek.includes(nextDay));

                if (nextBusinessHours) {
                    var [startHour, startMinute] = nextBusinessHours.startTime.split(':').map(Number);
                    startOfNextDay.setHours(startHour, startMinute, 0, 0);

                    var endOfNextDay = new Date(startOfNextDay);
                    var [endNextHour, endNextMinute] = nextBusinessHours.endTime.split(':').map(Number);
                    endOfNextDay.setHours(endNextHour, endNextMinute, 0, 0);

                    if (remainingDuration <= (endOfNextDay - startOfNextDay)) {
                        var endOfNextDayEvent = new Date(startOfNextDay.getTime() + remainingDuration);
                        event.setEnd(endOfNextDayEvent);
                        break;
                    } else {
                        remainingDuration -= (endOfNextDay - startOfNextDay);
                    }
                }
            }
        }
    }

    //Função Ajax para atualizar os dados de agendamento da TMO
    function updateEvent(event) {

        var url = "{{ route('agenda.updateAgeAjax') }}";

        $.ajax({
            url: url, // URL do seu controlador
            method: 'POST',
            data: {
                start: event.start.toISOString(),
                end: event.end.toISOString(),
                empresa: event.extendedProps.empresa, // Inclua a empresa
                os: event.extendedProps.os, // Inclua o número da OS
                req: event.extendedProps.req, // Inclua o número da OS
                seq: event.extendedProps.seq, // Inclua o número da OS
                prestador: event.extendedProps.prestador, // Inclua o prestador
                _token: $('meta[name="csrf-token"]').attr('content') // Token CSRF para segurança
            },
            success: function(response) {
                console.log('Evento atualizado com sucesso');
            },
            error: function(xhr, status, error) {
                console.error('Erro ao atualizar o evento:', error);
            }
        });
    }

    /* ******************** Final Funções Extras ******************** */

    /* initialize the external events
    -----------------------------------------------------------------*/
    function ini_events(ele) {
        ele.each(function () {

            // create an Event Object (https://fullcalendar.io/docs/event-object)
            // it doesn't need to have a start or end
            var eventObject = {
                title: $.trim($(this).text()), // use the element's text as the event title
                duration: $(this).data('duration') // Adiciona a duração ao evento
            }

            // store the Event Object in the DOM element so we can get to it later
            $(this).data('eventObject', eventObject)

            // make the event draggable using jQuery UI
            $(this).draggable({
            zIndex        : 1070,
            revert        : true, // will cause the event to go back to its
            revertDuration: 0  //  original position after the drag
            })

        })
    }

    // Initialize events for both containers
    ini_events($('#external-events-prt div.external-event'))
    ini_events($('#external-events-sem-prt div.external-event'))

    /* initialize the calendar
    -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()

    var Calendar = FullCalendar.Calendar;
    var Draggable = FullCalendar.Draggable;

    var containerElPrt = document.getElementById('external-events-prt');
    var containerElSemPrt = document.getElementById('external-events-sem-prt');
    var calendarEl = document.getElementById('calendar');

    // initialize the external events

    // Initialize Draggable for both containers
    new Draggable(containerElPrt, {
        itemSelector: '.external-event',
        eventData: function(eventEl) {
            return {
                title: eventEl.innerText,
                duration: eventEl.getAttribute('data-duration'), // Define the duration of the event
                backgroundColor: '#007bff',
                borderColor: '#007bff',
                textColor: window.getComputedStyle(eventEl, null).getPropertyValue('color'),
                empresa: eventEl.getAttribute('data-empresa'), // Recupera a empresa
                os: eventEl.getAttribute('data-os'), // Recupera o número da OS
                req: eventEl.getAttribute('data-req'), // Recupera o número da requisição
                seq: eventEl.getAttribute('data-seq'), // Recupera o número da sequencia
                prestador: eventEl.getAttribute('data-prestador'), // Recupera o prestador
                empresaNome: eventEl.getAttribute('data-empresaNome'),
                prestadorNome: eventEl.getAttribute('data-prestadorNome'),
                tmo: eventEl.getAttribute('data-tmo'),
                complemento: eventEl.getAttribute('data-complemento'),
                duracao: eventEl.getAttribute('data-duracao'),
                status: eventEl.getAttribute('data-status'),
                area: eventEl.getAttribute('data-area'),
                setor: eventEl.getAttribute('data-setor'),
            };
        }
    });

    new Draggable(containerElSemPrt, {
        itemSelector: '.external-event',
        eventData: function(eventEl) {
            return {
                title: eventEl.innerText,
                duration: eventEl.getAttribute('data-duration') + 'm', // Define the duration of the event
                backgroundColor: '#007bff',
                borderColor: '#007bff',
                textColor: window.getComputedStyle(eventEl, null).getPropertyValue('color'),
                empresa: eventEl.getAttribute('data-empresa'), // Recupera a empresa
                os: eventEl.getAttribute('data-os'), // Recupera o número da OS
                req: eventEl.getAttribute('data-req'), // Recupera o número da requisição
                seq: eventEl.getAttribute('data-seq'), // Recupera o número da sequencia
                prestador: eventEl.getAttribute('data-prestador'), // Recupera o prestador
                empresaNome: eventEl.getAttribute('data-empresaNome'),
                prestadorNome: eventEl.getAttribute('data-prestadorNome'),
                tmo: eventEl.getAttribute('data-tmo'),
                complemento: eventEl.getAttribute('data-complemento'),
                duracao: eventEl.getAttribute('data-duracao'),
                status: eventEl.getAttribute('data-status'),
                area: eventEl.getAttribute('data-area'),
                setor: eventEl.getAttribute('data-setor'),
            };
        }
    });

    var calendar = new Calendar(calendarEl, {
        locale: 'pt-br', // Define o idioma para português
        initialView: 'timeGridWeek', // Define a visualização inicial para semana
        slotMinTime: {!! json_encode($horaMinima) !!}, // Início do horário exibido
        slotMaxTime: {!! json_encode($horaMaxima) !!}, // Fim do horário exibido 
        slotDuration: '00:15:00', // Intervalo de 15 minutos
        allDaySlot: false, // Remove a linha de "All Day"
        businessHours: getBusinessHours(),
        //timeZone: 'local', // Adicionado fuso horário UTC
        headerToolbar: {
            left  : 'prev,next today',
            center: 'title',
            right : 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        themeSystem: 'bootstrap',
        contentHeight: 'auto', // Ajusta a altura de acordo com o conteúdo
        slotLabelFormat: { // Formatar todos os horarios para 00:00
            hour: '2-digit',
            minute: '2-digit',
            omitZeroMinute: false,
            meridiem: false
        },
        validRange: {
            start: {!! json_encode($dataIniAge) !!}, // Data de início permitida
            end: {!! json_encode($dataFinAgeCalendar) !!} // Data de fim permitida
        },
        //selectConstraint: "businessHours", // Restringe a seleção aos businessHours
        //eventConstraint: "businessHours", // Restringe eventos aos businessHours
        events: getLunchBreaks().concat(eventosPrt), // Combina eventos de intervalo de almoço e eventos do prestador
        /*eventContent: function(arg) {
            let title = arg.event.title.replace(/\n/g, '<br>');
            return { html: `<div>${title}</div>` };
        },*/
        // Callback quando um evento é redimensionado
        eventResize: function(info) {
            //adjustEventEnd(info.event);
            updateEvent(info.event); // Chama AJAX para atualizar evento
        },
        // Callback quando um evento é movido
        eventDrop: function(info) {
            adjustEventEnd(info.event);
            updateEvent(info.event); // Chama AJAX para atualizar evento
        },
        // Callback quando um novo evento é criado
        eventReceive: function(info) {
            adjustEventEnd(info.event);
            updateEvent(info.event); // Chama AJAX para atualizar evento
        },
        editable  : true,
        droppable : true, // this allows things to be dropped onto the calendar !!!
        drop      : function(info) {
            var calendar = info.view.calendar;
            if (calendar.view.type === 'dayGridMonth') {
                // Prevent drop in month view and revert event position
                info.revert();
            } else {
                info.draggedEl.parentNode.removeChild(info.draggedEl);
            }
        },
        eventClick: function(info) {
            // Preencha o modal com os detalhes do evento
            var extendedProps = info.event.extendedProps;
            var modalContent = `
                <div class="text-muted">
                    <div class="row">
                        <p class="text-sm col-md-4">TMO
                            <b class="d-block">${extendedProps.tmo}</b>
                        </p>
                        <p class="text-sm col-md-4">Complemento
                            <b class="d-block">${extendedProps.complemento}</b>
                        </p>
                        <p class="text-sm col-md-4">Tempo Estimado
                            <b class="d-block">${extendedProps.duracao}</b>
                        </p>
                    </div>
                    <div class="row">
                        <p class="text-sm col-md-4">OS
                            <b class="d-block">${extendedProps.os}</b>
                        </p>
                        <p class="text-sm col-md-4">Requisição
                            <b class="d-block">${extendedProps.req}</b>
                        </p>
                        <p class="text-sm col-md-4">Seq. Serviço
                            <b class="d-block">${extendedProps.seq}</b>
                        </p>
                    </div>
                    <div class="row">
                        <p class="text-sm col-md-4">Situação
                            <b class="d-block">${extendedProps.status}</b>
                        </p>
                        <p class="text-sm col-md-4">Data / Hora Início
                            <b class="d-block">${info.event.start.toLocaleString()}</b>
                        </p>
                        <p class="text-sm col-md-4">Data / Hora Encerramento
                            <b class="d-block">${info.event.end ? info.event.end.toLocaleString() : 'N/A'}</b>
                        </p>
                    </div>
                    <div class="row">
                        <p class="text-sm col-md-4">Empresa
                            <b class="d-block">${extendedProps.empresa} - ${extendedProps.empresaNome}</b>
                        </p>
                        <p class="text-sm col-md-4">Prestador
                            <b class="d-block">${extendedProps.prestador} - ${extendedProps.prestadorNome}</b>
                        </p>
                        <p class="text-sm col-md-4">Área / Setor
                            <b class="d-block">${extendedProps.area} / ${extendedProps.setor}</b>
                        </p>
                    </div>
                </div>
            `;
            document.getElementById('modalContent').innerHTML = modalContent;

            // Abre o modal
            //$('#eventModal').modal('show');
            document.getElementById('openModalButton').click();
        },
        /*eventOverlap: function(stillEvent, movingEvent) {
            //Bloqueia os horarios de intevalo
            var lunchEvents = getLunchBreaks();
            for (var i = 0; i < lunchEvents.length; i++) {
                var lunchEvent = lunchEvents[i];
                var lunchStart = new Date(movingEvent.start).setHours(lunchEvent.startTime.split(':')[0], lunchEvent.startTime.split(':')[1]);
                var lunchEnd = new Date(movingEvent.end).setHours(lunchEvent.endTime.split(':')[0], lunchEvent.endTime.split(':')[1]);

                if (movingEvent.start >= lunchStart && movingEvent.start < lunchEnd) {
                    return false;
                }
            }
            return true;
        },*/
    });

    calendar.render();
    // $('#calendar').fullCalendar()

    /* ADDING EVENTS */
    var currColor = '#3c8dbc' //Red by default
    // Color chooser button
    $('#color-chooser > li > a').click(function (e) {
        e.preventDefault()
        // Save color
        currColor = $(this).css('color')
        // Add color effect to button
        $('#add-new-event').css({
            'background-color': currColor,
            'border-color'    : currColor
        })
    })
    $('#add-new-event').click(function (e) {
        e.preventDefault()
        // Get value and make sure it is not null
        var val = $('#new-event').val()
        if (val.length == 0) {
            return
        }

        // Create events
        var event = $('<div />')
        event.css({
            'background-color': currColor,
            'border-color'    : currColor,
            'color'           : '#fff'
        }).addClass('external-event')
        event.text(val)
        $('#external-events').prepend(event)

        // Add draggable funtionality
        ini_events(event)

        // Remove event from text input
        $('#new-event').val('')
    })
})
</script>
@stop

