@extends('adminlte::page')

@section('title', 'Painel Emissão de NF')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h1>Faturamento</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                @if($origem == 'REEMISSAO')
                <li class="breadcrumb-item active">
                    <a href="{{route('home.reemissaoNF')}}">Filtro de Reemissão</a>
                </li>
                <li class="breadcrumb-item active">
                    <a href="{{ route('reemissaoNF.consultaReemissaoNF') }}">Consulta de Reemissão</a>
                </li>
                <li class="breadcrumb-item active">Geração de NF</li>
                @else
                <li class="breadcrumb-item active">
                    <a href="{{route('home.emissaoNF')}}">Filtro Emissão</a>
                </li>
                <li class="breadcrumb-item active">
                    <a href="{{ route('emissaoNF.consultaNF') }}">Consulta de Emissão</a>
                </li>
                <li class="breadcrumb-item active">Geração de NF</li>
                @endif
            </ol>
        </div>
    </div>
@stop


@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-8">
        <x-adminlte-card title="Status NFS-e" theme="navy" collapsible maximizable>
            <div class="row">
                <table class="table tabela-dados-os">
                    <tbody>
                        @php 
                            $dataNfs = DB::table('faturamento_nfs')->where('nfs_emp', $empresa)->where('nfs_nfhdr_num', $numControle)->get();
                            $dataNfsXML = DB::table('faturamento_nfs_xml_envios')->where('nfsenv_emp', $empresa)->where('nfsenv_nfhdr_num', $numControle)->where('nfsenv_num', $dataNfs[0]->nfs_nnfs)->get();

                            $pathXML = $dataNfsXML[0]->nfsenv_cnpj.'/file/doc/nfsxml/envio/'.$pathXML;
                            
                            if($dataNfs[0]->nfs_origem == 'ES'){
                                $origem = 'Emi. Simplificada';
                            }else{
                                $origem = 'OS';
                            }
                        @endphp
                        <tr>
                            <td style="border: 0px; width: 15%;">
                                <p class="text-sm">Data/Hora
                                    <b class="d-block">{{Helper::formataDataHora($dataNfsXML[0]->nfsenv_dt_atu)}}</b>
                                </p>
                            </td>
                            <td style="border: 0px; width: 10%;">
                                <p class="text-sm">{{$origem}}
                                    <b class="d-block">{{$dataNfs[0]->nfs_nfhdr_num_ped}}</b>
                                </p>
                            </td>
                            <td style="border: 0px; width: 15%;">
                                <p class="text-sm">Nr./Série NFS-e
                                    <b class="d-block">{{$dataNfs[0]->nfs_nnfs.'-'.$dataNfs[0]->nfs_snfs}}</b>
                                </p>
                            </td>
                            <td style="border: 0px; width: 35%;">
                                <p class="text-sm">Status da Geração
                                    @if($dataNfsXML[0]->nfsenv_sts == 3)
                                    <b class="d-block text-success">{{$dataNfsXML[0]->nfsenv_obs}}</b>
                                    @elseif($dataNfsXML[0]->nfsenv_sts == 2)
                                    <b class="d-block text-danger">{{'Erro: '.$dataNfsXML[0]->nfsenv_sts_emi.' - '.$dataNfsXML[0]->nfsenv_obs}}</b>
                                    @else
                                    <b class="d-block text-danger">{{'Erro: '.$dataNfsXML[0]->nfsenv_sts_emi.' - '.$dataNfsXML[0]->nfsenv_obs}}</b>
                                    @endif
                                </p>
                            </td>
                            <td style="border: 0px; width: 10%;">
                                <p class="text-sm">Impressão
                                    <b class="d-block"><a href="{{route('impresaoNF.nfsePDF',['empresa' => $dataNfs[0]->nfs_emp, 'numControle' => $dataNfs[0]->nfs_nfhdr_num])}}" target="_blank">Abrir NFS-e</a></b>
                                </p>
                            </td>
                            <td style="border: 0px; width: 15%;">
                                <p class="text-sm">Arquivo
                                    <b class="d-block"><a href="{{asset($pathXML)}}" target="_blank">XML</a></b>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table> 
            </div>
            <x-slot name="footerSlot">
                @if($origem == 'REEMISSAO')
                <x-adminlte-button class="btn-flat" type="button" onclick="window.location='{{ route('reemissaoNF.consultaReemissaoNF') }}'" label="Voltar" theme="info"/>
                @else
                <x-adminlte-button class="btn-flat" type="button" onclick="window.location='{{ route('emissaoNF.consultaNF') }}'" label="Voltar" theme="info"/>
                @endif
            </x-slot>
        </x-adminlte-card>
    </div>
</div>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.Sweetalert2', true)
@section('plugins.jqueryValidation', true)

@section('css')
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
