@extends('adminlte::page')

@section('title', 'Cadastro de Administradora de Cartão')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Cadastros</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('cadastroCartao.index')}}">Administradora de Cartão</a>
            </li>
            @if($tipoCad == 'N')
            <li class="breadcrumb-item active">Cadastro da Administradora de Cartão</li>
            @else
            <li class="breadcrumb-item active">Manutenção da Administradora de Cartão</li>
            @endif
        </ol>
    </div>
</div>
@stop

@section('content')
@php 
    if($tipoCad == 'N'){
        $titulo = "Cadastro de Nova Administradora de Cartão";
    }else{
        $titulo = "Manutenção da Administradora de Cartão Selecionada";
    }
@endphp
@if($tipoCad == 'N')
<form method="post" action="{{route('cadastroCartao.store')}}" id="formulario-cartao" novalidate="novalidate">
@csrf 
@method('post')
@else
<form method="post" action="{{route('cadastroCartao.update',['cadastroCartao' => $dadosCartao])}}" id="formulario-cartao" novalidate="novalidate">
@csrf 
@method('put')
@endif
    <x-adminlte-card :title="$titulo" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>

        <div class="row">
            @php

                if(!empty($dadosCartao)){

                    $nomeAdm = $dadosCartao->administradora_nome;
                    $codAdm = $dadosCartao->administradora_codigo;
                    $cnpjAdm = $dadosCartao->administradora_cnpj;
                    $tipoAdm = $dadosCartao->administradora_tipo;
                    $parAdm = $dadosCartao->administradora_parcela;

                }else{
                    $nomeAdm = '';
                    $codAdm = '';
                    $cnpjAdm = '';
                    $tipoAdm = '';
                    $parAdm = '';
                }
            @endphp

            @if($tipoCad == 'N')
            <!-- Nome -->
            <x-adminlte-input name="nome" type="text" placeholder="Descrição da Administradora" fgroup-class="col-md-6" value="{{$nomeAdm}}">
                <x-slot name="label">
                    Nome da Administradora <span style="color:red;">*</span>
                </x-slot>
            </x-adminlte-input>
            @else
            <!-- Código -->
            <x-adminlte-input name="codigo" type="text" placeholder="Código da Administradora" fgroup-class="col-md-3" value="{{$codAdm}}">
                <x-slot name="label">
                    Código da Administradora <span style="color:red;">*</span>
                </x-slot>
            </x-adminlte-input>
            <!-- Nome -->
            <x-adminlte-input name="nome" type="text" placeholder="Descrição da Administradora" fgroup-class="col-md-6" value="{{$nomeAdm}}">
                <x-slot name="label">
                    Nome da Administradora <span style="color:red;">*</span>
                </x-slot>
            </x-adminlte-input>
            @endif
        </div>

        <div class="row bloco-nom-fant">
            <x-adminlte-input name="cnpjAdm" type="text" label="CNPJ" placeholder="CNPJ da Administradora" fgroup-class="col-md-4" value="{{$cnpjAdm}}">
                <x-slot name="label">
                    CNPJ <span style="color:red;">*</span>
                </x-slot>
            </x-adminlte-input>
        
            @php
                $array_tip_adm = HelperArrayFixo::arrayTipoAdministradora(1,2);
            @endphp

            <x-adminlte-select name="tipoAdm" fgroup-class="col-md-4">
                <x-slot name="label">
                    Tipo <span style="color:red;">*</span>
                </x-slot>
                <x-adminlte-options :options="$array_tip_adm" empty-option="Selecione..." selected="{{$tipoAdm}}"/>
            </x-adminlte-select>

            <x-adminlte-input name="parAdm" type="number" placeholder="Qtd. de Parcelas" fgroup-class="col-md-4" value="{{$parAdm}}">
                <x-slot name="label">
                    Qtd. Máxima de Parcelas no Crédito <span style="color:red;">*</span>
                </x-slot>
            </x-adminlte-input>
        </div>

        <x-slot name="footerSlot">
            <div class="d-flex justify-content-between w-100">
                <div class="d-flex">
                    <x-adminlte-button class="btn-nexus mr-2" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                    @if($tipoCad == 'M')
                    <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('cadastroCartao.create') }}'" label="Nova Administradora" theme="" icon="fa-solid fa-plus"/>
                    @endif
                </div>
                <div class="d-flex">
                    <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('cadastroCartao.index') }}'" label="Voltar" theme="" icon=""/>
                </div>
            </div>
        </x-slot>
    </x-adminlte-card>
</form>
@stop

@section('css')
@stop

<!-- Chamada dos Plugins usados na app -->  
@section('plugins.jqueryValidation', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.Inputmask', true)

@section('js')

<!--
|--------------------------------------------------------------------------
| Eventos Iniciais da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() {

        $('#cnpjAdm').inputmask({
            "mask": " 99.999.999/9999-99",
            // Specify other options...
        });

        var tipoCad = {!! json_encode($tipoCad) !!};

        if(tipoCad == 'M'){

            $("#codigo").prop('disabled', true);
        }
    });
</script>

<script>
$(function () {
    $('#formulario-cartao').validate({
        rules: {
            nome: {
                required: true,
                maxlength: 80
            },
            cnpjAdm: {
                required: true
            },
            tipoAdm: {
                required: true
            },
            parAdm: {
                required: true
            }
        },
        messages: {
            nome: {
                required: "Por Favor informe o Nome da Administradora",
                maxlength: "Informe no máximo 80 caracteres"
            },
            cnpjAdm: {
                required: "Por Favor informe o CNPJ"
            },
            tipoAdm: {
                required: "Por Favor informe o Tipo da Administradora"
            },
            parAdm: {
                required: "Por Favor informe a Qtd. de Parcelas"
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
            html: "{!! session('info') !!}",
            icon: "info",
            customClass: {
                icon: "no-before-icon",
            }
        });
    @endif
</script>
@stop
