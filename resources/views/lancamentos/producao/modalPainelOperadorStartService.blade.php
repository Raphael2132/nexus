<!-- Cabeçalho do Modal -->
<div class="text-muted">
    <div class="row">
        <p class="text-sm col-md-3">Ordem de Serviço
            <b class="d-block">{{ $dadosOS->os_nos }}</b>
        </p>
        <p class="text-sm col-md-3">Cliente da OS
            <b class="d-block">{{ $dadosOS->os_cli.' - '.$dadosCli->cliente_nome }}</b>
        </p>
        <p class="text-sm col-md-3">Consultor da Abertura da OS
            <b class="d-block">{{ $dadosOS->os_res_abr.' - '.$dadosUsu->name }}</b>
        </p>
        <p class="text-sm col-md-3">Situação da OS
            @if($dadosOS->os_sts == 'A')
            <b class="d-block"><span class="badge badge-pill badge-info badge-custom">Aberta</span></b>
            @elseif($dadosOS->os_sts == 'F')
            <b class="d-block"><span class="badge badge-pill badge-success badge-custom">Finalizada</span></b>
            @else
            <b class="d-block"><span class="badge badge-pill badge-danger badge-custom">Cancelada</span></b>
            @endif
        </p>
    </div>
    <div class="row">
        
        <p class="text-sm col-md-3">Data e Hora da Abertura da OS
            <b class="d-block"><span class="badge badge-pill badge-primary badge-custom">{{ Helper::formataDataHora($dadosOS->os_dha) }}</span></b>                                   
        </p>
        <p class="text-sm col-md-3">Data e Hora da Previsão de Entrega
            @if(!empty($dadosOS->os_dpe))
                @php 
                    $dtHrPreEnt = $dadosOS->os_dpe.' '.Helper::formataHoraMinuto($dadosOS->os_hpe).':00';
                    $dtHrHoje = date('Y-m-d H:i:s');
                    $dtHrFin = $dadosOS->os_dhf;
                @endphp
                @if($dadosOS->os_sts == 'A' && $dtHrHoje < $dtHrPreEnt)
                <b class="d-block"><span class="badge badge-pill badge-success badge-custom">{{Helper::formataDataHora($dtHrPreEnt)}}</span></b>
                @elseif($dadosOS->os_sts == 'A' && $dtHrHoje > $dtHrPreEnt)
                <b class="d-block"><span class="badge badge-pill badge-danger badge-custom">{{Helper::formataDataHora($dtHrPreEnt)}}</span></b>
                @elseif($dadosOS->os_sts == 'F' && $dtHrFin > $dtHrPreEnt)
                <b class="d-block"><span class="badge badge-pill badge-danger badge-custom">{{Helper::formataDataHora($dtHrPreEnt)}}</span></b>
                @elseif($dadosOS->os_sts == 'F' && $dtHrFin < $dtHrPreEnt)
                <b class="d-block"><span class="badge badge-pill badge-success badge-custom">{{Helper::formataDataHora($dtHrPreEnt)}}</span></b>
                @else
                <b class="d-block"><span class="badge badge-pill badge-warning badge-custom">{{Helper::formataDataHora($dtHrPreEnt)}}</span></b>
                @endif
            @else
            <b class="d-block">Não Informada</b>
            @endif                                    
        </p>
        @php
            $config = [
                "singleDatePicker" => true,
                "showDropdowns" => true,
                "minYear" => 2000,
                "maxYear" => "js:parseInt(moment().format('YYYY'),10)",
                "timePicker" => true,
                "timePicker24Hour" => true,
                "timePickerSeconds" => false,
                "cancelButtonClasses" => "btn-danger",
                "locale" => ["format" => "HH:mm"],
            ];
        @endphp
        <x-adminlte-date-range name="horaIniSrv" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-3">
            <x-slot name="label">
                Hora de Inicio <span style="color:red;">*</span>
            </x-slot>
            <x-slot name="appendSlot">
                <div class="input-group-text">
                    <i class="far fa-lg fa-clock"></i>
                </div>
            </x-slot>
        </x-adminlte-date-range>

        <x-adminlte-date-range name="horaFinSrv" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-3" disabled>
            <x-slot name="label">
                Hora de Término <span style="color:red;">*</span>
            </x-slot>
            <x-slot name="appendSlot">
                <div class="input-group-text">
                    <i class="far fa-lg fa-clock"></i>
                </div>
            </x-slot>
        </x-adminlte-date-range>
    </div>
</div>
<div class="d-flex justify-content-center">
    <div class="col-md-12">
        <x-adminlte-callout theme="info" title="Dados da TMO"> 
            <div class="row">
                <p class="text-sm col-md-3">TMO
                    <b class="d-block">{{ $dadosSrv->exetrf_tmo.' - '.$dadosSrv->exetrf_desc }}</b>
                </p>
                <p class="text-sm col-md-3">Complemento
                    <b class="d-block">{{ $dadosSrv->exetrf_cmp }}</b>
                </p>
                <p class="text-sm col-md-3">Prestador
                    @if(!empty($dadosPrtTMO->prestador_codigo))
                    <b class="d-block">{{ $dadosPrtTMO->prestador_codigo.' - '.$dadosPrtTMO->prestador_nome }}</b>
                    @else 
                    <b class="d-block">Não Alocado</b>
                    @endif
                </p>
                <p class="text-sm col-md-3">Setor
                    <b class="d-block">{{ $dadosSetor->setor_codigo.' - '.$dadosSetor->setor_desc }}</b>
                </p>
            </div>
            <div class="row">
                <p class="text-sm col-md-3">Tipo da Hora
                    @if($dadosSrv->exetrf_ths == 'P')
                    <b class="d-block">Padrão</b>
                    @elseif($dadosSrv->exetrf_ths == 'I')
                    <b class="d-block">Informada</b>
                    @elseif($dadosSrv->exetrf_ths == 'R')
                    <b class="d-block">Real</b>
                    @elseif($dadosSrv->exetrf_ths == 'F')
                    <b class="d-block">Fixo</b>
                    @else
                    <b class="d-block">Terceiros</b>
                    @endif
                </p>
                <p class="text-sm col-md-3">Tempo Previsto
                    <b class="d-block">{{ $dadosSrv->exetrf_qhr }}</b>
                </p>
                <p class="text-sm col-md-3">Data / Hora Agendamento Inicio
                    @if($dadosSrv->exetrf_age == 'S')
                    <b class="d-block">{{ Helper::formataData($dadosSrv->exetrf_dt_age_tmo).' '.Helper::formataHoraMinuto($dadosSrv->exetrf_hr_age_tmo) }}</b>
                    @else 
                    <b class="d-block">Não Agendado</b>
                    @endif
                </p>
                <p class="text-sm col-md-3">Data / Hora Previsão Término
                    @if($dadosSrv->exetrf_age == 'S')
                    <b class="d-block">{{ Helper::formataData($dadosSrv->exetrf_dt_age_fin_tmo).' '.Helper::formataHoraMinuto($dadosSrv->exetrf_hr_age_fin_tmo) }}</b>
                    @else 
                    <b class="d-block">Não Agendado</b>
                    @endif
                </p>
            </div>
        </x-adminlte-callout>
    </div>
</div>

<script>
$(document).ready(function() {  
        //Esconde calendário de data
        $(function() { 
            $('#horaIniSrv').on('showCalendar.daterangepicker', function(ev, picker) {

                $('.calendar-table').hide();

            }); 
        }); 
    }); 
</script>