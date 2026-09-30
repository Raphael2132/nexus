@extends('adminlte::page')

@section('title', 'Motivos de Cancelamento')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Parâmetros Gerais</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('motivoCancelamento.index')}}">Motivos de Cancelamento</a>
            </li>
            @if($acao == 'N')
                <li class="breadcrumb-item active">Cadastro de Motivos de Cancelamento</li>
            @else
                <li class="breadcrumb-item active">Manutenção de Motivos de Cancelamento</li>
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
        <form method="post" action="{{route('motivoCancelamento.store')}}" id="quickForm" novalidate="novalidate">
        @csrf 
        @method('post')
        @else
        <form method="post" action="{{route('motivoCancelamento.update',['motivoCancelamento' => $dadosMotCan])}}" id="quickForm" novalidate="novalidate">
        @csrf 
        @method('put')
        @endif
            <x-adminlte-card title="{{ $acao == 'N' ? 'Cadastro de Novo' : 'Manutenção do' }} Motivo de Cancelamento" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
                @php 
                    if($acao == 'N'){
                        $codigo = '';
                        $desc = '';
                    }else{
                        $codigo = $dadosMotCan->canmot_codigo;
                        $desc = $dadosMotCan->canmot_desc;
                    }
                @endphp

                <div class="row"> 
                    <!-- Código -->
                    <x-adminlte-input name="codigo" type="number" value="{{$codigo}}" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Código <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>

                    <!-- Descrição -->
                    <x-adminlte-input name="descricao" type="text" value="{{$desc}}" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Descrição <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>
                </div>                          

                <!-- /.card -->
                <x-slot name="footerSlot">
                    @php
                        if(!empty($dadosMotCan->canmot_id)){
                            $motCan = $dadosMotCan->canmot_id;
                        }else{
                            $motCan = '';
                        }
                    @endphp
                    <div class="d-flex justify-content-between w-100">
                        <div class="d-flex">
                            <x-adminlte-button class="btn-nexus mr-2 btn_novo" type="button" onclick="window.location='{{route('motivoCancelamento.create')}}'" label="Novo" theme="" icon="fa-solid fa-plus"/>
                            <x-adminlte-button class="btn-nexus mr-2 btn_salvar" type="submit" label="Salvar" theme="info" icon="fa-solid fa-share-from-square"/>
                            <x-adminlte-button class="btn-nexus mr-2 btn_incluir" type="submit" label="Incluir" theme="info" icon="fa-solid fa-plus"/>
                            <x-adminlte-button class="btn-nexus mr-2 btn_excluir" type="button" data-id="{{$motCan}}" data-token="{{ csrf_token() }}" label="Excluir" theme="info" icon="fa-solid fa-trash"/>
                        </div>
                        <div class="d-flex">
                            <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('motivoCancelamento.index') }}'" label="Voltar" theme="info" icon=""/>
                        </div>
                    </div>
                </x-slot>
            </x-adminlte-card>
        </form>
        @if($acao != 'N')
        <!-- Formulário escondido para exclusão do registro -->
        <form id="delete-form-{{ $motCan }}" action="{{ route('motivoCancelamento.destroy', $motCan) }}" method="POST" style="display: none;">
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
       
        //Verifica de onde veio a app se foi do cadastro ou edição
        var acao = {!! json_encode($acao) !!};

        if(acao == 'N'){
            $("#codigo").attr("disabled", false);
            $(".btn_novo").hide();
            $(".btn_excluir").hide();
            $(".btn_salvar").hide();
        }else{
            $("#codigo").attr("disabled", true);
            $(".btn_incluir").hide();
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
                required: true
            },
            descricao: {
                required: true,
                maxlength: 80
            },
        },
        messages: {
            codigo: {
                required: "Por Favor informe um Código"
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
