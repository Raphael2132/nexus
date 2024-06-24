@extends('adminlte::page')

@section('title', 'Consulta Reemissão de NF')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h1>Faturamento</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">
                    <a href="{{route('home.reemissaoNF')}}">Filtro Reemissão de NF</a>
                </li>
                <li class="breadcrumb-item active">Consulta Reemissão de NF</li>
            </ol>
        </div>
    </div>
@stop


@section('content')

@php
$heads = [
    'Empresa',
    'Data Emissao',
    'Cliente',
    'Pedido / OS',
    'Nota',
    'Valor',
    'Tipo',
    'Situação',
    'Impressão'
];
$config = [
    'lengthMenu' => [ 10, 25, 50, 100],
    'language' => [
        'decimal' =>        '',
        'emptyTable' =>     'Sem dados disponíveis na tabela',
        'info' =>           'Mostrando _START_ a _END_ de _TOTAL_ registros',
        'infoEmpty' =>      'Mostrando 0 a 0 de 0 registros',
        'infoFiltered' =>   '(Filtrado do total de _MAX_ registros)',
        'infoPostFix' =>    '',
        'thousands' =>      ',',
        'lengthMenu' =>     'Mostrar _MENU_ registros',
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
];
@endphp

<x-adminlte-card title="Reemissão de NF" theme="navy" collapsible maximizable>
    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable with-buttons>
        @foreach($dadosHeader as $header)
            @php 
                $data = DB::table('cadastro_empresas')->where('empresa_codigo', $header->nfhdr_emp)->get();

                $data_cli = DB::table('cadastro_clientes')->where('cliente_codigo', $header->nfhdr_cli)->get();
            @endphp
            <tr>
                <td>{{$header->nfhdr_emp.' - '.$data[0]->empresa_nome}}</td>
                <td>{{Helper::formataData($header->nfhdr_dt_nf)}}</td>
                <td>{{$header->nfhdr_cli.' - '.$data_cli[0]->cliente_nome}}</td>
                <td>{{$header->nfhdr_num_ped}}</td>
                <td>{{$header->nfhdr_num_nf.'-'.$header->nfhdr_ser_nf}}</td>
                <td>{{Helper::formataValorMonetario($header->nfhdr_vlr_tot)}}</td>
                <td>NFS-e</td>
                <td><b class="text-danger">NFS-e Rejeitada</b><a href="{{route('emissaoNF.gerarNF', ['empresa' => $header->nfhdr_emp, 'cliente' => $header->nfhdr_cli, 'nfSelecionada' => $header->nfhdr_num])}}"> Reenviar</a></td>
                <td><a href="">Abrir NFS-e</a></td>
            </tr>
        @endforeach
    </x-adminlte-datatable>
    <x-slot name="footerSlot">
    </x-slot>
</x-adminlte-card>
@stop

@section('css')
@stop

@section('js')
@stop
