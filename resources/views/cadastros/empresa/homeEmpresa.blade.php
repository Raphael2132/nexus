@extends('adminlte::page')

@section('title', 'Cadastro de Empresas')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Cadastros</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Empresas</li>
        </ol>
    </div>
</div>
@stop

@section('content')
@if(Auth::user()->usuario_tipo == 'M')
<div class="row">
    <div class="col-md-4">
        <x-adminlte-small-box title="Cadastro" text="Empresa" icon="fas fa-building-circle-check" theme="primary" url="{{ route('cadastroEmpresa.create') }}" url-text="Cadastrar Empresa"/>
    </div>
</div>
@endif
<div class="row">
    <div class="col-md-12"> 
        {{-- Setup data for datatables --}}
        @php
            $heads = [
                ['label' => '', 'no-export' => true, 'width' => 5],
                'Empresa',
                'CNPJ',
                'Inscrição Estadual',
                'Inscrição Municipal',
                ['label' => 'Opções', 'no-export' => true, 'width' => 5],
            ];

            //Variavel para o filtro
            $arraySelEmp = HelperArraySelect::arrayEmpresas(2,1);
        @endphp
        <x-adminlte-card  title="Empresas Cadastradas" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
            <x-adminlte-datatable id="tabela-home" :heads="$heads" theme="light" striped hoverable>
                @foreach ($empresas as $empresa)
                    @php
                        $cnpj = substr($empresa->empresa_cnpj,0,2).'.'.substr($empresa->empresa_cnpj,2,3).'.'.substr($empresa->empresa_cnpj,5,3).'/'.substr($empresa->empresa_cnpj,8,4).'-'.substr($empresa->empresa_cnpj,12,2);
                        
                        if(!empty($empresa->empresa_insc_estadual)){
                            $inscEstad = $empresa->empresa_insc_estadual;
                        }else{
                            $inscEstad = "Não Cadastrado";
                        }

                        if(!empty($empresa->empresa_insc_municipal)){
                            $inscMuni = $empresa->empresa_insc_municipal;
                        }else{
                            $inscMuni = "Não Cadastrado";
                        }
                    @endphp
                    <tr>
                        <td>
                            <nobr class="d-flex justify-content-center">
                                <!-- Cria o modal dos detalhes da empresa -->
                                <x-adminlte-modal id="modalCustom_{{$empresa->empresa_codigo}}" title="Detalhes da Empresa" size="xl" theme="modal-nexus" icon="fa-solid fa-building" v-centered scrollable>
                                    <div class="row" style="height:auto;">
                                        <!-- Conteudo da esquerda do modal -->
                                        <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                                            <div class="post">
                                                <h4 class="text-primary">Dados Gerais da Empresa</h4>
                                                <div class="text-muted">
                                                    <div class="row">
                                                        <p class="text-sm col-md-6">Empresa
                                                            <b class="d-block">{{ $empresa->empresa_codigo }} - {{ $empresa->empresa_nome }}</b>
                                                        </p>
                                                        <p class="text-sm col-md-6">CNPJ
                                                            <b class="d-block">{{ $cnpj }}</b>
                                                        </p>
                                                    </div>
                                                    <div class="row">
                                                        <p class="text-sm col-md-6">Inscrição Estadual
                                                            <b class="d-block">{{ $inscEstad }}</b>
                                                        </p>
                                                        <p class="text-sm col-md-6">Inscrição Municipal
                                                            <b class="d-block">{{ $inscMuni }}</b>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="post">
                                                <h4 class="text-primary">Módulos de Acesso</h4>
                                                <div class="text-muted">
                                                    @php 
                                                        $modulo = DB::table('parametros_sis_modulos')->where('modulo_empresa_codigo', $empresa->empresa_codigo)->first();
                                                    @endphp
                                                    <div class="row">
                                                        <p class="text-sm col-md-6">Data de Expiração 
                                                            <b class="d-block">{{ Helper::formataData($modulo->modulo_dt_validade) }}</b>
                                                        </p>
                                                        <p class="text-sm col-md-6">Qtd. de Usuários
                                                            <b class="d-block">{{ $modulo->modulo_qtd_usuarios }}</b>
                                                        </p>
                                                    </div>
                                                    <div class="row">
                                                        <p class="text-sm col-md-6">Módulo de Serviços
                                                            <b class="d-block">{{ Helper::formataSimNao($modulo->modulo_servico) }}</b>
                                                        </p>
                                                        <p class="text-sm col-md-6">Módulo de Emissão RPS
                                                            <b class="d-block">{{ Helper::formataSimNao($modulo->modulo_emissao_rps) }}</b>
                                                        </p>
                                                    </div>
                                                    <div class="row">
                                                        <p class="text-sm col-md-6">Módulo de Emissão NFS-e
                                                            <b class="d-block">{{ Helper::formataSimNao($modulo->modulo_emissao_nfs) }}</b>
                                                        </p>
                                                        <p class="text-sm col-md-6">Módulo de Emissão Simplificada NFS-e
                                                            <b class="d-block">{{ Helper::formataSimNao($modulo->modulo_emissao_nfs_simp) }}</b>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Conteudo da direita do modal -->
                                        <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                                            <h4 class="text-primary">Endereço</h4>
                                            @php
                                                //Busca os dados do endereço da empresa e faz o tratamento de dados
                                                $endereco = DB::table('cadastro_empresa_enderecos')->where('endereco_empresa_codigo','=',$empresa->empresa_codigo)->where('endereco_principal','=','S')->get();
                                                
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
                                                if($empresa->empresa_tel_celular){
                                                    $telCelular = "(".substr($empresa->empresa_tel_celular,0,2).") ".substr($empresa->empresa_tel_celular,2,1)." ".substr($empresa->empresa_tel_celular,3,4)."-".substr($empresa->empresa_tel_celular,-4,4);
                                                }else{
                                                    $telCelular = "Não Cadastrado";
                                                }

                                                if($empresa->empresa_tel_comercial){
                                                    $telComercial = "(".substr($empresa->empresa_tel_comercial,0,2).") ".substr($empresa->empresa_tel_comercial,2,4)."-".substr($empresa->empresa_tel_comercial,-4,4);
                                                }else{
                                                    $telComercial = "Não Cadastrado";
                                                }

                                                if($empresa->empresa_email){
                                                    $email = $empresa->empresa_email;
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
                                                <p class="text-sm">Telefone Comercial
                                                    <b class="d-block">{{ $telComercial }}</b>
                                                </p>
                                                <p class="text-sm">Email
                                                    <b class="d-block">{{ $email }}</b>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <x-slot name="footerSlot">
                                        <x-adminlte-button theme="info" label="Voltar" data-dismiss="modal"/>
                                    </x-slot>
                                </x-adminlte-modal>
                                <!-- Gera o icone da lupa que abre o modal -->
                                <a href="" class="lupa-consulta" data-toggle="modal" title="Detalhes da Empresa" data-target="#modalCustom_{{$empresa->empresa_codigo}}">
                                    <i class="fa-solid fa-magnifying-glass fa-lg" style="color: #74C0FC;"></i>
                                </a>
                            </nobr>                       
                        </td>
                        <td>{{ $empresa->empresa_codigo.' - '.$empresa->empresa_nome }}</td>
                        <td>{{ $cnpj }}</td>
                        <td>{{ $inscEstad }}</td>
                        <td>{{ $inscMuni }}</td>
                        <td>
                            <nobr class="d-flex justify-content-center">
                                <form method="get" action="{{ route('cadastroEmpresa.edit', ['cadastroEmpresa' => $empresa->empresa_codigo]) }}" style="float: left;">
                                    @csrf
                                    <button class="btn btn-xs btn-outline-primary mx-1" title="Editar Registro" value="Edit" type="submit">
                                        <i class="fa fa-lg fa-fw fa-pen"></i>
                                    </button>
                                </form>
                                @if(Auth::user()->usuario_tipo == 'M')
                                <form method="post" action="{{route('cadastroEmpresa.destroy', ['cadastroEmpresa' => $empresa])}}" style="float: left;">
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

        /* ********** Inicializa o Datatable ********** */
        var table = $('#tabela-home').DataTable({
            dom: getDatatableDom(),
            buttons: getDatatableButtons(),
            lengthMenu: [5, 10, 25, 50, 100],
            pageLength: 5,
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
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="1" href="#">Empresa</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="2" href="#">CNPJ</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="3" href="#">Inscrição Estadual</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="4" href="#">Inscrição Municipal</a>
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
                        <x-adminlte-input name="filterCNPJ" type="text" label="CNPJ" class="form-control" placeholder="Filtrar CNPJ" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterInsEst" type="text" label="Inscrição Estadual" class="form-control" placeholder="Filtrar Inscrição Estadual" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterInsMun" type="text" label="Inscrição Municipal" class="form-control" placeholder="Filtrar Inscrição Municipal" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
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

                    // Filtrando por Data
                    $wrapper.find('#filterEmp').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(1).search(selectedValue).draw();
                    });

                    // Filtrando por Cliente
                    $wrapper.find('#filterCNPJ').on('keyup', function() {
                        table.column(2).search(this.value).draw();
                    });

                    // Filtrando por Consultor
                    $wrapper.find('#filterInsEst').on('keyup', function() {
                        table.column(3).search(this.value).draw();
                    });

                    // Filtrando por Previsão de Entrega
                    $wrapper.find('#filterInsMun').on('keyup', function() {
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
</script>
@stop
