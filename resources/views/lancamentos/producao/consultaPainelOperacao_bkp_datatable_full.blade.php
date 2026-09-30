@extends('adminlte::page')

@section('title', 'Painel de Operação')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Controle de Produção</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.painelOperador')}}">Filtro Painel de Operação</a>
            </li>
            <li class="breadcrumb-item active">Painel de Operação</li>
        </ol>
    </div>
</div>
@stop

@section('content')

@php 

$headsPrincipal = [
    'OS',
    ['label' => '', 'no-export' => true, 'width' => '5'],
    ['label' => '', 'no-export' => true, 'width' => '5'],
    ['label' => 'Agenda', 'no-export' => true, 'width' => '5'],
    'Situação',
    'OS',
    'Data/Hora Abertura',
    'Cliente',
    'Consultor',
    'Previsão Entrega',
    'Situação Entrega'
];

//Define as variaveis de sessão aqui na view por que depois de 2 redirect elas são destruidas
$_SESSION['where_consulta_painelOperador'] = $empresa_os;
$_SESSION['empresaOS_consulta_painelOperador'] = $where_app;
@endphp

<x-adminlte-card title="Painel de Operações da Produção" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
    <!-- Tabela Principal da OS -->
    <x-adminlte-datatable id="tabela-principal" :heads="$headsPrincipal" theme="light" striped hoverable beautify compressed with-buttons>
        @foreach ($dadosOS as $os)
            @php 
                //Gera os Dados Sobre o Cliente e o Usuário da OS
                $dadosCli =  DB::table('cadastro_clientes')->where('cliente_codigo', $os->os_cli)->get();
                $dadosUsu =  DB::table('users')->where('usuario_codigo', $os->os_res_abr)->get();

                if(!empty($os->os_dpe)){
                    $dataHora = date('Y-m-d H:i:s');
                    $prevEnt = 'S';

                    if($os->os_sts == 'F' && $os->os_dpe.' '.Helper::formataHoraMinuto($os->os_hpe).':00' < $os->os_dhf){
                        $sitPrevEnt = "AT";
                    }elseif($os->os_sts == 'F' && $os->os_dpe.' '.Helper::formataHoraMinuto($os->os_hpe).':00' >= $os->os_dhf){
                        $sitPrevEnt = "OK";
                    }elseif($os->os_sts == 'A' && $os->os_dpe.' '.Helper::formataHoraMinuto($os->os_hpe).':00' < $dataHora){
                        $sitPrevEnt = "AT";
                    }elseif($os->os_sts == 'A' && $os->os_dpe.' '.Helper::formataHoraMinuto($os->os_hpe).':00' >= $dataHora){
                        $sitPrevEnt = "OK";
                    }else{
                        $sitPrevEnt = "AT";
                    }
                }else{
                    $prevEnt = 'N';
                }

                // Verifica se os valores estão definidos
                if (!empty($os->os_dpe) && !empty($os->os_hpe)) {

                    $dataHoraString = $os->os_dpe . ' ' . Helper::formataHoraMinuto($os->os_hpe) . ':00';
                    $dataHora = new DateTime($dataHoraString);

                    // Formata a data e hora para o formato ISO 8601
                    $dataHoraFormatada = $dataHora->format(DateTime::ATOM); // Ex: 2024-10-15T14:30:00-03:00
                } else {
                    $dataHoraFormatada = null;
                }
            @endphp
            <tr id="row_{{$os->os_id}}" data-os="{{ $os->os_nos }}" data-empresa="{{ $os->os_emp }}">
                <td>{{ $os->os_nos }}</td>
                <td class='icone-row-sub'>
                    <i class="fa-regular fa-chevron-right fa-xs" title="Detalhes do Registro" style="cursor: pointer;"></i>
                </td>
                <td>
                    <!-- Link para o Painel da OS -->
                    <a href="{{route('situacaoOS.carregaOS', ['empresa' => $os->os_emp, 'cliente' => $os->os_cli, 'nos' => $os->os_nos, 'estagioAPP' => 'PRINCIPAL'])}}" class="text-muted" title="Detalhes da OS">
                        <i class="fa-solid fa-magnifying-glass fa-lg" style="color: #74C0FC;"></i>
                    </a>
                </td>
                <td>
                    <!-- Gera o botão que abre o Modal de Agenda dos Serviços da OS -->
                    <button type="button" class="btn btn-xl" title="Agenda da OS" 
                        onclick="openModalAgendaSrvOS('{{ $os->os_emp }}', '{{ $os->os_nos }}')">
                        <i class="fa-solid fa-clipboard-list fa-xl" style="color: #39cccc;"></i>
                    </button>
                </td>
                <td>
                    <!-- Gera o Icone do Status da OS -->
                    @if($os->os_sts == 'A')
                        <i class="fa-solid fa-screwdriver-wrench fa-xl text-info mx-2" title="Aberta"></i>
                        <span class="d-none">Aberta</span> <!-- Coluna Oculta -->
                    @elseif($os->os_sts == 'F')
                        <i class="fa-solid fa-thumbs-up fa-xl text-success mx-2" title="Finalizada"></i>
                        <span class="d-none">Finalizada</span> <!-- Coluna Oculta -->
                    @else
                        <i class="fa-solid fa-ban fa-xl text-danger mx-2" title="Cancelada"></i>
                        <span class="d-none">Cancelada</span> <!-- Coluna Oculta -->
                    @endif
                </td>
                <td>{{ $os->os_nos }}</td>
                <td>{{ $os->os_dha }}</td>
                <td>{{ $os->os_cli.' - '.$dadosCli[0]->cliente_nome }}</td>
                <td>{{ $os->os_res_abr.' - '.$dadosUsu[0]->name }}</td>
                <td>{{$dataHoraFormatada}}</td>
                @if($prevEnt == 'S')
                <td>
                    <!-- Gera o Icone do Status da Prev Ent -->
                    @if($sitPrevEnt == 'OK')
                    <span class="badge badge-pill badge-success badge-custom">Dentro do Prazo</span>
                    @else
                    <span class="badge badge-pill badge-danger badge-custom">Atrazado</span>
                    @endif
                </td>
                @else
                <td></td>
                @endif
            </tr>
        @endforeach
    </x-adminlte-datatable>

    <!-- Modal Único - Agenda dos Serviços da OS -->
    <x-adminlte-modal id="modalAgendaSrv" title="Agenda Programada dos Serviços da OS" size="xl" theme="modal-nexus" icon="fa-solid fa-clipboard-list" v-centered scrollable>
        <!-- O conteúdo será carregado via AJAX -->
        <div id="modalContentAgendaSrv"></div>
        <x-slot name="footerSlot">
            <x-adminlte-button class="btn-nexus" theme="" label="Voltar" data-dismiss="modal"/>
        </x-slot>
    </x-adminlte-modal>

    <!-- ***** Modal Único - Adição de Auxiliar - Seleção da TMO ***** -->
    <form method="post" action="" id="addAuxForm" novalidate="novalidate">
        @csrf 
        @method('post')
        <x-adminlte-modal id="modalAddAux" title="Inclusão de Prestador Auxiliar da TMO" size="xl" theme="modal-nexus" icon="fa-solid fa-people-carry-box" v-centered scrollable>
            <!-- O conteúdo será carregado via AJAX -->
            <div id="modalContentAddAux"></div>
            <x-slot name="footerSlot">
                <input type="hidden" id="selected-prtAux" name="selected_prtAux">
                <x-adminlte-button class="btn-nexus" type="submit" label="Incluir" theme="" icon="fa-solid fa-user-plus"/>
                <x-adminlte-button class="btn-nexus" theme="" label="Voltar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </form>

    <!-- ***** Modal Único - Adição/Alteração do Prestador da TMO ***** -->
    <form method="post" action="" id="addChangePrtForm" novalidate="novalidate">
        @csrf 
        @method('post')
        <x-adminlte-modal id="modalAddChangePrt" title="Inclusão / Alteração do Prestador da TMO" size="xl" theme="modal-nexus" icon="fa-solid fa-people-arrows" v-centered scrollable>
            <!-- O conteúdo será carregado via AJAX -->
            <div id="modalContentAddChangePrt"></div>
            <x-slot name="footerSlot">
                <input type="hidden" id="selected-prt" name="selected_prt">
                <x-adminlte-button class="btn-nexus" type="submit" label="Incluir / Alterar" theme="" icon="fa-solid fa-pencil"/>
                <x-adminlte-button class="btn-nexus" theme="" label="Voltar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </form>

    <!-- ***** Modal Único - Iniciar Serviço ***** -->
    <form method="post" action="" id="startServiceForm" novalidate="novalidate">
        @csrf 
        @method('post')
        <x-adminlte-modal id="modalStartService" title="Iniciar Serviço" size="xl" theme="modal-nexus" icon="fa-solid fa-person-digging" v-centered scrollable>
            <!-- O conteúdo será carregado via AJAX -->
            <div id="modalContentStartService"></div>
            <x-slot name="footerSlot">
                <x-adminlte-button class="btn-nexus" type="submit" label="Iniciar" theme="" icon="fa-solid fa-circle-play"/>
                <x-adminlte-button class="btn-nexus" theme="" label="Voltar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </form>

    <!-- ***** Modal Único - Finalizar Serviço ***** -->
    <form method="post" action="" id="finishServiceForm" novalidate="novalidate">
        @csrf 
        @method('post')
        <x-adminlte-modal id="modalFinishService" title="Finalizar Serviço" size="xl" theme="modal-nexus" icon="fa-solid fa-person-digging" v-centered scrollable>
            <!-- O conteúdo será carregado via AJAX -->
            <div id="modalContentFinishService"></div>
            <x-slot name="footerSlot">
                <x-adminlte-button class="btn-nexus" type="submit" label="Finalizar" theme="" icon="fa-solid fa-circle-stop"/>
                <x-adminlte-button class="btn-nexus" theme="" label="Voltar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </form>

    <!-- ***** Modal Único - Cancelar Serviço ***** -->
    <form method="post" action="" id="cancelServiceForm" novalidate="novalidate">
        @csrf 
        @method('post')
        <x-adminlte-modal id="modalCancelService" title="Cancelar Serviço" size="xl" theme="modal-nexus" icon="fa-solid fa-person-digging" v-centered scrollable>
            <!-- O conteúdo será carregado via AJAX -->
            <div id="modalContentCancelService"></div>
            <x-slot name="footerSlot">
                <x-adminlte-button class="btn-nexus" type="submit" label="Cancelar" theme="" icon="fa-solid fa-ban"/>
                <x-adminlte-button class="btn-nexus" theme="" label="Voltar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </form>

    <!-- ***** Modal Único - Suspender Serviço ***** -->
    <form method="post" action="" id="suspendServiceForm" novalidate="novalidate">
        @csrf 
        @method('post')
        <x-adminlte-modal id="modalSuspendService" title="Suspender Serviço" size="xl" theme="modal-nexus" icon="fa-solid fa-person-digging" v-centered scrollable>
            <!-- O conteúdo será carregado via AJAX -->
            <div id="modalContentSuspendService"></div>
            <x-slot name="footerSlot">
                <x-adminlte-button class="btn-nexus" type="submit" label="Suspender" theme="" icon="fa-solid fa-triangle-exclamation"/>
                <x-adminlte-button class="btn-nexus" theme="" label="Voltar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </form>

    <!-- ***** Modal Único - Reabrir Serviço ***** -->
    <form method="post" action="" id="reopenServiceForm" novalidate="novalidate">
        @csrf 
        @method('post')
        <x-adminlte-modal id="modalReopenService" title="Reabrir Serviço" size="xl" theme="modal-nexus" icon="fa-solid fa-person-digging" v-centered scrollable>
            <!-- O conteúdo será carregado via AJAX -->
            <div id="modalContentReopenService"></div>
            <x-slot name="footerSlot">
                <x-adminlte-button class="btn-nexus" type="submit" label="Reabrir" theme="" icon="fa-regular fa-folder-open"/>
                <x-adminlte-button class="btn-nexus" theme="" label="Voltar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </form>

    <!-- ***** Modal Único - Finalizar Requisição ***** -->
    <form method="post" action="" id="finishRequisicaoForm" novalidate="novalidate">
        @csrf 
        @method('post')
        <x-adminlte-modal id="modalFinishRequisicao" title="Finalizar Requisição" size="xl" theme="modal-nexus" icon="fa-solid fa-clipboard-check" v-centered scrollable>
            <!-- O conteúdo será carregado via AJAX -->
            <div id="modalContentFinishRequisicao"></div>
            <x-slot name="footerSlot">
                <x-adminlte-button class="btn-nexus" type="submit" label="Finalizar" theme="" icon="fa-solid fa-check"/>
                <x-adminlte-button class="btn-nexus" theme="" label="Voltar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </form>

    <!-- ***** Modal Único - Reabrir Requisição ***** -->
    <form method="post" action="" id="reopenRequisicaoForm" novalidate="novalidate">
        @csrf 
        @method('post')
        <x-adminlte-modal id="modalReopenRequisicao" title="Reabertura da Requisição" size="xl" theme="modal-nexus" icon="fa-solid fa-clipboard-list" v-centered scrollable>
            <!-- O conteúdo será carregado via AJAX -->
            <div id="modalContentReopenRequisicao"></div>
            <x-slot name="footerSlot">
                <x-adminlte-button class="btn-nexus" type="submit" label="Reabrir" theme="" icon="fa-regular fa-folder-open"/>
                <x-adminlte-button class="btn-nexus" theme="" label="Voltar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </form>
    <x-slot name="footerSlot">
        <div class="d-flex justify-content-end w-100">
            <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.painelOperador') }}'" label="Voltar" theme="" icon=""/>
        </div>
    </x-slot>
