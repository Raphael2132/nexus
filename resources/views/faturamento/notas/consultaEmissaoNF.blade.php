@extends('adminlte::page')

@section('title', 'Emissão de NFS-e')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Faturamento de Notas</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.emissaoNF')}}">Filtro Emissão de NF</a>
            </li>
            <li class="breadcrumb-item active">Consulta Emissão de NF</li>
        </ol>
    </div>
</div>
@stop


@section('content')

@php
    //Define as variaveis de sessão aqui na view por que depois de 2 redirect elas são destruidas
    session(['glo_where_emissao_nf_completo' => $glo_where_emissao_nf_completo]);
    session(['glo_where_emissao_nf_semi' => $glo_where_emissao_nf_semi]);

    $heads = [
        'Empresa',
        'Cliente',
        'Quantidade Notas',
        'Valor Total'
    ];
    $config = [
        'lengthMenu' => [5, 10, 25, 50, 100],
        'pageLength' => 10,
        'language' => Helper::dataTableLangPtBR(),
        'order' => [[1, 'asc'],[0, 'asc']],
    ];
@endphp

<x-adminlte-card title="Consulta de Notas para Emissão" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable with-buttons>
        @foreach($dadosHeader as $header)
            @php 
                $data = DB::table('cadastro_empresas')->where('empresa_codigo', $header->nfhdr_emp)->get();

                $data_cli = DB::table('cadastro_clientes')->where('cliente_codigo', $header->nfhdr_cli)->get();
            @endphp
            <tr>
                <td>{{$header->nfhdr_emp.' - '.$data[0]->empresa_nome}}</td>
                <td><a href="{{route('emissaoNF.painelNF',['empresa' => $header->nfhdr_emp, 'cliente' => $header->nfhdr_cli])}}">{{$header->nfhdr_cli.' - '.$data_cli[0]->cliente_nome}}</a></td>
                <td>{{$header->qtd_reg}}</td>
                <td>{{Helper::formataValorMonetario($header->total_pedidos)}}</td>
            </tr>
        @endforeach
    </x-adminlte-datatable>
    <x-slot name="footerSlot">
        <div style="float: right;">
            <x-adminlte-button type="button" onclick="window.location='{{ route('home.emissaoNF') }}'" label="Voltar" theme="info" icon=""/>
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
