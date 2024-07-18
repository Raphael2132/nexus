@extends('adminlte::page')

@section('title', 'Manutenção Cadastro de Modulos do sistema')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h4 style="margin-bottom: 0px !important;">Parâmetros do Sistema</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">
                    <a href="{{route('home.parSisModulo')}}">Módulos do Sistema</a>
                </li>
                <li class="breadcrumb-item active">Manutenção dos Módulos do Sistema</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-10">
        <form method="post" action="{{route('parametrosSistemaModulos.atualizar', [ 'empresa' => $dadosModulo[0]['modulo_empresa_codigo'] ] )}}" id="quickForm" novalidate="novalidate">
            @csrf 
            @method('post')
            <x-adminlte-card title="Parametrização dos Módulos do Sistema" theme="navy">

                <div class="row">
                    @php
                        $nomeEmp = DB::table('cadastro_empresas')->select('empresa_nome')->where('empresa_codigo','=',$dadosModulo[0]->modulo_empresa_codigo)->get();
                        $nomeEmpresa = $dadosModulo[0]->modulo_empresa_codigo.' - '.$nomeEmp[0]->empresa_nome;
                    @endphp
                    <!-- Nome -->
                    <x-adminlte-input name="empresa" label="Empresa" type="text" value="{{$nomeEmpresa}}" fgroup-class="col-md-12"/>
                </div>

                <div class="row">
                    <!-- Utiliza Módulo Serviços -->
                    <x-adminlte-select name="modSrv" label="Módulo de Serviços" fgroup-class="col-md-3">
                        <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" empty-option="Selecione..." selected="{{$dadosModulo[0]['modulo_servico']}}"/>
                    </x-adminlte-select>

                    <!-- Utiliza Módulo Emissão RPS -->
                    <x-adminlte-select name="emiRps" label="Módulo de Emissão RPS" fgroup-class="col-md-3">
                        <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" empty-option="Selecione..." selected="{{$dadosModulo[0]['modulo_emissao_rps']}}"/>
                    </x-adminlte-select>

                    <!-- Utiliza Módulo Emissão NF-e -->
                    <x-adminlte-select name="emiNfs" label="Módulo de Emissão NFS-e" fgroup-class="col-md-3">
                        <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" empty-option="Selecione..." selected="{{$dadosModulo[0]['modulo_emissao_nfs']}}"/>
                    </x-adminlte-select>

                    <!-- Utiliza Módulo Emissão Simplificada NFS-e -->
                    <x-adminlte-select name="emiNfsSimp" label="Módulo de Emissão Simplificada NFS-e" fgroup-class="col-md-3">
                        <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" empty-option="Selecione..." selected="{{$dadosModulo[0]['modulo_emissao_nfs_simp']}}"/>
                    </x-adminlte-select>
                </div>

                <!-- /.card -->
                <x-slot name="footerSlot">
                    <div class="d-flex justify-content-between w-100">
                        <x-adminlte-button class="btn-flat" type="submit" label="Salvar" theme="info" icon="fa-solid fa-share-from-square"/>
                        <x-adminlte-button class="btn-flat" type="button" onclick="window.location='{{ route('home.parSisModulo') }}'" label="Voltar" theme="info" icon=""/>
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
<!--
|--------------------------------------------------------------------------
| Eventos Iniciais da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() {
        $('#empresa').prop('disabled', true);
    });
</script>

<script>
$(function () {
    $('#quickForm').validate({
        rules: {
            modSrv: {
                required: true
            },
            emiRps: {
                required: true
            },
            emiNfs: {
                required: true
            },
            emiNfsSimp: {
                required: true
            },
        },
        messages: {
            modSrv: {
                required: "Por Favor informe se utiliza o Módulo de Serviços"
            },
            emiRps: {
                required: "Por Favor informe se utiliza o Módulo de Emissão RPS"
            },
            emiNfs: {
                required: "Por Favor informe se utiliza o Módulo de Emissão NFS-e"
            },
            emiNfsSimp: {
                required: "Por Favor informe se utiliza o Módulo de Emissão Simplificada NFS-e"
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
