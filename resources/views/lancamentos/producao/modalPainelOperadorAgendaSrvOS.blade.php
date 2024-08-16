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
        <p class="text-sm col-md-3">observações da OS
            <b class="d-block">{{ $dadosOS->os_observacao }}</b>
        </p>
    </div>
    <div class="row">
        <p class="text-sm col-md-3">Situação da OS
            @if($dadosOS->os_sts == 'A')
            <b class="d-block"><span class="badge badge-pill badge-info badge-custom">Aberta</span></b>
            @elseif($dadosOS->os_sts == 'F')
            <b class="d-block"><span class="badge badge-pill badge-success badge-custom">Finalizada</span></b>
            @else
            <b class="d-block"><span class="badge badge-pill badge-danger badge-custom">Cancelada</span></b>
            @endif
        </p>
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
        <p class="text-sm col-md-3">Data e Hora de Fechamento
            @if(!empty($dadosOS->os_dhf))
            <b class="d-block"><span class="badge badge-pill badge-dark badge-custom">{{Helper::formataDataHora($dadosOS->os_dhf)}}</span></b>
            @else
            <b class="d-block">OS em Andamento</b>
            @endif                                    
        </p>
    </div>
</div>
<!-- Gera a tabela do Modal -->
@php
    $headsAG = [
        'Linha Oculta',
        'Req Oculto',
        'Seq Oculto',
        'Req.',
        'Requisição / Serviço',
        'TS',
        'Setor',
        'Prestador',
        'Situação',
        'Tempo',
        'Agendamento Ini',
        'Agendamento fin',
        'Hora Ini',
        'Data Ini',
        'Data Fin',
        'Hora Fin',
        'Hr Real',
        'Hr Saldo',
        ['label' => '', 'no-export' => true, 'width' => '3%'],
    ];
    $configAG = [
        'paging' => false,
        'searching' => false,
        'language' => [
            'decimal' =>        '',
            'emptyTable' =>     'Sem dados disponíveis na tabela',
            'info' =>           '',
            'infoEmpty' =>      '',
            'infoFiltered' =>   '(Filtrado do total de _MAX_ registros)',
            'infoPostFix' =>    '',
            'thousands' =>      ',',
            'lengthMenu' =>     '',
            'loadingRecords' => 'Carregando...',
            'processing' =>     '',
            'search' =>         'Pesquisar:',
            'zeroRecords' =>    'Nenhum registro correspondente encontrado',
            'paginate' => [
                'first' =>      'Primeiro',
                'last' =>       'Último',
                'next' =>       'Próximo',
                'previous' =>   'Anterior'
            ],
            'aria' => [
                'sortAscending' =>  ': ativar para classificar a coluna em ordem crescente',
                'sortDescending' => ': ativar para classificar a coluna em ordem decrescente'
            ],
        ],
        'order' => [[1, 'asc'],[0, 'asc'],[2, 'asc']],
        'columns' => [
            ['orderable' => false, 'visible' => false], // Esconder primeira coluna
            ['orderable' => false, 'visible' => false], // Esconder segunda coluna
            ['orderable' => false, 'visible' => false], // Esconder terceira coluna
            ['orderable' => false], 
            ['orderable' => false], 
            ['orderable' => false], 
            ['orderable' => false],
            ['orderable' => false], 
            ['orderable' => false], 
            ['orderable' => false], 
            ['orderable' => false], 
            ['orderable' => false], 
            ['orderable' => false], 
            ['orderable' => false], 
            ['orderable' => false], 
            ['orderable' => false], 
            ['orderable' => false], 
            ['orderable' => false], 
            ['orderable' => false]
        ],
    ];
