@extends('adminlte::page')

@section('title', 'Situação de OS')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Lançamento de OS</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Situação de OS</li>
            @php   
                $dadosEmp = HelperDataSelect::buscaDadosEmpresa(session('glo_empresa_exibicao_home'));
            @endphp
            <li class="breadcrumb-item active">{{ $dadosEmp->empresa_codigo.' - '.$dadosEmp->empresa_nome}}</li>
        </ol>
    </div>
</div>
@stop

@section('content')
@php
$perTotFormat = number_format($perTot,2,",",".").'%';
$perServFormat = number_format($perServ,2,",",".").'%';

$valSumTotFormat = 'R$'.number_format($valSumTot,2,",",".");
$valSumServFormat = 'R$'.number_format($valSumServ,2,",",".");

$perQtdFinFormat = number_format($perQtdFin,2,",",".").'%';
$perQtdCanFormat = number_format($perQtdCan,2,",",".").'%';
@endphp
<div class="row">
    <div class="col-md-3"> 
        <x-adminlte-small-box :title="$osTot" text="Total de OS" icon="fas fa-file-invoice-dollar" theme="primary" url="{{ route('situacaoOS.consulta', ['statusOS' => 'T']) }}" url-text="Detalhes Gerais de OS"/>
    </div>
    <div class="col-md-3"> 
        <x-adminlte-small-box :title="$osAberta" text="OS em Aberto" icon="fas fa-file-pen" theme="info" url="{{ route('situacaoOS.consulta', ['statusOS' => 'A']) }}" url-text="Detalhes de OS em Aberto"/>
    </div>
    <div class="col-md-3"> 
        <x-adminlte-small-box :title="$osFinalizada" text="OS Finalizadas" icon="fas fa-solid fa-file-circle-check" theme="success" url="{{ route('situacaoOS.consulta', ['statusOS' => 'F']) }}" url-text="Detalhes de OS Finalizadas"/>
    </div>
    <div class="col-md-3"> 
        <x-adminlte-small-box :title="$osCancelada" text="OS Canceladas" icon="fas fa-file-circle-exclamation" theme="danger" url="{{ route('situacaoOS.consulta', ['statusOS' => 'C']) }}" url-text="Detalhes de OS Canceladas"/>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <x-adminlte-card title="Relatório de recapitulação mensal" icon="fa-solid fa-chart-line" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="chart">
                        <canvas id="lineChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;" class="chartjs-render-monitor"></canvas>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="chart">
                        <canvas id="stackedBarChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>

            <x-slot name="footerSlot">
                <div class="row">
                    <div class="col-sm-3 col-6">
                        <div class="description-block border-right">
                            @if($perTot > 0 && $perTot < 50)
                            <span class="description-percentage text-success"><i class="fas fa-angle-up"></i> {{$perTotFormat}}</span>
                            @elseif($perTot > 50)
                            <span class="description-percentage text-success"><i class="fas fa-angles-up"></i> {{$perTotFormat}}</span>
                            @elseif($perTot < 0 && $perTot <= -50)
                            <span class="description-percentage text-danger"><i class="fas fa-angles-down"></i> {{$perTotFormat}}</span>
                            @elseif($perTot < 0)
                            <span class="description-percentage text-danger"><i class="fas fa-angle-down"></i> {{$perTotFormat}}</span>
                            @else
                            <span class="description-percentage text-warning"><i class="fas fa-square fa-2xs"></i> {{$perTotFormat}}</span>
                            @endif
                            <h5 class="description-header">{{$valSumTotFormat}}</h5>
                            <span class="description-text">VALOR TOTAL</span>
                        </div>
                    </div>

                    <div class="col-sm-3 col-6">
                        <div class="description-block border-right">
                            @if($perServ > 0 && $perServ < 50)
                            <span class="description-percentage text-success"><i class="fas fa-angle-up"></i> {{$perServFormat}}</span>
                            @elseif($perServ > 50)
                            <span class="description-percentage text-success"><i class="fas fa-angles-up"></i> {{$perServFormat}}</span>
                            @elseif($perServ < 0 && $perServ <= -50)
                            <span class="description-percentage text-danger"><i class="fas fa-angles-down"></i> {{$perServFormat}}</span>
                            @elseif($perServ < 0)
                            <span class="description-percentage text-danger"><i class="fas fa-angle-down"></i> {{$perServFormat}}</span>
                            @else
                            <span class="description-percentage text-warning"><i class="fas fa-square fa-2xs"></i> {{$perServFormat}}</span>
                            @endif
                            <h5 class="description-header">{{$valSumServFormat}}</h5>
                            <span class="description-text">VALOR TOTAL SERVIÇOS</span>
                        </div>
                    </div>

                    <div class="col-sm-3 col-6">
                        <div class="description-block border-right">
                            @if($perQtdFin > 0 && $perQtdFin < 50)
                            <span class="description-percentage text-success"><i class="fas fa-angle-up"></i> {{$perQtdFinFormat}}</span>
                            @elseif($perQtdFin > 50)
                            <span class="description-percentage text-success"><i class="fas fa-angles-up"></i> {{$perQtdFinFormat}}</span>
                            @elseif($perQtdFin < 0 && $perQtdFin <= -50)
                            <span class="description-percentage text-danger"><i class="fas fa-angles-down"></i> {{$perQtdFinFormat}}</span>
                            @elseif($perQtdFin < 0)
                            <span class="description-percentage text-danger"><i class="fas fa-angle-down"></i> {{$perQtdFinFormat}}</span>
                            @else
                            <span class="description-percentage text-warning"><i class="fas fa-square fa-2xs"></i> {{$perQtdFinFormat}}</span>
                            @endif
                            <h5 class="description-header">{{$valSumFin}}</h5>
                            <span class="description-text">TOTAL DE OS FINALIZADAS</span>
                        </div>
                    </div>

                    <div class="col-sm-3 col-6">
                        <div class="description-block">
                            @if($perQtdCan > 0 && $perQtdCan < 50)
                            <span class="description-percentage text-success"><i class="fas fa-angle-up"></i> {{$perQtdCanFormat}}</span>
                            @elseif($perQtdCan > 50)
                            <span class="description-percentage text-success"><i class="fas fa-angles-up"></i> {{$perQtdCanFormat}}</span>
                            @elseif($perQtdCan < 0 && $perQtdCan <= -50)
                            <span class="description-percentage text-danger"><i class="fas fa-angles-down"></i> {{$perQtdCanFormat}}</span>
                            @elseif($perQtdCan < 0)
                            <span class="description-percentage text-danger"><i class="fas fa-angle-down"></i> {{$perQtdCanFormat}}</span>
                            @else
                            <span class="description-percentage text-warning"><i class="fas fa-square fa-2xs"></i> {{$perQtdCanFormat}}</span>
                            @endif
                            <h5 class="description-header">{{$valSumCan}}</h5>
                            <span class="description-text">TOTAL DE OS Canceladas</span>
                        </div>
                    </div>
                </div>
            </x-slot>
        </x-adminlte-card>
    </div>
