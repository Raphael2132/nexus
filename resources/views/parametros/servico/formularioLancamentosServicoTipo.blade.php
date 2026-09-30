@extends('adminlte::page')

@section('title', 'Tipos de Serviço')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Parâmetros Gerais</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('tipoServico.index')}}">Tipos de Serviço</a>
            </li>
            @if($acao == 'N')
                <li class="breadcrumb-item active">Cadastro Tipo de Serviço</li>
            @else
                <li class="breadcrumb-item active">Manutenção Tipo de Serviço</li>
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
        @php 
            $titulo = 'Cadastro de Novo Tipo de Serviço';
        @endphp
        <form method="post" action="{{route('tipoServico.store')}}" id="quickForm" novalidate="novalidate">
        @csrf 
        @method('post')
        @else
        @php 
            $titulo = 'Manutenção do Tipo de Serviço';
        @endphp
        <form method="post" action="{{route('tipoServico.update', ['tipoServico' => $dadosTipo])}}" id="quickForm" novalidate="novalidate">
        @csrf 
        @method('put')
        @endif
            <x-adminlte-card :title="$titulo" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>

                <div class="row">
                    @php
                        $array_opt = HelperArraySelect::arrayEmpresas(1,1);

                        if(!empty($dadosTipo->tipsrv_emp)){
                            $emp_sel = $dadosTipo->tipsrv_emp;
                        }else{
                            $emp_sel = '';
                        }

                        if(!empty($dadosTipo->tipsrv_sts)){
                            $sts_sel = $dadosTipo->tipsrv_sts;
                        }else{
                            $sts_sel = 'A';
                        }

                        if(!empty($dadosTipo->tipsrv_nom)){
                            $descricao_sel = $dadosTipo->tipsrv_nom;
                        }else{
                            $descricao_sel = '';
                        }
                    @endphp
                    <!-- Empresa do Setor -->
                    <x-adminlte-select name="empresa" fgroup-class="col-md-5">
                        <x-slot name="label">
                            Empresa <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_opt" empty-option="Selecione..." selected="{{$emp_sel}}"/>
                    </x-adminlte-select>
                    
                    <!-- Status do tipo do serviço -->
                    <x-adminlte-select name="status" fgroup-class="col-md-2">
                        <x-slot name="label">
                            Status <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="['A' => 'Ativo', 'D' => 'Desativado']" selected="{{$sts_sel}}"/>
                    </x-adminlte-select>
                    
                    <!-- Descrição -->
                    <x-adminlte-input name="descricao" type="text" value="{{$descricao_sel}}" placeholder="Informe a Descrição" fgroup-class="col-md-5">
                        <x-slot name="label">
                            Descrição <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>
                </div>

                <div class="row">
                    @php
                        if(!empty($dadosTipo->tipsrv_cod)){
                            $codigo_sel = $dadosTipo->tipsrv_cod;
                        }else{
                            $codigo_sel = '';
                        }

                        $array_opt_cat = HelperArraySelect::arrayCategoriaAtend(1,1);

                        if(!empty($dadosTipo->tipsrv_cat)){
                            $cat_sel = $dadosTipo->tipsrv_cat;
                        }else{
                            $cat_sel = '';
                        }

                        $array_opt_are = HelperArraySelect::arrayArea(1,1);

                        if(!empty($dadosTipo->tipsrv_are)){
                            $are_sel = $dadosTipo->tipsrv_are;
                        }else{
                            $are_sel = '';
                        }
                    @endphp
                    <!-- Código -->
                    <x-adminlte-input class="text-uppercase" name="codigo" type="text" value="{{$codigo_sel}}" placeholder="Informe o Código de dois dígitos" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Código <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>

                    <!--categoria -->
                    <x-adminlte-select name="categoria" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Categoria <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_opt_cat" empty-option="Selecione..." selected="{{$cat_sel}}"/>
                    </x-adminlte-select>

                    <!-- area -->
                    <x-adminlte-select name="area" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Área <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_opt_are" empty-option="Selecione..." selected="{{$are_sel}}"/>
                    </x-adminlte-select>
                </div>

                <div class="row"> 
                    @php
                        if(!empty($dadosTipo->tipsrv_pmt_des)){
                            $pmt_des_sel = $dadosTipo->tipsrv_pmt_des;
                        }else{
                            $pmt_des_sel = 'N';
                        }
                    @endphp
                    <!-- Permite Desconto no Lançamento da TMO -->
                    <x-adminlte-select name="permiteDesc" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Permite Desconto na TMO <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" selected="{{$pmt_des_sel}}"/>
                    </x-adminlte-select>
                </div>

                <div class="row valores-desconto"> 
                    @php
                        if(!empty($dadosTipo->tipsrv_pmd)){
                            $pmd_sel = $dadosTipo->tipsrv_pmd;
                        }else{
                            $pmd_sel = '';
                        }

                        if(!empty($dadosTipo->tipsrv_vmd)){
                            $vmd_sel = $dadosTipo->tipsrv_vmd;
                        }else{
                            $vmd_sel = '';
                        }
                    @endphp
                    <!-- Percentual Máximo de Desconto -->
                    <x-adminlte-input name="perMaxDes" type="text" placeholder="0,00" value="{{$pmd_sel}}" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Percentual Máximo de Desconto <span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-percent"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <!-- Valor Máximo de Desconto -->
                    <x-adminlte-input name="valMaxDes" type="text" placeholder="0,00" value="{{$vmd_sel}}" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Valor Máximo de Desconto <span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-brazilian-real-sign"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>

                <div class="row"> 
                    @php
                        if(!empty($dadosTipo->tipsrv_ahs)){
                            $ahs_sel = $dadosTipo->tipsrv_ahs;
                        }else{
                            $ahs_sel = 'N';
                        }

                        if(!empty($dadosTipo->tipsrv_avs)){
                            $avs_sel = $dadosTipo->tipsrv_avs;
                        }else{
                            $avs_sel = 'N';
                        }
                    @endphp
                    <!-- Altera hora do serviço -->
                    <x-adminlte-select name="altHR" label="Altera Hora do Serviço" fgroup-class="col-md-6">
                        <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" selected="{{$ahs_sel}}"/>
                    </x-adminlte-select>

                    <!-- Altera Valor do serviço -->
                    <x-adminlte-select name="altVLR" label="Altera Valor do Serviço" fgroup-class="col-md-6">
                        <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" selected="{{$avs_sel}}"/>
                    </x-adminlte-select>
                </div>

                <!-- /.card -->
                <x-slot name="footerSlot">
                    @php
                        if(!empty($dadosTipo)){
                            $tipoSRV = $dadosTipo->tipsrv_id;
                        }else{
                            $tipoSRV = '';
                        }
                    @endphp
                    <div class="d-flex justify-content-between w-100">
                        <div class="d-flex">
                            <x-adminlte-button class="btn-nexus mr-2 btn_novo" type="button" onclick="window.location='{{ route('tipoServico.create') }}'" label="Novo Tipo de Serviço" theme="" icon="fa-solid fa-plus"/>
                            <x-adminlte-button class="btn-nexus mr-2 btn_salvar" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                            <x-adminlte-button class="btn-nexus mr-2 btn_excluir" type="button" data-id="{{$tipoSRV}}" data-token="{{ csrf_token() }}" label="Excluir" theme="" icon="fa-solid fa-trash"/>
                        </div>
                        <div class="d-flex">
                            <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('tipoServico.index') }}'" label="Voltar" theme="" icon=""/>
                        </div>
                    </div>
                </x-slot>
            </x-adminlte-card>
        </form>
        @if($acao != 'N')
        <!-- Formulário escondido para exclusão do registro -->
        <form id="delete-form-{{ $tipoSRV }}" action="{{ route('tipoServico.destroy', $dadosTipo) }}" method="POST" style="display: none;">
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
@section('plugins.Inputmask', true)
@section('plugins.Select2', true)

