@extends('adminlte::page')

@section('title', 'Consulta Reemissão Simplificada de NFS-e')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h4 style="margin-bottom: 0px !important;">Faturamento</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">
                    <a href="{{route('home.reemissaoSimpNF')}}">Filtro Reemissão Simplificada</a>
                </li>
                <li class="breadcrumb-item active">Consulta Reemissão Simplificada</li>
            </ol>
        </div>
    </div>
@stop


@section('content')

@php
$heads = [
    'Empresa',
    'Cliente',
    'Data Emissao',
    'ES',
    'Nota',
    'Valor',
    'Tipo',
    'Situação',
    ['label' => 'Impressão', 'no-export' => true, 'width' => 10],
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
    'columns' => [null, null, null, null, null, null, null, null, ['orderable' => false]],
];
@endphp

<x-adminlte-card title="Reemissão Simplificada de NFS-e" theme="navy" collapsible maximizable>
    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable with-buttons>
        @foreach($dadosHeader as $header)
            @php 
                $data = DB::table('cadastro_empresas')->where('empresa_codigo', $header->nfhdr_emp)->get();
                $data_cli = DB::table('cadastro_clientes')->where('cliente_codigo', $header->nfhdr_cli)->get();
                $dadosXmlNfsEnv = DB::table('faturamento_nfs_xml_envios')->where('nfsenv_emp',$header->nfhdr_emp)->where('nfsenv_num',$header->nfhdr_num_nf)->get();
                
                if(!empty($header->nfhdr_dt_nf)){
                    $dataNF = Helper::formataData($header->nfhdr_dt_nf);
                }else{
                    $dataNF = '';
                }

                if(empty($dadosXmlNfsEnv[0]->nfsenv_sts)){
                    $stsNF = 4;
                }elseif($dadosXmlNfsEnv[0]->nfsenv_sts == 1){
                    $stsNF = 1;
                }elseif($dadosXmlNfsEnv[0]->nfsenv_sts == 2){
                    $stsNF = 2;
                }else{
                    $stsNF = 3;
                }
            @endphp
            <tr>
                <td>{{$header->nfhdr_emp.' - '.$data[0]->empresa_nome}}</td>
                <td>{{$header->nfhdr_cli.' - '.$data_cli[0]->cliente_nome}}</td>
                <td>{{$dataNF}}</td>
                <td>{{$header->nfhdr_num_ped}}</td>
                <td>{{$header->nfhdr_num_nf.'-'.$header->nfhdr_ser_nf}}</td>
                <td>{{Helper::formataValorMonetario($header->nfhdr_vlr_tot_nf)}}</td>
                <td>NFS-e</td>
                @if($stsNF == 1)
                <td class="max-width-sts"><span class="badge badge-danger">NFS-e Não Enviada: {{$dadosXmlNfsEnv[0]->nfsenv_obs}}</span> <a class="btn btn-outline-info btn-sm" href="{{route('emissaoNF.gerarNF', ['empresa' => $header->nfhdr_emp, 'cliente' => $header->nfhdr_cli, 'nfSelecionada' => $header->nfhdr_num, 'origem' => 'REEMISSAO_SIMP'])}}">Reenviar</a></td>
                @elseif($stsNF == 2)
                <td class="max-width-sts"><span class="badge badge-danger">NFS-e Rejeitada: Erro {{$dadosXmlNfsEnv[0]->nfsenv_sts_emi.' - '.$dadosXmlNfsEnv[0]->nfsenv_obs}}</span> <a class="btn btn-outline-info btn-sm" href="{{route('emissaoNF.gerarNF', ['empresa' => $header->nfhdr_emp, 'cliente' => $header->nfhdr_cli, 'nfSelecionada' => $header->nfhdr_num, 'origem' => 'REEMISSAO_SIMP'])}}">Reenviar</a></td>
                @elseif($stsNF == 3)
                <td class="max-width-sts"><span class="badge badge-success">{{$dadosXmlNfsEnv[0]->nfsenv_obs}}</span></td>
                @else
                <td class="max-width-sts"><span class="badge badge-info">Geração Iniciada</span> <a class="btn btn-outline-info btn-sm" href="{{route('emissaoNF.gerarNF', ['empresa' => $header->nfhdr_emp, 'cliente' => $header->nfhdr_cli, 'nfSelecionada' => $header->nfhdr_num, 'origem' => 'REEMISSAO_SIMP'])}}">Reenviar</a></td>
                @endif
                @if($stsNF != 4)
                <td><a class="btn btn-outline-info btn-sm" href="{{route('impresaoNF.nfsePDF',['empresa' => $header->nfhdr_emp, 'numControle' => $header->nfhdr_num])}}" target="_blank">Abrir NFS-e</a></td>
                @else
                <td></td>
                @endif
            </tr>
        @endforeach
    </x-adminlte-datatable>
    <x-slot name="footerSlot">
        <div class="d-flex justify-content-between w-100">
            <x-adminlte-button class="btn-flat" type="button" onclick="window.location='{{ route('home.emissaoSimpNFS') }}'" label="Nova NFS-e" theme="info" icon="fa-solid fa-plus"/>
            <x-adminlte-button class="btn-flat" type="button" onclick="window.location='{{ route('home.reemissaoSimpNF') }}'" label="Voltar" theme="info" icon=""/>
        </div>
    </x-slot>
</x-adminlte-card>
@stop

@section('css')
<style>
    .max-width-sts {
        max-width: 50ch;
        white-space: normal; /* Permite quebra de linha */
        overflow-wrap: break-word; /* Permite quebras de linha apenas em espaços */
        word-break: keep-all; /* Evita quebras de linha no meio de palavras */
    }
    .badge, .badge-success {
        white-space: normal; /* Permite quebra de linha */
        overflow-wrap: break-word; /* Permite quebras de linha apenas em espaços */
        word-break: keep-all; /* Evita quebras de linha no meio de palavras */
    }
</style>
@stop

@section('js')
@stop
