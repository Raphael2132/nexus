@extends('adminlte::page')

@section('title', 'Outros Recebimentos')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Recebimento</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Filtro de Outros Recebimentos</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-8">
        <form method="post" action="{{route('recebimentoOUT.iniciaRecOUT')}}" id="form-rec-out" novalidate="novalidate">
            @csrf 
            @method('post')
            <x-adminlte-card title="Filtro de Outros Recebimentos" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
                @php
                    $array_emp = HelperArraySelect::arrayEmpresas(1,1);
                    $array_tipo_rec = HelperArraySelect::arrayTipoCreditoFin(1,1,'T3');
                @endphp
                <div class="row"> 
                    <x-adminlte-select name="empresa" fgroup-class="col-md-12">
                        <x-slot name="label">
                            Empresa <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_emp" empty-option="Selecione..." selected=""/>
                    </x-adminlte-select>
                </div>
                <div class="row"> 
                    <x-adminlte-select name="tipCred" fgroup-class="col-md-12">
                        <x-slot name="label">
                            Tipo do Crédito <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_tipo_rec" empty-option="Selecione..." selected=""/>
                    </x-adminlte-select>
                </div>
                <div class="row">
                    <x-adminlte-input name="valorRecebimento" type="text" value="" placeholder="0,00" fgroup-class="col-md-6" value="">
                        <x-slot name="label">
                            Valor a Receber <span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-brazilian-real-sign"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <x-slot name="footerSlot">
                    <x-adminlte-button class="btn-nexus" type="submit" label="Receber" theme="info" icon="fa-solid fa-hand-holding-dollar"/>
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
<script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script> 

<!--
|--------------------------------------------------------------------------
| Eventos Iniciais da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() { 

        //Mascaras dos campos de valores
        $('#valorRecebimento').mask('#.##0,00', {reverse: true});
    });
</script>

<!--
|--------------------------------------------------------------------------
| Eventos Validate da app
|--------------------------------------------------------------------------
-->
<script>
$(function () {

    //Inserção / Atualização Recebimento com Cartão de Débito
    $('#form-rec-out').validate({
        rules: {
            empresa: {
                required: true
            },
            tipCred: {
                required: true
            },
            valorRecebimento: {
                required: true,
                maxlength: 20
            },
        },
        messages: {
            empresa: {
                required:  "Por Favor informe a Empresa"
            },
            tipCred: {
                required:  "Por Favor informe o Tipo de Crédito"
            },
            valorRecebimento: {
                required:  "Por Favor informe o Valor a Receber",
                maxlength: "Limite máximo do valor é de 15 digitos"
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
