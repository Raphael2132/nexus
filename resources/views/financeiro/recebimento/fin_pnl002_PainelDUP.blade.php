@extends('adminlte::page')

@section('title', 'Duplicatas')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Recebimento</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.filtroRecDUP')}}">Filtro de Duplicatas</a>
            </li>
            <li class="breadcrumb-item active">Painel de Duplicatas</li>
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
            $empresaFormat = HelperFormatSelect::formataEmpresaCodigoNome($empresaREC);
        @endphp
        <div class="row">
            <div style="width:100%; margin: 0 10px 10px 10px; color:#fff">
                <table style="width:100%; font-style: normal; border-collapse: separate; border-spacing: 5px 5px;">
                    <tbody>
                        <tr style="text-align: center;">
                            <td style="background-color: #00abab; border-radius: 8px; border: 1px solid #008f8f;"><strong>Empresa</strong></td>
                            <td style="background-color: #00abab; border-radius: 8px; border: 1px solid #008f8f;"><strong>Usuário</strong></td>
                            <td style="background-color: #00abab; border-radius: 8px; border: 1px solid #008f8f;"><strong>Caixa Operacional</strong></td>
                            <td style="background-color: #00abab; border-radius: 8px; border: 1px solid #008f8f;"><strong>Cliente</strong></td>
                        </tr>
                        <tr style="border: 1px solid #008f8f; background-color: #fff; color:#008f8f; text-align: center;">
                            <td style="border: 1px solid #008f8f; border-radius: 8px; text-align: center;">{{ $empresaFormat }}</td>
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
                            'Duplicata Cod. Hide',
                            'Duplicata Seq. Hide',
                            ['label' => '', 'no-export' => true, 'width' => 5],
                            'Duplicata',
                            'Emissão',
                            'Vencimento',
                            'Valor',
                            'Aberto',
                            'Juros',
                            'Desconto',
                            'Receber',
                        ];
                    @endphp
                    <x-adminlte-datatable id="tabela-dup-sel" :heads="$heads" theme="light" striped hoverable>
                        @foreach($dupSelecionadas as $duplicata)
                            @php
                                $codigoFormatado = str_pad($duplicata->recdup_cod, 8, '0', STR_PAD_LEFT);
                                $sequenciaFormatada = str_pad($duplicata->recdup_seq, 2, '0', STR_PAD_LEFT);
                                $concatenado = $codigoFormatado . '/' . $sequenciaFormatada;

                                $valorAberto = $duplicata->recdup_vlr_dup - $duplicata->recdup_vlr_pag;
                                $valorRec = ($duplicata->recdup_vlr_bxa + $duplicata->recdup_vlr_jmt) - $duplicata->recdup_vlr_des;
                            @endphp
                            <tr>
                                <td>{{ $duplicata->recdup_cod }}</td>
                                <td>{{ $duplicata->recdup_seq }}</td>
                                <td>
                                    @if($estagio_app == 'PAINEL_PRINCIPAL')
                                    <nobr class="d-flex justify-content-center">
                                        
                                        <!-- Gera o botão que abre o Modal de inclusão / manutenção de duplicata -->
                                        <button class="btn btn-xs btn-outline-primary mx-1" title="Editar Registro" value="Edit" type="button" 
                                            onclick="openModalIncManDuplicata('{{ $duplicata->recdup_emp }}', '{{ $duplicata->recdup_cli }}', '{{ $duplicata->recdup_cod }}', '{{ $duplicata->recdup_seq }}', 'MAN')">
                                            <i class="fa fa-lg fa-fw fa-pen"></i>
                                        </button>
                                            
                                        <form method="post" action="{{ route('recebimentoDUP.delete', ['empresa' => $empresaREC, 'cliente' => $clienteREC, 'numDUP' => $duplicata->recdup_cod, 'seqDUP' => $duplicata->recdup_seq]) }}">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btn-xs btn-outline-danger mx-1" title="Excluir Registro" value="Delete" type="submit" >
                                                <i class="fa fa-lg fa-fw fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </nobr>
                                    @endif
                                </td>
                                <td>{{ $concatenado }}</td>
                                <td>{{ Helper::formataData($duplicata->recdup_dte) }}</td>
                                <td>{{ Helper::formataData($duplicata->recdup_dtv) }}</td>
                                <td>{{ Helper::formataValorMonetario($duplicata->recdup_vlr_dup) }}</td>
                                <td>{{ Helper::formataValorMonetario($valorAberto) }}</td>
                                <td>{{ Helper::formataValorMonetario($duplicata->recdup_vlr_jmt) }}</td>
                                <td>{{ Helper::formataValorMonetario($duplicata->recdup_vlr_des) }}</td>
                                <td>{{ Helper::formataValorMonetario($valorRec) }}</td>
                            </tr>
                        @endforeach
                    </x-adminlte-datatable>
                    <x-slot name="footerSlot">
                        <div style="float: right;">
                            @if($estagio_app == 'PAINEL_PRINCIPAL')
                            <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('recebimentoDUP.selecionarDUP', ['empresa' => $empresaREC, 'cliente' => $clienteREC, 'idRecebimento' => $idRecebimento]) }}'" label="Selecionar Duplicata" theme="" icon=""/>
                            <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('recebimento.painelRecebimento', ['empresa' => $empresaREC, 'cliente' => $clienteREC, 'idRecebimento' => $idRecebimento, 'tipoRecebimento' => 'DUP']) }}'" label="Receber" theme="" icon="fa-solid fa-sack-dollar"/>
                            @else 
                            <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('recebimentoDUP.reabreSelecao', ['empresa' => $empresaREC, 'cliente' => $clienteREC, 'idRecebimento' => $idRecebimento]) }}'" label="Reabrir Seleção" theme="" icon=""/>
                            @endif
                        </div>
                    </x-slot>
                </x-adminlte-card>

                <!-- Modal Único - Inclusão / Manutenção Duplicata -->
                <form method="post" action="{{ route('recebimentoDUP.updateModal', ['empresa' => $empresaREC, 'cliente' => $clienteREC, 'idRecebimento' => $idRecebimento]) }}" id="form-inc-man-dup" novalidate="novalidate">
                    @csrf
                    @method('post')
                    <x-adminlte-modal id="modalIncManDuplicata" title="Duplicata Selecionada" size="xl" theme="modal-nexus" icon="fa-solid fa-square-check" v-centered scrollable>
                        <!-- O conteúdo será carregado via AJAX -->
                        <div id="modalContentIncManDuplicata"></div>
                        <x-slot name="footerSlot">
                            <x-adminlte-button class="btn-nexus" type="submit" theme="" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                            <x-adminlte-button class="btn-nexus" theme="" label="Voltar" data-dismiss="modal"/>
                        </x-slot>
                    </x-adminlte-modal>
                </form>
                @endif
                <!-- ***** Final dos dados de Recebimento - Bloco: PAINEL_PRINCIPAL ***** -->

                <!-- ***** Início dos dados das Duplicatas erm Aberto para Seleção - Bloco: PAINEL_SEL_DUP ***** -->
                @if($estagio_app == 'PAINEL_SEL_DUP')
                <x-adminlte-card title="Duplicatas em Aberto para Seleção" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
                    @php
                        // Monta os dados da tabela do bloco
                        $heads = [
                            'Duplicata Cod. Hide',
                            'Duplicata Seq. Hide',
                            ['label' => '', 'no-export' => true, 'width' => 5],
                            'Duplicata',
                            'Emissão',
                            'Vencimento',
                            'Valor',
                            'Valor a Receber',
                        ];
                    @endphp
                    <x-adminlte-datatable id="tabela-dup-aberta" :heads="$heads" theme="light" striped hoverable>
                        @foreach($duplicatasAbertas as $duplicata)
                            @php
                                $codigoFormatado = str_pad($duplicata->conrec_codigo, 8, '0', STR_PAD_LEFT);
                                $sequenciaFormatada = str_pad($duplicata->conrec_sequencia, 2, '0', STR_PAD_LEFT);
                                $concatenado = $codigoFormatado . '/' . $sequenciaFormatada;

                                $calculo = HelperFinanceiro::calculaMoraDiaAtrasoDuplicata($duplicata->conrec_val_dup, $duplicata->conrec_val_pag, $duplicata->conrec_val_mora, $duplicata->conrec_dt_vencimento);
                                $valorRec = $calculo['saldo'] + $calculo['mora_total'];
                            @endphp
                            <tr>
                                <td>{{ $duplicata->conrec_codigo }}</td>
                                <td>{{ $duplicata->conrec_sequencia }}</td>
                                <td>
                                    @if($estagio_app == 'PAINEL_SEL_DUP')
                                    <nobr class="d-flex justify-content-center">
                                        <!-- Gera o botão que abre o Modal de inclusão / manutenção de duplicata -->
                                        <x-adminlte-button  type="button" class="btn btn-nexus btn-sm" label="Selecionar" theme="" icon=""
                                            onclick="openModalIncManDuplicata('{{ $duplicata->conrec_empresa }}', '{{ $duplicata->conrec_cliente }}', '{{ $duplicata->conrec_codigo }}', '{{ $duplicata->conrec_sequencia }}', 'INC')">
                                        </x-adminlte-button>
                                    </nobr>
                                    @endif
                                </td>
                                <td>{{ $concatenado }}</td>
                                <td>{{ Helper::formataData($duplicata->conrec_dt_emissao) }}</td>
                                <td>{{ Helper::formataData($duplicata->conrec_dt_vencimento) }}</td>
                                <td>{{ Helper::formataValorMonetario($duplicata->conrec_val_dup) }}</td>
                                <td>{{ Helper::formataValorMonetario($valorRec) }}</td>
                            </tr>
                        @endforeach
                    </x-adminlte-datatable>
                    <x-slot name="footerSlot">
                        <div style="float: right;">
                            <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('recebimentoDUP.painelPrincipalDUP', ['empresa' => $empresaREC, 'cliente' => $clienteREC, 'idRecebimento' => $idRecebimento]) }}'" label="Voltar" theme="" icon=""/>
                        </div>
                    </x-slot>
                </x-adminlte-card>

                <!-- Modal Único - Inclusão / Manutenção Duplicata -->
                <form method="post" action="{{ route('recebimentoDUP.insertModal', ['empresa' => $empresaREC, 'cliente' => $clienteREC, 'idRecebimento' => $idRecebimento]) }}" id="form-inc-man-dup" novalidate="novalidate">
                    @csrf
                    @method('post')
                    <x-adminlte-modal id="modalIncManDuplicata" title="Duplicata Selecionada" size="xl" theme="modal-nexus" icon="fa-solid fa-square-check" v-centered scrollable>
                        <!-- O conteúdo será carregado via AJAX -->
                        <div id="modalContentIncManDuplicata"></div>
                        <x-slot name="footerSlot">
                            <x-adminlte-button class="btn-nexus" type="submit" theme="" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                            <x-adminlte-button class="btn-nexus" theme="" label="Voltar" data-dismiss="modal"/>
                        </x-slot>
                    </x-adminlte-modal>
                </form>
                @endif
            </div>
            <!-- ***** Final dos dados das Duplicatas erm Aberto para Seleção - Bloco: PAINEL_SEL_DUP ***** -->

            <!-- ************************************************** Bloco do Lado Direito do Painel Principal ************************************************** -->
            <div class="col-md-6">
                @include('financeiro.recebimento.fin_pnl001_Recebimento')
            </div>

        </div>
        <x-slot name="footerSlot">
            <div style="float: right;">
                <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.filtroRecDUP') }}'" label="Voltar" theme="" icon=""/>
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

