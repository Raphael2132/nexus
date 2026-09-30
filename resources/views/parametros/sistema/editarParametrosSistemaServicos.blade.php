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
            <li class="breadcrumb-item active">Manutenção do Serviço da NFS-e</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-8">
        <form method="post" action="{{route('servicosSistema.update', [ 'servicosSistema' => $dadosServico ] )}}" id="quickForm" novalidate="novalidate">
            @csrf 
            @method('put')
            <x-adminlte-card title="Serviço da NFS-e" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>

                <div class="row">
                    @php
                        $grupo_desc = DB::table('parametros_sis_servico_grupos')->where('grupo_codigo', $dadosServico->servico_grupo)->first();
                        $desc_drupo = $dadosServico->servico_grupo.' - '.$grupo_desc->grupo_desc;
                    @endphp
                    <!-- Grupo do serviço -->
                    <x-adminlte-input name="grupo" type="text" value="{{$desc_drupo}}" fgroup-class="col-md-10" disabled>
                        <x-slot name="label">
                            Grupo <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>
                    <!-- Código do Serviço -->
                    <x-adminlte-input name="codigo" type="number" value="{{$dadosServico->servico_codigo}}" fgroup-class="col-md-2" disabled>
                        <x-slot name="label">
                            Código <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                
                <div class="row">
                    <!-- Empresa do Serviço -->
                    <x-adminlte-textarea name="descricao" label="Descriçao" rows=3 label-class="text-dark" igroup-size="sm" placeholder="Informe a descrição do serviço..." fgroup-class="col-md-12">
                        <x-slot name="label">
                            Descrição <span style="color:red;">*</span>
                        </x-slot>
                        {{$dadosServico->servico_desc}}
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

@section('css')
@stop

<!-- Chamada dos Plugins usados na app -->  
@section('plugins.Sweetalert2', true)
@section('plugins.toastr', true)
@section('plugins.jqueryValidation', true)

@section('js')

<script>
$(function () {

    $('#quickForm').validate({
        rules: {
            descricao: {
                required: true,
                maxlength: 800
            },   
        },
        messages: {
            descricao: {
                required: "Por Favor informe a Descrição",
                maxlength: "Infome no máximo 800 caracteres"
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
