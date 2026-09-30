@extends('adminlte::page')

@section('title', 'Tipos de Serviço')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Parâmetros Gerais</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Tipos de Serviço</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="col-md-4">
    <x-adminlte-small-box title="Tipos" text="Serviço" icon="fas fa-list-ol" theme="primary" url="{{route('tipoServico.create')}}" url-text="Cadastrar"/>
</div>
<div class="col-md-12">
    {{-- Setup data for datatables --}}
    @php    
        $heads = [
            'Empresa',
            'Código',
            'Descrição',
            'Categoria',
            'Área',
            'Status',
            ['label' => 'Editar', 'no-export' => true, 'width' => 10],
        ];

        //Variavel para o filtro
        $arraySelEmp = HelperArraySelect::arrayEmpresas(2,1);
        $arraySelArea = HelperArraySelect::arrayAreaSetCadastrado(2,1);
        $arraySelCat = HelperArraySelect::arrayCategoriaAtend(2,1);
    @endphp
    <x-adminlte-card title="Tipos de Serviço Cadastrados" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
        <x-adminlte-datatable id="tabela-home" :heads="$heads" theme="light" striped hoverable>
            @foreach ($tipos as $tipo)
                @php 
                    $data = DB::table('cadastro_empresas')->select('empresa_codigo', 'empresa_nome')->where('empresa_codigo', $tipo->tipsrv_emp)->get();

                    $empresa = $tipo->tipsrv_emp.' - '.$data[0]->empresa_nome;

                    $data_cat = DB::table('lancamento_srv_categorias')->select('categoria_codigo', 'categoria_desc')->where('categoria_codigo', $tipo->tipsrv_cat)->get();

                    $categoria = $tipo->tipsrv_cat.' - '.$data_cat[0]->categoria_desc;

                    $data_are = DB::table('parametros_sis_areas')->select('area_codigo', 'area_desc')->where('area_codigo', $tipo->tipsrv_are)->get();

                    $area = $tipo->tipsrv_are.' - '.$data_are[0]->area_desc;

                    if($tipo->tipsrv_sts == 'A'){
                        $status = 'Ativado';
                    }else{
                        $status = 'Desativado';
                    }
                @endphp
                <tr>
                    <td>{{ $empresa }}</td>
                    <td>{{ $tipo->tipsrv_cod }}</td>
                    <td>{{ $tipo->tipsrv_nom }}</td>
                    <td>{{ $categoria }}</td>
                    <td>{{ $area }}</td>
                    <td>{{ $status }}</td>
                    <td>
                        <nobr class="d-flex justify-content-center">
                            <form method="get" action="{{route('tipoServico.edit', ['tipoServico' => $tipo])}}" style="float: left;">
                                @csrf
                                <button class="btn btn-xs btn-outline-primary mx-1" title="Editar Registro" value="Edit" type="submit">
                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                </button>
                            </form>
                            <form method="post" action="{{ route('tipoServico.destroy',['tipoServico' => $tipo]) }}" style="float: left;">
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
                null,
                { orderable: false }
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
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="1" href="#">Código</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="2" href="#">Descrição</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="3" href="#">Categoria</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="4" href="#">Área</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="5" href="#">Status</a>
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
                        <x-adminlte-input name="filterCod" type="text" label="Código" class="form-control" placeholder="Filtrar Código" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterDesc" type="text" label="Descrição" class="form-control" placeholder="Filtrar Descrição" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-select name="filterCat" label="Categoria" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="$arraySelCat" empty-option="Selecione..."/>
                        </x-adminlte-select>
                    </div>
                    <div class="row">
                        <x-adminlte-select name="filterArea" label="Área" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="$arraySelArea" empty-option="Selecione..."/>
                        </x-adminlte-select>
                        <x-adminlte-select name="filterSts" label="Situação" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="['Ativado' => 'Ativado', 'Desativado' => 'Desativado']" empty-option="Selecione..."/>
                        </x-adminlte-select>
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

                    $wrapper.find('#filterCod').on('keyup', function() {
                        table.column(1).search(this.value).draw();
                    });

                    $wrapper.find('#filterDesc').on('keyup', function() {
                        table.column(2).search(this.value).draw();
                    });

                    $wrapper.find('#filterCat').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(3).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterArea').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(4).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterSts').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(5).search(selectedValue).draw();
                    });
                });
                /* ------------------------------ Final dos Eventos dos Filtros Individuais ------------------------------ */
                
            }
        });

        /* ------------------------------ Final a Inicialização do Datatable ------------------------------ */

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