</x-adminlte-card>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.Sweetalert2', true)
@section('plugins.jqueryValidation', true)
@section('plugins.DateRangePicker', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.BootstrapSwitch', true)
@section('plugins.Moment', true)

@section('css')
<style>
    .nested-table {
        margin: 0;
        background: none;
        border-collapse: collapse; /* Colapsa as bordas da tabela */
    }
    .nested-table th, .nested-table td {
        padding: 8px; /* Adiciona um pouco de espaço interno */
        background: none;
        border: 1px solid #dee2e6; /* Adiciona uma borda para melhor visualização */
    }

    .nested-table thead th {
        background-color: none; /* Cor de fundo para o cabeçalho da tabela aninhada */
        border-bottom: 2px solid #dee2e6; /* Linha inferior para separar o cabeçalho */
    }

    .dropdown-menu {
        text-align: center; /* Centraliza o texto no dropdown */
        color: black; /* Define a cor do texto como preto */
    }
    .dropdown-menu a {
        color: black; /* Define a cor do link como preto */
        display: block; /* Faz com que o link ocupe toda a largura do dropdown */
    }
    .dropdown-menu a:hover {
        background-color: #f8f9fa; /* Cor de fundo ao passar o mouse */
        color: black; /* Mantém a cor do texto como preto ao passar o mouse */
    }

    .badge-custom {
        font-size: 0.85rem !important; /* Aumenta o tamanho do texto */
    }

    #modalAgendaSrv .modal-xl {
        max-width: 85% !important; /* Aumenta o tamanho do modal */
    }

    #modalAddChangePrt .modal-xl {
        max-width: 75% !important; /* Aumenta o tamanho do modal */
    }

    #modalAddAux .modal-xl {
        max-width: 75% !important; /* Aumenta o tamanho do modal */
    }
