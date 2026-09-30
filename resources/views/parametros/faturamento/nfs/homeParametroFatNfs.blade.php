@extends('adminlte::page')

@section('title', 'Parâmetros da NFS-e')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Parâmetros Gerais</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Parâmetros da NFS-e</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="esquerdo col-md-9">
        @php
            $heads = [
                'Empresa',
                'Gera NFS-e',
                'Provedor',
                'Impressão RPS',
                'Impressão NFS-e',
                ['label' => 'Editar', 'no-export' => true, 'width' => 5],
            ];

            $arraySelEmp = HelperArraySelect::arrayEmpresas(2,1);
            $arraySelPro = HelperArraySelect::arrayProvedores(2,1);
        @endphp
        <x-adminlte-card title="Parâmetros da Emissão de NFS-e" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
            <x-adminlte-datatable id="tabela-emissao" :heads="$heads" theme="light" striped hoverable>
                @foreach ($emiNfs as $nfs)
                    @php
                        $data_emp = DB::table('cadastro_empresas')->where('empresa_codigo','=',$nfs->parnfs_empresa)->get();
                        $empresa = $nfs->parnfs_empresa.' - '.$data_emp[0]->empresa_nome;

                        if(!empty($nfs->parnfs_provedor)){
                            $data_prov = DB::table('parametros_fat_nfs_provedores')->where('provedor_codigo', $nfs->parnfs_provedor)->get();
                            $provedor = $nfs->parnfs_provedor.' - '.$data_prov[0]->provedor_desc;
                        }else{
                            $provedor = 'Não Cadastrado';
                        }

                        $utilizaNFS = Helper::formataSimNao($nfs->parnfs_utiliza_nfs);
                        $imprimeRPS = Helper::formataSimNao($nfs->parnfs_impressao_rps);
                        $imprimeNFS = Helper::formataSimNao($nfs->parnfs_impressao_nfs);
                    @endphp
                    <tr>
                        <td>{{ $empresa }}</td>
                        <td>{{ $utilizaNFS }}</td>
                        <td>{{ $provedor }}</td>
                        <td>{{ $imprimeRPS }}</td>
                        <td>{{ $imprimeNFS }}</td>
                        <td>
                            <nobr>
                                <form method="get" action="{{ route('emissaoNFSe.edit', ['emissaoNFSe' => $nfs->parnfs_empresa]) }}" style="float: left;">
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

        {{-- Setup data for datatables --}}
        @php
            $heads2 = [
                'Empresa',
                'Provedor',
                'Ambiente',
                ['label' => 'Editar', 'no-export' => true, 'width' => 5],
            ];
        @endphp
        <x-adminlte-card title="Parâmetros de Conexões da NFS-e" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
            <x-adminlte-datatable id="tabela-conexao" :heads="$heads2" theme="light" striped hoverable>
                @foreach ($conNfs as $nfsCon)
                    @php
                        $data_emp2 = DB::table('cadastro_empresas')->where('empresa_codigo','=',$nfsCon->conexao_empresa)->get();
                        $empresa2 = $nfsCon->conexao_empresa.' - '.$data_emp2[0]->empresa_nome;

                        if(!empty($nfsCon->conexao_provedor)){
                            $data_prov2 = DB::table('parametros_fat_nfs_provedores')->where('provedor_codigo','=',$nfsCon->conexao_provedor)->get();
                            $provedor2 = $nfsCon->conexao_provedor.' - '.$data_prov2[0]->provedor_desc;
                        }else{
                            $provedor2 = 'Não Cadastrado';
                        }
                        if($nfsCon->conexao_ambiente == 'H'){
                            $ambiente = 'Homologação';
                        }else{
                            $ambiente = 'Produção';
                        }
                    @endphp
                    <tr>
                        <td>{{ $empresa2 }}</td>
                        <td>{{ $provedor2 }}</td>
                        <td>{{ $ambiente }}</td>
                        <td>
                            <nobr>
                                <form method="get" action="{{ route('conexaoNFSe.edit', ['conexaoNFSe' => $nfsCon->conexao_empresa]) }}" style="float: left;">
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
    <div class="direito col-md-3">
        <x-adminlte-small-box title="NFS-e" text="Emissão" icon="fas fa-file-export" theme="primary" url="{{ route('emissaoNFSe.index') }}" url-text="Detalhes"/>
        <x-adminlte-small-box title="NFS-e" text="Conexão" icon="fas fa-wifi" theme="success" url="{{ route('conexaoNFSe.index') }}" url-text="Detalhes"/>
        @if(Auth::user()->usuario_tipo == 'M')
        <x-adminlte-small-box title="NFS-e" text="Provedor" icon="fas fa-server" theme="danger" url="{{ route('provedorNFSe.index') }}" url-text="Cadastrar"/>
        @endif
    </div>
