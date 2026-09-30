@extends('adminlte::page')

@section('title', 'Cadastro de Usuários')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Cadastros</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Usuários</li>
        </ol>
    </div>
</div>
@stop

@section('content')

<div class="row">
    <div class="col-md-3"> 
        <x-adminlte-small-box :title="$usuTot" text="Total" icon="fas fa-users" theme="info" url="{{ route('cadastroUsuario.show',['cadastroUsuario' => 'T']) }}" url-text="Detalhes de Todos Usuários"/>
    </div>
    <div class="col-md-3"> 
        <x-adminlte-small-box :title="$usuAtivo" text="Ativos" icon="fas fa-user-check" theme="success" url="{{ route('cadastroUsuario.show',['cadastroUsuario' => 'A']) }}" url-text="Detalhes de Usuários Ativos"/>
    </div>
    <div class="col-md-3"> 
        <x-adminlte-small-box :title="$usuDesat" text="Desativados" icon="fas fa-user-lock" theme="danger" url="{{ route('cadastroUsuario.show',['cadastroUsuario' => 'D']) }}" url-text="Detalhes de Usuários Desativados"/>
    </div>
    <div class="col-md-3"> 
        <x-adminlte-small-box title="Cadastro" text="Usuários" icon="fas fa-user-plus" theme="primary" url="{{ route('cadastroUsuario.create', ['tipo' => 'newCad']) }}" url-text="Cadastrar Usuário"/>
    </div>
</div>
    
