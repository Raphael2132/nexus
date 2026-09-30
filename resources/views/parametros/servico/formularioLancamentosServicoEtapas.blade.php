@extends('adminlte::page')

@section('title', 'Etapas de Atendimento')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Parâmetros Gerais</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('etapaAtendimento.index')}}">Etapas de Atendimento</a>
            </li>
            @if($acao == 'N')
                <li class="breadcrumb-item active">Cadastro Etapas de Atendimento</li>
            @else
                <li class="breadcrumb-item active">Manutenção Etapas de Atendimento</li>
            @endif
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-8">
        <!-- Define se o formulario é edição ou novo -->
        @if($acao == 'N')
        <form method="post" action="{{route('etapaAtendimento.store')}}" id="quickForm" novalidate="novalidate">
        @csrf 
        @method('post')
        @else
        <form method="post" action="{{route('etapaAtendimento.update', ['etapaAtendimento' => $dadosEAT])}}" id="quickForm" novalidate="novalidate">
        @csrf 
        @method('put')
        @endif
            <x-adminlte-card title="Cadastro de Nova Etapa de Atendimento" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>

                <div class="row">
                    @php
                        $array_opt = HelperArraySelect::arrayEmpresas(1,1);

                        if(!empty($dadosEAT->eat_emp)){
                            $emp_sel = $dadosEAT->eat_emp;
                        }else{
                            $emp_sel = '';
                        }

                        $array_opt_cat = HelperArraySelect::arrayCategoriaAtend(1,1);

                        if(!empty($dadosEAT->eat_cat)){
                            $cat_sel = $dadosEAT->eat_cat;
                        }else{
                            $cat_sel = '';
                        }
                    @endphp

                    <!-- Empresa -->
                    <x-adminlte-select name="empresa" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Empresa <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_opt" empty-option="Selecione..." selected="{{$emp_sel}}"/>
                    </x-adminlte-select>

                    <!-- Categoria -->
                    <x-adminlte-select name="categoria" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Categoria <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_opt_cat" empty-option="Selecione..." selected="{{$cat_sel}}"/>
                    </x-adminlte-select>
                </div>

                <div class="row">
                    @php
                        if(!empty($dadosEAT->eat_cod)){
                            $codigo_sel = $dadosEAT->eat_cod;
                        }else{
                            $codigo_sel = '';
                        }

                        if(!empty($dadosEAT->eat_ord)){
                            $ordem_sel = $dadosEAT->eat_ord;
                        }else{
                            $ordem_sel = '';
                        }

                        if(!empty($dadosEAT->eat_nom)){
                            $descricao_sel = $dadosEAT->eat_nom;
                        }else{
                            $descricao_sel = '';
                        }
                    @endphp                    
                    <!-- Grupo da Categoria -->
                    <x-adminlte-input name="codigo" type="number" value="{{$codigo_sel}}" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Grupo da Categoria <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>

                    <!-- Ordem -->
                    <x-adminlte-input name="ordem" type="number" value="{{$ordem_sel}}" fgroup-class="col-md-2">
                        <x-slot name="label">
                            Ordem <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>
                    
                    <!-- Descrição -->
                    <x-adminlte-input name="descricao" type="text" value="{{$descricao_sel}}" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Descrição <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>
                </div>

                <!-- /.card -->
                <x-slot name="footerSlot">
                    @php
                        if(!empty($dadosEAT)){
                            $tipoEAT = $dadosEAT->eat_id;
                        }else{
                            $tipoEAT = '';
                        }
                    @endphp
                    <div class="d-flex justify-content-between w-100">
                        <div class="d-flex">
                            <x-adminlte-button class="btn-nexus mr-2 btn_novo" type="button" onclick="window.location='{{ route('etapaAtendimento.create') }}'" label="Nova Categoria" theme="" icon="fa-solid fa-plus"/>
                            <x-adminlte-button class="btn-nexus mr-2 btn_salvar" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                            <x-adminlte-button class="btn-nexus mr-2 btn_excluir" type="button" data-id="{{$tipoEAT}}" data-token="{{ csrf_token() }}" label="Excluir" theme="" icon="fa-solid fa-trash"/>
                        </div>
                        <div class="d-flex">
                            <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('etapaAtendimento.index') }}'" label="Voltar" theme="" icon=""/>
                        </div>
                    </div>
                </x-slot>
            </x-adminlte-card>
        </form>
        @if($acao != 'N')
        <!-- Formulário escondido para exclusão do registro -->
        <form id="delete-form-{{ $tipoEAT }}" action="{{ route('etapaAtendimento.destroy', $dadosEAT) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
        @endif
    </div>
</div>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.jqueryValidation', true)
@section('plugins.Sweetalert2', true)
@section('plugins.toastr', true)
@section('plugins.Select2', true)

@section('css')
@stop

@section('js')
<!--
|--------------------------------------------------------------------------
| Eventos Inicial da app
|--------------------------------------------------------------------------
-->
<script>
    //Capturar o clique no botão e submeter o formulário de exclusão                    
    document.querySelectorAll('.btn_excluir').forEach(button => {
        button.addEventListener('click', function() {
            let setorId = this.getAttribute('data-id');
            let token = this.getAttribute('data-token'); 
            
            // Submete o formulário oculto
            document.getElementById('delete-form-' + setorId).submit();
        });
    });

    $(document).ready(function() {    

        //Verifica de onde veio a app, cadastro ou edição
        var acao = {!! json_encode($acao) !!};

        if(acao == 'E'){
            $("#empresa").attr("disabled", true);
            $("#codigo").attr("disabled", true);
        }else{
            $(".btn_novo").hide();
            $(".btn_excluir").hide();
        }
    });
</script>

<!--
|--------------------------------------------------------------------------
| Eventos Validate da app
|--------------------------------------------------------------------------
-->
<script>
$(function () {

    $('#quickForm').validate({
        rules: {
            empresa: {
                required: true
            },
            codigo: {
                required: true,
                maxlength: 9
            },
            ordem: {
                required: true,
                maxlength: 3
            },
            descricao: {
                required: true,
                maxlength: 80
            },
            categoria: {
                required: true
            },
            tipoSRV: {
                required: true
            },
        },
        messages: {
            empresa: {
                required: "Por Favor informe a Empresa"
            },
            codigo: {
                required: "Por Favor informe um Código",
                maxlength: "O Código deve ter no máximo 9 digitos"
            },
            ordem: {
                required: "Por Favor informe uma Ordem",
                maxlength: "A Ordem deve ter no máximo 3 digitos"
            },
            descricao: {
                required: "Por Favor informe a Descrição",
                maxlength: "Infome no máximo 80 caracteres"
            },
            categoria: {
                required: "Por Favor informe a Categoria"
            },
            tipoSRV: {
                required: "Por Favor informe o Tipo de Serviço"
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

<!--
|--------------------------------------------------------------------------
| Eventos de Messagem da app
|--------------------------------------------------------------------------
-->
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
