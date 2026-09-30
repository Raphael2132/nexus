@extends('adminlte::page')

@section('title', 'Cadastro de Clientes')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Cadastros</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Clientes</li>
        </ol>
    </div>
</div>
@stop

@section('content')

    <div class="row">
        <div class="col-md-3"> 
            <x-adminlte-small-box :title="$cliTot" text="Total de Clientes" icon="fas fa-users" theme="info" url="{{ route('cadastroCliente.show',['cadastroCliente' => 'T']) }}" url-text="Detalhes de Todos Clientes"/>
        </div>
        <div class="col-md-3"> 
            <x-adminlte-small-box :title="$cliFisico" text="Pessoa Física" icon="fas fa-user" theme="success" url="{{ route('cadastroCliente.show',['cadastroCliente' => 'F']) }}" url-text="Detalhes de Pessoas Físicas"/>
        </div>
        <div class="col-md-3"> 
            <x-adminlte-small-box :title="$cliJuridico" text="Pessoa Jurídica" icon="fas fa-user-tie" theme="danger" url="{{ route('cadastroCliente.show',['cadastroCliente' => 'J']) }}" url-text="Detalhes de Pessoas Jurídicas"/>
        </div>
        <div class="col-md-3"> 
            <x-adminlte-small-box title="Cadastro" text="Clientes" icon="fas fa-user-plus" theme="primary" url="{{ route('cadastroCliente.create') }}" url-text="Cadastrar Cliente"/>
        </div>
    </div>
 
    <div class="row d-flex align-items-stretch">
        <div class="col-md-3">
            <x-adminlte-info-box title="Clientes" :text="$clientesTot" icon="fa-solid fa-user-group" url="{{ route('cadastroCliente.show',['cadastroCliente' => 'CLI']) }}" theme="gradient-teal" class="mb-3"/>
            <x-adminlte-info-box title="Clientes Fisicos" :text="$clientesFis" icon="fa-solid fa-user-vneck" url="{{ route('cadastroCliente.show',['cadastroCliente' => 'CLF']) }}" theme="gradient-teal" class="mb-3"/>
            <x-adminlte-info-box title="Clientes Juridicos" :text="$clientesJur" icon="fa-solid fa-user-tie" url="{{ route('cadastroCliente.show',['cadastroCliente' => 'CLJ']) }}" theme="gradient-teal" class="mb-3"/>
            <x-adminlte-info-box title="Fornecedores" :text="$clientesFor" icon="fa-solid fa-user-tag" url="{{ route('cadastroCliente.show',['cadastroCliente' => 'FOR']) }}" theme="gradient-teal"/>
        </div>
        
        <div class="col-md-5 d-flex flex-column">
            <x-adminlte-card title="Novos Clientes nos Últimos Seis Meses" icon="fa-solid fa-chart-column" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable class="flex-fill" style="height: 360px;">
                <div class="chart" style="height: 100%;">
                    <canvas id="barChart" style="width: 100%; height: 100%;"></canvas>
                </div>
            </x-adminlte-card>
        </div>
        
        <div class="col-md-4 d-flex flex-column">
            <x-adminlte-card title="Novos Fornecedores nos Últimos Seis Meses" icon="fa-solid fa-chart-column" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable class="flex-fill" style="height: 360px;">
                <div class="chart" style="height: 100%;">
                    <canvas id="barChartFornecedor" style="width: 100%; height: 100%;"></canvas>
                </div>
            </x-adminlte-card>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12"> 
            <x-adminlte-card title="Novos Clientes dos Últimos Seis Meses" icon="" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
                @php
                    $heads = [
                        'Data Cadastro',
                        'Cliente',
                        'Tipo de Pessoa',
                        'CPF / CNPJ',
                        'Email',
                        ['label' => 'Opção', 'no-export' => true, 'width' => 5],
                    ];
                @endphp
                <x-adminlte-datatable id="tabela-clientes" :heads="$heads" theme="light" striped hoverable>
                    @foreach ($clientes as $cliente)
                        @php
                            if($cliente->cliente_tipo_cadastro == 'F'){
                                $tipo = "Fornecedor";
                            }else{
                                $tipo = "Cliente";
                            }

                            if($cliente->cliente_tipo_pessoa == 'F'){
                                $cpfCnpj = Helper::mascaraCPF($cliente->cliente_cpf_cnpj);
                                $tipoPes = "Física";
                            }else{
                                $cpfCnpj = Helper::mascaraCNPJ($cliente->cliente_cpf_cnpj);
                                $tipoPes = "Jurídica";
                            }
                        @endphp
                        <tr>
                            <td>{{$cliente->cliente_dt_inc}}</td>
                            <td>{{$cliente->cliente_codigo.' - '.$cliente->cliente_nome}}</td>
                            <td>{{$tipoPes}}</td>
                            <td>{{$cpfCnpj}}</td>
                            <td>{{$cliente->cliente_email}}</td>
                            <td>
                                <nobr class="d-flex justify-content-center">
                                    <form method="get" action="{{ route('cadastroCliente.edit', ['cadastroCliente' => $cliente->cliente_codigo]) }}" style="float: left;">
                                        <!-- Informa o Tipo da Busca para a Edição -->
                                        <input id="tipo" type="hidden" value="H" name="tipo">
                                        @csrf 
                                        <button class="btn btn-xs btn-outline-primary mx-1" title="Editar Registro" value="Edit" type="submit">
                                            <i class="fa fa-lg fa-fw fa-pen"></i>
                                        </button>
                                    </form>
                                </nobr>
                            </td>
                        </tr>
                    @endforeach
                </x-adminlte-datatable>
            </x-adminlte-card>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12"> 
            <x-adminlte-card title="Novos Fornecedores dos Últimos Seis Meses" icon="" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
                @php
                    $heads = [
                        'Data Cadastro',
                        'Fornecedor',
                        'Tipo de Pessoa',
                        'CPF / CNPJ',
                        'Email',
                        ['label' => 'Opção', 'no-export' => true, 'width' => 5],
                    ];
                @endphp
                <x-adminlte-datatable id="tabela-fornecedores" :heads="$heads" theme="light" striped hoverable>
                    @foreach ($fornecedores as $fornecedor)
                        @php
                            if($fornecedor->cliente_tipo_cadastro == 'F'){
                                $tipo = "Fornecedor";
                            }else{
                                $tipo = "Cliente";
                            }

                            if($fornecedor->cliente_tipo_pessoa == 'F'){
                                $cpfCnpj = Helper::mascaraCPF($fornecedor->cliente_cpf_cnpj);
                                $tipoPes = "Física";
                            }else{
                                $cpfCnpj = Helper::mascaraCNPJ($fornecedor->cliente_cpf_cnpj);
                                $tipoPes = "Jurídica";
                            }
                        @endphp
                        <tr>
                            <td>{{$fornecedor->cliente_dt_inc}}</td>
                            <td>{{$fornecedor->cliente_codigo.' - '.$fornecedor->cliente_nome}}</td>
                            <td>{{$tipoPes}}</td>
                            <td>{{$cpfCnpj}}</td>
                            <td>{{$fornecedor->cliente_email}}</td>
                            <td>
                                <nobr class="d-flex justify-content-center">
                                    <form method="get" action="{{ route('cadastroCliente.edit', ['cadastroCliente' => $fornecedor->cliente_codigo]) }}" style="float: left;">
                                        <!-- Informa o Tipo da Busca para a Edição -->
                                        <input id="tipo" type="hidden" value="H" name="tipo">
                                        @csrf 
                                        <button class="btn btn-xs btn-outline-primary mx-1" title="Editar Registro" value="Edit" type="submit">
                                            <i class="fa fa-lg fa-fw fa-pen"></i>
                                        </button>
                                    </form>
                                </nobr>
                            </td>
                        </tr>
                    @endforeach
                </x-adminlte-datatable>
            </x-adminlte-card>
        </div>
    </div>