@endphp
<x-adminlte-datatable id="tableModalAgendaSrvOS" :heads="$headsAG" :config="$configAG" head-theme="dark" striped hoverable beautify>
    @foreach($dadosTMOAgenda as $agenda)
        @php 
            if($agenda->age_status == 'F'){
                $badgeSts = 'success';
                $textoSts = 'Finalizada';
            }elseif($agenda->age_status == 'A'){
                $badgeSts = 'info';
                if($agenda->age_tipo == 'R'){
                    $textoSts = 'Aberta';
                }else{
                    $textoSts = 'Em Andamento';
                }
            }elseif($agenda->age_status == 'C'){
                $badgeSts = 'danger';
                $textoSts = 'Cancelada';
            }elseif($agenda->age_status == 'S'){
                $badgeSts = 'warning';
                $textoSts = 'Suspensa';
            }else{
                $badgeSts = 'primary';
                $textoSts = 'Em Espera';
            }

            if(!empty($agenda->age_prestador)){
                $dadosPrtAge =  DB::table('cadastro_prestadores')->where('prestador_codigo', $agenda->age_prestador)->get();
                $prestadorAge = $agenda->age_prestador.' - '.$dadosPrtAge[0]->prestador_nome;
            }else{
                $prestadorAge = '';
            }
        @endphp
    @if($agenda->age_tipo == 'R')
    <tr style="background-color: #adb5bd;}};">
    @else
    <tr>
    @endif
        <td>{{ $agenda->age_tipo }}</td>
        <td>{{ $agenda->age_req }}</td>
        <td>{{ $agenda->age_seq }}</td>
        <td>{{ $agenda->age_tipo == 'R' ? $agenda->age_req : '' }}</td>
        <td>{{ $agenda->age_descricao }}</td>
        <td>{{ $agenda->age_tipo_srv }}</td>
        <td>{{ $agenda->age_setor }}</td>
        <td>{{ $prestadorAge }}</td>
        <td>
            @if($agenda->age_tipo == 'R' && $agenda->age_status == 'F')
            <div class="btn-group" style="width: 100%;"style="width: 100%;">
                <button type="button" class="btn btn-success btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Finalizada <span class="caret"></span>
                </button>	
                <ul class="dropdown-menu">
                    <li><a href="#" class="execucao-tarefa" onclick="openModalReopenRequisicao('{{ $agenda->age_empresa }}', '{{ $agenda->age_nos }}', '{{ $agenda->age_req }}')">Reabrir</a></li>
                </ul>		
            </div>
            @elseif($agenda->age_tipo == 'R' && $agenda->age_status == 'A')
            <div class="btn-group" style="width: 100%;"style="width: 100%;">
                <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Aberta <span class="caret"></span>
                </button>	
                <ul class="dropdown-menu">
                    <li><a href="#" class="execucao-tarefa" onclick="openModalFinishRequisicao('{{ $agenda->age_empresa }}', '{{ $agenda->age_nos }}', '{{ $agenda->age_req }}')">Finalizar</a></li>
                </ul>		
            </div>
            @elseif($agenda->age_tipo == 'S' && $agenda->age_status == 'F')
            <div class="btn-group" style="width: 100%;"style="width: 100%;">
                <button type="button" class="btn btn-success btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Finalizada <span class="caret"></span>
                </button>	
                <ul class="dropdown-menu">
                    <li><a href="#" class="execucao-tarefa" onclick="openModalReopenService('{{ $agenda->age_empresa }}', '{{ $agenda->age_nos }}', '{{ $agenda->age_req }}', '{{ $agenda->age_seq }}')">Reabrir</a></li>
                </ul>		
            </div>
            @elseif($agenda->age_tipo == 'S' && $agenda->age_status == 'C')
            <div class="btn-group" style="width: 100%;"style="width: 100%;">
                <button type="button" class="btn btn-danger btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Cancelada <span class="caret"></span>
                </button>	
                <ul class="dropdown-menu">
                    <li><a href="#" class="execucao-tarefa" onclick="openModalReopenService('{{ $agenda->age_empresa }}', '{{ $agenda->age_nos }}', '{{ $agenda->age_req }}', '{{ $agenda->age_seq }}')">Reabrir</a></li>
                </ul>		
            </div>
            @elseif($agenda->age_tipo == 'S' && $agenda->age_status == 'S')
            <div class="btn-group" style="width: 100%;"style="width: 100%;">
                <button type="button" class="btn btn-warning btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Suspensa <span class="caret"></span>
                </button>	
                <ul class="dropdown-menu">
                    <li><a href="#" class="execucao-tarefa" onclick="openModalReopenService('{{ $agenda->age_empresa }}', '{{ $agenda->age_nos }}', '{{ $agenda->age_req }}', '{{ $agenda->age_seq }}')">Reabrir</a></li>
                    <li><a href="#" class="execucao-tarefa" onclick="openModalCancelService('{{ $agenda->age_empresa }}', '{{ $agenda->age_nos }}', '{{ $agenda->age_req }}', '{{ $agenda->age_seq }}')">Cancelar</a></li>
                </ul>		
            </div>
            @elseif($agenda->age_tipo == 'S' && $agenda->age_status == 'A')
            <div class="btn-group" style="width: 100%;"style="width: 100%;">
                <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Em Andamento <span class="caret"></span>
                </button>	
                <ul class="dropdown-menu">
                    <li><a href="#" class="execucao-tarefa" onclick="openModalFinishService('{{ $agenda->age_empresa }}', '{{ $agenda->age_nos }}', '{{ $agenda->age_req }}', '{{ $agenda->age_seq }}')">Finalizar</a></li>
                    <li><a href="#" class="execucao-tarefa" onclick="openModalCancelService('{{ $agenda->age_empresa }}', '{{ $agenda->age_nos }}', '{{ $agenda->age_req }}', '{{ $agenda->age_seq }}')">Cancelar</a></li>
                    <li><a href="#" class="execucao-tarefa" onclick="openModalSuspendService('{{ $agenda->age_empresa }}', '{{ $agenda->age_nos }}', '{{ $agenda->age_req }}', '{{ $agenda->age_seq }}')">Suspender</a></li>
                </ul>		
            </div>
            @elseif($agenda->age_tipo == 'S' && $agenda->age_status == 'E')
            <div class="btn-group" style="width: 100%;"style="width: 100%;">
                <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Em Espera <span class="caret"></span>
                </button>	
                <ul class="dropdown-menu">
                    <li><a href="#" class="execucao-tarefa" onclick="openModalStartService('{{ $agenda->age_empresa }}', '{{ $agenda->age_nos }}', '{{ $agenda->age_req }}', '{{ $agenda->age_seq }}')">Iniciar</a></li>
                    <li><a href="#" class="execucao-tarefa" onclick="openModalCancelService('{{ $agenda->age_empresa }}', '{{ $agenda->age_nos }}', '{{ $agenda->age_req }}', '{{ $agenda->age_seq }}')">Cancelar</a></li>
                    <li><a href="#" class="execucao-tarefa" onclick="openModalSuspendService('{{ $agenda->age_empresa }}', '{{ $agenda->age_nos }}', '{{ $agenda->age_req }}', '{{ $agenda->age_seq }}')">Suspender</a></li>
                </ul>		
            </div>
            @endif
        </td>
        <td>{{ $agenda->age_qtd_hr }}</td>
        @if($agenda->age_agendado == 'S')
            @php 
                $dtHrAge = $agenda->age_dt_age_ini.' '.Helper::formataHoraMinuto($agenda->age_hr_age_ini).':00';
                $dtHrAgeFin = $agenda->age_dt_age_fin.' '.Helper::formataHoraMinuto($agenda->age_hr_age_fin).':00';
                $dtHrHoje = date('Y-m-d H:i:s');
                $dtHrFin = $dadosOS->os_dhf;
            @endphp
            <td>
                @if(($agenda->age_status == 'A' || $agenda->age_status == 'F')  && $agenda->age_dt_ini.' '.Helper::formataHoraMinuto($agenda->age_hr_ini).':00' <= $dtHrAge)
                <b class="d-block"><span class="badge badge-pill badge-success badge-custom">{{Helper::formataDataHora($dtHrAge)}}</span></b>
                @elseif(($agenda->age_status == 'A' || $agenda->age_status == 'F') && $agenda->age_dt_ini.' '.Helper::formataHoraMinuto($agenda->age_hr_ini).':00' > $dtHrAge)
                <b class="d-block"><span class="badge badge-pill badge-danger badge-custom">{{Helper::formataDataHora($dtHrAge)}}</span></b>
                @elseif($agenda->age_status == 'E' && $dtHrAge > $dtHrHoje)
                <b class="d-block"><span class="badge badge-pill badge-danger badge-custom">{{Helper::formataDataHora($dtHrAge)}}</span></b>
                @elseif($agenda->age_status == 'E' && $dtHrAge <= $dtHrHoje)
                <b class="d-block"><span class="badge badge-pill badge-success badge-custom">{{Helper::formataDataHora($dtHrAge)}}</span></b>
                @else
                <b class="d-block"><span class="badge badge-pill badge-warning badge-custom">{{Helper::formataDataHora($dtHrAge)}}</span></b>
                @endif
            </td>
            <td>
                @if($agenda->age_status == 'F' && $dtHrAgeFin >= $agenda->age_dt_fin.' '.Helper::formataHoraMinuto($agenda->age_hr_fin).':00')
                <b class="d-block"><span class="badge badge-pill badge-success badge-custom">{{Helper::formataDataHora($dtHrAgeFin)}}</span></b>
                @elseif($agenda->age_status == 'F' && $dtHrAgeFin < $agenda->age_dt_fin.' '.Helper::formataHoraMinuto($agenda->age_hr_fin).':00')
                <b class="d-block"><span class="badge badge-pill badge-danger badge-custom">{{Helper::formataDataHora($dtHrAgeFin)}}</span></b>
                @elseif(($agenda->age_status == 'E' || $agenda->age_status == 'A') && $dtHrHoje > $dtHrAgeFin)
                <b class="d-block"><span class="badge badge-pill badge-danger badge-custom">{{Helper::formataDataHora($dtHrAgeFin)}}</span></b>
                @elseif(($agenda->age_status == 'E' || $agenda->age_status == 'A') && $dtHrHoje <= $dtHrAgeFin)
                <b class="d-block"><span class="badge badge-pill badge-success badge-custom">{{Helper::formataDataHora($dtHrAgeFin)}}</span></b>
                @else
                <b class="d-block"><span class="badge badge-pill badge-warning badge-custom">{{Helper::formataDataHora($dtHrAgeFin)}}</span></b>
                @endif
            </td>
        @else
            <td>Não Agendado</td>
            <td>Não Agendado</td>
        @endif
        @if(!empty($agenda->age_dt_ini) && $agenda->age_tipo == 'S')
            <td>{{ Helper::formataData($agenda->age_dt_ini) }}</td>
            <td>{{ Helper::formataHoraMinuto($agenda->age_hr_ini) }}</td>
        @else
            <td></td>
            <td></td>
        @endif
        @if(!empty($agenda->age_dt_fin) && $agenda->age_tipo == 'S')
            <td>{{ Helper::formataData($agenda->age_dt_fin) }}</td>
            <td>{{ Helper::formataHoraMinuto($agenda->age_hr_fin) }}</td>
            <td>{{ $agenda->age_qtd_hr_real }}</td>
            <td>
                @if($agenda->age_qtd_hr_saldo > 0)
                    <span class="text-primary">{{ $agenda->age_qtd_hr_saldo }} <i class="fa-solid fa-angles-right"></i></span>
                @elseif($agenda->age_qtd_hr_saldo < 0)
                    <span class="text-danger">{{ $agenda->age_qtd_hr_saldo }} <i class="fa-solid fa-angles-left"></i></span>
                @else
                    <span class="text-success">{{ $agenda->age_qtd_hr_saldo }} <i class="fa-solid fa-stop"></i></span>
                @endif
            </td>
        @else
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        @endif
        <td>
            @if($agenda->age_tipo == 'S')
                @php 
                    $cntAux = DB::table('lancamento_srv_prt_auxiliares')->where('prtaux_emp', $agenda->age_empresa)->where('prtaux_nos', $agenda->age_nos)->where('prtaux_req', $agenda->age_req)->where('prtaux_srv', $agenda->age_seq)->count('prtaux_prt');
                @endphp
                <nobr class="d-flex justify-content-center">
                    <button type="button" class="btn btn-xl" title="Adicionar Novo Auxiliar"
                        onclick="openModalAddChangePrt('{{ $agenda->age_empresa }}', '{{ $agenda->age_nos }}', '{{ $agenda->age_req }}', '{{ $agenda->age_seq }}')">
                        @if(!empty($agenda->age_prestador))
                        <i class="fa-solid fa-user-pen fa-lg" style="color: #39cccc;"></i>
                        @else
                        <i class="fa-solid fa-user-plus fa-lg" style="color: #007bff;"></i>
                        @endif
                    </button>
                    <button type="button" class="btn btn-xl" title="Adicionar Novo Auxiliar" 
                        onclick="openModalAddAux('{{ $agenda->age_empresa }}', '{{ $agenda->age_nos }}', '{{ $agenda->age_req }}', '{{ $agenda->age_seq }}')">
                        @if($cntAux == 0)
                        <i class="fa-solid fa-users-slash fa-lg" style="color: #808080;"></i>
                        @else
                        <i class="fa-solid fa-users fa-lg" style="color: #74C0FC;"></i>
                        @endif
                    </button>
                </nobr>
            @endif
        </td>
    </tr>
    @endforeach
</x-adminlte-datatable>
