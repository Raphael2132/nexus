@extends('adminlte::page')

@section('title', 'Cadastro de Motivos de Cancelamento')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h4 style="margin-bottom: 0px !important;">Parâmetros Gerais</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">
                    <a href="{{route('home.parMotCan')}}">Motivos de Cancelamento</a>
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
        <form method="post" action="{{route('parametrosSisMotCan.insert')}}" id="quickForm" novalidate="novalidate">
        @else
        <form method="post" action="{{route('parametrosSisMotCan.update')}}" id="quickForm" novalidate="novalidate">
        @endif
            @csrf 
            <x-adminlte-card title="{{ $acao == 'N' ? 'Cadastro de Novo' : 'Manutenção do' }} Motivo de Cancelamento" theme="navy">
                @php 
                    if($acao == 'N'){
                        $codigo = '';
                        $desc = '';
                    }else{
                        $codigo = $dadosMotCan[0]->canmot_codigo;
                        $desc = $dadosMotCan[0]->canmot_desc;
                    }
                @endphp

                <div class="row"> 
                    <!-- Código -->
                    <x-adminlte-input name="codigo" label="Código" type="number" value="{{$codigo}}" fgroup-class="col-md-6"/>

                    <!-- Descrição -->
                    <x-adminlte-input name="descricao" label="Descrição" type="text" value="{{$desc}}" fgroup-class="col-md-6"/>
                </div>                          

                <!-- /.card -->
                <x-slot name="footerSlot">
                    @php
                        if(!empty($dadosMotCan[0]->canmot_id)){
                            $motCan = $dadosMotCan[0]->canmot_id;
                        }else{
                            $motCan = '';
                        }
                    @endphp
                    <div class="d-flex justify-content-between w-100">
                        <div class="d-flex">
                            <x-adminlte-button class="btn-flat mr-2 btn_novo" type="button" onclick="window.location='{{route('parametrosSisMotCan.cadastroMotCan',['acao' => 'N', 'dadosMotCan' => ' '])}}'" label="Novo" theme="info" icon="fa-solid fa-plus"/>
                            <x-adminlte-button class="btn-flat mr-2 btn_salvar" type="submit" label="Salvar" theme="info" icon="fa-solid fa-share-from-square"/>
                            <x-adminlte-button class="btn-flat mr-2 btn_incluir" type="submit" label="Incluir" theme="info" icon="fa-solid fa-plus"/>
                            <x-adminlte-button class="btn-flat mr-2 btn_excluir" type="button" data-id="{{$motCan}}" data-token="{{ csrf_token() }}" label="Excluir" theme="info" icon="fa-solid fa-trash"/>
                        </div>
                        <div class="d-flex">
                            <x-adminlte-button class="btn-flat" type="button" onclick="window.location='{{ route('home.parMotCan') }}'" label="Voltar" theme="info" icon=""/>
                        </div>
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
| Eventos onClick da app
|--------------------------------------------------------------------------
-->

<script>
    $(document).ready(function() {

        //Ao clicar no botão salvar retira o disabled do campo para não ter problema no request do update do campo
        $(".btn_salvar").click(function(){
            $("#codigo").attr("disabled", false);
        });

        $(".btn_excluir").click(function(){
            var id = $(this).attr("data-id");

            var url = "{{ route('parametrosSisMotCan.destroy', [':id', 'ajax']) }}";
            url = url.replace(':id', id);

            $.ajax({
                url: url,
                dataType: "JSON",
                type: 'POST',
                data: {
                    '_token': $('meta[name=csrf-token]').attr("content"),
                    '_method': 'DELETE',
                    "id": id
                },
                success: function ()
                {
                    window.location = "{{ route('home.parMotCan') }}";
                }
            });
        });
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
            desc: {
                required: true,
                maxlength: 80
            },
        },
        messages: {
            codigo: {
                required: "Por Favor informe um Código"
            },
            desc: {
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