<div class="row">

    <div class="col-md-3"> 
        <x-adminlte-info-box title="Administrador" :text="$usuAdm" icon="fa-solid fa-user-shield" url="{{ route('cadastroUsuario.show',['cadastroUsuario' => 'ADM']) }}" theme="gradient-teal"/>
        <x-adminlte-info-box title="Caixa" :text="$usuCaixa" icon="fa-solid fa-user-tag" url="{{ route('cadastroUsuario.show',['cadastroUsuario' => 'CX']) }}" theme="gradient-teal"/>
        <x-adminlte-info-box title="Consultor" :text="$usuCon" icon="fa-solid fa-user-headset" url="{{ route('cadastroUsuario.show',['cadastroUsuario' => 'CO']) }}" theme="gradient-teal"/>
        <x-adminlte-info-box title="Prestador" :text="$usuPrt" icon="fa-solid fa-user-helmet-safety" url="{{ route('cadastroUsuario.show',['cadastroUsuario' => 'PR']) }}" theme="gradient-teal"/>
    </div>

    <div class="col-md-9"> 
        {{-- Setup data for datatables --}}
        @php
            $heads = [
                ['label' => '', 'no-export' => true, 'width' => 5],
                'Empresa',
                'Usuário',
                'Tipo do Usuário',
                'Status',
                ['label' => 'Opção', 'no-export' => true, 'width' => 5],
            ];
            
            //Variavel para o filtro
            $arraySelEmp = HelperArraySelect::arrayEmpresas(2,1);
        @endphp

        <x-adminlte-card title="Usuários Cadastrados" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
            <x-adminlte-datatable id="tabela-home" :heads="$heads" theme="light" striped hoverable>
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
                        <td>
                            <nobr class="d-flex justify-content-center">
                                <!-- Cria o modal dos detalhes do usuario -->
                                <x-adminlte-modal id="modalCustom_{{$usuario->usuario_codigo}}" title="Detalhes do Usuario" size="xl" theme="modal-nexus" icon="fa-solid fa-address-card" v-centered scrollable>
                                    <div class="row" style="height:auto;">
                                        <!-- Conteudo da esquerda do modal -->
                                        <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                                            <div class="post">
                                                <h5 class="text-primary">Dados Gerais do Usuário</h5>
                                                @php
                                                    if(!empty($usuario->usuario_cpf)){
                                                        $cpf = substr($usuario->usuario_cpf,0,3).'.'.substr($usuario->usuario_cpf,3,3).'.'.substr($usuario->usuario_cpf,6,3).'-'.substr($usuario->usuario_cpf,-2,2);
                                                    }else{
                                                        $cpf = "Não Cadastrado";
                                                    }

                                                    if(!empty($usuario->usuario_rg)){
                                                        $rg = substr($usuario->usuario_rg,0,2).'.'.substr($usuario->usuario_rg,2,3).'.'.substr($usuario->usuario_rg,5,3).'-'.substr($usuario->usuario_rg,-1,1);
                                                    }else{
                                                        $rg = "Não Cadastrado";
                                                    }

                                                    if(!empty($usuario->usuario_data_nascimento)){
                                                        $dataNasc = date('d/m/Y', strtotime($usuario->usuario_data_nascimento));
                                                    }else{
                                                        $dataNasc = "Não Cadastrado";
                                                    }

                                                    if(!empty($usuario->usuario_sexo)){
                                                        if($usuario->usuario_sexo == 'M'){
                                                            $sexo = "Masculino";
                                                        }else{
                                                            $sexo = "Feminino";
                                                        }
                                                    }else{
                                                        $sexo = "Não Cadastrado";
                                                    }

                                                    $tipo = Helper::formataTipoUsuario($usuario->usuario_tipo);

                                                    if($usuario->usuario_status == 'A'){
                                                        $status = "Ativo";
                                                    }else{
                                                        $status = "Desativado";
                                                    }

                                                    $acessaCadastro = Helper::formataSimNao($usuario->usuario_acesso_cadastros);
                                                    $alteraPermissao = Helper::formataSimNao($usuario->usuario_altera_permissoes_acesso);
                                                    $acessaParametros = Helper::formataSimNao($usuario->usuario_acesso_pararametros);
                                                    $autDesconto = Helper::formataSimNao($usuario->usuario_aut_desc);
                                                    $acessaModSrv = Helper::formataSimNao($usuario->usuario_acesso_mod_servicos);
                                                    $acessaModNf = Helper::formataSimNao($usuario->usuario_acesso_mod_nf);
                                                @endphp
                                                <div class="text-muted">
                                                    <div class="row">
                                                        <p class="text-sm col-md-6">Empresa
                                                            <b class="d-block">{{ $usuario->usuario_empresa.' - '.$dataEmp[0]->empresa_nome }}</b>
                                                        </p>
                                                        <p class="text-sm col-md-6">Tipo do Usuário
                                                            <b class="d-block">{{ $tipo }}</b>
                                                        </p>
                                                    </div>
                                                    <div class="row">
                                                        <p class="text-sm col-md-6">Usuário
                                                            <b class="d-block">{{ $usuario->usuario_codigo }} - {{ $usuario->name }}</b>
                                                        </p>
                                                        <p class="text-sm col-md-6">Data de Nascimento
                                                            <b class="d-block">{{ $dataNasc }}</b>
                                                        </p>
                                                    </div>
                                                    <div class="row">
                                                        <p class="text-sm col-md-6">CPF
                                                            <b class="d-block">{{ $cpf }}</b>
                                                        </p>
                                                        <p class="text-sm col-md-6">RG
                                                            <b class="d-block">{{ $rg }}</b>
                                                        </p>
                                                    </div>
                                                    <div class="row">
                                                        <p class="text-sm col-md-6">Sexo
                                                            <b class="d-block">{{ $sexo }}</b>
                                                        </p>
                                                        <p class="text-sm col-md-6">Status do Usuário
                                                            <b class="d-block">{{ $status }}</b>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="post">
                                                <h5 class="text-primary">Módulos do Sistema</h5>
                                                <div class="text-muted">
                                                    <div class="row">
                                                        <p class="text-sm col-md-6">Acessa Área de Cadastros
                                                            <b class="d-block">{{ $acessaCadastro }}</b>
                                                        </p>
                                                        <p class="text-sm col-md-6">Acessa Área de Parametrização Geral
                                                            <b class="d-block">{{ $acessaParametros }}</b>
                                                        </p>
                                                    </div>
                                                    <div class="row">
                                                        <p class="text-sm col-md-6">Acessa o Módulo de Serviços
                                                            <b class="d-block">{{ $acessaModSrv }}</b>
                                                        </p>
                                                        <p class="text-sm col-md-6">Acessa o Módulo de Emissão de NFS-e
                                                            <b class="d-block">{{ $acessaModNf }}</b>
                                                        </p>
                                                    </div>
                                                </div>
                                                <h5 class="text-primary">Permissões do Sistema</h5>
                                                <div class="text-muted">
                                                    <div class="row">
                                                        <p class="text-sm col-md-6">Permissão de Autorização de Desconto
                                                            <b class="d-block">{{ $autDesconto }}</b>
                                                        </p>
                                                        <p class="text-sm col-md-6">Altera Permissões de Acesso dos Usuários
                                                            <b class="d-block">{{ $alteraPermissao }}</b>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Conteudo da direita do modal -->
                                        <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                                            <h5 class="text-primary">Endereço</h5>
                                            @php
                                                //Busca os dados do endereço da empresa e faz o tratamento de dados
                                                $endereco = DB::table('cadastro_usuario_enderecos')->where('endereco_usuario_codigo','=',$usuario->usuario_codigo)->where('endereco_principal','=','S')->get();
                                              
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
                                                if($usuario->usuario_tel_celular){
                                                    $telCelular = "(".substr($usuario->usuario_tel_celular,0,2).") ".substr($usuario->usuario_tel_celular,2,1)." ".substr($usuario->usuario_tel_celular,3,4)."-".substr($usuario->usuario_tel_celular,-4,4);
                                                }else{
                                                    $telCelular = "Não Cadastrado";
                                                }

                                                if($usuario->usuario_tel_residencial){
                                                    $telResidencial = "(".substr($usuario->usuario_tel_residencial,0,2).") ".substr($usuario->usuario_tel_residencial,2,4)."-".substr($usuario->usuario_tel_residencial,-4,4);
                                                }else{
                                                    $telResidencial = "Não Cadastrado";
                                                }

                                                if($usuario->email){
                                                    $email = $usuario->email;
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
                                            <h5 class="text-primary">Contato</h5>
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
                                <a href="" class="lupa-consulta" data-toggle="modal" title="Detalhes do Usuário" data-target="#modalCustom_{{$usuario->usuario_codigo}}">
                                    <i class="fa-solid fa-magnifying-glass" style="color: #74C0FC;"></i>
                                </a>
                            </nobr>
                        </td>   
                        <td>{{ $usuario->usuario_empresa.' - '.$dataEmp[0]->empresa_nome }}</td>
                        <td>{{ $usuario->usuario_codigo.' - '.$usuario->name }}</td>
                        <td>{{ $tip_usu }}</td>
                        <td>{{ $sts_usu }}</td>
                        <td>
                            <nobr class="d-flex justify-content-center">
                                <form method="get" action="{{ route('cadastroUsuario.edit', ['cadastroUsuario' => $usuario->usuario_codigo]) }}" style="float: left;">
                                    @csrf 
                                    <!-- Informa o Tipo da Busca para a Edição -->
                                    <input id="tipo" type="hidden" value="homeEdit" name="tipo">
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
                [1, 'asc'],
                [4, 'asc'],
                [3, 'asc'],
                [2, 'asc']
            ],
            pagingType: 'full_numbers',
            processing: true,
            columns: [
                { orderable: false },
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
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="1" href="#">Empresa</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="2" href="#">Usuário</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="3" href="#">Tipo do Usuário</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="4" href="#">Status</a>
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
                        <x-adminlte-input name="filterUsu" type="text" label="Usuário" class="form-control" placeholder="Filtrar Usuário" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-select name="filterTipUsu" label="Tipo do Usuário" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="['Administrador' => 'Administrador','Caixa' => 'Caixa','Consultor' => 'Consultor','Master' => 'Master','Prestador' => 'Prestador']" empty-option="Selecione..."/>
                        </x-adminlte-select>
                        <x-adminlte-select name="filterSts" label="Status" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="['Ativo' => 'Ativo','Desativado' => 'Desativado']" empty-option="Selecione..."/>
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
                        table.column(1).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterUsu').on('keyup', function() {
                        table.column(2).search(this.value).draw();
                    });

                    $wrapper.find('#filterTipUsu').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(3).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterSts').on('change', function() {
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
