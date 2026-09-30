@extends('adminlte::page')

@section('title', 'Status de Emissão da NF')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Faturamento</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            @if($origem == 'REEMISSAO')
            <li class="breadcrumb-item active">
                <a href="{{route('home.reemissaoNF')}}">Filtro de Reemissão</a>
            </li>
            <li class="breadcrumb-item active">
                <a href="{{ route('reemissaoNF.redirConsultaReemissaoNF') }}">Consulta de Reemissão</a>
            </li>
            @elseif($origem == 'EMISSAO')
            <li class="breadcrumb-item active">
                <a href="{{route('home.emissaoNF')}}">Filtro Emissão</a>
            </li>
            <li class="breadcrumb-item active">
                <a href="{{ route('emissaoNF.consultaNF') }}">Consulta de Emissão</a>
            </li>
            @elseif($origem == 'REEMISSAO_SIMP')
            <li class="breadcrumb-item active">
                <a href="{{route('home.reemissaoSimpNF')}}">Filtro Reemissão Simplificada</a>
            </li>
            <li class="breadcrumb-item active">
                <a href="{{ route('reemissaoSimpNF.redirConsultaReemissaoSimpNF') }}">Consulta Reemissão Simplificada</a>
            </li>
            @elseif($origem == 'EMISSAO_SIMP')
            <li class="breadcrumb-item active">
                <a href="{{route('home.emissaoSimpNFS')}}">Emissão Simplificada</a>
            </li>
            @endif
            <li class="breadcrumb-item active">Status Emissão da NF</li>
        </ol>
    </div>
</div>
@stop


@section('content')
@php
    //Define as globais de acordo com a origem que serão usadas no redirecionamento
    if($origem == 'EMISSAO'){
        //Define as variaveis de sessão aqui na view por que depois de 2 redirect elas são destruidas
        session(['glo_emissao_nf_where_completo' => $where_completo]);
        session(['glo_emissao_nf_where_semi' => $where_semi]);
    }if($origem == 'REEMISSAO' || $origem == 'REEMISSAO_SIMP'){
        //Define as variaveis de sessão aqui na view por que depois de 2 redirect elas são destruidas
        session(['glo_where_reemissao_nf' => $glo_where_reemissao_nf]);
    }