@stop

<!-- Chamada dos Plugins usados na app -->  
@section('plugins.Chartjs', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.Moment', true)

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
                /* ********** Inicializa o Datatable ********** */
        var table = $('#tabela-clientes').DataTable({
            dom: getDatatableDom(),
            buttons: getDatatableButtons(),
            lengthMenu: [5, 10, 25, 50, 100],
            pageLength: 5,
            language: dataTableLangPtBR,
            order: [
                [0, 'desc'],
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
                { orderable: false }
            ],
            columnDefs: [
                {
                    targets: 0,
                    render: getRenderDateFunction('DD/MM/YYYY')
                },
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
                customSearchingField('#tabela-clientes_filter');

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
                $('#tabela-clientes_wrapper .btn-datatable-dir .tool-bar').prepend(buttonsTooBarHTML);

                /* ******************** Cards dos Botões Principais ******************** */

                /* ********** Botões do Card da Coluna ********** */
                let btnColunaHTML = `
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="0" href="#">Data Cadastro</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="1" href="#">Cliente</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="2" href="#">Tipo de Pessoa</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="3" href="#">CPF / CNPJ</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="4" href="#">Email</a>
                `;

                /* ********** Monta o Card da Coluna ********** */
                let cardColunaHTML = getCardColunas(btnColunaHTML);

                /* ********** Adiciona o Card da Coluna ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-clientes_wrapper .linha-cards-menu').append(cardColunaHTML);

                /* ********** Campos do Card de Filtro ********** */
                let fieldFiltroHTML = `
                    <div class="row">
                        <x-adminlte-input name="filterData" type="text" label="Data Cadastro" class="form-control" placeholder="Filtrar Data Cadastro" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterCliente" type="text" label="Cliente" class="form-control" placeholder="Filtrar Cliente" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-select name="filterTipo" label="Tipo de Pessoa" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="['Física' => 'Física', 'Jurídica' => 'Jurídica']" empty-option="Selecione..."/>
                        </x-adminlte-select>
                        <x-adminlte-input name="filterCpfCnpj" type="text" label="CPF / CNPJ" class="form-control" placeholder="Filtrar CPF / CNPJ" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                    </div>
                    <div class="row">
                        <x-adminlte-input name="filterEmail" type="text" label="Email" class="form-control" placeholder="Filtrar Email" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                    </div>
                `;

                /* ********** Monta o Card de Filtro ********** */
                let cardFiltroHTML = getCardFiltros(fieldFiltroHTML);

                /* ********** Adiciona o Card de Filtro ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-clientes_wrapper .linha-cards-menu').append(cardFiltroHTML);
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
                $('#tabela-clientes_wrapper').each(function() {
                    var $wrapper = $(this);  // Armazena o contexto atual do wrapper

                    $wrapper.find('#filterData').on('keyup', function() {
                        table.column(0).search(this.value).draw();
                    });

                    $wrapper.find('#filterCliente').on('keyup', function() {
                        table.column(1).search(this.value).draw();
                    });

                    $wrapper.find('#filterTipo').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(2).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterCpfCnpj').on('keyup', function() {
                        table.column(3).search(this.value).draw();
                    });

                    $wrapper.find('#filterEmail').on('keyup', function() {
                        table.column(4).search(this.value).draw();
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
        setupActionsColuna(table, '#tabela-clientes_wrapper');

        /* ------------------------------ Final dos Eventos de Controle do Card de Colunas e Funcionalidades ------------------------------ */

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Controle do Card de Filtro e Funcionalidades
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Chama a função de configuração de filtros
        setupActionsFiltro(table, '#tabela-clientes_wrapper');

        /* ------------------------------ Final dos Eventos de Controle do Card de Filtro e Funcionalidades ------------------------------ */

    });

    $(() => {
                /* ********** Inicializa o Datatable ********** */
        var tableFor = $('#tabela-fornecedores').DataTable({
            dom: getDatatableDom(),
            buttons: getDatatableButtons(),
            lengthMenu: [5, 10, 25, 50, 100],
            pageLength: 5,
            language: dataTableLangPtBR,
            order: [
                [0, 'desc'],
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
                { orderable: false }
            ],
            columnDefs: [
                {
                    targets: 0,
                    render: getRenderDateFunction('DD/MM/YYYY')
                },
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
                customSearchingField('#tabela-fornecedores_filter');

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
                $('#tabela-fornecedores_wrapper .btn-datatable-dir .tool-bar').prepend(buttonsTooBarHTML);

                /* ******************** Cards dos Botões Principais ******************** */

                /* ********** Botões do Card da Coluna ********** */
                let btnColunaHTML = `
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="0" href="#">Data Cadastro</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="1" href="#">Fornecedor</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="2" href="#">Tipo de Pessoa</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="3" href="#">CPF / CNPJ</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="4" href="#">Email</a>
                `;

                /* ********** Monta o Card da Coluna ********** */
                let cardColunaHTML = getCardColunas(btnColunaHTML);

                /* ********** Adiciona o Card da Coluna ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-fornecedores_wrapper .linha-cards-menu').append(cardColunaHTML);

                /* ********** Campos do Card de Filtro ********** */
                let fieldFiltroHTML = `
                    <div class="row">
                        <x-adminlte-input name="filterData" type="text" label="Data Cadastro" class="form-control" placeholder="Filtrar Data Cadastro" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterCliente" type="text" label="Cliente" class="form-control" placeholder="Filtrar Cliente" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-select name="filterTipo" label="Tipo de Pessoa" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="['Física' => 'Física', 'Jurídica' => 'Jurídica']" empty-option="Selecione..."/>
                        </x-adminlte-select>
                        <x-adminlte-input name="filterCpfCnpj" type="text" label="CPF / CNPJ" class="form-control" placeholder="Filtrar CPF / CNPJ" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                    </div>
                    <div class="row">
                        <x-adminlte-input name="filterEmail" type="text" label="Email" class="form-control" placeholder="Filtrar Email" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                    </div>
                `;

                /* ********** Monta o Card de Filtro ********** */
                let cardFiltroHTML = getCardFiltros(fieldFiltroHTML);

                /* ********** Adiciona o Card de Filtro ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-fornecedores_wrapper .linha-cards-menu').append(cardFiltroHTML);
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
                $('#tabela-fornecedores_wrapper').each(function() {
                    var $wrapper = $(this);  // Armazena o contexto atual do wrapper

                    $wrapper.find('#filterData').on('keyup', function() {
                        tableFor.column(0).search(this.value).draw();
                    });

                    $wrapper.find('#filterCliente').on('keyup', function() {
                        tableFor.column(1).search(this.value).draw();
                    });

                    $wrapper.find('#filterTipo').on('change', function() {
                        var selectedValue = $(this).val();
                        tableFor.column(2).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterCpfCnpj').on('keyup', function() {
                        tableFor.column(3).search(this.value).draw();
                    });

                    $wrapper.find('#filterEmail').on('keyup', function() {
                        tableFor.column(4).search(this.value).draw();
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
        setupActionsColuna(tableFor, '#tabela-fornecedores_wrapper');

        /* ------------------------------ Final dos Eventos de Controle do Card de Colunas e Funcionalidades ------------------------------ */

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Controle do Card de Filtro e Funcionalidades
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Chama a função de configuração de filtros
        setupActionsFiltro(tableFor, '#tabela-fornecedores_wrapper');

        /* ------------------------------ Final dos Eventos de Controle do Card de Filtro e Funcionalidades ------------------------------ */

    });

    /* ------------------------------ Final da Inicialização do Datatable ------------------------------ */
</script>

<script>
    $(function () {
        /* ChartJS
        * -------
        * Here we will create a few charts using ChartJS.
        */

        //---------------------
        // BAR CHART - Clientes
        //---------------------
        var barChartData = {
            labels  : {!! $meses !!},
            datasets: [
                {
                    label               : 'Pessoa Física',
                    backgroundColor     : 'rgba(60,141,188,0.9)',
                    borderColor         : 'rgba(60,141,188,0.8)',
                    pointRadius          : false,
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(60,141,188,1)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data                : {!! $grafF !!}
                },
                {
                    label               : 'Pessoa Jurídica',
                    backgroundColor     : 'rgba(210, 214, 222, 1)',
                    borderColor         : 'rgba(210, 214, 222, 1)',
                    pointRadius         : false,
                    pointColor          : 'rgba(210, 214, 222, 1)',
                    pointStrokeColor    : '#c1c7d1',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(220,220,220,1)',
                    data                : {!! $grafJ !!}
                },
            ]
        };

        var barChartCanvas = $('#barChart').get(0).getContext('2d');

        var barChartOptions = {
            responsive : true,
            maintainAspectRatio : false,
            scales: {
                xAxes: [{
                    stacked: false,
                }],
                yAxes: [{
                    stacked: false,
                    ticks: {
                        beginAtZero: true, // Começar do zero
                        //stepSize: 1,       // Define o incremento
                        callback: function(value) {
                            if (value % 1 === 0) {
                                return value; // Exibir apenas números inteiros
                            }
                        }
                    }
                }]
            }
        };

        new Chart(barChartCanvas, {
            type: 'bar',
            data: barChartData,
            options: barChartOptions
        });

        //---------------------
        // BAR CHART - Fornecedores
        //---------------------
        var barChartDataFornecedor = {
            labels  : {!! $meses !!},
            datasets: [
                {
                    label               : 'Fornecedores',
                    backgroundColor     : 'rgba(60,141,188,0.9)',
                    borderColor         : 'rgba(60,141,188,0.8)',
                    pointRadius          : false,
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(60,141,188,1)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data                : {!! $grafFornecedores !!}
                }
            ]
        };

        var barChartCanvasFornecedor = $('#barChartFornecedor').get(0).getContext('2d');

        var barChartOptionsFornecedor = {
            responsive : true,
            maintainAspectRatio : false,
            scales: {
                xAxes: [{
                    stacked: false,
                }],
                yAxes: [{
                    stacked: false,
                    ticks: {
                        beginAtZero: true, // Começar do zero
                        //stepSize: 1,       // Define o incremento
                        callback: function(value) {
                            if (value % 1 === 0) {
                                return value; // Exibir apenas números inteiros
                            }
                        }
                    }
                }]
            }
        };

        new Chart(barChartCanvasFornecedor, {
            type: 'bar',
            data: barChartDataFornecedor,
            options: barChartOptionsFornecedor
        });
    })
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
</script>
@stop
