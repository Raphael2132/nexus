@extends('adminlte::page')

@section('title', 'Cadastro de Razões')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Cadastros</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('cadastroRazao.index')}}">Razão</a>
            </li>
            @if($tipoCad == 'N')
            <li class="breadcrumb-item active">Cadastro do Razão</li>
            @else
            <li class="breadcrumb-item active">Manutenção do Razão</li>
            @endif
        </ol>
    </div>
</div>
@stop

@section('content')
@php 
    if($tipoCad == 'N'){
        $titulo = "Cadastro de Novo Razão";
    }else{
        $titulo = "Manutenção do Razão Selecionado";
    }
@endphp
@if($tipoCad == 'N')
<form method="post" action="{{route('cadastroRazao.store')}}" id="formulario-razao" novalidate="novalidate">
@csrf 
@method('post')
@else
<form method="post" action="{{route('cadastroRazao.update',['cadastroRazao' => $dadosRazao])}}" id="formulario-razao" novalidate="novalidate">
@csrf 
@method('put')
@endif
    <x-adminlte-card :title="$titulo" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>

        <div class="row">
            @php
                $array_opt = HelperArraySelect::arrayEmpresas(1,1);
                $array_tipo_raz = HelperArraySelect::arrayTipoRazao(1,1);

                if(!empty($dadosRazao)){

                    $empSel = $dadosRazao->razao_empresa;
                    $tipoRazSel = $dadosRazao->razao_tipo;
                    $nomeSel = $dadosRazao->razao_nome;
                    $nomeFan = $dadosRazao->razao_nom_fan;
                    $valIniSel = $dadosRazao->razao_saldo_ini;
                    $codSel = $dadosRazao->razao_codigo;

                    if($tipoRazSel == "BA"){
                        $codBancoSel = $dadosRazao->razao_bco_cod;
                        $ageSel = $dadosRazao->razao_bco_age;
                        $ageDvSel = $dadosRazao->razao_bco_age_dv;
                        $contaSel = $dadosRazao->razao_bco_ncc;
                        $contaDvSel = $dadosRazao->razao_bco_ncc_dv;
                    }else{
                        $codBancoSel = '';
                        $ageSel = '';
                        $ageDvSel = '';
                        $contaSel = '';
                        $contaDvSel = '';
                    }

                    if($tipoRazSel == "CC"){
                        $tipoAdm = $dadosRazao->razao_tip_adm;
                        $depOn = Helper::formataSimNao($dadosRazao->razao_dep_on);
                        $qtdDiaPar = $dadosRazao->razao_dia_prl;
                        $qtdDiaVista = $dadosRazao->razao_dia_vst;
                        $bancoAdm = $dadosRazao->razao_bco_adm;
                        $bandCard = $dadosRazao->razao_ban_card;
                        $tipoCard = $dadosRazao->razao_tip_card;
                        $codAdm = $dadosRazao->razao_adm_cod;
                        $bancoAdmCorp = '';
                    }elseif($tipoRazSel == "CO"){
                        $tipoAdm = '';
                        $depOn = Helper::formataSimNao($dadosRazao->razao_dep_on);
                        $qtdDiaPar = '';
                        $qtdDiaVista = '';
                        $bancoAdm = '';
                        $bancoAdmCorp = $dadosRazao->razao_bco_adm;
                        $codAdm = '';
                        $bandCard = '';
                        $tipoCard = '';
                    }else{
                        $tipoAdm = '';
                        $depOn = 'Sim';
                        $qtdDiaPar = '';
                        $qtdDiaVista = '';
                        $bancoAdm = '';
                        $bancoAdmCorp = '';
                        $codAdm = '';
                        $bandCard = '';
                        $tipoCard = '';
                    }
                }else{
                    $empSel = '';
                    $tipoRazSel = '';
                    $nomeSel = '';
                    $valIniSel = '';
                    $codBancoSel = '';
                    $ageSel = '';
                    $ageDvSel = '';
                    $contaSel = '';
                    $contaDvSel = '';
                    $codSel = '';
                    $nomeFan = '';
                    $tipoAdm = '';
                    $depOn = 'Sim';
                    $qtdDiaPar = '';
                    $qtdDiaVista = '';
                    $codAdm = '';
                    $bancoAdm = '';
                    $bancoAdmCorp = '';
                    $bandCard = '';
                    $tipoCard = '';
                }
            @endphp

            <!-- Empresa -->
            <x-adminlte-select name="empresa" fgroup-class="col-md-6">
                <x-slot name="label">
                    Empresa <span style="color:red;">*</span>
                </x-slot>
                <x-adminlte-options :options="$array_opt" empty-option="Selecione..." selected="{{$empSel}}"/>
            </x-adminlte-select>

            <!-- Tipo do Razão -->
            <x-adminlte-select name="tipoRazao" type="text" fgroup-class="col-md-6">
                <x-slot name="label">
                    Tipo do Razão <span style="color:red;">*</span>
                </x-slot>
                <x-adminlte-options :options="$array_tipo_raz" empty-option="Selecione..." selected="{{$tipoRazSel}}"/>
            </x-adminlte-select>
        </div>

        <div class="row bloco-cod-nom">
            @if($tipoCad == 'N')
            <!-- Nome -->
            <x-adminlte-input name="nome" type="text" placeholder="Descrição do Razão" fgroup-class="col-md-6" value="{{$nomeSel}}">
                <x-slot name="label">
                    Nome do Razão <span style="color:red;">*</span>
                </x-slot>
            </x-adminlte-input>
            @else
            <!-- Código -->
            <x-adminlte-input name="codigo" type="text" placeholder="Código do Razão" fgroup-class="col-md-2" value="{{$codSel}}">
                <x-slot name="label">
                    Código do Razão <span style="color:red;">*</span>
                </x-slot>
            </x-adminlte-input>
            <!-- Nome -->
            <x-adminlte-input name="nome" type="text" placeholder="Descrição do Razão" fgroup-class="col-md-4" value="{{$nomeSel}}">
                <x-slot name="label">
                    Nome do Razão <span style="color:red;">*</span>
                </x-slot>
            </x-adminlte-input>
            @endif
        </div>

        <div class="row bloco-nom-fant">
            <x-adminlte-input name="nomeFan" type="text" label="Nome Fantasia" placeholder="Nome Fantasia" fgroup-class="col-md-6" value="{{$nomeFan}}"></x-adminlte-input>
        </div>

        <div class="post titulo-banco">
            <h5 class="text-secondary font-weight-bold">Dados da Conta</h5>
        </div>

        <div class="row bloco-banco">
            @php
                $array_cnc_banco = HelperArraySelect::arrayCncBancos(1,1);
            @endphp

            <!-- Código do CNC/COMPE do Banco -->
            <x-adminlte-select name="codBanco" fgroup-class="col-md-6">
                <x-slot name="label">
                    Código do Banco <span style="color:red;">*</span>
                </x-slot>
                <x-adminlte-options :options="$array_cnc_banco" empty-option="Selecione..." selected="{{$codBancoSel}}"/>
            </x-adminlte-select>
        </div>

        <div class="row bloco-banco">
            <!-- Agência -->
            <x-adminlte-input name="agencia" type="text" fgroup-class="col-md-4" value="{{$ageSel}}">
                <x-slot name="label">
                    Agência <span style="color:red;">*</span>
                </x-slot>
            </x-adminlte-input>

            <!-- Dígito Verificador da Agência -->
            <x-adminlte-input name="agenciaDV" label="Dígito da Agência" type="text" fgroup-class="col-md-2" value="{{$ageDvSel}}"></x-adminlte-input>

            <!-- Conta Corrente -->
            <x-adminlte-input name="conta" type="text" fgroup-class="col-md-4" value="{{$contaSel}}">
                <x-slot name="label">
                    Conta Corrente <span style="color:red;">*</span>
                </x-slot>
            </x-adminlte-input>

            <!-- Dígito Verificador da Conta Corrente -->
            <x-adminlte-input name="contaDV" type="text" fgroup-class="col-md-2" value="{{$contaDvSel}}">
                <x-slot name="label">
                    Dígito da Conta Corrente<span style="color:red;">*</span>
                </x-slot>
            </x-adminlte-input>
        </div>

        <div class="post titulo-cartao">
            <h5 class="text-secondary font-weight-bold">Dados do Cartão</h5>
        </div>

        <div class="bloco-cartao">
            <div class="row">
                @php 
                    $array_opt_adm = HelperArraySelect::arrayAdministradoras(1,1);
                @endphp
                <x-adminlte-select name="codAdmCC" type="text" fgroup-class="col-md-6">
                    <x-slot name="label">
                        Administradora <span style="color:red;">*</span>
                    </x-slot>
                    <x-adminlte-options :options="$array_opt_adm" empty-option="Selecione..." selected="{{ $codAdm }}"/>
                </x-adminlte-select>
            </div>
            <div class="row">
                <x-adminlte-select name="tipoAdm" type="text" fgroup-class="col-md-6">
                    <x-slot name="label">
                        Tipo da Administradora <span style="color:red;">*</span>
                    </x-slot>
                    <x-adminlte-options :options="['B' => 'Banco', 'C' => 'Crédito']" empty-option="Selecione..." selected="{{ $tipoAdm }}"/>
                </x-adminlte-select>

                @php 
                    $array_opt_bco_adm = HelperArraySelect::arrayRazoesPorTipo(1, 1, $empSel, 'BA');
                @endphp
                <x-adminlte-select name="bancoAdm" type="text" fgroup-class="col-md-6">
                    <x-slot name="label">
                        Banco da Administradora <span style="color:red;">*</span>
                    </x-slot>
                    <x-adminlte-options :options="$array_opt_bco_adm" empty-option="Selecione..." selected="{{ $bancoAdm }}"/>
                </x-adminlte-select>
            </div>
        </div>

        <div class="row bloco-cartao-2">
            <x-adminlte-select name="tipoCard" type="text" fgroup-class="col-md-6">
                <x-slot name="label">
                    Tipo do Cartão <span style="color:red;">*</span>
                </x-slot>
                <x-adminlte-options :options="['C' => 'Crédito', 'D' => 'Débito']" empty-option="Selecione..." selected="{{ $tipoCard }}"/>
            </x-adminlte-select>

            @php 
                $array_opt_bandeira = HelperArraySelect::arrayBandeirasCartao(1, 1);
            @endphp
            <x-adminlte-select name="bandeiraCard" type="text" fgroup-class="col-md-6">
                <x-slot name="label">
                    Bandeira <span style="color:red;">*</span>
                </x-slot>
                <x-adminlte-options :options="$array_opt_bandeira" empty-option="Selecione..." selected="{{ $bandCard }}"/>
            </x-adminlte-select>
        </div>

        <div class="row bloco-cartao-3">
            <x-adminlte-input name="diaParcela" placeholder="Dias por Parcela" type="number" fgroup-class="col-md-5" value="{{$qtdDiaPar}}">
                <x-slot name="label">
                    Quantidade de Dias por Parcela <span style="color:red;">*</span>
                </x-slot>
            </x-adminlte-input>

            <x-adminlte-input name="diaVista" placeholder="Dias por Venda À Vista" type="number" fgroup-class="col-md-5" value="{{$qtdDiaVista}}">
                <x-slot name="label">
                    Quantidade de Dias por Venda À Vista <span style="color:red;">*</span>
                </x-slot>
            </x-adminlte-input>

            <x-adminlte-input name="depOn" label="Depósito Online" type="text" fgroup-class="col-md-2" value="{{ $depOn }}" disabled></x-adminlte-input>
        </div>

        <div class="bloco-cartao-corp">
            <div class="row">
                @php 
                    $array_opt_bco_adm = HelperArraySelect::arrayRazoesPorTipo(1, 1, $empSel, 'BA');
                @endphp
                <x-adminlte-select name="bancoAdmCorp" type="text" fgroup-class="col-md-6">
                    <x-slot name="label">
                        Banco da Administradora <span style="color:red;">*</span>
                    </x-slot>
                    <x-adminlte-options :options="$array_opt_bco_adm" empty-option="Selecione..." selected="{{ $bancoAdmCorp }}"/>
                </x-adminlte-select>

                <x-adminlte-input name="depOn" label="Depósito Online" type="text" fgroup-class="col-md-6" value="{{ $depOn }}" disabled></x-adminlte-input>
            </div>
        </div>
        
        <div class="row">
            <div class="bloco-saldo col-md-6">
                <x-adminlte-input name="valIni" type="text" value="" placeholder="0,00" fgroup-class="col-md-12" value="{{$valIniSel}}">
                    <x-slot name="label">
                        Saldo Inicial <span style="color:red;">*</span>
                    </x-slot>
                    <x-slot name="prependSlot">
                        <div class="input-group-text x-slot-nexus">
                            <i class="fa-solid fa-brazilian-real-sign"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
            </div>
            <div class="bloco-data col-md-6">
                @php
                    $config = Helper::dtRangeDataPtBR();
                    
                    if(!empty($dadosRazao->razao_dt_con)){
                        $data_conc = date('d/m/Y', strtotime($dadosRazao->razao_dt_con));
                    }else{
                        $data_conc = '';
                    }
                @endphp
                <x-adminlte-date-range name="dataConc" :config="$config" placeholder="Formato dia/mês/ano" fgroup-class="col-md-12">
                    <x-slot name="label">
                        Data da Primeira Conciliação <span style="color:red;">*</span>
                    </x-slot>    
                    <x-slot name="prependSlot">
                        <div class="input-group-text x-slot-nexus">
                            <i class="far fa-lg fa-calendar-alt"></i>
                        </div>
                    </x-slot>
                </x-adminlte-date-range>
                @push('js')<script>$(() => $("#dataConc").val('{{ $data_conc }}'))</script>@endpush
            </div>
        </div>

        <!-- /.card -->
        <x-slot name="footerSlot">
            <div class="d-flex justify-content-between w-100">
                <div class="d-flex">
                    <x-adminlte-button class="btn-nexus mr-2" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                    @if($tipoCad == 'M')
                    <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('cadastroRazao.create') }}'" label="Novo Razão" theme="" icon="fa-solid fa-plus"/>
                    @endif
                </div>
                <div class="d-flex">
                    <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('cadastroRazao.index') }}'" label="Voltar" theme="" icon=""/>
                </div>
            </div>
        </x-slot>
    </x-adminlte-card>
