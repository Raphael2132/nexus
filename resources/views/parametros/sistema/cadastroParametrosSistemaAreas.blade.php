@extends('adminlte::page')

@section('title', 'Cadastro de Áreas')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h1>Parâmetros do Sistema</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">
                    <a href="{{route('home.parSisServico')}}">Áreas</a>
                </li>
                <li class="breadcrumb-item active">Cadastro de Áreas</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-8">
        <form method="post" action="{{route('parametrosSistemaAreas.inserir')}}" id="quickForm" novalidate="novalidate">
            @csrf 
            <x-adminlte-card title="Cadastro de Nova Área" theme="navy">
                <div class="row">
                    <!-- Código -->
                    <x-adminlte-input name="codigo" label="Código" type="text" class="text-uppercase" fgroup-class="col-md-6"/>
                </div>

                <div class="row">
                    <!-- Descrição -->
                    <x-adminlte-input name="descricao" label="Descrição" type="text" fgroup-class="col-md-12"/>
                </div>

                <!-- /.card -->
                <x-slot name="footerSlot">
                    <x-adminlte-button class="btn-flat" type="submit" label="Salvar" theme="info" icon="fa-solid fa-share-from-square"/>
                </x-slot>
            </x-adminlte-card>
        </form>
    </div>
</div>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.jqueryValidation', true)
@section('plugins.Sweetalert2', true)
@section('plugins.toastr', true)

@section('css')
@stop

@section('js')
<script>
$(function () {
    $('#quickForm').validate({
        rules: {
            codigo: {
                required: true,
                maxlength: 3,
                minlength: 3
            },
            descricao: {
                required: true,
                maxlength: 40
            }, 
        },
        messages: {
            codigo: {
                required: "Por Favor informe um Código",
                maxlength: "Informe um código de 3 caracteres",
                minlength: "Informe um código de 3 caracteres"
            },
            descricao: {
                required: "Por Favor informe a Descrição",
                maxlength: "Infome no máximo 40 caracteres"
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
        }
    });
});
</script>

<script>
    @if(Session::has('error'))
        Swal.fire({
        confirmButtonColor: "#007bff",
        title: "Erro!!!",
        text: "{{ session('error') }}",
        icon: "error"
    });
    @endif
</script>
@stop
