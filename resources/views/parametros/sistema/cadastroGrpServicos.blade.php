@extends('adminlte::page')

@section('title', 'Grupos e Serviços da NFS-e')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Parâmetros do Sistema</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.parSisServico')}}">Grupos e Serviços da NFS-e</a>
            </li>
            <li class="breadcrumb-item active">Cadastro de Grupo de Serviço</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-8">
        <form method="post" action="{{route('parametrosSistemaGrpServico.inserir')}}" id="quickForm" novalidate="novalidate">
            @csrf 
            <x-adminlte-card title="Cadastro de Novo Grupo de Serviço" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
                <div class="row">
                    <!-- Código do Grupo do Serviço -->
                    <x-adminlte-input name="codigo" type="number" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Código <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                
                <div class="row">
                    <!-- Descrição do Serviço -->
                    <x-adminlte-textarea name="descricao" rows=3 label-class="text-dark" igroup-size="sm" placeholder="Informe a descrição do grupo..." fgroup-class="col-md-12">
                        <x-slot name="label">
                            Descriçao <span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fas fa-lg fa-file-alt text-white"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-textarea>
                </div>

                <!-- /.card -->
                <x-slot name="footerSlot">
                    <div class="d-flex justify-content-between w-100">
                        <x-adminlte-button class="btn-nexus" type="submit" label="Salvar" theme="info" icon="fa-solid fa-share-from-square"/>
                        <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.parSisServico') }}'" label="Voltar" theme="info" icon=""/>
                    </div>
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
                maxlength: 5
            },
            descricao: {
                required: true,
                maxlength: 800
            },            
        },
        messages: {
            codigo: {
                required: "Por Favor informe um Código",
                maxlength: "Informe no máximo 5 dígitos"
            },
            descricao: {
                required: "Por Favor informe a Descrição",
                maxlength: "Infome no máximo 800 caracteres"
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
