@extends('adminlte::page')

@section('title', 'Cartão Corporativo')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Parâmetros Financeiro</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('financeiroCorporativo.index')}}">Cartão Corporativo</a>
            </li>
            @if($acao == 'N')
                <li class="breadcrumb-item active">Cadastro de Cartão Corporativo</li>
            @else
                <li class="breadcrumb-item active">Manutenção de Cartão Corporativo</li>
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
        <form method="post" action="{{route('financeiroCorporativo.store')}}" id="formulario-card-corp" novalidate="novalidate">
        @csrf 
        @method('post')
        @else
        <form method="post" action="{{route('financeiroCorporativo.update', ['financeiroCorporativo' => $dadosCardCorp])}}" id="formulario-card-corp" novalidate="novalidate">
        @csrf 
        @method('put')
        @endif
            @csrf 
            <x-adminlte-card title="{{ $acao == 'N' ? 'Cadastro de Novo' : 'Manutenção do' }} Cartão Corporativo" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
                @php 

                    $array_emp = HelperArraySelect::arrayEmpresas(1,1);

                    if($acao == 'N'){
                        $empresa = '';
                        $adm = '';
                        $numeroCard = '';
                        $nomeCard = '';
                        $diaFec = '';
                        $diaVct = '';
                        $qtdPar = '';
                        $sts = '';
                    }else{
                        $empresa = $dadosCardCorp->parcco_emp;
                        $adm = $dadosCardCorp->parcco_adm;
                        $numeroCard = $dadosCardCorp->parcco_num;
                        $nomeCard = $dadosCardCorp->parcco_nom;
                        $diaFec = $dadosCardCorp->parcco_dif;
                        $diaVct = $dadosCardCorp->parcco_div;
                        $qtdPar = $dadosCardCorp->parcco_par;
                        $sts = $dadosCardCorp->parcco_sts;
                    }

                    $array_adm = HelperArraySelect::arrayRazoesPorTipo(1,1,$empresa,'CO');
                @endphp

                <div class="row"> 
                    <!-- Empresa -->
                    <x-adminlte-select name="empresa" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Empresa <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_emp" empty-option="Selecione..." selected="{{$empresa}}"/>
                    </x-adminlte-select>

                    <!-- Administradora do Cartão -->
                    <x-adminlte-select name="administradora" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Administradora <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_adm" empty-option="Selecione..." selected="{{$adm}}"/>
                    </x-adminlte-select>

                    <!-- Status -->
                    <x-adminlte-select name="situacao" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Situação do Cartão <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="['A' => 'Ativo', 'D' => 'Desativado']" empty-option="Selecione..." selected="{{$sts}}"/>
                    </x-adminlte-select>
                </div> 

                <div class="row"> 
                    <!-- Número do Cartão -->
                    <x-adminlte-input name="numCard" type="text" value="{{$numeroCard}}" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Número do Cartão <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>

                    <!-- Nome do Cartão -->
                    <x-adminlte-input name="nomCard" type="text" value="{{$nomeCard}}" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Nome do Cartão <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>
                </div>     
                
                <div class="row"> 
                    <!-- Dia de Fechamento -->
                    <x-adminlte-input name="diaFec" type="number" value="{{$diaFec}}" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Dia de Fechamento <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>

                    <!-- Dia de Vencimento -->
                    <x-adminlte-input name="diaVct" type="number" value="{{$diaVct}}" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Dia de Vencimento <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>

                    <!-- Quantidade Máxima de Parcelas -->
                    <x-adminlte-input name="qtdPar" type="number" value="{{$qtdPar}}" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Quantidade Máxima de Parcelas <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>
                </div> 

                <!-- /.card -->
                <x-slot name="footerSlot">
                    @php
                        if(!empty($dadosCardCorp->parcco_id)){
                            $cardCorp = $dadosCardCorp->parcco_id;
                        }else{
                            $cardCorp = '';
                        }
                    @endphp
                    <div class="d-flex justify-content-between w-100">
                        <div class="d-flex">
                            <x-adminlte-button class="btn-nexus mr-2 btn_novo" type="button" onclick="window.location='{{route('financeiroCorporativo.create')}}'" label="Novo" theme="" icon="fa-solid fa-plus"/>
                            <x-adminlte-button class="btn-nexus mr-2 btn_salvar" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                            <x-adminlte-button class="btn-nexus mr-2 btn_incluir" type="submit" label="Incluir" theme="" icon="fa-solid fa-plus"/>
                            <x-adminlte-button class="btn-nexus mr-2 btn_excluir" type="button" data-id="{{$cardCorp}}" data-token="{{ csrf_token() }}" label="Excluir" theme="" icon="fa-solid fa-trash"/>
                        </div>
                        <div class="d-flex">
                            <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('financeiroCorporativo.index') }}'" label="Voltar" theme="" icon=""/>
                        </div>
                    </div>
                </x-slot>
            </x-adminlte-card>
        </form>
        @if($acao != 'N')
        <!-- Formulário escondido para exclusão do registro -->
        <form id="delete-form-{{ $cardCorp }}" action="{{ route('financeiroCorporativo.destroy', $cardCorp) }}" method="POST" style="display: none;">
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
            $("#administradora").attr("disabled", false);
            $("#empresa").attr("disabled", false);
            $(".btn_novo").hide();
            $(".btn_excluir").hide();
            $(".btn_salvar").hide();
        }else{
            $("#administradora").attr("disabled", true);
            $("#empresa").attr("disabled", true);
            $(".btn_incluir").hide();
        }

    });