</div>
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('css')
<style>
    .esquerdo {
        float: left;
    }

    .direito {
        float: right;
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
        
        /* ********** Inicializa o Datatable ********** */
        var table = $('#tabela-emissao').DataTable({
            dom: getDatatableDom(),
            buttons: getDatatableButtons(),
            lengthMenu: [5, 10, 25, 50, 100],
            pageLength: 5,
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
                customSearchingField('#tabela-emissao_filter');

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
                $('#tabela-emissao_wrapper .btn-datatable-dir .tool-bar').prepend(buttonsTooBarHTML);

                /* ******************** Cards dos Botões Principais ******************** */

                /* ********** Botões do Card da Coluna ********** */
                let btnColunaHTML = `
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="0" href="#">Empresa</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="1" href="#">Gera NFS-e</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="2" href="#">Provedor</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="3" href="#">Impressão de RPS</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="4" href="#">Impressão de NFS-e</a>
                `;

                /* ********** Monta o Card da Coluna ********** */
                let cardColunaHTML = getCardColunas(btnColunaHTML);

                /* ********** Adiciona o Card da Coluna ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-emissao_wrapper .linha-cards-menu').append(cardColunaHTML);

                /* ********** Campos do Card de Filtro ********** */
                let fieldFiltroHTML = `
                    <div class="row">
                        <x-adminlte-select name="filterEmp" label="Empresa" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="$arraySelEmp" empty-option="Selecione..."/>
                        </x-adminlte-select>
                        <x-adminlte-select name="filterGerNFS" label="Gera NFS-e" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="['Sim' => 'Sim', 'Não' => 'Não']" empty-option="Selecione..."/>
                        </x-adminlte-select>
                        <x-adminlte-select name="filterPro" label="Provedor" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="$arraySelPro" empty-option="Selecione..."/>
                        </x-adminlte-select>
                        <x-adminlte-select name="filterImpRPS" label="Impressão de RPS" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="['Sim' => 'Sim', 'Não' => 'Não']" empty-option="Selecione..."/>
                        </x-adminlte-select>
                    </div>
                    <div class="row">
                        <x-adminlte-select name="filterImpNFS" label="Impressão de NFS-e" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="['Sim' => 'Sim', 'Não' => 'Não']" empty-option="Selecione..."/>
                        </x-adminlte-select>
                    </div>
                `;

                /* ********** Monta o Card de Filtro ********** */
                let cardFiltroHTML = getCardFiltros(fieldFiltroHTML);

                /* ********** Adiciona o Card de Filtro ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-emissao_wrapper .linha-cards-menu').append(cardFiltroHTML);
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
                $('#tabela-emissao_wrapper').each(function() {
                    var $wrapper = $(this);  // Armazena o contexto atual do wrapper

                    $wrapper.find('#filterEmp').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(0).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterGerNFS').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(1).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterPro').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(2).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterImpRPS').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(3).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterImpNFS').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(4).search(selectedValue).draw();
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
        setupActionsColuna(table, '#tabela-emissao_wrapper');

        /* ------------------------------ Final dos Eventos de Controle do Card de Colunas e Funcionalidades ------------------------------ */

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Controle do Card de Filtro e Funcionalidades
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Chama a função de configuração de filtros
        setupActionsFiltro(table, '#tabela-emissao_wrapper');

        /* ------------------------------ Final dos Eventos de Controle do Card de Filtro e Funcionalidades ------------------------------ */

        /* ********** Inicializa o Datatable ********** */
        var tableCon = $('#tabela-conexao').DataTable({
            dom: getDatatableDom(),
            buttons: getDatatableButtons(),
            lengthMenu: [5, 10, 25, 50, 100],
            pageLength: 5,
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
                        tableCon.column(0).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterPro').on('change', function() {
                        var selectedValue = $(this).val();
                        tableCon.column(1).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterAmb').on('change', function() {
                        var selectedValue = $(this).val();
                        tableCon.column(2).search(selectedValue).draw();
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
        setupActionsColuna(tableCon, '#tabela-conexao_wrapper');

        /* ------------------------------ Final dos Eventos de Controle do Card de Colunas e Funcionalidades ------------------------------ */

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Controle do Card de Filtro e Funcionalidades
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Chama a função de configuração de filtros
        setupActionsFiltro(tableCon, '#tabela-conexao_wrapper');

        /* ------------------------------ Final dos Eventos de Controle do Card de Filtro e Funcionalidades ------------------------------ */

    });

    /* ------------------------------ Final da Inicialização do Datatable ------------------------------ */
</script>
@stop
