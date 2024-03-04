@extends('adminlte::page')

@section('title', 'Emissão de Ordem de Serviço')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h1>Lançamentos</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">
                    <a href="{{route('home.emissaoOS')}}">Emissão de OS</a>
                </li>
                <li class="breadcrumb-item active">Painel de Abertura de OS</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
@php

//Gera as variaveis globais do painel de OS
$glo_os_dadosOS = session('glo_os_dadosOS');
$glo_os_dadosRequisicoes = session('glo_os_dadosRequisicoes');
$glo_os_dadosEmpresa = session('glo_os_dadosEmpresa');
$glo_os_dadosCliente = session('glo_os_dadosCliente');
$glo_os_dadosClienteEndereco = session('glo_os_dadosClienteEndereco');
$glo_os_empresa = session('glo_os_empresa');
$glo_os_cliente = session('glo_os_cliente');
$glo_os_nos = session('glo_os_nos');
$glo_os_estagioAPP = session('glo_os_estagioAPP');
$glo_os_req_eat_cod = session('glo_os_req_eat_cod');
$glo_os_req_eat_cat = session('glo_os_req_eat_cat');
$glo_os_req_eat_are = session('glo_os_req_eat_are');
$glo_os_dadosServico = session('glo_os_dadosServico');
$glo_os_dadosTMO = session('glo_os_dadosTMO');
$glo_os_dadosTmoSelecionada = session('glo_os_dadosTmoSelecionada');
$glo_os_subEstagioRequisicao = session('glo_os_subEstagioRequisicao');
$glo_os_dadosServicoSelecionado = session('glo_os_dadosServicoSelecionado');

