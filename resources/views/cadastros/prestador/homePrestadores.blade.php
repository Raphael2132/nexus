@extends('adminlte::page')

@section('title', 'Cadastro de Prestadores')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Cadastros</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Prestadores</li>
        </ol>
    </div>
</div>
@stop


@section('content')

<div class="row">
    <div class="col-md-3"> 
        <x-adminlte-small-box :title="$totalPrestadores" text="Total" icon="fas fa-users-gear" theme="info" url="{{ route('cadastroPrestador.show',['cadastroPrestador' => 'T']) }}" url-text="Detalhes de Todos Prestadores"/>
    </div>
    <div class="col-md-3"> 
        <x-adminlte-small-box :title="$prestadoresAtivos" text="Ativos" icon="fas fa-user-check" theme="success" url="{{ route('cadastroPrestador.show',['cadastroPrestador' => 'A']) }}" url-text="Detalhes de Prestadores Ativos"/>
    </div>
    <div class="col-md-3"> 
        <x-adminlte-small-box :title="$prestadoresDemitidos" text="Demitidos" icon="fas fa-user-xmark" theme="danger" url="{{ route('cadastroPrestador.show',['cadastroPrestador' => 'D']) }}" url-text="Detalhes de Prestadores Demitidos"/>
    </div>
    <div class="col-md-3"> 
        <x-adminlte-small-box title="Cadastro" text="Prestadores" icon="fas fa-user-plus" theme="primary" url="{{ route('cadastroPrestador.create') }}" url-text="Cadastrar Prestador"/>
    </div>
</div>
    
