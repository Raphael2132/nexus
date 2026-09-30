@extends('adminlte::page')

@section('title', 'Cadastro de Prestadores')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Cadastros</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('cadastroPrestador.index')}}">Prestadores</a>
            </li>
            <li class="breadcrumb-item active">Prestadores Cadastrados</li>
        </ol>
    </div>
</div>
@stop


@section('content')

@php
    $heads = [
        'Empresa',
        'Área',
        'Setor',
        'Prestador',
        'CPF',
        'Acessa Sis.',
        'Usuário Sis.',
        'Status',
        ['label' => 'Opções', 'no-export' => true, 'width' => 10],
    ];

    //Variavel para o filtro
    $arraySelEmp = HelperArraySelect::arrayEmpresas(2,1);
    $arraySelArea = HelperArraySelect::arrayAreaSetCadastrado(2,1);
    $arraySelSet = HelperArraySelect::arraySetor(2,1);

    if($tipo == 'A'){
        $titulo = 'Prestadores Ativos Cadastrados';
    }elseif($tipo == 'D'){
        $titulo = 'Prestadores Demitidos Cadastrados';
    }else{
        $titulo = 'Prestadores Cadastrados';
    }
@endphp

<x-adminlte-card :title="$titulo" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
    <x-adminlte-datatable id="tabela-prestador" :heads="$heads" theme="light" striped hoverable>
        @foreach ($prestadores as $prestador)
            <tr>
                @php 
                    if($prestador->prestador_acesso_sis == 'S'){
                        $usuario = HelperFormatSelect::formataUsuarioCodigoNome($prestador->prestador_usuario_cod, $prestador->prestador_empresa);
                    }else{
                        $usuario = '';
                    }
                @endphp
                <td>{{ HelperFormatSelect::formataEmpresaCodigoNome($prestador->prestador_empresa) }}</td>
                <td>{{ HelperFormatSelect::formataAreaCodigoDesc($prestador->prestador_are) }}</td>
                <td>{{ HelperFormatSelect::formataSetorCodigoDesc($prestador->prestador_empresa, $prestador->prestador_are, $prestador->prestador_set) }}</td>
                <td>{{ $prestador->prestador_codigo.' - '.$prestador->prestador_nome }}</td>
                <td>{{ Helper::mascaraCPF($prestador->prestador_cpf) }}</td>
                <td>{{ Helper::formataSimNao($prestador->prestador_acesso_sis) }}</td>
                <td>{{ $usuario }}</td>
                <td>{{ Helper::formataUsuarioStatus($prestador->prestador_status) }}</td>
                <td>
                    <nobr class="d-flex justify-content-center">
                        <form method="get" action="{{route('cadastroPrestador.edit', ['cadastroPrestador' => $prestador->prestador_codigo])}}" style="float: left;">
                            @csrf
                            @method('get')
                            <!-- Informa o Tipo da Busca para a Edição -->
                            <input id="tipo" type="hidden" value="{{ $tipo }}" name="tipo">

                            <button class="btn btn-xs btn-outline-primary mx-1" title="Editar Registro" value="Edit" type="submit">
                                <i class="fa fa-lg fa-fw fa-pen"></i>
                            </button>
                        </form>
                        <form method="post" action="{{ route('cadastroPrestador.destroy', ['cadastroPrestador' => $prestador]) }}" style="float: left;">
                            @csrf 
                            @method('delete')
                            <button class="btn btn-xs btn-outline-danger mx-1" title="Excluir Registro" value="Delete" type="submit" >
                                <i class="fa fa-lg fa-fw fa-trash-can"></i>
                            </button>
                        </form>
                    </nobr>
                </td>                
            </tr>
        @endforeach
    </x-adminlte-datatable>
    <x-slot name="footerSlot">
        <div class="d-flex justify-content-between w-100">
            <form method="get" action="{{ route('cadastroPrestador.create') }}">
                @csrf 
                <x-adminlte-button class="btn-nexus" label="Novo Prestador" theme="" icon="fas fa-user-plus" type="submit"/>
            </form>
            <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('cadastroPrestador.index') }}'" label="Voltar" theme="" icon=""/>
        </div>
    </x-slot>
