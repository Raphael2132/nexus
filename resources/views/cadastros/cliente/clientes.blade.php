@extends('adminlte::page')

@section('title', 'Cadastro de Clientes')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h4 style="margin-bottom: 0px !important;">Cadastros</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">
                    <a href="{{route('cadastroCliente.index')}}">Clientes</a>
                </li>
                <li class="breadcrumb-item active">Clientes Cadastrados</li>
            </ol>
        </div>
    </div>
@stop


@section('content')

@php
    $heads = [
        ['label' => '', 'no-export' => true, 'width' => 5],
        'Cliente',
        'Tipo de Cadastro',
        'Tipo de Pessoa',
        'CPF/CNPJ',
        'Email',
        ['label' => 'Opções', 'no-export' => true, 'width' => 10],
    ];

    if($tipo == 'J'){
        $titulo = 'Clientes Jurídicos Cadastrados';
    }elseif($tipo == 'F'){
        $titulo = 'Clientes Físicos Cadastrados';
    }elseif($tipo == 'M'){
        $titulo = 'Clientes Cadastrados no Mês';
    }elseif($tipo == 'CLI'){
        $titulo = 'Clientes Cadastrados';
    }elseif($tipo == 'CLF'){
        $titulo = 'Clientes Físicos Cadastrados';
    }elseif($tipo == 'CLJ'){
        $titulo = 'Clientes Jurídicos Cadastrados';
    }elseif($tipo == 'FOR'){
        $titulo = 'Fornecedores Cadastrados';
    }else{
        $titulo = 'Clientes Cadastrados';
    }
@endphp

