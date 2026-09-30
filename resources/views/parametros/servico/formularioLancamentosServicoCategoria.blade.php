@extends('adminlte::page')

@section('title', 'Categorias de Atendimento')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Parâmetros Gerais</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('categAtendimento.index')}}">Categorias de Atendimento</a>
            </li>
            @if($acao == 'N')
                <li class="breadcrumb-item active">Cadastro Categoria de Atendimento</li>
            @else
                <li class="breadcrumb-item active">Manutenção Categoria de Atendimento</li>
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
        <form method="post" action="{{route('categAtendimento.store')}}" id="quickForm" novalidate="novalidate">
        @csrf 
        @method('post')
        @else
        <form method="post" action="{{route('categAtendimento.update', ['categAtendimento' => $dadosCat])}}" id="quickForm" novalidate="novalidate">
        @csrf 
        @method('put')
        @endif
            <x-adminlte-card title="{{ $acao == 'N' ? 'Cadastro de Nova' : 'Manutenção da' }} Categoria de Atendimento" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>

                <div class="row">
                    @php
                        if(!empty($dadosCat->categoria_codigo)){
                            $codigo_sel = $dadosCat->categoria_codigo;
                        }else{
                            $codigo_sel = '';
                        }

                        if(!empty($dadosCat->categoria_desc)){
                            $descricao_sel = $dadosCat->categoria_desc;
                        }else{
                            $descricao_sel = '';
                        }
                    @endphp
                    <!-- Código -->
                    <x-adminlte-input class="text-uppercase" name="codigo" type="text" value="{{$codigo_sel}}" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Código <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>
                    
                    <!-- Descrição -->
                    <x-adminlte-input name="descricao" type="text" value="{{$descricao_sel}}" fgroup-class="col-md-8">
                        <x-slot name="label">
                            Descrição <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>
                </div>

                <!-- /.card -->
                <x-slot name="footerSlot">
                    @php
                        if(!empty($dadosCat)){
                            $tipoCat = $dadosCat->categoria_id;
                        }else{
                            $tipoCat = '';
                        }
                    @endphp
                    <div class="d-flex justify-content-between w-100">
                        <div class="d-flex">
                            <x-adminlte-button class="btn-nexus mr-2 btn_novo" type="button" onclick="window.location='{{ route('categAtendimento.create') }}'" label="Nova Categoria" theme="" icon="fa-solid fa-plus"/>
                            <x-adminlte-button class="btn-nexus mr-2 btn_salvar" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                            <x-adminlte-button class="btn-nexus mr-2 btn_excluir" type="button" data-id="{{$tipoCat}}" data-token="{{ csrf_token() }}" label="Excluir" theme="" icon="fa-solid fa-trash"/>
                        </div>
                        <div class="d-flex">
                            <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('categAtendimento.index') }}'" label="Voltar" theme="" icon=""/>
                        </div>
                    </div>
                </x-slot>
            </x-adminlte-card>
        </form>
        @if($acao != 'N')
        <!-- Formulário escondido para exclusão do registro -->
        <form id="delete-form-{{ $tipoCat }}" action="{{ route('categAtendimento.destroy', $dadosCat) }}" method="POST" style="display: none;">
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
            codigo: {
                required: true,
                maxlength: 1,
                minlength: 1
            },
            descricao: {
                required: true,
                maxlength: 80
            },
        },
        messages: {
            codigo: {
                required: "Por Favor informe um Código",
                maxlength: "O Código deve ter 1 caracter",
                minlength: "O Código deve ter 1 caracter"
            },
            descricao: {
                required: "Por Favor informe a Descrição",
                maxlength: "Infome no máximo 80 caracteres"
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