</style>
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
        
        //Define a exibição de até 5 páginas na paginação
        $.fn.DataTable.ext.pager.numbers_length = 5;

        var groupColumns = [7];

        /* ********** Inicializa o Datatable ********** */
        var table = $('#tabela-principal').DataTable({
            dom: '<"row" <"col-md-6 btn-datatable-esq mb-2" B> <"col-md-6 btn-datatable-dir mb-2" f>><"row" <"col-12 auxiliar-menu" F>><"row" <"col-12 body-datatable" tr>><"row footer-datatable" <"col-md-5" i> <"col-md-7" p>>',
            buttons: {
                dom: {
                    button: {
                        className: "btn btn-default" // Adicionando uma classe padrão
                    }
                },
                buttons: [
                    {
                        extend: "pageLength",
                        className: "btn-nexus"
                    },
                    {
                        extend: "print",
                        className: "btn-nexus",
                        text: "<i class='fas fa-fw fa-lg fa-print'></i>",
                        titleAttr: "Imprimir",
                        exportOptions: {
                            columns: ":not([dt-no-export])"
                        }
                    },
                    {
                        extend: "csv",
                        className: "btn-nexus",
                        text: "<i class='fas fa-fw fa-lg fa-file-csv'></i>",
                        titleAttr: "Exportar para CSV",
                        exportOptions: {
                            columns: ":not([dt-no-export])"
                        }
                    },
                    {
                        extend: "excel",
                        className: "btn-nexus",
                        text: "<i class='fa-solid fa-file-xls fa-fw fa-lg'></i>",
                        titleAttr: "Exportar para Excel",
                        exportOptions: {
                            columns: ":not([dt-no-export])"
                        }
                    },
                    {
                        extend: "pdf",
                        className: "btn-nexus",
                        text: "<i class='fas fa-fw fa-lg fa-file-pdf'></i>",
                        titleAttr: "Exportar para PDF",
                        exportOptions: {
                            columns: ":not([dt-no-export])"
                        }
                    }
                ]
            },
            lengthMenu: [5, 10, 25, 50, 100],
            pageLength: 10,
            language: dataTableLangPtBR,
            order: [[groupColumns, 'asc'],[0, 'desc']],
            pagingType: 'full_numbers',
            processing: true,
            columns: [
                { orderable: false, visible: false }, // Esconder primeira coluna
                { orderable: false },
                { orderable: false },
                { orderable: false },
                null,
                null,
                null,
                null,
                null,
                null,
                null
            ],
            columnDefs: [
                { visible: false, targets: groupColumns },
                {
                    targets: 6,
                    render: function(data, type) {
                        if (type === 'display' || type === 'filter') {
                            return data ? moment(data).format('DD/MM/YYYY HH:mm:ss') : '';
                        }
                        return data;
                    }
                },
                {
                    targets: 9,
                    render: function(data, type) {
                        if (type === 'display' || type === 'filter') {
                            return data ? moment(data).format('DD/MM/YYYY HH:mm') : '';
                        }
                        return data;
                    }
                }
            ],
            drawCallback: function (settings) {
                var api = this.api();
                var rows = api.rows({ page: 'current' }).nodes();
                var lastGroupValues = [];

                // Verifica se há colunas para agrupar
                if (groupColumns.length > 0) {
                    groupColumns.forEach(function(groupColumn, index) {
                        lastGroupValues[index] = null; // Inicializa os valores anteriores

                        api.column(groupColumn, { page: 'current' })
                            .data()
                            .each(function (group, i) {
                                if (lastGroupValues[index] !== group) {
                                    // Obtenha a label da coluna correspondente
                                    var label = $(api.column(groupColumn).header()).text();

                                    // Crie a linha de agrupamento com a label e o grupo
                                    $(rows).eq(i).before(
                                        '<tr class="group group-' + index + '"><td colspan="10">' + label + ': ' + group + '</td></tr>'
                                    );

                                    lastGroupValues[index] = group; // Atualiza o último valor
                                }
                            });
                    });
                }
            },
            initComplete: function() {

                /* *****
                |----------------------------------------------------------------------------------------------------
                | Botões da Barra de Ferramentas do Datatable
                |----------------------------------------------------------------------------------------------------
                |
                | Adicionamos aqui a criação manual dos botões utilizados na barra de ferramentas do datatable
                |
                ***** */

                /* ********** Criação dos botões de Filtro e Visualir Colunas ********** */
                let dropdownHTML = `
                    <div class="d-flex flex-wrap justify-content-end align-items-end">
                        
                        <!-- Botão Quebras -->
                        <div class="ml-2 mb-2">
                            <button type="button" class="btn btn-outline-nexus btn-quebra-datatable" title="Quebra de Consulta">
                                Quebras 
                                <i class="fa-solid fa-bars-staggered"></i>
                            </button>
                        </div>

                        <!-- Botão Visualizar Colunas -->
                        <div class="ml-2 mb-2">
                            <button type="button" class="btn btn-outline-nexus btn-visualizar-datatable" title="Visualizar Colunas">
                                Colunas 
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>

                        <!-- Botão Filtro -->
                        <div class="ml-2 mb-2">
                            <button type="button" class="btn btn-outline-nexus btn-filtro-datatable" title="Filtrar Registros">
                                Filtro 
                                <i class="fa-regular fa-filter-list fa-lg"></i>
                            </button>
                        </div>                     

                        <!-- Campo de pesquisa -->
                        <div class="ml-2 mb-2 flex-grow-1 flex-md-grow-0">
                            <x-adminlte-input name="filter-input" type="text" placeholder="Pesquisa rápida" id="custom-search">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                    </div>
                `;

                /* ********** Adicionando os botões no DIV de Filtros ********** */
                $('.btn-datatable-dir').prepend(dropdownHTML);

                /* ********** Cards do Filtro de Pesquisa E Exbição de Campos ********** */
                let teste = `
                    <!-- Card Exibição de Quebras -->
                    <div class="row visualizar-quebras">
                        <div class="col-md-12">
                            <x-adminlte-card style="border-top: 3px solid #00abab !important;">
                                <div class="row d-flex justify-content-center align-items-center">
                                    <h5 style="color: #00abab;"><i class="fa-solid fa-bars-staggered"></i> Quebra de Consulta</h5>
                                </div>
                                <div class="row">
                                    <hr style="width: 100%;">
                                </div>
                                <div class="row d-flex justify-content-center align-items-center">
                                    <a class="btn btn-outline-nexus groupCol mr-2" data-column="4" href="#">Situação</a>
                                    <a class="btn btn-nexus groupCol mr-2" data-column="7" href="#">Cliente</a>
                                    <a class="btn btn-outline-nexus groupCol mr-2" data-column="8" href="#">Consultor</a>
                                    <a class="btn btn-outline-nexus groupCol mr-2" data-column="10" href="#">Situação Entrega</a>
                                </div>
                                <div class="row">
                                    <hr style="width: 100%;">
                                </div>
                                <div class="row d-flex justify-content-center align-items-center">
                                    <a class="btn btn-nexus removeGroupCol mr-2" href="#"><i class="fa-solid fa-trash-can"></i> Remover Quebras</a>
                                </div>
                            </x-adminlte-card>  
                        </div>
                    </div>
                    <!-- Card Exibição de Botões -->
                    <div class="row visualizar-colunas">
                        <div class="col-md-12">
                            <x-adminlte-card style="border-top: 3px solid #00abab !important;">
                                <div class="row d-flex justify-content-center align-items-center">
                                    <h5 style="color: #00abab;"><i class="fa-regular fa-eye"></i> Visualizar Colunas</h5>
                                </div>
                                <div class="row">
                                    <hr style="width: 100%;">
                                </div>
                                <div class="row d-flex justify-content-center align-items-center">
                                    <a class="btn btn-nexus toggle-vis mr-2" data-column="4" href="#">Situação</a>
                                    <a class="btn btn-nexus toggle-vis mr-2" data-column="5" href="#">OS</a>
                                    <a class="btn btn-nexus toggle-vis mr-2" data-column="6" href="#">Data/Hora Abertura</a>
                                    <a class="btn btn-outline-nexus toggle-vis mr-2" data-column="7" href="#">Cliente</a>
                                    <a class="btn btn-nexus toggle-vis mr-2" data-column="8" href="#">Consultor</a>
                                    <a class="btn btn-nexus toggle-vis mr-2" data-column="9" href="#">Previsão Entrega</a>
                                    <a class="btn btn-nexus toggle-vis mr-2" data-column="10" href="#">Situação Entrega</a>
                                </div>
                            </x-adminlte-card>  
                        </div>
                    </div>
                    <!-- Card Filtro -->
                    <div class="row filtrar-registros">
                        <div class="col-md-12">
                            <x-adminlte-card style="border-top: 3px solid #00abab !important;">
                                <div class="row d-flex justify-content-center align-items-center">
                                    <h5 style="color: #00abab;"><i class="fa-regular fa-filter"></i> Filtrar Registros</h5>
                                </div>
                                <div class="row">
                                    <hr style="width: 100%;">
                                </div>
                                <div class="row">
                                    <x-adminlte-select name="filterSts" label="Situação" igroup-size="sm" fgroup-class="col-md-3">
                                        <x-adminlte-options :options="['Aberta' => 'Aberta', 'Cancelada' => 'Cancelada', 'Finalizada' => 'Finalizada']" empty-option="Selecione..."/>
                                    </x-adminlte-select>

                                    <x-adminlte-input name="filterOS" type="text" label="OS" class="form-control" placeholder="Filtrar OS" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>

                                    <x-adminlte-input name="filterData" type="text" label="Data/Hora Abertura" class="form-control" placeholder="Filtrar Data/Hora Abertura" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                                    
                                    <x-adminlte-input name="filterCliente" type="text" label="Cliente" class="form-control" placeholder="Filtrar Cliente" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                                </div>
                                <div class="row">
                                    <x-adminlte-input name="filterConsultor" type="text" label="Consultor" class="form-control" placeholder="Filtrar Consultor" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                                    
                                    <x-adminlte-input name="filterPrevEnt" type="text" label="Previsão entrega" class="form-control" placeholder="Filtrar Previsão Entrega" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>

                                    <x-adminlte-select name="filterStsPreEnt" label="Situação Entrega" igroup-size="sm" fgroup-class="col-md-3">
                                        <x-adminlte-options :options="['Dentro do Prazo' => 'Dentro do Prazo', 'Atrazado' => 'Atrazado']" empty-option="Selecione..."/>
                                    </x-adminlte-select>
                                </div>
                                <div class="row">
                                    <hr style="width: 100%;">
                                </div>
                                <div class="row d-flex justify-content-center align-items-center">
                                    <a class="btn btn-nexus btn-limpa-filtro mr-2" href="#"><i class="fa-solid fa-broom-wide"></i> Limpar Filtros</a>
                                </div>
                            </x-adminlte-card>  
                        </div>
                    </div>
                `;

                $('.auxiliar-menu').prepend(teste);
                /* ------------------------------ Final dos Eventos Botões de Dropdown do Datatable ------------------------------ */
                
                /* *****
                |----------------------------------------------------------------------------------------------------
                | Eventos dos Filtros Individuais
                |----------------------------------------------------------------------------------------------------
                |
                | Esses eventos devem ser feitos ao criar a tabela no "initComplete" para funcionar a busca dos dados
                |
                ***** */

                // Vincular o evento de mudança do select para filtrar a coluna correspondente
                $('#filterSts').on('change', function() {
                    var selectedValue = $(this).val();
                    table.column(4).search(selectedValue).draw();
                });

                // Filtrando por OS
                $('#filterOS').on('keyup', function() {
                    table.column(5).search(this.value).draw();
                });

                // Filtrando por Data
                $('#filterData').on('keyup', function() {
                    table.column(6).search(this.value).draw();
                });

                // Filtrando por Cliente
                $('#filterCliente').on('keyup', function() {
                    table.column(7).search(this.value).draw();
                });

                // Filtrando por Consultor
                $('#filterConsultor').on('keyup', function() {
                    table.column(8).search(this.value).draw();
                });

                // Filtrando por Previsão de Entrega
                $('#filterPrevEnt').on('keyup', function() {
                    table.column(9).search(this.value).draw();
                });

                // Filtrando por Situação da Previsão de Entrega
                $('#filterStsPreEnt').on('change', function() {
                    var selectedValue = $(this).val();
                    table.column(10).search(selectedValue).draw();
                });

                /* *****
                |----------------------------------------------------------------------------------------------------
                | Campo Pesquisa Rápida
                |----------------------------------------------------------------------------------------------------
                |
                | Criação da pesquisa geral de dados da tabela, sobreposição ao botão original pela dificuldade de estilização
                |
                ***** */

                // Pesquisa Rápida
                $('#custom-search').on('keyup', function() {
                    table.search(this.value).draw();
                });
                /* ------------------------------ Final dos Eventos de Filtro ------------------------------ */
                
            }
        });
        /* ------------------------------ Final a Inicialização do Datatable ------------------------------ */

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Eventos da Sub Consulta (Linha de Detalhes)
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Função para preencher os dados da linha filha (Detalhes)
        function format(tr) {

            // Obtendo os parâmetros da OS e da empresa a partir do 'tr' (linha da tabela)
            let os = $(tr).data('os');
            let empresa = $(tr).data('empresa');

            //Gerando a URL da rota da Sub Consulta
            var url = "{{ route('painelOperacao.carregarDadosSubConsultaPainelOperador', [':emp',':os']) }}";
            url = url.replace(':os', os);
            url = url.replace(':emp', empresa);

            // Evento AJAX para buscar o conteúdo da view através da rota
            return $.ajax({
                url: url, 
                type: 'GET',
                data: {
                    empresa: empresa,
                    os: os
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Adiciona o token CSRF
                },
                success: function(response) {
                    
                    return response;
                },
                error: function(xhr) {

                    return 'Erro ao buscar detalhes da OS.';
                }
            });
        }

        //Array com os dados da linha
        const detailRows = [];

        // Event listener para expandir/recolher as linhas filhas de detalhes
        $('#tabela-principal').on('click', 'tbody td.icone-row-sub', function (event) {
                
            let tr = $(this).closest('tr'); // Use jQuery para selecionar o 'tr'
            let row = table.row(tr);
            let icon = $(this).find('i'); // Buscando o ícone

            if (row.child.isShown()) {

                // Ocultar a linha filha
                tr.removeClass('details');
                row.child.hide();
                icon.removeClass('fa-chevron-down').addClass('fa-chevron-right'); // Ícone de "fechado"

                // Remover do array 'detailRows'
                detailRows.splice(detailRows.indexOf(tr.attr('id')), 1);

            } else {

                // Mostrar a linha filha com o conteúdo da view via AJAX
                format(tr).done(function(response) {

                    tr.addClass('details');
                    row.child(response).show();
                    icon.removeClass('fa-chevron-right').addClass('fa-chevron-down'); // Ícone de "aberto"
                });

                // Adicionar ao array 'detailRows'
                if (detailRows.indexOf(tr.attr('id')) === -1) {

                    detailRows.push(tr.attr('id'));
                }
            }
        });

        // reabrir automaticamente as linhas filhas de uma tabela ao redesenhar (redraw) a tabela que estavam abertas
        $('#tabela-principal').on('draw', () => {

            detailRows.forEach((id) => {
                let el = document.querySelector('#' + id + ' td.icone-row-sub');
                if (el) {
                    el.dispatchEvent(new Event('click', { bubbles: true }));
                }
            });
        });
        /* ------------------------------ Final dos Eventos da Sub Consulta ------------------------------ */

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Eventos do Botão de Visualizar Colunas
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Adiciona um event listener a cada link de toggle
        document.querySelectorAll('a.toggle-vis').forEach((el) => {
            el.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Obtém o índice da coluna a partir do atributo data-column
                let columnIdx = e.target.getAttribute('data-column');
                let column = table.column(columnIdx);
                
                // Alterna a visibilidade da coluna
                let isVisible = column.visible();
                column.visible(!isVisible);
                
                // Altera a classe do botão com base na visibilidade
                if (isVisible) {
                    e.target.classList.remove('btn-nexus');  // Remove a classe de botão ativo
                    e.target.classList.add('btn-outline-nexus');     // Adiciona a classe de botão inativo
                } else {
                    e.target.classList.remove('btn-outline-nexus');   // Remove a classe de botão inativo
                    e.target.classList.add('btn-nexus');       // Adiciona a classe de botão ativo
                }
            });
        });
        /* ------------------------------ Final dos Eventos de Visualizar Colunas ------------------------------ */

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Controle dos Cards de Filtro e Visualização
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Inicialmente esconde as divs
        $('.filtrar-registros').hide();
        $('.visualizar-colunas').hide();
        $('.visualizar-quebras').hide();

        // Esconde a DIV do Pesquisar Original - Esse nunca exibe
        $('.dataTables_filter').hide();

        // Função para alternar a exibição da div "Quebra" e trocar a classe do botão
        $('button.btn-quebra-datatable').on('click', function() {
            $('.visualizar-quebras').slideToggle();  // Exibe ou oculta com efeito deslizante

            // Alterna a classe do botão
            $(this).toggleClass('btn-nexus btn-outline-nexus');
        });

        // Função para alternar a exibição da div "Filtrar Registros" e trocar a classe do botão
        $('button.btn-filtro-datatable').on('click', function() {
            $('.filtrar-registros').slideToggle();  // Exibe ou oculta com efeito deslizante

            // Alterna a classe do botão
            $(this).toggleClass('btn-nexus btn-outline-nexus');
        });

        // Função para alternar a exibição da div "Visualizar Colunas" e trocar a classe do botão
        $('button.btn-visualizar-datatable').on('click', function() {
            $('.visualizar-colunas').slideToggle();  // Exibe ou oculta com efeito deslizante

            // Alterna a classe do botão
            $(this).toggleClass('btn-nexus btn-outline-nexus');
        });

        // Função para limpar os campos de filtro e atualizar o DataTable
        document.querySelector('.btn-limpa-filtro').addEventListener('click', function(e) {
            e.preventDefault(); // Evita o comportamento padrão do link

            // Seleciona todos os campos de entrada e select
            const inputs = document.querySelectorAll('input[name^="filter"], select[name^="filter"]');

            // Limpa o valor de cada campo
            inputs.forEach(input => {
                if (input.tagName.toLowerCase() === 'select') {
                    input.selectedIndex = 0; // Para selects, define a opção vazia
                } else {
                    input.value = ''; // Para inputs, limpa o valor
                }
            });

            // Atualiza o DataTable para refletir os campos limpos
            table.search('').columns().search('').draw(); // Limpa todos os filtros
        });
        /* ------------------------------ Final dos Eventos de Controle dos Cards ------------------------------ */

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Controle dos Botões de Quebra (Agrupamento)
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Altera a Ordenação do agrupamento entre ASC/DESC
        $('#tabela-principal tbody').on('click', 'tr.group', function () {
            var groupIndex = $(this).attr('class').match(/group-(\d+)/)[1]; // Pega o índice do grupo clicado a partir da classe
            var groupColumn = groupColumns[groupIndex]; // Pega a coluna correspondente ao grupo clicado
            var currentOrder = table.order();

            // Verifica se a coluna atual está sendo ordenada e alterna entre 'asc' e 'desc'
            var found = currentOrder.find(function(order) {
                return order[0] === groupColumn;
            });

            if (found && found[1] === 'asc') {
                table.order([groupColumn, 'desc']).draw();
            } else {
                table.order([groupColumn, 'asc']).draw();
            }
        });

        // Troca a classe do botão Ativado/Desativado
        $('a.groupCol').on('click', function() {
            // Alterna a classe do botão
            $(this).toggleClass('btn-nexus btn-outline-nexus');
        });

        // Troca a classe de todos os botões para Desativado (Remove Quebras)
        $('a.removeGroupCol').on('click', function() {
            // Remove todas as classes que não sejam btn-nexus
            $('a.groupCol').removeClass('btn-nexus').addClass('btn-outline-nexus');
        });

        // Ordenação original (Secundária além do agrupamento)
        var originalOrder = [[0, 'desc']];

        // Função para adicionar/remover uma coluna ao agrupamento
        // Função para adicionar/remover uma coluna ao agrupamento
        function toggleGroupColumn(columnIndex) {
            var index = groupColumns.indexOf(columnIndex);
            if (index > -1) {
                groupColumns.splice(index, 1); // Remove a coluna se já estiver agrupada
                table.column(columnIndex).visible(true); // Torna a coluna visível novamente
                // Troca a classe do botão para btn-nexus
                $('a.toggle-vis[data-column="' + columnIndex + '"]').removeClass('btn-outline-nexus').addClass('btn-nexus');
            } else {
                groupColumns.push(columnIndex); // Adiciona a coluna para agrupar
                table.column(columnIndex).visible(false); // Esconde a coluna ao agrupar
                // Troca a classe do botão para btn-outline-nexus
                $('a.toggle-vis[data-column="' + columnIndex + '"]').removeClass('btn-nexus').addClass('btn-outline-nexus');
            }

            // Define a nova ordem, respeitando a ordem original como secundária
            var order = groupColumns.map(function(col) {
                return [col, 'asc'];
            }).concat(originalOrder); // Adiciona a ordem original como secundária

            // Aplica a nova ordem à tabela e redesenha
            table.order(order).draw();
        }

        // Função para remover todos os agrupamentos
        function removeAllGroups() {
            // Para cada coluna agrupada, torna-a visível novamente e troca a classe do botão
            groupColumns.forEach(function(columnIndex) {
                // Torna a coluna visível
                table.column(columnIndex).visible(true); 

                // Altera a classe do botão correspondente
                $('a[data-column="' + columnIndex + '"]').removeClass('btn-outline-nexus').addClass('btn-nexus');
            });

            // Limpa o array de colunas agrupadas
            groupColumns = []; 

            // Redefine a ordem para a original (ou a que você deseja) sem agrupamento
            table.order([[0, 'desc']]).draw(); // Remove a ordenação e o agrupamento e faz ordenação secundaria original
        }

        // Botões para alternar agrupamento dinamicamente
        $('.groupCol').on('click', function (e) {
            e.preventDefault();
            var column = $(this).data('column'); // Obtém a coluna do atributo data-column
            toggleGroupColumn(column); // Adiciona ou remove a coluna para agrupamento
        });

        // Botão para remover todos os agrupamentos
        $('.removeGroupCol').on('click', function (e) {
            e.preventDefault();
            removeAllGroups(); // Remove todos os agrupamentos
        });
        /* ------------------------------ Final dos Eventos de dos Botões de Quebra ------------------------------ */

    });
    /* ------------------------------ Final da Inicialização do Datatable ------------------------------ */