@endphp
<div class="col-md-12">

    <!-- ********** Painel Principal da Abertura de OS ********** -->
    <x-adminlte-card title="Painel de Abertura de Ordem de Serviço" theme="navy" collapsible maximizable>

        <!-- Posiciona os blocos do lado esquerdo e direito na mesma linha -->
        <div class="row">

            <!-- ************************************************** Bloco do Lado Esquerdo do Painel Principal da Abertura de OS ************************************************** -->
            <div class="col-md-4">

                <!-- ********** Bloco dos dados principais da abertura de OS ********** -->
                <x-adminlte-card title="Dados da Ordem de Serviço" theme="navy" theme-mode="outline" collapsible maximizable>
                    @php 
                        if($glo_os_dadosOS[0]['os_sts'] == 'A'){
                            $situacao = "Aberta";
                        }elseif($glo_os_dadosOS[0]['os_sts'] == 'F'){
                            $situacao = "Finalizada";
                        }else{
                            $situacao = "Cancelada";
                        }

                        $dataAbertura = date("d/m/Y H:i:s", strtotime($glo_os_dadosOS[0]['os_dha']));

                        if($glo_os_dadosCliente[0]->cliente_tipo_pessoa == 'F'){
                            $cpfCnpjCliente = substr($glo_os_dadosCliente[0]->cliente_cpf_cnpj,0,3).'.'.substr($glo_os_dadosCliente[0]->cliente_cpf_cnpj,3,3).'.'.substr($glo_os_dadosCliente[0]->cliente_cpf_cnpj,6,3).'-'.substr($glo_os_dadosCliente[0]->cliente_cpf_cnpj,9,2);
                        }else{
                            $cpfCnpjCliente = substr($glo_os_dadosCliente[0]->cliente_cpf_cnpj,0,2).'.'.substr($glo_os_dadosCliente[0]->cliente_cpf_cnpj,2,3).'.'.substr($glo_os_dadosCliente[0]->cliente_cpf_cnpj,5,3).'/'.substr($glo_os_dadosCliente[0]->cliente_cpf_cnpj,8,4).'-'.substr($glo_os_dadosCliente[0]->cliente_cpf_cnpj,12,2);
                        }
                    @endphp
                    <div class="row">
                        <table class="table tabela-dados-os">
                            <tbody>
                                <tr>
                                    <td colspan="2" style="border: 0px;">
                                        <p class="text-sm">Empresa
                                            <b class="d-block">{{ $glo_os_empresa }} - {{$glo_os_dadosEmpresa[0]->empresa_nome}}</b>
                                        </p>
                                    </td>
                                    <td colspan="2" style="border: 0px;">
                                        <p class="text-sm">Número da OS
                                            <b class="d-block">{{ $glo_os_nos }}</b>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <p class="text-sm">Data e Hora de Abertura
                                            <b class="d-block">{{ $dataAbertura }}</b>
                                        </p>
                                    </td>
                                    <td colspan="2">
                                        <p class="text-sm">Situação
                                            <b class="d-block">{{ $situacao }}</b>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="4">Dados do Cliente</th>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <p class="text-sm">Cliente
                                            <b class="d-block">{{ $glo_os_cliente }} - {{$glo_os_dadosCliente[0]->cliente_nome}}</b>
                                        </p>
                                    </td>
                                    <td colspan="2">
                                        <p class="text-sm">CPF / CNPJ
                                            <b class="d-block">{{$cpfCnpjCliente}}</b>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <p class="text-sm">Logradouro
                                            <b class="d-block">{{$glo_os_dadosClienteEndereco[0]->endereco_logradouro}}, {{$glo_os_dadosClienteEndereco[0]->endereco_numero}}</b>
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-sm">Complemento
                                            <b class="d-block">@if(!empty($glo_os_dadosClienteEndereco[0]->endereco_complemento)){{$glo_os_dadosClienteEndereco[0]->endereco_complemento}}@endif</b>
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-sm">CEP
                                            <b class="d-block">{{$glo_os_dadosClienteEndereco[0]->endereco_cep}}</b>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="text-sm">Bairro
                                            <b class="d-block">{{$glo_os_dadosClienteEndereco[0]->endereco_bairro}}</b>
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-sm">Cidade
                                            <b class="d-block">{{$glo_os_dadosClienteEndereco[0]->endereco_cidade}}</b>
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-sm">UF
                                            <b class="d-block">{{$glo_os_dadosClienteEndereco[0]->endereco_uf}}</b>
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-sm">Pais
                                            <b class="d-block">{{$glo_os_dadosClienteEndereco[0]->endereco_pais}}</b>
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table> 
                    </div>
                </x-adminlte-card><!-- Fechamento do bloco dos dados principais da abertura de OS -->

                <!-- ********** Bloco dos dados das etapas de atendimento da abertura de OS ********** -->
                <x-adminlte-card title="Etapas de Atendimento" theme="navy" theme-mode="outline" collapsible maximizable>
                    @php 
                        $etapas = DB::table('lancamento_srv_etapa_atendimentos')->where('eat_emp', $glo_os_empresa)->orderBy('eat_ord', 'asc')->get();
                        $cnt_etapa = 0;
                    @endphp
                    <table class="table">
                        <tbody>
                            @foreach($etapas as $etapa)
                                @php 
                                    $cnt_etapa += 1;
                                @endphp
                                <tr>
                                    @if($cnt_etapa == 1)
                                    <td style="border: 0px;"><a href="{{ route('painelOS.abreRequisicao', ['empresa' => $glo_os_empresa, 'nos' => $glo_os_nos, 'estagioAPP' => 'INCLUSAO_REQUISICAO', 'glo_eat_cod' => $etapa->eat_cod]) }}">{{$etapa->eat_nom}}</a></td>
                                    @else
                                    <td><a href="{{ route('painelOS.abreRequisicao', ['empresa' => $glo_os_empresa, 'nos' => $glo_os_nos, 'estagioAPP' => 'INCLUSAO_REQUISICAO', 'glo_eat_cod' => $etapa->eat_cod]) }}">{{$etapa->eat_nom}}</a></td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>                            
                </x-adminlte-card><!-- Fechamento do bloco dos dados do atendimento da abertura de OS ********** -->

            </div><!-- Fechamento do Bloco do Lado Esquerdo do Painel Principal da Abertura de OS -->

            <!-- ************************************************** Bloco do Lado Direito do Painel Principal da Abertura de OS ************************************************************ -->
            <div class="col-md-8">
                
                <!-- ********** Bloco GERAL do painel principal da Abertura de OS - Etapa = PRINCIPAL ********** -->
                @if($glo_os_estagioAPP == "PRINCIPAL")
                <x-adminlte-card title="Lista de Tarefas da Ordem de Serviço" theme="navy" theme-mode="outline" collapsible maximizable>
                    @php
                    // Monta os dados da tabela do bloco
                    $heads = [
                        ['label' => '', 'no-export' => true, 'width' => 5],
                        'Requisição',
                        'Descrição',
                        'Setor',
                        'Tipo se Serviço',
                        'Total',
                        ['label' => 'Situação', 'no-export' => true, 'width' => 5]
                    ];
                    
                    $config = [
                        'searching' => false,
                        'lengthChange' => false,
                        'pageLength' => 5,
                        'language' => [
                            'decimal' =>        '',
                            'emptyTable' =>     'Sem dados disponíveis na tabela',
                            'info' =>           'Mostrando _START_ a _END_ de _TOTAL_ registros',
                            'infoEmpty' =>      'Mostrando 0 a 0 de 0 registros',
                            'infoFiltered' =>   '(Filtrado do total de _MAX_ registros)',
                            'infoPostFix' =>    '',
                            'thousands' =>      ',',
                            'lengthMenu' =>     'Mostrar _MENU_ registros',
                            'loadingRecords' => 'Carregando...',
                            'processing' =>     '',
                            'search' =>         'Pesquisar:',
                            'zeroRecords' =>    'Nenhum registro correspondente encontrado',
                            'paginate' => [
                                'first' =>      'Primeiro',
                                'last' =>       'Último',
                                'next' =>       'Próximo',
                                'previous' =>   'Anterior'
                            ],
                            'aria' => [
                                'sortAscending' =>  ': ativar para classificar a coluna em ordem crescente',
                                'sortDescending' => ': ativar para classificar a coluna em ordem decrescente'
                            ],
                        ],
                        'columns' => [['orderable' => false], null, null, null, null, null, ['orderable' => false]],
                    ];
                    @endphp
                    <x-adminlte-datatable id="tabelaGeral" :heads="$heads" :config="$config" theme="light" striped hoverable>
                        @foreach($glo_os_dadosRequisicoes as $requisicao)
                            <tr>
                                <td>
                                    <nobr class="d-flex justify-content-center">
                                        <a class="text-muted" title="Detalhes da Requisição" href="{{route('painelOS.consultaRequisicao', ['empresa' => $glo_os_empresa, 'nos' => $glo_os_nos, 'estagioAPP' => 'CONSULTA_REQUISICAO', 'requisicao' => $requisicao->req_seq])}}">
                                            <i class="fa-solid fa-magnifying-glass fa-lg text-primary"></i>
                                        </a>
                                    </nobr>
                                </td>
                                <td>{{ $requisicao->req_seq }}</td>
                                <td>{{ $requisicao->req_dsc }}</td>
                                <td>{{ $requisicao->req_set }}</td>
                                <td>{{ $requisicao->req_tos }}</td>
                                <td>{{ $requisicao->req_vlr }}</td>
                                <td>
                                    <nobr class="d-flex justify-content-center">
                                        @if($requisicao->req_sts == 'F')
                                        <a class="text-muted" title="Finalizado" >
                                            <i class="fa-solid fa-circle-check fa-lg text-success"></i>
                                        </a>
                                        @elseif($requisicao->req_sts == 'A')
                                        <a class="text-muted" title="Andamento">
                                            <i class="fa-solid fa-clock-rotate-left fa-lg text-warning"></i>
                                        </a>
                                        @else
                                        <a class="text-muted" title="Cancelado">
                                            <i class="fa-solid fa-circle-xmark fa-lg text-danger"></i>
                                        </a>
                                        @endif
                                    </nobr>
                                </td>       
                            </tr>
                        @endforeach
                    </x-adminlte-datatable>
                </x-adminlte-card>
                @endif
                <!-- Fechamento do bloco GERAL do painel principal da Abertura de OS ********** -->

                <!-- ********** Bloco de inclusão da requisição da Abertura de OS - Etapa = INCLUSAO_REQUISICAO ********** -->
                @if($glo_os_estagioAPP == "INCLUSAO_REQUISICAO")
                <x-adminlte-card title="Inclusão de Requisição na Ordem de Serviço" theme="navy" theme-mode="outline" collapsible maximizable>
                    <form method="post" action="{{route('requisicaoOS.inserir', ['empresa' => $glo_os_empresa, 'numOS' => $glo_os_nos, 'glo_eat_cod' => $glo_os_req_eat_cod])}}" id="quickForm-insert-requisicao" novalidate="novalidate">
                        @csrf
                        <!-- Linha 1 dos dados principais da tarefa -->
                        <div class="row">

                            <!-- Descrição da tarefa -->
                            <x-adminlte-textarea name="descricaoReq" label="Descriçao" rows=3 label-class="text-dark" igroup-size="sm" placeholder="Informe a descrição da tarefa da OS..." fgroup-class="col-md-12">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text bg-navy">
                                        <i class="fas fa-lg fa-file-alt text-white"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-textarea>

                        </div><!-- Fechamento da linha 1 dos dados principais da tarefa -->

                        <!-- Linha 2 dos dados principais da tarefa -->
                        <div class="row">

                            @php
                            
                                $data_cat = DB::table('lancamento_srv_categorias')->select('categoria_codigo', 'categoria_desc')->orderBy('categoria_codigo', 'asc')->get();

                                $new_array1_cat =[];
                                $new_array2_cat =[];

                                foreach ($data_cat as $categoria) {
                                    $new_array1_cat[] = $categoria->categoria_codigo;
                                    $new_array2_cat[] = $categoria->categoria_codigo.' - '.$categoria->categoria_desc;
                                }
                                $array_opt_cat = array_combine($new_array1_cat, $new_array2_cat);

                                $data_are = DB::table('parametros_sistema_areas')->select('area_codigo', 'area_desc')->orderBy('area_codigo', 'asc')->get();

                                $new_array1_are =[];
                                $new_array2_are =[];

                                foreach ($data_are as $area) {
                                    $new_array1_are[] = $area->area_codigo;
                                    $new_array2_are[] = $area->area_codigo.' - '.$area->area_desc;
                                }
                                $array_opt_are = array_combine($new_array1_are, $new_array2_are);

                                if(!empty($glo_os_req_eat_are) && !empty($glo_os_req_eat_cat)){
                                    $eat_are_sel = $glo_os_req_eat_are;
                                    $eat_cat_sel = $glo_os_req_eat_cat;

                                    $data_tos = DB::table('lancamento_srv_tipo_servicos')->select('tipsrv_cod', 'tipsrv_nom')->where('tipsrv_emp', $glo_os_empresa)->where('tipsrv_are', $glo_os_req_eat_are)->where('tipsrv_cat', $glo_os_req_eat_cat)->orderBy('tipsrv_cod', 'asc')->get();

                                    $new_array1_tos =[];
                                    $new_array2_tos =[];

                                    foreach ($data_tos as $tos) {
                                        $new_array1_tos[] = $tos->tipsrv_cod;
                                        $new_array2_tos[] = $tos->tipsrv_cod.' - '.$tos->tipsrv_nom;
                                    }
                                    $array_opt_tos = array_combine($new_array1_tos, $new_array2_tos);

                                    $data_set = DB::table('parametros_srv_setores')->select('setor_codigo', 'setor_desc')->where('setor_empresa', $glo_os_empresa)->where('setor_area', $glo_os_req_eat_are)->orderBy('setor_codigo', 'asc')->get();

                                    $new_array1_set =[];
                                    $new_array2_set =[];

                                    foreach ($data_set as $setor) {
                                        $new_array1_set[] = $setor->setor_codigo;
                                        $new_array2_set[] = $setor->setor_codigo.' - '.$setor->setor_desc;
                                    }
                                    $array_opt_set = array_combine($new_array1_set, $new_array2_set);

                                }else{
                                    $eat_are_sel = '';
                                    $eat_cat_sel = '';
                                    $array_opt_set = null;
                                    $array_opt_tos = null;
                                }
                                
                            @endphp

                            <!-- Categoria -->
                            <x-adminlte-select name="categoriaReq" label="Categoria" fgroup-class="col-md-3">
                                <x-adminlte-options :options="$array_opt_cat" empty-option="Selecione..." selected="{{$eat_cat_sel}}"/>
                            </x-adminlte-select>

                            <!-- área -->
                            <x-adminlte-select name="areaReq" label="Área" fgroup-class="col-md-3">
                                <x-adminlte-options :options="$array_opt_are" empty-option="Selecione..." selected="{{$eat_are_sel}}"/>
                            </x-adminlte-select>

                            <!-- Setor -->
                            <x-adminlte-select name="setorReq" label="Setor" fgroup-class="col-md-3">
                                <x-adminlte-options :options="$array_opt_set" empty-option="Selecione..."/>
                            </x-adminlte-select>

                            <!-- Tipo Serviço -->
                            <x-adminlte-select name="tipoServicoReq" label="Tipo do Serviço" fgroup-class="col-md-3">
                                <x-adminlte-options :options="$array_opt_tos" empty-option="Selecione..."/>
                            </x-adminlte-select>

                        </div><!-- Fechamento da linha 2 dos dados principais da tarefa -->

                        <!-- Botão hide de inclusão de nova requisição -->
                        <x-adminlte-button class="btn_hide_incluir_requisicao" type="submit" label="Salvar" theme="info"/>

                    </form><!-- Fechamento do formulario de inserção da requisição -->
                </x-adminlte-card><!-- Fechamento do bloco de inclusão de requisição da OS ********** -->
                @endif

                <!-- ********** Bloco de consulta da requisição da Abertura de OS - Etapa = CONSULTA_REQUISICAO ********** -->
                @if($glo_os_estagioAPP == "CONSULTA_REQUISICAO")
                <x-adminlte-card title="Consulta de Requisição da Ordem de Serviço" theme="navy" theme-mode="outline" collapsible maximizable>
                    <div class="row">
                        <table class="table tabela-dados-req">
                            <tbody>
                                <tr>
                                    <th colspan="4">Dados da Requisição</th>
                                </tr>
                                <tr>
                                    <td colspan="1" style="border: 0px;">
                                        <p class="text-sm">Código
                                            <b class="d-block">{{ $glo_os_dadosRequisicoes[0]['req_seq'] }}</b>
                                        </p>
                                    </td>
                                    <td colspan="2" style="border: 0px;">
                                        <p class="text-sm">Descrição da Requisição
                                            <b class="d-block">{{ $glo_os_dadosRequisicoes[0]['req_dsc'] }}</b>
                                        </p>
                                    </td>
                                    <td colspan="1" style="border: 0px;">
                                        <p class="text-sm">Situação
                                            <b class="d-block">
                                                @if($glo_os_dadosRequisicoes[0]['req_sts'] == 'F')
                                                <a class="text-muted" title="Finalizado" >
                                                    <i class="fa-solid fa-circle-check fa-xl text-success"></i>
                                                </a>
                                                @elseif($glo_os_dadosRequisicoes[0]['req_sts'] == 'A')
                                                <a class="text-muted" title="Andamento">
                                                    <i class="fa-solid fa-clock-rotate-left fa-xl text-info"></i>
                                                </a>
                                                @else
                                                <a class="text-muted" title="Cancelado">
                                                    <i class="fa-solid fa-circle-xmark fa-xl text-danger"></i>
                                                </a>
                                                @endif
                                            </b>
                                        </p>
                                    </td>
                                </tr>
                                @php
                                    $nom_cat = DB::table('lancamento_srv_categorias')->select('categoria_desc')->where('categoria_codigo', $glo_os_dadosRequisicoes[0]['req_cat'])->get();

                                    $nom_are = DB::table('parametros_sistema_areas')->select('area_desc')->where('area_codigo', $glo_os_dadosRequisicoes[0]['req_are'])->get();

                                    $nom_tos = DB::table('lancamento_srv_tipo_servicos')->select('tipsrv_nom')->where('tipsrv_emp', $glo_os_empresa)->where('tipsrv_cod', $glo_os_dadosRequisicoes[0]['req_tos'])->get();

                                    $nom_set = DB::table('parametros_srv_setores')->select('setor_desc')->where('setor_empresa', $glo_os_empresa)->where('setor_codigo', $glo_os_dadosRequisicoes[0]['req_set'])->get();
                                @endphp
                                <tr>
                                    <td>
                                        <p class="text-sm">Categoria
                                            <b class="d-block">{{ $glo_os_dadosRequisicoes[0]['req_cat'] }} - {{ $nom_cat[0]->categoria_desc }}</b>
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-sm">Área
                                            <b class="d-block">{{ $glo_os_dadosRequisicoes[0]['req_are'] }} - {{ $nom_are[0]->area_desc }} </b>
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-sm">Setor
                                            <b class="d-block">{{ $glo_os_dadosRequisicoes[0]['req_set'] }} - {{ $nom_set[0]->setor_desc }}</b>
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-sm">Tipo de Serviço
                                            <b class="d-block">{{ $glo_os_dadosRequisicoes[0]['req_tos'] }} - {{ $nom_tos[0]->tipsrv_nom }}</b>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="4">Serviços da Requisição</th>
                                </tr>
                            </tbody>
                        </table> 
                    </div>
                    @php
                    //Monta dados da tabela do bloco
                    $heads1 = [
                        ['label' => '', 'no-export' => true, 'width' => 5],
                        'Código',
                        'Descrição',
                        'Prestador',
                        'UN',
                        'Qtd.',
                        'Val. Uni.',
                        'Val. Total',
                        'Aprovado',
                        'Situação'
                    ];
                    
                    $config1 = [
                        'searching' => false,
                        'lengthChange' => false,
                        'pageLength' => 5,
                        'language' => [
                            'decimal' =>        '',
                            'emptyTable' =>     'Sem dados disponíveis na tabela',
                            'info' =>           'Mostrando _START_ a _END_ de _TOTAL_ registros',
                            'infoEmpty' =>      'Mostrando 0 a 0 de 0 registros',
                            'infoFiltered' =>   '(Filtrado do total de _MAX_ registros)',
                            'infoPostFix' =>    '',
                            'thousands' =>      ',',
                            'lengthMenu' =>     'Mostrar _MENU_ registros',
                            'loadingRecords' => 'Carregando...',
                            'processing' =>     '',
                            'search' =>         'Pesquisar:',
                            'zeroRecords' =>    'Nenhum registro correspondente encontrado',
                            'paginate' => [
                                'first' =>      'Primeiro',
                                'last' =>       'Último',
                                'next' =>       'Próximo',
                                'previous' =>   'Anterior'
                            ],
                            'aria' => [
                                'sortAscending' =>  ': ativar para classificar a coluna em ordem crescente',
                                'sortDescending' => ': ativar para classificar a coluna em ordem decrescente'
                            ],
                        ],
                        'columns' => [['orderable' => false], null, null, null, null,  null, null, null, null, null],
                    ];
                    @endphp
                    <x-adminlte-datatable id="detalhesServicos" :heads="$heads1" :config="$config1" theme="light" striped hoverable beautify>
                        @foreach($glo_os_dadosServico as $servico)
                            @php 
                                $srv_vhr = number_format($servico->srv_vhr,2,",",".");
                                $srv_vts = number_format($servico->srv_vts,2,",",".");

                                if($servico->srv_sts == 'F'){
                                    $icone_sts_servico = "fa-solid fa-circle-check fa-lg text-success";
                                }elseif($servico->srv_sts == 'A'){
                                    $icone_sts_servico = "fa-solid fa-clock-rotate-left fa-lg text-info";
                                }elseif($servico->srv_sts == 'C'){
                                    $icone_sts_servico = "fa-solid fa-circle-xmark fa-lg text-danger";
                                }else{
                                    $icone_sts_servico = "fa-solid fa-triangle-exclamation fa-lg text-warning";
                                }

                                if($servico->srv_flg_apr == 'S'){
                                    $icone_apr_servico = "fa-solid fa-circle-check fa-lg text-success";
                                }else{
                                    $icone_apr_servico = "fa-solid fa-circle-xmark fa-lg text-danger";
                                }
                            @endphp
                            <tr>
                                <td>
                                    <nobr class="d-flex justify-content-center">
                                        <form method="get" action="{{route('painelOS.consultaServicoRequisicao', ['empresa'=> $glo_os_empresa,'numOS'=> $glo_os_nos,'requisicao'=> $servico->srv_req,'sequencia'=> $servico->srv_seq,'codTMO'=> $servico->srv_tmo,'estagioAPP'=> 'MANUTENCAO_SERVICO','subEstagioRequisicao'=> 'TMO_SELECIONADA'])}}">
                                            @csrf 
                                            <button class="btn btn-xs btn-default mx-1 shadow text-success" title="Editar Registro" value="Editar" type="submit">
                                                <i class="fa fa-lg fa-fw fa-pen"></i>
                                            </button>
                                        </form>
                                    </nobr>
                                </td>
                                <td>{{$servico->srv_tmo}}</td>
                                <td>{{$servico->srv_dsc}}</td>  
                                <td>{{$servico->srv_prt}}</td>
                                <td>{{$servico->srv_und}}</td>
                                <td>{{$servico->srv_qhr}}</td>
                                <td>{{$srv_vhr}}</td> 
                                <td>{{$srv_vts}}</td>
                                <td><i class="{{$icone_apr_servico}}"></i></td>
                                <td><i class="{{$icone_sts_servico}}"></i></td>   
                            </tr>
                        @endforeach
                        <tfoot class="thead-light">
                            <tr>
                                <th colspan="5" style="text-align:left">Sub-Total</br>Total Geral</th>
                                <th style="text-align:center"></th>
                                <th style="text-align:center"></th>
                                <th style="text-align:center"></th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </x-adminlte-datatable>
                </x-adminlte-card><!-- Fechamento do bloco de consulta de requisição da OS ********** -->
                @endif

                <!-- ********** Bloco Inclusão/Manutenção de serviços na requisição da OS ********** -->
                @if($glo_os_estagioAPP == "INCLUSAO_SERVICO" || $glo_os_estagioAPP == "MANUTENCAO_SERVICO")
                <x-adminlte-card title="Inclusão Inclusão / Manutenção de Serviços na Requisição da OS" theme="navy" theme-mode="outline" collapsible maximizable>
                    <div class="row">
                        <table class="table tabela-dados-req">
                            <tbody>
                                <tr>
                                    <th colspan="4">Dados da Requisição</th>
                                </tr>
                                <tr>
                                    <td colspan="1" style="border: 0px;">
                                        <p class="text-sm">Código
                                            <b class="d-block">{{ $glo_os_dadosRequisicoes[0]['req_seq'] }}</b>
                                        </p>
                                    </td>
                                    <td colspan="2" style="border: 0px;">
                                        <p class="text-sm">Descrição da Requisição
                                            <b class="d-block">{{ $glo_os_dadosRequisicoes[0]['req_dsc'] }}</b>
                                        </p>
                                    </td>
                                    <td colspan="1" style="border: 0px;">
                                        <p class="text-sm">Situação
                                            <b class="d-block">
                                                @if($glo_os_dadosRequisicoes[0]['req_sts'] == 'F')
                                                <a class="text-muted" title="Finalizado" >
                                                    <i class="fa-solid fa-circle-check fa-xl text-success"></i>
                                                </a>
                                                @elseif($glo_os_dadosRequisicoes[0]['req_sts'] == 'A')
                                                <a class="text-muted" title="Andamento">
                                                    <i class="fa-solid fa-clock-rotate-left fa-xl text-info"></i>
                                                </a>
                                                @else
                                                <a class="text-muted" title="Cancelado">
                                                    <i class="fa-solid fa-circle-xmark fa-xl text-danger"></i>
                                                </a>
                                                @endif
                                            </b>
                                        </p>
                                    </td>
                                </tr>
                                @php
                                    $nom_cat = DB::table('lancamento_srv_categorias')->select('categoria_desc')->where('categoria_codigo', $glo_os_dadosRequisicoes[0]['req_cat'])->get();

                                    $nom_are = DB::table('parametros_sistema_areas')->select('area_desc')->where('area_codigo', $glo_os_dadosRequisicoes[0]['req_are'])->get();

                                    $nom_tos = DB::table('lancamento_srv_tipo_servicos')->select('tipsrv_nom')->where('tipsrv_emp', $glo_os_empresa)->where('tipsrv_cod', $glo_os_dadosRequisicoes[0]['req_tos'])->get();

                                    $nom_set = DB::table('parametros_srv_setores')->select('setor_desc')->where('setor_empresa', $glo_os_empresa)->where('setor_codigo', $glo_os_dadosRequisicoes[0]['req_set'])->get();
                                @endphp
                                <tr>
                                    <td>
                                        <p class="text-sm">Categoria
                                            <b class="d-block">{{ $glo_os_dadosRequisicoes[0]['req_cat'] }} - {{ $nom_cat[0]->categoria_desc }}</b>
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-sm">Área
                                            <b class="d-block">{{ $glo_os_dadosRequisicoes[0]['req_are'] }} - {{ $nom_are[0]->area_desc }} </b>
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-sm">Setor
                                            <b class="d-block">{{ $glo_os_dadosRequisicoes[0]['req_set'] }} - {{ $nom_set[0]->setor_desc }}</b>
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-sm">Tipo de Serviço
                                            <b class="d-block">{{ $glo_os_dadosRequisicoes[0]['req_tos'] }} - {{ $nom_tos[0]->tipsrv_nom }}</b>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="4">Inclusão / Manutenção de TMO</th>
                                </tr>
                            </tbody>
                        </table> 
                    </div>
                        
                    <!-- Linha 1 - Campo código da TMO e modal de seleção-->
                    <div class="row">
                        @php 
                            if($glo_os_subEstagioRequisicao == 'TMO_SELECIONADA'){
                                if($glo_os_estagioAPP == "INCLUSAO_SERVICO"){
                                    $cod_tmo_sel = $glo_os_dadosTmoSelecionada[0]->tmo_cod;
                                }else{
                                    $cod_tmo_sel = $glo_os_dadosServicoSelecionado[0]->srv_tmo;
                                }
                            }else{
                                $cod_tmo_sel = '';
                            }
                        @endphp

                        <!-- Código da TMO -->
                        <x-adminlte-input name="codigoTMO" label="Código" type="text" value="{{$cod_tmo_sel}}" fgroup-class="col-md-4" disabled/>
                        
                        @if($glo_os_estagioAPP == "MANUTENCAO_SERVICO")
                        <table class="table tabela-situacao-tmo col-md-8" style="text-align: center;">
                            <tbody>
                                <tr>
                                    <td colspan="1" style="border: 0px;">
                                        <p class="text-md font-weight-bold">Aprovado
                                            <b class="d-block" style="padding: 5px;">
                                                @if($glo_os_dadosServicoSelecionado[0]->srv_flg_apr == 'S')
                                                <a class="text-muted" title="Aprovado">
                                                    <i class="fa-solid fa-circle-check fa-2xl text-success"></i>
                                                </a>
                                                @else
                                                <a class="text-muted" title="Não Aprovado">
                                                    <i class="fa-solid fa-circle-xmark fa-2xl text-danger"></i>
                                                </a>
                                                @endif
                                            </b>
                                        </p>
                                    </td>
                                    <td colspan="1" style="border: 0px;">
                                        <p class="text-md font-weight-bold">Situação
                                            <b class="d-block" style="padding: 5px;">
                                                @if($glo_os_dadosServicoSelecionado[0]->srv_sts == 'F')
                                                <a class="text-muted" title="Finalizado">
                                                    <i class="fa-solid fa-circle-check fa-2xl text-success"></i>
                                                </a>
                                                @elseif($glo_os_dadosServicoSelecionado[0]->srv_sts == 'A')
                                                <a class="text-muted" title="Andamento">
                                                    <i class="fa-solid fa-clock-rotate-left fa-2xl text-info"></i>
                                                </a>
                                                @elseif($glo_os_dadosServicoSelecionado[0]->srv_sts == 'C')
                                                <a class="text-muted" title="Cancelado">
                                                    <i class="fa-solid fa-circle-xmark fa-2xl text-danger"></i>
                                                </a>
                                                @else
                                                <a class="text-muted" title="Suspenso">
                                                    <i class="fa-solid fa-triangle-exclamation fa-2xl text-warning"></i>
                                                </a>
                                                @endif
                                            </b>
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table> 
                        @endif

                        @if($glo_os_estagioAPP == "INCLUSAO_SERVICO")
                        <!-- Icone de pesquisa do Serviço -->
                        <div class="col-md-8">
                            <table style="height: 100%;">
                                <tbody>
                                    <tr>
                                        <td class="align-middle" style="padding-top: 15px;">
                                            <!-- Cria o modal dos detalhes da empresa -->
                                            <x-adminlte-modal id="modalServico_{{$glo_os_dadosRequisicoes[0]['req_seq']}}" title="Tarefas de Mão de Obra" size="xl" theme="navy" v-centered scrollable>
                                                <div class="row" style="height:auto;">
                                                    @php
                                                        //Monta dados da tabela do bloco
                                                        $heads = [
                                                            ['label' => '', 'no-export' => true, 'width' => 5],
                                                            'Código',
                                                            'Descrição',
                                                            'Complemento',
                                                            'Tipo',
                                                            'Qtd. Hora',
                                                            'Val. Uni.',
                                                            'Val. Total'
                                                        ];
                    
                                                        $config = [
                                                            'lengthMenu' => [ 5, 10, 25, 50],
                                                            'language' => [
                                                                'decimal' =>        '',
                                                                'emptyTable' =>     'Sem dados disponíveis na tabela',
                                                                'info' =>           'Mostrando _START_ a _END_ de _TOTAL_ registros',
                                                                'infoEmpty' =>      'Mostrando 0 a 0 de 0 registros',
                                                                'infoFiltered' =>   '(Filtrado do total de _MAX_ registros)',
                                                                'infoPostFix' =>    '',
                                                                'thousands' =>      ',',
                                                                'lengthMenu' =>     'Mostrar _MENU_ registros',
                                                                'loadingRecords' => 'Carregando...',
                                                                'processing' =>     '',
                                                                'search' =>         'Pesquisar:',
                                                                'zeroRecords' =>    'Nenhum registro correspondente encontrado',
                                                                'paginate' => [
                                                                    'first' =>      'Primeiro',
                                                                    'last' =>       'Último',
                                                                    'next' =>       'Próximo',
                                                                    'previous' =>   'Anterior'
                                                                ],
                                                                'aria' => [
                                                                    'sortAscending' =>  ': ativar para classificar a coluna em ordem crescente',
                                                                    'sortDescending' => ': ativar para classificar a coluna em ordem decrescente'
                                                                ],
                                                            ],
                                                            'columns' => [['orderable' => false], null, null, null, null,  null, null, null],
                                                        ];
                                                    @endphp
                                                    <x-adminlte-datatable id="detTMO-Selecao" :heads="$heads" :config="$config" theme="light" striped hoverable with-buttons>
                                                        @foreach($glo_os_dadosTMO as $TMO)
                                                            <tr>
                                                                <td>
                                                                    <nobr class="d-flex justify-content-center">
                                                                        <form method="get" action="{{route('painelOS.selecionarTMO', ['empresa' => $glo_os_empresa, 'setor' => $glo_os_dadosRequisicoes[0]['req_set'], 'codigo' => $TMO->tmo_cod, 'estagioAPP' => 'INCLUSAO_SERVICO', 'subEstagioRequisicao' => 'TMO_SELECIONADA'])}}">
                                                                            @csrf
                                                                            <x-adminlte-button class="btn-sm" type="submit" label="Selecionar" theme="info" icon="fa-solid fa-check"/>
                                                                        </form>
                                                                    </nobr>
                                                                </td>
                                                                <td>{{$TMO->tmo_cod}}</td>
                                                                <td>{{$TMO->tmo_dsc}}</td>
                                                                <td>{{$TMO->tmo_cmp}}</td>   
                                                                <td>{{$TMO->tmo_tip}}</td>
                                                                <td>{{$TMO->tmo_qtd_hr}}</td>
                                                                <td>{{$TMO->tmo_val_hr}}</td>
                                                                <td>{{$TMO->tmo_val_tot}}</td>       
                                                            </tr>
                                                        @endforeach
                                                    </x-adminlte-datatable>
                                                </div>
                                                <x-slot name="footerSlot">
                                                    <x-adminlte-button theme="info" label="Voltar" data-dismiss="modal"/>
                                                </x-slot>
                                            </x-adminlte-modal>
                                            <x-adminlte-button label="Pesquisar" theme="info" icon="fa-solid fa-magnifying-glass" type="button" data-toggle="modal" data-target="#modalServico_{{$glo_os_dadosRequisicoes[0]['req_seq']}}"/>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>

                    @if($glo_os_subEstagioRequisicao == 'TMO_SELECIONADA')
                    <!-- Formulario de Inclusão (INCLUSAO_SERVICO) / Manutenção da TMO (MANUTENCAO_SERVICO) -->
                    @if($glo_os_estagioAPP == "INCLUSAO_SERVICO")
                    <form method="post" action="{{route('servicoOS.inserir', ['empresa' => $glo_os_empresa, 'numOS' => $glo_os_nos, 'requisicao' => $glo_os_dadosRequisicoes[0]['req_seq'], 'codTMO' => $glo_os_dadosTmoSelecionada[0]->tmo_cod, 'estagioAPP' => 'CONSULTA_REQUISICAO'])}}" id="quickForm-ins_upd-servico" novalidate="novalidate">
                    @else
                    <form method="post" action="{{route('servicoOS.atualizar', ['empresa' => $glo_os_empresa, 'numOS' => $glo_os_nos, 'requisicao' => $glo_os_dadosServicoSelecionado[0]->srv_req, 'sequencia' => $glo_os_dadosServicoSelecionado[0]->srv_seq, 'codTMO' => $glo_os_dadosServicoSelecionado[0]->srv_tmo, 'estagioAPP' => 'CONSULTA_REQUISICAO'])}}" id="quickForm-ins_upd-servico" novalidate="novalidate">
                    @endif
                        @csrf
                        <!-- Demais campos da TMO selecionada -->
                        <div class="row">
                            @php 
                                if($glo_os_estagioAPP == "INCLUSAO_SERVICO"){
                                    $descricaoTMO_sel = $glo_os_dadosTmoSelecionada[0]->tmo_dsc;
                                    $complementoTMO_sel = $glo_os_dadosTmoSelecionada[0]->tmo_cmp;
                                }else{
                                    $descricaoTMO_sel = $glo_os_dadosServicoSelecionado[0]->srv_dsc;
                                    $complementoTMO_sel = $glo_os_dadosServicoSelecionado[0]->srv_cmp;
                                }
                            @endphp
                            <!-- Descrição da TMO -->
                            <x-adminlte-input name="descricaoTMO" label="Descrição" type="text" value="{{$descricaoTMO_sel}}" fgroup-class="col-md-6"/>
                            <!-- Complemento da TMO -->
                            <x-adminlte-input name="complementoTMO" label="Complemento" type="text" value="{{$complementoTMO_sel}}" fgroup-class="col-md-6"/>
                        </div>

                        <div class="row">
                            @php 
                                if($glo_os_estagioAPP == "INCLUSAO_SERVICO"){
                                    $tipoTMO_sel = $glo_os_dadosTmoSelecionada[0]->tmo_tip;

                                    $prestadorTOS = DB::table('lancamento_srv_tipo_servicos')->select('tipsrv_res')->where('tipsrv_emp',$glo_os_empresa)->where('tipsrv_cod',$glo_os_dadosRequisicoes[0]['req_tos'])->get();
                                    if(!empty($prestadorTOS)){
                                        $prestadorTMO_sel = $prestadorTOS[0]->tipsrv_res;
                                    }else{
                                        $prestadorTMO_sel = '';
                                    }
                                }else{
                                    $tipoTMO_sel = $glo_os_dadosServicoSelecionado[0]->srv_ths;
                                    $prestadorTMO_sel = $glo_os_dadosServicoSelecionado[0]->srv_prt;
                                }
                            @endphp
                            <!-- Tipo da TMO -->
                            <x-adminlte-input name="tipoTMO" label="Tipo da Tarefa" type="text" value="{{$tipoTMO_sel}}" fgroup-class="col-md-4"/>
                            <!-- Prestador responsavel da TMO -->
                            <x-adminlte-input name="prestadorTMO" label="Prestador da Tarefa" type="text" value="{{$prestadorTMO_sel}}" fgroup-class="col-md-8"/>
                        </div>

                        <div class="row">
                            @php 
                                if($glo_os_estagioAPP == "INCLUSAO_SERVICO"){
                                    $qtdHrTMO_sel = $glo_os_dadosTmoSelecionada[0]->tmo_qtd_hr;
                                    $valUniHrTMO_sel = $glo_os_dadosTmoSelecionada[0]->tmo_val_hr;
                                    $valTotHrTMO_sel = $glo_os_dadosTmoSelecionada[0]->tmo_val_tot;
                                }else{
                                    $qtdHrTMO_sel = $glo_os_dadosServicoSelecionado[0]->srv_qhr;
                                    $valUniHrTMO_sel = $glo_os_dadosServicoSelecionado[0]->srv_vhr;
                                    $valTotHrTMO_sel = $glo_os_dadosServicoSelecionado[0]->srv_vts;
                                }
                            @endphp
                            <!-- Quantidade de Hortas -->
                            <x-adminlte-input name="qtdHrTMO" label="Qtd. de Horas" type="text" value="{{$qtdHrTMO_sel}}" fgroup-class="col-md-4"/>
                            <!-- Valor da Hora -->
                            <x-adminlte-input name="valUniHrTMO" label="Valor Unitário" type="text" value="{{$valUniHrTMO_sel}}" fgroup-class="col-md-4"/>
                            <!-- Valor Total -->
                            <x-adminlte-input name="valTotHrTMO" label="Valor Total" type="text" value="{{$valTotHrTMO_sel}}" fgroup-class="col-md-4"/>
                        </div>

                        <div class="row">
                            @php 
                                if($glo_os_estagioAPP == "INCLUSAO_SERVICO"){
                                    $forTerceiroTMO_sel = $glo_os_dadosTmoSelecionada[0]->tmo_for_cgt;
                                }else{
                                    $forTerceiroTMO_sel = $glo_os_dadosServicoSelecionado[0]->srv_for;
                                }
                            @endphp
                            <!-- Fornecedor do Serviço de Terceiros -->
                            <x-adminlte-input name="forTerceiroTMO" label="Fornecedor Terceiro" type="text" value="{{$forTerceiroTMO_sel}}" fgroup-class="col-md-6"/>
                        </div>

                        <div class="row">
                            <!-- Número da NF -->
                            <x-adminlte-input name="numNfTerceiroTMO" label="Número da NF" type="text" value="" fgroup-class="col-md-4"/>
                            <!-- Série da NF -->
                            <x-adminlte-input name="serNfTerceiroTMO" label="Série da NF" type="text" value="" fgroup-class="col-md-4"/>
                            <!-- Data da NF -->
                            <x-adminlte-input name="dataNfTerceiroTMO" label="Data da NF" type="text" value="" fgroup-class="col-md-4"/>
                        </div>

                        <div class="row">
                            @php 
                                if($glo_os_estagioAPP == "INCLUSAO_SERVICO"){
                                    $tipCustoTMO_sel = $glo_os_dadosTmoSelecionada[0]->tmo_tip_val_cgt;
                                    $valCustoTMO_sel = $glo_os_dadosTmoSelecionada[0]->tmo_val_cgt;
                                    $perCustoTMO_sel = $glo_os_dadosTmoSelecionada[0]->tmo_per_cgt;
                                }else{
                                    $tipCustoTMO_sel = $glo_os_dadosServicoSelecionado[0]->srv_tcg;
                                    $valCustoTMO_sel = $glo_os_dadosServicoSelecionado[0]->srv_vcg;
                                    $perCustoTMO_sel = $glo_os_dadosServicoSelecionado[0]->srv_pcg;
                                }
                            @endphp
                            <!-- Quantidade de Hortas -->
                            <x-adminlte-input name="tipCustoTMO" label="Tipo do Custo" type="text" value="{{$tipCustoTMO_sel}}" fgroup-class="col-md-4"/>
                            <!-- Valor da Hora -->
                            <x-adminlte-input name="valCustoTMO" label="Valor do Custo" type="text" value="{{$valCustoTMO_sel}}" fgroup-class="col-md-4"/>
                            <!-- Valor Total -->
                            <x-adminlte-input name="perCustoTMO" label="Percentual do custo" type="text" value="{{$perCustoTMO_sel}}" fgroup-class="col-md-4"/>
                        </div>

                        @if($glo_os_estagioAPP == "INCLUSAO_SERVICO")
                        <!-- Botão hide de inclusão da tmo -->
                        <x-adminlte-button class="btn_hide_incluir_tmo" type="submit" label="Incluir TMO" theme="info"/>
                        @else
                        <!-- Botão hide de manutenção da tmo -->
                        <x-adminlte-button class="btn_hide_atualizar_tmo" type="submit" label="Salvar TMO" theme="info"/>
                        @endif

                    </form><!-- Fechamento do formulario de Inclusão / Manutenção da TMO -->
                    @endif
                </x-adminlte-card><!-- Fechamento do bloco Inclusão/Manutenção do serviço na requisição da OS ********** -->
                @endif
                
                <!-- ********** Bloco ENCERRAMENTO da tarefa da Abertura de OS ********** -->
                @if($glo_os_estagioAPP == "ENCERRAMENTO")
                <x-adminlte-card title="Encerramento da Ordem de Serviço" theme="navy" theme-mode="outline" collapsible maximizable>
                    
                    <!-- Linha 1 - lista dos serviços -->    
                    <div class="row">
                        @php
                        //Monta dados da tabela do bloco
                        $heads = [
                            'Código',
                            'Nome',
                            'Tipo do Usuário',
                            'Status',
                        ];
                        
                        $config = [
                            'searching' => false,
                            'lengthChange' => false,
                            'pageLength' => 5,
                            'language' => [
                                'decimal' =>        '',
                                'emptyTable' =>     'Sem dados disponíveis na tabela',
                                'info' =>           'Mostrando _START_ a _END_ de _TOTAL_ registros',
                                'infoEmpty' =>      'Mostrando 0 a 0 de 0 registros',
                                'infoFiltered' =>   '(Filtrado do total de _MAX_ registros)',
                                'infoPostFix' =>    '',
                                'thousands' =>      ',',
                                'lengthMenu' =>     'Mostrar _MENU_ registros',
                                'loadingRecords' => 'Carregando...',
                                'processing' =>     '',
                                'search' =>         'Pesquisar:',
                                'zeroRecords' =>    'Nenhum registro correspondente encontrado',
                                'paginate' => [
                                    'first' =>      'Primeiro',
                                    'last' =>       'Último',
                                    'next' =>       'Próximo',
                                    'previous' =>   'Anterior'
                                ],
                                'aria' => [
                                    'sortAscending' =>  ': ativar para classificar a coluna em ordem crescente',
                                    'sortDescending' => ': ativar para classificar a coluna em ordem decrescente'
                                ],
                            ],
                            'columns' => [null, null, null, null],
                        ];
                        @endphp
                        <x-adminlte-datatable id="table3" :heads="$heads" :config="$config" theme="light" striped hoverable>
                            <tr>
                                <td>usuariousuario_codigo</td>
                                <td>usuarioname</td>
                                <td>tip_us</td>
                                <td>sts_usu</td>           
                            </tr>
                        </x-adminlte-datatable>
                    </div>

                    <!-- ********** Bloco dos TOTAIS da tarefa da Abertura de OS ********** -->
                    <x-adminlte-card title="Totais da Ordem de Serviço" theme="navy">
                        <div class="row">
                            <!-- Grupo do serviço -->
                            <x-adminlte-input name="grupo" label="Grupo" type="text" value="" fgroup-class="col-md-12" disabled/>
                        </div>
                        <div class="row">
                            <!-- Grupo do serviço -->
                            <x-adminlte-input name="grupo" label="Grupo" type="text" value="" fgroup-class="col-md-12" disabled/>
                        </div>
                        <div class="row">
                            <!-- Grupo do serviço -->
                            <x-adminlte-input name="grupo" label="Grupo" type="text" value="" fgroup-class="col-md-12" disabled/>
                        </div>
                    </x-adminlte-card><!-- Fechamento do bloco dos TOTAIS da tarefa da Abertura de OS -->

                </x-adminlte-card>
                @endif
                <!-- Fechamento do bloco ENCERRAMENTO da tarefa da Abertura de OS -->

            </div><!-- Fechamento do Bloco do Lado Direito do Painel Principal da Abertura de OS -->
            
        </div><!-- Fechamento do Posicionamento os blocos do lado esquerdo e direito na mesma linha -->

        <!-- ********** Rodapé com os botões do Painel Principal da Abertura de OS ********** -->
        <x-slot name="footerSlot">
            <x-adminlte-button class="btn_geral" type="button" onclick="window.location='{{ route('situacaoOS.carregaOS', ['empresa' => $glo_os_empresa, 'cliente' => $glo_os_cliente, 'nos' => $glo_os_nos, 'estagioAPP' => 'PRINCIPAL']) }}'" label="Geral" theme="info" icon=""/>
            
            
            <x-adminlte-button class="btn_incluir_requisicao" type="button" onclick="document.querySelector('.btn_hide_incluir_requisicao').click()" label="Incluir Requisição" theme="info" icon="fa-solid fa-plus"/>
            <x-adminlte-button class="btn_novo_servico" type="button" onclick="window.location='{{ route('painelOS.abrirServicoRequisicao', ['empresa' => $glo_os_empresa, 'setor' => $glo_os_dadosRequisicoes[0]['req_set'], 'estagioAPP' => 'INCLUSAO_SERVICO', 'subEstagioRequisicao' => 'SELECAO_TMO']) }}'" label="Novo Serviço" theme="info" icon=""/>

            <x-adminlte-button class="btn_incluir_tmo" type="button" onclick="document.querySelector('.btn_hide_incluir_tmo').click()" label="Incluir" theme="info" icon="fa-solid fa-plus"/>
            <x-adminlte-button class="btn_atualizar_tmo" type="button" onclick="document.querySelector('.btn_hide_atualizar_tmo').click()" label="Salvar" theme="info" icon="fa-solid fa-share-from-square"/>


            <!--
            <x-adminlte-button label="Orçamento" theme="info" icon="fas fa-user-plus" type="submit"/>
            <x-adminlte-button label="Totais" theme="info" icon="fas fa-user-plus" type="submit"/>
            <x-adminlte-button label="Reabrir OS" theme="info" icon="fas fa-user-plus" type="submit"/>
            <x-adminlte-button label="Finalizar OS" theme="info" icon="fas fa-user-plus" type="submit"/>
            <x-adminlte-button label="Excluir TMO" theme="info" icon="fas fa-user-plus" type="submit"/>     
            <x-adminlte-button label="Concluir" theme="info" icon="fas fa-user-plus" type="submit"/>
            -->
        </x-slot>

    </x-adminlte-card><!-- Fechamento do Painel Principal da Abertura de OS -->

