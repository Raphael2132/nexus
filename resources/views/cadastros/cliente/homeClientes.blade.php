@extends('adminlte::page')

@section('title', 'Cadastro de Clientes')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Cadastros</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Clientes</li>
        </ol>
    </div>
</div>
@stop

@section('content')

    <div class="row">
        <div class="col-md-3"> 
            <x-adminlte-small-box :title="$cliTot" text="Total de Clientes" icon="fas fa-users" theme="info" url="{{ route('clientes',['tipo' => 'T']) }}" url-text="Detalhes de Todos Clientes"/>
        </div>
        <div class="col-md-3"> 
            <x-adminlte-small-box :title="$cliFisico" text="Pessoa Física" icon="fas fa-user" theme="success" url="{{ route('clientes',['tipo' => 'F']) }}" url-text="Detalhes de Pessoas Físicas"/>
        </div>
        <div class="col-md-3"> 
            <x-adminlte-small-box :title="$cliJuridico" text="Pessoa Jurídica" icon="fas fa-user-tie" theme="danger" url="{{ route('clientes',['tipo' => 'J']) }}" url-text="Detalhes de Pessoas Jurídicas"/>
        </div>
        <div class="col-md-3"> 
            <x-adminlte-small-box title="Cadastro" text="Clientes" icon="fas fa-user-plus" theme="primary" url="{{ route('cliente.cadastro') }}" url-text="Cadastrar Cliente"/>
        </div>
    </div>
 
    <div class="row">
        <div class="col-md-6"> 
            <x-adminlte-card title="Novos Clientes nos Últimos Seis Meses" icon="fa-solid fa-chart-column" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
                <div class="chart">
                    <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </x-adminlte-card>
        </div>
        <div class="col-md-6"> 
            <x-adminlte-card title="Clientes dos Últimos Seis Meses" icon="" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
                @php
                    $heads = [
                        'Data Cadastro',
                        'Cliente',
                        'Tipo',
                        'CPF / CNPJ',
                        'Email',
                        ['label' => 'Opção', 'no-export' => true, 'width' => 5],
                    ];
                    
                    $config = [
                        'lengthMenu' => [ 5, 10, 25, 50],
                        'pageLength' => 5,
                        'language' => Helper::dataTableLangPtBR(),
                        'pagingType' => 'full_numbers',
                        'order' => [[0, 'desc'],[1, 'asc']],
                        'columns' => [
                            null, 
                            null, 
                            null, 
                            null, 
                            null, 
                            ['orderable' => false]
                        ],
                    ];
                @endphp
                <x-adminlte-datatable id="tabela-principal" :heads="$heads" :config="$config" theme="light" striped hoverable with-buttons>
                    @foreach ($clientes as $cliente)
                        @php
                            if($cliente->cliente_tipo_cadastro == 'F'){
                                $tipo = "Fornecedor";
                            }else{
                                $tipo = "Cliente";
                            }

                            if($cliente->cliente_tipo_pessoa == 'F'){
                                $cpfCnpj = Helper::mascaraCPF($cliente->cliente_cpf_cnpj);
                            }else{
                                $cpfCnpj = Helper::mascaraCNPJ($cliente->cliente_cpf_cnpj);
                            }
                        @endphp
                        <tr>
                            <td>{{Helper::formataData($cliente->cliente_dt_inc)}}</td>
                            <td>{{$cliente->cliente_codigo.' - '.$cliente->cliente_nome}}</td>
                            <td>{{$tipo}}</td>
                            <td>{{$cpfCnpj}}</td>
                            <td>{{$cliente->cliente_email}}</td>
                            <td>
                                <nobr class="d-flex justify-content-center">
                                    <form method="get" action="" style="float: left;">
                                        @csrf 
                                        <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Editar Registro" value="Edit" type="submit">
                                            <i class="fa fa-lg fa-fw fa-pen"></i>
                                        </button>
                                    </form>
                                </nobr>
                            </td>
                        </tr>
                    @endforeach
                </x-adminlte-datatable>
            </x-adminlte-card>
        </div>
    </div>

@stop

<!-- Chamada dos Plugins usados na app -->  
@section('plugins.Chartjs', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('css')
@stop

@section('js')

<script>
    $(function () {
        /* ChartJS
        * -------
        * Here we will create a few charts using ChartJS.
        */

        //---------------------
        // BAR CHART -
        //---------------------
        var barChartData = {
        labels  : {!! $meses !!},
        datasets: [
            {
            label               : 'Pessoa Física',
            backgroundColor     : 'rgba(60,141,188,0.9)',
            borderColor         : 'rgba(60,141,188,0.8)',
            pointRadius          : false,
            pointColor          : '#3b8bba',
            pointStrokeColor    : 'rgba(60,141,188,1)',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: 'rgba(60,141,188,1)',
            data                : {!! $grafF !!}
            },
            {
            label               : 'Pessoa Jurídica',
            backgroundColor     : 'rgba(210, 214, 222, 1)',
            borderColor         : 'rgba(210, 214, 222, 1)',
            pointRadius         : false,
            pointColor          : 'rgba(210, 214, 222, 1)',
            pointStrokeColor    : '#c1c7d1',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: 'rgba(220,220,220,1)',
            data                : {!! $grafJ !!}
            },
        ]}

        var barChartCanvas = $('#barChart').get(0).getContext('2d')

        var barChartOptions = {
        responsive              : true,
        maintainAspectRatio     : false,
        scales: {
            xAxes: [{
            stacked: false,
            }],
            yAxes: [{
                stacked: false,
                ticks: {
                    beginAtZero: true, // Começar do zero
                    //stepSize: 1,       // Define o incremento
                    callback: function(value) {
                        if (value % 1 === 0) {
                            return value; // Exibir apenas números inteiros
                        }
                    }
                }
            }]
        }
        }

        new Chart(barChartCanvas, {
        type: 'bar',
        data: barChartData,
        options: barChartOptions
        })
    })
</script>

<script>
    @if(Session::has('success'))
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        Toast.fire({
            icon: "success",
            title: "{{ session('success') }}"
        });
    @endif
</script>
@stop
