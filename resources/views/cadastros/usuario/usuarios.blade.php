@extends('adminlte::page')

@section('title', 'Cadastro de Usuários')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Cadastros</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('cadastroUsuario.index')}}">Usuários</a>
            </li>
            <li class="breadcrumb-item active">Usuários Cadastrados</li>
        </ol>
    </div>
</div>
@stop

@section('content')

@php
    $heads = [
        'Empresa',
        'Usuário',
        'Email',
        'Tipo do Usuário',
        'Data de Inclusão',
        'Status',
        ['label' => 'Opção', 'no-export' => true, 'width' => 12],
    ];

    //Variavel para o filtro
    $arraySelEmp = HelperArraySelect::arrayEmpresas(2,1);

    if($tipo == 'T'){
        $titulo = 'Usuários Cadastrados';
    }elseif($tipo == 'A'){
        $titulo = 'Usuários Ativos Cadastrados';
    }elseif($tipo == 'D'){
        $titulo = 'Usuários Desativados Cadastrados';
    }elseif($tipo == 'ADM'){
        $titulo = 'Usuários Administradores Cadastrados';
    }elseif($tipo == 'PR'){
        $titulo = 'Usuários Prestadores Cadastrados';
    }elseif($tipo == 'CX'){
        $titulo = 'Usuários Caixa Cadastrados';
    }else{
        $titulo = 'Usuários Consultores Cadastrados';
    }
@endphp

<x-adminlte-card :title="$titulo" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
    <x-adminlte-datatable id="tabela-usuario" :heads="$heads" theme="light" striped hoverable>
        @foreach ($usuarios as $usuario)
            @php
                $tip_usu = Helper::formataTipoUsuario($usuario->usuario_tipo);
                
                if($usuario->usuario_status == 'A'){
                    $sts_usu = 'Ativo';
                }else{
                    $sts_usu = 'Desativado';
                }

                $dataEmp = DB::table('cadastro_empresas')->where('empresa_codigo', $usuario->usuario_empresa)->get();

            @endphp
            <tr>
                <td>{{ $usuario->usuario_empresa.' - '.$dataEmp[0]->empresa_nome }}</td>
                <td>{{ $usuario->usuario_codigo.' - '.$usuario->name }}</td>
                <td>{{ $usuario->email }}</td>
                <td>{{ $tip_usu }}</td>
                <td>{{ $usuario->created_at }}</td>
                <td>{{ $sts_usu }}</td>
                <td>
                    <nobr class="d-flex justify-content-center">
                        <form method="get" action="{{ route('cadastroUsuario.edit', ['cadastroUsuario' => $usuario->usuario_codigo]) }}" style="float: left;">
                            <!-- Informa o Tipo da Busca para a Edição -->
                            <input id="tipo" type="hidden" value="{{ $tipo }}" name="tipo">

                            @csrf 
                            <button class="btn btn-xs btn-outline-primary mx-1" title="Editar Registro" value="Edit" type="submit">
                                <i class="fa fa-lg fa-fw fa-pen"></i>
                            </button>
                        </form>
                        <form method="post" action="{{route('cadastroUsuario.destroy', ['cadastroUsuario' => $usuario])}}" style="float: left;">
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
            <form method="get" action="{{ route('cadastroUsuario.create') }}">
            @csrf 
                <x-adminlte-button class="btn-nexus" label="Novo Usuário" theme="" icon="fas fa-user-plus" type="submit"/>
            </form>
            <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('cadastroUsuario.index') }}'" label="Voltar" theme="" icon=""/>
        </div>
    </x-slot>
</x-adminlte-card>
@stop

<!-- Chamada dos Plugins usados na app -->  
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
        var table = $('#tabela-usuario').DataTable({
            dom: getDatatableDom(),
            buttons: getDatatableButtons(),
            lengthMenu: [5, 10, 25, 50, 100],
            pageLength: 10,
            language: dataTableLangPtBR,
            order: [
                [0, 'asc'],
                [5, 'asc'],
                [3, 'asc'],
                [1, 'asc'],
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
            columnDefs: [
                {
                    targets: 4,
                    render: getRenderDateFunction('DD/MM/YYYY')
                }
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
                customSearchingField('#tabela-usuario_filter');

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
                $('#tabela-usuario_wrapper .btn-datatable-dir .tool-bar').prepend(buttonsTooBarHTML);

                /* ******************** Cards dos Botões Principais ******************** */

                /* ********** Botões do Card da Coluna ********** */
                let btnColunaHTML = `
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="0" href="#">Empresa</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="1" href="#">Usuário</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="2" href="#">Email</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="3" href="#">Tipo do Usuário</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="4" href="#">Data de Inclusão</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="5" href="#">Status</a>
                `;

                /* ********** Monta o Card da Coluna ********** */
                let cardColunaHTML = getCardColunas(btnColunaHTML);

                /* ********** Adiciona o Card da Coluna ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-usuario_wrapper .linha-cards-menu').append(cardColunaHTML);

                /* ********** Campos do Card de Filtro ********** */
                let fieldFiltroHTML = `
                    <div class="row">
                        <x-adminlte-select name="filterEmp" label="Empresa" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="$arraySelEmp" empty-option="Selecione..."/>
                        </x-adminlte-select>
                        <x-adminlte-input name="filterUsu" type="text" label="Usuário" class="form-control" placeholder="Filtrar Usuário" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterEmail" type="text" label="Email" class="form-control" placeholder="Filtrar Email" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-select name="filterTipUsu" label="Tipo do Usuário" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="['Administrador' => 'Administrador','Caixa' => 'Caixa','Consultor' => 'Consultor','Master' => 'Master','Prestador' => 'Prestador']" empty-option="Selecione..."/>
                        </x-adminlte-select>
                    </div>
                    <div class="row">
                        <x-adminlte-input name="filterDataInc" type="text" label="Data de Inclusão" class="form-control" placeholder="Filtrar Data de Inclusão" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-select name="filterSts" label="Status" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="['Ativo' => 'Ativo','Desativado' => 'Desativado']" empty-option="Selecione..."/>
                        </x-adminlte-select>
                    </div>
                `;

                /* ********** Monta o Card de Filtro ********** */
                let cardFiltroHTML = getCardFiltros(fieldFiltroHTML);

                /* ********** Adiciona o Card de Filtro ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-usuario_wrapper .linha-cards-menu').append(cardFiltroHTML);
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
                $('#tabela-usuario_wrapper').each(function() {
                    var $wrapper = $(this);  // Armazena o contexto atual do wrapper

                    $wrapper.find('#filterEmp').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(0).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterUsu').on('keyup', function() {
                        table.column(1).search(this.value).draw();
                    });

                    $wrapper.find('#filterEmail').on('keyup', function() {
                        table.column(2).search(this.value).draw();
                    });

                    $wrapper.find('#filterTipUsu').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(3).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterDataInc').on('keyup', function() {
                        table.column(4).search(this.value).draw();
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

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Controle do Card de Colunas e Funcionalidades
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Chama a função para adicionar os eventos de visibilidade das colunas
        setupActionsColuna(table, '#tabela-usuario_wrapper');

        /* ------------------------------ Final dos Eventos de Controle do Card de Colunas e Funcionalidades ------------------------------ */

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Controle do Card de Filtro e Funcionalidades
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Chama a função de configuração de filtros
        setupActionsFiltro(table, '#tabela-usuario_wrapper');

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
