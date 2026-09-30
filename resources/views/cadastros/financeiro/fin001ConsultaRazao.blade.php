@extends('adminlte::page')

@section('title', 'Cadastro de Razão')

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
            @if($tipoRazao == 'BA')
            <li class="breadcrumb-item active">Bancos Cadastrados</li>
            @elseif($tipoRazao == 'CX')
            <li class="breadcrumb-item active">Caixas Cadastrados</li>
            @else 
            <li class="breadcrumb-item active">Tesourarias Cadastradas</li>
            @endif
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12"> 
        @php
            $heads = [
                'Empresa',
                'Razão',
                'Tipo',
                'Ano',
                'Saldo Atual',
                ['label' => 'Opções', 'no-export' => true, 'width' => 5],
            ];

            //Variavel para o filtro
            $arraySelEmp = HelperArraySelect::arrayEmpresas(2,1);

            if($tipoRazao == 'BA'){
                $titulo = "Bancos Cadastrados";
            }elseif($tipoRazao == 'CX'){
                $titulo = "Caixas Cadastrados";
            }elseif($tipoRazao == 'TE'){ 
                $titulo = "Tesourarias Cadastradas";
            }else{ 
                $titulo = "Cartão de Crédito Pendente";
            }
        @endphp
        <x-adminlte-card  :title="$titulo" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
            <x-adminlte-datatable id="tabela-home" :heads="$heads" theme="light" striped hoverable>
                @foreach ($razoes as $razao)
                    <tr>
                        @php 
                            $empresa = HelperFormatSelect::formataEmpresaCodigoNome($razao->razao_empresa);

                            $tipoRazao = Helper::formataTipoRazao($razao->razao_tipo);

                            $SaldoAtual = Helper::formataValorMonetario($razao->razao_saldo_atu);
                        @endphp

                        <td>{{ $empresa }}</td>
                        <td>{{ $razao->razao_codigo.' - '.$razao->razao_nome }}</td>
                        <td>{{ $tipoRazao }}</td>
                        <td>{{ $razao->razao_ano }}</td>
                        <td>{{ $SaldoAtual }}</td>
                        <td>
                            <nobr class="d-flex justify-content-center">
                                <form method="get" action="{{ route('cadastroRazao.edit', ['cadastroRazao' => $razao->razao_codigo]) }}" style="float: left;">
                                    @csrf
                                    <button class="btn btn-xs btn-outline-primary mx-1" title="Editar Registro" value="Edit" type="submit">
                                        <i class="fa fa-lg fa-fw fa-pen"></i>
                                    </button>
                                </form>
                                @if(Auth::user()->usuario_tipo == 'M')
                                <form method="post" action="{{ route('cadastroRazao.destroy', ['cadastroRazao' => $razao]) }}" style="float: left;">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-xs btn-outline-danger mx-1" title="Excluir Registro" value="Delete" type="submit" >
                                        <i class="fa fa-lg fa-fw fa-trash-can"></i>
                                    </button>
                                </form>
                                @endif
                            </nobr>
                        </td>                
                    </tr>
                @endforeach
            </x-adminlte-datatable>
            <x-slot name="footerSlot">
                <div class="d-flex justify-content-between w-100">
                    <form method="get" action="{{ route('cadastroRazao.create') }}">
                        <x-adminlte-button class="btn-nexus" label="Novo Razão" theme="" icon="fa-solid fa-plus" type="submit"/>
                    </form>
                    <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('cadastroRazao.index') }}'" label="Voltar" theme="" icon=""/>
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
        var groupColumns = [];

        /* ********** Inicializa o Datatable ********** */
        var table = $('#tabela-home').DataTable({
            dom: getDatatableDom(),
            buttons: getDatatableButtons(),
            lengthMenu: [5, 10, 25, 50, 100],
            pageLength: 10,
            language: dataTableLangPtBR,
            order: [
                [0, 'asc'],
                [2, 'asc'],
                [3, 'asc'],
                [1, 'asc']
            ],
            pagingType: 'full_numbers',
            processing: true,
            columns: [
                null,
                null,
                null,
                null,
                null,
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
                customSearchingField('#tabela-home_filter');

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
                let buttonsTooBarHTML = getButtonsTollBar(['quebra','colunas','filtro']);

                /* ********** Adicionando os botões na barra de ferramentas ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-home_wrapper .btn-datatable-dir .tool-bar').prepend(buttonsTooBarHTML);

                /* ******************** Cards dos Botões Principais ******************** */

                /* ********** Botões do Card da Quebra ********** */
                let btnQuebraHTML = `
                    <a class="btn btn-outline-nexus groupCol mr-2" data-column="0" href="#">Empresa</a>
                    <a class="btn btn-outline-nexus groupCol mr-2" data-column="2" href="#">Tipo do Razão</a>
                    <a class="btn btn-outline-nexus groupCol mr-2" data-column="3" href="#">Ano</a>
                `;

                /* ********** Monta o Card da Quebra ********** */
                let cardQuebraHTML = getCardQuebras(btnQuebraHTML);

                /* ********** Adiciona o Card da Quebra ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-home_wrapper .linha-cards-menu').append(cardQuebraHTML);

                /* ********** Botões do Card da Coluna ********** */
                let btnColunaHTML = `
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="0" href="#">Empresa</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="1" href="#">Razao</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="2" href="#">Tipo Razão</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="3" href="#">Ano</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="4" href="#">Saldo Atual</a>
                `;

                /* ********** Monta o Card da Coluna ********** */
                let cardColunaHTML = getCardColunas(btnColunaHTML);

                /* ********** Adiciona o Card da Coluna ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-home_wrapper .linha-cards-menu').append(cardColunaHTML);

                /* ********** Campos do Card de Filtro ********** */
                let fieldFiltroHTML = `
                    <div class="row">
                        <x-adminlte-select name="filterEmp" label="Empresa" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="$arraySelEmp" empty-option="Selecione..."/>
                        </x-adminlte-select>
                        <x-adminlte-input name="filterRazao" type="text" label="Razão" class="form-control" placeholder="Filtrar Razão" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-select name="filterTip" label="Tipo do Razão" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="['Banco' => 'Banco', 'Caixa' => 'Caixa', 'Tesouraria' => 'Tesouraria']" empty-option="Selecione..."/>
                        </x-adminlte-select>
                        <x-adminlte-input name="filterAno" type="text" label="Razão" class="form-control" placeholder="Filtrar Ano" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                    </div>
                    <div class="row">
                        <x-adminlte-input name="filterVlrAtu" type="text" label="Saldo Atual" class="form-control" placeholder="Filtrar Saldo Atual" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                    </div>
                `;

                /* ********** Monta o Card de Filtro ********** */
                let cardFiltroHTML = getCardFiltros(fieldFiltroHTML);

                /* ********** Adiciona o Card de Filtro ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-home_wrapper .linha-cards-menu').append(cardFiltroHTML);
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
                $('#tabela-home_wrapper').each(function() {
                    var $wrapper = $(this);  // Armazena o contexto atual do wrapper

                    $wrapper.find('#filterEmp').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(0).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterRazao').on('keyup', function() {
                        table.column(1).search(this.value).draw();
                    });

                    $wrapper.find('#filterTip').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(2).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterAno').on('keyup', function() {
                        table.column(3).search(this.value).draw();
                    });

                    $wrapper.find('#filterVlrAtu').on('keyup', function() {
                        table.column(4).search(this.value).draw();
                    });
                });
                /* ------------------------------ Final dos Eventos dos Filtros Individuais ------------------------------ */
                
            }
        });

        /* ------------------------------ Final a Inicialização do Datatable ------------------------------ */

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Controle do Card de Quebra e Funcionalidades
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Ordenação original (Secundária além do agrupamento)
        var originalOrder = [
            [0, 'asc'],
            [2, 'asc'],
            [3, 'asc'],
            [1, 'asc']
        ];

        // Chama a função e passa as variáveis
        setupActionsQuebra(table, '#tabela-home_wrapper', '#tabela-home', originalOrder, groupColumns);

        /* ------------------------------ Final dos Eventos de Controle do Card de Quebra e Funcionalidades ------------------------------ */

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Controle do Card de Colunas e Funcionalidades
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Chama a função para adicionar os eventos de visibilidade das colunas
        setupActionsColuna(table, '#tabela-home_wrapper');

        /* ------------------------------ Final dos Eventos de Controle do Card de Colunas e Funcionalidades ------------------------------ */

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Controle do Card de Filtro e Funcionalidades
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Chama a função de configuração de filtros
        setupActionsFiltro(table, '#tabela-home_wrapper');

        /* ------------------------------ Final dos Eventos de Controle do Card de Filtro e Funcionalidades ------------------------------ */

    });

    /* ------------------------------ Final da Inicialização do Datatable ------------------------------ */
</script>
@stop
