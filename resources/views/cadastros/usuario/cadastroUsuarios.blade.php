@extends('adminlte::page')

@section('title', 'Cadastro de Usuarios')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Cadastros</h4>
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
            <x-adminlte-card title="Cadastro de Usuário" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
            
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
                    <x-adminlte-select name="empresa" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Empresa <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_opt" empty-option="Selecione..."/>
                    </x-adminlte-select>

                    <!-- Tipo Usuario -->
                    <x-adminlte-select name="tipoUsuario" fgroup-class="col-md-3">
                        <x-slot name="label">
                            Tipo de Usuário <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="['A' => 'Administrador', 'P' => 'Padrão']" empty-option="Selecione..."/>
                    </x-adminlte-select>

                    <!-- Status do Usuario -->
                    <x-adminlte-select name="statusUsuario" fgroup-class="col-md-3">
                        <x-slot name="label">
                            Status <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="['A' => 'Ativo', 'D' => 'Desativado']" empty-option="Selecione..."/>
                    </x-adminlte-select>
                </div>
                    
                <!-- Nome -->
                <x-adminlte-input name="nome" type="text" placeholder="Nome" fgroup-class="col-md-12" autocomplete="off">
                    <x-slot name="label">
                        Nome <span style="color:red;">*</span>
                    </x-slot>
                    <x-slot name="prependSlot">
                        <div class="input-group-text x-slot-nexus">
                            <i class="fa-solid fa-id-badge fa-lg"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>

                <!-- Senha -->
                <x-adminlte-input name="senha" type="password" placeholder="Nova Senha" igroup-size="md" fgroup-class="col-md-12" autocomplete="new-password">
                    <x-slot name="prependSlot">
                        <div class="input-group-text x-slot-nexus">
                            <i class="fa-solid fa-key"></i>
                        </div>
                    </x-slot>
                    <x-slot name="label">
                        Senha <span style="color:red;">*</span>
                    </x-slot>
                    <x-slot name="appendSlot">
                        <x-adminlte-button class="toggle-password btn-outline-nexus" theme="" data-target="senha" icon="fa fa-eye"/>
                    </x-slot>
                </x-adminlte-input>
                <x-adminlte-input name="senha2" type="password" placeholder="Confirmar Nova Senha" igroup-size="md" fgroup-class="col-md-12" autocomplete="new-password">
                    <x-slot name="prependSlot">
                        <div class="input-group-text x-slot-nexus">
                            <i class="fa-solid fa-key"></i>
                        </div>
                    </x-slot>
                    <x-slot name="label">
                        Confirmar Senha <span style="color:red;">*</span>
                    </x-slot>
                    <x-slot name="appendSlot">
                        <x-adminlte-button class="toggle-password btn-outline-nexus" theme="" data-target="senha2" icon="fa fa-eye"/>
                    </x-slot>
                </x-adminlte-input>

                <div class="row">
                    <!-- Tipo do Email -->
                    <x-adminlte-select name="tipoEmail" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Tipo do Email <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="['P' => 'Pessoal', 'C' => 'Comercial']" empty-option="Selecione..."/>
                    </x-adminlte-select>

                    <!-- Email -->
                    <x-adminlte-input name="email" type="email" placeholder="email@exemplo.com" fgroup-class="col-md-8">
                        <x-slot name="label">
                            Email <span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>

                <x-slot name="footerSlot">
                    <div class="d-flex justify-content-between w-100">
                        <x-adminlte-button class="btn-nexus" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                        <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.usuarios') }}'" label="Voltar" theme="" icon=""/>
                    </div>
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
    document.addEventListener('DOMContentLoaded', function () {
        const togglePasswordButtons = document.querySelectorAll('.toggle-password');

        togglePasswordButtons.forEach(button => {
            button.addEventListener('click', function () {
                const targetInput = document.querySelector(`input[name="${this.getAttribute('data-target')}"]`);
                const icon = this.querySelector('i');

                if (targetInput.type === 'password') {
                    targetInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    targetInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    });
</script>

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
            senha2: {
                required: true,
                minlength: 6,
                equalTo: "#senha"
            },
            email: {
                required: true,
                email: true
            },
            empresa: {
                required: true
            },
            tipoEmail: {
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
                required: "Por favor, insira a nova senha.",
                minlength: "A senha deve ter pelo menos 6 caracteres."
            },
            senha2: {
                required: "Por favor, confirme a nova senha.",
                minlength: "A senha deve ter pelo menos 6 caracteres.",
                equalTo: "As senhas não coincidem."
            },
            email: {
                required: "Por Favor informe um Email válido do Usuário",
                email: "Infome um email válido"
            },
            empresa: {
                required: "Por Favor informe uma Empresa"
            },
            tipoEmail: {
                required: "Por Favor informe o Tipo do Email"
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