<x-adminlte-card :title="$titulo" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
    <x-adminlte-datatable id="tabela-cliente" :heads="$heads" theme="light" striped hoverable>
        @foreach ($clientes as $cliente)
            @php
                if($cliente->cliente_tipo_pessoa == 'F'){
                    $tip_pess = 'Física';
                    $cpfcnpj = Helper::mascaraCPF($cliente->cliente_cpf_cnpj);
                }else{
                    $tip_pess = 'Jurídica';
                    $cpfcnpj = Helper::mascaraCNPJ($cliente->cliente_cpf_cnpj);
                }
            @endphp
            <tr>
                <td>
                    <nobr class="d-flex justify-content-center">
                        <!-- Cria o modal dos detalhes do usuario -->
                        <x-adminlte-modal id="modalCustom_{{$cliente->cliente_codigo}}" title="Detalhes do Usuario" size="xl" theme="modal-nexus" icon="fa-solid fa-address-card" v-centered scrollable>
                            <div class="row" style="height:auto;">
                                <!-- Conteudo da esquerda do modal -->
                                <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                                    <div class="post">
                                        <h4 class="text-primary">Dados do Cliente</h4>
                                        @php
                                            if(!empty($cliente->cliente_rg)){
                                                $rg = substr($cliente->cliente_rg,0,2).'.'.substr($cliente->cliente_rg,2,3).'.'.substr($cliente->cliente_rg,5,3).'-'.substr($cliente->cliente_rg,-1,1);
                                            }else{
                                                $rg = "Não Cadastrado";
                                            }

                                            if(!empty($cliente->cliente_data_nascimento)){
                                                $dataNasc = date('d/m/Y', strtotime($cliente->cliente_data_nascimento));
                                            }else{
                                                $dataNasc = "Não Cadastrado";
                                            }

                                            if(!empty($cliente->cliente_sexo)){
                                                if($cliente->cliente_sexo == 'M'){
                                                    $sexo = "Masculino";
                                                }else{
                                                    $sexo = "Feminino";
                                                }
                                            }else{
                                                $sexo = "Não Cadastrado";
                                            }

                                            if($cliente->cliente_tipo_cadastro == 'C'){
                                                $tipoCli = "Cliente";
                                            }else{
                                                $tipoCli = "Fornecedor";
                                            }

                                            if(!empty($cliente->cliente_insc_estadual)){
                                                $inscEst = $cliente->cliente_insc_estadual;
                                            }else{
                                                $inscEst = "Não Cadastrado";
                                            }

                                            if(!empty($cliente->cliente_insc_municipal)){
                                                $inscMuni = $cliente->cliente_insc_municipal;
                                            }else{
                                                $inscMuni = "Não Cadastrado";
                                            }
                                        @endphp
                                        <div class="text-muted">
                                            <div class="row">
                                                <p class="text-sm col-md-6">Tipo do Cadastro
                                                    <b class="d-block">{{ $tipoCli }}</b>
                                                </p>
                                                <p class="text-sm col-md-6">Tipo de Pessoa
                                                    <b class="d-block">{{ $tip_pess }}</b>
                                                </p>
                                            </div>
                                            <div class="row">
                                                <p class="text-sm col-md-6">Cliente
                                                    <b class="d-block">{{ $cliente->cliente_codigo }} - {{ $cliente->cliente_nome }}</b>
                                                </p>
                                                <p class="text-sm col-md-6">CPF / CNPJ
                                                    <b class="d-block">{{ $cpfcnpj }}</b>
                                                </p>
                                            </div>
                                            @if($cliente->cliente_tipo_pessoa == 'F')
                                            <div class="row">
                                                <p class="text-sm col-md-6">Data de Nascimento
                                                    <b class="d-block">{{ $dataNasc }}</b>
                                                </p>
                                                <p class="text-sm col-md-6">RG
                                                    <b class="d-block">{{ $rg }}</b>
                                                </p>
                                            </div>
                                            <div class="row">
                                                <p class="text-sm col-md-6">Sexo
                                                    <b class="d-block">{{ $sexo }}</b>
                                                </p>
                                            </div>
                                            @else
                                            <div class="row">
                                                <p class="text-sm col-md-6">Inscrição Estadual
                                                    <b class="d-block">{{ $inscEst }}</b>
                                                </p>
                                                <p class="text-sm col-md-6">Inscrição Municipal
                                                    <b class="d-block">{{ $inscMuni }}</b>
                                                </p>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!-- Conteudo da direita do modal -->
                                <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                                    <h4 class="text-primary">Endereço</h4>
                                    @php
                                        //Busca os dados do endereço da empresa e faz o tratamento de dados
                                        $endereco = DB::table('cadastro_cliente_enderecos')->where('endereco_cliente_codigo','=',$cliente->cliente_codigo)->where('endereco_principal','=','S')->get();
                                        
                                        //Se tiver endereço cadastrado gera os dados se não fica em branco
                                        if(!empty($endereco[0])){
                                            if(!empty($endereco[0]->endereco_numero)){
                                                $numero = $endereco[0]->endereco_numero;
                                            }else{
                                                $numero = "S/N";
                                            }
                                            if(!empty($endereco[0]->endereco_complemento)){
                                                $complemento = $endereco[0]->endereco_complemento;
                                            }else{
                                                $complemento = "";
                                            }
                                            $logradouro = $endereco[0]->endereco_logradouro;
                                            $bairro = $endereco[0]->endereco_bairro;
                                            $cep = substr($endereco[0]->endereco_cep,0,5).'-'.substr($endereco[0]->endereco_cep,-3,3);
                                            $uf = $endereco[0]->endereco_uf;
                                            $pais = $endereco[0]->endereco_pais;
                                            $cidade = $endereco[0]->endereco_cidade;
                                        }else{
                                            $numero = "";
                                            $complemento = "";
                                            $logradouro = "";
                                            $bairro = "";
                                            $cep = "";
                                            $uf = "";
                                            $pais = "";
                                            $cidade = "";
                                        }

                                        //Trata dados do contato
                                        if(!empty($cliente->cliente_tel_celular)){
                                            $telCelular = "(".substr($cliente->cliente_tel_celular,0,2).") ".substr($cliente->cliente_tel_celular,2,1)." ".substr($cliente->cliente_tel_celular,3,4)."-".substr($cliente->cliente_tel_celular,-4,4);
                                        }else{
                                            $telCelular = "Não Cadastrado";
                                        }

                                        if(!empty($cliente->cliente_tel_residencial)){
                                            $telResidencial = "(".substr($cliente->cliente_tel_residencial,0,2).") ".substr($cliente->cliente_tel_residencial,2,4)."-".substr($cliente->cliente_tel_residencial,-4,4);
                                        }else{
                                            $telResidencial = "Não Cadastrado";
                                        }

                                        if(!empty($cliente->cliente_email)){
                                            $email = $cliente->cliente_email;
                                        }else{
                                            $email = "Não Cadastrado";
                                        }
                                    @endphp
                                    <div class="col-sm-4 invoice-col">
                                        <!-- Se tiver endereço monta os dados -->
                                        <address>
                                            @if(!empty($cep))
                                                <strong>Principal</strong><br>
                                                {{$logradouro}}, {{$numero}}<br>
                                                @if(!empty($complemento)){{$complemento}}<br>@endif
                                                {{$cep}}<br>
                                                {{$bairro}}<br>
                                                {{$cidade}} - {{$uf}}<br>
                                                {{$pais}}
                                            @else
                                                <strong>Endereço não cadastrado</strong><br>
                                            @endif
                                        </address>
                                    </div>
                                    <h4 class="text-primary">Contato</h4>
                                    <!-- Dados do contato -->
                                    <div class="text-muted">
                                        <p class="text-sm">Telefone Celular
                                            <b class="d-block">{{ $telCelular }}</b>
                                        </p>
                                        <p class="text-sm">Telefone Residencial
                                            <b class="d-block">{{ $telResidencial }}</b>
                                        </p>
                                        <p class="text-sm">Email
                                            <b class="d-block">{{ $email }}</b>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <x-slot name="footerSlot">
                                <x-adminlte-button class="btn-nexus" theme="" label="Voltar" data-dismiss="modal"/>
                            </x-slot>
                        </x-adminlte-modal>
                        <!-- Gera o icone da lupa que abre o modal -->
                        <a class="lupa-consulta" data-toggle="modal" title="Visualisar Detalhes" data-target="#modalCustom_{{$cliente->cliente_codigo}}">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </a>
                    </nobr>
                </td>
                <td>{{ $cliente->cliente_codigo.' - '.$cliente->cliente_nome }}</td>
                <td>{{ $tipoCli }}</td>
                <td>{{ $tip_pess }}</td>
                <td>{{ $cpfcnpj }}</td>
                <td>{{ $cliente->cliente_email }}</td>
                <td>
                    <nobr class="d-flex justify-content-center">
                        <form method="get" action="{{ route('cadastroCliente.edit', ['cadastroCliente' => $cliente->cliente_codigo]) }}" style="float: left;">
                            @csrf
                            @method('get')

                            <!-- Informa o Tipo da Busca para a Edição -->
                            <input id="tipo" type="hidden" value="{{ $tipo }}" name="tipo">

                            <button class="btn btn-xs btn-outline-primary mx-1" title="Editar Registro" value="Edit" type="submit">
                                <i class="fa fa-lg fa-fw fa-pen"></i>
                            </button>
                        </form>
                        <form method="post" action="{{route('cadastroCliente.destroy', ['cadastroCliente' => $cliente])}}" style="float: left;">
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
            <form method="get" action="{{ route('cadastroCliente.create') }}">
                <x-adminlte-button class="btn-nexus" label="Novo Cliente" theme="" icon="fas fa-user-plus" type="submit"/>
            </form>
            <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('cadastroCliente.index') }}'" label="Voltar" theme="" icon=""/>
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
        
        /* ********** Inicializa o Datatable ********** */
        var table = $('#tabela-cliente').DataTable({
            dom: getDatatableDom(),
            buttons: getDatatableButtons(),
            lengthMenu: [5, 10, 25, 50, 100],
            pageLength: 10,
            language: dataTableLangPtBR,
            order: [
                [1, 'asc']
            ],
            pagingType: 'full_numbers',
            processing: true,
            columns: [
                { orderable: false },
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
                customSearchingField('#tabela-cliente_filter');

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
                $('#tabela-cliente_wrapper .btn-datatable-dir .tool-bar').prepend(buttonsTooBarHTML);

                /* ******************** Cards dos Botões Principais ******************** */

                /* ********** Botões do Card da Coluna ********** */
                let btnColunaHTML = `
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="1" href="#">Cliente</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="2" href="#">Tipo de Cadastro</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="3" href="#">Tipo de Pessoa</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="4" href="#">CPF / CNPJ</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="5" href="#">Email</a>
                `;

                /* ********** Monta o Card da Coluna ********** */
                let cardColunaHTML = getCardColunas(btnColunaHTML);

                /* ********** Adiciona o Card da Coluna ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-cliente_wrapper .linha-cards-menu').append(cardColunaHTML);

                /* ********** Campos do Card de Filtro ********** */
                let fieldFiltroHTML = `
                    <div class="row">
                        <x-adminlte-input name="filterCliente" type="text" label="Cliente" class="form-control" placeholder="Filtrar Cliente" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-select name="filterTipCad" label="Tipo de Cadastro" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="['Cliente' => 'Cliente', 'Fornecedor' => 'Fornecedor']" empty-option="Selecione..."/>
                        </x-adminlte-select>
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
                $('#tabela-cliente_wrapper .linha-cards-menu').append(cardFiltroHTML);
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
                $('#tabela-cliente_wrapper').each(function() {
                    var $wrapper = $(this);  // Armazena o contexto atual do wrapper

                    $wrapper.find('#filterCliente').on('keyup', function() {
                        table.column(1).search(this.value).draw();
                    });

                    $wrapper.find('#filterTipCad').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(2).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterTipo').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(3).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterCpfCnpj').on('keyup', function() {
                        table.column(4).search(this.value).draw();
                    });

                    $wrapper.find('#filterEmail').on('keyup', function() {
                        table.column(5).search(this.value).draw();
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
        setupActionsColuna(table, '#tabela-cliente_wrapper');

        /* ------------------------------ Final dos Eventos de Controle do Card de Colunas e Funcionalidades ------------------------------ */

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Controle do Card de Filtro e Funcionalidades
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Chama a função de configuração de filtros
        setupActionsFiltro(table, '#tabela-cliente_wrapper');

        /* ------------------------------ Final dos Eventos de Controle do Card de Filtro e Funcionalidades ------------------------------ */

    });

    /* ------------------------------ Final da Inicialização do Datatable ------------------------------ */
</script>
@stop