<style>
    #modalIncManDuplicata .modal-xl {
        max-width: 85% !important; /* Aumenta o tamanho do modal */
    }
</style>
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

        var tableCcrSel = $('#tabela-dup-sel').DataTable({
            pageLength: 10,
            searching: false,
            lengthChange: false,
            language: dataTableLangPtBR,
            order: [
                [0, 'asc'],
                [1, 'asc']
            ],
            pagingType: 'full_numbers',
            processing: true,
            columns: [
                { orderable: false, visible: false },
                { orderable: false, visible: false },
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
        });

        var tableCcrAberta = $('#tabela-dup-aberta').DataTable({
            pageLength: 10,
            searching: false,
            lengthChange: false,
            language: dataTableLangPtBR,
            order: [
                [0, 'asc'],
                [1, 'asc']
            ],
            pagingType: 'full_numbers',
            processing: true,
            columns: [
                { orderable: false, visible: false },
                { orderable: false, visible: false },
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
| Eventos de Modal
|--------------------------------------------------------------------------
-->
<script>
    
    /* ******************** Fazer requisição AJAX para carregar os dados do modal de inclusão / manutenção de duplicata ******************** */
    function openModalIncManDuplicata(emp, cli, num, seq, ori) {

        var url = "{{ route('recebimentoDUP.carregarDadosModalIncManDuplicata', [':emp',':cli',':num',':seq',':ori']) }}";
        url = url.replace(':emp', emp);
        url = url.replace(':cli', cli);
        url = url.replace(':num', num);
        url = url.replace(':seq', seq);
        url = url.replace(':ori', ori);

        $.ajax({
            url: url,
            type: 'GET',
            data: { 
                '_token': $('meta[name=csrf-token]').attr("content"),
                '_method': 'GET',
                "emp": emp,
                "cli": cli,
                "num": num,
                "seq": seq,
                "ori": ori
            },
            success: function(data) {
                // Preenche o conteúdo do modal
                $('#modalContentIncManDuplicata').html(data);
                
                // Abre o modal
                $('#modalIncManDuplicata').modal('show');

                /* *************** Por vir de uma requisição Ajax o JS do modal tem que ser adicionado aqui *************** */
                var estagioAPP = {!! json_encode($estagio_app) !!};

                /* **************************************** Eventos do bloco  - PAINEL_SEL_DUP e PAINEL_PRINCIPAL **************************************** */
                if( estagioAPP == 'PAINEL_SEL_DUP' || estagioAPP == 'PAINEL_PRINCIPAL' ){
                    
                    $('#saldoAbe').mask('#.##0,00', {reverse: true});
                    $('#valRec').mask('#.##0,00', {reverse: true});
                    $('#valTotRec').mask('#.##0,00', {reverse: true});
                    $('#moraDia').mask('#.##0,00', {reverse: true});
                    $('#jurMoraRec').mask('#.##0,00', {reverse: true});
                    $('#valDesc').mask('#.##0,00', {reverse: true});
                    $('#valPIS').mask('#.##0,00', {reverse: true});
                    $('#valCOFINS').mask('#.##0,00', {reverse: true});
                    $('#valCSLL').mask('#.##0,00', {reverse: true});
                    $('#valISSQN').mask('#.##0,00', {reverse: true});
                    $('#valIRRF').mask('#.##0,00', {reverse: true});
                    $('#valIRRFNS').mask('#.##0,00', {reverse: true});
                    $('#valTotImp').mask('#.##0,00', {reverse: true});

                    //Evento Inicial da APP
                    if( $('#tipoOpr').val() == 'MORA'){
                        $('.bloco-impostos').hide();
                        $('.dados-desconto').hide();
                    }else{
                        $('.dados-mora').hide();
                        $('.dados-desconto').show();

                        if( $('#tipoDesc').val() == 'FIN'){
                            $('.bloco-impostos').hide();
                        }else{
                            $('.bloco-impostos').show();
                        }
                    }

                    //Evento Change da APP
                    $('#tipoOpr').change(function(){

                        var valRec = $('#valRec').val();
                        $('#valTotRec').val(valRec);

                        if( $(this).val() == 'MORA' ) {
                            $('.bloco-impostos').hide();
                            $('.dados-desconto').hide();
                            $('.dados-mora').show();

                            $('#tipoDesc').val('FIN');
                            $('#valDesc').val('');

                            $('#valPIS').val('');
                            $('#valCOFINS').val('');
                            $('#valCSLL').val('');
                            $('#valISSQN').val('');
                            $('#valIRRF').val('');
                            $('#valIRRFNS').val('');
                            $('#valTotImp').val('0,00');
                        }else{
                            $('.dados-mora').hide();
                            $('.dados-desconto').show();
                            $('.bloco-impostos').hide();

                            $('#jurMoraRec').val('');
                        }
                    });

                    $('#tipoDesc').change(function(){

                        if( $(this).val() == 'FIN' ) {

                            $('.bloco-impostos').hide();

                            $('#valPIS').val('');
                            $('#valCOFINS').val('');
                            $('#valCSLL').val('');
                            $('#valISSQN').val('');
                            $('#valIRRF').val('');
                            $('#valIRRFNS').val('');
                            $('#valTotImp').val('0,00');
                        }else{
                            $('.bloco-impostos').show();
                        }
                    });

                    // Função para calcular a soma dos valores dos campos de impostos
                    function calcularSomaImpostos() {
                        var soma = 0;
                        // Itera sobre cada campo de imposto e soma os valores
                        $('#valPIS, #valCOFINS, #valCSLL, #valISSQN, #valIRRF, #valIRRFNS').each(function() {
                            var valor = parseFloat($(this).val().replace(/\./g, '').replace(',', '.'));
                            if (!isNaN(valor)) {
                                soma += valor;
                            }
                        });
                        // Atualiza o campo valTotImp com a soma calculada
                        $('#valTotImp').val(soma.toFixed(2).replace('.', ','));
                    }

                    // Adiciona eventos de mudança e digitação nos campos de impostos
                    $('#valPIS, #valCOFINS, #valCSLL, #valISSQN, #valIRRF, #valIRRFNS').on('input', function() {
                        calcularSomaImpostos();
                    });

                    // Função para limpar e converter valores para formato numérico
                    function limparValorBR(valor) {
                        return parseFloat(valor.replace(/\./g, '').replace(',', '.'));
                    }

                    // Função para atualizar o campo valTotRec com base no tipo de operação
                    function atualizarValTotRec() {
                        var valRec = limparValorBR($('#valRec').val());
                        var tipoOpr = $('#tipoOpr').val();
                        var valDesc = limparValorBR($('#valDesc').val());
                        var jurMoraRec = limparValorBR($('#jurMoraRec').val());
                        var valTotRec;

                        // Verifica o tipo de operação e calcula valTotRec
                        if (tipoOpr === "DESC") {
                            valTotRec = valRec - valDesc;
                        } else if (tipoOpr === "MORA") {
                            valTotRec = valRec + jurMoraRec;
                        } else {
                            valTotRec = valRec;
                        }

                        // Atualiza o campo valTotRec com o valor calculado
                        $('#valTotRec').val(valTotRec.toFixed(2).replace('.', ','));
                    }

                    // Adiciona evento de mudança (input) nos campos relevantes
                    $('#valRec, #valDesc, #jurMoraRec').on('input change', function() {
                        atualizarValTotRec();
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar dados:', error); 
            }
        });
    }
</script>

<!--
|--------------------------------------------------------------------------
| Eventos Iniciais da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() { 

        var estagioAPP = {!! json_encode($estagio_app) !!};

        /* **************************************** Eventos Iniciais do bloco  - PAINEL_SEL_DUP **************************************** */
        if( estagioAPP == 'PAINEL_SEL_DUP' ){
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

        var estagioAPP = {!! json_encode($estagio_app) !!};

        /* **************************************** Eventos Iniciais do bloco  - PAINEL_SEL_DUP **************************************** */
        if( estagioAPP == 'PAINEL_SEL_DUP' ){
            
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

    //Inserção da Conta Corrente no Recebimento
    $('#form-inc-man-dup').validate({
        rules: {
            valRec: {
                required: true,
                maxlength: 20
            },
            valDesc: {
                required: {
                    depends: function(element) {
                        return $('#tipoOpr').val() === 'DESC';
                    }
                },
                maxlength: 20
            }
        },
        messages: {
            valRec: {
                required:  "Por Favor informe o Valor do Recebimento",
                maxlength: "Limite máximo do valor é de 15 digitos"
            },
            valDesc: {
                required:  "Por Favor informe o Valor do Desconto",
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

            function limparValorBR(valor) {
                return parseFloat(valor.replace(/\./g, '').replace(',', '.')) || 0;
            }

            let valRec = limparValorBR($('#valRec').val());
            let saldoAbe = limparValorBR($('#saldoAbe').val());
            let tipoOpr = $('#tipoOpr').val();
            let tipoDesc = $('#tipoDesc').val();
            let valDesc = limparValorBR($('#valDesc').val());
            let valTotImp = limparValorBR($('#valTotImp').val());

            // 1. Verifica se Valor a Receber foi informado
            if (isNaN(valRec) || valRec === 0) {
                Swal.fire({
                    confirmButtonColor: "#007bff",
                    title: "Aviso!",
                    text: "Obrigatório informar o Valor a Receber!",
                    icon: "info"
                });
                return false;
            }

            //1.2 Verifica se o valor é negativo
            if (valRec < 0) {
                Swal.fire({
                    confirmButtonColor: "#007bff",
                    title: "Aviso!",
                    text: "O Valor a Receber não pode ser menor do que zero!",
                    icon: "info"
                });
                return false;
            }

            // 2. Verifica se Valor a Receber é maior que Saldo em Aberto
            if (valRec > saldoAbe) {
                Swal.fire({
                    confirmButtonColor: "#007bff",
                    title: "Aviso!",
                    text: "O Valor a Receber não pode ser maior que o Saldo em Aberto!",
                    icon: "info"
                });
                return false;
            }

            // 3. Se a operação for Desconto, valida o campo valDesc
            if (tipoOpr === "DESC") {

                // 3.1 Verifica se informou o valor do desconto
                if (isNaN(valDesc) || valDesc === 0) {
                    Swal.fire({
                        confirmButtonColor: "#007bff",
                        title: "Aviso!",
                        text: "Obrigatório informar o Valor do Desconto!",
                        icon: "info"
                    });
                    return false;
                }

                // 3.2 Verifica se o valor do desconto é maior que o valor a receber
                if (valDesc > valRec) {
                    Swal.fire({
                        confirmButtonColor: "#007bff",
                        title: "Aviso!",
                        text: "O Valor do Desconto não pode ser maior que o Valor a Receber!",
                        icon: "info"
                    });
                    return false;
                }

                // 3.3 Se o tipo de desconto for RET, verifica o valor total do imposto
                if (tipoDesc === "RET") {

                    // 3.3.1 Verifica se o valor total do imposto é diferente do valor do desconto
                    if (valTotImp !== valDesc) {
                        Swal.fire({
                            confirmButtonColor: "#007bff",
                            title: "Aviso!",
                            text: "O Valor Total de Impostos deve ser igual ao Valor de Desconto!",
                            icon: "info"
                        });
                        return false;
                    }
                }
            }

            // 4. Se a operação for Mora, valida o valor a receber em relação aos juros
            if (tipoOpr === "MORA") {
                let jurMoraRec = limparValorBR($('#jurMoraRec').val());

                // 4.1 Verifica se o valor a receber é menor que o saldo em aberto e menor ou igual aos juros de mora
                if (valRec < saldoAbe && valRec <= jurMoraRec) {
                    Swal.fire({
                        confirmButtonColor: "#007bff",
                        title: "Aviso!",
                        text: "Para pagamento parcial, o Valor a Receber não pode ser menor ou igual ao valor dos Juros de Mora!",
                        icon: "info"
                    });
                    return false;
                }
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
            text: "{{ session('success2') }}",
            icon: "success",
            customClass: {
                icon: "no-before-icon",
            }
        });
    @endif
</script>
@stop

