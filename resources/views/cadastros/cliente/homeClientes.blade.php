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
 
    <x-adminlte-card title="Novos Clientes nos Últimos Seis Meses" theme="navy" theme-mode="outline" icon="fa-solid fa-chart-column" header-class="text-uppercase rounded-bottom border-info">
        <div class="chart">
            <canvas id="stackedBarChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
        </div>
    </x-adminlte-card>

@stop

<!-- Chamada dos Plugins usados na app -->  
@section('plugins.Chartjs', true)
@section('plugins.Sweetalert2', true)
@section('plugins.toastr', true)

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
        //- STACKED BAR CHART -
        //---------------------
        var stackedBarChartData = {
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

        var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')

        var stackedBarChartOptions = {
        responsive              : true,
        maintainAspectRatio     : false,
        scales: {
            xAxes: [{
            stacked: true,
            }],
            yAxes: [{
            stacked: true
            }]
        }
        }

        new Chart(stackedBarChartCanvas, {
        type: 'bar',
        data: stackedBarChartData,
        options: stackedBarChartOptions
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
