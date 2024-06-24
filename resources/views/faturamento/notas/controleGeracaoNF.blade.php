@extends('adminlte::page')

@section('title', 'Painel Emissão de NF')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h1>Faturamento</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">
                    <a href="{{route('home.emissaoNF')}}">Filtro Emissão de NF</a>
                </li>
                <li class="breadcrumb-item active">Geração de NF-e / NFS-e</li>
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
                        <tr>
                            <td style="border: 0px; width: 20%;">
                                <p class="text-sm">Data/Hora
                                    <b class="d-block">01/01/2024 15:00:00</b>
                                </p>
                            </td>
                            <td style="border: 0px; width: 15%;">
                                <p class="text-sm">Nr./Série NFS-e
                                    <b class="d-block">475-RP</b>
                                </p>
                            </td>
                            <td style="border: 0px; width: 35%;">
                                <p class="text-sm">Status da Geração
                                    <b class="d-block text-success"> Gerado com sucesso</b>
                                </p>
                            </td>
                            <td style="border: 0px; width: 15%;">
                                <p class="text-sm">Impressão
                                    <b class="d-block"><a href="">Abrir NFS-e</a></b>
                                </p>
                            </td>
                            <td style="border: 0px; width: 15%;">
                                <p class="text-sm">Arquivo
                                    <b class="d-block"><a href="">XML</a></b>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table> 
            </div>
            <x-slot name="footerSlot">
                <x-adminlte-button class="btn-flat" type="submit" label="Voltar" theme="info"/>
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
