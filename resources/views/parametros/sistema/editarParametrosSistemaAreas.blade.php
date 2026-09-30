@extends('adminlte::page')

@section('title', 'Áreas do Sistema')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Parâmetros do Sistema</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('areasSistema.index')}}">Áreas</a>
            </li>
            <li class="breadcrumb-item active">Manutenção da Área</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-8">
        <form method="post" action="{{route('areasSistema.update', [ 'areasSistema' => $dadosArea ] )}}" id="quickForm" novalidate="novalidate">
            @csrf 
            @method('put')
            <x-adminlte-card title="Manutenção da Área do Sistema" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>

                <div class="row">
                    <!-- Código -->
                    <x-adminlte-input name="codigo" type="text" value="{{$dadosArea->area_codigo}}" fgroup-class="col-md-6" readonly>
                        <x-slot name="label">
                            Código <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>
                </div>

                <div class="row">
                    <!-- Descrição -->
                    <x-adminlte-input name="descricao" type="text" value="{{$dadosArea->area_desc}}" fgroup-class="col-md-12">
                        <x-slot name="label">
                            Descrição <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>
                </div>

                <!-- /.card -->
                <x-slot name="footerSlot">
                    <div class="d-flex justify-content-between w-100">
                        <x-adminlte-button class="btn-nexus" type="submit" label="Salvar" theme="info" icon="fa-solid fa-share-from-square"/>
                        <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('areasSistema.index') }}'" label="Voltar" theme="info" icon=""/>
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
</script>
@stop
