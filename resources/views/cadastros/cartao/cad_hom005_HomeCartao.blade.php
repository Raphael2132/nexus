@extends('adminlte::page')

@section('title', 'Cadastro de Administradora de Cartão')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Cadastros</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Administradora de Cartão</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-md-3">
        <x-adminlte-small-box title="Cadastro" text="Administradora de Cartão" icon="fas fa-chart-pie-simple-circle-dollar" theme="primary" url="{{ route('cadastroCartao.create') }}" url-text="Cadastrar Adm. Cartão"/>
    </div>
</div>
<div class="row">
    <div class="col-md-12"> 
        @php
            $heads = [
                'Administradora',
                'CNPJ',
                'Tipo',
                'Qtd. Parcelas Crédito',
                ['label' => 'Opções', 'no-export' => true, 'width' => 5],
            ];

            //Variavel para o filtro
            $arraySelAdm = HelperArraySelect::arrayAdministradoras(2,1);
        @endphp
        <x-adminlte-card  title="Administradoras Cadastradas" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
            <x-adminlte-datatable id="tabela-home" :heads="$heads" theme="light" striped hoverable>
                @foreach ($cartoes as $cartao)
                    <tr>
                        @php 
                            $adm = $cartao->administradora_codigo.' - '.$cartao->administradora_nome;
                            $cnpj = Helper::mascaraCNPJ($cartao->administradora_cnpj);

                            if($cartao->administradora_tipo == 'C'){
                                $tipo = 'Crédito';
                            }elseif($cartao->administradora_tipo == 'D'){
                                $tipo = 'Débito';
                            }else{
                                $tipo = 'Crédito / Débito';
                            }
                        @endphp

                        <td>{{ $adm }}</td>
                        <td>{{ $cnpj }}</td>
                        <td>{{ $tipo }}</td>
                        <td>{{ $cartao->administradora_parcela }}</td>
                        <td>
                            <nobr class="d-flex justify-content-center">
                                <form method="get" action="{{ route('cadastroCartao.edit', ['cadastroCartao' => $cartao->administradora_codigo]) }}" style="float: left;">
                                    @csrf
                                    <button class="btn btn-xs btn-outline-primary mx-1" title="Editar Registro" value="Edit" type="submit">
                                        <i class="fa fa-lg fa-fw fa-pen"></i>
                                    </button>
                                </form>
                                @if(Auth::user()->usuario_tipo == 'M')
                                <form method="post" action="{{ route('cadastroCartao.destroy', ['cadastroCartao' => $cartao]) }}" style="float: left;">
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
                [0, 'asc']
            ],
            pagingType: 'full_numbers',
            processing: true,
            columns: [
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
                let buttonsTooBarHTML = getButtonsTollBar(['colunas','filtro']);

                /* ********** Adicionando os botões na barra de ferramentas ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-home_wrapper .btn-datatable-dir .tool-bar').prepend(buttonsTooBarHTML);

                /* ******************** Cards dos Botões Principais ******************** */

                /* ********** Botões do Card da Coluna ********** */
                let btnColunaHTML = `
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="0" href="#">Administradora</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="1" href="#">CNPJ</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="2" href="#">Tipo</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="3" href="#">Qtd. Parcelas Crédito</a>
                `;

                /* ********** Monta o Card da Coluna ********** */
                let cardColunaHTML = getCardColunas(btnColunaHTML);

                /* ********** Adiciona o Card da Coluna ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-home_wrapper .linha-cards-menu').append(cardColunaHTML);

                /* ********** Campos do Card de Filtro ********** */
                let fieldFiltroHTML = `
                    <div class="row">
                        <x-adminlte-select name="filterAdm" label="Administradora" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="$arraySelAdm" empty-option="Selecione..."/>
                        </x-adminlte-select>
                        <x-adminlte-input name="filterCNPJ" type="text" label="CNPJ" class="form-control" placeholder="Filtrar CNPJ" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-select name="filterTip" label="Tipo" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="['Crédito' => 'Crédito', 'Débito' => 'Débito', 'Crédito / Débito' => 'Crédito / Débito']" empty-option="Selecione..."/>
                        </x-adminlte-select>
                        <x-adminlte-input name="filterPar" type="text" label="Qtd. Parcelas Crédito" class="form-control" placeholder="Filtrar Parcelas" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
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

                    $wrapper.find('#filterAdm').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(0).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterCNPJ').on('keyup', function() {
                        table.column(1).search(this.value).draw();
                    });

                    $wrapper.find('#filterTip').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(2).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterPar').on('keyup', function() {
                        table.column(3).search(this.value).draw();
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
