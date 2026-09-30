@extends('adminlte::page')

@section('title', 'Contas Correntes')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Recebimento</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.filtroRecCCT')}}">Filtro de Contas Correntes</a>
            </li>
            <li class="breadcrumb-item active">Painel de Contas Correntes</li>
        </ol>
    </div>
</div>
@stop


@section('content')
<div class="col-md-12">

    <!-- ********** Painel Principal da Emissão de NF ********** -->
    <x-adminlte-card title="Painel de Recebimento de Contas Correntes" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
        @php 
            $dadosCli = DB::table('cadastro_clientes')->where('cliente_codigo', $clienteREC)->first();
            $dadosUsuFin = DB::table('financeiro_tab_usuarios')->where('tabusu_usuario', Auth::user()->usuario_codigo)->where('tabusu_empresa', Auth::user()->usuario_empresa)->first();
            $dadosRaz = DB::table('financeiro_razoes')->where('razao_codigo', $dadosUsuFin->tabusu_razao)->where('razao_empresa', $dadosUsuFin->tabusu_empresa)->where('razao_tipo', $dadosUsuFin->tabusu_tipo_razao)->first();
        @endphp
        <div class="row">
            <div style="width:100%; margin: 0 10px 10px 10px; color:#fff">
                <table style="width:100%; font-style: normal; border-collapse: separate; border-spacing: 5px 5px;">
                    <tbody>
                        <tr style="text-align: center;">
                            <td style="background-color: #00abab; border-radius: 8px; border: 1px solid #008f8f;"><strong>Usuário</strong></td>
                            <td style="background-color: #00abab; border-radius: 8px; border: 1px solid #008f8f;"><strong>Caixa Operacional</strong></td>
                            <td style="background-color: #00abab; border-radius: 8px; border: 1px solid #008f8f;"><strong>Cliente</strong></td>
                        </tr>
                        <tr style="border: 1px solid #008f8f; background-color: #fff; color:#008f8f; text-align: center;">
                            <td style="border: 1px solid #008f8f; border-radius: 8px; text-align: center;">{{ Auth::user()->usuario_codigo.' - '.Auth::user()->name }}</td>
                            <td style="border: 1px solid #008f8f; border-radius: 8px; text-align: center;">{{ $dadosUsuFin->tabusu_razao.' - '.$dadosRaz->razao_nome }}</td>
                            <td style="border: 1px solid #008f8f; border-radius: 8px; text-align: center;">{{ $clienteREC.' - '.$dadosCli->cliente_nome }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Posiciona os blocos do lado esquerdo e direito na mesma linha -->
        <div class="row">

            <!-- ************************************************** Bloco do Lado Esquerdo do Painel Principal ************************************************** -->
            <div class="col-md-6">

                <!-- ***** Início dos dados de Recebimento - Bloco: PAINEL_PRINCIPAL ***** -->
                @if($estagio_app == 'PAINEL_PRINCIPAL' || $estagio_app == 'PAINEL_RECEBIMENTO' || $estagio_app == 'REC_DINHEIRO' || $estagio_app == 'REC_PIX' || 
                    $estagio_app == 'REC_CHEQUE' || $estagio_app == 'REC_C_CREDITO' || $estagio_app == 'REC_C_DEBITO' || $estagio_app == 'REC_FIL_C_CORRENTE' || 
                    $estagio_app == 'REC_SEL_C_CORRENTE' || $estagio_app == 'REC_CONTA_CORRENTE')
                <x-adminlte-card title="Contas Selecionadas para Recebimento" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
                    @php
                        // Monta os dados da tabela do bloco
                        $heads = [
                            'Estabelecimento',
                            'Operação',
                            ['label' => '', 'no-export' => true, 'width' => 5],
                            'Tipo',
                            'Conta',
                            'Valor Conta',
                            'Valor Rec.',
                            'Acréscimo',
                            'ISS Ret.',
                            'IRRF',
                            'Desp. Bancária'
                        ];
                    @endphp
                    <x-adminlte-datatable id="tabela-contas-sel" :heads="$heads" theme="light" striped hoverable>
                        @foreach($contasRecebidas as $conta)
                            @php 
                                if($conta->reccct_flag == 'N'){
                                    $flag = 'Nova Conta';
                                }elseif($conta->reccct_flag == 'A'){
                                    $flag = 'Acréscimo';
                                }else{
                                    $flag = 'Baixa';
                                }

                                $dadosEmp = HelperDataSelect::buscaDadosEmpresa($conta->reccct_emi);
                            @endphp
                            <tr>
                                <td>{{ $conta->reccct_emi.' - '.$dadosEmp->empresa_nome }}</td>
                                <td>{{ $flag }}</td>
                                <td>
                                    @if($estagio_app == 'PAINEL_PRINCIPAL')
                                    <nobr class="d-flex justify-content-center">
                                        <form method="get" action="{{ route('recebimentoCCT.excluirCCT', ['empresa' => $empresaREC, 'cliente' => $clienteREC, 'idRecebimento' => $idRecebimento, 'tipoCCR' => $conta->reccct_tcc, 'numCCR' => $conta->reccct_ncc]) }}">
                                            @csrf
                                            @method('get')
                                            <button class="btn btn-xs btn-outline-danger mx-1" title="Excluir Registro" value="Delete" type="submit" >
                                                <i class="fa fa-lg fa-fw fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </nobr>
                                    @endif
                                </td>
                                <td>{{ $conta->reccct_tcc }}</td>
                                <td>{{ $conta->reccct_ncc }}</td>
                                <td>{{ Helper::formataValorMonetario($conta->reccct_valor) }}</td>
                                <td>{{ Helper::formataValorMonetario($conta->reccct_valor_rec) }}</td>
                                <td>{{ Helper::formataValorMonetario($conta->reccct_acrescimo) }}</td>
                                <td>{{ Helper::formataValorMonetario($conta->reccct_valor_iss) }}</td>
                                <td>{{ Helper::formataValorMonetario($conta->reccct_valor_irrf) }}</td>
                                <td>{{ Helper::formataValorMonetario($conta->reccct_desp_banc) }}</td>
                            </tr>
                        @endforeach
                    </x-adminlte-datatable>
                        <x-slot name="footerSlot">
                            @if($estagio_app == 'PAINEL_PRINCIPAL')
                            <div style="float: right;">
                                <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('recebimento.painelRecebimento', ['empresa' => $empresaREC, 'cliente' => $clienteREC, 'idRecebimento' => $idRecebimento, 'tipoRecebimento' => 'CCT']) }}'" label="Receber" theme="" icon="fa-solid fa-sack-dollar"/>
                            </div>
                            <div style="float: left;">
                                <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('recebimentoCCT.consultaCCTAberta', ['empresa' => $empresaREC, 'cliente' => $clienteREC, 'idRecebimento' => $idRecebimento]) }}'" label="Contas Abertas" theme="" icon="fa-regular fa-folder-open"/>
                                <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('recebimentoCCT.novaCCT', ['empresa' => $empresaREC, 'cliente' => $clienteREC, 'idRecebimento' => $idRecebimento]) }}'" label="Nova Conta" theme="" icon="fa-solid fa-plus"/>
                            </div>
                            @else
                            <div style="float: right;">
                                <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('recebimentoCCT.reabreSelecao', ['empresa' => $empresaREC, 'cliente' => $clienteREC, 'idRecebimento' => $idRecebimento]) }}'" label="Reabrir Seleção" theme="" icon="fa-solid fa-book-open"/>
                            </div>
                            @endif
                        </x-slot>
                </x-adminlte-card>
                @endif
                <!-- ***** Final dos dados de Recebimento - Bloco: PAINEL_PRINCIPAL ***** -->

                <!-- ***** Início dos dados das Contas Correntes em Aberto - Bloco: SEL_CONTAS_ABERTAS ***** -->
                @if($estagio_app == 'SEL_CONTAS_ABERTAS')
                <x-adminlte-card title="Contas Abertas para Recebimento" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
                    @php
                        // Monta os dados da tabela do bloco
                        $heads = [
                            ['label' => '', 'no-export' => true, 'width' => 5],
                            'Tipo',
                            'Conta',
                            'Responsável',
                            'Empresa',
                            'Data Emissão',
                            'Data Vencimento',
                            'Valor'
                        ];
                    @endphp
                    <x-adminlte-datatable id="tabela-contas-aberta" :heads="$heads" theme="light" striped hoverable>
                        @foreach($contasAbertas as $conta)
                            @php 
                                $url = route('recebimentoCCT.contaSelecionada', [
                                    'empresa' => $empresaREC,
                                    'cliente' => $clienteREC,
                                    'idRecebimento' => $idRecebimento,
                                    'tipoCCR' => $conta->conta_tipo,
                                    'numCCR' => $conta->conta_num_conta
                                ]);
                            @endphp
                            <tr>
                                <td>
                                    <nobr class="d-flex justify-content-center">
                                        <a href="{{ $url }}" class="btn btn-nexus btn-sm">
                                            <i class="fa-solid fa-check"></i> Selecionar
                                        </a>
                                    </nobr>
                                </td>
                                <td>{{ $conta->conta_tipo }}</td>
                                <td>{{ $conta->conta_num_conta }}</td>
                                <td>{{ $conta->conta_responsavel }}</td>
                                <td>{{ $conta->conta_empresa }}</td>
                                <td>{{ Helper::formataData($conta->conta_dt_emissao) }}</td>
                                <td>{{ Helper::formataData($conta->conta_dt_vencimento) }}</td>
                                <td>{{ Helper::formataValorMonetario($conta->conta_valor - $conta->conta_val_rec) }}</td>
                            </tr>
                        @endforeach
                    </x-adminlte-datatable>
                        <x-slot name="footerSlot">
                            <div style="float: right;">
                                <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('recebimentoCCT.painelPrincipalCCT', ['empresa' => $empresaREC, 'cliente' => $clienteREC, 'idRecebimento' => $idRecebimento]) }}'" label="Voltar" theme="" icon=""/>
                            </div>
                        </x-slot>
                </x-adminlte-card>
                @endif
                <!-- ***** Final dos dados das Contas Correntes em Aberto - Bloco: SEL_CONTAS_ABERTAS ***** -->

                <!-- ***** Início dos Dados da Conta Corrente Selecionada para Inclusão - Bloco: CONTA_SELECIONADA ***** -->
                @if($estagio_app == 'CONTA_SELECIONADA')
                <form method="post" action="{{ route('recebimentoCCT.inserirCCT', ['empresa' => $empresaREC, 'cliente' => $clienteREC, 'idRecebimento' => $idRecebimento]) }}" id="form-rec-cct" novalidate="novalidate">
                    @csrf 
                    @method('post')
                    <x-adminlte-card title="Dados da Conta Corrente Selecionada" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
                        @php 
                            $dadosTipCCT = DB::table('financeiro_tab_contas')->where('tabcon_codigo', $contaSelecionada->conta_tipo)->first();
                            $dadosEmp = HelperDataSelect::buscaDadosEmpresa($empresaREC);
                            $dadosOri = DB::table('parametros_sis_origens')->where('origem_codigo', $contaSelecionada->conta_origem)->first();
                            $dadosSCC = DB::table('financeiro_tab_subtipo_contas_correntes')->where('tabscc_tcc', $contaSelecionada->conta_tipo)->where('tabscc_cod', $contaSelecionada->conta_subtipo)->first();
                            $valorCCT = $contaSelecionada->conta_valor - $contaSelecionada->conta_val_rec;
                            $tipoContaRec = $contaSelecionada->conta_tipo;
                        @endphp    
                        <div class="text-muted">
                            <div class="row">
                                <p class="text-sm col-md-4">Tipo da Conta
                                    <b class="d-block">{{ $contaSelecionada->conta_tipo.' - '.$dadosTipCCT->tabcon_nome }}</b>
                                </p>
                                <p class="text-sm col-md-4">Responsável
                                    <b class="d-block">{{ $contaSelecionada->conta_responsavel.' - '.$dadosCli->cliente_nome }}</b>
                                </p>
                                <p class="text-sm col-md-4">Empresa
                                    <b class="d-block">{{ $contaSelecionada->conta_empresa.' - '.$dadosEmp->empresa_nome }}</b>
                                </p>
                            </div>
                            <div class="row">
                                <p class="text-sm col-md-4">Número da Conta
                                    <b class="d-block">{{ $contaSelecionada->conta_num_conta }}</b>
                                </p>
                                <p class="text-sm col-md-4">Data de Emissão
                                    <b class="d-block">{{ Helper::formataData($contaSelecionada->conta_dt_emissao) }}</b>
                                </p>
                                <p class="text-sm col-md-4">Data de Vencimento
                                    <b class="d-block">{{ Helper::formataData($contaSelecionada->conta_dt_vencimento) }}</b>
                                </p>
                            </div>
                            <div class="row">
                                <p class="text-sm col-md-4">Origem
                                    <b class="d-block">{{ $contaSelecionada->conta_origem.' - '.$dadosOri->origem_desc }}</b>
                                </p>
                                <p class="text-sm col-md-4">Subtipo
                                    <b class="d-block">{{ $contaSelecionada->conta_subtipo.' - '.$dadosSCC->tabscc_nom }}</b>
                                </p>
                                <p class="text-sm col-md-4">Complemento
                                    <b class="d-block">{{ $contaSelecionada->conta_complemento }}</b>
                                </p>
                            </div>
                            <div class="row">
                                <p class="text-sm col-md-4">Valor da Conta
                                    <b class="d-block">{{ 'R$ '.Helper::formataValorMonetario($valorCCT) }}</b>
                                </p>
                                @if($tipoContaRec == 'AC')
                                <p class="text-sm col-md-4">Novo Saldo da Conta
                                    <b class="d-block" id="saldoNovo">{{ 'R$ '.Helper::formataValorMonetario($valorCCT) }}</b>
                                </p>
                                @else
                                <p class="text-sm col-md-4">Saldo Restante
                                    <b class="d-block" id="saldoRestante">{{ 'R$ '.Helper::formataValorMonetario($valorCCT) }}</b>
                                </p>
                                <p class="text-sm col-md-4">Total Recebido
                                    <b class="d-block" id="totalRecebimento">R$ 0,00</b>
                                </p>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <x-adminlte-input name="valCCT" type="text" value="" placeholder="0,00" fgroup-class="col-md-12" igroup-size="sm">
                                <x-slot name="label">
                                    Valor do Recebimento <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fa-solid fa-brazilian-real-sign"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                        <div class="row bloco-rec-acr-des">
                            <x-adminlte-input name="valAcreDesc" type="text" value="" placeholder="0,00" fgroup-class="col-md-12" igroup-size="sm">
                                <x-slot name="label">
                                    Acréscimo
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fa-solid fa-brazilian-real-sign"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                        <div class="row bloco-rec-imposto">
                            <x-adminlte-input name="valISS" type="text" value="" placeholder="0,00" fgroup-class="col-md-4" igroup-size="sm">
                                <x-slot name="label">
                                    ISS Retido
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fa-solid fa-brazilian-real-sign"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                            <x-adminlte-input name="valIRRF" type="text" value="" placeholder="0,00" fgroup-class="col-md-4" igroup-size="sm">
                                <x-slot name="label">
                                    IRRF
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fa-solid fa-brazilian-real-sign"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                            <x-adminlte-input name="valDepBan" type="text" value="" placeholder="0,00" fgroup-class="col-md-4" igroup-size="sm">
                                <x-slot name="label">
                                    Despesa Bancária
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fa-solid fa-brazilian-real-sign"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                        <div class="row">
                            <x-adminlte-textarea name="observacao" label="Observação" rows=3 label-class="text-dark" igroup-size="sm" placeholder="Informe a Observação..." fgroup-class="col-md-12">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fas fa-lg fa-file-alt text-white"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-textarea>
                        </div>

                        <!-- Campos escondidos para o request -->
                        <input id="tipoCCR" type="hidden" value="{{ $tipoContaRec }}" name="tipoCCR">
                        <input id="numCCR" type="hidden" value="{{ $contaSelecionada->conta_num_conta }}" name="numCCR">

                        <x-slot name="footerSlot">
                            <div style="float: right;">
                                <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('recebimentoCCT.consultaCCTAberta', ['empresa' => $empresaREC, 'cliente' => $clienteREC, 'idRecebimento' => $idRecebimento]) }}'" label="Voltar" theme="" icon=""/>
                            </div>
                            <div style="float: left;">
                                <x-adminlte-button class="btn-nexus" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                            </div>
                        </x-slot>
                    </x-adminlte-card>
                </form>
                @endif
                <!-- ***** Final dos  Dados da Conta Corrente Selecionada para Inclusão - Bloco: CONTA_SELECIONADA ***** -->

                <!-- ***** Início dos Dados de Inclusão da Nova Conta Corrente - Bloco: NOVA_CONTA_CORRENTE ***** -->
                @if($estagio_app == 'NOVA_CONTA_CORRENTE')
                <form method="post" action="{{ route('recebimentoCCT.inserirNovaCCT', ['empresa' => $empresaREC, 'cliente' => $clienteREC, 'idRecebimento' => $idRecebimento]) }}" id="form-rec-new-cct" novalidate="novalidate">
                    @csrf 
                    @method('post')
                    <x-adminlte-card title="Inclusão de Nova Conta Corrente" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
                        @php 
                            $array_empresa = HelperArraySelect::arrayEmpresas(1, 1);
                            $array_origem = HelperArraySelect::arrayOrigens(1, 1);
                            $array_subtipo = HelperArraySelect::arraySubtipo(1, 1, 'AC');
                        @endphp    
                        <div class="row">
                            <x-adminlte-select name="tipoCCR" fgroup-class="col-md-6" igroup-size="sm">
                                <x-slot name="label">
                                    Tipo da Conta <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="['AC' => 'AC - Adiantamento de Clientes']" empty-option="Selecione..." selected=""/>
                            </x-adminlte-select>
                        </div>
                        <div class="row">
                            <x-adminlte-select name="empCCR" fgroup-class="col-md-6" igroup-size="sm">
                                <x-slot name="label">
                                    Estabelecimento <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="$array_empresa" empty-option="Selecione..." selected=""/>
                            </x-adminlte-select>
                            <x-adminlte-select name="resCCR" fgroup-class="col-md-6" igroup-size="sm">
                                <x-slot name="label">
                                    Responsável <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="[$dadosCli->cliente_codigo => $dadosCli->cliente_codigo . ' - ' . $dadosCli->cliente_nome]" empty-option="Selecione..." selected=""/>
                            </x-adminlte-select>
                        </div>
                        <div class="row">
                            @php
                                $config = Helper::dtRangeDataPtBR();
                                $dataEmi = '';
                            @endphp
                            <!-- Data de Nascimento -->
                            <x-adminlte-date-range name="dataEmiCCR" :config="$config" placeholder="Formato dia/mês/ano" fgroup-class="col-md-6" igroup-size="sm">
                                <x-slot name="label">
                                    Data de Emissão <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="far fa-lg fa-calendar-alt"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-date-range>
                            @push('js')<script>$(() => $("#dataEmiCCR").val('{{ $dataEmi }}'))</script>@endpush
                            @php
                                $config = Helper::dtRangeDataPtBR();
                                $dataVct = '';
                            @endphp
                            <!-- Data de Nascimento -->
                            <x-adminlte-date-range name="dataVctCCR" :config="$config" placeholder="Formato dia/mês/ano" fgroup-class="col-md-6" igroup-size="sm">
                                <x-slot name="label">
                                    Data de Vencimento <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="far fa-lg fa-calendar-alt"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-date-range>
                            @push('js')<script>$(() => $("#dataVctCCR").val('{{ $dataVct }}'))</script>@endpush
                        </div>
                        <div class="row">
                            <x-adminlte-select name="oriCCR" fgroup-class="col-md-6" igroup-size="sm">
                                <x-slot name="label">
                                    Origem <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="$array_origem" empty-option="Selecione..." selected=""/>
                            </x-adminlte-select>
                            <x-adminlte-select name="sccCCR" fgroup-class="col-md-6" igroup-size="sm">
                                <x-slot name="label">
                                    Subtipo <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="$array_subtipo" empty-option="Selecione..." selected=""/>
                            </x-adminlte-select>
                        </div>
                        <div class="row">
                            <x-adminlte-input name="valCCT" type="text" value="" placeholder="0,00" fgroup-class="col-md-6" igroup-size="sm">
                                <x-slot name="label">
                                    Valor do Recebimento <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fa-solid fa-brazilian-real-sign"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                            <x-adminlte-input name="compCCR" label="Complemento" type="text" value="" placeholder="Complemento" fgroup-class="col-md-6" igroup-size="sm"></x-adminlte-input>
                        </div>
                        <div class="row">
                            <x-adminlte-textarea name="observacao" label="Observação" rows=3 label-class="text-dark" igroup-size="sm" placeholder="Informe a Observação..." fgroup-class="col-md-12">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fas fa-lg fa-file-alt text-white"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-textarea>
                        </div>
                        <x-slot name="footerSlot">
                            <div style="float: right;">
                                <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('recebimentoCCT.consultaCCTAberta', ['empresa' => $empresaREC, 'cliente' => $clienteREC, 'idRecebimento' => $idRecebimento]) }}'" label="Voltar" theme="" icon=""/>
                            </div>
                            <div style="float: left;">
                                <x-adminlte-button class="btn-nexus" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                            </div>
                        </x-slot>
                    </x-adminlte-card>
                </form>
                @endif
                <!-- ***** Final dos Dados de Inclusão da Nova Conta Corrente - Bloco: NOVA_CONTA_CORRENTE ***** -->
            </div>

            <!-- ************************************************** Bloco do Lado Direito do Painel Principal ************************************************** -->
            <div class="col-md-6">
                @include('financeiro.recebimento.fin_pnl001_Recebimento')
            </div>

        </div>
        <x-slot name="footerSlot">
            <div style="float: right;">
                <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.filtroRecCCT') }}'" label="Voltar" theme="" icon=""/>
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
<!-- Importa o CSS referente a app do painel de recebimento - fin_pnl001_Recebimento.blade.php -->
<link rel="stylesheet" href="{{ asset('vendor/nexus/financeiro/recebimento/css/painel.recebimento.css') }}">
@stop