</div>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.Sweetalert2', true)
@section('plugins.toastr', true)
@section('plugins.Chartjs', true)

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
            label               : 'OS Finalizadas',
            backgroundColor     : 'rgba(60,141,188,0.9)',
            borderColor         : 'rgba(60,141,188,0.8)',
            pointRadius          : false,
            pointColor          : '#3b8bba',
            pointStrokeColor    : 'rgba(60,141,188,1)',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: 'rgba(60,141,188,1)',
            data                : {!! $barFin !!}
            },
            {
            label               : 'OS Canceladas',
            backgroundColor     : 'rgba(210, 214, 222, 1)',
            borderColor         : 'rgba(210, 214, 222, 1)',
            pointRadius         : false,
            pointColor          : 'rgba(210, 214, 222, 1)',
            pointStrokeColor    : '#c1c7d1',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: 'rgba(220,220,220,1)',
            data                : {!! $barCan !!}
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

    
  $(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    //--------------
    //- AREA CHART -
    //--------------

    // Get context with jQuery - using jQuery's .get() method.

    var areaChartData = {
        labels  : {!! $meses !!},
      datasets: [
        {
          label               : 'Valor Total',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : 4,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : {!! $linTot !!}
        },
        {
          label               : 'Valor Total Serviços',
          backgroundColor     : 'rgba(210, 214, 222, 1)',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : 4,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : {!! $linSer !!}
        },
      ]
    }

    var areaChartOptions = {
      maintainAspectRatio : false,
      responsive : true,
      legend: {
        display: true
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

    //-------------
    //- LINE CHART -
    //--------------
    var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
    var lineChartOptions = $.extend(true, {}, areaChartOptions)
    var lineChartData = $.extend(true, {}, areaChartData)
    lineChartData.datasets[0].fill = false;
    lineChartData.datasets[1].fill = false;
    lineChartOptions.datasetFill = false

    var lineChart = new Chart(lineChartCanvas, {
      type: 'line',
      data: lineChartData,
      options: lineChartOptions
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

    @if(Session::has('error'))
        Swal.fire({
        confirmButtonColor: "#007bff",
        title: "Erro!!!",
        text: "{{ session('error') }}",
        icon: "error"
    });
    @endif
</script>
@stop
