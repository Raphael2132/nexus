@extends('adminlte::page')

@section('title', 'Cadastro de Empresa')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h1>Cadastros</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">
                    <a href="{{route('home.empresa')}}">Empresas</a>
                </li>
                <li class="breadcrumb-item active">Cadastro de Empresa</li>
            </ol>
        </div>
    </div>
@stop

@section('content')

<form method="post" action="{{route('empresa.inserir')}}" id="quickForm" novalidate="novalidate">
    @csrf 
    @method('post')
    <x-adminlte-card title="Cadastro de Nova Empresa" theme="navy">

        <div class="row">
            <!-- Nome -->
            <x-adminlte-input name="nome" label="Nome" type="text" placeholder="Nome Completo" fgroup-class="col-md-8"/>

            <!-- CNPJ -->
            <x-adminlte-input name="cnpj" type="text" label="CNPJ" fgroup-class="col-md-4"></x-adminlte-input>
        </div>
        
        <div class="row">
            <!-- Inscrição Estadual -->
            <x-adminlte-input name="insEstadual" type="number" label="Inscrição Estadual" fgroup-class="col-md-6"></x-adminlte-input>

            <!-- Inscrição Municipal -->
            <x-adminlte-input name="insMunicipal" type="number" label="Inscrição Municipal" fgroup-class="col-md-6"></x-adminlte-input>
        </div>

        <!-- /.card -->
        <x-slot name="footerSlot">
            <x-adminlte-button class="btn-flat" type="submit" label="Salvar" theme="info" icon="fa-solid fa-share-from-square"/>
        </x-slot>
    </x-adminlte-card>
</form>
@stop

@section('css')
@stop

<!-- Chamada dos Plugins usados na app -->  
@section('plugins.Inputmask', true)
@section('plugins.jqueryValidation', true)
@section('plugins.Sweetalert2', true)
@section('plugins.toastr', true)

@section('js')
<script>
    $(document).ready(function() {

        // Init input mask on the target element.
        $('#cnpj').inputmask({
            "mask": " 99.999.999/9999-99",
            // Specify other options...
        });
    });
</script>

<script>
$(function () {
  $('#quickForm').validate({
    rules: {
      nome: {
        required: true,
        minlength: 5,
		maxlength: 80
      },
      cnpj: {
        required: true,
      },
	  insEstadual: {
		maxlength: 14
      },
	  insMunicipal: {
		maxlength: 15
      },
    },
    messages: {
      nome: {
        required: "Por Favor informe um Nome para a Empresa",
        minlength: "Informe no mínimo 5 caracteres no Nome da Empresa",
		maxlength: "Informe no máximo 80 caracteres no Nome da Empresa"
      },
      cnpj: {
        required: "Por Favor informe o CNPJ da Empresa"
      },
	  insEstadual: {
		maxlength: "Informe no máximo 14 dígitos na Incrição Estadual"
      },
	  insMunicipal: {
		maxlength: "Informe no máximo 15 dígitos na Incrição Municipal"
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
