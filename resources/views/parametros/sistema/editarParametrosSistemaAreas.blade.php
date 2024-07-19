@extends('adminlte::page')

@section('title', 'Manutenção Cadastro de Modulos do sistema')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h1>Parâmetros do Sistema</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">
                    <a href="{{route('home.parSisArea')}}">Áreas</a>
                </li>
                <li class="breadcrumb-item active">Manutenção da Área</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-8">
        <form method="post" action="{{route('parametrosSistemaAreas.atualizar', [ 'area' => $dadosArea[0]['area_codigo'] ] )}}" id="quickForm" novalidate="novalidate">
            @csrf 
            @method('post')
            <x-adminlte-card title="Manutenção da Área do Sistema" theme="navy">

                <div class="row">
                    <!-- Código -->
                    <x-adminlte-input name="codigo" label="Código" type="text" value="{{$dadosArea[0]['area_codigo']}}" fgroup-class="col-md-6" readonly/>
                </div>

                <div class="row">
                    <!-- Descrição -->
                    <x-adminlte-input name="descricao" label="Descrição" type="text" value="{{$dadosArea[0]['area_desc']}}" fgroup-class="col-md-12"/>
                </div>

                <!-- /.card -->
                <x-slot name="footerSlot">
                    <div class="d-flex justify-content-between w-100">
                        <x-adminlte-button class="btn-flat" type="submit" label="Salvar" theme="info" icon="fa-solid fa-share-from-square"/>
                        <x-adminlte-button class="btn-flat" type="button" onclick="window.location='{{ route('home.parSisArea') }}'" label="Voltar" theme="info" icon=""/>
                    </div>
                </x-slot>
            </x-adminlte-card>
        </form>
    </div>
</div>
@stop

@section('css')
@stop

<!-- Chamada dos Plugins usados na app -->  
@section('plugins.jqueryValidation', true)
@section('plugins.Sweetalert2', true)
@section('plugins.toastr', true)

@section('js')
<script>
$(function () {
  $('#quickForm').validate({
    rules: {
        codigo: {
            required: true
        },
        descricao: {
            required: true,
            maxlength: 40
        },
    },
    messages: {
        codigo: {
            required: "Por Favor informe o código"
        },
        descricao: {
            required: "Por Favor informe a Descrição",
            maxlength: "Informe no máximo 40 caracteres"
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
</script>
@stop
