@extends('adminlte::page')

@section('title', 'Página Inicial')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h1>Bem vind{{ Auth::user()->usuario_sexo == 'F' ? 'a' : 'o' }}, <b>{{ strtoupper(Auth::user()->name) }}</b></h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Página Inicial</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
@php
    $headsOS = [
        'OS / Data',
        'empresa',
        'Cliente',
        'Situação',
        'Valor',
    ];
    $configOS = [
        'lengthMenu' => [ 5, 10, 25, 50],
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
        'order' => [null,null,null,null,null],
        'columns' => [null, null, null, null, null],
    ];

    $headsNFS = [
        'Nº / Série',
        'OS',
        'empresa',
        'Cliente',
        'Situação',
        'Valor',
    ];
    $configNFS = [
        'lengthMenu' => [ 5, 10, 25, 50],
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
        'order' => [null,null,null,null,null,null],
        'columns' => [['orderable' => false], ['orderable' => false], ['orderable' => false], ['orderable' => false], ['orderable' => false], ['orderable' => false]],
    ];
@endphp

<div class="col-md-12">
    
    <div class="row">
        <div class="col-md-6">
            <x-adminlte-card title="Relatório de NFS-e Geradas na Última Semana" theme="navy" theme-mode="outline">
                
                        <div class="d-flex">
                            <p class="d-flex flex-column">
                                <span class="text-bold text-lg">{{$qtdNFS}}</span>
                                <span>Geradas essa semana</span>
                            </p>
                            <p class="ml-auto d-flex flex-column text-right">
                                @if($perNfsSemana > 0)<span class="text-success"><i class="fas fa-arrow-up"></i>
                                @elseif($perNfsSemana < 0)<span class="text-danger"><i class="fas fa-arrow-down"></i>@php $perNfsSemana *= -1;@endphp
                                @else<span class="text-warning"><i class="fas fa-square fa-2xs"></i>
                                @endif
                                {{Helper::formataPorcentagem($perNfsSemana)}}%
                                </span>
                                <span class="text-muted">Desde a semana passada</span>
                            </p>
                        </div>

                        <div class="chart">
                            <canvas id="visitors-chart" height="190" width="639" style="display: block; width: 639px; height: 190px;" class="chartjs-render-monitor"></canvas>
                        </div>
                        <div class="d-flex flex-row justify-content-end">
                            <span class="mr-2">
                                <i class="fas fa-square text-primary"></i> Esta Semana
                            </span>
                            <span>
                                <i class="fas fa-square text-gray"></i> Semana Passada
                            </span>
                        </div>
            </x-adminlte-card>
        </div>
        <div class="col-md-6">
            <x-adminlte-card title="Resumo Geral da Empresa no Mês" theme="navy" theme-mode="outline">
                <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                    <p class="text-teal text-xl">
                    <i class="fa-solid fa-money-bill-1"></i>
                    </p>
                    <p class="d-flex flex-column text-right">
                    @if($perVlrNfsMes > 0)<span class="font-weight-bold text-success"><i class="fa-solid fa-arrow-up"></i>
                    @elseif($perVlrNfsMes < 0)<span class="font-weight-bold text-danger"><i class="fa-solid fa-arrow-down"></i>@php $perVlrNfsMes *= -1;@endphp
                    @else<span class="font-weight-bold text-warning"><i class="fas fa-square fa-2xs"></i>
                    @endif
                    {{Helper::formataPorcentagem($perVlrNfsMes)}}%
                    </span>
                    <span class="text-muted">Valor de NFS-e Emitidas</span>
                    </p>
                </div>
                <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                    <p class="text-lightblue text-xl">
                    <i class="fa-solid fa-file-invoice"></i>
                    </p>
                    <p class="d-flex flex-column text-right">
                    @if($qtdOsMes > 0)<span class="font-weight-bold text-success"><i class="fa-solid fa-arrow-up"></i>
                    @elseif($qtdOsMes < 0)<span class="font-weight-bold text-danger"><i class="fa-solid fa-arrow-down"></i>@php $qtdOsMes *= -1;@endphp
                    @else<span class="font-weight-bold text-warning"><i class="fas fa-square fa-2xs"></i>
                    @endif
                    {{Helper::formataPorcentagem($qtdOsMes)}}%
                    </span>
                    <span class="text-muted">OS Geradas</span>
                    </p>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-0">
                    <p class="text-purple text-xl">
                    <i class="fa-solid fa-users"></i>
                    </p>
                    <p class="d-flex flex-column text-right">
                    @if($qtdCliMes > 0)<span class="font-weight-bold text-success"><i class="fa-solid fa-arrow-up"></i>
                    @elseif($qtdCliMes < 0)<span class="font-weight-bold text-danger"><i class="fa-solid fa-arrow-down"></i>@php $qtdCliMes *= -1;@endphp
                    @else<span class="font-weight-bold text-warning"><i class="fas fa-square fa-2xs"></i>
                    @endif
                    {{Helper::formataPorcentagem($qtdCliMes)}}%
                    </span>
                    <span class="text-muted">Novos Clientes</span>
                    </p>
                </div>
            </x-adminlte-card>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <x-adminlte-card title="Últimas OS Abertas" theme="navy" theme-mode="outline">
                <x-adminlte-datatable id="table-os" :heads="$headsOS" :config="$configOS" theme="light" striped hoverable>
                    @foreach($dadosOS as $os)
                        @php 
                            $dataEmp = DB::table('cadastro_empresas')->where('empresa_codigo', $os->os_emp)->get();
                            $dataCli = DB::table('cadastro_clientes')->where('cliente_codigo', $os->os_cli_fatura)->get();
                        @endphp
                        <tr>
                            <td><a href="{{route('situacaoOS.carregaOS', ['empresa' => $os->os_emp, 'cliente' => $os->os_cli, 'nos' => $os->os_nos, 'estagioAPP' => 'PRINCIPAL'])}}">{{$os->os_nos.' - '.Helper::formataDataHoraParaData($os->os_dha)}}</a></td>
                            <td>{{$os->os_emp.' - '.$dataEmp[0]->empresa_nome}}</td>
                            <td>{{$os->os_cli_fatura.' - '.$dataCli[0]->cliente_nome}}</td>
                            @if($os->os_sts == 'F')
                            <td style="text-align: center;"><span class="badge badge-success">Finalizado</span></td>
                            @elseif($os->os_sts == 'C')
                            <td style="text-align: center;"><span class="badge badge-danger">Cancelado</span></td>
                            @else
                            <td style="text-align: center;"><span class="badge badge-info">Andamento</span></td>
                            @endif
                            <td style="text-align: right;">{{Helper::formataValorMonetario($os->os_vlt)}}</td>
                        </tr>
                    @endforeach
                </x-adminlte-datatable>

                <x-slot name="footerSlot">
                    <form method="get" action="{{ route('home.emissaoOS') }}" style="float: left;">
                        <x-adminlte-button label="Nova OS" theme="info" icon="fa-solid fa-plus" type="submit"/>
                    </form>
                    <form method="get" action="{{ route('situacaoOS.consulta', ['statusOS' => 'T']) }}" style="float: right;">
                        <x-adminlte-button label="Consultar OS" theme="info" icon="fa-regular fa-eye" type="submit"/>
                    </form>
                </x-slot>
            </x-adminlte-card>
        </div>
        <div class="col-md-6">
            <x-adminlte-card title="Últimas NFS-e Geradas" theme="navy" theme-mode="outline">
                <x-adminlte-datatable id="table-nfs" :heads="$headsNFS" :config="$configNFS" theme="light" striped hoverable>
                    @foreach($dadosNFS as $nfs)
                        @php 
                            $dataEmp = DB::table('cadastro_empresas')->where('empresa_codigo', $nfs->nfs_emp)->get();
                        @endphp
                        <tr>
                            <td>{{$nfs->nfs_nnfs.'-'.$nfs->nfs_snfs}}</td>
                            <td>{{$nfs->nfs_nfhdr_num_ped}}</td>
                            <td>{{$nfs->nfs_emp.' - '.$dataEmp[0]->empresa_nome}}</td>
                            <td>{{$nfs->nfs_cli.' - '.$nfs->nfs_nom_tom}}</td>
                            @if($nfs->nfs_sts == 'G')
                            <td style="text-align: center;"><span class="badge badge-success">NFS-e Gerada</span></td>
                            @elseif($nfs->nfs_sts == 'C')
                            <td style="text-align: center;"><span class="badge badge-danger">NFS-e Cancelada</span></td>
                            @elseif($nfs->nfs_sts == 'E')
                            <td style="text-align: center;"><span class="badge badge-warning" style="color: #fff !important;">NFS-e Erro</span></td>
                            @else
                            <td style="text-align: center;"><span class="badge badge-info">NFS-e Iniciada</span></td>
                            @endif
                            <td style="text-align: right;">{{Helper::formataValorMonetario($nfs->nfs_vlr_tot)}}</td>
                        </tr>
                    @endforeach
                </x-adminlte-datatable>
                <x-slot name="footerSlot">
                    <form method="get" action="{{route('reemissaoNF.consultaReemissaoNF')}}" style="float: right;">
                        <x-adminlte-button label="Consultar NFS-e" theme="info" icon="fa-regular fa-eye" type="submit"/>
                    </form>
                </x-slot>
            </x-adminlte-card>
        </div>
    </div>