</x-adminlte-card>
@stop

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
        var table = $('#tabela-prestador').DataTable({
            dom: getDatatableDom(),
            buttons: getDatatableButtons(),
            lengthMenu: [5, 10, 25, 50, 100],
            pageLength: 10,
            language: dataTableLangPtBR,
            order: [
                [0, 'asc'],
                [1, 'asc'],
                [2, 'asc'],
                [3, 'asc']
            ],
            pagingType: 'full_numbers',
            processing: true,
            columns: [
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                { orderable: false }
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
                customSearchingField('#tabela-prestador_filter');

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
                $('#tabela-prestador_wrapper .btn-datatable-dir .tool-bar').prepend(buttonsTooBarHTML);

                /* ******************** Cards dos Botões Principais ******************** */

                /* ********** Botões do Card da Quebra ********** */
                let btnQuebraHTML = `
                    <a class="btn btn-outline-nexus groupCol mr-2" data-column="0" href="#">Empresa</a>
                    <a class="btn btn-outline-nexus groupCol mr-2" data-column="1" href="#">Área</a>
                    <a class="btn btn-outline-nexus groupCol mr-2" data-column="2" href="#">Setor</a>
                `;

                /* ********** Monta o Card da Quebra ********** */
                let cardQuebraHTML = getCardQuebras(btnQuebraHTML);

                /* ********** Adiciona o Card da Quebra ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-prestador_wrapper .linha-cards-menu').append(cardQuebraHTML);

                /* ********** Botões do Card da Coluna ********** */
                let btnColunaHTML = `
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="0" href="#">Empresa</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="1" href="#">Área</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="2" href="#">Setor</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="3" href="#">Prestador</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="4" href="#">CPF</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="5" href="#">Acessa Sis.</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="6" href="#">Usuário Sis.</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="7" href="#">Status</a>
                `;

                /* ********** Monta o Card da Coluna ********** */
                let cardColunaHTML = getCardColunas(btnColunaHTML);

                /* ********** Adiciona o Card da Coluna ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-prestador_wrapper .linha-cards-menu').append(cardColunaHTML);

                /* ********** Campos do Card de Filtro ********** */
                let fieldFiltroHTML = `
                    <div class="row">
                        <x-adminlte-select name="filterEmp" label="Empresa" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="$arraySelEmp" empty-option="Selecione..."/>
                        </x-adminlte-select>
                        <x-adminlte-select name="filterArea" label="Área" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="$arraySelArea" empty-option="Selecione..."/>
                        </x-adminlte-select>
                        <x-adminlte-select name="filterSet" label="Setor" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="$arraySelSet" empty-option="Selecione..."/>
                        </x-adminlte-select>
                        <x-adminlte-input name="filterPrestador" type="text" label="Prestador" class="form-control" placeholder="Filtrar Prestador" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                    </div>
                    <div class="row">
                        <x-adminlte-input name="filterCPF" type="text" label="CPF" class="form-control" placeholder="Filtrar CPF" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-select name="filterAcessSis" label="Acessa Sis." igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="['Sim' => 'Sim', 'Não' => 'Não']" empty-option="Selecione..."/>
                        </x-adminlte-select>
                        <x-adminlte-input name="filterUsuario" type="text" label="Usuário Sis." class="form-control" placeholder="Filtrar Usuário Sis." igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-select name="filterSts" label="Status" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="['Ativo' => 'Ativo', 'Demitido' => 'Demitido']" empty-option="Selecione..."/>
                        </x-adminlte-select>
                    </div>
                `;

                /* ********** Monta o Card de Filtro ********** */
                let cardFiltroHTML = getCardFiltros(fieldFiltroHTML);

                /* ********** Adiciona o Card de Filtro ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-prestador_wrapper .linha-cards-menu').append(cardFiltroHTML);
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
                $('#tabela-prestador_wrapper').each(function() {
                    var $wrapper = $(this);  // Armazena o contexto atual do wrapper

                    $wrapper.find('#filterEmp').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(0).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterArea').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(1).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterSet').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(2).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterPrestador').on('keyup', function() {
                        table.column(3).search(this.value).draw();
                    });

                    $wrapper.find('#filterCPF').on('keyup', function() {
                        table.column(4).search(this.value).draw();
                    });

                    $wrapper.find('#filterAcessSis').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(5).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterUsuario').on('keyup', function() {
                        table.column(6).search(this.value).draw();
                    });

                    $wrapper.find('#filterSts').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(7).search(selectedValue).draw();
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
            [1, 'asc'],
            [2, 'asc'],
            [3, 'asc']
        ];

        // Chama a função e passa as variáveis
        setupActionsQuebra(table, '#tabela-prestador_wrapper', '#tabela-prestador', originalOrder, groupColumns);

        /* ------------------------------ Final dos Eventos de Controle do Card de Quebra e Funcionalidades ------------------------------ */

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Controle do Card de Colunas e Funcionalidades
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Chama a função para adicionar os eventos de visibilidade das colunas
        setupActionsColuna(table, '#tabela-prestador_wrapper');

        /* ------------------------------ Final dos Eventos de Controle do Card de Colunas e Funcionalidades ------------------------------ */

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Controle do Card de Filtro e Funcionalidades
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Chama a função de configuração de filtros
        setupActionsFiltro(table, '#tabela-prestador_wrapper');

        /* ------------------------------ Final dos Eventos de Controle do Card de Filtro e Funcionalidades ------------------------------ */

    });

    /* ------------------------------ Final da Inicialização do Datatable ------------------------------ */
</script>
@stop
