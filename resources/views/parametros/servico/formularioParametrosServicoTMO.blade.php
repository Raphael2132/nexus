@extends('adminlte::page')

@section('title', 'Tarefas de Mão de Obra')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Parâmetros Gerais</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.parSrvTMO')}}">Tarefas Mão de Obra</a>
            </li>
            @if($acao == 'N')
                <li class="breadcrumb-item active">Cadastro Tarefas Mão de Obra</li>
            @else
                <li class="breadcrumb-item active">Edição Tarefas Mão de Obra</li>
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
        <form method="post" action="{{route('parametrosSrvTMO.insert')}}" id="quickForm" novalidate="novalidate">
        @else
        <form method="post" action="{{route('parametrosSrvTMO.update')}}" id="quickForm" novalidate="novalidate">
        @endif
            @csrf 
            <x-adminlte-card title="Cadastro de Nova Tarefa de Mão de Obra" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
                
                </br>
                <div class="post">
                    <h4 class="text-secondary font-weight-bold">Dados Gerais</h4>
                </div>

                <div class="row"> 
                    @php
                        $data = DB::table('cadastro_empresas')->selectRaw('empresa_codigo, empresa_nome')->orderBy('empresa_codigo', 'asc')->get();

                        $new_array1 =[];
                        $new_array2 =[];

                        foreach ($data as $empresa) {
                            $new_array1[] = $empresa->empresa_codigo;
                            $new_array2[] = $empresa->empresa_codigo.' - '.$empresa->empresa_nome;
                        }
                        $array_opt = array_combine($new_array1, $new_array2);

                        if(!empty($dadosTMO[0]['tmo_emp'])){
                            $emp_sel = $dadosTMO[0]['tmo_emp'];
                        }else{
                            $emp_sel = '';
                        }

                        if(!empty($dadosTMO[0]['tmo_sts'])){
                            $sts_sel = $dadosTMO[0]['tmo_sts'];
                        }else{
                            $sts_sel = 'A';
                        }
                    @endphp
                    <!-- Empresa -->
                    <x-adminlte-select name="empresa" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Empresa <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_opt" empty-option="Selecione..." selected="{{$emp_sel}}"/>
                    </x-adminlte-select>

                    <!-- Status da Tarefa -->
                    <x-adminlte-select name="status" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Status <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="['A' => 'Ativo', 'D' => 'Desativado']" selected="{{$sts_sel}}"/>
                    </x-adminlte-select>
                </div>

                <div class="row"> 
                    @php

                        $data_are = DB::table('parametros_sis_areas')->join('parametros_sis_setores', 'area_codigo', '=', 'setor_area')->orderBy('area_codigo', 'asc')->get();

                        $new_array_are1 =[];
                        $new_array_are2 =[];

                        foreach ($data_are as $area) {
                            $new_array_are1[] = $area->area_codigo;
                            $new_array_are2[] = $area->area_codigo.' - '.$area->area_desc;
                        }
                        $array_opt_are = array_combine($new_array_are1, $new_array_are2);

                        if(!empty($dadosTMO[0]['tmo_are'])){
                            $are_sel = $dadosTMO[0]['tmo_are'];
                        }else{
                            $are_sel = '';
                        }

                        if(!empty($dadosTMO[0]['tmo_set'])){

                            $data_set = DB::table('parametros_sis_setores')->selectRaw('setor_codigo, setor_desc')->orderBy('setor_codigo', 'asc')->get();

                            $new_array_set1 =[];
                            $new_array_set2 =[];

                            foreach ($data_set as $setor) {
                                $new_array_set1[] = $setor->setor_codigo;
                                $new_array_set2[] = $setor->setor_codigo.' - '.$setor->setor_desc;
                            }
                            $array_opt_set = array_combine($new_array_set1, $new_array_set2);

                            $set_sel = $dadosTMO[0]['tmo_set'];
                        }else{
                            $set_sel = '';
                            $array_opt_set = null;
                        }
                    @endphp
                    <!-- Area -->
                    <x-adminlte-select name="area" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Área <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_opt_are" empty-option="Selecione..." selected="{{$are_sel}}"/>
                    </x-adminlte-select>

                    <!-- Setor -->
                    <x-adminlte-select name="setor" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Setor <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_opt_set" empty-option="Selecione..." selected="{{$set_sel}}"/>
                    </x-adminlte-select>
                </div>

                <div class="row">
                    @php
                        if(!empty($dadosTMO[0]['tmo_cod'])){
                            $codigo_sel = $dadosTMO[0]['tmo_cod'];
                        }else{
                            $codigo_sel = '';
                        }

                        if(!empty($dadosTMO[0]['tmo_are']) && !empty($dadosTMO[0]['tmo_set'])){

                            if(!empty($dadosTMO[0]['tmo_res'])){
                                $prestador_nom = DB::table('cadastro_prestadores')->select('prestador_nome')->where('prestador_codigo', $dadosTMO[0]['tmo_res'])->get();
                                $prestResp_sel = $dadosTMO[0]['tmo_res'].' - '.$prestador_nom[0]->prestador_nome;
                            }else{
                                $prestResp_sel = '';
                            }

                            //Faz o lookup do campo de fornecedores 
                            $data_pres = DB::table('cadastro_prestadores')->where('prestador_set',$dadosTMO[0]['tmo_set'])->where('prestador_are',$dadosTMO[0]['tmo_are'])->where('prestador_status','A')->orderBy('prestador_codigo', 'asc')->get();
                            $html = '<datalist id="prestadores">';
                            foreach($data_pres as $prestador){
                                $html .= '<option value="'.$prestador->prestador_codigo.' - '.$prestador->prestador_nome.'">'.$prestador->prestador_codigo.' - '.$prestador->prestador_nome.'</option>';
                            }
                            $html .='</datalist>';
                            //Echo adiciona o html ao campo dos fornecedores
                            echo $html;
                        }else{
                            $prestResp_sel = '';
                        }
                    @endphp
                    <!-- Código -->
                    <x-adminlte-input name="codigo" type="text" value="{{$codigo_sel}}" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Código da TMO <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>
                    <!-- Prestador responsavel da TMO -->
                    <x-adminlte-input name="prestResp" label="Responsável da TMO" type="search" list="prestadores" autocomplete="off" value="{{$prestResp_sel}}" fgroup-class="col-md-6"/>
                </div>

                <div class="row">
                    @php
                        if(!empty($dadosTMO[0]['tmo_dsc'])){
                            $desc_sel = $dadosTMO[0]['tmo_dsc'];
                        }else{
                            $desc_sel = '';
                        }

                        if(!empty($dadosTMO[0]['tmo_cmp'])){
                            $cmp_sel = $dadosTMO[0]['tmo_cmp'];
                        }else{
                            $cmp_sel = '';
                        }
                    @endphp
                    <!-- Descrição -->
                    <x-adminlte-input name="descricao" type="text" value="{{$desc_sel}}" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Descrição da TMO <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>
                    <!-- Descrição -->
                    <x-adminlte-input name="complemento" label="Complemento" type="text" value="{{$cmp_sel}}" fgroup-class="col-md-6"/>
                </div>

                </br>
                
                <div class="post">
                    <h4 class="text-secondary font-weight-bold">Valores</h4>
                </div>

                <div class="row"> 
                    @php
                        if(!empty($dadosTMO[0]['tmo_tip'])){
                            $tipTMO_sel = $dadosTMO[0]['tmo_tip'];
                        }else{
                            $tipTMO_sel = 'P';
                        }
                    @endphp
                    <!-- Tipo da TMO -->
                    <x-adminlte-select name="tipoTMO" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Tipo da TMO <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="['P' => 'Padrão', 'I' => 'Hora / Valor Informada', 'R' => 'Hora Real', 'F' => 'Hora Fixa', 'T' => 'Terceiros']" selected="{{$tipTMO_sel}}"/>
                    </x-adminlte-select>
                </div>

                <div class="row"> 
                    @php
                        if(!empty($dadosTMO[0]['tmo_qtd_hr'])){
                            $qtdHr_sel = $dadosTMO[0]['tmo_qtd_hr'];
                        }else{
                            $qtdHr_sel = '';
                        }

                        if(!empty($dadosTMO[0]['tmo_val_tot'])){
                            $totTMO_sel = $dadosTMO[0]['tmo_val_tot'];
                        }else{
                            $totTMO_sel = '';
                        }

                        if(!empty($dadosTMO[0]['tmo_val_hr'])){
                            $valHr_sel = $dadosTMO[0]['tmo_val_hr'];
                        }else{
                            $valHr_sel = '';
                        }
                    @endphp
                    <!-- Quantidade de horas da tarefa -->
                    <x-adminlte-input name="qtdHora" type="text" value="{{$qtdHr_sel}}" placeholder="0.00" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Quantidade de Horas <span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-clock-rotate-left fa-flip-horizontal"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <!-- Valor da hora da tarefa -->
                    <x-adminlte-input name="valHora" type="text" value="{{$valHr_sel}}" placeholder="0,00" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Valor da Hora <span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-brazilian-real-sign"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <!-- Valor total da tarefa -->
                    <x-adminlte-input name="valTot" type="text" value="{{$totTMO_sel}}" placeholder="0,00" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Valor Total da TMO <span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-brazilian-real-sign"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>

                <div class="bloco-terceiros">
                    </br>
                    <div class="post">
                        <h4 class="text-secondary font-weight-bold">Fornecedor do Serviço de Terceiros</h4>
                    </div>

                    <div class="row"> 
                        @php
                            if(!empty($dadosTMO[0]['tmo_for_cgt'])){

                                $dadosCli = DB::table('cadastro_clientes')->where('cliente_tipo_cadastro','F')->where('cliente_codigo',$dadosTMO[0]['tmo_for_cgt'])->orderBy('cliente_codigo', 'asc')->get();

                                $fornecedor_sel = $dadosTMO[0]['tmo_for_cgt'].' - '.$dadosCli[0]->cliente_nome;
                            }else{
                                $fornecedor_sel = '';
                            }

                            $data_cli = DB::table('cadastro_clientes')->selectRaw('cliente_codigo, cliente_nome')->where('cliente_tipo_cadastro','F')->orderBy('cliente_codigo', 'asc')->get();
                            $html = '<datalist id="fornecedores">';
                            foreach($data_cli as $cliente){
                                $html .= '<option value="'.$cliente->cliente_codigo.' - '.$cliente->cliente_nome.'">'.$cliente->cliente_codigo.' - '.$cliente->cliente_nome.'</option>';
                            }
                            $html .='</datalist>';
                            echo $html;
                        @endphp
                        <!-- Fornecedor do serviço de terceiros -->
                        <x-adminlte-input name="fornecedor" label="Fornecedor do Custo de Terceiros" type="search" list="fornecedores" autocomplete="off" value="{{$fornecedor_sel}}" fgroup-class="col-md-12"/>
                    </div>
                </div>
                                    
                </br>
                <div class="post">
                    <h4 class="text-secondary font-weight-bold">Custo Gerencial da Tarefa</h4>
                </div>

                <div class="row"> 
                    @php
                        if(!empty($dadosTMO[0]['tmo_tip_val_cgt'])){
                            $tipValCgt_sel = $dadosTMO[0]['tmo_tip_val_cgt'];
                        }else{
                            $tipValCgt_sel = '1';
                        }
                    @endphp
                    <!-- Tipo do Valor do custo gerencial de terceiros -->
                    <x-adminlte-select name="tipValCGT" label="Tipo do Custo" fgroup-class="col-md-3">
                        <x-adminlte-options :options="['1' => 'Valor', '2' => 'Percentual']" selected="{{$tipValCgt_sel}}"/>
                    </x-adminlte-select>
                </div>

                <div class="row"> 
                    @php
                        if(!empty($dadosTMO[0]['tmo_per_cgt'])){
                            $perCGT_sel = $dadosTMO[0]['tmo_per_cgt'];
                        }else{
                            $perCGT_sel = '';
                        }

                        if(!empty($dadosTMO[0]['tmo_val_cgt'])){
                            $valCGT_sel = $dadosTMO[0]['tmo_val_cgt'];
                        }else{
                            $valCGT_sel = '';
                        }
                    @endphp
                    <!--Valor do custo gerencial de terceiros -->
                    <x-adminlte-input name="valCGT" label="Valor do Custo" type="text" value="{{$valCGT_sel}}" placeholder="0,00" fgroup-class="col-md-6">
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-brazilian-real-sign"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                    <!-- Porcentagem do custo de terceiros -->
                    <x-adminlte-input name="perCGT" label="Porcentagem do Custo" type="text" value="{{$perCGT_sel}}" placeholder="0,00" fgroup-class="col-md-6">
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-percent"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>                                

                <!-- /.card -->
                <x-slot name="footerSlot">
                    @php
                        if(!empty($dadosTMO[0])){
                            $tarefa = $dadosTMO[0]->tmo_id;
                        }else{
                            $tarefa = '';
                        }
                    @endphp
                    <div class="d-flex justify-content-between w-100">
                        <div class="d-flex">
                            <x-adminlte-button class="btn-nexus mr-2 btn_novo" type="button" onclick="window.location='{{ route('parametrosSrvTMO.cadastro') }}'" label="Nova Tarefa" theme="" icon="fa-solid fa-plus"/>
                            <x-adminlte-button class="btn-nexus mr-2 btn_salvar" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                            <x-adminlte-button class="btn-nexus mr-2 btn_excluir" type="button" data-id="{{$tarefa}}" data-token="{{ csrf_token() }}" label="Excluir" theme="" icon="fa-solid fa-trash"/>
                        </div>
                        <div class="d-flex">
                            <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.parSrvTMO') }}'" label="Voltar" theme="" icon=""/>
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
    $(document).ready(function() {

        //Mascaras de campos float
        $('#qtdHora').mask('#.##0.00', {reverse: true});
        $('#valHora').mask('#.##0,00', {reverse: true});
        $('#valTot').mask('#.##0,00', {reverse: true});
        $('#perCGT').mask('#.##0,00', {reverse: true});
        $('#valCGT').mask('#.##0,00', {reverse: true});
        
        //Verifica de onde veio a app se foi do cadastro ou edição
        var acao = {!! json_encode($acao) !!};

        if(acao == 'E'){
            $("#setor").attr("disabled", true);
            $("#empresa").attr("disabled", true);
            $("#codigo").attr("disabled", true);
            $("#area").attr("disabled", true);
        }else{
            $(".btn_novo").hide();
            $(".btn_excluir").hide();
        }
            
        //Ao carregar a app verifica o tipo do valor da tarefa
        if($("#tipoTMO").val() == 'P'){
            $("#valTot").prop('disabled', true);
            $("#qtdHora").prop('disabled', false);
            $("#valHora").prop('disabled', false);
            $(".bloco-terceiros").hide();
        }else if($("#tipoTMO").val() == 'I'){
            $("#valTot").prop('disabled', true);
            $("#qtdHora").prop('disabled', false);
            $("#valHora").prop('disabled', false);
            $(".bloco-terceiros").hide();
        }else if($("#tipoTMO").val() == 'R'){
            $("#valTot").prop('disabled', true);
            $("#qtdHora").prop('disabled', true);
            $("#valHora").prop('disabled', false);
            $(".bloco-terceiros").hide();
        }else if($("#tipoTMO").val() == 'T'){
            $("#valTot").prop('disabled', false);
            $("#valHora").prop('disabled', true);
            $("#qtdHora").prop('disabled', true);
            $(".bloco-terceiros").show();
        }else{
            $("#valTot").prop('disabled', false);
            $("#valHora").prop('disabled', true);
            $("#qtdHora").prop('disabled', false);
            $(".bloco-terceiros").hide();
        }
           
        //Ao carregar a app verifica o tipo do custo
        if($("#tipValCGT").val() == '1'){
            $("#valCGT").prop('disabled', false);
            $("#perCGT").hide();
            $('label[for="perCGT"]').hide();
            $("#perCGT").closest('.input-group').find('.x-slot-nexus').hide();
        }else{
            $("#valCGT").prop('disabled', true);
            $("#perCGT").show();
            $('label[for="perCGT"]').show();
            $("#perCGT").closest('.input-group').find('.x-slot-nexus').show();
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
        $("#tipoTMO").change(function(){
            
            if(this.value == 'P'){
                $("#valTot").prop('disabled', true);
                $("#qtdHora").prop('disabled', false);
                $("#valHora").prop('disabled', false);
                $("#qtdHora").val('');
                $("#valHora").val('');
                $("#valTot").val('');
                $(".bloco-terceiros").hide();
                $("#fornecedor").val('');
            }else if(this.value == 'I'){
                $("#valTot").prop('disabled', true);
                $("#qtdHora").prop('disabled', false);
                $("#valHora").prop('disabled', false);
                $("#qtdHora").val('');
                $("#valHora").val('');
                $("#valTot").val('');
                $(".bloco-terceiros").hide();
                $("#fornecedor").val('');
            }else if(this.value == 'R'){
                $("#valTot").prop('disabled', true);
                $("#qtdHora").prop('disabled', true);
                $("#valHora").prop('disabled', false);
                $("#qtdHora").val('0.00');
                $("#valHora").val('');
                $("#valTot").val('0,00');
                $(".bloco-terceiros").hide();
                $("#fornecedor").val('');
            }else if(this.value == 'T'){
                $("#valTot").prop('disabled', false);
                $("#valHora").prop('disabled', true);
                $("#qtdHora").prop('disabled', true);
                $("#qtdHora").val('0.00');
                $("#valHora").val('0,00');
                $("#valTot").val('');
                $(".bloco-terceiros").show();
                $("#fornecedor").val('');
            }else{
                $("#valTot").prop('disabled', false);
                $("#valHora").prop('disabled', true);
                $("#qtdHora").prop('disabled', false);
                $("#qtdHora").val('');
                $("#valHora").val('');
                $("#valTot").val('');
                $(".bloco-terceiros").hide();
                $("#fornecedor").val('');
            }
        });

        //Evento ao trocar o valor do campo tipo do custo da tarefa
        $("#tipValCGT").change(function(){
            
            if(this.value == '1'){
                $("#valCGT").prop('disabled', false);
                $("#valCGT").val('');
                $("#perCGT").val('0.00');
                $("#perCGT").hide();
                $('label[for="perCGT"]').hide();
                $("#perCGT").closest('.input-group').find('.x-slot-nexus').hide();
            }else{
                $("#valCGT").prop('disabled', true);
                $("#valCGT").val('');
                $("#perCGT").val('');
                $("#perCGT").show();
                $('label[for="perCGT"]').show();
                $("#perCGT").closest('.input-group').find('.x-slot-nexus').show();
            }
        });

        //Evento ao trocar o valor do campo tipo do custo da tarefa
        $("#valHora").change(function(){
            
            if($("#tipoTMO").val() == 'P'){
                
                var qtdHr = $("#qtdHora").val();
                var valHr = this.value;

                valHr = valHr.replaceAll('.', '');
                valHr = valHr.replaceAll(',', '.');

                var valTot = qtdHr * valHr;
                valTot = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valTot);

                $("#valTot").val(valTot);

            }else if($("#tipoTMO").val() == 'I' && $("#qtdHora").val() != ''){
                
                var qtdHr = $("#qtdHora").val();
                var valHr = this.value;

                valHr = valHr.replaceAll('.', '');
                valHr = valHr.replaceAll(',', '.');

                var valTot = qtdHr * valHr;
                valTot = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valTot);

                $("#valTot").val(valTot);

            }
        });

        //Evento ao trocar o valor do campo tipo do custo da tarefa
        $("#valTot").change(function(){
            
            if($("#tipoTMO").val() == 'F' && $("#qtdHora").val() != ''){
                
                var qtdHr = $("#qtdHora").val();
                var valTot = this.value;

                valTot = valTot.replaceAll('.', '');
                valTot = valTot.replaceAll(',', '.');

                var valHr = valTot/qtdHr;
                valHr = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valHr);

                $("#valHora").val(valHr);

            }else if($("#tipoTMO").val() == 'I' && $("#qtdHora").val() != ''){
                
                var qtdHr = $("#qtdHora").val();
                var valHr = this.value;

                valHr = valHr.replaceAll('.', '');
                valHr = valHr.replaceAll(',', '.');

                var valTot = qtdHr * valHr;
                valTot = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valTot);

                $("#valTot").val(valTot);

            }
        });

        $("#qtdHora").change(function(){
            
            if(($("#tipoTMO").val() == 'I' || $("#tipoTMO").val() == 'P')  && $("#valHora").val() != ''){
                
                var valHr = $("#valHora").val();
                var qtdHr = this.value;

                valHr = valHr.replaceAll('.', '');
                valHr = valHr.replaceAll(',', '.');

                var valTot = valHr*qtdHr;
                valTot = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valTot);

                $("#valTot").val(valTot);

            }else if($("#tipoTMO").val() == 'F' && $("#valTot").val() != ''){
                
                var valTot = $("#valTot").val();
                var qtdHr = this.value;

                valTot = valTot.replaceAll('.', '');
                valTot = valTot.replaceAll(',', '.');

                var valHr = valTot / qtdHr;
                valHr = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valHr);

                $("#valHora").val(valHr);

            }
        });

        $("#perCGT").change(function(){
            
            if($("#valTot").val() != ''){
                
                var valTot = $("#valTot").val();
                var perCGT = this.value;

                perCGT = perCGT.replaceAll('.', '');
                perCGT = perCGT.replaceAll(',', '.');

                valTot = valTot.replaceAll('.', '');
                valTot = valTot.replaceAll(',', '.');

                var valCGT = (valTot / 100) * perCGT;
                valCGT = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valCGT);

                $("#valCGT").val(valCGT);

            }
        });

        //Evento de carregamento ajax dos dados dos códigos do serviço do grupo selecionado
        $('#grpSrv').change(function(){

            if( $(this).val() ) {
                var id = $(this).val();

                var url = "{{ route('parametrosSrvTMO.carregaCodSrvAjax', [':id']) }}";
                url = url.replace(':id', id);

                $.ajax({
                    url: url,
                    dataType: "JSON",
                    type: 'GET',
                    data: {
                        '_token': $('meta[name=csrf-token]').attr("content"),
                        '_method': 'GET',
                        "id": id
                    },
                    success: function (data)
                    {
                        var options = '<option value="">Selecione...</option>';	

						for (var i = 0; i < data.servicos_ajax.length; i++) {

							options += '<option value="' + data.servicos_ajax[i].id + '">' + data.servicos_ajax[i].cod_servico + '</option>';
						}	
						$('#codSrv').html(options);
                    }
                });
            } else {
				$('#codSrv').html('<option value="">Selecione...</option>');
			}
        });

        //Evento de carregamento ajax dos dados dos setores
        $('#area').change(function(){

            if( $(this).val() && $('#empresa').val() != '' ) {
                var are = $(this).val();
                var emp = $('#empresa').val();

                var url = "{{ route('parametrosSrvTMO.carregaSetAjax', [':are',':emp']) }}";
                url = url.replace(':are', are);
                url = url.replace(':emp', emp);

                $.ajax({
                    url: url,
                    dataType: "JSON",
                    type: 'GET',
                    data: {
                        '_token': $('meta[name=csrf-token]').attr("content"),
                        '_method': 'GET',
                        "are": are,
                        "emp": emp
                    },
                    success: function (data)
                    {
                        if(data.setores_ajax_existe == 'S'){

                            var options = '<option value="">Selecione...</option>';	

                            for (var i = 0; i < data.setores_ajax.length; i++) {

                                options += '<option value="' + data.setores_ajax[i].id + '">' + data.setores_ajax[i].cod_setor + '</option>';
                            }	
                            $('#setor').html(options);

                        }else{
                            $('#setor').html('<option value="">Selecione...</option>');
                        }
                    }
                });
                $('#prestResp').val('');
                $('#prestResp').html('<datalist id="prestadores"></datalist>');
            } else {
                $('#setor').html('<option value="">Selecione...</option>');
                $('#prestResp').val('');
                $('#prestResp').html('<datalist id="prestadores"></datalist>');
            }
        });

        //Evento de carregamento ajax dos dados dos setores
        $('#empresa').change(function(){

            if( $(this).val() && $('#area').val() != '' ) {
                var are = $('#area').val();
                var emp = $(this).val();

                var url = "{{ route('parametrosSrvTMO.carregaSetAjax', [':are',':emp']) }}";
                url = url.replace(':are', are);
                url = url.replace(':emp', emp);

                $.ajax({
                    url: url,
                    dataType: "JSON",
                    type: 'GET',
                    data: {
                        '_token': $('meta[name=csrf-token]').attr("content"),
                        '_method': 'GET',
                        "are": are,
                        "emp": emp
                    },
                    success: function (data)
                    {
                        if(data.setores_ajax_existe == 'S'){

                            var options = '<option value="">Selecione...</option>';	

                            for (var i = 0; i < data.setores_ajax.length; i++) {

                                options += '<option value="' + data.setores_ajax[i].id + '">' + data.setores_ajax[i].cod_setor + '</option>';
                            }	

                            $('#setor').html(options);
                            $('#prestResp').val('');
                            $('#prestResp').html('<datalist id="prestadores"></datalist>');

                        }else{
                            $('#setor').html('<option value="">Selecione...</option>');
                            $('#prestResp').val('');
                            $('#prestResp').html('<datalist id="prestadores"></datalist>');
                        }
                    }
                });
            } else {
                $('#setor').html('<option value="">Selecione...</option>');
                $('#prestResp').val('');
                $('#prestResp').html('<datalist id="prestadores"></datalist>');
            }
        });

        //Evento de carregamento ajax dos dados dos setores
        $('#setor').change(function(){

            if( $(this).val() ) {
                var set = $(this).val();
                var emp = $('#empresa').val();
                var are = $('#area').val();

                var url = "{{ route('parametrosSrvTMO.carregaRespAjax', [':are',':set',':emp']) }}";
                url = url.replace(':are', are);
                url = url.replace(':set', set);
                url = url.replace(':emp', emp);

                $.ajax({
                    url: url,
                    dataType: "JSON",
                    type: 'GET',
                    data: {
                        '_token': $('meta[name=csrf-token]').attr("content"),
                        '_method': 'GET',
                        "are": are,
                        "set": set,
                        "emp": emp
                    },
                    success: function (data)
                    {
                        if(data.prestadores_ajax_existe == 'S'){
                            var options = '<datalist id="prestadores">';	

                            for (var i = 0; i < data.prestadores_ajax.length; i++) {

                                options += '<option value="' + data.prestadores_ajax[i].id + '">' + data.prestadores_ajax[i].id + '</option>';
                            }	

                            options += '</datalist>';

                            $('#prestResp').html(options);
                            $('#prestResp').val('');
                        }else{
                            $('#prestResp').val('');
                            $('#prestResp').html('<datalist id="prestadores"></datalist>');
                        }
                    }
                });
            } else {
                $('#prestResp').val('');
                $('#prestResp').html('<datalist id="prestadores"></datalist>');
            }
        });
    });
