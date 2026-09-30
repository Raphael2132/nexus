@extends('adminlte::page')

@section('title', 'Conta Corrente')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Pagamento de Conta Corrente</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                @if($appOrigem == 'LOTE')
                <a href="{{route('home.homePCCLote',['appOrigem' => $appOrigem])}}">Lote</a>
                @else
                <a href="{{route('home.homePCCLote',['appOrigem' => $appOrigem])}}">Adiantamento de Fornecedor</a>
                @endif
            </li>
            <li class="breadcrumb-item active">
                <a href="{{route('pagamentoPCC.selecaoCCLote',['appOrigem' => $appOrigem])}}">Seleção de Contas</a>
            </li>
            <li class="breadcrumb-item active">Painel de Pagamento</li>
        </ol>
    </div>
</div>
@stop


@section('content')
<div class="col-md-12">

    <!-- ********** Painel Principal da Emissão de NF ********** -->
    <x-adminlte-card title="Painel de Pagamento {{ $appOrigem === 'LOTE' ? ' em Lote' : ' do Adiantamento de Fornecedor' }}" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
        @php 
            $dadosCli = null;

            if (strlen($clienteLote) == 10) {

                // CLIENTE / FORNECEDOR
                $dadosCli = DB::table('cadastro_clientes')
                    ->where('cliente_codigo', $clienteLote)
                    ->first();

                $nomeCli = $dadosCli->cliente_nome;

            } elseif (strlen($clienteLote) == 6) {

                // RAZÃO (CO)
                $dadosCli = DB::table('financeiro_razoes')
                    ->where('razao_codigo', $clienteLote)
                    ->where('razao_ano', date('Y'))
                    ->first();

                $nomeCli = $dadosCli->razao_nome;
            }
            $dadosUsuFin = DB::table('financeiro_tab_usuarios')->where('tabusu_usuario', Auth::user()->usuario_codigo)->where('tabusu_empresa', Auth::user()->usuario_empresa)->first();
            $dadosRaz = DB::table('financeiro_razoes')->where('razao_codigo', $dadosUsuFin->tabusu_razao)->where('razao_empresa', $dadosUsuFin->tabusu_empresa)->where('razao_tipo', $dadosUsuFin->tabusu_tipo_razao)->first();

            $cntPagCO = DB::table('financeiro_pagamento_contas_correntes')
            ->where('pagcct_emp', $empresaLote)
            ->where('pagcct_cod_pag', $idPagamento)
            ->whereIn('pagcct_tip_cct', ['DV','AC','CO','VU'])
            ->count();
        @endphp
        <div class="row">
            <div style="width:100%; margin: 0 10px 10px 10px; color:#fff">
                <table style="width:100%; font-style: normal; border-collapse: separate; border-spacing: 5px 5px;">
                    <tbody>
                        <tr style="text-align: center;">
                            <td style="background-color: #00abab; border-radius: 8px; border: 1px solid #008f8f;"><strong>Usuário</strong></td>
                            <td style="background-color: #00abab; border-radius: 8px; border: 1px solid #008f8f;"><strong>Caixa Operacional</strong></td>
                            <td style="background-color: #00abab; border-radius: 8px; border: 1px solid #008f8f;"><strong>Beneficiário</strong></td>
                        </tr>
                        <tr style="border: 1px solid #008f8f; background-color: #fff; color:#008f8f; text-align: center;">
                            <td style="border: 1px solid #008f8f; border-radius: 8px; text-align: center;">{{ Auth::user()->usuario_codigo.' - '.Auth::user()->name }}</td>
                            <td style="border: 1px solid #008f8f; border-radius: 8px; text-align: center;">{{ $dadosUsuFin->tabusu_razao.' - '.$dadosRaz->razao_nome }}</td>
                            <td style="border: 1px solid #008f8f; border-radius: 8px; text-align: center;">{{ $clienteLote.' - '.$nomeCli }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Posiciona os blocos do lado esquerdo e direito na mesma linha -->
        <div class="row">

            <!-- ************************************************** Bloco do Lado Esquerdo do Painel Principal ************************************************** -->
            <div class="col-md-6">
                <!-- ***** Início dos dados de Pagamento - Bloco: PAINEL_PRINCIPAL ***** -->
                @if($estagio_app == 'PAINEL_PRINCIPAL' || $estagio_app == 'PAINEL_PAGAMENTO' || $estagio_app == 'PAG_DINHEIRO' || 
                    $estagio_app == 'PAG_PIX' || $estagio_app == 'PAG_CHEQUE' || $estagio_app == 'PAG_FIL_C_CORRENTE' || 
                    $estagio_app == 'PAG_SEL_C_CORRENTE' || $estagio_app == 'PAG_CONTA_CORRENTE' || $estagio_app == 'PAG_C_CORPORATIVO')
                <x-adminlte-card title="Contas do Pagamento" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
                    @php
                        // Monta os dados da tabela do bloco
                        $heads = [
                            ['label' => '', 'no-export' => true, 'width' => 10],
                            'Tipo',
                            'Conta',
                            'Vencimento',
                            'Valor da Conta',
                            'Valor a Pagar'
                        ];
                    @endphp
                    <x-adminlte-datatable id="tabela-cc-lote" :heads="$heads" theme="light" striped hoverable>
                        @foreach($dadosContasLote as $conta)
                            @php
                                $tipoConta = DB::table('financeiro_tab_contas')->where('tabcon_codigo', $conta->pagcct_tip_cct)->first();
                            @endphp
                            <tr>
                                <td>
                                    @if($estagio_app == 'PAINEL_PRINCIPAL')
                                    <nobr class="d-flex justify-content-center">
                                        <form method="get" action="{{ route('pagamentoPCC.loteEditarCC',['appOrigem' => $appOrigem, 'numCC' => $conta->pagcct_num_cct, 'tipoCC' => $conta->pagcct_tip_cct]) }}" style="float: left;">
                                            @csrf
                                            <button class="btn btn-xs btn-outline-primary mx-1" title="Editar Registro" value="Edit" type="submit">
                                                <i class="fa fa-lg fa-fw fa-pen"></i>
                                            </button>
                                        </form>
                                        <form method="get" action="{{ route('pagamentoPCC.loteDeletarCC',['appOrigem' => $appOrigem, 'numCC' => $conta->pagcct_num_cct, 'tipoCC' => $conta->pagcct_tip_cct]) }}" style="float: left;">
                                            @csrf
                                            <button class="btn btn-xs btn-outline-danger mx-1" title="Excluir Registro" value="Delete" type="submit" >
                                                <i class="fa fa-lg fa-fw fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </nobr>
                                    @endif
                                </td>
                                <td>{{ $conta->pagcct_tip_cct.' - '.$tipoConta->tabcon_nome }}</td>
                                <td>{{ $conta->pagcct_num_cct }}</td>
                                <td>{{ Helper::formataData($conta->pagcct_dtv) }}</td>
                                <td>{{ Helper::formataValorMonetario($conta->pagcct_vlr_cct) }}</td>
                                <td>{{ Helper::formataValorMonetario($conta->pagcct_vlr_tot) }}</td>
                            </tr>
                        @endforeach
                    </x-adminlte-datatable>
                    <x-slot name="footerSlot">
                        @if($estagio_app == 'PAINEL_PRINCIPAL')
                            <div style="float: right;">
                                <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('pagamentoPCC.pagarLote',['appOrigem' => $appOrigem]) }}'" label="Pagamento" theme="" icon="fa-solid fa-sack-dollar"/>
                            </div>
                        @else
                            <div style="float: right;">
                                <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('pagamentoPCC.reabreLote',['appOrigem' => $appOrigem]) }}'" label="Reabrir Lote" theme="" icon="fa-solid fa-folder-open"/>
                            </div>
                        @endif
                    </x-slot>
                </x-adminlte-card>
                @endif
                <!-- ***** Final dos dados de Pagamento - Bloco: PAINEL_PRINCIPAL ***** -->     
                 
                <!-- ***** Edição da Conta Corrente - Bloco: EDITAR_CONTA ***** -->
                @if($estagio_app == 'EDITAR_CONTA')
                <form method="post" action="{{ route('pagamentoPCC.loteSalvarEdicaoCC',['appOrigem' => $appOrigem]) }}" id="form-edit-cc" novalidate="novalidate">
                    @csrf 
                    @method('post')
                    <x-adminlte-card title="Dados da Conta Corrente Selecionada" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
                        @php 
                            $dadosTipCCT = DB::table('financeiro_tab_contas')->where('tabcon_codigo', $dadosCC->conta_tipo)->first();
                            $dadosEmp = HelperDataSelect::buscaDadosEmpresa($empresaLote);
                            $valorCC = ($dadosCCLote->pagcct_vlr_pag + $dadosCCLote->pagcct_vlr_acr) - $dadosCCLote->pagcct_vlr_des;
                            $tipoConta = $dadosCC->conta_tipo;
                            $saldoCC = $dadosCC->conta_valor - $dadosCC->conta_val_rec;

                            if($tipoConta == 'GD' || $tipoConta == 'NF' || $tipoConta == 'NP' || $tipoConta == 'VU' || $tipoConta == 'GV' || $tipoConta == 'VC'){
 
                                $valorCC = ($dadosCCLote->pagcct_vlr_pag + $dadosCCLote->pagcct_vlr_acr + $dadosCCLote->pagcct_vlr_mul + $dadosCCLote->pagcct_vlr_vrm) - $dadosCCLote->pagcct_vlr_des;
                                
                            }elseif($tipoConta == 'AF'){
                            
                                $valorCC = $dadosCCLote->pagcct_vlr_pag;
                                
                            }elseif($tipoConta == 'CP' || $tipoConta == 'DV' || $tipoConta == 'IS' || $tipoConta == 'ID' || $tipoConta == 'AC'){
                            
                                $valorCC = ($dadosCCLote->pagcct_vlr_pag + $dadosCCLote->pagcct_vlr_acr) - $dadosCCLote->pagcct_vlr_des;
                                
                            }elseif($tipoConta == 'CO'){
                            
                                $valorCC = ($dadosCCLote->pagcct_vlr_pag + $dadosCCLote->pagcct_vlr_acr + $dadosCCLote->pagcct_vlr_mul) - $dadosCCLote->pagcct_vlr_des;
                            }
                        @endphp    
                        <div class="text-muted">
                            <div class="row">
                                <p class="text-sm col-md-4">Tipo da Conta
                                    <b class="d-block">{{ $dadosCC->conta_tipo.' - '.$dadosTipCCT->tabcon_nome }}</b>
                                </p>
                                <p class="text-sm col-md-4">Número da Conta
                                    <b class="d-block">{{ $dadosCC->conta_num_conta }}</b>
                                </p>
                                <p class="text-sm col-md-4">Data de Emissão
                                    <b class="d-block">{{ Helper::formataData($dadosCC->conta_dt_emissao) }}</b>
                                </p>
                            </div>
                            <div class="row">
                                <p class="text-sm col-md-4">Valor da Conta
                                    <b class="d-block">{{ 'R$ '.Helper::formataValorMonetario($dadosCC->conta_valor) }}</b>
                                </p>
                                <p class="text-sm col-md-4">Valor Pago
                                    <b class="d-block" id="saldoRestante">{{ 'R$ '.Helper::formataValorMonetario($dadosCC->conta_val_rec) }}</b>
                                </p>
                                <p class="text-sm col-md-4">Data de Vencimento
                                    <b class="d-block">{{ Helper::formataData($dadosCC->conta_dt_vencimento) }}</b>
                                </p>
                            </div>
                        </div>
                        <div class="post">
                            <h5 class="text-secondary font-weight-bold">Valores do Pagamento</h5>
                        </div>
                        <div class="row">
                            <x-adminlte-input name="saldoCC" type="text" value="{{ Helper::formataValorMonetario($saldoCC) }}" placeholder="0,00" fgroup-class="col-md-6" igroup-size="sm" disabled>
                                <x-slot name="label">
                                    Saldo Restante
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fa-solid fa-brazilian-real-sign"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>

                            @php
                                $config = Helper::dtRangeDataPtBR();
                                $dataPGT = Helper::formataData($dadosCCLote->pagcct_dtp);
                            @endphp
                            <x-adminlte-date-range name="dataPagamento" :config="$config" placeholder="Formato dia/mês/ano" fgroup-class="col-md-6" igroup-size="sm" disabled>
                                <x-slot name="label">
                                    Data de Pagamento <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="far fa-lg fa-calendar-alt"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-date-range>
                            @push('js')<script>$(() => $("#dataPagamento").val('{{ $dataPGT }}'))</script>@endpush
                        </div>
                        <div class="row">
                            <x-adminlte-input name="valorAcres" type="text" value="{{ Helper::formataValorMonetario($dadosCCLote->pagcct_vlr_acr) }}" placeholder="0,00" fgroup-class="col-md-6" igroup-size="sm">
                                <x-slot name="label">
                                    Valor do Acréscimo
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fa-solid fa-brazilian-real-sign"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                            
                            <x-adminlte-input name="valorDesc" type="text" value="{{ Helper::formataValorMonetario($dadosCCLote->pagcct_vlr_des) }}" placeholder="0,00" fgroup-class="col-md-6" igroup-size="sm">
                                <x-slot name="label">
                                    Valor do Desconto
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fa-solid fa-brazilian-real-sign"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                        <div class="row">
                            <x-adminlte-input name="valorMulta" type="text" value="{{ Helper::formataValorMonetario($dadosCCLote->pagcct_vlr_mul) }}" placeholder="0,00" fgroup-class="col-md-6" igroup-size="sm">
                                <x-slot name="label">
                                    Valor da Multa
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fa-solid fa-brazilian-real-sign"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                            
                            <x-adminlte-input name="variacaoMonet" type="text" value="{{ Helper::formataValorMonetario($dadosCCLote->pagcct_vlr_vrm) }}" placeholder="0,00" fgroup-class="col-md-6" igroup-size="sm">
                                <x-slot name="label">
                                    Variação Monetária
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fa-solid fa-brazilian-real-sign"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                        <div class="row">
                            <x-adminlte-input name="valorPagamento" type="text" value="{{ Helper::formataValorMonetario($dadosCCLote->pagcct_vlr_pag) }}" placeholder="0,00" fgroup-class="col-md-6" igroup-size="sm">
                                <x-slot name="label">
                                    Valor do Pagamento
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fa-solid fa-brazilian-real-sign"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                            
                            <x-adminlte-input name="valorTotal" type="text" value="{{ Helper::formataValorMonetario($valorCC) }}" placeholder="0,00" fgroup-class="col-md-6" igroup-size="sm" disabled>
                                <x-slot name="label">
                                    Valor Total a Pagar
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fa-solid fa-brazilian-real-sign"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>

                        <!-- Campos escondidos para o request -->
                        <input id="numCC" type="hidden" value="{{ $dadosCC->conta_num_conta }}" name="numCC">
                        <input id="tipoCC" type="hidden" value="{{ $dadosCC->conta_tipo }}" name="tipoCC">

                        <x-slot name="footerSlot">
                            <div style="float: right;">
                                <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('pagamentoPCC.fecharLote',['appOrigem' => $appOrigem]) }}'" label="Voltar" theme="" icon=""/>
                            </div>
                            <div style="float: left;">
                                <x-adminlte-button class="btn-nexus" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                            </div>
                        </x-slot>
                    </x-adminlte-card>
                </form>
                @endif
                <!-- ***** Final da edição da Conta Corrente - Bloco: EDITAR_CONTA ***** --> 
            </div>

            <!-- ************************************************** Bloco do Lado Direito do Painel Principal ************************************************** -->
            <div class="col-md-6">
                @include('financeiro.pagamento.fin_pnl006_Pagamento')
            </div>

        </div>
        <x-slot name="footerSlot">
            <div style="float: right;">
                <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.homePCCLote',['appOrigem' => $appOrigem]) }}'" label="Voltar" theme="" icon=""/>
            </div>
        </x-slot>
    </x-adminlte-card>

