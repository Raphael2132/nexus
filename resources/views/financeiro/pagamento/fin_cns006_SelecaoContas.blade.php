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
            <li class="breadcrumb-item active">Seleção de Contas</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-12">
        @csrf 
        @method('post')
        <x-adminlte-card title="Contas para Seleção do Pagamento {{ $appOrigem === 'LOTE' ? ' em Lote' : ' de Adiantamento de Fornecedor' }}" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
            @php
                // Monta os dados da tabela do bloco
                $heads = [
                    ['label' => '', 'no-export' => true, 'width' => 5],
                    ['label' => '', 'no-export' => true, 'width' => 5],
                    'Tipo da Conta',
                    'Cliente',
                    'Conta',
                    'SubTipo',
                    'Data de Vencimento',
                    'Valor da Conta',
                    'Valor a Pagar',
                ];

                $tipoConta = 'AC';
                $arraySelSubTip = HelperArraySelect::arraySubTipoCC(2,1,$tipoConta);
            @endphp
            <x-adminlte-datatable id="table-contas" :heads="$heads" theme="light" striped hoverable>
                @foreach($dadosContasSel as $contas)
                    @php 
                        $scc = HelperFormatSelect::formataSubTipoCC($contas->conta_tipo,$contas->conta_subtipo);

                        if($contas->conta_tipo == 'CO'){
                            $cliente = HelperFormatSelect::formataRazaoCodNom($contas->conta_responsavel);
                        }else{
                            $cliente = HelperFormatSelect::formataClientes($contas->conta_responsavel);
                        }
                        $tipoConta = HelperFormatSelect::formataTipoCC($contas->conta_tipo);

                        $cnt_cc = DB::table('financeiro_pagamento_contas_correntes')
                        ->where('pagcct_cli', $clienteLote)
                        ->where('pagcct_tip_cct', $contas->conta_tipo)
                        ->where('pagcct_num_cct', $contas->conta_num_conta)
                        ->where('pagcct_emp', $empresaLote)
                        ->where('pagcct_cod_pag', $idPagamento)
                        ->count();
                        
                        $saldo = $contas->conta_valor - $contas->conta_val_rec;
                    @endphp
                    <tr>
                        <td class="text-center align-middle">
                            @if($cnt_cc == 0)
                                <nobr class="d-flex justify-content-center">
                                    <a class="btn btn-nexus btn-sm" title="Selecionar NF"
                                    href="{{ route('pagamentoPCC.insertCCLote', ['appOrigem' => $appOrigem, 'numCC' => $contas->conta_num_conta, 'tipoCC' => $contas->conta_tipo, 'saldoCC' => $saldo]) }}">
                                    Selecionar
                                    </a>
                                </nobr>
                            @else
                                <nobr class="d-flex justify-content-center">
                                    <span class="text-muted" title="Conta Corrente Selecionada">
                                        <i class="fa-solid fa-circle-check fa-lg text-success"></i>
                                    </span>
                                </nobr>
                            @endif
                        </td>
                        <td class="text-center align-middle">
                            @if($cnt_cc == 0)
                                <nobr class="d-flex justify-content-center">
                                    <span class="text-muted" title="Conta Corrente Não Selecionada">
                                        <i class="fa-solid fa-circle-xmark fa-lg text-danger"></i>
                                    </span>
                                </nobr>
                            @else
                                <nobr class="d-flex justify-content-center">
                                    <a class="btn btn-nexus btn-sm" title="Selecionar NF"
                                    href="{{ route('pagamentoPCC.deleteCCLote', ['appOrigem' => $appOrigem, 'numCC' => $contas->conta_num_conta, 'tipoCC' => $contas->conta_tipo]) }}">
                                    Desmarcar
                                    </a>
                                </nobr>
                            @endif
                        </td>
                        <td>{{ $tipoConta }}</td>
                        <td>{{ $cliente }}</td>
                        <td>{{ $contas->conta_num_conta }}</td>
                        <td>{{ $scc }}</td>
                        <td>{{ Helper::formataData($contas->conta_dt_vencimento) }}</td>
                        <td>{{ Helper::formataValorMonetario($contas->conta_valor) }}</td>
                        <td>{{ Helper::formataValorMonetario($saldo) }}</td>
                    </tr>
                @endforeach
            </x-adminlte-datatable>
            <x-slot name="footerSlot">
                @php 
                    $cnt_tot_cc = DB::table('financeiro_pagamento_contas_correntes')
                    ->where('pagcct_emp', $empresaLote)
                    ->where('pagcct_cod_pag', $idPagamento)
                    ->count();
                @endphp
                <div class="d-flex justify-content-between w-100">
                    @if($cnt_tot_cc > 0)
                        <a class="btn btn-nexus" title="Fechar Lote" href="{{ route('pagamentoPCC.fecharLote',['appOrigem' => $appOrigem]) }}">
                            <i class="fa-regular fa-file-check"></i>
                            Fechar Lote
                        </a>
                    @else
                        <div class="invisible">placeholder</div>
                    @endif
                    <a class="btn btn-nexus" title="Voltar" href="{{ route('home.homePCCLote',['appOrigem' => $appOrigem]) }}">
                        Voltar
                    </a>
                </div>
            </x-slot>
        </x-adminlte-card>
    </div>
</div>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('css')
@stop

