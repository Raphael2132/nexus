@extends('adminlte::page')

@section('title', 'Cartão Corporativo')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Parâmetros Financeiro</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Cartão Corporativo</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="col-md-4">
    <x-adminlte-small-box title="Corporativo" text="Cartão" icon="fas fa-credit-card" theme="primary" url="{{route('financeiroCorporativo.create')}}" url-text="Cadastrar"/>
</div>
<div class="col-md-12">
    {{-- Setup data for datatables --}}
    @php
        $heads = [
            'Empresa',
            'Administradora',
            'Cartão',
            'Nome',
            'Situação',
            'Dia Fechamento',
            'Dia Vencimento',
            'Parcelas',
            ['label' => '', 'no-export' => true, 'width' => 10],
        ];

        //Variavel para o filtro
        $arraySelEmp = HelperArraySelect::arrayEmpresas(2,1);
        $arraySelAdm = HelperArraySelect::arrayTodosRazoesCardCorp(2,1);
    @endphp
    <x-adminlte-card title="Cartão Corporativo" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
        <x-adminlte-datatable id="tabela-home" :heads="$heads" theme="light" striped hoverable>
            @foreach ($dataParFinCardCorp as $cartao)
                @php
                    $empFormat = HelperFormatSelect::formataEmpresaCodigoNome($cartao->parcco_emp);
                    $admFormat = HelperFormatSelect::formataRazaoCodNom($cartao->parcco_adm);

                    if($cartao->parcco_sts == 'A'){
                        $situacao = 'Ativo';
                    }else{
                        $situacao = 'Desativado';
                    }
                @endphp
                <tr>
                    <td>{{ $empFormat }}</td>
                    <td>{{ $admFormat }}</td>
                    <td>{{ $cartao->parcco_num }}</td>
                    <td>{{ $cartao->parcco_nom }}</td>
                    <td>{{ $situacao }}</td>
                    <td>{{ $cartao->parcco_dif }}</td>
                    <td>{{ $cartao->parcco_div }}</td>
                    <td>{{ $cartao->parcco_par }}</td>
                    <td>
                        <nobr class="d-flex justify-content-center">
                            <form method="get" action="{{route('financeiroCorporativo.edit',['financeiroCorporativo' => $cartao])}}" style="float: left;">
                                @csrf
                                <button class="btn btn-xs btn-outline-primary mx-1" title="Editar Registro" value="Editar" type="submit">
                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                </button>
                            </form>
                            <form method="post" action="{{ route('financeiroCorporativo.destroy', ['financeiroCorporativo' => $cartao]) }}" style="float: left;">
                                @csrf 
                                @method('delete')
                                <button class="btn btn-xs btn-outline-danger mx-1" title="Excluir Registro" value="Excluir" type="submit" >
                                    <i class="fa fa-lg fa-fw fa-trash-can"></i>
                                </button>
                            </form>
                        </nobr>
                    </td>                
                </tr>
            @endforeach
        </x-adminlte-datatable>
    </x-adminlte-card>
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
        
        /* ********** Inicializa o Datatable ********** */
        var table = $('#tabela-home').DataTable({
            dom: getDatatableDom(),
            buttons: getDatatableButtons(),
            lengthMenu: [5, 10, 25, 50, 100],
            pageLength: 10,
            language: dataTableLangPtBR,
            order: [
                [0, 'asc'],
                [1, 'asc'],
                [2, 'asc']
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
                { orderable: false },
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
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="0" href="#">Empresa</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="1" href="#">Administradora</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="2" href="#">Cartão</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="3" href="#">Nome</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="4" href="#">Situação</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="5" href="#">Dia Fechamento</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="6" href="#">Dia Vencimento</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="7" href="#">Parcelas</a>
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
                        <x-adminlte-select name="filterAdm" label="Administradora" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="$arraySelAdm" empty-option="Selecione..."/>
                        </x-adminlte-select>
                        <x-adminlte-input name="filterCartao" type="text" label="Cartão" class="form-control" placeholder="Filtrar Cartão" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterNome" type="text" label="Nome" class="form-control" placeholder="Filtrar Nome" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                    </div>
                    <div class="row">
                        <x-adminlte-select name="filterSit" label="Situação" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="['Ativo' => 'Ativo', 'Desativado' => 'Desativado']" empty-option="Selecione..."/>
                        </x-adminlte-select>
                        <x-adminlte-input name="filterDiaFec" type="text" label="Dia Fechamento" class="form-control" placeholder="Filtrar Dia Fechamento" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterDiaVct" type="text" label="Dia Vencimento" class="form-control" placeholder="Filtrar Dia Vencimento" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterParcelas" type="text" label="Parcelas" class="form-control" placeholder="Filtrar Parcelas" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
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

                    $wrapper.find('#filterAdm').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(1).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterCartao').on('keyup', function() {
                        table.column(2).search(this.value).draw();
                    });

                    $wrapper.find('#filterNome').on('keyup', function() {
                        table.column(3).search(this.value).draw();
                    });

                    $wrapper.find('#filterSit').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(4).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterDiaFec').on('keyup', function() {
                        table.column(5).search(this.value).draw();
                    });

                    $wrapper.find('#filterDiaVct').on('keyup', function() {
                        table.column(6).search(this.value).draw();
                    });

                    $wrapper.find('#filterParcelas').on('keyup', function() {
                        table.column(7).search(this.value).draw();
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