</div>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.Sweetalert2', true)
@section('plugins.jqueryValidation', true)
@section('plugins.DateRangePicker', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('css')
<!-- Importa o CSS referente a app do painel de pagamento - fin_pnl006_Pagamento.blade.php -->
<link rel="stylesheet" href="{{ asset('vendor/nexus/financeiro/pagamento/css/painel.pagamento.css') }}">
@stop

@section('js')
<script>
    var estagioAPP = {!! json_encode($estagio_app) !!};

    if(estagioAPP == 'PAG_C_CORPORATIVO' ){  
        var subEstagioPag = {!! json_encode($subEstagioPag ?? '') !!};
        var empresaLote = {!! json_encode($empresaLote ?? '') !!};
        var getParcelasUrl = "{{ route('ajax.getParcelasCartaoCorporativo', [':adm',':emp',':val']) }}";
        var getDadosUrl = "{{ route('ajax.getDadosCartaoCorporativo', [':adm',':emp']) }}";
    }

    if(estagioAPP == 'PAG_CHEQUE' ){  
        var subEstagioPag = {!! json_encode($subEstagioPag ?? '') !!};
        var empresaLote = {!! json_encode($empresaLote ?? '') !!};
        var getDadosBancoUrl = "{{ route('ajax.getDadosBanco', [':bco',':emp']) }}";
    }

    if(estagioAPP == 'PAG_CONTA_CORRENTE' ){
        var saldoInicial = {!! json_encode($dadosCCT->conta_saldo ?? '0') !!};
    }

    if(estagioAPP == 'PAINEL_PAGAMENTO' ){
        var cntPagCO = {!! json_encode($cntPagCO ?? '0') !!};
    }
</script>
<!-- Importa o JS referente a app do painel de pagamento - fin_pnl006_Pagamento.blade.php -->
<script src="{{ asset('vendor/nexus/financeiro/pagamento/js/painel.pagamento.js') }}"></script>

<script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script> 

<!--
|--------------------------------------------------------------------------
| Eventos de geração dos Datatable
|--------------------------------------------------------------------------
-->
<script>
    /* *****
    |----------------------------------------------------------------------------------------------------
    | Eventos da Inicialização do Datatable
    |----------------------------------------------------------------------------------------------------
    |
    | Adicionamos aqui todos os eventos relacionados a criação e manipulação de eventos do datatable
    |
    ***** */
    $(() => {

        var tableCcrSel = $('#tabela-cc-lote').DataTable({
            pageLength: 10,
            searching: false,
            lengthChange: false,
            language: dataTableLangPtBR,
            order: [
                [1, 'asc'],
                [2, 'asc']
            ],
            pagingType: 'full_numbers',
            processing: true,
            columns: [
                { orderable: false },
                { orderable: false },
                { orderable: false },
                { orderable: false },
                { orderable: false },
                { orderable: false },
            ]
        });
    });

    /* ------------------------------ Final da Inicialização do Datatable ------------------------------ */
</script>

<script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script> 

<!--
|--------------------------------------------------------------------------
| Eventos Iniciais da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() { 

        var estagioAPP = {!! json_encode($estagio_app) !!};
        var appOrigem = {!! json_encode($appOrigem) !!};
        var tipoConta = {!! json_encode($tipoConta) !!};

        /* **************************************** Eventos Iniciais do bloco  - EDITAR_CONTA **************************************** */

        if(estagioAPP == 'EDITAR_CONTA' ){

            /* ******************** Mascaras de campos float ******************** */

            //Mascaras dos campos de valores
            $('#valorAcres').mask('#.##0,00', {reverse: true});
            $('#valorDesc').mask('#.##0,00', {reverse: true});
            $('#valorPagamento').mask('#.##0,00', {reverse: true});
            $('#valorTotal').mask('#.##0,00', {reverse: true});
            $('#valorMulta').mask('#.##0,00', {reverse: true});
            $('#variacaoMonet').mask('#.##0,00', {reverse: true});

            if(appOrigem == 'PAG_AF'){

                $('#valorAcres').closest('.form-group').hide();
                $('#valorDesc').closest('.form-group').hide();
                $('#valorPagamento').closest('.form-group').hide();
                $('#valorMulta').closest('.form-group').hide();
                $('#variacaoMonet').closest('.form-group').hide();

            }else{

                if(tipoConta == 'CP' || tipoConta == 'DV' || tipoConta == 'IS' || tipoConta == 'ID' || tipoConta == 'AC'){
	
                    $('#valorMulta').closest('.form-group').hide();
                    $('#variacaoMonet').closest('.form-group').hide();
	
                }else if(tipoConta == 'CO'){
 
                    $('#variacaoMonet').closest('.form-group').hide();
                }
            }
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

        
    });
</script>

<!--
|--------------------------------------------------------------------------
| Eventos onChange da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() {

        // Função que converte string monetária PT-BR em número
        function toFloatBR(valor) {
            if (!valor) return 0;
            return parseFloat(valor.replace(/\./g, '').replace(',', '.')) || 0;
        }

        // Função que formata número para moeda brasileira (com 2 casas decimais)
        function formatBR(num) {
            return num.toFixed(2).replace('.', ',');
        }

        // Função para calcular o valor total
        function calcularValorTotal() {
            let valorPagamento = toFloatBR($('#valorPagamento').val());
            let valorAcres     = toFloatBR($('#valorAcres').val());
            let valorDesc      = toFloatBR($('#valorDesc').val());
            let valorMulta     = toFloatBR($('#valorMulta').val());
            let variacaoMonet  = toFloatBR($('#variacaoMonet').val());

            // (Pagamento + Acréscimo) - Desconto
            let valorTotal = (valorPagamento + valorAcres + valorMulta + variacaoMonet) - valorDesc;

            // Atualiza o campo valorTotal formatado
            $('#valorTotal').val(formatBR(valorTotal >= 0 ? valorTotal : 0));
        }

        // Escuta alterações nos campos
        $('#valorPagamento, #valorAcres, #valorDesc, #valorMulta, #variacaoMonet').on('input change blur', function() {
            calcularValorTotal();
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

    // Adiciona uma regra personalizada para não permitir zero
    $.validator.addMethod("notZero", function (value, element) {
        // Remove possíveis vírgulas e pontos para converter corretamente
        let val = value.replace(/\./g, '').replace(',', '.');
        return parseFloat(val) > 0;
    }, "O valor não pode ser zero.");

    //Inserção da Conta Corrente no Recebimento
    $('#form-edit-cc').validate({
        rules: {
            valorPagamento: {
                required: true,
                maxlength: 20,
                notZero: true
            },
            valorAcres: {
                maxlength: 20
            },
            valorDesc: {
                maxlength: 20
            },
            valorMulta: {
                maxlength: 20
            },
            variacaoMonet: {
                maxlength: 20
            },
        },
        messages: {
            valorPagamento: {
                required:  "Por Favor informe o Valor do Pagamento",
                maxlength: "Limite máximo do valor é de 15 digitos",
                notZero: "O valor do pagamento deve ser maior que zero"
            },
            valorAcres: {
                maxlength: "Limite máximo do valor é de 15 digitos"
            },
            valorDesc: {
                maxlength: "Limite máximo do valor é de 15 digitos"
            },
            valorMulta: {
                maxlength: "Limite máximo do valor é de 15 digitos"
            },
            variacaoMonet: {
                maxlength: "Limite máximo do valor é de 15 digitos"
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

            // Pega os valores dos campos
            let pagamento = $('#valorPagamento').val().replace(/\./g, '').replace(',', '.');
            let desconto = $('#valorDesc').val().replace(/\./g, '').replace(',', '.');
            let saldoCC = $('#saldoCC').val().replace(/\./g, '').replace(',', '.');

            // Converte para número (se vazio vira 0)
            let valPagamento = parseFloat(pagamento) || 0;
            let valDesc = parseFloat(desconto) || 0;
            let valSaldoCC = parseFloat(saldoCC) || 0;

            // Verifica se o desconto é igual ou maior que o pagamento
            if (valDesc >= valPagamento) {
                Swal.fire({
                    icon: 'info',
                    title: 'Aviso!',
                    text: 'O valor do desconto não pode ser igual ou maior que o valor do pagamento.',
                    confirmButtonColor: "#007bff",
                    confirmButtonText: 'Ok'
                });
                return false; // Impede o envio do formulário
            }

             // Verifica o valor pago da conta é maior que o saldo restante
            if (valPagamento > valSaldoCC) {
                Swal.fire({
                    icon: 'info',
                    title: 'Aviso!',
                    text: 'O Valor do Pagamento não pode ser maior que o Saldo Restante da conta corrente.',
                    confirmButtonColor: "#007bff",
                    confirmButtonText: 'Ok'
                });
                return false;
            }

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
| Eventos de Menssagens da APP
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
            text: '{!! session("error") !!}',
            icon: "error"
        });
    @endif

    @if(Session::has('info'))
        Swal.fire({
            confirmButtonColor: "#007bff",
            title: "Aviso!",
            text: "{{ session('info') }}",
            icon: "info",
            customClass: {
                icon: "no-before-icon",
            }
        });
    @endif

    @if(Session::has('success2'))
        Swal.fire({
            confirmButtonColor: "#007bff",
            title: "Sucesso!",
            html: "{!! session('success2') !!}",
            icon: "success",
            customClass: {
                icon: "no-before-icon",
            }
        });
    @endif
</script>
@stop