@section('js')
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

        /* ********** Inicializa o Datatable ********** */
        var table = $('#table-contas').DataTable({
            dom: getDatatableDom(),
            buttons: getDatatableButtons(),
            lengthMenu: [5, 10, 25, 50, 100],
            pageLength: 10,
            language: dataTableLangPtBR,
            order: [
                [2, 'asc'],
                [3, 'asc'],
                [4, 'asc']
            ],
            pagingType: 'full_numbers',
            processing: true,
            columns: [
                { orderable: false },
                { orderable: false },
                { orderable: true },
                { orderable: true },
                { orderable: true },
                { orderable: true },
                { orderable: true },
                { orderable: true },
                { orderable: true }
            ],
            initComplete: function() {

                /* *****
                |----------------------------------------------------------------------------------------------------
                | Altera o Campo Searching Original
                |----------------------------------------------------------------------------------------------------
                |
                | Alteramos a aparencia do input encapsulando ele dentro de input-group estilizado para o Nexus
                |
                ***** */

                // Chama a função para customizar o filtro da tabela com base no ID
                // #id = ID_TABELA_filter
                customSearchingField('#table-contas_filter');

                /* ------------------------------ Final dos Eventos Altera o Campo Searching Original ------------------------------ */

                /* *****
                |----------------------------------------------------------------------------------------------------
                | Cards e Botões da Barra de Ferramentas do Datatable
                |----------------------------------------------------------------------------------------------------
                |
                | Adicionamos aqui a criação dos Cards e Botões utilizados na barra de ferramentas do datatable
                |
                ***** */

                /* ******************** Botões Principais ******************** */

                /* ********** Criação dos botões principais da barra de ferramentas ********** */
                let buttonsTooBarHTML = getButtonsTollBar(['colunas','filtro']);

                /* ********** Adicionando os botões na barra de ferramentas ********** */
                // #id = ID_TABELA_wrapper
                $('#table-contas_wrapper .btn-datatable-dir .tool-bar').prepend(buttonsTooBarHTML);

                /* ******************** Cards dos Botões Principais ******************** */

                /* ********** Botões do Card da Coluna ********** */
                let btnColunaHTML = `
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="2" href="#">Tipo da Conta</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="3" href="#">Cliente</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="4" href="#">Conta</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="5" href="#">Subtipo</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="6" href="#">Data de Vencimento</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="7" href="#">Valor da Conta</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="8" href="#">Valor a Pagar</a>
                `;

                /* ********** Monta o Card da Coluna ********** */
                let cardColunaHTML = getCardColunas(btnColunaHTML);

                /* ********** Adiciona o Card da Coluna ********** */
                // #id = ID_TABELA_wrapper
                $('#table-contas_wrapper .linha-cards-menu').append(cardColunaHTML);

                /* ********** Campos do Card de Filtro ********** */
                let fieldFiltroHTML = `
                    <div class="row">
                        <x-adminlte-input name="filterCliente" type="text" label="Cliente" class="form-control" placeholder="Filtrar Cliente" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterConta" type="text" label="Conta" class="form-control" placeholder="Filtrar Conta" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterData" type="text" label="Data de Vencimento" class="form-control" placeholder="Filtrar Data de Vencimento" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterValor" type="text" label="Valor da Conta" class="form-control" placeholder="Filtrar Valor da Conta" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                    </div>
                    <div class="row">
                        <x-adminlte-input name="filterSaldo" type="text" label="Valor a Pagar" class="form-control" placeholder="Filtrar Valor a Pagar" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                    </div>
                `;

                /* ********** Monta o Card de Filtro ********** */
                let cardFiltroHTML = getCardFiltros(fieldFiltroHTML);

                /* ********** Adiciona o Card de Filtro ********** */
                // #id = ID_TABELA_wrapper
                $('#table-contas_wrapper .linha-cards-menu').append(cardFiltroHTML);
                /* ------------------------------ Final dos Eventos Cards e Botões da Barra de Ferramentas do Datatable ------------------------------ */
                
                /* *****
                |----------------------------------------------------------------------------------------------------
                | Eventos dos Filtros Individuais
                |----------------------------------------------------------------------------------------------------
                |
                | Esses eventos devem ser feitos ao criar a tabela no "initComplete" para funcionar a busca dos dados
                |
                ***** */

                // Escopo para a tabela trabalhada
                // #id = ID_TABELA_wrapper
                $('#table-contas_wrapper').each(function() {
                    var $wrapper = $(this);  // Armazena o contexto atual do wrapper

                    $wrapper.find('#filterCliente').on('keyup', function() {
                        table.column(3).search(this.value).draw();
                    });

                    $wrapper.find('#filterConta').on('keyup', function() {
                        table.column(4).search(this.value).draw();
                    });

                    $wrapper.find('#filterData').on('keyup', function() {
                        table.column(6).search(this.value).draw();
                    });

                    $wrapper.find('#filterValor').on('keyup', function() {
                        table.column(7).search(this.value).draw();
                    });

                    $wrapper.find('#filterSaldo').on('keyup', function() {
                        table.column(8).search(this.value).draw();
                    });

                });
                /* ------------------------------ Final dos Eventos dos Filtros Individuais ------------------------------ */
                
            }
        });
        
        /* ------------------------------ Final a Inicialização do Datatable ------------------------------ */

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Controle do Card de Colunas e Funcionalidades
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Chama a função para adicionar os eventos de visibilidade das colunas
        setupActionsColuna(table, '#table-contas_wrapper');

        /* ------------------------------ Final dos Eventos de Controle do Card de Colunas e Funcionalidades ------------------------------ */

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Controle do Card de Filtro e Funcionalidades
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Chama a função de configuração de filtros
        setupActionsFiltro(table, '#table-contas_wrapper');

        /* ------------------------------ Final dos Eventos de Controle do Card de Filtro e Funcionalidades ------------------------------ */
    });

    /* ------------------------------ Final da Inicialização do Datatable ------------------------------ */
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