<div class="row">
    <div class="col-md-12"> 
        {{-- Setup data for datatables --}}
        @php
            $heads = [
                ['label' => '', 'no-export' => true, 'width' => 5],
                'Empresa',
                'Prestador',
                'CPF',
                'Setor',
                'Acessa Sis.',
                'Status',
                ['label' => 'Opção', 'no-export' => true, 'width' => 5],
            ];

            //Variavel para o filtro
            $arraySelEmp = HelperArraySelect::arrayEmpresas(2,1);
            $arraySelSetor = HelperArraySelect::arraySetor(2,1);
        @endphp

        <x-adminlte-card title="Prestadores Cadastrados" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
            <x-adminlte-datatable id="tabela-home" :heads="$heads" theme="light" striped hoverable>
                @foreach ($prestadores as $prestador)
                    @php 
                        $dadosSet = DB::table('parametros_sis_setores')->where('setor_codigo', $prestador->prestador_set)->where('setor_empresa', $prestador->prestador_empresa)->get();
                        $dadosArea = DB::table('parametros_sis_areas')->where('area_codigo', $dadosSet[0]->setor_area)->get();
                        $dataEmp = DB::table('cadastro_empresas')->where('empresa_codigo', $prestador->prestador_empresa)->first();

                        if($prestador->prestador_acesso_sis == 'N'){
                            $acessoSis = 'Não';
                        }else{
                            $acessoSis = 'Sim';
                        }

                        if($prestador->prestador_status == 'A'){
                            $sts = 'Ativo';
                        }else{
                            $sts = 'Demitido';
                        }
                    @endphp
                    <tr>
                        <td>
                            <nobr class="d-flex justify-content-center">
                                <x-adminlte-modal id="modalCustom_{{$prestador->prestador_codigo}}" title="Detalhes do Prestador" size="xl" theme="modal-nexus" icon="fa-solid fa-clipboard-user" v-centered scrollable>
                                    <div class="row" style="height:auto;">
                                        <!-- Conteudo da esquerda do modal -->
                                        <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                                            <div class="post">
                                                <h4 class="text-primary">Dados Gerais do Prestador</h4>
                                                @php
                                                    if(!empty($prestador->prestador_sexo)){
                                                        if($prestador->prestador_sexo == 'M'){
                                                            $sexo = 'Masculino';
                                                        }else{
                                                            $sexo = "Feminino";
                                                        }
                                                    }else{
                                                        $sexo = "Não Cadastrado";
                                                    }

                                                    if(!empty($prestador->prestador_rg)){
                                                        $rg = Helper::mascaraRG($prestador->prestador_rg);
                                                    }else{
                                                        $rg = "Não Cadastrado";
                                                    }

                                                    if(!empty($prestador->prestador_data_nascimento)){
                                                        $dataNas = Helper::formataData($prestador->prestador_data_nascimento);
                                                    }else{
                                                        $dataNas = "Não Cadastrado";
                                                    }
                                                @endphp
                                                <div class="text-muted">
                                                    <div class="row">
                                                        <p class="text-sm col-md-6">Prestador
                                                            <b class="d-block">{{ $prestador->prestador_codigo.' - '.$prestador->prestador_nome }}</b>
                                                        </p>
                                                        <p class="text-sm col-md-6">CPF
                                                            <b class="d-block">{{ Helper::mascaraCPF($prestador->prestador_cpf) }}</b>
                                                        </p>
                                                    </div>
                                                    <div class="row">
                                                        <p class="text-sm col-md-4">RG
                                                            <b class="d-block ">{{ $rg }}</b>
                                                        </p>
                                                        <p class="text-sm col-md-4">Data Nascimento
                                                            <b class="d-block">{{ $dataNas }}</b>
                                                        </p>
                                                        <p class="text-sm col-md-4">Sexo
                                                            <b class="d-block">{{ $sexo }}</b>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="post">
                                                <h4 class="text-primary">Detalhes do Contrato</h4>
                                                @php
                                                    if($prestador->prestador_sexo == 'M'){
                                                        $sexo = 'Masculino';
                                                    }else{
                                                        $sexo = "Feminino";
                                                    }

                                                    if(!empty($prestador->prestador_data_admissao)){
                                                        $dataAdm = Helper::formataData($prestador->prestador_data_admissao);
                                                    }else{
                                                        $dataAdm = '';
                                                    }

                                                    if(!empty($prestador->prestador_data_demissao)){
                                                        $dataDem = Helper::formataData($prestador->prestador_data_demissao);
                                                    }else{
                                                        $dataDem = '';
                                                    }

                                                    $dataTur = DB::table('parametros_ger_turnos')->where('partur_emp', $prestador->prestador_empresa)->where('partur_cod', $prestador->prestador_tur_cod)->first();
                                                @endphp   
                                                <div class="text-muted">
                                                    <div class="row">
                                                        <p class="text-sm col-md-4">Situação
                                                            <b class="d-block">{{ $sts }}</b>
                                                        </p>
                                                        <p class="text-sm col-md-4">Data de Admissão
                                                            <b class="d-block">{{ $dataAdm }}</b>
                                                        </p>
                                                        <p class="text-sm col-md-4">Data de Demissão
                                                            <b class="d-block">{{ $dataDem }}</b>
                                                        </p>
                                                    </div>
                                                    <div class="row">
                                                        <p class="text-sm col-md-4">Área
                                                            <b class="d-block">{{ $dadosSet[0]->setor_area.' - '.$dadosArea[0]->area_desc }}</b>
                                                        </p>
                                                        <p class="text-sm col-md-4">Setor
                                                            <b class="d-block">{{ $prestador->prestador_set.' - '.$dadosSet[0]->setor_desc }}</b>
                                                        </p>
                                                        <p class="text-sm col-md-4">Turno
                                                            <b class="d-block">{{ $prestador->prestador_tur_cod.' - '.$dataTur->partur_desc }}</b>
                                                        </p>
                                                    </div>
                                                    <div class="row">
                                                        <p class="text-sm col-md-4">Acessa Sistema
                                                            <b class="d-block">{{ $acessoSis }}</b>
                                                        </p>
                                                        @if($acessoSis == "Sim")
                                                        @php 
                                                            $dadosUsu = DB::table('users')->where('usuario_codigo', $prestador->prestador_usuario_cod)->where('usuario_empresa', $prestador->prestador_empresa)->get();
                                                        @endphp
                                                        <p class="text-sm col-md-4">Usuario Sistema
                                                            <b class="d-block">{{ $prestador->prestador_usuario_cod.' - '.$dadosUsu[0]->name }}</b>
                                                        </p>
                                                        @else
                                                        <p class="text-sm col-md-4">Usuario Sistema
                                                            <b class="d-block">Não Cadastrado</b>
                                                        </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Conteudo da direita do modal -->
                                        <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                                            <h4 class="text-primary">Endereço</h4>
                                            @php
                                                //Busca os dados do endereço da empresa e faz o tratamento de dados
                                                $endereco = DB::table('cadastro_prestadores_enderecos')->where('endereco_prestador_codigo', $prestador->prestador_codigo)->where('endereco_principal','S')->get();
                                              
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
                                                if($prestador->prestador_tel_celular){
                                                    $telCelular = Helper::mascaraTelCelular($prestador->prestador_tel_celular);
                                                }else{
                                                    $telCelular = "Não Cadastrado";
                                                }

                                                if($prestador->prestador_tel_residencial){
                                                    $telResidencial = Helper::mascaraTelResidencial($prestador->prestador_tel_residencial);
                                                }else{
                                                    $telResidencial = "Não Cadastrado";
                                                }

                                                if($prestador->prestador_email){
                                                    $email = $prestador->prestador_email;
                                                }else{
                                                    $email = "Não Cadastrado";
                                                }
                                            @endphp
                                            <div class="col-sm-4 invoice-col">
                                                <!-- Se tiver endereço monta os dados -->
                                                <address>
                                                    @if(!empty($cep))
                                                        <strong class="text-muted">Principal</strong><br>
                                                        {{$logradouro}}, {{$numero}}<br>
                                                        @if(!empty($complemento)){{$complemento}}<br>@endif
                                                        {{$cep}}<br>
                                                        {{$bairro}}<br>
                                                        {{$cidade}} - {{$uf}}<br>
                                                        {{$pais}}
                                                    @else
                                                        <strong class="text-muted">Endereço não cadastrado</strong><br>
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
                                        <x-adminlte-button theme="info" label="Voltar" data-dismiss="modal"/>
                                    </x-slot>
                                </x-adminlte-modal>
                                <!-- Gera o icone da lupa que abre o modal -->
                                <a href="" class="lupa-consulta" data-toggle="modal" title="Detalhes do Prestador" data-target="#modalCustom_{{$prestador->prestador_codigo}}">
                                    <i class="fa-solid fa-magnifying-glass" style="color: #74C0FC;"></i>
                                </a>
                            </nobr>
                        </td>   
                        <td>{{ $prestador->prestador_empresa.' - '.$dataEmp->empresa_nome }}</td>
                        <td>{{ $prestador->prestador_codigo.' - '.$prestador->prestador_nome }}</td>
                        <td>{{ Helper::mascaraCPF($prestador->prestador_cpf) }}</td>
                        <td>{{ $prestador->prestador_set.' - '.$dadosSet[0]->setor_desc }}</td>
                        <td>{{ $acessoSis }}</td>
                        <td>{{ $sts }}</td>
                        <td>
                            <nobr class="d-flex justify-content-center">
                                <form method="get" action="{{route('cadastroPrestador.edit', ['cadastroPrestador' => $prestador->prestador_codigo])}}" style="float: left;">
                                    @csrf 

                                    <!-- Informa o Tipo da Busca para a Edição -->
                                    <input id="tipo" type="hidden" value="H" name="tipo">

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
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="2" href="#">Prestador</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="3" href="#">CPF</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="4" href="#">Setor</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="5" href="#">Acessa Sis.</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="6" href="#">Status</a>
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
                        <x-adminlte-input name="filterPrest" type="text" label="Prestador" class="form-control" placeholder="Filtrar Prestador" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterCPF" type="text" label="CPF" class="form-control" placeholder="Filtrar CPF" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-select name="filterSetor" label="Setor" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="$arraySelSetor" empty-option="Selecione..."/>
                        </x-adminlte-select>
                    </div>
                    <div class="row">
                        <x-adminlte-select name="filterAceSis" label="Acessa Sis." igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="['Sim' => 'Sim', 'Não' => 'Não']" empty-option="Selecione..."/>
                        </x-adminlte-select>
                        <x-adminlte-select name="filterSts" label="Status" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="['Ativo' => 'Ativo', 'Demitido' => 'Demitido']" empty-option="Selecione..."/>
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

                    $wrapper.find('#filterPrest').on('keyup', function() {
                        table.column(2).search(this.value).draw();
                    });

                    $wrapper.find('#filterCPF').on('keyup', function() {
                        table.column(3).search(this.value).draw();
                    });

                    $wrapper.find('#filterSetor').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(4).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterAceSis').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(5).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterSts').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(6).search(selectedValue).draw();
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
