@extends('adminlte::page')

@section('title', 'Orçamentos')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h4 style="margin-bottom: 0px !important;">Lançamento de OS</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">
                    <a href="{{route('home.orcamentoOS')}}">Filtro de Orçamento</a>
                </li>
                <li class="breadcrumb-item active">Consulta Orçamentos Emitidos</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
@php
    $heads = [
        'Empresa',
        'Cliente',
        'OS',
        'Orçamento',
        'Data',
        ['label' => 'Impressão', 'no-export' => true, 'width' => 10],
    ];
    $config = [
        'lengthMenu' => [ 5, 10, 25, 50, 100],
        'pageLength' => 10,
        'language' => Helper::dataTableLangPtBR(),
        'pagingType' => 'full_numbers',
        'order' => [[0, 'asc'],[2, 'desc']],
        'columns' => [null, null, null, null, null, ['orderable' => false]],
    ];
@endphp
<x-adminlte-card title="Consulta da Situação das OS Emitidas" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable with-buttons>
        @foreach ($dadosOrc as $orc)
            @php
                $emp = DB::table('cadastro_empresas')->where('empresa_codigo',$orc->orc_emp)->get();
                $empresa = $orc->orc_emp.' - '.$emp[0]->empresa_nome;

                $cli = DB::table('cadastro_clientes')->where('cliente_codigo',$orc->orc_cli)->get();
                $cliente = $orc->orc_cli.' - '.$cli[0]->cliente_nome;
            @endphp
            <tr>
                <td>{{ $empresa }}</td>
                <td>{{ $cliente }}</td>
                <td>{{ $orc->orc_nos }}</td>
                <td>{{ $orc->orc_num_orc }}</td>
                <td>{{ Helper::formataData($orc->orc_dt_orc) }}</td>     
                <td class="d-flex justify-content-center"><a class="btn btn-outline-nexus btn-sm" href="{{ route('painelOS.orcamentoPDF', ['empresa' => $orc->orc_emp, 'numOS' => $orc->orc_nos]) }}" target="_blank">Abrir Orçamento</a></td>
            </tr>
        @endforeach
    </x-adminlte-datatable>
    <x-slot name="footerSlot">
        <div class="d-flex justify-content-between w-100">
            <form method="get" action="{{ route('home.emissaoOS') }}">
                <x-adminlte-button class="btn-nexus" label="Nova OS" theme="" icon="fa-solid fa-file-circle-plus" type="submit"/>
            </form>
            <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.orcamentoOS') }}'" label="Voltar" theme="" icon=""/>
        </div>
    </x-slot>
</x-adminlte-card>
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('css')
@stop

@section('js')
@stop
