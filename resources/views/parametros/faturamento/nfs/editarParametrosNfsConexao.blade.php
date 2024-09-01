@extends('adminlte::page')

@section('title', 'Parâmetros da NFS-e')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Parâmetros Gerais</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.parFatNfs')}}">Parâmetros da NFS-e</a>
            </li>
            @if($appOrigem == 'parametrosNfsConexao')
                <li class="breadcrumb-item active">
                    <a href="{{route('parametrosNfsConexao')}}">Conexão da NFS-e</a>
                </li>
            @endif
            <li class="breadcrumb-item active">Manutenção Conexão da NFS-e</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-10">
        <form method="post" action="{{route('parmetrosNfsCon.atualizar', [ 'empresa' => $dadosConexao[0]['conexao_empresa'] ] )}}" id="quickForm" novalidate="novalidate">
            @csrf 
            @method('post')
            <x-adminlte-card title="Manutenção da Parametrização de Conexão da NFS-e" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>

                <div class="row">
                    @php
                        $nomeEmp = DB::table('cadastro_empresas')->selectRaw('empresa_nome')->where('empresa_codigo','=',$dadosConexao[0]->conexao_empresa)->get();
                        $nomeEmpresa = $dadosConexao[0]->conexao_empresa.' - '.$nomeEmp[0]->empresa_nome;
                    @endphp
                    <!-- Nome -->
                    <x-adminlte-input name="empresa" type="text" value="{{$nomeEmpresa}}" fgroup-class="col-md-6" readonly>
                        <x-slot name="label">
                            Empresa <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>

                    @php 
                        if(!empty($dadosConexao[0]->conexao_provedor)){
                          $nomePro = DB::table('parametros_fat_nfs_provedores')->selectRaw('provedor_desc')->where('provedor_id','=',$dadosConexao[0]->conexao_provedor)->get();
                          $nomeProvedor = $dadosConexao[0]->conexao_provedor.' - '.$nomePro[0]->provedor_desc;
                        }else{
							$nomeProvedor = '';
                        }
                    @endphp
                    <!-- Nome -->
                    <x-adminlte-input name="provedor" type="text" value="{{$nomeProvedor}}" fgroup-class="col-md-6" readonly>
                        <x-slot name="label">
                            Provedor da NFS-e <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                
                <div class="row">
                    <!-- Ambiente do Serviço -->
                    <x-adminlte-select name="ambiente" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Ambiente <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="['H' => 'Homologação', 'P' => 'Produção']" empty-option="Selecione..." selected="{{$dadosConexao[0]['conexao_ambiente']}}"/>
                    </x-adminlte-select>
                </div>

                <div class="row">
                    <!-- Inscrição Estadual -->
                    <x-adminlte-input name="usuario" type="text" label="Usuario de Acesso" fgroup-class="col-md-6" value="{{$dadosConexao[0]['conexao_usuario']}}">
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-user"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <!-- Inscrição Municipal -->
                    <x-adminlte-input name="senha" type="text" label="Senha de Acesso" fgroup-class="col-md-6" value="{{$dadosConexao[0]['conexao_senha']}}">
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-key"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>

                <div class="row">
                    <!-- Inscrição Municipal -->
                    <x-adminlte-input name="token" type="text" label="Token de Acesso" fgroup-class="col-md-6" value="{{$dadosConexao[0]['conexao_token']}}">
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-unlock"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <!-- Inscrição Municipal -->
                    <x-adminlte-input name="wsdl" type="text" label="Caminho URL / WSDL" fgroup-class="col-md-6" value="{{$dadosConexao[0]['conexao_wsdl']}}">
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-route"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>

                <!-- /.card -->
                <x-slot name="footerSlot">
                    <div class="d-flex justify-content-between w-100">
                        <x-adminlte-button class="btn-nexus" type="submit" label="Salvar" theme="info" icon="fa-solid fa-share-from-square"/>
                        @if($appOrigem == 'parametrosNfsConexao')
                        <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('parametrosNfsConexao') }}'" label="Voltar" theme="info" icon=""/>
                        @else
                        <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.parFatNfs') }}'" label="Voltar" theme="info" icon=""/>
                        @endif
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
            empresa: {
                required: true
            },
            provedor: {
                required: true
            },
            ambiente: {
                required: true
            },
            usuario: {
                maxlength: 80
            },
            senha: {
                maxlength: 80
            },
            token: {
                maxlength: 80
            },
            wsdl: {
                maxlength: 100
            },
        },
        messages: {
            empresa: {
                required: "Por Favor informe uma Empresa"
            },
            provedor: {
                required: "Por Favor informe um Provedor"
            },
            ambiente: {
                required: "Por Favor informe um Ambiente"
            },
            usuario: {
                maxlength: "Informe no máximo 80 caracteres"
            },
            senha: {
                maxlength: "Informe no máximo 80 caracteres"
            },
            token: {
                maxlength: "Informe no máximo 80 caracteres"
            },
            wsdl: {
                maxlength: "Informe no máximo 100 caracteres"
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