</div>
@stop

@section('plugins.Chartjs', true)

@section('css')
@stop

@section('js')
<script>
    $(function () {

        //-------------
        //- LINE CHART -
        //--------------

        var lineChartOptions = {
            maintainAspectRatio : false,
            responsive : true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                gridLines : {
                    display : false,
                }
                }],
                yAxes: [{
                gridLines : {
                    display : false,
                }
                }]
            }
        }

        var lineChartData = {
            labels  :  {!! $diasSemana !!},
            datasets: [{
                data: {!! $qtdSemAtu !!},
                backgroundColor: 'transparent',
                borderColor: '#007bff',
                pointBorderColor: '#007bff',
                pointBackgroundColor: '#007bff',
                fill: false
                // pointHoverBackgroundColor: '#007bff',
                // pointHoverBorderColor    : '#007bff'
            },
            {
                data: {!! $qtdSemPas !!},
                backgroundColor: 'tansparent',
                borderColor: '#ced4da',
                pointBorderColor: '#ced4da',
                pointBackgroundColor: '#ced4da',
                fill: false
                // pointHoverBackgroundColor: '#ced4da',
                // pointHoverBorderColor    : '#ced4da'
            }]
        }

        var lineChartCanvas = $('#visitors-chart').get(0).getContext('2d');
        var lineChartOptions = $.extend(true, {}, lineChartOptions);
        var lineChartData = $.extend(true, {}, lineChartData);
        lineChartData.datasets[0].fill = false;
        lineChartData.datasets[1].fill = false;
        lineChartOptions.datasetFill = false;

        var lineChart = new Chart(lineChartCanvas, {
        type: 'line',
        data: lineChartData,
        options: lineChartOptions
        });
    });
</script>
@stop