</form>
@stop

@section('css')
@stop

<!-- Chamada dos Plugins usados na app -->  
@section('plugins.jqueryValidation', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.DateRangePicker', true)

@section('js')
<script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script> 

<!--
|--------------------------------------------------------------------------
| Eventos Iniciais da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() {

        $('#valIni').mask('#.##0,00', {reverse: true});

        var tipoCad = {!! json_encode($tipoCad) !!};

        if(tipoCad == 'N'){

            $(".titulo-banco").hide();
            $(".bloco-banco").hide();
            $(".titulo-cartao").hide();
            $(".bloco-cartao").hide();
            $(".bloco-cartao-corp").hide();
            $(".bloco-cartao-2").hide();
            $(".bloco-cartao-3").hide();
            $(".bloco-sal-data").hide();
            $(".bloco-nom-fant").hide();
            $(".bloco-cod-nom").hide();
            $(".bloco-saldo").hide();
            $(".bloco-data").hide();

        }else{

            $("#empresa").prop('disabled', true);
            $("#tipoRazao").prop('disabled', true);
            $("#valIni").prop('disabled', true);
            $("#codigo").prop('disabled', true);

            var tipoRazSel = {!! json_encode($tipoRazSel) !!};

            if(tipoRazSel == 'BA'){

                $(".titulo-banco").show();
                $(".bloco-banco").show();
                $(".bloco-nom-fant").show();
                $(".bloco-cod-nom").show();
                $(".bloco-saldo").show();
                $(".bloco-data").show();

                $("#codBanco").prop('disabled', true);
                $("#agencia").prop('disabled', true);
                $("#agenciaDV").prop('disabled', true);
                $("#conta").prop('disabled', true);
                $("#contaDV").prop('disabled', true);
                $("#dataConc").prop('disabled', true);

                $(".titulo-cartao").hide();
                $(".bloco-cartao").hide();
                $(".bloco-cartao-2").hide();
                $(".bloco-cartao-3").hide();
                $(".bloco-cartao-corp").hide();

            }else if(tipoRazSel == 'CC'){

                $(".titulo-cartao").show();
                $(".bloco-cartao").show();
                $(".bloco-cartao-2").show();
                $(".bloco-cartao-3").show();
                $(".bloco-cod-nom").show();
                $(".bloco-nom-fant").show();

                $("#tipoAdm").prop('disabled', true);
                $("#tipoCard").prop('disabled', true);
                $("#bandeiraCard").prop('disabled', true);

                $(".titulo-banco").hide();
                $(".bloco-banco").hide();
                $(".bloco-saldo").hide();
                $(".bloco-data").hide();
                $(".bloco-cartao-corp").hide();

            }else if(tipoRazSel == 'CX'){

                $(".bloco-saldo").show();
                $(".bloco-cod-nom").show();

                $(".titulo-cartao").hide();
                $(".bloco-cartao").hide();
                $(".bloco-cartao-2").hide();
                $(".bloco-cartao-3").hide();
                $(".titulo-banco").hide();
                $(".bloco-banco").hide();
                $(".bloco-nom-fant").hide();
                $(".bloco-data").hide();
                $(".bloco-cartao-corp").hide();

            }else if(tipoRazSel == 'TE'){

                $(".bloco-saldo").show();
                $(".bloco-cod-nom").show();

                $(".titulo-cartao").hide();
                $(".bloco-cartao").hide();
                $(".bloco-cartao-2").hide();
                $(".bloco-cartao-3").hide();
                $(".titulo-banco").hide();
                $(".bloco-banco").hide();
                $(".bloco-nom-fant").hide();
                $(".bloco-data").hide();
                $(".bloco-cartao-corp").hide();

            }else if(tipoRazSel == 'CO'){

                $(".titulo-cartao").show();
                $(".bloco-cartao").hide();
                $(".bloco-cartao-2").hide();
                $(".bloco-cartao-3").hide();
                $(".bloco-cod-nom").show();
                $(".bloco-nom-fant").show();

                $(".titulo-banco").hide();
                $(".bloco-banco").hide();
                $(".bloco-saldo").hide();
                $(".bloco-data").hide();
                $(".bloco-cartao-corp").show();

            }
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

        /* *************** Evento ao trocar o tipo do razão exibir/ocultar informações da conta corrente do BA *************** */
        $("#tipoRazao").change(function(){
                
            if(this.value == 'BA'){

                $(".titulo-banco").show();
                $(".bloco-banco").show();
                $(".bloco-nom-fant").show();
                $(".bloco-cod-nom").show();
                $(".bloco-saldo").show();
                $(".bloco-data").show();

                $(".titulo-cartao").hide();
                $(".bloco-cartao").hide();
                $(".bloco-cartao-2").hide();
                $(".bloco-cartao-3").hide();
                $(".bloco-cartao-corp").hide();

                $('#codBanco').val('');
                $('#agencia').val('');
                $('#agenciaDV').val('');
                $('#conta').val('');
                $('#contaDV').val('');

            }else if(this.value == 'CC'){

                $(".titulo-cartao").show();
                $(".bloco-cartao").show();
                $(".bloco-cartao-2").show();
                $(".bloco-cartao-3").show();
                $(".bloco-cod-nom").show();
                $(".bloco-nom-fant").show();

                $(".titulo-banco").hide();
                $(".bloco-banco").hide();
                $(".bloco-saldo").hide();
                $(".bloco-data").hide();
                $(".bloco-cartao-corp").hide();

                $('#codBanco').val('');
                $('#agencia').val('');
                $('#agenciaDV').val('');
                $('#conta').val('');
                $('#contaDV').val('');

            }else if(this.value == 'CX'){

                $(".bloco-saldo").show();
                $(".bloco-cod-nom").show();

                $(".titulo-cartao").hide();
                $(".bloco-cartao").hide();
                $(".bloco-cartao-2").hide();
                $(".bloco-cartao-3").hide();
                $(".titulo-banco").hide();
                $(".bloco-banco").hide();
                $(".bloco-nom-fant").hide();
                $(".bloco-data").hide();
                $(".bloco-cartao-corp").hide();

                $('#codBanco').val('');
                $('#agencia').val('');
                $('#agenciaDV').val('');
                $('#conta').val('');
                $('#contaDV').val('');

            }else if(this.value == 'TE'){
                
                $(".bloco-saldo").show();
                $(".bloco-cod-nom").show();
                
                $(".titulo-cartao").hide();
                $(".bloco-cartao").hide();
                $(".bloco-cartao-2").hide();
                $(".bloco-cartao-3").hide();
                $(".titulo-banco").hide();
                $(".bloco-banco").hide();
                $(".bloco-nom-fant").hide();
                $(".bloco-data").hide();
                $(".bloco-cartao-corp").hide();

                $('#codBanco').val('');
                $('#agencia').val('');
                $('#agenciaDV').val('');
                $('#conta').val('');
                $('#contaDV').val('');

            }else if(this.value == 'CO'){

                $(".titulo-cartao").show();
                $(".bloco-cartao").hide();
                $(".bloco-cartao-corp").show();
                $(".bloco-cartao-2").hide();
                $(".bloco-cartao-3").hide();
                $(".bloco-cod-nom").show();
                $(".bloco-nom-fant").show();

                $(".titulo-banco").hide();
                $(".bloco-banco").hide();
                $(".bloco-saldo").hide();
                $(".bloco-data").hide();

                $('#codBanco').val('');
                $('#agencia').val('');
                $('#agenciaDV').val('');
                $('#conta').val('');
                $('#contaDV').val('');

            }else{
                
                $(".bloco-sal-data").hide();
                $(".titulo-cartao").hide();
                $(".bloco-cartao").hide();
                $(".bloco-cartao-2").hide();
                $(".bloco-cartao-3").hide();
                $(".titulo-banco").hide();
                $(".bloco-banco").hide();
                $(".bloco-nom-fant").hide();
                $(".bloco-cod-nom").hide();
                $(".bloco-sal-data").hide();
                $(".bloco-cartao-corp").hide();

                $('#codBanco').val('');
                $('#agencia').val('');
                $('#agenciaDV').val('');
                $('#conta').val('');
                $('#contaDV').val('');
            }
        });

        //Evento de carregamento ajax dos dados do banco da administradora de cartão
        $('#empresa').change(function(){

            if( $(this).val() ) {
                var razao = 'BA';
                var empresa = $(this).val();

                var url = "{{ route('ajax.carregaRazaoAjax', [':raz',':emp']) }}";
                url = url.replace(':raz', razao);
                url = url.replace(':emp', empresa);

                $.ajax({
                    url: url,
                    dataType: "JSON",
                    type: 'GET',
                    data: {
                        '_token': $('meta[name=csrf-token]').attr("content"),
                        '_method': 'GET',
                        "raz": razao,
                        "emp": empresa
                    },
                    success: function (data)
                    {
                        if(data.razoes_ajax_existe == 'S'){

                            var options = '<option value="">Selecione...</option>';	

                            for (var i = 0; i < data.razoes_ajax.length; i++) {

                                options += '<option value="' + data.razoes_ajax[i].cod + '">' + data.razoes_ajax[i].desc_razao + '</option>';
                            }	
                            $('#bancoAdm').html(options);
                            $('#bancoAdmCorp').html(options);

                        }else{
                            $('#bancoAdm').html('<option value="">Selecione...</option>');
                            $('#bancoAdmCorp').html('<option value="">Selecione...</option>');
                        }
                    }
                });
            } else {
                $('#bancoAdm').html('<option value="">Selecione...</option>');
                $('#bancoAdmCorp').html('<option value="">Selecione...</option>');
            }
        });
    });
</script>

<script>
$(function () {
    $('#formulario-razao').validate({
        rules: {
            empresa: {
                required: true
            },
            tipoRazao: {
                required: true
            },
            nome: {
                required: true,
                maxlength: 60
            },
            nomeFan: {
                maxlength: 10
            },
            codBanco: {
                required: function(element) {
                    let tipoRaz = $('#tipoRazao').val();
                    return tipoRaz === 'BA';
                },
            },
            agencia: {
                required: function(element) {
                    let tipoRaz = $('#tipoRazao').val();
                    return tipoRaz === 'BA';
                },
                maxlength: 5
            },
            agenciaDV: {
                maxlength: 1
            },
            conta: {
                required: function(element) {
                    let tipoRaz = $('#tipoRazao').val();
                    return tipoRaz === 'BA';
                },
                maxlength: 10
            },
            contaDV: {
                required: function(element) {
                    let tipoRaz = $('#tipoRazao').val();
                    return tipoRaz === 'BA';
                },
                maxlength: 1
            },
            valIni: {
                required: function(element) {
                    let tipoRaz = $('#tipoRazao').val();
                    return tipoRaz === 'BA';
                },
                maxlength: 20
            },
            dataConc: {
                required: function(element) {
                    let tipoRaz = $('#tipoRazao').val();
                    return tipoRaz === 'BA';
                },
            },
            tipoAdm: {
                required: function(element) {
                    let tipoRaz = $('#tipoRazao').val();
                    return tipoRaz === 'CC';
                },
            },
            bancoAdm: {
                required: function(element) {
                    let tipoRaz = $('#tipoRazao').val();
                    return tipoRaz === 'CC';
                },
            },
            tipoCard: {
                required: function(element) {
                    let tipoRaz = $('#tipoRazao').val();
                    return tipoRaz === 'CC';
                },
            },
            bandeiraCard: {
                required: function(element) {
                    let tipoRaz = $('#tipoRazao').val();
                    return tipoRaz === 'CC';
                },
            },
            diaParcela: {
                required: function(element) {
                    let tipoRaz = $('#tipoRazao').val();
                    return tipoRaz === 'CC';
                },
            },
            diaVista: {
                required: function(element) {
                    let tipoRaz = $('#tipoRazao').val();
                    return tipoRaz === 'CC';
                },
            },
        },
        messages: {
            empresa: {
                required: "Por Favor informe a Empresa"
            },
            tipoRazao: {
                required: "Por Favor informe o Tipo do Razão"
            },
            nome: {
                required: "Por Favor informe o Nome do Razão",
                maxlength: "Informe no máximo 60 caracteres"
            },
            nomeFan: {
                maxlength: "Informe no máximo 10 caracteres"
            },
            codBanco: {
                required: "Por Favor informe o Código do Banco"
            },
            agencia: {
                required: "Por Favor informe a Agência",
                maxlength: "Informe no máximo 5 dígitos"
            },
            agenciaDV: {
                maxlength: "Informe apenas 1 dígito"
            },
            conta: {
                required: "Por Favor informe a Conta Corrente",
                maxlength: "Informe no máximo 10 dígitos"
            },
            contaDV: {
                required: "Por Favor informe o Dígito da Conta Corrente",
                maxlength: "Informe apenas 1 dígito"
            },
            valIni: {
                required: "Por Favor informe o Saldo Inicial",
                maxlength: "Limite máximo do valor é de 15 digitos"
            },
            dataConc: {
                required: "Por Favor informe a Data da Primeira Conciliação"
            },
            tipoAdm: {
                required: "Por Favor informe o Tipo da Administradora"
            },
            bancoAdm: {
                required: "Por Favor informe o Banco da Administradora"
            },
            tipoCard: {
                required: "Por Favor informe o Tipo do Cartão"
            },
            bandeiraCard: {
                required: "Por Favor informe a Bandeira"
            },
            diaParcela: {
                required: "Por Favor informe a Quantidade de Dias por Parcela"
            },
            diaVista: {
                required: "Por Favor informe a Quantidade de Dias por Venda À Vista"
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

	@if(Session::has('error'))
        Swal.fire({
        confirmButtonColor: "#007bff",
        title: "Erro!!!",
        text: "{{ session('error') }}",
        icon: "error"
    });
    @endif

    @if(Session::has('info'))
        Swal.fire({
            confirmButtonColor: "#007bff",
            title: "Aviso!",
            html: "{!! session('info') !!}",
            icon: "info",
            customClass: {
                icon: "no-before-icon",
            }
        });
    @endif
</script>
@stop