@endphp
<div class="d-flex justify-content-center">
    <div class="col-md-8">
        @php 
            //Busca as notas que foram geradas
            $dataNf = DB::table('faturamento_nf_headers')->where('nfhdr_emp', $empresa)->wherein('nfhdr_num', $where_hdr)->orderby('nfhdr_num')->get();
        @endphp
        @foreach($dataNf as $nota)
            <x-adminlte-card title="Status de Emissão da Nota Fiscal" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
                <div class="row">
                    <table class="table tabela-dados-os">
                        <tbody>
                            @php 
                                $dataNfsXML = DB::table('faturamento_nfs_xml_envios')->where('nfsenv_emp', $empresa)->where('nfsenv_nfhdr_num', $nota->nfhdr_num)->where('nfsenv_num', $nota->nfhdr_num_nf)->first();

                                // Obter o nome do host do servidor
                                if(!empty($_SERVER['SERVER_NAME'])){
                                    $serverName = $_SERVER['SERVER_NAME'];
                                }else{
                                    $serverName = '';
                                }

                                // Verificar se está rodando no localhost
                                if ($serverName == '127.0.0.1' || stripos($serverName, 'localhost') !== false) {
                                    
                                    $pathXML = $dataNfsXML->nfsenv_cnpj.'/file/doc/nfsxml/envio/'.$dataNfsXML->nfsenv_nom_arq_env;
                                    $servidor = "localhost";
                                
                                } else {
                                    
                                    $pathXML = '/home/'.$dataNfsXML->nfsenv_cnpj.'/file/doc/nfsxml/envio/'.$dataNfsXML->nfsenv_nom_arq_env;
                                    $servidor = "vps";
                                }                                
                                
                                if($nota->nfhdr_ori == '02'){
                                    $origemLabel = 'ES';
                                }elseif($nota->nfhdr_ori == '01'){
                                    $origemLabel = 'OS';
                                }else{
                                    $origemLabel = '';
                                }

                                $estagio = DB::table('faturamento_nf_estagios')->where('nfetg_emp', $nota->nfhdr_emp)->where('nfetg_num', $nota->nfhdr_num)->first();

                                if($nota->nfhdr_vlr_lqs == 0 AND $nota->nfhdr_vlr_srv == 0 AND $nota->nfhdr_vlr_srv_ter == 0){

                                    if($estagio->nfetg_cod == 2){
                                        $errEtg = "Atualização de Documentos não executado";
                                    }elseif($estagio->nfetg_cod == 3){
                                        $errEtg = "Faturamento não executado";
                                    }elseif($estagio->nfetg_cod == 4){
                                        $errEtg = "Financeiro não executado";
                                    }else{
                                        $errEtg = '';
                                    }

                                }else{

                                    if($estagio->nfetg_cod == 0){
                                        $errEtg = "Geração de NFS-e não executado";
                                    }elseif($estagio->nfetg_cod == 2){
                                        $errEtg = "Atualização de Documentos não executado";
                                    }elseif($estagio->nfetg_cod == 3){
                                        $errEtg = "Faturamento não executado";
                                    }elseif($estagio->nfetg_cod == 2){
                                        $errEtg = "Financeiro não executado";
                                    }else{
                                        $errEtg = '';
                                    }
                                }
                            @endphp
                            <tr>
                                <td style="border: 0px; width: 20%;">
                                    <p class="text-sm">Data/Hora
                                        <b class="d-block">{{Helper::formataDataHora($dataNfsXML->nfsenv_dt_atu)}}</b>
                                    </p>
                                </td>
                                <td style="border: 0px; width: 5%;">
                                    <p class="text-sm">{{$origemLabel}}
                                        <b class="d-block">{{$nota->nfhdr_num_ped}}</b>
                                    </p>
                                </td>
                                <td style="border: 0px; width: 10%;">
                                    <p class="text-sm">Nr./Série RPS
                                        <b class="d-block">{{$nota->nfhdr_num_nf.'-'.$nota->nfhdr_ser_nf}}</b>
                                    </p>
                                </td>
                                <td style="border: 0px; width: 10%;">
                                    @if($dataNfsXML->nfsenv_sts == 3)
                                    <p class="text-sm">Nr. NFS-e
                                        <b class="d-block">{{$dataNfsXML->nfsenv_num_nfs}}</b>
                                    </p>
                                    @else
                                    <p class="text-sm">Nr. NFS-e
                                        <b class="d-block">Não Gerada</b>
                                    </p>
                                    @endif
                                </td>
                                <td style="border: 0px; width: 30%;">
                                    <p class="text-sm">Status da Geração
                                        @if($dataNfsXML->nfsenv_sts == 3)
                                        <b class="text-md d-block"><span class="badge badge-success">{{$dataNfsXML->nfsenv_obs}}</span></b>
                                        @elseif($dataNfsXML->nfsenv_sts == 2)
                                        <b class="text-md d-block"><span class="badge badge-danger">{{'Erro: '.$dataNfsXML->nfsenv_sts_emi.' - '.$dataNfsXML->nfsenv_obs}}</span></b>
                                        @else
                                        <b class="text-md d-block"><span class="badge badge-danger">{{'Erro: '.$dataNfsXML->nfsenv_sts_emi.' - '.$dataNfsXML->nfsenv_obs}}</span></b>
                                        @endif
                                        @if(!empty($errEtg))
                                        <b class="text-md d-block"><span class="badge badge-warning">{{$errEtg}}</span></b>
                                        @endif
                                    </p>
                                </td>
                                <td style="border: 0px; width: 15%;">
                                    <p class="text-sm">Impressão
                                        @if($dataNfsXML->nfsenv_sts == 1 || $dataNfsXML->nfsenv_sts == 2)
                                            <!-- Verifica se a empresa emite RPS -->
                                            @php 
                                                $moduloRPS = DB::table('parametros_sis_modulos')->where('modulo_empresa_codigo', $empresa)->first();
                                                $geraRPS = DB::table('parametros_fat_nfs')->where('parnfs_empresa', $empresa)->first();
                                            @endphp
                                            @if($moduloRPS->modulo_emissao_rps == "S" && $geraRPS->parnfs_impressao_rps == "S")
                                                <b class="d-block"><a href="{{route('impresaoRPS.rpsPDF',['empresa' => $nota->nfhdr_emp, 'numControle' => $nota->nfhdr_num, 'appOrigem' => 'IMPRESSAO'])}}" target="_blank">Abrir RPS</a></b>
                                            @else
                                                <b class="d-block">NFS-e Não Gerada</b>
                                            @endif
                                        @else
                                        <b class="d-block"><a href="{{route('impresaoNF.nfsePDF',['empresa' => $nota->nfhdr_emp, 'numControle' => $nota->nfhdr_num, 'appOrigem' => 'IMPRESSAO'])}}" target="_blank">Abrir NFS-e</a></b>
                                        @endif
                                    </p>
                                </td>
                                <td style="border: 0px; width: 10%;">
                                    <p class="text-sm">Arquivo
                                        @if($servidor == "localhost")
                                        <b class="d-block"><a href="{{asset($pathXML)}}" target="_blank">XML</a></b>
                                        @else
                                        <b class="d-block"><a href="{{ route('nfs.xmlEnvio', ['cnpj' => $dataNfsXML->nfsenv_cnpj, 'filename' => $dataNfsXML->nfsenv_nom_arq_env]) }}" target="_blank">XML</a></b>
                                        @endif
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table> 
                </div>
                <x-slot name="footerSlot">
                    <div class="d-flex flex-row-reverse">
                        @if($origem == 'REEMISSAO')
                        <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('reemissaoNF.redirConsultaReemissaoNF') }}'" label="Voltar" theme=""/>
                        @elseif($origem == 'EMISSAO')
                        <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('emissaoNF.consultaNF') }}'" label="Voltar" theme=""/>
                        @elseif($origem == 'REEMISSAO_SIMP')
                        <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('reemissaoSimpNF.redirConsultaReemissaoSimpNF') }}'" label="Voltar" theme=""/>
                        @else
                        <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.emissaoSimpNFS') }}'" label="Voltar" theme=""/>
                        @endif
                    </div>
                </x-slot>
            </x-adminlte-card>
        @endforeach
    </div>
</div>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.Sweetalert2', true)
@section('plugins.jqueryValidation', true)

@section('css')
<style>
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

    @if(Session::has('glo_fat_pnl001_EmissaoNF_erro_geracao_nf'))
        Swal.fire({
            confirmButtonColor: "#007bff",
            title: "Erro!!!",
            text: "{{ session('glo_fat_pnl001_EmissaoNF_erro_geracao_nf') }}",
            icon: "error"
        });
    @endif
</script>
@stop
