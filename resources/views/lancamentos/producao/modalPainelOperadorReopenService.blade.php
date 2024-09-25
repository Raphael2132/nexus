<!-- Cabeçalho do Modal -->
<div class="text-muted">
    <div class="row">
        <p class="text-sm col-md-4">Ordem de Serviço
            <b class="d-block">{{ $dadosOS->os_nos }}</b>
        </p>
        <p class="text-sm col-md-4">Cliente da OS
            <b class="d-block">{{ $dadosOS->os_cli.' - '.$dadosCli->cliente_nome }}</b>
        </p>
        <p class="text-sm col-md-4">Consultor da Abertura da OS
            <b class="d-block">{{ $dadosOS->os_res_abr.' - '.$dadosUsu->name }}</b>
        </p>
    </div>
    <div class="row">
        <p class="text-sm col-md-4">Situação da OS
            @if($dadosOS->os_sts == 'A')
            <b class="d-block"><span class="badge badge-pill badge-info badge-custom">Aberta</span></b>
            @elseif($dadosOS->os_sts == 'F')
            <b class="d-block"><span class="badge badge-pill badge-success badge-custom">Finalizada</span></b>
            @else
            <b class="d-block"><span class="badge badge-pill badge-danger badge-custom">Cancelada</span></b>
            @endif
        </p>
        <p class="text-sm col-md-4">Data e Hora da Abertura da OS
            <b class="d-block"><span class="badge badge-pill badge-primary badge-custom">{{ Helper::formataDataHora($dadosOS->os_dha) }}</span></b>                                   
        </p>
        <p class="text-sm col-md-4">Data e Hora da Previsão de Entrega
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
                <p class="text-sm col-md-3">Data / Hora Agendamento Início
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
            <div class="row">
                <p class="text-sm col-md-3">Situação da TMO
                    @if($dadosSrv->exetrf_sts == 'F')
                    <b class="d-block"><span class="badge badge-pill badge-success badge-custom">Finalizada</span></b>
                    @elseif($dadosSrv->exetrf_sts == 'C')
                    <b class="d-block"><span class="badge badge-pill badge-danger badge-custom">Cancelada</span></b>
                    @elseif($dadosSrv->exetrf_sts == 'S')
                    <b class="d-block"><span class="badge badge-pill badge-warning badge-custom">Suspensa</span></b>
                    @elseif($dadosSrv->exetrf_sts == 'A')
                    <b class="d-block"><span class="badge badge-pill badge-info badge-custom">Em Andamento</span></b>
                    @else
                    <b class="d-block"><span class="badge badge-pill badge-primary badge-custom">Em Espera</span></b>
                    @endif                        
                </p>
                <p class="text-sm col-md-3">Data e Hora de Início TMO
                    @if(!empty($dadosSrv->exetrf_dt_ini_srv))
                    <b class="d-block">{{ Helper::formataData($dadosSrv->exetrf_dt_ini_srv).' '.Helper::formataHoraMinuto($dadosSrv->exetrf_hr_ini_srv) }}</b>       
                    @else
                    <b class="d-block"></b>  
                    @endif                             
                </p>
                <p class="text-sm col-md-3">Data e Hora de Término TMO
                    @if(!empty($dadosSrv->exetrf_dt_fin_srv))
                    <b class="d-block">{{ Helper::formataData($dadosSrv->exetrf_dt_fin_srv).' '.Helper::formataHoraMinuto($dadosSrv->exetrf_hr_fin_srv) }}</b>        
                    @else
                    <b class="d-block"></b>  
                    @endif                           
                </p>
            </div>
            @if($dadosSrv->exetrf_sts == 'C')
            <div class="row">
                <p class="text-sm col-md-3">Data / Hora Cancelamento
                    <b class="d-block">{{ Helper::formataData($dadosSrv->exetrf_dt_can_srv).' '.Helper::formataHoraMinuto($dadosSrv->exetrf_hr_can_srv) }}</b>
                </p>
                <p class="text-sm col-md-3">Responsável do Cancelamento
                    @php 
                        $dadosPrtCan = DB::table('users')->where('usuario_codigo', $dadosSrv->exetrf_res_can)->where('usuario_empresa', $dadosSrv->exetrf_emp)->first();
                    @endphp
                    <b class="d-block">{{ $dadosSrv->exetrf_res_can.' - '.$dadosPrtCan->name }}</b>
                </p>
                <p class="text-sm col-md-3">Motivo de Cancelamento
                    @php 
                        $dadosMotCan = DB::table('parametros_sis_can_motivos')->where('canmot_codigo', $dadosSrv->exetrf_mot_can_srv)->first();
                    @endphp
                    <b class="d-block">{{ $dadosSrv->exetrf_mot_can_srv.' - '.$dadosMotCan->canmot_desc }}</b>
                </p>
            </div>
            @elseif($dadosSrv->exetrf_sts == 'S')
            <div class="row">
                <p class="text-sm col-md-3">Data / Hora Suspensão
                    <b class="d-block">{{ Helper::formataData($dadosSrv->exetrf_dt_sus_srv).' '.Helper::formataHoraMinuto($dadosSrv->exetrf_hr_sus_srv) }}</b>
                </p>
                <p class="text-sm col-md-3">Responsável da Suspensão
                    @php 
                        $dadosPrtSus = DB::table('users')->where('usuario_codigo', $dadosSrv->exetrf_res_sus)->where('usuario_empresa', $dadosSrv->exetrf_emp)->first();
                    @endphp
                    <b class="d-block">{{ $dadosSrv->exetrf_res_sus.' - '.$dadosPrtSus->name }}</b>
                </p>
                <p class="text-sm col-md-3">Motivo da Suspenção
                    @php 
                        $dadosMotSus = DB::table('parametros_sis_sus_motivos')->where('susmot_codigo', $dadosSrv->exetrf_mot_sus_srv)->first();
                    @endphp
                    <b class="d-block">{{ $dadosSrv->exetrf_mot_sus_srv.' - '.$dadosMotSus->susmot_desc }}</b>
                </p>
            </div>
            @endif
        </x-adminlte-callout>
    </div>
</div>
