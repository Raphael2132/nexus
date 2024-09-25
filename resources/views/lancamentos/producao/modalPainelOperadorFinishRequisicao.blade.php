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
    </div>
</div>
<div class="d-flex justify-content-center">
    <div class="col-md-12">
        <x-adminlte-callout theme="info" title="Dados da Requisição"> 
            <div class="row">
                <p class="text-sm col-md-3">Código
                    <b class="d-block">{{ $dadosReq->req_seq }}</b>
                </p>
                <p class="text-sm col-md-3">Descrição
                    <b class="d-block">{{ $dadosReq->req_dsc }}</b>
                </p>
                <p class="text-sm col-md-3">Tempo Total Previsto
                    <b class="d-block">{{ $dadosReq->req_qtd_hr }}</b>
                </p>
                <p class="text-sm col-md-3">Situação
                    @if($dadosReq->req_sts == 'A')
                    <b class="d-block"><span class="badge badge-pill badge-info badge-custom">Aberta</span></b>
                    @else
                    <b class="d-block"><span class="badge badge-pill badge-success badge-custom">Finalizada</span></b>
                    @endif
                </p>
            </div>
            <div class="row">
                <p class="text-sm col-md-3">Categoria
                    @php 
                        $dadosCat = DB::table('lancamento_srv_categorias')->where('categoria_codigo', $dadosReq->req_cat)->first();
                    @endphp
                    <b class="d-block">{{ $dadosReq->req_cat.' - '.$dadosCat->categoria_desc }}</b>
                </p>
                <p class="text-sm col-md-3">Área
                    @php 
                        $dadosAre = DB::table('parametros_sis_areas')->where('area_codigo', $dadosReq->req_are)->first();
                    @endphp
                    <b class="d-block">{{ $dadosReq->req_are.' - '.$dadosAre->area_desc }}</b>
                </p>
                <p class="text-sm col-md-3">Setor
                    @php 
                        $dadosSet = DB::table('parametros_sis_setores')->where('setor_codigo', $dadosReq->req_set)->where('setor_empresa', $dadosReq->req_emp)->where('setor_area', $dadosReq->req_are)->first();
                    @endphp
                    <b class="d-block">{{ $dadosReq->req_set.' - '.$dadosSet->setor_desc }}</b>
                </p>
                <p class="text-sm col-md-3">Tipo Serviço
                    @php 
                        $dadosTos = DB::table('lancamento_srv_tipo_servicos')->where('tipsrv_cod', $dadosReq->req_tos)->where('tipsrv_emp', $dadosReq->req_emp)->where('tipsrv_are', $dadosReq->req_are)->where('tipsrv_cat', $dadosReq->req_cat)->first();
                    @endphp
                    <b class="d-block">{{ $dadosReq->req_tos.' - '.$dadosTos->tipsrv_nom }}</b>
                </p>
            </div>
        </x-adminlte-callout>
    </div>
</div>
