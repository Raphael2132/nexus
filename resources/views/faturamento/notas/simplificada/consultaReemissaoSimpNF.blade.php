@extends('adminlte::page')

@section('title', 'Reemissão Simplificada de NFS-e')

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
    //Define as variaveis de sessão aqui na view por que depois de 2 redirect elas são destruidas
    session(['glo_where_reemissao_nf' => $glo_where_reemissao_nf]);

    $heads = [
        'Nota Oculta',
        'Empresa',
        'Cliente',
        'Data Emissao',
        'ES',
        'Nota',
        'Valor',
        'Tipo',
        'Situação',
        ['label' => 'Impressão / Email', 'no-export' => true, 'width' => 15],
    ];
    $config = [
        'lengthMenu' => [5, 10, 25, 50, 100],
        'pageLength' => 10,
        'language' => Helper::dataTableLangPtBR(),
        'pagingType' => 'full_numbers',
        'order' => [[0, 'desc']],
        'columns' => [
            ['orderable' => false, 'visible' => false], // Esconder primeira coluna
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

<x-adminlte-card title="Reemissão Simplificada de NFS-e" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable with-buttons>
        @foreach($dadosHeader as $header)
            @php 
                $data = DB::table('cadastro_empresas')->where('empresa_codigo', $header->nfhdr_emp)->get();
                $data_cli = DB::table('cadastro_clientes')->where('cliente_codigo', $header->nfhdr_cli)->get();
                $dadosXmlNfsEnv = DB::table('faturamento_nfs_xml_envios')->where('nfsenv_emp',$header->nfhdr_emp)->where('nfsenv_num',$header->nfhdr_num_nf)->get();
                
                $moduloRPS = DB::table('parametros_sis_modulos')->where('modulo_empresa_codigo', $header->nfhdr_emp)->first();
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
                <td>{{$header->nfhdr_num_nf}}</td>
                <td>{{$header->nfhdr_emp.' - '.$data[0]->empresa_nome}}</td>
                <td>{{$header->nfhdr_cli.' - '.$data_cli[0]->cliente_nome}}</td>
                <td>{{$dataNF}}</td>
                <td>{{$header->nfhdr_num_ped}}</td>
                <td>{{$header->nfhdr_num_nf.'-'.$header->nfhdr_ser_nf}}</td>
                <td>{{Helper::formataValorMonetario($header->nfhdr_vlr_tot_nf)}}</td>
                <td>NFS-e</td>
                @if($stsNF == 1)
                <td class="max-width-sts"><span class="badge badge-danger">NFS-e Não Enviada: {{$dadosXmlNfsEnv[0]->nfsenv_obs}}</span> <a class="btn btn-outline-nexus btn-sm" href="{{route('emissaoNF.gerarNF', ['origem' => 'REEMISSAO_SIMP', 'empresa' => $header->nfhdr_emp, 'nfReemissao' => $header->nfhdr_num])}}">Reenviar</a></td>
                @elseif($stsNF == 2)
                <td class="max-width-sts"><span class="badge badge-danger">NFS-e Rejeitada: Erro {{$dadosXmlNfsEnv[0]->nfsenv_sts_emi.' - '.$dadosXmlNfsEnv[0]->nfsenv_obs}}</span> <a class="btn btn-outline-nexus btn-sm" href="{{route('emissaoNF.gerarNF', ['origem' => 'REEMISSAO_SIMP', 'empresa' => $header->nfhdr_emp, 'nfReemissao' => $header->nfhdr_num])}}">Reenviar</a></td>
                @elseif($stsNF == 3)
                <td class="max-width-sts"><span class="badge badge-success">{{$dadosXmlNfsEnv[0]->nfsenv_obs}}</span></td>
                @else
                <td class="max-width-sts"><span class="badge badge-info">Geração Iniciada</span> <a class="btn btn-outline-nexus btn-sm" href="{{route('emissaoNF.gerarNF', ['origem' => 'REEMISSAO_SIMP', 'empresa' => $header->nfhdr_emp, 'nfReemissao' => $header->nfhdr_num])}}">Reenviar</a></td>
                @endif
                @if($stsNF == 3)
                <td class="d-flex justify-content-center"><a class="btn btn-outline-nexus btn-sm mr-2" href="{{route('impresaoNF.nfsePDF',['empresa' => $header->nfhdr_emp, 'numControle' => $header->nfhdr_num, 'appOrigem' => 'IMPRESSAO'])}}" target="_blank">Abrir NFS-e</a>
                <a class="btn btn-outline-nexus btn-sm" href="{{route('impresaoNF.nfseEmail',['empresa' => $header->nfhdr_emp, 'numControle' => $header->nfhdr_num])}}">Enviar Email</a></td>
                @elseif($stsNF == 2 || $stsNF == 1)
                    @if($moduloRPS->modulo_emissao_rps == "S" && $geraRPS->parnfs_impressao_rps == "S")
                    <td class="d-flex justify-content-center"><a class="btn btn-outline-nexus btn-sm mr-2" href="{{route('impresaoRPS.rpsPDF',['empresa' => $header->nfhdr_emp, 'numControle' => $header->nfhdr_num, 'appOrigem' => 'REEMISSAO_SIMP'])}}" target="_blank">Abrir RPS</a>
                    <a class="btn btn-outline-nexus btn-sm" href="{{route('impresaoRPS.rpsEmail',['empresa' => $header->nfhdr_emp, 'numControle' => $header->nfhdr_num])}}">Enviar Email</a></td>
                    @else
                    <td></td>
                    <td></td>
                    @endif
                @else
                <td></td>
                <td></td>
                @endif
            </tr>
        @endforeach
    </x-adminlte-datatable>
    <x-slot name="footerSlot">
        <div class="d-flex justify-content-between w-100">
            <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.emissaoSimpNFS') }}'" label="Nova NFS-e" theme="info" icon="fa-solid fa-plus"/>
            <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.reemissaoSimpNF') }}'" label="Voltar" theme="info" icon=""/>
        </div>
    </x-slot>
</x-adminlte-card>
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.Sweetalert2', true)

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

    @if(Session::has('info'))
        Swal.fire({
            confirmButtonColor: "#007bff",
            title: "Aviso!",
            text: "{{ session('info') }}",
            icon: "info",
            customClass: {
                icon: "no-before-icon",
            }
        });
    @endif

    @if(Session::has('success2'))
        Swal.fire({
            confirmButtonColor: "#007bff",
            title: "Sucesso!",
            text: "{{ session('success2') }}",
            icon: "success",
            customClass: {
                icon: "no-before-icon",
            }
        });
    @endif
</script>
@stop