</script>

<!--
|--------------------------------------------------------------------------
| Eventos onChange da app
|--------------------------------------------------------------------------
-->
<script>    
    $(document).ready(function() {
       
        //Evento de carregamento ajax dos dados dos setores
        $('#empresa').change(function(){

            if( $(this).val() ) {
                var emp = $(this).val();
                var tip = 'CO';

                var url = "{{ route('ajax.carregaRazaoAjax', [':tip',':emp']) }}";
                url = url.replace(':tip', tip);
                url = url.replace(':emp', emp);

                $.ajax({
                    url: url,
                    dataType: "JSON",
                    type: 'GET',
                    data: {
                        '_token': $('meta[name=csrf-token]').attr("content"),
                        '_method': 'GET',
                        "tip": tip,
                        "emp": emp
                    },
                    success: function (data)
                    {
                        if(data.razoes_ajax_existe == 'S'){

                            var options = '<option value="">Selecione...</option>';	

                            for (var i = 0; i < data.razoes_ajax.length; i++) {

                                options += '<option value="' + data.razoes_ajax[i].cod + '">' + data.razoes_ajax[i].desc_razao + '</option>';
                            }	

                            $('#administradora').html(options);

                        }else{
                            $('#administradora').html('<option value="">Selecione...</option>');
                        }
                    }
                });
            } else {
                $('#administradora').html('<option value="">Selecione...</option>');
            }
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

    $('#formulario-card-corp').validate({
        rules: {
            empresa: {
                required: true
            },
            administradora: {
                required: true
            },
            situacao: {
                required: true
            },
            numCard: {
                required: true,
                maxlength: 18
            },
            nomCard: {
                required: true,
                maxlength: 60
            },
            diaFec: {
                required: true
            },
            diaVct: {
                required: true
            },
            qtdPar: {
                required: true
            },
        },
        messages: {
            empresa: {
                required: "Por Favor informe a Empresa"
            },
            administradora: {
                required: "Por Favor informe a Administradora"
            },
            situacao: {
                required: "Por Favor informe a Situação "
            },
            numCard: {
                required: "Por Favor informe o Cartão",
                maxlength: "Infome no máximo 18 caracteres"
            },
            nomCard: {
                required: "Por Favor informe o Nome do Cartão",
                maxlength: "Infome no máximo 60 caracteres"
            },
            diaFec: {
                required: "Por Favor informe o Dia de Fechamento"
            },
            diaVct: {
                required: "Por Favor informe o Dia de Vencimento"
            },
            qtdPar: {
                required: "Por Favor informe a Quantidade Máxima de Parcelas"
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
