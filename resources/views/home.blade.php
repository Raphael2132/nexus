@extends('adminlte::page')

@section('title', 'Página Inicial')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Página Inicial </h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Painel Principal</li>
            @if (session('glo_tipo_home') == 'homeNFSeSimplificada')
            <li class="breadcrumb-item active">Emissão Simplificada de NFS-e</li>
            @elseif (session('glo_tipo_home') == 'homeNFSe')
            <li class="breadcrumb-item active">Emissão OS e NFS-e</li>
            @else
            <li class="breadcrumb-item active">Resumo Geral</li>
            @endif
            @php   
                $dadosEmp = Helper::buscaDadosEmpresa(session('glo_empresa_exibicao_home'));
            @endphp
            <li class="breadcrumb-item active">{{ $dadosEmp->empresa_codigo.' - '.$dadosEmp->empresa_nome}}</li>
        </ol>
    </div>
</div>
@stop

@section('content')

    @if (session('glo_tipo_home') == 'homeNFSeSimplificada')
        @include('home.homeNFSeSimplificada')
    @elseif (session('glo_tipo_home') == 'homeNFSe')
        @include('home.homeNFSe')
    @else
        @include('home.homeCompleta')
    @endif

@stop

@section('plugins.Sweetalert2', true)

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

    @if(Session::has('warning'))
        Swal.fire({
            title: "Atenção!",
            html: "{!! session('warning') !!}",
            icon: "warning",
            confirmButtonColor: "#007bff",
        });
    @endif
</script>
@stop