@section('css')
@stop

@section('js')
<script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script> 

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

        //Mascaras de campos float
        //$('#perMaxDes').mask('#.##0,00', {reverse: true});
        //$('#perMaxDes').mask('##0,00%', {reverse: true});
        $('#perMaxDes').mask('000,00', {reverse: true});
        //$('#valMaxDes').mask('#.##0,00', {reverse: true});
        //$('#valMaxDes').mask("#.##0,00", {reverse: true});
        $('#valMaxDes').mask('0.000.000.000.000,00', {reverse: true});        

        //Verifica de onde veio a app, cadastro ou edição
        var acao = {!! json_encode($acao) !!};

        console.log(acao);

        if(acao == 'E'){
            $("#codigo").attr("disabled", true);
            $("#empresa").attr("disabled", true);
            $("#categoria").attr("disabled", true);
            $("#area").attr("disabled", true);

            if($("#permiteDesc").val() == 'N'){
                $(".valores-desconto").hide();
            }
        }else{
            $(".btn_novo").hide();
            $(".btn_excluir").hide();
            $(".valores-desconto").hide();
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

        //Evento ao trocar o valor do campo tipo da tarefa
        $("#permiteDesc").change(function(){
            
            if(this.value == 'S'){
                $(".valores-desconto").show();
            }else{
                $("#perMaxDes").val('');
                $("#valMaxDes").val('');
                $(".valores-desconto").hide();
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
    jQuery.validator.addMethod("maxpercent", function(value, element) {
        return this.optional(element) || /^(\d{1,2}|\d{1,2}\,\d{1,2}|100\,[0]{1,2}|100)$/i.test(value);
    }, "Porcentagem máxima de 100,00 %");

    $('#quickForm').validate({
        rules: {
            empresa: {
                required: true
            },
            codigo: {
                required: true,
                maxlength: 2,
                minlength: 2
            },
            descricao: {
                required: true,
                maxlength: 80
            }, 
            categoria: {
                required: true
            },
            area: {
                required: true
            },
            perMaxDes: {
                maxpercent: true
            },
        },
        messages: {
            empresa: {
                required: "Por Favor informe uma Empresa"
            },
            codigo: {
                required: "Por Favor informe um Código",
                maxlength: "O Código deve ter 2 caracteres",
                minlength: "O Código deve ter 2 caracteres"
            },
            descricao: {
                required: "Por Favor informe a Descrição",
                maxlength: "Infome no máximo 80 caracteres"
            },
            categoria: {
                required: "Por Favor informe uma Categoria"
            },
            area: {
                required: "Por Favor informe uma Área"
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