</script>

<!--
    |
    |----------------------------------------------------------------------------------------------------
    | Eventos Iniciais da app
    |----------------------------------------------------------------------------------------------------
    |
-->
<script>
    $(document).ready(function() { 

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Evento de seleção dos Prestadores para Inclusão/Alteração
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Gera o submit enviando os dados do prestador selecionado
        document.getElementById('addChangePrtForm').addEventListener('submit', function (e) {
            e.preventDefault();

            // Captura o prestador selecionado pelo radio button
            let selectedPrestador = document.querySelector('input[name="selected_prestador"]:checked');

            // Verifica se algum prestador foi selecionado
            if (!selectedPrestador) {
                Swal.fire({
                    confirmButtonColor: "#007bff",
                    title: "Erro!!!",
                    text: "Por favor, selecione um prestador para continuar!",
                    icon: "error"
                });
                return;
            }

            // Define o valor selecionado no campo hidden
            document.getElementById('selected-prt').value = selectedPrestador.value;

            // Envia o formulário
            this.submit();
        });

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Evento de seleção dos Prestadores Auxiliares da TMO
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Gera o submit enviando os dados dos serviços que serão aprovados
        document.getElementById('addAuxForm').addEventListener('submit', function (e) {
            e.preventDefault();

            let selectedPrtAux = [];
            document.querySelectorAll('.record-checkbox-prtAux:checked').forEach(function (checkbox) {
                selectedPrtAux.push(checkbox.value);
            });

            if (selectedPrtAux.length === 0) {
                Swal.fire({
                    confirmButtonColor: "#007bff",
                    title: "Erro!!!",
                    text: "Por favor, selecione pelo menos um prestado para incluir.",
                    icon: "error"
                });
                return;
            }

            document.getElementById('selected-prtAux').value = selectedPrtAux.join(',');

            this.submit();
        });
    });
