@extends('adminlte::page')

@section('title', 'Duplicatas')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Recebimento</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Filtro de Duplicatas</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-8">
        <form method="post" action="{{route('recebimentoDUP.iniciaRecDUP')}}" id="form-rec-dup" novalidate="novalidate">
            @csrf 
            @method('post')
            <x-adminlte-card title="Filtro do Recebimento de Duplicatas" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
                @php
                    $array_emp = HelperArraySelect::arrayEmpresas(1,1);
                    $htmlCli = HelperDataList::geraDatalistGeralClientes('clientes');

                    //Echo adiciona o html ao campo dos clientes
                    echo $htmlCli;
                @endphp
                <div class="row"> 
                    <!-- Empresa -->
                    <x-adminlte-select name="empresa" fgroup-class="col-md-12">
                        <x-slot name="label">
                            Empresa <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_emp" empty-option="Selecione..." selected=""/>
                    </x-adminlte-select>
                </div>
                <div class="row">
                    <!-- Cliente da NF -->
                    <x-adminlte-input name="cliente" type="search" list="clientes" autocomplete="off" value="" fgroup-class="col-md-12">
                        <x-slot name="label">
                            Cliente <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <x-slot name="footerSlot">
                    <x-adminlte-button class="btn-nexus" type="submit" label="Pesquisar" theme="info" icon="fa-solid fa-magnifying-glass"/>
                    <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('recebimentoDUP.dupAbertas') }}'" label="Duplicatas em Aberto" theme="info" icon=""/>
                </x-slot>
            </x-adminlte-card>
        </form>
    </div>
</div>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.Sweetalert2', true)
@section('plugins.jqueryValidation', true)

@section('css')
@stop

@section('js')
<!--
|--------------------------------------------------------------------------
| Eventos Validate da app
|--------------------------------------------------------------------------
-->
<script>
$(function () {

    //Inserção / Atualização Recebimento com Cartão de Débito
    $('#form-rec-dup').validate({
        rules: {
            empresa: {
                required: true
            },
            cliente: {
                required: true
            },
        },
        messages: {
            empresa: {
                required:  "Por Favor informe a Empresa"
            },
            cliente: {
                required:  "Por Favor informe o Cliente"
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        // Ao submeter o formulário, reativar os campos desativados
        submitHandler: function (form) {
            // Ativar campos desativados antes de enviar
            $(':disabled').each(function () {
                $(this).removeAttr('disabled');
            });
            form.submit();
        }
    });
});
</script>

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