</div>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.Sweetalert2', true)
@section('plugins.toastr', true)
@section('plugins.jqueryValidation', true)

@section('css')
<style>
    .text-sm{
        margin-bottom: 0px;
        font-size: 10pt !important;
    }
    .endereco-cliente{
        font-size: 10pt !important;
    }

    .tabela-dados-os td, .tabela-dados-os th{
        padding: 5px 5px 5px 15px !important;
    }
    .tabela-dados-os th {
        background-color: #001f3f;
        color: #fff;
    }
    .tabela-dados-os {
        border-left: 0px solid #001f3f;
    }

    .tabela-dados-req td, .tabela-dados-req th{
        padding: 5px 5px 5px 15px !important;
    }
    .tabela-dados-req th {
        background-color: #001f3f;
        color: #fff;
    }
    .tabela-dados-req {
        border-left: 0px solid #001f3f;
    }
    .tabela-dados-req .text-sm{
        font-size: 11pt !important;
    }
</style>
@stop

@section('js')
<!--
|--------------------------------------------------------------------------
| Eventos Iniciais da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() { 

        /* ******************** Esconde botões secundarios da app que serão executados pelos botões da barra principal do painel ******************** */

        //Botão quadro - INCLUSAO_REQUISICAO
        $(".btn_hide_incluir_requisicao").hide();

        //Botão quadro -  INCLUSAO_SERVICO / MANUTENCAO_SERVICO
        $(".btn_hide_incluir_tmo").hide();
        $(".btn_hide_atualizar_tmo").hide();

        /* ******************** Ao iniciar a app verifica qual a etapa executada e realiza a exibição dos botões da etapa ******************** */

        var estagioAPP = {!! json_encode($glo_os_estagioAPP) !!};

        if(estagioAPP == 'PRINCIPAL'){
            $(".btn_incluir_requisicao").hide();
            $(".btn_novo_servico").hide();
            $(".btn_incluir_tmo").hide();
            $(".btn_atualizar_tmo").hide();
        }else if(estagioAPP == 'INCLUSAO_REQUISICAO'){
            $(".btn_novo_servico").hide();
            $(".btn_incluir_tmo").hide();
            $(".btn_atualizar_tmo").hide();
        }else if(estagioAPP == 'CONSULTA_REQUISICAO'){
            $(".btn_incluir_requisicao").hide();
            $(".btn_incluir_tmo").hide();
            $(".btn_atualizar_tmo").hide();
        }else if(estagioAPP == 'INCLUSAO_SERVICO'){
            $(".btn_novo_servico").hide();
            $(".btn_incluir_requisicao").hide();
            $(".btn_atualizar_tmo").hide();
        }else if(estagioAPP == 'MANUTENCAO_SERVICO'){
            $(".btn_novo_servico").hide();
            $(".btn_incluir_requisicao").hide();
            $(".btn_incluir_tmo").hide();
        }

        /* ******************** Eventos de totalização do rodapé das tabelas ******************** */

        /* Tabela de detalhes da requisição com os dados dos serviços relacionados */
        var tableDetServ = $('#detalhesServicos').DataTable();

        var intVal = function ( i ) {
            return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
        };

        //Função que altera o rodapé quando a pagina da tabela é alterada
        $('#detalhesServicos').on( 'draw.dt', function () {

            //Total dos registros exibidos na página atual da tabela 
            pagQtdTot = tableDetServ.column(5, { page: 'current'} ).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
            var cnt =0;
            pagValUniTot = tableDetServ.column(6, { page: 'current'} ).data().reduce( function (a, b) {
                if(cnt == 0){
                    a = String(a).replaceAll('.','');
                    a = String(a).replaceAll(',','.');
                }
                cnt +=1;
                b = String(b).replaceAll('.','');
                b = String(b).replaceAll(',','.');
                return intVal(a) + intVal(b);
            }, 0 );
            var cnt =0;
            pagValTot = tableDetServ.column(7, { page: 'current'} ).data().reduce( function (a, b) {
                if(cnt == 0){
                    a = String(a).replaceAll('.','');
                    a = String(a).replaceAll(',','.');
                }
                cnt +=1;
                b = String(b).replaceAll('.','');
                b = String(b).replaceAll(',','.');
                return intVal(a) + intVal(b);
            }, 0 );

            //Total de todos os registros da tabela 
            qtdTot = tableDetServ.column( 5 ).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
            });
            var cnt =0;
            valUniTot = tableDetServ.column( 6 ).data().reduce( function (a, b) {
                if(cnt == 0){
                    a = String(a).replaceAll('.','');
                    a = String(a).replaceAll(',','.');
                }
                cnt +=1;
                b = String(b).replaceAll('.','');
                b = String(b).replaceAll(',','.');
                return intVal(a) + intVal(b);
            });
            var cnt =0;
            valTot = tableDetServ.column( 7 ).data().reduce( function (a, b) {
                if(cnt == 0){
                    a = String(a).replaceAll('.','');
                    a = String(a).replaceAll(',','.');
                }
                cnt +=1;
                b = String(b).replaceAll('.','');
                b = String(b).replaceAll(',','.');
                return intVal(a) + intVal(b);
            });

            //Atualiza o rodapé
            $( tableDetServ.column( 5 ).footer() ).html(new Intl.NumberFormat('en-EN', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(pagQtdTot) +' </br> '+ new Intl.NumberFormat('en-EN', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(qtdTot) );
            $( tableDetServ.column( 6 ).footer() ).html('R$' + new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(pagValUniTot) +' </br> R$'+ new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valUniTot) );
            $( tableDetServ.column( 7 ).footer() ).html('R$' + new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(pagValTot) +' </br> R$'+ new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valTot) );
        });

        //Total dos registros exibidos na página atual da tabela 
        pagQtdTot = tableDetServ.column(5, { page: 'current'} ).data().reduce( function (a, b) {
            return intVal(a) + intVal(b);
        }, 0 );
        var cnt =0;
        pagValUniTot = tableDetServ.column(6, { page: 'current'} ).data().reduce( function (a, b) {
            if(cnt == 0){
                a = String(a).replaceAll('.','');
                a = String(a).replaceAll(',','.');
            }
            cnt +=1;
            b = String(b).replaceAll('.','');
            b = String(b).replaceAll(',','.');
            return intVal(a) + intVal(b);
        }, 0 );
        var cnt =0;
        pagValTot = tableDetServ.column(7, { page: 'current'} ).data().reduce( function (a, b) {
            if(cnt == 0){
                a = String(a).replaceAll('.','');
                a = String(a).replaceAll(',','.');
            }
            cnt +=1;
            b = String(b).replaceAll('.','');
            b = String(b).replaceAll(',','.');
            return intVal(a) + intVal(b);
        }, 0 );

        //Total de todos os registros da tabela 
        qtdTot = tableDetServ.column( 5 ).data().reduce( function (a, b) {
            return intVal(a) + intVal(b);
        });
        var cnt =0;
        valUniTot = tableDetServ.column( 6 ).data().reduce( function (a, b) {
            if(cnt == 0){
                a = String(a).replaceAll('.','');
                a = String(a).replaceAll(',','.');
            }
            cnt +=1;
            b = String(b).replaceAll('.','');
            b = String(b).replaceAll(',','.');
            return intVal(a) + intVal(b);
        });
        var cnt =0;
        valTot = tableDetServ.column( 7 ).data().reduce( function (a, b) {
            if(cnt == 0){
                a = String(a).replaceAll('.','');
                a = String(a).replaceAll(',','.');
            }
            cnt +=1;
            b = String(b).replaceAll('.','');
            b = String(b).replaceAll(',','.');
            return intVal(a) + intVal(b);
        });
        
        //Atualiza o rodapé
        $( tableDetServ.column( 5 ).footer() ).html(new Intl.NumberFormat('en-EN', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(pagQtdTot) +' </br> '+ new Intl.NumberFormat('en-EN', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(qtdTot) );
        $( tableDetServ.column( 6 ).footer() ).html('R$' + new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(pagValUniTot) +' </br> R$'+ new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valUniTot) );
        $( tableDetServ.column( 7 ).footer() ).html('R$' + new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(pagValTot) +' </br> R$'+ new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valTot) );
        /* Final da montagem da Tabela de detalhes da requisição com os dados dos serviços relacionados */
        
    });
</script>

<!--
|--------------------------------------------------------------------------
| Eventos Validate da app
|--------------------------------------------------------------------------
-->
<script>
$(function () {

    $('#quickForm-insert-requisicao').validate({
        rules: {
            descricaoReq: {
                required: true,
                maxlength: 80
            },
            tipoServicoReq: {
                required: true
            },
            categoriaReq: {
                required: true
            },
            setorReq: {
                required: true
            },
            areaReq: {
                required: true
            },
        },
        messages: {
            descricaoReq: {
                required: "Por Favor informe a Descrição da Requisição",
                maxlength: "Infome no máximo 80 caracteres"
            },
            tipoServicoReq: {
                required: "Por Favor informe um Tipo de Serviço da Requisição"
            },
            categoriaReq: {
                required: "Por Favor informe uma Categoria da Requisição"
            },
            setorReq: {
                required: "Por Favor informe o Setor da Requisição"
            },
            areaReq: {
                required: "Por Favor informe a Área da Requisição"
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
});
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
