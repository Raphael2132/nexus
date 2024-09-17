@extends('adminlte::page')

@section('title', 'Reemissão de NF')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Faturamento de Notas</h4>
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
    //Define as variaveis de sessão aqui na view por que depois de 2 redirect elas são destruidas
    session(['glo_where_reemissao_nf' => $glo_where_reemissao_nf]);

    $heads = [
        'Empresa',
        'Cliente',
        'Data Emissao',
        'Pedido / OS',
        'Nota',
        'Valor',
        'Tipo',
        'Situação',
        ['label' => 'Impressão', 'no-export' => true, 'width' => 10]
    ];
    $config = [
        'lengthMenu' => [5, 10, 25, 50, 100],
        'pageLength' => 10,
        'language' => Helper::dataTableLangPtBR(),
        'order' => [[0, 'desc'],[3, 'desc']],
        'columns' => [
            null, 
            null, 
            null, 
            null, 
            null, 
            null, 
            null, 
            null, 
            ['orderable' => false]
        ],
    ];
@endphp

<x-adminlte-card title="Consulta de Notas para Reemissão" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable with-buttons>
        @foreach($dadosHeader as $header)
            @php 
                $data = DB::table('cadastro_empresas')->where('empresa_codigo', $header->nfhdr_emp)->get();
                $data_cli = DB::table('cadastro_clientes')->where('cliente_codigo', $header->nfhdr_cli)->get();
                $dadosXmlNfsEnv = DB::table('faturamento_nfs_xml_envios')->where('nfsenv_emp',$header->nfhdr_emp)->where('nfsenv_num',$header->nfhdr_num_nf)->get();
                
                $moduloRPS = DB::table('parametros_sistema_modulos')->where('modulo_empresa_codigo', $header->nfhdr_emp)->first();
                $geraRPS = DB::table('parametros_fat_nfs')->where('parnfs_empresa', $header->nfhdr_emp)->first();
                
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
                <td class="max-width-sts"><span class="badge badge-danger">NFS-e Não Enviada: {{$dadosXmlNfsEnv[0]->nfsenv_obs}}</span> <a class="btn btn-outline-nexus btn-sm" href="{{route('emissaoNF.gerarNF', ['empresa' => $header->nfhdr_emp, 'nfReemissao' => $header->nfhdr_num, 'origem' => 'REEMISSAO'])}}">Reenviar</a></td>
                @elseif($stsNF == 2)
                <td class="max-width-sts"><span class="badge badge-danger">NFS-e Rejeitada: Erro {{$dadosXmlNfsEnv[0]->nfsenv_sts_emi.' - '.$dadosXmlNfsEnv[0]->nfsenv_obs}}</span> <a class="btn btn-outline-nexus btn-sm" href="{{route('emissaoNF.gerarNF', ['empresa' => $header->nfhdr_emp, 'nfReemissao' => $header->nfhdr_num, 'origem' => 'REEMISSAO'])}}">Reenviar</a></td>
                @elseif($stsNF == 3)
                <td class="max-width-sts"><span class="badge badge-success">{{$dadosXmlNfsEnv[0]->nfsenv_obs}}</span></td>
                @else
                <td class="max-width-sts"><span class="badge badge-info">Geração Iniciada</span> <a class="btn btn-outline-nexus btn-sm" href="{{route('emissaoNF.gerarNF', ['empresa' => $header->nfhdr_emp, 'nfReemissao' => $header->nfhdr_num, 'origem' => 'REEMISSAO'])}}">Reenviar</a></td>
                @endif
                @if($stsNF == 3)
                <td class="d-flex justify-content-center"><a class="btn btn-outline-nexus btn-sm" href="{{route('impresaoNF.nfsePDF',['empresa' => $header->nfhdr_emp, 'numControle' => $header->nfhdr_num, 'appOrigem' => 'IMPRESSAO'])}}" target="_blank">Abrir NFS-e</a></td>
                @elseif($stsNF == 2 || $stsNF == 1)
                    @if($moduloRPS->modulo_emissao_rps == "S" && $geraRPS->parnfs_impressao_rps == "S")
                    <td class="d-flex justify-content-center"><a class="btn btn-outline-nexus btn-sm" href="{{route('impresaoRPS.rpsPDF',['empresa' => $header->nfhdr_emp, 'numControle' => $header->nfhdr_num, 'appOrigem' => 'REEMISSAO'])}}" target="_blank">Abrir RPS</a></td>
                    @else
                    <td></td>
                    @endif
                @else
                <td></td>
                @endif
            </tr>
        @endforeach
    </x-adminlte-datatable>
    <x-slot name="footerSlot">
        <div class="d-flex justify-content-between w-100">
            <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.emissaoNF') }}'" label="Emissão de NF" theme="" icon=""/>
            <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.reemissaoNF') }}'" label="Voltar" theme="" icon=""/>
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