</script>

<!--
|----------------------------------------------------------------------------------------------------
| Eventos das Aberturas dos Modais da app
|----------------------------------------------------------------------------------------------------
-->
<script>
    /* ******************** Fazer requisição AJAX para carregar os dados do modal de Adição de Prestador Auxiliar na TMO ******************** */
    function openModalAddAux(emp, nos, req, seq) {

        var url = "{{ route('painelOperacao.carregarDadosModalAddAxuTMO', [':emp',':nos',':req', ':seq']) }}";
        url = url.replace(':emp', emp);
        url = url.replace(':nos', nos);
        url = url.replace(':req', req);
        url = url.replace(':seq', seq);

        // Atualiza a url do formulário
        var formActionUrl = "{{ route('painelOperacao.addPrtAuxTMO', ['empresa' => ':emp', 'numOS' => ':nos', 'requisicao' => ':req', 'servico' => ':seq']) }}";
        formActionUrl = formActionUrl.replace(':emp', emp)
                                    .replace(':nos', nos)
                                    .replace(':req', req)
                                    .replace(':seq', seq);

        $.ajax({
            url: url,
            type: 'GET',
            data: { 
                '_token': $('meta[name=csrf-token]').attr("content"),
                '_method': 'GET',
                "emp": emp,
                "nos": nos,
                "req": req,
                "seq": seq
            },
            success: function(data) {
                // Preenche o conteúdo do modal
                $('#modalContentAddAux').html(data);

                // Inicializa ou reinicializa o DataTable
                $('#tabelaModalAddAux').DataTable({
                    paging: false,
                    searching: false,
                    autoWidth: false,
                    order: [[0, 'asc']],
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
                    language: {
                        decimal: '',
                        emptyTable: 'Sem dados disponíveis na tabela',
                        info: '',
                        infoEmpty: '',
                        infoFiltered: '(Filtrado do total de _MAX_ registros)',
                        thousands: ',',
                        loadingRecords: 'Carregando...',
                        search: 'Pesquisar:',
                        zeroRecords: 'Nenhum registro correspondente encontrado',
                        paginate: {
                            first: 'Primeiro',
                            last: 'Último',
                            next: 'Próximo',
                            previous: 'Anterior'
                        },
                        aria: {
                            sortAscending: ': ativar para classificar a coluna em ordem crescente',
                            sortDescending: ': ativar para classificar a coluna em ordem decrescente'
                        }
                    }
                });

                // Atualiza a ação do formulário
                $('#addAuxForm').attr('action', formActionUrl);

                // Abre o modal
                $('#modalAddAux').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar dados:', error);
            }
        });
    }

    /* ******************** Fazer requisição AJAX para carregar os dados do modal de agenda de serviços da OS ******************** */
    function openModalAgendaSrvOS(emp, nos) {

        var url = "{{ route('painelOperacao.carregarDadosModalAgendaSrvOS', [':emp',':nos']) }}";
        url = url.replace(':emp', emp);
        url = url.replace(':nos', nos);

        $.ajax({
            url: url,
            type: 'GET',
            data: { 
                '_token': $('meta[name=csrf-token]').attr("content"),
                '_method': 'GET',
                "emp": emp,
                "nos": nos
            },
            success: function(data) {
                // Preenche o conteúdo do modal
                $('#modalContentAgendaSrv').html(data);
                
                // Inicializa ou reinicializa o DataTable
                $('#tableModalAgendaSrvOS').DataTable({
                    paging: false,
                    searching: false,
                    autoWidth: false,
                    order: [[1, 'asc'],[0, 'asc'],[2, 'asc']],
                    columns: [
                        { orderable: false, visible: false }, 
                        { orderable: false, visible: false }, 
                        { orderable: false, visible: false }, 
                        { orderable: false, render: $.fn.dataTable.render.text() }, 
                        { orderable: false, render: $.fn.dataTable.render.text() }, 
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
                        { orderable: false }, 
                        { orderable: false }, 
                        { orderable: false }
                    ],
                    language: {
                        decimal: '',
                        emptyTable: 'Sem dados disponíveis na tabela',
                        info: '',
                        infoEmpty: '',
                        infoFiltered: '(Filtrado do total de _MAX_ registros)',
                        thousands: ',',
                        loadingRecords: 'Carregando...',
                        search: 'Pesquisar:',
                        zeroRecords: 'Nenhum registro correspondente encontrado',
                        paginate: {
                            first: 'Primeiro',
                            last: 'Último',
                            next: 'Próximo',
                            previous: 'Anterior'
                        },
                        aria: {
                            sortAscending: ': ativar para classificar a coluna em ordem crescente',
                            sortDescending: ': ativar para classificar a coluna em ordem decrescente'
                        }
                    }
                });
                
                // Abre o modal
                $('#modalAgendaSrv').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar dados:', error);
            }
        });
    }

    /* ******************** Fazer requisição AJAX para carregar os dados do modal de inicio da TMO ******************** */
    function openModalStartService(emp, nos, req, seq) {

        var url = "{{ route('painelOperacao.carregarDadosModalStartService', [':emp',':nos',':req', ':seq']) }}";
        url = url.replace(':emp', emp);
        url = url.replace(':nos', nos);
        url = url.replace(':req', req);
        url = url.replace(':seq', seq);

        // Atualiza a url do formulário
        var formActionUrl = "{{ route('painelOperacao.iniciarTMO', ['empresa' => ':emp', 'numOS' => ':nos', 'requisicao' => ':req', 'servico' => ':seq']) }}";
        formActionUrl = formActionUrl.replace(':emp', emp)
                                    .replace(':nos', nos)
                                    .replace(':req', req)
                                    .replace(':seq', seq);

        $.ajax({
            url: url,
            type: 'GET',
            data: { 
                '_token': $('meta[name=csrf-token]').attr("content"),
                '_method': 'GET',
                "emp": emp,
                "nos": nos,
                "req": req,
                "seq": seq
            },
            success: function(data) {
                // Preenche o conteúdo do modal
                $('#modalContentStartService').html(data);

                // Reativa o DateRangePicker para o campo horaIniSrv
                $('#horaIniSrv').daterangepicker({
                    singleDatePicker: true,
                    showDropdowns: true,
                    minYear: 2000,
                    maxYear: parseInt(moment().format('YYYY'),10),
                    timePicker: true,
                    timePicker24Hour: true,
                    timePickerSeconds: false,
                    cancelButtonClasses: "btn-danger",
                    locale: {
                        format: "HH:mm"
                    },
                    startDate: moment(),
                });

                // Atualiza a ação do formulário
                $('#startServiceForm').attr('action', formActionUrl);

                // Abre o modal
                $('#modalStartService').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar dados:', error);
            }
        });
    }

    /* ******************** Fazer requisição AJAX para carregar os dados do modal de inicio da TMO ******************** */
    function openModalFinishService(emp, nos, req, seq) {

        var url = "{{ route('painelOperacao.carregarDadosModalFinishService', [':emp',':nos',':req', ':seq']) }}";
        url = url.replace(':emp', emp);
        url = url.replace(':nos', nos);
        url = url.replace(':req', req);
        url = url.replace(':seq', seq);

        // Atualiza a url do formulário
        var formActionUrl = "{{ route('painelOperacao.finalizarTMO', ['empresa' => ':emp', 'numOS' => ':nos', 'requisicao' => ':req', 'servico' => ':seq']) }}";
        formActionUrl = formActionUrl.replace(':emp', emp)
                                    .replace(':nos', nos)
                                    .replace(':req', req)
                                    .replace(':seq', seq);

        $.ajax({
            url: url,
            type: 'GET',
            data: { 
                '_token': $('meta[name=csrf-token]').attr("content"),
                '_method': 'GET',
                "emp": emp,
                "nos": nos,
                "req": req,
                "seq": seq
            },
            success: function(data) {
                // Preenche o conteúdo do modal
                $('#modalContentFinishService').html(data);

                // Reativa o DateRangePicker para o campo horaIniSrv
                $('#horaFinSrv').daterangepicker({
                    singleDatePicker: true,
                    showDropdowns: true,
                    minYear: 2000,
                    maxYear: parseInt(moment().format('YYYY'),10),
                    timePicker: true,
                    timePicker24Hour: true,
                    timePickerSeconds: false,
                    cancelButtonClasses: "btn-danger",
                    locale: {
                        format: "HH:mm"
                    },
                    startDate: moment(),
                });

                // Atualiza a ação do formulário
                $('#finishServiceForm').attr('action', formActionUrl);

                // Abre o modal
                $('#modalFinishService').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar dados:', error);
            }
        });
    }

    /* ******************** Fazer requisição AJAX para carregar os dados do modal de Adição/alteração de Prestador na TMO ******************** */
    function openModalAddChangePrt(emp, nos, req, seq) {

        var url = "{{ route('painelOperacao.carregarDadosModalAddChangePrt', [':emp',':nos',':req', ':seq']) }}";
        url = url.replace(':emp', emp);
        url = url.replace(':nos', nos);
        url = url.replace(':req', req);
        url = url.replace(':seq', seq);

        // Atualiza a url do formulário
        var formActionUrl = "{{ route('painelOperacao.addChangePrtTMO', ['empresa' => ':emp', 'numOS' => ':nos', 'requisicao' => ':req', 'servico' => ':seq']) }}";
        formActionUrl = formActionUrl.replace(':emp', emp)
                                    .replace(':nos', nos)
                                    .replace(':req', req)
                                    .replace(':seq', seq);

        $.ajax({
            url: url,
            type: 'GET',
            data: { 
                '_token': $('meta[name=csrf-token]').attr("content"),
                '_method': 'GET',
                "emp": emp,
                "nos": nos,
                "req": req,
                "seq": seq
            },
            success: function(data) {
                // Preenche o conteúdo do modal
                $('#modalContentAddChangePrt').html(data);

                // Inicializa ou reinicializa o DataTable
                $('#tabelaModalAddChangePrt').DataTable({
                    paging: false,
                    searching: false,
                    autoWidth: false,
                    order: [[0, 'asc']],
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
                    language: {
                        decimal: '',
                        emptyTable: 'Sem dados disponíveis na tabela',
                        info: '',
                        infoEmpty: '',
                        infoFiltered: '(Filtrado do total de _MAX_ registros)',
                        thousands: ',',
                        loadingRecords: 'Carregando...',
                        search: 'Pesquisar:',
                        zeroRecords: 'Nenhum registro correspondente encontrado',
                        paginate: {
                            first: 'Primeiro',
                            last: 'Último',
                            next: 'Próximo',
                            previous: 'Anterior'
                        },
                        aria: {
                            sortAscending: ': ativar para classificar a coluna em ordem crescente',
                            sortDescending: ': ativar para classificar a coluna em ordem decrescente'
                        }
                    }
                });

                // Atualiza a ação do formulário
                $('#addChangePrtForm').attr('action', formActionUrl);

                // Abre o modal
                $('#modalAddChangePrt').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar dados:', error);
            }
        });
    }

    /* ******************** Fazer requisição AJAX para carregar os dados do modal de cancelar a TMO ******************** */
    function openModalCancelService(emp, nos, req, seq) {

        var url = "{{ route('painelOperacao.carregarDadosModalCancelService', [':emp',':nos',':req', ':seq']) }}";
        url = url.replace(':emp', emp);
        url = url.replace(':nos', nos);
        url = url.replace(':req', req);
        url = url.replace(':seq', seq);

        // Atualiza a url do formulário
        var formActionUrl = "{{ route('painelOperacao.cancelarTMO', ['empresa' => ':emp', 'numOS' => ':nos', 'requisicao' => ':req', 'servico' => ':seq']) }}";
        formActionUrl = formActionUrl.replace(':emp', emp)
                                    .replace(':nos', nos)
                                    .replace(':req', req)
                                    .replace(':seq', seq);

        $.ajax({
            url: url,
            type: 'GET',
            data: { 
                '_token': $('meta[name=csrf-token]').attr("content"),
                '_method': 'GET',
                "emp": emp,
                "nos": nos,
                "req": req,
                "seq": seq
            },
            success: function(data) {
                // Preenche o conteúdo do modal
                $('#modalContentCancelService').html(data);

                // Atualiza a ação do formulário
                $('#cancelServiceForm').attr('action', formActionUrl);

                // Abre o modal
                $('#modalCancelService').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar dados:', error);
            }
        });
    }

    /* ******************** Fazer requisição AJAX para carregar os dados do modal de suspender a TMO ******************** */
    function openModalSuspendService(emp, nos, req, seq) {

        var url = "{{ route('painelOperacao.carregarDadosModalSuspendService', [':emp',':nos',':req', ':seq']) }}";
        url = url.replace(':emp', emp);
        url = url.replace(':nos', nos);
        url = url.replace(':req', req);
        url = url.replace(':seq', seq);

        // Atualiza a url do formulário
        var formActionUrl = "{{ route('painelOperacao.suspenderTMO', ['empresa' => ':emp', 'numOS' => ':nos', 'requisicao' => ':req', 'servico' => ':seq']) }}";
        formActionUrl = formActionUrl.replace(':emp', emp)
                                    .replace(':nos', nos)
                                    .replace(':req', req)
                                    .replace(':seq', seq);

        $.ajax({
            url: url,
            type: 'GET',
            data: { 
                '_token': $('meta[name=csrf-token]').attr("content"),
                '_method': 'GET',
                "emp": emp,
                "nos": nos,
                "req": req,
                "seq": seq
            },
            success: function(data) {
                // Preenche o conteúdo do modal
                $('#modalContentSuspendService').html(data);

                // Atualiza a ação do formulário
                $('#suspendServiceForm').attr('action', formActionUrl);

                // Abre o modal
                $('#modalSuspendService').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar dados:', error);
            }
        });
    }

    /* ******************** Fazer requisição AJAX para carregar os dados do modal de reabrir a TMO ******************** */
    function openModalReopenService(emp, nos, req, seq) {

        var url = "{{ route('painelOperacao.carregarDadosModalReopenService', [':emp',':nos',':req', ':seq']) }}";
        url = url.replace(':emp', emp);
        url = url.replace(':nos', nos);
        url = url.replace(':req', req);
        url = url.replace(':seq', seq);

        // Atualiza a url do formulário
        var formActionUrl = "{{ route('painelOperacao.reabrirTMO', ['empresa' => ':emp', 'numOS' => ':nos', 'requisicao' => ':req', 'servico' => ':seq']) }}";
        formActionUrl = formActionUrl.replace(':emp', emp)
                                    .replace(':nos', nos)
                                    .replace(':req', req)
                                    .replace(':seq', seq);

        $.ajax({
            url: url,
            type: 'GET',
            data: { 
                '_token': $('meta[name=csrf-token]').attr("content"),
                '_method': 'GET',
                "emp": emp,
                "nos": nos,
                "req": req,
                "seq": seq
            },
            success: function(data) {
                // Preenche o conteúdo do modal
                $('#modalContentReopenService').html(data);

                // Atualiza a ação do formulário
                $('#reopenServiceForm').attr('action', formActionUrl);

                // Abre o modal
                $('#modalReopenService').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar dados:', error);
            }
        });
    }

    /* ******************** Fazer requisição AJAX para carregar os dados do modal de finalizar a requisição ******************** */
    function openModalFinishRequisicao(emp, nos, req) {

        var url = "{{ route('painelOperacao.carregarDadosModalFinishRequisicao', [':emp',':nos',':req']) }}";
        url = url.replace(':emp', emp);
        url = url.replace(':nos', nos);
        url = url.replace(':req', req);

        // Atualiza a url do formulário
        var formActionUrl = "{{ route('painelOperacao.finalizarReqPO', ['empresa' => ':emp', 'numOS' => ':nos', 'requisicao' => ':req']) }}";
        formActionUrl = formActionUrl.replace(':emp', emp)
                                    .replace(':nos', nos)
                                    .replace(':req', req);

        $.ajax({
            url: url,
            type: 'GET',
            data: { 
                '_token': $('meta[name=csrf-token]').attr("content"),
                '_method': 'GET',
                "emp": emp,
                "nos": nos,
                "req": req
            },
            success: function(data) {
                // Preenche o conteúdo do modal
                $('#modalContentFinishRequisicao').html(data);

                // Atualiza a ação do formulário
                $('#finishRequisicaoForm').attr('action', formActionUrl);

                // Abre o modal
                $('#modalFinishRequisicao').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar dados:', error);
            }
        });
    }

    /* ******************** Fazer requisição AJAX para carregar os dados do modal de finalizar a requisição ******************** */
    function openModalReopenRequisicao(emp, nos, req) {

        var url = "{{ route('painelOperacao.carregarDadosModalReopenRequisicao', [':emp',':nos',':req']) }}";
        url = url.replace(':emp', emp);
        url = url.replace(':nos', nos);
        url = url.replace(':req', req);

        // Atualiza a url do formulário
        var formActionUrl = "{{ route('painelOperacao.reabrirReqPO', ['empresa' => ':emp', 'numOS' => ':nos', 'requisicao' => ':req']) }}";
        formActionUrl = formActionUrl.replace(':emp', emp)
                                    .replace(':nos', nos)
                                    .replace(':req', req);

        $.ajax({
            url: url,
            type: 'GET',
            data: { 
                '_token': $('meta[name=csrf-token]').attr("content"),
                '_method': 'GET',
                "emp": emp,
                "nos": nos,
                "req": req
            },
            success: function(data) {
                // Preenche o conteúdo do modal
                $('#modalContentReopenRequisicao').html(data);

                // Atualiza a ação do formulário
                $('#reopenRequisicaoForm').attr('action', formActionUrl);

                // Abre o modal
                $('#modalReopenRequisicao').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar dados:', error);
            }
        });
    }
    /* ------------------------------ Final dos Eventos de Abertura dos Modais ------------------------------ */
</script>

<!--
|--------------------------------------------------------------------------
| Eventos Validate da app
|--------------------------------------------------------------------------
-->
<script>
$(function () {
    $('#suspendServiceForm').validate({
        rules: {
            motivoSus: {
                required: true
            },
        },
        messages: {
            motivoSus: {
                required: "Por Favor informe o Motivo da Suspensão"
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

$(function () {
    $('#cancelServiceForm').validate({
        rules: {
            motivoCan: {
                required: true
            },
        },
        messages: {
            motivoCan: {
                required: "Por Favor informe o Motivo do Cancelamento"
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
            text: "{{ session('info') }}",
            icon: "info",
            customClass: {
                icon: "no-before-icon",
            }
        });
    @endif
</script>
@stop

