@extends('adminlte::page')

@section('title', 'Transferência')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Reforço de Caixa</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Filtro</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-6">
        <form method="post" action="{{route('transferencia.painelReforcoCaixa')}}" id="form-transf" novalidate="novalidate">
            @csrf 
            @method('post')
            <x-adminlte-card title="Trasferência para Reforço de Caixa" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
                @php
                    $array_te = HelperArraySelect::arrayRazoesPorTipo(1,1,'','TE');
                    $array_cx = HelperArraySelect::arrayRazoesPorTipo(1,1,'','CX');
                @endphp
                <div class="row"> 
                    <x-adminlte-select name="teOrigem" fgroup-class="col-md-12" igroup-size="sm">
                        <x-slot name="label">
                            Tesouraria de Origem <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_te" empty-option="Selecione..." selected=""/>
                    </x-adminlte-select>
                </div>
                <div class="row"> 
                    <x-adminlte-select name="cxDestino" fgroup-class="col-md-12" igroup-size="sm">
                        <x-slot name="label">
                            Caixa de Destino <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_cx" empty-option="Selecione..." selected=""/>
                    </x-adminlte-select>
                </div>
                
                <x-slot name="footerSlot">
                    <x-adminlte-button class="btn-nexus" type="submit" label="Confirmar" theme="info" icon="fa-solid fa-check"/>
                </x-slot>
            </x-adminlte-card>
        </form>
    </div>
</div>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.Sweetalert2', true)
@section('plugins.jqueryValidation', true)
@section('plugins.Select2', true)
@section('plugins.BootstrapSelect', true)

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
    $('#form-transf').validate({
        rules: {
            teOrigem: {
                required: true
            },
            cxDestino: {
                required: true
            }
        },
        messages: {
            teOrigem: {
                required:  "Por Favor informe a Tesouraria de Origem"
            },
            cxDestino: {
                required:  "Por Favor informe o Caixa de Destino"
            }
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