</script>

<!--
|--------------------------------------------------------------------------
| Eventos onClick da app
|--------------------------------------------------------------------------
-->

<script>
    $(document).ready(function() {

        $(".btn_excluir").click(function(){
            var id = $(this).attr("data-id");

            var url = "{{ route('parametrosSrvTMO.destroy', [':id', 'ajax']) }}";
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
                    window.location = "{{ route('parametrosSrvTMO.homeAjax') }}";
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
    jQuery.validator.addMethod("maxpercent", function(value, element) {
        return this.optional(element) || /^(\d{1,2}|\d{1,2}\,\d{1,2}|100\,[0]{1,2}|100)$/i.test(value);
    }, "Porcentagem máxima de 100,00 %");

    jQuery.validator.addMethod("maxqtdhr", function(value, element) {
        return this.optional(element) || /^(\d{1,3}|\d{1,3}\.\d{1,3}|999\.[0]{1,2}|999)$/i.test(value);
    }, "Quantidade de horas máxima de 999.99");

    $('#quickForm').validate({
        rules: {
            empresa: {
                required: true
            },
            setor: {
                required: true
            },
            area: {
                required: true
            },
            descricao: {
                required: true,
                maxlength: 40
            }, 
            complemento: {
                maxlength: 40
            },
            codigo: {
                required: true,
                maxlength: 15
            }, 
            perCGT: {
                maxpercent: true
            }, 
            qtdHora: {
                required: function(element) {
                    let tipoTMO = $('#tipoTMO').val();
                    return tipoTMO === 'P' || tipoTMO === 'I' || tipoTMO === 'F';
                },
                maxqtdhr: true
            }, 
            grpSrv: {
                required: true
            },
            codSrv: {
                required: true
            },
            valHora: {
                required: function(element) {
                    let tipoTMO = $('#tipoTMO').val();
                    return tipoTMO === 'P' || tipoTMO === 'I' || tipoTMO === 'R';
                },
                maxlength: 20
            },
            valTot: {
                required: function(element) {
                    let tipoTMO = $('#tipoTMO').val();
                    return tipoTMO === 'F' || tipoTMO === 'T';
                },
                maxlength: 20
            },
            valCGT: {
                maxlength: 20
            },
        },
        messages: {
            empresa: {
                required: "Por Favor informe uma Empresa"
            },
            setor: {
                required: "Por Favor informe um Setor"
            },
            area: {
                required: "Por Favor informe uma Área"
            },
            descricao: {
                required: "Por Favor informe a Descrição",
                maxlength: "Infome no máximo 40 caracteres"
            },
            complemento: {
                maxlength: "Infome no máximo 80 caracteres"
            },
            codigo: {
                required: "Por Favor informe o Código",
                maxlength: "Infome no máximo 15 caracteres"
            },
            grpSrv: {
                required: "Por Favor informe um Grupo de Serviço"
            },
            codSrv: {
                required: "Por Favor informe um Código do Serviço"
            },
            valHora: {
                required: "O valor da hora é obrigatório para os tipos Padrão, Informada e Real.",
                maxlength: "Limite máximo do valor é de 15 digitos"
            },
            valTot: {
                required: "O valor totala é obrigatório para os tipos Fixo e Terceiros.",
                maxlength: "Limite máximo do valor é de 15 digitos"
            },
            valCGT: {
                maxlength: "Limite máximo do valor é de 15 digitos"
            },
            qtdHora: {
                required: "A quantidade de horas é obrigatória para os tipos Padrão, Informada e Fixa."
            }
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