@section('js')
<script>
    var estagioAPP = {!! json_encode($estagio_app) !!};

    if(estagioAPP == 'REC_C_CREDITO' ){  
        var subEstagioRec = {!! json_encode($subEstagioRec ?? '') !!};
        var empresaREC = {!! json_encode($empresaREC ?? '') !!};
        var getParcelasUrl = "{{ route('ajax.getParcelasCartaoCredito', [':adm',':emp',':val']) }}";
    }

    if(estagioAPP == 'REC_CONTA_CORRENTE' ){
        var saldoInicial = {!! json_encode($dadosCCT->conta_saldo ?? '0') !!};
    }
</script>
<!-- Importa o JS referente a app do painel de recebimento - fin_pnl001_Recebimento.blade.php -->
<script src="{{ asset('vendor/nexus/financeiro/recebimento/js/painel.recebimento.js') }}"></script>

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
        
        // Variavel do agrupamento inicial do Datatable
        var groupColumns = [0,1];

        var tableCcrSel = $('#tabela-contas-sel').DataTable({
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
                { orderable: false },
                { orderable: false },
                { orderable: false },
                { orderable: false },
                { orderable: false },
            ],
            columnDefs: [
                { visible: false, targets: groupColumns },
            ],
            drawCallback: function (settings) {

                var api = this.api();
                var colspan = 10; // Ou qualquer valor que você precise para o colspan
                
                applyRowGrouping(api, groupColumns, colspan); // Chama a função externa
            },
        });

        var tableCcrAberta = $('#tabela-contas-aberta').DataTable({
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
                { orderable: false },
                { orderable: false }
            ],
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

        /* **************************************** Eventos Iniciais do bloco  - CONTA_SELECIONADA **************************************** */

        if(estagioAPP == 'CONTA_SELECIONADA' ){

            /* ******************** Mascaras de campos float ******************** */

            //Mascaras dos campos de valores
            $('#valCCT').mask('#.##0,00', {reverse: true});
            $('#valAcreDesc').mask('#.##0,00', {reverse: true});
            $('#valISS').mask('#.##0,00', {reverse: true});
            $('#valIRRF').mask('#.##0,00', {reverse: true});
            $('#valDepBan').mask('#.##0,00', {reverse: true});

            var tipoConta = {!! json_encode($tipoContaRec ?? '') !!};

            if(tipoConta == 'AC'){
                $('.bloco-rec-imposto').hide();
                $('.bloco-rec-acr-des').hide();
            }else if(tipoConta == 'AF' || tipoConta == 'DC'){
                $('.bloco-rec-imposto').hide();
                $('.bloco-rec-acr-des').show();
            }

            let saldoInicial = {!! json_encode($valorCCT ?? '0') !!}; // Pega o saldo inicial da variável PHP
            saldoInicial = parseFloat(saldoInicial);

            // Função para remover máscara e converter para número
            function limparMoeda(valor) {
                return parseFloat(valor.replace(/\./g, '').replace(',', '.')) || 0;
            }

            // Função para formatar número como moeda brasileira
            function formatarMoeda(valor) {
                return valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
            }

            if(tipoConta == 'AC'){

                // Evento ao alterar o campo
                $("#valCCT").on("input", function() {
                    let valorDigitado = limparMoeda($(this).val()); // Remove máscara e converte para número
                    let saldoNovo = saldoInicial + valorDigitado; // Calcula o novo saldo

                    // Atualiza o saldo formatado
                    $("#saldoNovo").text(formatarMoeda(saldoNovo));
                });

            }
            
            if(tipoConta == 'AF' || tipoConta == 'DC'){

                // Evento ao alterar o campo
                $("#valCCT").on("input", function() {
                    let valorDigitado = limparMoeda($(this).val());
                    let valorAcre = limparMoeda($('#valAcreDesc').val());
                    let totalRec = valorAcre + valorDigitado;

                    // Atualiza o saldo formatado
                    $("#totalRecebimento").text(formatarMoeda(totalRec));

                    let saldoNovo = saldoInicial - valorDigitado;

                    // Atualiza o saldo formatado
                    $("#saldoRestante").text(formatarMoeda(saldoNovo));
                });

                // Evento ao alterar o campo
                $("#valAcreDesc").on("input", function() {
                    let valorDigitado = limparMoeda($(this).val());
                    let valorRec = limparMoeda($('#valCCT').val());
                    let totalRec = valorRec + valorDigitado;

                    // Atualiza o saldo formatado
                    $("#totalRecebimento").text(formatarMoeda(totalRec));
                });
            }
            
            if(tipoConta == 'RD'){
                
                // Evento ao alterar o campo
                $("#valCCT").on("input", function() {
                    let valorDigitado = limparMoeda($(this).val());
                    let valorISS = limparMoeda($('#valISS').val());
                    let valorAcre = limparMoeda($('#valAcreDesc').val());
                    let valorIRRF = limparMoeda($('#valIRRF').val());
                    let valorDepBan = limparMoeda($('#valDepBan').val());
                    let totalRec = (valorAcre + valorDigitado) - valorIRRF - valorISS - valorDepBan;

                    // Atualiza o saldo formatado
                    $("#totalRecebimento").text(formatarMoeda(totalRec));

                    let saldoNovo = saldoInicial - valorDigitado;

                    // Atualiza o saldo formatado
                    $("#saldoRestante").text(formatarMoeda(saldoNovo));
                });

                // Evento ao alterar o campo
                $("#valAcreDesc").on("input", function() {
                    let valorDigitado = limparMoeda($(this).val());
                    let valorISS = limparMoeda($('#valISS').val());
                    let valorReceb = limparMoeda($('#valCCT').val());
                    let valorIRRF = limparMoeda($('#valIRRF').val());
                    let valorDepBan = limparMoeda($('#valDepBan').val());
                    let totalRec = (valorReceb + valorDigitado) - valorIRRF - valorISS - valorDepBan;

                    // Atualiza o saldo formatado
                    $("#totalRecebimento").text(formatarMoeda(totalRec));
                });

                // Evento ao alterar o campo
                $("#valISS").on("input", function() {
                    let valorDigitado = limparMoeda($(this).val());
                    let valorRecebimento = limparMoeda($('#valCCT').val());
                    let valorAcre = limparMoeda($('#valAcreDesc').val());
                    let valorIRRF = limparMoeda($('#valIRRF').val());
                    let valorDepBan = limparMoeda($('#valDepBan').val());
                    let totalRec = (valorAcre + valorRecebimento) - valorIRRF - valorDigitado - valorDepBan;

                    // Atualiza o saldo formatado
                    $("#totalRecebimento").text(formatarMoeda(totalRec));
                });

                // Evento ao alterar o campo
                $("#valIRRF").on("input", function() {
                    let valorDigitado = limparMoeda($(this).val());
                    let valorRecebimento = limparMoeda($('#valCCT').val());
                    let valorAcre = limparMoeda($('#valAcreDesc').val());
                    let valorISS = limparMoeda($('#valISS').val());
                    let valorDepBan = limparMoeda($('#valDepBan').val());
                    let totalRec = (valorAcre + valorRecebimento) - valorISS - valorDigitado - valorDepBan;

                    // Atualiza o saldo formatado
                    $("#totalRecebimento").text(formatarMoeda(totalRec));
                });

                // Evento ao alterar o campo
                $("#valDepBan").on("input", function() {
                    let valorDigitado = limparMoeda($(this).val());
                    let valorRecebimento = limparMoeda($('#valCCT').val());
                    let valorAcre = limparMoeda($('#valAcreDesc').val());
                    let valorISS = limparMoeda($('#valISS').val());
                    let valorIRRF = limparMoeda($('#valIRRF').val());
                    let totalRec = (valorAcre + valorRecebimento) - valorISS - valorDigitado - valorIRRF;

                    // Atualiza o saldo formatado
                    $("#totalRecebimento").text(formatarMoeda(totalRec));
                });
            }
        }

        /* **************************************** Eventos Iniciais do bloco  - CONTA_SELECIONADA **************************************** */

        if(estagioAPP == 'NOVA_CONTA_CORRENTE' ){

            /* ******************** Mascaras de campos float ******************** */

            //Mascaras dos campos de valores
            $('#valCCT').mask('#.##0,00', {reverse: true});

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

        
    });
