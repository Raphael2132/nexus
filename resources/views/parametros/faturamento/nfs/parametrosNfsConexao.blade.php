@extends('adminlte::page')

@section('title', 'Parâmetros da NFS-e')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Parâmetros Gerais</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.parFatNfs')}}">Parâmetros da NFS-e</a>
            </li>
            <li class="breadcrumb-item active">Conexão da NFS-e</li>
        </ol>
    </div>
</div>
@stop

@section('content')
@php
    $heads = [
        'Empresa',
        'Provedor',
        'Ambiente',
        'Usuario',
        'Senha',
        'Token',
        'URL / WSDL',
        ['label' => 'Editar', 'no-export' => true, 'width' => 5],
    ];

    //Variavel para o filtro
    $arraySelEmp = HelperArraySelect::arrayEmpresas(2,1);
    $arraySelPro = HelperArraySelect::arrayProvedores(2,1);
@endphp
<x-adminlte-card title="Parâmetros de Conexões da NFS-e" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
    <x-adminlte-datatable id="tabela-conexao" :heads="$heads" theme="light" striped hoverable>
        @foreach ($conexoes as $conexao)
            @php
                $data_emp = DB::table('cadastro_empresas')->where('empresa_codigo','=',$conexao->conexao_empresa)->get();
                $empresa = $conexao->conexao_empresa.' - '.$data_emp[0]->empresa_nome;
                
                if(!empty($conexao->conexao_provedor)){
                    $nomePro = DB::table('parametros_fat_nfs_provedores')->selectRaw('provedor_desc')->where('provedor_id','=',$conexao->conexao_provedor)->get();
                    $nomeProvedor = $conexao->conexao_provedor.' - '.$nomePro[0]->provedor_desc;
                }else{
                    $nomeProvedor ='Não Cadastrado';
                }

                if($conexao->conexao_ambiente =='H'){
                    $ambiente = 'Homologação';
                }else{
                    $ambiente = 'Produção';
                }
            @endphp
            <tr>
                <td>{{ $empresa }}</td>
                <td>{{ $nomeProvedor }}</td>  
                <td>{{ $ambiente }}</td> 
                <td>{{ $conexao->conexao_usuario }}</td>  
                <td>{{ $conexao->conexao_senha }}</td>  
                <td>{{ $conexao->conexao_token }}</td>    
                <td>{{ $conexao->conexao_wsdl }}</td>  
                <td>
                    <nobr>
                        <form method="get" action="{{ route('conexaoNFSe.edit', ['conexaoNFSe' => $conexao->conexao_empresa]) }}" style="float: left;">
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
    <x-slot name="footerSlot">
        <div class="d-flex justify-content-between w-100">
            <div class="d-flex">
            </div>
            <div class="d-flex">
                <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.parFatNfs') }}'" label="Voltar" theme="info" icon=""/>
            </div>
        </div>
    </x-slot>
</x-adminlte-card>
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
        var table = $('#tabela-conexao').DataTable({
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
                { visible: false },
                { visible: false },
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
                customSearchingField('#tabela-conexao_filter');

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
                $('#tabela-conexao_wrapper .btn-datatable-dir .tool-bar').prepend(buttonsTooBarHTML);

                /* ******************** Cards dos Botões Principais ******************** */

                /* ********** Botões do Card da Coluna ********** */
                let btnColunaHTML = `
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="0" href="#">Empresa</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="1" href="#">Provedor</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="2" href="#">Ambiente</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="3" href="#">Usuário</a>
                    <a class="btn btn-outline-nexus toggle-vis mr-2" data-column="4" href="#">Senha</a>
                    <a class="btn btn-outline-nexus toggle-vis mr-2" data-column="5" href="#">Token</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="6" href="#">URL / WSDL</a>
                `;

                /* ********** Monta o Card da Coluna ********** */
                let cardColunaHTML = getCardColunas(btnColunaHTML);

                /* ********** Adiciona o Card da Coluna ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-conexao_wrapper .linha-cards-menu').append(cardColunaHTML);

                /* ********** Campos do Card de Filtro ********** */
                let fieldFiltroHTML = `
                    <div class="row">
                        <x-adminlte-select name="filterEmp" label="Empresa" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="$arraySelEmp" empty-option="Selecione..."/>
                        </x-adminlte-select>
                        <x-adminlte-select name="filterPro" label="Provedor" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="$arraySelPro" empty-option="Selecione..."/>
                        </x-adminlte-select>
                        <x-adminlte-select name="filterAmb" label="Ambiente" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="['Homologação' => 'Homologação', 'Produção' => 'Produção']" empty-option="Selecione..."/>
                        </x-adminlte-select>
                    </div>
                `;

                /* ********** Monta o Card de Filtro ********** */
                let cardFiltroHTML = getCardFiltros(fieldFiltroHTML);

                /* ********** Adiciona o Card de Filtro ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-conexao_wrapper .linha-cards-menu').append(cardFiltroHTML);
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
                $('#tabela-conexao_wrapper').each(function() {
                    var $wrapper = $(this);  // Armazena o contexto atual do wrapper

                    $wrapper.find('#filterEmp').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(0).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterPro').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(1).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterAmb').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(2).search(selectedValue).draw();
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
        setupActionsColuna(table, '#tabela-conexao_wrapper');

        /* ------------------------------ Final dos Eventos de Controle do Card de Colunas e Funcionalidades ------------------------------ */

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Controle do Card de Filtro e Funcionalidades
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Chama a função de configuração de filtros
        setupActionsFiltro(table, '#tabela-conexao_wrapper');

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
</script>
@stop
