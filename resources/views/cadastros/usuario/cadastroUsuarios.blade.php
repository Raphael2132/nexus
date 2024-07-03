@extends('adminlte::page')

@section('title', 'Cadastro de Usuarios')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h1>Cadastros</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">
                    <a href="{{route('home.usuarios')}}">Usuários</a>
                </li>
                @if($tipo != 'newCad' && $tipo != 'editCad')
                    <li class="breadcrumb-item active">
                        <a href="{{route('usuarios', ['tipo' => $tipo])}}">Usuários Cadastrados</a>
                    </li>
                @endif
                <li class="breadcrumb-item active">Cadastro de Usuários</li>
            </ol>
        </div>
    </div>
@stop

@section('content')

<div class="d-flex justify-content-center">
    <div class="col-md-6">
        <form method="post" action="{{route('usuario.inserir',['tipo' => $tipo])}}" id="quickForm" novalidate="novalidate">
            @csrf 
            @method('post')
            <x-adminlte-card title="Cadastro de Usuário" theme="navy">
            
                @php 
                    $data = DB::table('cadastro_empresas')->select('empresa_codigo', 'empresa_nome')->orderBy('empresa_codigo', 'asc')->get();

                    $new_array1 =[];
                    $new_array2 =[];

                    foreach ($data as $empresa) {
                        $new_array1[] = $empresa->empresa_codigo;
                        $new_array2[] = $empresa->empresa_codigo.' - '.$empresa->empresa_nome;
                    }
                    $array_opt = array_combine($new_array1, $new_array2);
                @endphp
                <div class="row">
                    <!-- Empresa -->
                    <x-adminlte-select name="empresa" label="Empresa" fgroup-class="col-md-6">
                        <x-adminlte-options :options="$array_opt" empty-option="Selecione..."/>
                    </x-adminlte-select>

                    <!-- Tipo Usuario -->
                    <x-adminlte-select name="tipoUsuario" label="Tipo de Usuário" fgroup-class="col-md-3">
                        <x-adminlte-options :options="['A' => 'Administrador', 'P' => 'Padrão']" empty-option="Selecione..."/>
                    </x-adminlte-select>

                    <!-- Status do Usuario -->
                    <x-adminlte-select name="statusUsuario" label="Status" fgroup-class="col-md-3">
                        <x-adminlte-options :options="['A' => 'Ativo', 'D' => 'Desativado']" empty-option="Selecione..."/>
                    </x-adminlte-select>
                </div>
                    
                <!-- Nome -->
                <x-adminlte-input name="nome" label="Nome" type="text" placeholder="Nome" fgroup-class="col-md-12"/>

                <!-- Senha -->
                <x-adminlte-input name="senha" label="Senha" type="password" placeholder="Senha" fgroup-class="col-md-12"/>

                <div class="row">
                    <!-- Tipo do Email -->
                    <x-adminlte-select name="tipoEmail" label="Tipo do Email" fgroup-class="col-md-4">
                        <x-adminlte-options :options="['P' => 'Pessoal', 'C' => 'Comercial']" empty-option="Selecione..."/>
                    </x-adminlte-select>

                    <!-- Email -->
                    <x-adminlte-input name="email" type="email" label="Email" placeholder="email@exemplo.com" fgroup-class="col-md-8">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>

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
@section('plugins.Select2', true)

@section('css')
@stop

@section('js')
<script>
$(function () {
    $('#quickForm').validate({
        rules: {
            tipoUsuario: {
                required: true
            },
            statusUsuario: {
                required: true
            },
            nome: {
                required: true,
                minlength: 5
            },
            senha: {
                required: true,
                minlength: 6
            },
            email: {
                required: true,
                email: true
            },
            empresa: {
                required: true
            },
        },
        messages: {
            tipoUsuario: {
                required: "Por Favor informe um Tipo de Usuário"
            },
            statusUsuario: {
                required: "Por Favor informe o Status do Usuário"
            },
            nome: {
                required: "Por Favor informe o Nome do Usuário",
                minlength: "Infome no mínimo 5 caracteres"
            },
            senha: {
                required: "Por Favor informe a Senha do Usuário",
                minlength: "Infome no mínimo 6 caracteres"
            },
            email: {
                required: "Por Favor informe um Email válido do Usuário",
                email: "Infome um email válido"
            },
            empresa: {
                required: "Por Favor informe uma Empresa"
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
@stop