</script>

<!--
|--------------------------------------------------------------------------
| Eventos Validate da app
|--------------------------------------------------------------------------
-->
<script>
$(function () {

    //Inserção da Conta Corrente no Recebimento
    $('#form-rec-cct').validate({
        rules: {
            valCCT: {
                required: true,
                maxlength: 20
            },
            valAcreDesc: {
                maxlength: 20
            },
            valISS: {
                maxlength: 20
            },
            valIRRF: {
                maxlength: 20
            },
            valDepBan: {
                maxlength: 20
            },
            observacao: {
                maxlength: 255
            },
        },
        messages: {
            valCCT: {
                required:  "Por Favor informe o Valor do Recebimento",
                maxlength: "Limite máximo do valor é de 15 digitos"
            },
            valAcreDesc: {
                maxlength: "Limite máximo do valor é de 15 digitos"
            },
            valISS: {
                maxlength: "Limite máximo do valor é de 15 digitos"
            },
            valIRRF: {
                maxlength: "Limite máximo do valor é de 15 digitos"
            },
            valDepBan: {
                maxlength: "Limite máximo do valor é de 15 digitos"
            },
            observacao: {
                maxlength: "Limite máximo da Observação é 255 caracteres"
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

    //Inserção da Conta Corrente no Recebimento
    $('#form-rec-new-cct').validate({
        rules: {
            valCCT: {
                required: true,
                maxlength: 20
            },
            tipoCCR: {
                required: true
            },
            resCCR: {
                required: true
            },
            empCCR: {
                required: true
            },
            dataEmiCCR: {
                required: true
            },
            dataVctCCR: {
                required: true
            },
            oriCCR: {
                required: true
            },
            sccCCR: {
                required: true
            },
            compCCR: {
                maxlength: 40
            },
            observacao: {
                maxlength: 255
            },
        },
        messages: {
            valCCT: {
                required:  "Por Favor informe o Valor do Recebimento",
                maxlength: "Limite máximo do valor é de 15 digitos"
            },
            tipoCCR: {
                required:  "Por Favor informe o Tipo da Conta"
            },
            resCCR: {
                required:  "Por Favor informe o Responsável"
            },
            empCCR: {
                required:  "Por Favor informe a Empresa"
            },
            dataEmiCCR: {
                required:  "Por Favor informe a Data de Emissão"
            },
            dataVctCCR: {
                required:  "Por Favor informe a Data de Vencimento"
            },
            oriCCR: {
                required:  "Por Favor informe a Origem"
            },
            sccCCR: {
                required:  "Por Favor informe o Subtipo"
            },
            compCCR: {
                maxlength: "Limite máximo do Complemento é 40 caracteres"
            },
            observacao: {
                maxlength: "Limite máximo da Observação é 255 caracteres"
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
            text: "{{ session('success2') }}",
            icon: "success",
            customClass: {
                icon: "no-before-icon",
            }
        });
    @endif
</script>
@stop

