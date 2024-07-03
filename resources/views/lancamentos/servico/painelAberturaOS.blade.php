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
                    <a href="{{route('home.situacaoOS')}}">Situação de OS</a>
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
$glo_os_dadosServicos = session('glo_os_dadosServicos');
$glo_os_dadosEmpresa = session('glo_os_dadosEmpresa');
$glo_os_dadosEmpresaEndereco = session('glo_os_dadosEmpresaEndereco');
$glo_os_dadosCliente = session('glo_os_dadosCliente');
$glo_os_dadosClienteEndereco = session('glo_os_dadosClienteEndereco');
$glo_os_empresa = session('glo_os_empresa');
$glo_os_cliente = session('glo_os_cliente');
$glo_os_nos = session('glo_os_nos');
$glo_os_estagioAPP = session('glo_os_estagioAPP');
$glo_os_req_eat_cod = session('glo_os_req_eat_cod');
$glo_os_req_eat_cat = session('glo_os_req_eat_cat');
//$glo_os_req_eat_are = session('glo_os_req_eat_are');
$glo_os_req_eat_ord = session('glo_os_req_eat_ord');
$glo_os_dadosServico = session('glo_os_dadosServico');
$glo_os_dadosTMO = session('glo_os_dadosTMO');
$glo_os_dadosTmoSelecionada = session('glo_os_dadosTmoSelecionada');
$glo_os_subEstagioRequisicao = session('glo_os_subEstagioRequisicao');
$glo_os_dadosServicoSelecionado = session('glo_os_dadosServicoSelecionado');


$altValorTOS = '';
$altHoraTOS = '';
$status_requisicao = '';
$status_servico = '';

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
                        if($glo_os_dadosOS[0]->os_sts == 'A'){
                            $situacao = "Aberta";
                        }elseif($glo_os_dadosOS[0]->os_sts == 'F'){
                            $situacao = "Finalizada";
                        }else{
                            $situacao = "Cancelada";
                        }

                        $dataAbertura = date("d/m/Y H:i:s", strtotime($glo_os_dadosOS[0]->os_dha));

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

                @php 
                    $status_os = $glo_os_dadosOS[0]->os_sts;
                @endphp

                @if($glo_os_dadosOS[0]->os_sts == 'F' || $glo_os_dadosOS[0]->os_sts == 'C')
                <!-- ********** Bloco do resumo da os ********** -->
                <x-adminlte-card title="Resumo da OS" theme="navy" theme-mode="outline" collapsible maximizable>
                    @php 
                        $usu_abr = DB::table('users')->where('usuario_codigo', $glo_os_dadosOS[0]->os_res_abr)->get();
                    @endphp
                    <table class="table text-sm">
                        <tbody>
                            <tr>
                                <td colspan="2" style="border: 0px;">
                                    <p class="text-sm">Responsável da Abertura OS
                                        <b class="d-block">{{ $glo_os_dadosOS[0]->os_res_abr }} - {{$usu_abr[0]->name}}</b>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                @if($glo_os_dadosOS[0]->os_sts == 'F')
                                @php 
                                    $usu_fec = DB::table('users')->where('usuario_codigo', $glo_os_dadosOS[0]->os_res_fec)->get();
                                @endphp
                                <td colspan="2">
                                    <p class="text-sm">Responsável do Encerramento OS
                                        <b class="d-block">{{ $glo_os_dadosOS[0]->os_res_fec }} - {{$usu_fec[0]->name}}</b>
                                    </p>
                                </td>
                                <td colspan="2">
                                    <p class="text-sm">Data e Hora do Encerramento
                                        <b class="d-block">{{ Helper::formataDataHora($glo_os_dadosOS[0]->os_dhf) }}</b>
                                    </p>
                                </td>
                                @else
                                @php 
                                    $usu_can = DB::table('users')->where('usuario_codigo', $glo_os_dadosOS[0]->os_res_abr)->get();
                                @endphp
                                <td colspan="2">
                                    <p class="text-sm">Responsável do Cancelamento OS
                                        <b class="d-block">{{ $glo_os_dadosOS[0]->os_res_can }} - {{$usu_can[0]->name}}</b>
                                    </p>
                                </td>
                                <td colspan="2">
                                    <p class="text-sm">Data do Cancelamento
                                        <b class="d-block">{{ Helper::formataData($glo_os_dadosOS[0]->os_dtc) }}</b>
                                    </p>
                                </td>
                                @endif
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: .35rem;"><strong>Valor Total Bruto da OS:</strong></td>
                                <td colspan="2" style="text-align: right; padding: .35rem;">R${{Helper::formataValorMonetario($glo_os_dadosOS[0]->os_vlr)}}</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: .35rem;"><strong>Valor Total de Descontos:</strong></td>
                                <td colspan="2" style="text-align: right; padding: .35rem;">R${{Helper::formataValorMonetario($glo_os_dadosOS[0]->os_val_des+$glo_os_dadosOS[0]->os_val_des_srv)}}</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: .35rem;"><strong>Valor Total à Pagar:</strong></td>
                                <td colspan="2" style="text-align: right; padding: .35rem;">R${{Helper::formataValorMonetario($glo_os_dadosOS[0]->os_vlt)}}</td>
                            </tr>
                        </tbody>
                    </table>                            
                </x-adminlte-card><!-- Fechamento do bloco do resumo da os ********** -->
                @else
                <!-- ********** Bloco dos dados das etapas de atendimento da abertura de OS ********** -->
                <x-adminlte-card title="Etapas de Atendimento" theme="navy" theme-mode="outline" collapsible maximizable>
                    @php 
                        $etapas = DB::table('lancamento_srv_etapa_atendimentos')->where('eat_emp', $glo_os_empresa)->orderBy('eat_ord', 'asc')->orderBy('eat_ord', 'asc')->get();
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
                                    <td style="border: 0px;"><a href="{{ route('painelOS.abreRequisicao', ['empresa' => $glo_os_empresa, 'nos' => $glo_os_nos, 'estagioAPP' => 'INCLUSAO_REQUISICAO', 'glo_eat_cod' => $etapa->eat_cod, 'glo_eat_ord' => $etapa->eat_ord]) }}">{{$etapa->eat_nom}}</a></td>
                                    @else
                                    <td><a href="{{ route('painelOS.abreRequisicao', ['empresa' => $glo_os_empresa, 'nos' => $glo_os_nos, 'estagioAPP' => 'INCLUSAO_REQUISICAO', 'glo_eat_cod' => $etapa->eat_cod, 'glo_eat_ord' => $etapa->eat_ord]) }}">{{$etapa->eat_nom}}</a></td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>                            
                </x-adminlte-card><!-- Fechamento do bloco dos dados do atendimento da abertura de OS ********** -->
                @endif

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
                            @php
                                $data_set = DB::table('parametros_srv_setores')->where('setor_empresa', $glo_os_empresa)->where('setor_codigo', $requisicao->req_set)->where('setor_area', $requisicao->req_are)->get();

                                $data_tos = DB::table('lancamento_srv_tipo_servicos')->where('tipsrv_emp', $glo_os_empresa)->where('tipsrv_cod', $requisicao->req_tos)->get();

                                $setor = $requisicao->req_set.' - '.$data_set[0]->setor_desc;

                                $tipo_servico = $requisicao->req_tos.' - '.$data_tos[0]->tipsrv_nom;
                            @endphp
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
                                <td>{{ $setor }}</td>
                                <td>{{ $tipo_servico }}</td>
                                <td>{{ $requisicao->req_vlt }}</td>
                                <td>
                                    <nobr class="d-flex justify-content-center">
                                        @if($requisicao->req_sts == 'F')
                                        <a class="text-muted" title="Finalizado" >
                                            <i class="fa-solid fa-circle-check fa-lg text-success"></i>
                                        </a>
                                        @elseif($requisicao->req_sts == 'A')
                                        <a class="text-muted" title="Em Andamento">
                                            <i class="fa-solid fa-clock-rotate-left fa-lg text-info"></i>
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
                            
                                //Categoria
                                $data_cat = DB::table('lancamento_srv_categorias')->where('categoria_codigo', $glo_os_req_eat_cat)->get();

                                $new_array1_cat =[];
                                $new_array2_cat =[];

                                foreach ($data_cat as $categoria) {
                                    $new_array1_cat[] = $categoria->categoria_codigo;
                                    $new_array2_cat[] = $categoria->categoria_codigo.' - '.$categoria->categoria_desc;
                                }
                                $array_opt_cat = array_combine($new_array1_cat, $new_array2_cat);

                                $eat_cat_sel = $glo_os_req_eat_cat;

                                //Areas
                                $whrIn = DB::table('parametros_srv_setores')->select('setor_area')->where('setor_empresa', $glo_os_empresa);
                                $data_are = DB::table('parametros_sistema_areas')->select('area_codigo', 'area_desc')->wherein('area_codigo', $whrIn)->orderBy('area_codigo', 'asc')->get();

                                $new_array1_are =[];
                                $new_array2_are =[];

                                foreach ($data_are as $area) {
                                    $new_array1_are[] = $area->area_codigo;
                                    $new_array2_are[] = $area->area_codigo.' - '.$area->area_desc;
                                }
                                $array_opt_are = array_combine($new_array1_are, $new_array2_are);

                                $array_opt_set = null;
                                $array_opt_tos = null;
                                
                            @endphp

                            <!-- Categoria -->
                            <x-adminlte-select name="categoriaReq" label="Categoria" fgroup-class="col-md-3">
                                <x-adminlte-options :options="$array_opt_cat" selected="{{$eat_cat_sel}}"/>
                            </x-adminlte-select>

                            <!-- área -->
                            <x-adminlte-select name="areaReq" label="Área" fgroup-class="col-md-3">
                                <x-adminlte-options :options="$array_opt_are" empty-option="Selecione..." selected=""/>
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
                                                @php 
                                                    $status_requisicao = $glo_os_dadosRequisicoes[0]['req_sts'];
                                                @endphp
                                                @if($glo_os_dadosRequisicoes[0]['req_sts'] == 'F')
                                                <a class="text-muted" title="Finalizado" >
                                                    <i title="Finalizado" class="fa-solid fa-circle-check fa-xl text-success"></i>
                                                </a>
                                                @elseif($glo_os_dadosRequisicoes[0]['req_sts'] == 'A')
                                                <a class="text-muted" title="Em Andamento">
                                                    <i title="Em Andamento" class="fa-solid fa-clock-rotate-left fa-xl text-info"></i>
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
                        'Seq.',
                        'Código',
                        'Descrição',
                        'Prestador',
                        'Tipo Hr.',
                        'Und.',
                        'Qtd.',
                        'Val. Uni.',
                        'Total',
                        'Desc.',
                        'Val. Liq.',
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
                        'columns' => [['orderable' => false], null, null, null, null, null, null, null, null, null, null, null, null, null],
                    ];
                    @endphp
                    <x-adminlte-datatable id="detalhesServicos" :heads="$heads1" :config="$config1" theme="light" striped hoverable beautify>
                        @foreach($glo_os_dadosServico as $servico)
                            @php 
                                $srv_vhr = number_format($servico->srv_vhr,2,",",".");
                                $srv_vtl = number_format($servico->srv_vtl,2,",",".");
                                $srv_vts = number_format($servico->srv_vts,2,",",".");
                                $srv_val_des = number_format($servico->srv_val_des,2,",",".");

                                if($servico->srv_sts == 'F'){
                                    $icone_sts_servico = "fa-solid fa-circle-check fa-lg text-success";
                                    $title = "Finalizado";
                                }elseif($servico->srv_sts == 'A'){
                                    $icone_sts_servico = "fa-solid fa-clock-rotate-left fa-lg text-info";
                                    $title = "Em Andamento";
                                }elseif($servico->srv_sts == 'C'){
                                    $icone_sts_servico = "fa-solid fa-circle-xmark fa-lg text-danger";
                                    $title = "Cancelado";
                                }elseif($servico->srv_sts == 'E'){
                                    $icone_sts_servico = "fa-solid fa-hourglass-start fa-lg text-primary";
                                    $title = "Em Espera";
                                }else{
                                    $icone_sts_servico = "fa-solid fa-triangle-exclamation fa-lg text-warning";
                                    $title = "Suspenso";
                                }

                                if($servico->srv_flg_apr == 'S'){
                                    $icone_apr_servico = "fa-solid fa-circle-check fa-lg text-success";
                                }else{
                                    $icone_apr_servico = "fa-solid fa-circle-xmark fa-lg text-danger";
                                }

                                if(!empty($servico->srv_prt)){
                                    $data_prest = DB::table('cadastro_prestadores')->where('prestador_empresa', $glo_os_empresa)->where('prestador_codigo', $servico->srv_prt)->get();

                                    $prestador = $servico->srv_prt.' - '.$data_prest[0]->prestador_nome;
                                }else{
                                    $prestador = '';
                                }

                                if($servico->srv_ths == 'F'){
                                    $tipo_hora = "Fixo";
                                }elseif($servico->srv_ths == 'P'){
                                    $tipo_hora = "Padrão";
                                }elseif($servico->srv_ths == 'I'){
                                    $tipo_hora = "Informada";
                                }elseif($servico->srv_ths == 'R'){
                                    $tipo_hora = "Real";
                                }else{
                                    $tipo_hora = "Terceiros";
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
                                <td>{{$servico->srv_seq}}</td>
                                <td>{{$servico->srv_tmo}}</td>
                                <td>{{$servico->srv_dsc}}</td>  
                                <td>{{$prestador}}</td>
                                <td>{{$tipo_hora}}</td>
                                <td>{{$servico->srv_und}}</td>
                                <td>{{$servico->srv_qhr}}</td>
                                <td>{{$srv_vhr}}</td> 
                                <td>{{$srv_vts}}</td>
                                <td>{{$srv_val_des}}</td>
                                <td>{{$srv_vtl}}</td>
                                <td>
                                    @if($servico->srv_flg_apr == 'S')
                                    <i title="Aprovado" class="{{$icone_apr_servico}}"></i>
                                    @else
                                    <a data-toggle="modal" data-target="#aprovaServico_{{$servico->srv_seq}}">
                                        <i title="Não Aprovado" class="{{$icone_apr_servico}}"></i>
                                    </a>
                                    <x-adminlte-modal id="aprovaServico_{{$servico->srv_seq}}" title="Aprovar Serviço" size="xl" theme="navy" v-centered static-backdrop scrollable>
                                        <div class="row" style="height:auto;">
                                            <p class="text-sm col-md-1">Seq.
                                                <b class="d-block">{{$servico->srv_seq}}</b>
                                            </p>
                                            <p class="text-sm col-md-1">Código
                                                <b class="d-block">{{$servico->srv_tmo}}</b>
                                            </p>
                                            <p class="text-sm col-md-4">Descrição
                                                <b class="d-block">{{$servico->srv_dsc}}</b>
                                            </p>
                                            @php 
                                                if($servico->srv_ths == 'F'){
                                                    $tipo_hora = "Fixo";
                                                }elseif($servico->srv_ths == 'P'){
                                                    $tipo_hora = "Padrão";
                                                }elseif($servico->srv_ths == 'I'){
                                                    $tipo_hora = "Informada";
                                                }elseif($servico->srv_ths == 'R'){
                                                    $tipo_hora = "Real";
                                                }else{
                                                    $tipo_hora = "Terceiros";
                                                }
                                            @endphp
                                            <p class="text-sm col-md-1">Tipo da Hora
                                                <b class="d-block">{{$tipo_hora}}</b>
                                            </p>
                                            <p class="text-sm col-md-1">Qtd.
                                                <b class="d-block">{{$servico->srv_qhr}}</b>
                                            </p>
                                            <p class="text-sm col-md-1">Val. Uni.
                                                <b class="d-block">{{$srv_vhr}}</b>
                                            </p>
                                            <p class="text-sm col-md-1">Total
                                                <b class="d-block">{{$srv_vts}}</b>
                                            </p>
                                            <p class="text-sm col-md-1">Val. Desc.
                                                <b class="d-block">{{$srv_val_des}}</b>
                                            </p>
                                            <p class="text-sm col-md-1">Val. Liq.
                                                <b class="d-block">{{$srv_vtl}}</b>
                                            </p>
                                        </div>
                                        <x-slot name="footerSlot">
                                            <form method="get" action="{{route('servicoOS.aprovar', ['empresa'=> $glo_os_empresa,'numOS'=> $glo_os_nos,'requisicao'=> $servico->srv_req,'sequencia'=> $servico->srv_seq,'codTMO'=> $servico->srv_tmo])}}">
                                                @csrf 
                                                <x-adminlte-button class="mr-auto" theme="info" label="Aprovar" value="Aprovar" type="submit"/>
                                            </form>
                                            <x-adminlte-button theme="info" label="Voltar" data-dismiss="modal"/>
                                        </x-slot>
                                    </x-adminlte-modal>
                                    @endif
                                </td>
                                <td><i title="{{$title}}" class="{{$icone_sts_servico}}"></i></td>   
                            </tr>
                        @endforeach
                        <tfoot class="thead-light">
                            <tr>
                                <th colspan="7" style="text-align:left">Sub-Total</br>Total Geral</th>
                                <th style="text-align:center"></th>
                                <th style="text-align:center"></th>
                                <th style="text-align:center"></th>
                                <th style="text-align:center"></th>
                                <th style="text-align:center"></th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </x-adminlte-datatable>

                    <!-- Botão hide de inclusão de nova requisição -->
                    <x-adminlte-button class="btn_hide_iniciar_servicos" type="button" onclick="window.location='{{ route('servicoOS.iniciar', ['empresa'=> $glo_os_empresa,'numOS'=> $glo_os_nos,'requisicao'=> $glo_os_dadosRequisicoes[0]['req_seq']]) }}'" label="Iniciar Serviços" theme="info"/>
                    <x-adminlte-button class="btn_hide_finalizar_servicos" type="button" onclick="window.location='{{ route('servicoOS.finalizar', ['empresa'=> $glo_os_empresa,'numOS'=> $glo_os_nos,'requisicao'=> $glo_os_dadosRequisicoes[0]['req_seq']]) }}'" label="Finalizar Serviços" theme="info"/>
                    <x-adminlte-button class="btn_hide_finalizar_requisicao" type="button" onclick="window.location='{{ route('requisicaoOS.finalizar', ['empresa'=> $glo_os_empresa,'numOS'=> $glo_os_nos,'requisicao'=> $glo_os_dadosRequisicoes[0]['req_seq']]) }}'" label="Finalizar Requisição" theme="info"/>
                    <x-adminlte-button class="btn_hide_reabrir_requisicao" type="button" onclick="window.location='{{ route('requisicaoOS.reabrir', ['empresa'=> $glo_os_empresa,'numOS'=> $glo_os_nos,'requisicao'=> $glo_os_dadosRequisicoes[0]['req_seq']]) }}'" label="Reabrir Requisição" theme="info"/>

                    <form method="post" action="{{ route('requisicaoOS.destroy', ['requisicaoOS' => $glo_os_dadosRequisicoes[0]['req_id'], 'empresa'=> $glo_os_empresa, 'cliente'=> $glo_os_cliente, 'numOS'=> $glo_os_nos]) }}">
                        @csrf 
                        @method('delete')
                        <x-adminlte-button class="btn_hide_excluir_requisicao" type="submit" label="Excluir Requisição" value="Excluir Requisição" theme="info"/>
                    </form>

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
                                                @php 
                                                    $status_requisicao = $glo_os_dadosRequisicoes[0]['req_sts'];
                                                @endphp
                                                @if($glo_os_dadosRequisicoes[0]['req_sts'] == 'F')
                                                <a class="text-muted" title="Finalizado" >
                                                    <i class="fa-solid fa-circle-check fa-xl text-success"></i>
                                                </a>
                                                @elseif($glo_os_dadosRequisicoes[0]['req_sts'] == 'A')
                                                <a class="text-muted" title="Andamento">
                                                    <i class="fa-solid fa-clock-rotate-left fa-xl text-info"></i>
                                                </a>
                                                @endif
                                            </b>
                                        </p>
                                    </td>
                                </tr>
                                @php
                                    $nom_cat = DB::table('lancamento_srv_categorias')->select('categoria_desc')->where('categoria_codigo', $glo_os_dadosRequisicoes[0]['req_cat'])->get();

                                    $nom_are = DB::table('parametros_sistema_areas')->select('area_desc')->where('area_codigo', $glo_os_dadosRequisicoes[0]['req_are'])->get();

                                    $nom_tos = DB::table('lancamento_srv_tipo_servicos')->select('tipsrv_nom', 'tipsrv_ahs', 'tipsrv_avs')->where('tipsrv_emp', $glo_os_empresa)->where('tipsrv_cod', $glo_os_dadosRequisicoes[0]['req_tos'])->get();

                                    $nom_set = DB::table('parametros_srv_setores')->select('setor_desc')->where('setor_empresa', $glo_os_empresa)->where('setor_codigo', $glo_os_dadosRequisicoes[0]['req_set'])->get();

                                    //Cria variaveis que serão utilizadas no JS inicial
                                    $altValorTOS = $nom_tos[0]->tipsrv_avs;
                                    $altHoraTOS = $nom_tos[0]->tipsrv_ahs;
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
                                        @php 
                                            $status_servico = $glo_os_dadosServicoSelecionado[0]->srv_sts;
                                        @endphp
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
                                                @elseif($glo_os_dadosServicoSelecionado[0]->srv_sts == 'E')
                                                <a class="text-muted" title="Em Espera">
                                                    <i class="fa-solid fa-hourglass-start fa-2xl text-primary"></i>
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
                                                                        <form method="get" action="{{route('painelOS.selecionarTMO', ['empresa' => $glo_os_empresa, 'area' => $glo_os_dadosRequisicoes[0]['req_are'], 'setor' => $glo_os_dadosRequisicoes[0]['req_set'], 'codigo' => $TMO->tmo_cod, 'estagioAPP' => 'INCLUSAO_SERVICO', 'subEstagioRequisicao' => 'TMO_SELECIONADA'])}}">
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
                    <form method="post" action="{{route('servicoOS.inserir', ['empresa' => $glo_os_empresa, 'numOS' => $glo_os_nos, 'requisicao' => $glo_os_dadosRequisicoes[0]['req_seq'], 'codTMO' => $glo_os_dadosTmoSelecionada[0]->tmo_cod, 'estagioAPP' => 'CONSULTA_REQUISICAO'])}}" id="quickForm-ins-upd-servico" novalidate="novalidate">
                    @else
                    <form method="post" action="{{route('servicoOS.atualizar', ['empresa' => $glo_os_empresa, 'numOS' => $glo_os_nos, 'requisicao' => $glo_os_dadosServicoSelecionado[0]->srv_req, 'sequencia' => $glo_os_dadosServicoSelecionado[0]->srv_seq, 'codTMO' => $glo_os_dadosServicoSelecionado[0]->srv_tmo, 'estagioAPP' => 'CONSULTA_REQUISICAO', 'tos' => $glo_os_dadosRequisicoes[0]['req_tos']])}}" id="quickForm-ins-upd-servico" novalidate="novalidate">
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

                                    //$prestadorTOS = DB::table('lancamento_srv_tipo_servicos')->select('tipsrv_res')->where('tipsrv_emp',$glo_os_empresa)->where('tipsrv_cod',$glo_os_dadosRequisicoes[0]['req_tos'])->get();
                                    if(!empty($glo_os_dadosTmoSelecionada[0]->tmo_res)){
                                        $prestadorTMO_sel = $glo_os_dadosTmoSelecionada[0]->tmo_res;
                                    }else{
                                        $prestadorTMO_sel = '';
                                    }
                                }else{
                                    $tipoTMO_sel = $glo_os_dadosServicoSelecionado[0]->srv_ths;
                                    $prestadorTMO_sel = $glo_os_dadosServicoSelecionado[0]->srv_prt;
                                }

                                if(!empty($prestadorTMO_sel)){
                                    $prestador_nom = DB::table('cadastro_prestadores')->select('prestador_nome')->where('prestador_codigo', $prestadorTMO_sel)->get();
                                    $prestadorTMO_sel = $prestadorTMO_sel.' - '.$prestador_nom[0]->prestador_nome;
                                }

                                //Faz o lookup do campo de fornecedores 
                                $data_pres = DB::table('cadastro_prestadores')->selectRaw('prestador_codigo, prestador_nome')->where('prestador_set',$glo_os_dadosRequisicoes[0]['req_set'])->where('prestador_are',$glo_os_dadosRequisicoes[0]['req_are'])->orderBy('prestador_codigo', 'asc')->get();
                                $html = '<datalist id="prestadores">';
                                foreach($data_pres as $prestador){
                                    $html .= '<option value="'.$prestador->prestador_codigo.' - '.$prestador->prestador_nome.'">'.$prestador->prestador_codigo.' - '.$prestador->prestador_nome.'</option>';
                                }
                                $html .='</datalist>';
                                //Echo adiciona o html ao campo dos fornecedores
                                echo $html;
                            @endphp
                            <!-- Tipo da TMO -->
                            <x-adminlte-select name="tipoTMO" label="Tipo da Tarefa" fgroup-class="col-md-4">
                                <x-adminlte-options :options="['P' => 'Padrão', 'I' => 'Hora Informada', 'R' => 'Hora Real', 'F' => 'Valor Fixo', 'T' => 'Terceiros']" selected="{{$tipoTMO_sel}}"/>
                            </x-adminlte-select>
                            <!-- Prestador responsavel da TMO -->
                            <x-adminlte-input name="prestadorTMO" label="Prestador da Tarefa" type="search" list="prestadores" value="{{$prestadorTMO_sel}}" fgroup-class="col-md-8"/>
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
                            <x-adminlte-input name="qtdHrTMO" label="Qtd. de Horas" type="text" value="{{$qtdHrTMO_sel}}" placeholder="0.00" fgroup-class="col-md-4"/>
                            <!-- Valor da Hora -->
                            <x-adminlte-input name="valUniHrTMO" label="Valor Unitário" type="text" value="{{$valUniHrTMO_sel}}" placeholder="0,00" fgroup-class="col-md-4"/>
                            <!-- Valor Total -->
                            <x-adminlte-input name="valTotHrTMO" label="Valor Total" type="text" value="{{$valTotHrTMO_sel}}" placeholder="0,00" fgroup-class="col-md-4"/>
                        </div>

                        @if($glo_os_estagioAPP == "MANUTENCAO_SERVICO")
                        <div class="row">
                            @php 
                                $perDesTMO_sel = $glo_os_dadosServicoSelecionado[0]->srv_per_des;
                                $valDesTMO_sel = $glo_os_dadosServicoSelecionado[0]->srv_val_des;
                                $valLiqTMO_sel = $glo_os_dadosServicoSelecionado[0]->srv_vtl;
                            @endphp
                            <!-- Quantidade de Hortas -->
                            <x-adminlte-input name="perDesTMO" label="Percentual de Desconto" type="text" value="{{$perDesTMO_sel}}" placeholder="0,00" fgroup-class="col-md-4"/>
                            <!-- Valor da Hora -->
                            <x-adminlte-input name="valDesTMO" label="Valor de Desconto" type="text" value="{{$valDesTMO_sel}}" placeholder="0,00" fgroup-class="col-md-4"/>
                            <!-- Valor Total -->
                            <x-adminlte-input name="valLiqTMO" label="Valor Total Líquido" type="text" value="{{$valLiqTMO_sel}}" placeholder="0,00" fgroup-class="col-md-4"/>
                        </div>
                        @endif

                        <div class="bloco-terceiros">
                            <div class="row">
                                @php 
                                    if($glo_os_estagioAPP == "INCLUSAO_SERVICO"){
                                        $forTerceiroTMO_sel = $glo_os_dadosTmoSelecionada[0]->tmo_for_cgt;
                                    }else{
                                        $forTerceiroTMO_sel = $glo_os_dadosServicoSelecionado[0]->srv_for;
                                    }

                                    if(!empty($forTerceiroTMO_sel)){
                                        $cli_nom = DB::table('cadastro_clientes')->select('cliente_nome')->where('cliente_codigo', $forTerceiroTMO_sel)->get();
                                        $forTerceiroTMO_sel = $forTerceiroTMO_sel.' - '.$cli_nom[0]->cliente_nome;
                                    }

                                    //Faz o lookup do campo de fornecedores 
                                    $data_cli = DB::table('cadastro_clientes')->selectRaw('cliente_codigo, cliente_nome')->where('cliente_tipo_cadastro','F')->orderBy('cliente_codigo', 'asc')->get();
                                    $html = '<datalist id="fornecedores">';
                                    foreach($data_cli as $cliente){
                                        $html .= '<option value="'.$cliente->cliente_codigo.' - '.$cliente->cliente_nome.'">'.$cliente->cliente_codigo.' - '.$cliente->cliente_nome.'</option>';
                                    }
                                    $html .='</datalist>';
                                    //Echo adiciona o html ao campo dos fornecedores
                                    echo $html;
                                @endphp
                                <!-- Fornecedor do Serviço de Terceiros -->
                                <x-adminlte-input name="forTerceiroTMO" label="Fornecedor Terceiro" type="search" list="fornecedores" value="{{$forTerceiroTMO_sel}}" fgroup-class="col-md-6"/>
                            </div>

                            <div class="row">
                                @php
                                    if($glo_os_estagioAPP == "INCLUSAO_SERVICO"){
                                        $data_nf = '';
                                    }else{
                                        if(!empty($glo_os_dadosServicoSelecionado[0]->srv_dtt)){
                                            $data_nf = $data_nf = date('d/m/Y', strtotime($glo_os_dadosServicoSelecionado[0]->srv_dtt));
                                        }else{
                                            $data_nf = '';
                                        }
                                    }

                                    $config_dt_nf = [
                                        "singleDatePicker" => true,
                                        "showDropdowns" => true,
                                        "startDate" => "js:moment()",
                                        "minYear" => 1900,
                                        "maxYear" => "js:parseInt(moment().format('YYYY'),10)",
                                        "timePicker" => false,
                                        "timePicker24Hour" => false,
                                        "timePickerSeconds" => false,
                                        "cancelButtonClasses" => "btn-danger",
                                        "locale" => ["format" => "DD/MM/YYYY"],
                                    ];

                                    if($glo_os_estagioAPP == "INCLUSAO_SERVICO"){
                                        $numNFTMO_sel = '';
                                        $serNFTMO_sel = '';
                                    }else{
                                        $numNFTMO_sel = $glo_os_dadosServicoSelecionado[0]->srv_nft;
                                        $serNFTMO_sel = $glo_os_dadosServicoSelecionado[0]->srv_srt;
                                    }
                                @endphp
                                <!-- Número da NF -->
                                <x-adminlte-input name="numNfTerceiroTMO" label="Número da NF" type="text" value="{{$numNFTMO_sel}}" fgroup-class="col-md-4"/>
                                <!-- Série da NF -->
                                <x-adminlte-input name="serNfTerceiroTMO" label="Série da NF" type="text" value="{{$serNFTMO_sel}}" fgroup-class="col-md-4"/>
                                <!-- Data da NF -->
                                <x-adminlte-date-range name="dataNfTerceiroTMO" label="Data da NF" :config="$config_dt_nf" placeholder="Formato dia/mês/ano" fgroup-class="col-md-4">
                                    <x-slot name="prependSlot">
                                        <div class="input-group-text">
                                            <i class="far fa-lg fa-calendar-alt"></i>
                                        </div>
                                    </x-slot>
                                </x-adminlte-date-range>
                                @push('js')<script>$(() => $("#dataNfTerceiroTMO").val('{{ $data_nf }}'))</script>@endpush
                            </div>
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
                            <!-- Tipo do Valor do custo gerencial de terceiros -->
                            <x-adminlte-select name="tipCustoTMO" label="Tipo do Custo" fgroup-class="col-md-4">
                                <x-adminlte-options :options="['1' => 'Valor', '2' => 'Percentual']" selected="{{$tipCustoTMO_sel}}"/>
                            </x-adminlte-select>
                            <!-- Valor do custo -->
                            <x-adminlte-input name="valCustoTMO" label="Valor do Custo" type="text" value="{{$valCustoTMO_sel}}" placeholder="0,00" fgroup-class="col-md-4"/>
                            <!-- Valor percentual do custo -->
                            <x-adminlte-input name="perCustoTMO" label="Percentual do custo" type="text" value="{{$perCustoTMO_sel}}" placeholder="0,00" fgroup-class="col-md-4"/>
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
                        
                    <!-- Botão hide de manutenção da tmo e Modal de liberação de desconto -->
                    @if($glo_os_estagioAPP == "MANUTENCAO_SERVICO")
                        <x-adminlte-button class="btn_hide_suspender_tmo" type="button" onclick="window.location='{{ route('servicoOS.suspender', ['empresa'=> $glo_os_empresa,'numOS'=> $glo_os_nos, 'requisicao'=> $glo_os_dadosRequisicoes[0]['req_seq'], 'sequencia' => $glo_os_dadosServicoSelecionado[0]->srv_seq, 'codTMO' => $cod_tmo_sel]) }}'" label="Suspender TMO" theme="info"/>
                        <x-adminlte-button class="btn_hide_cancelar_tmo" type="button" onclick="window.location='{{ route('servicoOS.cancelar', ['empresa'=> $glo_os_empresa,'numOS'=> $glo_os_nos, 'requisicao'=> $glo_os_dadosRequisicoes[0]['req_seq'], 'sequencia' => $glo_os_dadosServicoSelecionado[0]->srv_seq, 'codTMO' => $cod_tmo_sel]) }}'" label="Cancelar TMO" theme="info"/>
                        <x-adminlte-button class="btn_hide_reabrir_tmo" type="button" onclick="window.location='{{ route('servicoOS.reabrir', ['empresa'=> $glo_os_empresa,'numOS'=> $glo_os_nos, 'requisicao'=> $glo_os_dadosRequisicoes[0]['req_seq'], 'sequencia' => $glo_os_dadosServicoSelecionado[0]->srv_seq, 'codTMO' => $cod_tmo_sel]) }}'" label="Reabrir TMO" theme="info"/>

                        <form method="post" action="{{ route('servicoOS.destroy', ['servicoOS' => $glo_os_dadosServicoSelecionado[0]->srv_id, 'empresa'=> $glo_os_empresa,'numOS'=> $glo_os_nos, 'requisicao'=> $glo_os_dadosRequisicoes[0]['req_seq']]) }}">
                            @csrf 
                            @method('delete')
                            <x-adminlte-button class="btn_hide_excluir_tmo" type="submit" label="Excluir TMO" value="Excluir TMO" theme="info"/>
                        </form>

                        <!-- Modal de Liberação de Desconto da TMO -->
                        <form method="post" action="{{ route('requisicaoOS.autorizaDescTMO', ['empresa'=> $glo_os_empresa,'numOS'=> $glo_os_nos, 'requisicao' => $glo_os_dadosRequisicoes[0]['req_seq'], 'sequencia' => $glo_os_dadosServicoSelecionado[0]->srv_seq, 'tmo' => $glo_os_dadosServicoSelecionado[0]->srv_tmo]) }}" id="quickForm-aut-desconto" novalidate="novalidate">
                            @csrf 
                            @method('post')
                            <x-adminlte-modal id="modalLibDescTMO" title="Liberação de Desconto na TMO" size="xl" theme="navy" icon="fa-solid fa-lock-open" v-centered scrollable>
                                @php
                                    $nom_tos = DB::table('lancamento_srv_tipo_servicos')->select('tipsrv_nom','tipsrv_pmt_des','tipsrv_pmd','tipsrv_vmd')->where('tipsrv_emp', $glo_os_empresa)->where('tipsrv_cod', $glo_os_dadosRequisicoes[0]['req_tos'])->get();
                                @endphp
                                <div class="row" style="height:auto;">                       
                                    <div class="post  col-md-12">
                                        <div class="row">
                                            <div class="text-muted col-md-3">
                                                <p class="text-sm">Tipo de Serviço
                                                    <b class="d-block">{{ $glo_os_dadosRequisicoes[0]['req_tos'].' - '.$nom_tos[0]->tipsrv_nom}}</b>
                                                </p>
                                            </div>
                                            <div class="text-muted col-md-3">
                                                <p class="text-sm">Permite desconto
                                                    <b class="d-block">@if($nom_tos[0]->tipsrv_pmt_des == 'S') Sim @else Não @endif</b>
                                                </p>
                                            </div>
                                            <div class="text-muted col-md-3">
                                                <p class="text-sm">Percentual Máximo de Desconto
                                                    <b class="d-block">{{Helper::formataValorMonetario($nom_tos[0]->tipsrv_pmd).'%'}}</b>
                                                </p>
                                            </div>
                                            <div class="text-muted col-md-3">
                                                <p class="text-sm">Valor Máximo de Desconto
                                                    <b class="d-block">{{'R$'.Helper::formataValorMonetario($nom_tos[0]->tipsrv_vmd)}}</b>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="post col-md-12">
                                        <div class="row">
                                            @if($glo_os_dadosServicoSelecionado[0]->srv_aut_desc == 'N')
                                            <x-adminlte-input-switch name="liberaDesc" label="Desconto Liberado" data-on-text="Sim" data-off-text="Não" data-on-color="success" data-off-color="danger" fgroup-class="col-md-2" igroup-size="sm"/>
                                            @else
                                            <x-adminlte-input-switch name="liberaDesc" label="Desconto Liberado" data-on-text="Sim" data-off-text="Não" data-on-color="success" data-off-color="danger" fgroup-class="col-md-2" igroup-size="sm" checked/>
                                            @endif
                                            <!-- Usuário com permissão de dar o desconto -->
                                            <x-adminlte-input name="usuarioDesconto" label="Código do Usuário" type="text" placeholder="Usuário" fgroup-class="col-md-5" igroup-size="sm"/>
                                            <!-- Senha -->
                                            <x-adminlte-input name="senhaDesconto" label="Senha" placeholder="Senha" type="password" fgroup-class="col-md-5" igroup-size="sm"/>
                                        </div>
                                    </div>
                                </div>
                                <!-- Criação dos botões do Modal -->  
                                <x-slot name="footerSlot">
                                    <x-adminlte-button class="mr-auto" theme="info" label="Autorizar" icon="fa-solid fa-handshake" type="submit"/>
                                    <x-adminlte-button theme="info" label="Voltar" data-dismiss="modal"/>
                                </x-slot>
                                
                            </x-adminlte-modal>   
                        </form><!-- Final do Modal de Liberação de Desconto da TMO -->
                    @endif

                </x-adminlte-card><!-- Fechamento do bloco Inclusão/Manutenção do serviço na requisição da OS ********** -->
                @endif

                <!-- ********** Bloco PREVISAO_ENTREGA da OS ********** -->
                @if($glo_os_estagioAPP == "PREVISAO_ENTREGA")
                <x-adminlte-card title="Previsão de Entrega da Ordem de Serviço" theme="navy" theme-mode="outline" collapsible maximizable>

                    <x-adminlte-card title="Informações da Previsão de Entrega" theme="navy">
                        <form method="post" action="{{ route('painelOS.atualizaPrevEntrega', ['empresa'=> $glo_os_empresa,'numOS'=> $glo_os_nos, 'cliente' => $glo_os_cliente]) }}" id="quickForm-upd-prev-entrega" novalidate="novalidate">
                            @csrf 
                            @method('post')
                            <div class="row">
                                <!-- Grupo do serviço -->
                                <x-adminlte-input name="qtdHoraOS" label="Duração Prevista" type="text" value="{{$glo_os_dadosOS[0]->os_qtd_hr}}" placeholder="0.00" fgroup-class="col-md-4"/>

                                @php
                                    $config = [
                                        "singleDatePicker" => true,
                                        "showDropdowns" => true,
                                        "startDate" => "js:moment()",
                                        "minYear" => 1900,
                                        "maxYear" => "js:parseInt(moment().format('YYYY'),10)",
                                        "timePicker" => false,
                                        "timePicker24Hour" => false,
                                        "timePickerSeconds" => false,
                                        "cancelButtonClasses" => "btn-danger",
                                        "locale" => ["format" => "DD/MM/YYYY"],
                                    ];
                                    if(!empty($glo_os_dadosOS[0]->os_dpe)){
                                        $dataPrevEnt = date('d/m/Y', strtotime($glo_os_dadosOS[0]->os_dpe));
                                    }else{
                                        $dataPrevEnt = '';
                                    }
                                @endphp
                                <!-- Data da Previsão de Entrega -->
                                <x-adminlte-date-range name="dataPrevEnt" label="Data da Previsão de Entrega" :config="$config" placeholder="Formato dia/mês/ano" fgroup-class="col-md-4">
                                    <x-slot name="appendSlot">
                                        <div class="input-group-text">
                                            <i class="far fa-lg fa-calendar-alt"></i>
                                        </div>
                                    </x-slot>
                                </x-adminlte-date-range>
                                @push('js')<script>$(() => $("#dataPrevEnt").val('{{ $dataPrevEnt }}'))</script>@endpush
                                
                                @php
                                    if(!empty($glo_os_dadosOS[0]->os_dpe)){
                                        $startDate = substr($glo_os_dadosOS[0]->os_hpe,0,2).':'.substr($glo_os_dadosOS[0]->os_hpe,2,2);
                                    }else{
                                        $startDate = 'js:moment()';
                                    }
                                    $config = [
                                        "singleDatePicker" => true,
                                        "showDropdowns" => true,
                                        "startDate" => $startDate,
                                        "minYear" => 2000,
                                        "maxYear" => "js:parseInt(moment().format('YYYY'),10)",
                                        "timePicker" => true,
                                        "timePicker24Hour" => true,
                                        "timePickerSeconds" => false,
                                        "cancelButtonClasses" => "btn-danger",
                                        "locale" => ["format" => "HH:mm"],
                                    ];
                                    if(!empty($glo_os_dadosOS[0]->os_dpe)){
                                        $horaPrevEnt = substr($glo_os_dadosOS[0]->os_hpe,0,2).':'.substr($glo_os_dadosOS[0]->os_hpe,2,2);
                                    }else{
                                        $horaPrevEnt = '';
                                    }
                                @endphp
                                <x-adminlte-date-range name="horaPrevEnt" label="Hora da Previsão de Entrega" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-4">
                                    <x-slot name="appendSlot">
                                        <div class="input-group-text">
                                            <i class="far fa-lg fa-clock"></i>
                                        </div>
                                    </x-slot>
                                </x-adminlte-date-range>
                                @push('js')<script>$(() => $("#horaPrevEnt").val('{{ $horaPrevEnt }}'))</script>@endpush
                            </div>
                            <div class="row">
                                <!-- Gera novo orçamento -->
                                <x-adminlte-select name="clienteAguardaTermino" label="Cliente Aguarda Final do Serviço" fgroup-class="col-md-6">
                                    <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" selected="{{$glo_os_dadosOS[0]->os_cli_agr}}"/>
                                </x-adminlte-select>
                                
                                <!-- Gera novo orçamento -->
                                <x-adminlte-select name="avisaClienteTermino" label="Avisa Cliente o Final do Serviço" fgroup-class="col-md-6">
                                    <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" selected="{{$glo_os_dadosOS[0]->os_cli_avs}}"/>
                                </x-adminlte-select>
                            </div>

                            <x-adminlte-button class="btn_hide_atualizar_previsao_entrega" type="submit" label="Gerar Orçamento" value="Gerar Orçamento" theme="info"/>
                        </form>
                    </x-adminlte-card>

                </x-adminlte-card>
                @endif
                <!-- Fechamento do bloco PREVISAO_ENTREGA da OS -->

                <!-- ********** Bloco ORCAMENTO_OS da OS ********** -->
                @if($glo_os_estagioAPP == "ORCAMENTO_OS")
                <x-adminlte-card title="Orçamento da Ordem de Serviço" theme="navy" theme-mode="outline" collapsible maximizable>
                    @php 
                        if($glo_os_dadosOS[0]->os_num_orc != 0){
                            $orcamento = str_pad($glo_os_dadosOS[0]->os_num_orc, 8, "0", STR_PAD_LEFT);
                            $novoOrcamento = 'N';
                            $stsOrc = 'S';
                        }else{
                            $orcamento = "Não Gerado";
                            $novoOrcamento = 'S';
                            $stsOrc = 'N';
                        }
                    @endphp
                    <x-adminlte-card title="Informações do Orçamento" theme="navy">
                        <form method="post" action="{{ route('painelOS.abrirOrcamento', ['empresa'=> $glo_os_empresa,'numOS'=> $glo_os_nos, 'stsOS' => $glo_os_dadosOS[0]->os_sts, 'stsOrc' => $stsOrc, 'cliente' => $glo_os_dadosOS[0]->os_cli, 'dtOS' => $glo_os_dadosOS[0]->os_dha]) }}">
                            @csrf 
                            @method('post')
                            <div class="row">
                                <!-- Orçamento gerado -->
                                <x-adminlte-input name="orcamento" label="Número do Orçamento" type="text" value="{{$orcamento}}" fgroup-class="col-md-6" disabled/>
                                <!-- Gera novo orçamento -->
                                <x-adminlte-select name="novoOrcamento" label="Gerar Novo Orçamento" fgroup-class="col-md-6">
                                    <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" selected="{{$novoOrcamento}}"/>
                                </x-adminlte-select>
                            </div>

                            <x-adminlte-button class="btn_hide_gerar_orcamento" type="submit" label="Gerar Orçamento" value="Gerar Orçamento" theme="info"/>
                        </form>
                    </x-adminlte-card>
                </x-adminlte-card>
                @endif
                <!-- Fechamento do bloco ORCAMENTO_OS da OS -->

                <!-- ********** Bloco ORCAMENTO_OS_IMPRESSAO da OS ********** -->
                @if($glo_os_estagioAPP == "ORCAMENTO_OS_IMPRESSAO")
                <x-adminlte-card title="Impressão do Orçamento da Ordem de Serviço" theme="navy" theme-mode="outline" collapsible maximizable>
                    
                    <x-adminlte-card title="Orçamento da Ordem de Serviço" theme="navy">
                        <div class="col-md-12 tabela-orcamento">
                            <table class="table table-sm table-borderless">
                                <tbody>
                                    <tr>
                                        @php 
                                            $logo_emp = $glo_os_dadosEmpresa[0]->empresa_cnpj."/file/img/".$glo_os_dadosEmpresa[0]->empresa_codigo."_logo.png";
                                        @endphp
                                        <td rowspan="4" style="width: 40%;"><div class="d-flex justify-content-center"><img src="{{ asset($logo_emp) }}" style="max-width: 60%; max-height: 60%; float: right;" /></div></td>
                                        <td rowspan="4">
                                            <p class="text-sm">Endereço
                                                <b class="d-block">{{$glo_os_dadosEmpresaEndereco[0]->endereco_logradouro.', '.$glo_os_dadosEmpresaEndereco[0]->endereco_numero}}</b>
                                                <b class="d-block">{{$glo_os_dadosEmpresaEndereco[0]->endereco_cep}}</b>
                                                <b class="d-block">{{$glo_os_dadosEmpresaEndereco[0]->endereco_bairro}}</b>
                                                <b class="d-block">{{$glo_os_dadosEmpresaEndereco[0]->endereco_cidade.' - '.$glo_os_dadosEmpresaEndereco[0]->endereco_uf}}</b>
                                            </p>
                                            @php 
                                                if(!empty($glo_os_dadosEmpresa[0]->empresa_tel_celular)){
                                                    $telCelular = Helper::mascaraTelCelular($glo_os_dadosEmpresa[0]->empresa_tel_celular);
                                                }else{
                                                    $telCelular = "Não Cadastrado";
                                                }

                                                if(!empty($glo_os_dadosEmpresa[0]->empresa_tel_comercial)){
                                                    $telComercial = Helper::mascaraTelComercial($glo_os_dadosEmpresa[0]->empresa_tel_comercial);
                                                }else{
                                                    $telComercial = "Não Cadastrado";
                                                }

                                                if(!empty($glo_os_dadosEmpresa[0]->empresa_cnpj)){
                                                    $cnpjEmpresa = Helper::mascaraCNPJ($glo_os_dadosEmpresa[0]->empresa_cnpj);
                                                }else{
                                                    $cnpjEmpresa = "Não Cadastrado";
                                                }

                                                $dataConsultor = DB::table('users')->select('name')->where('usuario_codigo',$glo_os_dadosOS[0]->os_res_abr)->get();
                                                $consultorAbertura = $glo_os_dadosOS[0]->os_res_abr.' - '.$dataConsultor[0]->name;

                                                $dataHoraAbertura = Helper::formataDataHora($glo_os_dadosOS[0]->os_dha);

                                            @endphp
                                            <p class="text-sm">Telefone
                                                <b class="d-block">{{$telCelular.' / '.$telComercial}}</b>
                                            </p>
                                            <p class="text-sm">Email
                                                <b class="d-block">{{$glo_os_dadosEmpresa[0]->empresa_email}}</b>
                                            </p>
                                        </td>
                                        <td rowspan="4">
                                            <p class="text-sm">Empresa
                                                <b class="d-block">{{$glo_os_dadosEmpresa[0]->empresa_codigo.' - '.$glo_os_dadosEmpresa[0]->empresa_nome}}</b>
                                            </p>
                                            <p class="text-sm">CNPJ
                                                <b class="d-block">{{$cnpjEmpresa}}</b>
                                            </p>
                                            <p class="text-sm">Inscrição Estadual
                                                <b class="d-block">{{$glo_os_dadosEmpresa[0]->empresa_insc_estadual}}</b>
                                            </p>
                                            <p class="text-sm">Consultor Técnico
                                                <b class="d-block">{{$consultorAbertura}}</b>
                                            </p>
                                            <p class="text-sm">Data e Hora da Abertura da OS
                                                <b class="d-block">{{$dataHoraAbertura}}</b>
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table> 
                            <table class="table table-sm table-bordered tabela-orc-pre">
                                <tbody>
                                    <tr>
                                    @php 
                                         
                                        if(!empty($glo_os_dadosOS[0]->os_dpe)){
                                            $dataPreEnt = Helper::formataData($glo_os_dadosOS[0]->os_dpe);
                                        }else{
                                            $dataPreEnt = '';
                                        }
                                        
                                        if(!empty($glo_os_dadosOS[0]->os_hpe)){
                                            $horaPreEnt = Helper::formataHoraMinuto($glo_os_dadosOS[0]->os_hpe);
                                        }else{
                                            $horaPreEnt = '';
                                        }
                                        
                                        $dataOrcamento = Helper::formataData($glo_os_dadosOS[0]->os_dt_orc);
                                    @endphp
                                        <td>
                                            <p class="text-sm">Número da OS
                                                <b class="d-block">{{str_pad($glo_os_dadosOS[0]->os_nos, 8, "0", STR_PAD_LEFT)}}</b>
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-sm">Data e Hora da Previsão de Entrega
                                                <b class="d-block">{{$dataPreEnt.' '.$horaPreEnt}}</b>
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-sm">Número do Orçamento
                                                <b class="d-block">{{str_pad($glo_os_dadosOS[0]->os_num_orc, 8, "0", STR_PAD_LEFT)}}</b>
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-sm">Data do Orçamento
                                                <b class="d-block">{{$dataOrcamento}}</b>
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table> 
                            <table class="table table-sm table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col" colspan="3">dados do cliente</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <p class="text-sm">Cliente
                                                <b class="d-block">{{$glo_os_dadosCliente[0]->cliente_codigo.' - '.$glo_os_dadosCliente[0]->cliente_nome}}</b>
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-sm">CPF / CNPJ
                                                <b class="d-block">@if($glo_os_dadosCliente[0]->cliente_tipo_pessoa == 'J') 
                                                                        {{Helper::mascaraCNPJ($glo_os_dadosCliente[0]->cliente_cpf_cnpj)}} 
                                                                    @else 
                                                                    {{Helper::mascaraCPF($glo_os_dadosCliente[0]->cliente_cpf_cnpj)}} 
                                                                    @endif</b>
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-sm">RG
                                                <b class="d-block">@if($glo_os_dadosCliente[0]->cliente_tipo_pessoa == 'F' && !empty($glo_os_dadosCliente[0]->cliente_rg)) 
                                                                        {{Helper::mascaraRG($glo_os_dadosCliente[0]->cliente_rg)}} 
                                                                    @endif</b>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p class="text-sm">Endereço
                                                <b class="d-block">{{$glo_os_dadosClienteEndereco[0]->endereco_logradouro.', '.$glo_os_dadosClienteEndereco[0]->endereco_numero}}</b>
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-sm">Complemento
                                                <b class="d-block">{{$glo_os_dadosClienteEndereco[0]->endereco_complemento}}</b>
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-sm">Bairro
                                                <b class="d-block">{{$glo_os_dadosClienteEndereco[0]->endereco_bairro}}</b>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p class="text-sm">CEP
                                                <b class="d-block">{{$glo_os_dadosClienteEndereco[0]->endereco_cep}}</b>
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
                                    </tr>
                                    <tr>
                                        <td>
                                            <p class="text-sm">Telefone Celular
                                                <b class="d-block">@if(!empty($glo_os_dadosCliente[0]->cliente_tel_celular)) {{Helper::mascaraTelCelular($glo_os_dadosCliente[0]->cliente_tel_celular)}} @endif</b>
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-sm">Telefone Comercial
                                                <b class="d-block">@if(!empty($glo_os_dadosCliente[0]->cliente_tel_comercial)) {{Helper::mascaraTelComercial($glo_os_dadosCliente[0]->cliente_tel_comercial)}} @endif</b>
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-sm">Telefone Residencial
                                                <b class="d-block">@if(!empty($glo_os_dadosCliente[0]->cliente_tel_residencial)) {{Helper::mascaraTelResidencial($glo_os_dadosCliente[0]->cliente_tel_residencial)}} @endif</b>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <p class="text-sm">Email
                                                <b class="d-block">{{$glo_os_dadosCliente[0]->cliente_email}}</b>
                                            </p>
                                        </td>
                                    </tr>
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col" colspan="3">Solicitações de Servicos</th>
                                        </tr>
                                    </thead>
                                    <tr>
                                        <td colspan="3">
                                            <table class="table table-sm table-bordered" style="margin: 0px;">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th scope="col">Item</th>
                                                        <th scope="col">Etapa</th>
                                                        <th scope="col">Descrição</th>
                                                        <th scope="col">Área</th>
                                                        <th scope="col">Setor</th>
                                                        <th scope="col">Tipo de Serviço</th>
                                                        <th scope="col">Valor</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($glo_os_dadosRequisicoes as $requisicao)
                                                    <tr>
                                                        @php 
                                                            $dataEtapa = DB::table('lancamento_srv_etapa_atendimentos')->select('eat_nom')->where('eat_emp',$glo_os_empresa)->where('eat_cod',$requisicao->req_eat)->get();
                                                            $etapa = $requisicao->req_eat.' - '.$dataEtapa[0]->eat_nom;

                                                            $dataArea = DB::table('parametros_sistema_areas')->select('area_desc')->where('area_codigo',$requisicao->req_are)->get();
                                                            $area = $requisicao->req_are.' - '.$dataArea[0]->area_desc;

                                                            $dataSet = DB::table('parametros_srv_setores')->select('setor_desc')->where('setor_empresa',$glo_os_empresa)->where('setor_codigo',$requisicao->req_set)->where('setor_area',$requisicao->req_are)->get();
                                                            $setor = $requisicao->req_set.' - '.$dataSet[0]->setor_desc;

                                                            $dataTOS = DB::table('lancamento_srv_tipo_servicos')->select('tipsrv_nom')->where('tipsrv_emp', $glo_os_empresa)->where('tipsrv_are', $requisicao->req_are)->where('tipsrv_cod', $requisicao->req_tos)->get();
                                                            $tos = $requisicao->req_tos.' - '.$dataTOS[0]->tipsrv_nom;
                                                        @endphp
                                                        <td>{{$requisicao->req_seq}}</td>
                                                        <td>{{$etapa}}</td>
                                                        <td>{{$requisicao->req_dsc}}</td>
                                                        <td>{{$area}}</td>
                                                        <td>{{$setor}}</td>
                                                        <td>{{$tos}}</td>
                                                        <td>{{Helper::formataValorMonetario($requisicao->req_vlr)}}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col" colspan="3">Serviços de Mão de Obra</th>
                                        </tr>
                                    </thead>
                                    <tr>
                                        <td colspan="3">
                                            <table class="table table-sm table-bordered" style="margin: 0px;">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th scope="col">Requisição</th>
                                                        <th scope="col">Item</th>
                                                        <th scope="col">Código Serviço</th>
                                                        <th scope="col">Descrição</th>
                                                        <th scope="col">Tipo</th>
                                                        <th scope="col">Qtd. Horas</th>
                                                        <th scope="col">Val. Hora</th>
                                                        <th scope="col">Valor Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($glo_os_dadosServicos as $servico)
                                                    <tr>
                                                        @php 
                                                            if($servico->srv_ths == 'F'){
                                                                $tipoHR = 'Valor Fixo';
                                                            }elseif($servico->srv_ths == 'P'){
                                                                $tipoHR = 'Hora Padrão';
                                                            }elseif($servico->srv_ths == 'T'){
                                                                $tipoHR = 'Terceiros';
                                                            }elseif($servico->srv_ths == 'R'){
                                                                $tipoHR = 'Hora Real';
                                                            }else{
                                                                $tipoHR = 'Hora Informada';
                                                            }
                                                        @endphp
                                                        <td>{{$servico->srv_req}}</td>
                                                        <td>{{$servico->srv_seq}}</td>
                                                        <td>{{$servico->srv_tmo}}</td>
                                                        <td>{{$servico->srv_dsc}}</td>
                                                        <td>{{$tipoHR}}</td>
                                                        <td>{{$servico->srv_qhr}}</td>
                                                        <td>{{Helper::formataValorMonetario($servico->srv_vhr)}}</td>
                                                        <td>{{Helper::formataValorMonetario($servico->srv_vts)}}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table> 
                            <table class="table table-sm table-bordered tabela-totais">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col" colspan="4">Totais da OS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <p class="text-sm">Valor Total Bruto
                                                <b class="d-block">{{Helper::formataValorMonetario($glo_os_dadosOS[0]->os_vlr)}}</b>
                                            </p>
                                        </td>
                                        <td>
                                            @php
                                                $descontoReq = 0;
                                                foreach($glo_os_dadosRequisicoes as $requisicao){
                                                    $descontoReq = $descontoReq + $requisicao->req_val_des;
                                                }
                                                $descontoTot = $descontoReq + $glo_os_dadosOS[0]->os_val_des;
                                            @endphp
                                            <p class="text-sm">Descontos
                                                <b class="d-block">{{Helper::formataValorMonetario($descontoTot)}}</b>
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-sm">Valor Total de Serviços
                                                <b class="d-block">{{Helper::formataValorMonetario($glo_os_dadosOS[0]->os_vls)}}</b>
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-sm">Valor Total Liquido
                                                <b class="d-block">{{Helper::formataValorMonetario($glo_os_dadosOS[0]->os_vlt)}}</b>
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table> 
                            <table class="table table-sm table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Informações Adicionais</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>O cliente reconhece ter conhecimento prévio das condições gerais da realização dos serviços solicitados e autoriza a realização dos serviços constantes desta Ordem de Serviço / Orçamento e a Emissão da respectiva Nota Fiscal para pagamento.</td>
                                    </tr>
                                </tbody>
                            </table> 
                            <div class="row" style="margin-top: 75px;">
                                <div class="col-md-2"></div>
                                <div class="col-md-8 assinatura-consultor">
                                    <p class="text-sm">Assinatura Consultor</p>
                                </div>
                                <div class="col-md-2"></div>
                            </div>
                            <div class="row assinatura-cliente">
                                <div class="col-md-2">
                        
                                </div>
                                <div class="col-md-8 assinatura-meio">
                                    <p class="text-sm">Assinatura do Cliente ou Responsável Autorizado</p>
                                </div>
                                <div class="col-md-2">
                                    
                                </div>
                            </div>
                        </div>
                    </x-adminlte-card>

                </x-adminlte-card>
                @endif
                <!-- Fechamento do bloco ORCAMENTO_OS_IMPRESSAO da OS -->

                <!-- ********** Bloco TOTAIS_OS da tarefa da Abertura de OS ********** -->
                @if($glo_os_estagioAPP == "TOTAIS_OS")
                <x-adminlte-card title="Total da Ordem de Serviço" theme="navy" theme-mode="outline" collapsible maximizable>
                    
                    <div class="row">
                        <table class="table tabela-dados-req">
                            <tbody>
                                <tr>
                                    <th colspan="4">Requisições da Ordem de Serviço</th>
                                </tr>
                            </tbody>
                        </table> 
                    </div>
                    <!-- Linha 1 - lista dos serviços -->    
                    <div class="row">
                        @php
                            //Monta dados da tabela do bloco
                            $heads = [
                                'Requisição',
                                'Descrição',
                                'Tipo de Serviço',
                                'Área',
                                'setor',
                                'Valor Total',
                            ];
            
                            $config = [
                                'searching' => false,
                                'lengthChange' => false,
                                'paging' => false, 
                                'info' => false,
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
                                'columns' => [null, null, null, null, null, null],
                            ];
                        @endphp
                        <x-adminlte-datatable id="table3" :heads="$heads" :config="$config" theme="light" striped hoverable>
                            @foreach($glo_os_dadosRequisicoes as $requisicao)
                                @php 
                                    $data_set = DB::table('parametros_srv_setores')->where('setor_empresa', $glo_os_empresa)->where('setor_codigo', $requisicao->req_set)->where('setor_area', $requisicao->req_are)->get();
                                    $data_tos = DB::table('lancamento_srv_tipo_servicos')->where('tipsrv_emp', $glo_os_empresa)->where('tipsrv_cod', $requisicao->req_tos)->get();
                                    $data_are = DB::table('parametros_sistema_areas')->where('area_codigo', $requisicao->req_are)->get();

                                    $setor = $requisicao->req_set.' - '.$data_set[0]->setor_desc;
                                    $tipo_servico = $requisicao->req_tos.' - '.$data_tos[0]->tipsrv_nom;
                                    $area = $requisicao->req_are.' - '.$data_are[0]->area_desc;
                                @endphp
                                <tr>
                                    <td>{{$requisicao->req_seq}}</td>
                                    <td>{{$requisicao->req_dsc}}</td>
                                    <td>{{$tipo_servico}}</td>
                                    <td>{{$area}}</td>  
                                    <td>{{$setor}}</td>       
                                    <td>{{Helper::formataValorMonetario($requisicao->req_vlt)}}</td>      
                                </tr>
                            @endforeach
                        </x-adminlte-datatable>
                    </div>
                    <div class="row">  
                    <div class="col-md-6">       
                        <table class="table tabela-dados-req">
                            <tbody>
                                <tr>
                                    <th colspan=2>Valores Totais da Ordem de Serviço</th>
                                </tr>
                                <tr>
                                    <td><strong>Valor Total Bruto da OS:</strong></td>
                                    <td style="text-align: right">R${{Helper::formataValorMonetario($glo_os_dadosOS[0]->os_vlr)}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Valor Total de Serviços:</strong></td>
                                    <td style="text-align: right">R${{Helper::formataValorMonetario($glo_os_dadosOS[0]->os_vls)}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Valor Total de Descontos de Serviços:</strong></td>
                                    <td style="text-align: right">R${{Helper::formataValorMonetario($glo_os_dadosOS[0]->os_val_des_srv)}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Valor Total Líquido das Requisições:</strong></td>
                                    <td style="text-align: right">R${{Helper::formataValorMonetario($glo_os_dadosOS[0]->os_vls - $glo_os_dadosOS[0]->os_val_des_srv)}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Valor de Desconto da OS:</strong></td>
                                    <td style="text-align: right">R${{Helper::formataValorMonetario($glo_os_dadosOS[0]->os_val_des)}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Valor Total à Pagar:</strong></td>
                                    <td style="text-align: right">R${{Helper::formataValorMonetario($glo_os_dadosOS[0]->os_vlt)}}</td>
                                </tr>
                            </tbody>
                        </table> 
                        </div>
                        <div class="col-md-6"> 
                        <table class="table tabela-dados-req">
                            <tbody>
                                <tr>
                                    <th colspan=2>Contato / Observações da Ordem de Serviço</th>
                                </tr>
                                <tr>
                                    <td><nobr><strong>Cliente da OS:</strong></nobr></td>
                                    <td style="text-align: center">{{ $glo_os_cliente }} - {{$glo_os_dadosCliente[0]->cliente_nome}}</td>
                                </tr>
                                <tr>
                                    @php 
                                        $nom_cli_fat = DB::table('cadastro_clientes')->select('cliente_nome')->where('cliente_codigo', $glo_os_dadosOS[0]->os_cli_fatura)->get();
                                    @endphp
                                    <td><nobr><strong>Faturar OS Para:</strong></nobr></td>
                                    <td style="text-align: center">{{$glo_os_dadosOS[0]->os_cli_fatura}} - {{$nom_cli_fat[0]->cliente_nome}}</td>
                                </tr>
                                <tr>
                                    <td><nobr><strong>Telefone Residencial:</strong></nobr></td>
                                    <td style="text-align: center">@if(!empty($glo_os_dadosCliente[0]->cliente_tel_residencial)){{Helper::mascaraTelResidencial($glo_os_dadosCliente[0]->cliente_tel_residencial)}}@else Não Informado @endif</td>
                                </tr>
                                <tr>
                                    <td><nobr><strong>Telefone Celular:</strong></nobr></td>
                                    <td style="text-align: center">@if(!empty($glo_os_dadosCliente[0]->cliente_tel_celular)){{Helper::mascaraTelCelular($glo_os_dadosCliente[0]->cliente_tel_celular)}}@else Não Informado @endif</td>
                                </tr>
                                <tr>
                                    <td><nobr><strong>Telefone Comercial:</strong></nobr></td>
                                    <td style="text-align: center">@if(!empty($glo_os_dadosCliente[0]->cliente_tel_comercial)){{Helper::mascaraTelComercial($glo_os_dadosCliente[0]->cliente_tel_comercial)}}@else Não Informado @endif</td>
                                </tr>
                                <tr>
                                    <td><nobr><strong>Email:</strong></nobr></td>
                                    <td style="text-align: center">@if(!empty($glo_os_dadosCliente[0]->cliente_email)){{$glo_os_dadosCliente[0]->cliente_email}}@else Não Informado @endif</td>
                                </tr>
                                <tr>
                                    <td><nobr><strong>Observações:</strong></nobr></td>
                                    <td style="text-align: center">{{$glo_os_dadosOS[0]->os_observacao}}</td>
                                </tr>
                            </tbody>
                        </table> 
                        </div>
                    </div>
                </x-adminlte-card>
                        
                <!-- Modal do botão de observações -->
                <form method="post" action="{{ route('lancamentoOS.atualizaObservacao', ['empresa'=> $glo_os_empresa,'numOS'=> $glo_os_nos]) }}" id="formulario-observacao-os" novalidate="novalidate">
                    @csrf 
                    @method('post')
                    <x-adminlte-modal id="modalObservacaoOS" title="Observações da OS" size="xl" theme="navy" icon="" v-centered scrollable>
                        
                        <div class="row" style="height:auto;">
                            <div class="post col-md-12">
                                <div class="row">
                                    <!-- Observação da tarefa -->
                                    <x-adminlte-textarea name="observacaoOS" label="Observação" rows=3 label-class="text-dark" igroup-size="sm" placeholder="Informe a Observação da OS..." fgroup-class="col-md-12">
                                        <x-slot name="prependSlot">
                                            <div class="input-group-text bg-navy">
                                                <i class="fas fa-lg fa-file-alt text-white"></i>
                                            </div>
                                        </x-slot>
                                    </x-adminlte-textarea>
                                </div>
                            </div>
                        </div>
                        <!-- Criação dos botões do Modal -->  
                        <x-slot name="footerSlot">
                            <x-adminlte-button class="mr-auto" theme="info" label="Salvar" icon="fa-solid fa-share-from-square" type="submit"/>
                            <x-adminlte-button theme="info" label="Voltar" data-dismiss="modal"/>
                        </x-slot>
                        
                    </x-adminlte-modal>   
                </form>

                <!-- Modal do botão de troca cliente da fatura -->
                <form method="post" action="{{ route('lancamentoOS.atualizaCliFatura', ['empresa'=> $glo_os_empresa,'numOS'=> $glo_os_nos]) }}" id="formulario-troca-cli-fat" novalidate="novalidate">
                    @csrf 
                    @method('post')
                    <x-adminlte-modal id="modalTrocaCliFatOS" title="Trocar Cliente do Faturamento da OS" size="xl" theme="navy" icon="" v-centered scrollable>
                        
                        <div class="row" style="height:auto;">
                            <div class="post col-md-12">
                                <div class="row">
                                    @php
                                        //Faz o lookup do campo de novo cliente da fatura 
                                        $data_cli_fat = DB::table('cadastro_clientes')->select('cliente_codigo', 'cliente_nome')->orderBy('cliente_codigo', 'asc')->get();
                                        $html = '<datalist id="clientesFatura">';
                                        foreach($data_cli_fat as $cliente){
                                            $html .= '<option value="'.$cliente->cliente_codigo.' - '.$cliente->cliente_nome.'">'.$cliente->cliente_codigo.' - '.$cliente->cliente_nome.'</option>';
                                        }
                                        $html .='</datalist>';
                                        //Echo adiciona o html ao campo dos clientes
                                        echo $html;
                                    @endphp
                                    <!-- Prestador responsavel da TMO -->
                                    <x-adminlte-input name="cliFatura" label="Novo Cliente da Fatura" type="search" list="clientesFatura" fgroup-class="col-md-12"/>
                                </div>
                            </div>
                        </div>
                        <!-- Criação dos botões do Modal -->  
                        <x-slot name="footerSlot">
                            <x-adminlte-button class="mr-auto" theme="info" label="Salvar" icon="fa-solid fa-share-from-square" type="submit"/>
                            <x-adminlte-button theme="info" label="Voltar" data-dismiss="modal"/>
                        </x-slot>
                        
                    </x-adminlte-modal>   
                </form>

                <!-- Modal do botão de desconto da os -->
                <form method="post" action="{{ route('lancamentoOS.atualizaDescontoOS', ['empresa'=> $glo_os_empresa,'numOS'=> $glo_os_nos]) }}" id="formulario-desconto-os" novalidate="novalidate">
                    @csrf 
                    @method('post')
                    <x-adminlte-modal id="modalDescontoOS" title="Desconto da OS" size="xl" theme="navy" icon="" v-centered scrollable>
                        
                        <div class="row" style="height:auto;">
                            <div class="post col-md-12">
                                <table class="table table-sm table-borderless">
                                    <thead class="thead-default">
                                        <tr>
                                            <th scope="col">Valor da OS</th>
                                            <th scope="col">% Desconto</th>
                                            <th scope="col">Valor Desconto</th>
                                            <th scope="col">Valor Total da OS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <!-- Grupo do serviço -->
                                                <x-adminlte-input name="valBrutoOS" type="text" value="{{Helper::formataValorMonetario($glo_os_dadosOS[0]->os_vlt)}}" placeholder="0,00" fgroup-class="col-md-12" igroup-size="sm" disabled/>
                                            </td>
                                            <td>
                                                <!-- Grupo do serviço -->
                                                <x-adminlte-input name="perDescontoOS" type="text" value="{{Helper::formataValorMonetario($glo_os_dadosOS[0]->os_per_des)}}" placeholder="0,00" fgroup-class="col-md-12" igroup-size="sm"/>
                                            </td>
                                            <td>
                                                <!-- Grupo do serviço -->
                                                <x-adminlte-input name="valDescontoOS" type="text" value="{{Helper::formataValorMonetario($glo_os_dadosOS[0]->os_val_des)}}" placeholder="0,00" fgroup-class="col-md-12" igroup-size="sm"/>
                                            </td>
                                            <td>
                                                <!-- Grupo do serviço -->
                                                <x-adminlte-input name="valLiquidoOS" type="text" value="{{Helper::formataValorMonetario($glo_os_dadosOS[0]->os_vlt)}}" placeholder="0,00" fgroup-class="col-md-12" igroup-size="sm" disabled/>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Criação dos botões do Modal -->  
                        <x-slot name="footerSlot">
                            <x-adminlte-button class="mr-auto" theme="info" label="Salvar" icon="fa-solid fa-share-from-square" type="submit"/>
                            <x-adminlte-button theme="info" label="Voltar" data-dismiss="modal"/>
                        </x-slot>
                        
                    </x-adminlte-modal>   
                </form>
                @endif
                <!-- Fechamento do bloco TOTAIS_OS da tarefa da Abertura de OS -->

            </div><!-- Fechamento do Bloco do Lado Direito do Painel Principal da Abertura de OS -->
            
        </div><!-- Fechamento do Posicionamento os blocos do lado esquerdo e direito na mesma linha -->

        <!-- ********** Rodapé com os botões do Painel Principal da Abertura de OS ********** -->
        <x-slot name="footerSlot">
            @php
                if($glo_os_estagioAPP == 'CONSULTA_REQUISICAO'){

                    if(!empty($glo_os_dadosRequisicoes[0]['req_set'])){

                        $setor_req = $glo_os_dadosRequisicoes[0]['req_set'];
                        $requisicao = $glo_os_dadosRequisicoes[0]['req_seq'];
                        $area_req = $glo_os_dadosRequisicoes[0]['req_are'];
                    }else{
                        $setor_req = ' ';
                        $requisicao = ' ';
                        $area_req  = ' ';
                    }
                }else{
                    $setor_req = ' ';
                    $requisicao = ' ';
                    $area_req  = ' ';
                }
            @endphp
            <x-adminlte-button class="btn_geral" type="button" onclick="window.location='{{ route('situacaoOS.carregaOS', ['empresa' => $glo_os_empresa, 'cliente' => $glo_os_cliente, 'nos' => $glo_os_nos, 'estagioAPP' => 'PRINCIPAL']) }}'" label="Geral" theme="info" icon=""/>
            <x-adminlte-button class="btn_previsao_entrega" type="button" onclick="window.location='{{ route('painelOS.previsaoEntregaOS', ['empresa' => $glo_os_empresa, 'numOS' => $glo_os_nos]) }}'" label="Previsão de Entrega" theme="info" icon="fa-solid fa-truck"/>
            <x-adminlte-button class="btn_total_os" type="button" onclick="window.location='{{ route('painelOS.totalOS', ['empresa' => $glo_os_empresa, 'numOS' => $glo_os_nos]) }}'" label="Total OS" theme="info" icon=""/>

            <x-adminlte-button class="btn_atualizar_previsao_entrega" type="button" onclick="document.querySelector('.btn_hide_atualizar_previsao_entrega').click()" label="Salvar" theme="info" icon="fa-solid fa-share-from-square"/>
            
            <x-adminlte-button class="btn_gerar_orcamento" type="button" onclick="document.querySelector('.btn_hide_gerar_orcamento').click()" label="Gerar Orçamento" theme="info" icon=""/>

            <x-adminlte-button class="btn_observacao" type="button" data-toggle="modal" data-target="#modalObservacaoOS" label="Observações" theme="info" icon="fa-regular fa-eye"/>
            <x-adminlte-button class="btn_troca_cliente" type="button" data-toggle="modal" data-target="#modalTrocaCliFatOS" label="Troca Cliente Fatura" theme="info" icon="fa-solid fa-right-left"/>
            <x-adminlte-button class="btn_desconto_os" type="button" data-toggle="modal" data-target="#modalDescontoOS" label="Desconto" theme="info" icon="fa-solid fa-tag"/>
            <x-adminlte-button class="btn_orcamento" type="button" onclick="window.location='{{ route('painelOS.orcamentoOS', ['empresa' => $glo_os_empresa, 'numOS' => $glo_os_nos]) }}'" label="Orçamento" theme="info" icon="fa-solid fa-file-invoice-dollar"/>
            <x-adminlte-button class="btn_encerra_os" type="button" onclick="window.location='{{ route('painelOS.encerraOS', ['empresa' => $glo_os_empresa, 'numOS' => $glo_os_nos]) }}'" label="Encerrar OS" theme="info" icon="fa-solid fa-handshake"/>

            <x-adminlte-button class="btn_orcamentoPDF" type="button" onclick="window.open('{{ route('painelOS.orcamentoPDF', ['empresa' => $glo_os_empresa, 'numOS' => $glo_os_nos]) }}');" label="Gerar PDF" theme="info" icon="fa-solid fa-file-pdf"/>

            <x-adminlte-button class="btn_incluir_requisicao" type="button" onclick="document.querySelector('.btn_hide_incluir_requisicao').click()" label="Incluir Requisição" theme="info" icon="fa-solid fa-plus"/>

            <x-adminlte-button class="btn_finalizar_requisicao" type="button" onclick="document.querySelector('.btn_hide_finalizar_requisicao').click()" label="Finalizar Requisição" theme="info" icon="fa-solid fa-lock"/>
            <x-adminlte-button class="btn_excluir_requisicao" type="button" onclick="document.querySelector('.btn_hide_excluir_requisicao').click()" label="Excluir Requisição" theme="info" icon="fa-solid fa-trash"/>
            <x-adminlte-button class="btn_reabrir_requisicao" type="button" onclick="document.querySelector('.btn_hide_reabrir_requisicao').click()" label="Reabrir Requisição" theme="info" icon=""/>
            <x-adminlte-button class="btn_iniciar_servicos" type="button" onclick="document.querySelector('.btn_hide_iniciar_servicos').click()" label="Iniciar Serviços" theme="info" icon=""/>
            <x-adminlte-button class="btn_finalizar_servicos" type="button" onclick="document.querySelector('.btn_hide_finalizar_servicos').click()" label="Finalizar Serviços" theme="info" icon=""/>
            <x-adminlte-button class="btn_novo_servico" type="button" onclick="window.location='{{ route('painelOS.abrirServicoRequisicao', ['empresa' => $glo_os_empresa, 'area' => $area_req, 'setor' => $setor_req, 'estagioAPP' => 'INCLUSAO_SERVICO', 'subEstagioRequisicao' => 'SELECAO_TMO']) }}'" label="Novo Serviço" theme="info" icon="fa-solid fa-plus"/>

            <x-adminlte-button class="btn_incluir_tmo" type="button" onclick="document.querySelector('.btn_hide_incluir_tmo').click()" label="Incluir" theme="info" icon="fa-solid fa-plus"/>
            <x-adminlte-button class="btn_atualizar_tmo" type="button" onclick="document.querySelector('.btn_hide_atualizar_tmo').click()" label="Salvar" theme="info" icon="fa-solid fa-share-from-square"/>
            <x-adminlte-button class="btn_liberar_desconto_tmo" type="button" data-toggle="modal" data-target="#modalLibDescTMO" label="Liberar Desconto" theme="info" icon="fa-solid fa-unlock"/>
            <x-adminlte-button class="btn_suspender_tmo" type="button" onclick="document.querySelector('.btn_hide_suspender_tmo').click()" label="Suspender TMO" theme="info" icon="fa-solid fa-triangle-exclamation"/>
            <x-adminlte-button class="btn_cancelar_tmo" type="button" onclick="document.querySelector('.btn_hide_cancelar_tmo').click()" label="Cancelar TMO" theme="info" icon="fa-solid fa-ban"/>
            <x-adminlte-button class="btn_reabrir_tmo" type="button" onclick="document.querySelector('.btn_hide_reabrir_tmo').click()" label="Reabrir TMO" theme="info" icon="fa-solid fa-folder-open"/>
            <x-adminlte-button class="btn_excluir_tmo" type="button" onclick="document.querySelector('.btn_hide_excluir_tmo').click()" label="Excluir" theme="info" icon="fa-solid fa-trash"/>

        </x-slot>
    </x-adminlte-card><!-- Fechamento do Painel Principal da Abertura de OS -->

</div>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.Sweetalert2', true)
@section('plugins.jqueryValidation', true)
@section('plugins.DateRangePicker', true)
@section('plugins.Select2', true)
@section('plugins.BootstrapSwitch', true)

@section('css')
<style>
    #drSizeSm .calendar-table {
  display: none;
}
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

    .tabela-orcamento th{
        text-align: center;
    }
    .assinatura-cliente{
        text-align: center;
        margin-top: 75px;
    }
    .assinatura-consultor{
        text-align: center;
        border-top: 1px solid black;
    }
    .assinatura-meio{
        border-top: 1px solid black;
    }

    .tabela-orc-pre{
        text-align: center;
    }

    .tabela-totais{
        text-align: center;
    }
</style>
@stop

@section('js')
<script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script> 



<!--
|--------------------------------------------------------------------------
| Eventos Iniciais da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() { 

        /* **************************************** Esconde botões secundarios da app que serão executados pelos botões da barra principal do painel **************************************** */

        //Botão quadro - INCLUSAO_REQUISICAO
        $(".btn_hide_incluir_requisicao").hide();

        //Botão quadro - ORCAMENTO_OS
        $(".btn_hide_gerar_orcamento").hide();

        //Botão quadro - PREVISAO_ENTREGA
        $(".btn_hide_atualizar_previsao_entrega").hide();

        //Botão quadro -  INCLUSAO_SERVICO / MANUTENCAO_SERVICO
        $(".btn_hide_incluir_tmo").hide();
        $(".btn_hide_atualizar_tmo").hide();
        $(".btn_hide_suspender_tmo").hide();
        $(".btn_hide_cancelar_tmo").hide();
        $(".btn_hide_reabrir_tmo").hide();
        $(".btn_hide_excluir_tmo").hide();

        //Botão quadro - CONSULTA_REQUISICAO
        $(".btn_hide_iniciar_servicos").hide();
        $(".btn_hide_finalizar_servicos").hide();
        $(".btn_hide_finalizar_requisicao").hide();
        $(".btn_hide_reabrir_requisicao").hide();
        $(".btn_hide_excluir_requisicao").hide();


        /* **************************************** Ao iniciar a app verifica qual a etapa executada e realiza a exibição dos botões da etapa **************************************** */

        var estagioAPP = {!! json_encode($glo_os_estagioAPP) !!};
        var subEstagioRequisica = {!! json_encode($glo_os_subEstagioRequisicao) !!};
        var statusOS = {!! json_encode($status_os) !!};

        if(estagioAPP == 'PRINCIPAL'){

            $(".btn_incluir_requisicao").hide();
            $(".btn_novo_servico").hide();
            $(".btn_incluir_tmo").hide();
            $(".btn_atualizar_tmo").hide();
            $(".btn_iniciar_servicos").hide();
            $(".btn_finalizar_servicos").hide();
            $(".btn_finalizar_requisicao").hide();
            $(".btn_reabrir_requisicao").hide();
            $(".btn_suspender_tmo").hide();
            $(".btn_cancelar_tmo").hide();
            $(".btn_reabrir_tmo").hide();
            $(".btn_excluir_tmo").hide();
            $(".btn_excluir_requisicao").hide();
            $(".btn_orcamento").hide();
            $(".btn_orcamentoPDF").hide();
            $(".btn_gerar_orcamento").hide();
            $(".btn_atualizar_previsao_entrega").hide();
            $(".btn_desconto_requisicao").hide();
            $(".btn_encerra_os").hide();
            $(".btn_observacao").hide();
            $(".btn_desconto_os").hide();
            $(".btn_troca_cliente").hide();
            $(".btn_liberar_desconto_tmo").hide();  
            
            if(statusOS == 'F' || statusOS == 'C'){
                $(".btn_previsao_entrega").hide();
            }

        }else if(estagioAPP == 'INCLUSAO_REQUISICAO'){

            $(".btn_novo_servico").hide();
            $(".btn_incluir_tmo").hide();
            $(".btn_atualizar_tmo").hide();
            $(".btn_iniciar_servicos").hide();
            $(".btn_finalizar_servicos").hide();
            $(".btn_finalizar_requisicao").hide();
            $(".btn_reabrir_requisicao").hide();
            $(".btn_suspender_tmo").hide();
            $(".btn_cancelar_tmo").hide();
            $(".btn_reabrir_tmo").hide();
            $(".btn_excluir_tmo").hide();
            $(".btn_excluir_requisicao").hide();
            $(".btn_orcamento").hide();
            $(".btn_previsao_entrega").hide();
            $(".btn_orcamentoPDF").hide();
            $(".btn_gerar_orcamento").hide();
            $(".btn_atualizar_previsao_entrega").hide();
            $(".btn_desconto_requisicao").hide();
            $(".btn_total_os").hide();
            $(".btn_encerra_os").hide();
            $(".btn_observacao").hide();
            $(".btn_desconto_os").hide();
            $(".btn_troca_cliente").hide();
            $(".btn_liberar_desconto_tmo").hide(); 

        }else if(estagioAPP == 'CONSULTA_REQUISICAO'){

            $(".btn_incluir_requisicao").hide();
            $(".btn_incluir_tmo").hide();
            $(".btn_atualizar_tmo").hide();
            $(".btn_suspender_tmo").hide();
            $(".btn_cancelar_tmo").hide();
            $(".btn_reabrir_tmo").hide();
            $(".btn_excluir_tmo").hide();
            $(".btn_orcamento").hide();
            $(".btn_previsao_entrega").hide();
            $(".btn_orcamentoPDF").hide();
            $(".btn_gerar_orcamento").hide();
            $(".btn_atualizar_previsao_entrega").hide();
            $(".btn_total_os").hide();
            $(".btn_encerra_os").hide();
            $(".btn_observacao").hide();
            $(".btn_desconto_os").hide();
            $(".btn_troca_cliente").hide();
            $(".btn_liberar_desconto_tmo").hide(); 

            var status_requisicao = {!! json_encode($status_requisicao) !!};
            if(status_requisicao == 'F'){
                $(".btn_finalizar_requisicao").hide();
                $(".btn_iniciar_servicos").hide();
                $(".btn_finalizar_servicos").hide();
                $(".btn_novo_servico").hide();
                $(".btn_excluir_requisicao").hide();
            }else{
                $(".btn_reabrir_requisicao").hide();
            }

            if(statusOS == 'F' || statusOS == 'C'){
                $(".btn_reabrir_requisicao").hide();
            }

        }else if(estagioAPP == 'INCLUSAO_SERVICO'){

            $(".btn_novo_servico").hide();
            $(".btn_incluir_requisicao").hide();
            $(".btn_atualizar_tmo").hide();
            $(".btn_iniciar_servicos").hide();
            $(".btn_finalizar_servicos").hide();
            $(".btn_finalizar_requisicao").hide();
            $(".btn_reabrir_requisicao").hide();
            $(".btn_suspender_tmo").hide();
            $(".btn_cancelar_tmo").hide();
            $(".btn_reabrir_tmo").hide();
            $(".btn_excluir_tmo").hide();
            $(".btn_excluir_requisicao").hide();
            $(".btn_orcamento").hide();
            $(".btn_previsao_entrega").hide();
            $(".btn_orcamentoPDF").hide();
            $(".btn_gerar_orcamento").hide();
            $(".btn_atualizar_previsao_entrega").hide();
            $(".btn_desconto_requisicao").hide();
            $(".btn_total_os").hide();
            $(".btn_encerra_os").hide();
            $(".btn_observacao").hide();
            $(".btn_desconto_os").hide();
            $(".btn_troca_cliente").hide();
            $(".btn_liberar_desconto_tmo").hide();             

            if(subEstagioRequisica != 'TMO_SELECIONADA'){
                $(".btn_incluir_tmo").hide();
            }

        }else if(estagioAPP == 'MANUTENCAO_SERVICO'){

            $(".btn_novo_servico").hide();
            $(".btn_incluir_requisicao").hide();
            $(".btn_incluir_tmo").hide();
            $(".btn_iniciar_servicos").hide();
            $(".btn_finalizar_servicos").hide();
            $(".btn_finalizar_requisicao").hide();
            $(".btn_reabrir_requisicao").hide();
            $(".btn_excluir_requisicao").hide();
            $(".btn_orcamento").hide();
            $(".btn_previsao_entrega").hide();
            $(".btn_orcamentoPDF").hide();
            $(".btn_gerar_orcamento").hide();
            $(".btn_atualizar_previsao_entrega").hide();
            $(".btn_desconto_requisicao").hide();
            $(".btn_total_os").hide();
            $(".btn_encerra_os").hide();
            $(".btn_observacao").hide();
            $(".btn_desconto_os").hide();
            $(".btn_troca_cliente").hide();

            var status_requisicao = {!! json_encode($status_requisicao) !!};
            if(status_requisicao == 'F'){
                $(".btn_atualizar_tmo").hide();
                $(".btn_cancelar_tmo").hide();
                $(".btn_suspender_tmo").hide();
                $(".btn_reabrir_tmo").hide();
                $(".btn_excluir_tmo").hide();
                $(".btn_liberar_desconto_tmo").hide(); 
                
            }else{
                var status_servico = {!! json_encode($status_servico) !!};
                if(status_servico == 'F'){
                    $(".btn_suspender_tmo").hide();
                    $(".btn_liberar_desconto_tmo").hide(); 
                }else if(status_servico == 'S'){
                    $(".btn_suspender_tmo").hide();
                    $(".btn_liberar_desconto_tmo").hide(); 
                }else if(status_servico == 'C'){
                    $(".btn_cancelar_tmo").hide();
                    $(".btn_suspender_tmo").hide();
                    $(".btn_liberar_desconto_tmo").hide(); 
                }else{
                    $(".btn_reabrir_tmo").hide();
                }
            }
        }else if(estagioAPP == 'PREVISAO_ENTREGA'){

            $(".btn_incluir_requisicao").hide();
            $(".btn_novo_servico").hide();
            $(".btn_incluir_tmo").hide();
            $(".btn_atualizar_tmo").hide();
            $(".btn_iniciar_servicos").hide();
            $(".btn_finalizar_servicos").hide();
            $(".btn_finalizar_requisicao").hide();
            $(".btn_reabrir_requisicao").hide();
            $(".btn_suspender_tmo").hide();
            $(".btn_cancelar_tmo").hide();
            $(".btn_reabrir_tmo").hide();
            $(".btn_excluir_tmo").hide();
            $(".btn_excluir_requisicao").hide();
            $(".btn_orcamento").hide();
            $(".btn_previsao_entrega").hide();
            $(".btn_orcamentoPDF").hide();
            $(".btn_gerar_orcamento").hide();
            $(".btn_desconto_requisicao").hide();
            $(".btn_total_os").hide();
            $(".btn_encerra_os").hide();
            $(".btn_observacao").hide();
            $(".btn_desconto_os").hide();
            $(".btn_troca_cliente").hide();
            $(".btn_liberar_desconto_tmo").hide(); 

        }else if(estagioAPP == 'ORCAMENTO_OS_IMPRESSAO'){

            $(".btn_incluir_requisicao").hide();
            $(".btn_novo_servico").hide();
            $(".btn_incluir_tmo").hide();
            $(".btn_atualizar_tmo").hide();
            $(".btn_iniciar_servicos").hide();
            $(".btn_finalizar_servicos").hide();
            $(".btn_finalizar_requisicao").hide();
            $(".btn_reabrir_requisicao").hide();
            $(".btn_suspender_tmo").hide();
            $(".btn_cancelar_tmo").hide();
            $(".btn_reabrir_tmo").hide();
            $(".btn_excluir_tmo").hide();
            $(".btn_excluir_requisicao").hide();
            $(".btn_orcamento").hide();
            $(".btn_previsao_entrega").hide();
            $(".btn_gerar_orcamento").hide();
            $(".btn_atualizar_previsao_entrega").hide();
            $(".btn_desconto_requisicao").hide();
            $(".btn_total_os").hide();
            $(".btn_encerra_os").hide();
            $(".btn_observacao").hide();
            $(".btn_desconto_os").hide();
            $(".btn_troca_cliente").hide();
            $(".btn_liberar_desconto_tmo").hide(); 

        }else if(estagioAPP == 'ORCAMENTO_OS'){

            $(".btn_incluir_requisicao").hide();
            $(".btn_novo_servico").hide();
            $(".btn_incluir_tmo").hide();
            $(".btn_atualizar_tmo").hide();
            $(".btn_iniciar_servicos").hide();
            $(".btn_finalizar_servicos").hide();
            $(".btn_finalizar_requisicao").hide();
            $(".btn_reabrir_requisicao").hide();
            $(".btn_suspender_tmo").hide();
            $(".btn_cancelar_tmo").hide();
            $(".btn_reabrir_tmo").hide();
            $(".btn_excluir_tmo").hide();
            $(".btn_excluir_requisicao").hide();
            $(".btn_orcamento").hide();
            $(".btn_previsao_entrega").hide();
            $(".btn_orcamentoPDF").hide();
            $(".btn_atualizar_previsao_entrega").hide();
            $(".btn_desconto_requisicao").hide();
            $(".btn_total_os").hide();
            $(".btn_encerra_os").hide();
            $(".btn_observacao").hide();
            $(".btn_desconto_os").hide();
            $(".btn_troca_cliente").hide();
            $(".btn_liberar_desconto_tmo").hide(); 

        }else if(estagioAPP == 'TOTAIS_OS'){

            $(".btn_incluir_requisicao").hide();
            $(".btn_novo_servico").hide();
            $(".btn_incluir_tmo").hide();
            $(".btn_atualizar_tmo").hide();
            $(".btn_iniciar_servicos").hide();
            $(".btn_finalizar_servicos").hide();
            $(".btn_finalizar_requisicao").hide();
            $(".btn_reabrir_requisicao").hide();
            $(".btn_suspender_tmo").hide();
            $(".btn_cancelar_tmo").hide();
            $(".btn_reabrir_tmo").hide();
            $(".btn_excluir_tmo").hide();
            $(".btn_excluir_requisicao").hide();
            $(".btn_previsao_entrega").hide();
            $(".btn_orcamentoPDF").hide();
            $(".btn_atualizar_previsao_entrega").hide();
            $(".btn_desconto_requisicao").hide();
            $(".btn_gerar_orcamento").hide();
            $(".btn_total_os").hide();
            $(".btn_liberar_desconto_tmo").hide(); 

            if(statusOS == 'F' || statusOS == 'C'){
                $(".btn_encerra_os").hide();
                $(".btn_observacao").hide();
                $(".btn_desconto_os").hide();
                $(".btn_troca_cliente").hide();
            }
        }

        

        /* **************************************** Eventos Iniciais do bloco  - CONSULTA_REQUISICAO **************************************** */

        if(estagioAPP == 'CONSULTA_REQUISICAO'){

            /* ******************** Eventos de totalização do rodapé das tabelas - CONSULTA_REQUISICAO ******************** */

            /* Tabela de detalhes da requisição com os dados dos serviços relacionados */
            var tableDetServ = $('#detalhesServicos').DataTable();

            var intVal = function ( i ) {
                return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
            };

            //Função que altera o rodapé quando a pagina da tabela é alterada
            $('#detalhesServicos').on( 'draw.dt', function () {

                //Total dos registros exibidos na página atual da tabela 
                pagQtdTot = tableDetServ.column(7, { page: 'current'} ).data().reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                var cnt =0;
                pagValUniTot = tableDetServ.column(8, { page: 'current'} ).data().reduce( function (a, b) {
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
                pagValTot = tableDetServ.column(9, { page: 'current'} ).data().reduce( function (a, b) {
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
                pagValDes = tableDetServ.column(10, { page: 'current'} ).data().reduce( function (a, b) {
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
                pagValLiqTot = tableDetServ.column(11, { page: 'current'} ).data().reduce( function (a, b) {
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
                qtdTot = tableDetServ.column( 7 ).data().reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                });
                var cnt =0;
                valUniTot = tableDetServ.column( 8 ).data().reduce( function (a, b) {
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
                valTot = tableDetServ.column( 9 ).data().reduce( function (a, b) {
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
                valDes = tableDetServ.column( 10 ).data().reduce( function (a, b) {
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
                valLiqTot = tableDetServ.column( 11 ).data().reduce( function (a, b) {
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
                $( tableDetServ.column( 7 ).footer() ).html(new Intl.NumberFormat('en-EN', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(pagQtdTot) +' </br> '+ new Intl.NumberFormat('en-EN', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(qtdTot) );
                //$( tableDetServ.column( 8 ).footer() ).html('R$' + new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(pagValUniTot) +' </br> R$'+ new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valUniTot) );
                $( tableDetServ.column( 9 ).footer() ).html('R$' + new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(pagValTot) +' </br> R$'+ new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valTot) );
                $( tableDetServ.column( 10 ).footer() ).html('R$' + new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(pagValDes) +' </br> R$'+ new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valDes) );
                $( tableDetServ.column( 11 ).footer() ).html('R$' + new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(pagValLiqTot) +' </br> R$'+ new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valLiqTot) );
            });

            //Total dos registros exibidos na página atual da tabela 
            pagQtdTot = tableDetServ.column(7, { page: 'current'} ).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
            var cnt =0;
            pagValUniTot = tableDetServ.column(8, { page: 'current'} ).data().reduce( function (a, b) {
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
            pagValTot = tableDetServ.column(9, { page: 'current'} ).data().reduce( function (a, b) {
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
            pagValDes = tableDetServ.column(10, { page: 'current'} ).data().reduce( function (a, b) {
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
            pagValLiqTot = tableDetServ.column(11, { page: 'current'} ).data().reduce( function (a, b) {
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
            qtdTot = tableDetServ.column( 7 ).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
            });
            var cnt =0;
            valUniTot = tableDetServ.column( 8 ).data().reduce( function (a, b) {
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
            valTot = tableDetServ.column( 9 ).data().reduce( function (a, b) {
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
            valDes = tableDetServ.column( 10 ).data().reduce( function (a, b) {
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
            valLiqTot = tableDetServ.column( 11 ).data().reduce( function (a, b) {
                if(cnt == 0){
                    a = String(a).replaceAll('.','');
                    a = String(a).replaceAll(',','.');
                }
                cnt +=1;
                b = String(b).replaceAll('.','');
                b = String(b).replaceAll(',','.');
                return intVal(a) + intVal(b);
            });
            
            
            //valUniTot = String(valUniTot).replaceAll('.','');
            //valUniTot = String(valUniTot).replaceAll(',','.');

            //apenas quando tem um registro no serviço faz o replace se tiver mais de um serviço não precisa fazer
            if(cnt == 0){
                valTot = String(valTot).replaceAll('.','');
                valTot = String(valTot).replaceAll(',','.');
                valDes = String(valDes).replaceAll('.','');
                valDes = String(valDes).replaceAll(',','.');
                valLiqTot = String(valLiqTot).replaceAll('.','');
                valLiqTot = String(valLiqTot).replaceAll(',','.');
            }            

            console.log(cnt);console.log(pagValLiqTot);console.log(valLiqTot);

            //Atualiza o rodapé
            $( tableDetServ.column( 7 ).footer() ).html(new Intl.NumberFormat('en-EN', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(pagQtdTot) +' </br> '+ new Intl.NumberFormat('en-EN', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(qtdTot) );
            //$( tableDetServ.column( 8 ).footer() ).html('R$' + new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(pagValUniTot) +' </br> R$'+ new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valUniTot) );
            $( tableDetServ.column( 9 ).footer() ).html('R$' + new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(pagValTot) +' </br> R$'+ new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valTot) );
            $( tableDetServ.column( 10 ).footer() ).html('R$' + new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(pagValDes) +' </br> R$'+ new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valDes) );
            $( tableDetServ.column( 11 ).footer() ).html('R$' + new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(pagValLiqTot) +' </br> R$'+ new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valLiqTot) );
            /* Final da montagem da Tabela de detalhes da requisição com os dados dos serviços relacionados */
        }

        

        /* **************************************** Eventos Iniciais dos blocos  - INCLUSAO_SERVICO e MANUTENCAO_SERVICO **************************************** */

        if(estagioAPP == 'INCLUSAO_SERVICO' || estagioAPP == 'MANUTENCAO_SERVICO'){

            /* ******************** Mascaras de campos float ******************** */

            //Mascaras do inclusão da TMO do serviço
            $('#qtdHrTMO').mask('#.##0.00', {reverse: true});
            $('#valUniHrTMO').mask('#.##0,00', {reverse: true});
            $('#valTotHrTMO').mask('#.##0,00', {reverse: true});
            $('#valCustoTMO').mask('#.##0,00', {reverse: true});
            $('#perCustoTMO').mask('#.##0,00', {reverse: true});
            $('#perDesTMO').mask('#.##0,00', {reverse: true});
            $('#valDesTMO').mask('#.##0,00', {reverse: true});
            $('#valLiqTMO').mask('#.##0,00', {reverse: true});

            //Campos que não serão editaveis no formulario de inclusão / edição
            $("#descricaoTMO").prop('disabled', true);
            $("#tipoTMO").prop('disabled', true);
            $("#valLiqTMO").prop('disabled', true);

            //Ao carregar a app verifica o tipo do valor da tarefa
            if($("#tipoTMO").val() == 'P'){
                $("#valTotHrTMO").prop('disabled', true);
                $("#qtdHrTMO").prop('disabled', false);
                $("#valUniHrTMO").prop('disabled', false);
                $(".bloco-terceiros").hide();
            }else if($("#tipoTMO").val() == 'I'){
                $("#valTotHrTMO").prop('disabled', true);
                $("#qtdHrTMO").prop('disabled', false);
                $("#valUniHrTMO").prop('disabled', false);
                $(".bloco-terceiros").hide();
            }else if($("#tipoTMO").val() == 'R'){
                $("#valTotHrTMO").prop('disabled', true);
                $("#qtdHrTMO").prop('disabled', true);
                $("#valUniHrTMO").prop('disabled', false);
                $(".bloco-terceiros").hide();
            }else if($("#tipoTMO").val() == 'T'){
                $("#valTotHrTMO").prop('disabled', false);
                $("#valUniHrTMO").prop('disabled', true);
                $("#qtdHrTMO").prop('disabled', true);
                $(".bloco-terceiros").show();
            }else{
                $("#valTotHrTMO").prop('disabled', false);
                $("#valUniHrTMO").prop('disabled', true);
                $("#qtdHrTMO").prop('disabled', false);
                $(".bloco-terceiros").hide();
            }

            //Verifica a parametrização do tipo do serviço da requisição se pode alterar o valor do serviço e qtd de horas
            var altValor = {!! json_encode($altValorTOS) !!};
            var altHora = {!! json_encode($altHoraTOS) !!};

            if(altValor == 'N' && $("#tipoTMO").val() != 'R'){
                $("#valUniHrTMO").prop('disabled', true);
                $("#valTotHrTMO").prop('disabled', true);
            }

            if(altHora == 'N' && $("#tipoTMO").val() != 'R'){
                $("#qtdHrTMO").prop('disabled', true);
            }
           
            //Ao carregar a app verifica o tipo do custo
            if($("#tipCustoTMO").val() == '1'){
                $("#valCustoTMO").prop('disabled', false);
                $("#perCustoTMO").hide();
                $('label[for="perCustoTMO"]').hide();
            }else{
                $("#valCustoTMO").prop('disabled', true);
                $("#perCustoTMO").show();
                $('label[for="perCustoTMO"]').show();
            }
        }

        /* **************************************** Eventos Iniciais do bloco  - PREVISAO_ENTREGA **************************************** */

        if(estagioAPP == 'PREVISAO_ENTREGA'){

            //Mascaras do inclusão da TMO do serviço
            $('#qtdHoraOS').mask('#.##0.00', {reverse: true});

            //Esconde calendário de data
            $(function() { 
                $('#horaPrevEnt').on('showCalendar.daterangepicker', function(ev, picker) {
    
                    $('.calendar-table').hide();
    
                }) 
            });

            //Mostra calendário de data
            $(function() { 
                $('#dataPrevEnt').on('showCalendar.daterangepicker', function(ev, picker) {
    
                    $('.calendar-table').show();
                }) 
            });
        }     

        /* **************************************** Eventos Iniciais dos blocos  - DESCONTO_REQUISICAO e MANUTENCAO_SERVICO **************************************** */

        if(estagioAPP == 'TOTAIS_OS' ){

            /* ******************** Mascaras de campos float ******************** */

            //Mascaras do inclusão da TMO do serviço
            $('#valBrutoOS').mask('#.##0,00', {reverse: true});
            $('#perDescontoOS').mask('#.##0,00', {reverse: true});
            $('#valDescontoOS').mask('#.##0,00', {reverse: true});
            $('#valLiquidoOS').mask('#.##0,00', {reverse: true});
        }
    });
</script>




<!--
|--------------------------------------------------------------------------
| Eventos onChange da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() {


        var estagioAPP = {!! json_encode($glo_os_estagioAPP) !!};
        var subEstagioRequisica = {!! json_encode($glo_os_subEstagioRequisicao) !!};

        /* **************************************** Eventos onChange dos blocos  - INCLUSAO_SERVICO e MANUTENCAO_SERVICO **************************************** */

        if(estagioAPP == 'INCLUSAO_SERVICO' || estagioAPP == 'MANUTENCAO_SERVICO'){
        
            /* *************** Evento ao trocar o valor do campo tipo da tarefa - INCLUSAO_SERVICO / MANUTENCAO_SERVICO *************** */
            /* inicialmente não vai permitir trocar o tipo da hora da tarefa, caso mudar descomentar o trecho
                $("#tipoTMO").change(function(){
                
                if(this.value == 'P'){
                    $("#valTotHrTMO").prop('disabled', true);
                    $("#qtdHrTMO").prop('disabled', false);
                    $("#valUniHrTMO").prop('disabled', false);
                    $("#qtdHrTMO").val('');
                    $("#valUniHrTMO").val('');
                    $("#valTotHrTMO").val('');
                    $(".bloco-terceiros").hide();
                    $("#forTerceiroTMO").val('');
                }else if(this.value == 'I'){
                    $("#valTotHrTMO").prop('disabled', true);
                    $("#qtdHrTMO").prop('disabled', false);
                    $("#valUniHrTMO").prop('disabled', false);
                    $("#qtdHrTMO").val('');
                    $("#valUniHrTMO").val('');
                    $("#valTotHrTMO").val('');
                    $(".bloco-terceiros").hide();
                    $("#forTerceiroTMO").val('');
                }else if(this.value == 'R'){
                    $("#valTotHrTMO").prop('disabled', true);
                    $("#qtdHrTMO").prop('disabled', true);
                    $("#valUniHrTMO").prop('disabled', false);
                    $("#qtdHrTMO").val('0.00');
                    $("#valUniHrTMO").val('');
                    $("#valTotHrTMO").val('0,00');
                    $(".bloco-terceiros").hide();
                    $("#forTerceiroTMO").val('');
                }else if(this.value == 'T'){
                    $("#valTotHrTMO").prop('disabled', false);
                    $("#valUniHrTMO").prop('disabled', true);
                    $("#qtdHrTMO").prop('disabled', true);
                    $("#qtdHrTMO").val('0.00');
                    $("#valUniHrTMO").val('0,00');
                    $("#valTotHrTMO").val('');
                    $(".bloco-terceiros").show();
                    $("#forTerceiroTMO").val('');
                }else{
                    $("#valTotHrTMO").prop('disabled', false);
                    $("#valUniHrTMO").prop('disabled', true);
                    $("#qtdHrTMO").prop('disabled', false);
                    $("#qtdHrTMO").val('');
                    $("#valUniHrTMO").val('');
                    $("#valTotHrTMO").val('');
                    $(".bloco-terceiros").hide();
                    $("#forTerceiroTMO").val('');
                }
            });
            */
            /* *************** Evento ao trocar o valor do campo tipo do custo da tarefa - INCLUSAO_SERVICO / MANUTENCAO_SERVICO *************** */
            $("#tipCustoTMO").change(function(){
                
                if(this.value == '1'){
                    $("#valCustoTMO").prop('disabled', false);
                    $("#valCustoTMO").val('');
                    $("#perCustoTMO").val('0.00');
                    $("#perCustoTMO").hide();
                    $('label[for="perCGT"]').hide();
                }else{
                    $("#valCustoTMO").prop('disabled', true);
                    $("#valCustoTMO").val('');
                    $("#perCustoTMO").val('');
                    $("#perCustoTMO").show();
                    $('label[for="perCustoTMO"]').show();
                }
            });

            /* *************** Evento ao trocar o valor do campo tipo do custo da tarefa - INCLUSAO_SERVICO / MANUTENCAO_SERVICO *************** */
            $("#valUniHrTMO").change(function(){
                
                if($("#tipoTMO").val() == 'P'){
                    
                    var qtdHr = $("#qtdHrTMO").val();
                    var valHr = this.value;

                    valHr = valHr.replaceAll('.', '');
                    valHr = valHr.replaceAll(',', '.');

                    var valTot = qtdHr * valHr;
                    valTot = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valTot);

                    $("#valTotHrTMO").val(valTot);

                    $("#valDesTMO").val('0,00');
                    $("#valLiqTMO").val(valTot);
                    $("#perDesTMO").val('0,00');

                }else if($("#tipoTMO").val() == 'I' && $("#qtdHrTMO").val() != ''){
                    
                    var qtdHr = $("#qtdHrTMO").val();
                    var valHr = this.value;

                    valHr = valHr.replaceAll('.', '');
                    valHr = valHr.replaceAll(',', '.');

                    var valTot = qtdHr * valHr;
                    valTot = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valTot);

                    $("#valTotHrTMO").val(valTot);

                    $("#valDesTMO").val('0,00');
                    $("#valLiqTMO").val(valTot);
                    $("#perDesTMO").val('0,00');

                }
            });

            /* *************** Evento ao trocar o valor do campo tipo do custo da tarefa - INCLUSAO_SERVICO / MANUTENCAO_SERVICO *************** */
            $("#valTotHrTMO").change(function(){
                
                if($("#tipoTMO").val() == 'F' && $("#qtdHrTMO").val() != ''){
                    
                    var qtdHr = $("#qtdHrTMO").val();
                    var valTot = this.value;

                    valTot = valTot.replaceAll('.', '');
                    valTot = valTot.replaceAll(',', '.');

                    var valHr = valTot/qtdHr;
                    valHr = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valHr);

                    $("#valUniHrTMO").val(valHr);

                    $("#valDesTMO").val('0,00');
                    valTot = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valTot);
                    $("#valLiqTMO").val(valTot);
                    $("#perDesTMO").val('0,00');

                }else if($("#tipoTMO").val() == 'I' && $("#qtdHrTMO").val() != ''){
                    
                    var qtdHr = $("#qtdHrTMO").val();
                    var valHr = this.value;

                    valHr = valHr.replaceAll('.', '');
                    valHr = valHr.replaceAll(',', '.');

                    var valTot = qtdHr * valHr;
                    valTot = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valTot);

                    $("#valTotHrTMO").val(valTot);

                    $("#valDesTMO").val('0,00');
                    $("#valLiqTMO").val(valTot);
                    $("#perDesTMO").val('0,00');

                }
            });

            /* *************** Evento ao trocar o valor do campo  quantidade de horas da tarefa - INCLUSAO_SERVICO / MANUTENCAO_SERVICO *************** */
            $("#qtdHrTMO").change(function(){
                
                if(($("#tipoTMO").val() == 'I' || $("#tipoTMO").val() == 'P') && $("#valUniHrTMO").val() != ''){
                    
                    var valHr = $("#valUniHrTMO").val();
                    var qtdHr = this.value;

                    valHr = valHr.replaceAll('.', '');
                    valHr = valHr.replaceAll(',', '.');

                    var valTot = valHr*qtdHr;
                    valTot = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valTot);

                    $("#valTotHrTMO").val(valTot);

                    $("#valDesTMO").val('0,00');
                    $("#valLiqTMO").val(valTot);
                    $("#perDesTMO").val('0,00');

                }else if($("#tipoTMO").val() == 'F' && $("#valTotHrTMO").val() != ''){
                    
                    var valTot = $("#valTotHrTMO").val();
                    var qtdHr = this.value;

                    valTot = valTot.replaceAll('.', '');
                    valTot = valTot.replaceAll(',', '.');

                    var valHr = valTot / qtdHr;
                    valHr = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valHr);

                    $("#valUniHrTMO").val(valHr);

                    $("#valDesTMO").val('0,00');
                    valTot = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valTot);
                    $("#valLiqTMO").val(valTot);
                    $("#perDesTMO").val('0,00');
                }
            });

            /* *************** Evento ao trocar o valor do campo  de percentual do custo da tarefa - INCLUSAO_SERVICO / MANUTENCAO_SERVICO *************** */
            $("#perCustoTMO").change(function(){
                
                if($("#valTotHrTMO").val() != ''){
                    
                    var valTot = $("#valTotHrTMO").val();
                    var perCGT = this.value;

                    perCGT = perCGT.replaceAll('.', '');
                    perCGT = perCGT.replaceAll(',', '.');

                    valTot = valTot.replaceAll('.', '');
                    valTot = valTot.replaceAll(',', '.');

                    var valCGT = (valTot / 100) * perCGT;
                    valCGT = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valCGT);

                    $("#valCustoTMO").val(valCGT);

                }
            });

            /* *************** Evento ao trocar o valor do campo valor do desconto - INCLUSAO_SERVICO / MANUTENCAO_SERVICO *************** */
            $("#valDesTMO").change(function(){
                
                var valTot = $("#valTotHrTMO").val();
                var valDes = this.value;

                valTot = valTot.replaceAll('.', '');
                valTot = valTot.replaceAll(',', '.');

                valDes = valDes.replaceAll('.', '');
                valDes = valDes.replaceAll(',', '.');

                var percentual = (valDes / valTot) * 100;
                var valLiq = valTot - valDes;

                valLiq = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valLiq);
                percentual = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(percentual);

                $("#valLiqTMO").val(valLiq);
                $("#perDesTMO").val(percentual);
            });

            /* *************** Evento ao trocar o valor do campo de percentual do desconto - INCLUSAO_SERVICO / MANUTENCAO_SERVICO *************** */
            $("#perDesTMO").change(function(){
                
                var valTot = $("#valTotHrTMO").val();
                var percentual = this.value;

                valTot = valTot.replaceAll('.', '');
                valTot = valTot.replaceAll(',', '.');

                percentual = percentual.replaceAll('.', '');
                percentual = percentual.replaceAll(',', '.');

                var valorLiquido = valTot - ((valTot / 100) * percentual);
                var valorDesconto = (valTot / 100) * percentual;

                valorLiquido = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valorLiquido);
                valorDesconto = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valorDesconto);


                $("#valLiqTMO").val(valorLiquido);
                $("#valDesTMO").val(valorDesconto);
            });
        }

        if(estagioAPP == 'TOTAIS_OS'){

            /* *************** Evento ao trocar o valor do campo  de percentual do desconto - TOTAIS_OS *************** */
            $("#perDescontoOS").change(function(){
                
                var valBruto = $("#valBrutoOS").val();
                var percentual = this.value;

                valBruto = valBruto.replaceAll('.', '');
                valBruto = valBruto.replaceAll(',', '.');

                percentual = percentual.replaceAll('.', '');
                percentual = percentual.replaceAll(',', '.');


                var valorLiquido = valBruto - ((valBruto / 100) * percentual);
                var valorDesconto = (valBruto / 100) * percentual;

                valorLiquido = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valorLiquido);
                valorDesconto = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valorDesconto);


                $("#valLiquidoOS").val(valorLiquido);
                $("#valDescontoOS").val(valorDesconto);
            });

            /* *************** Evento ao trocar o valor do campo do valor do desconto - DESCONTO_REQUISICAO *************** */
            $("#valDescontoOS").change(function(){
                
                var valBruto = $("#valBrutoOS").val();
                var valDesc = this.value;

                valBruto = valBruto.replaceAll('.', '');
                valBruto = valBruto.replaceAll(',', '.');

                valDesc = valDesc.replaceAll('.', '');
                valDesc = valDesc.replaceAll(',', '.');


                var percentual = (valDesc / valBruto) * 100;
                var valorLiquido = valBruto - valDesc;

                valorLiquido = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valorLiquido);
                percentual = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(percentual);


                $("#valLiquidoOS").val(valorLiquido);
                $("#perDescontoOS").val(percentual);
                
            });
        }

        if(estagioAPP == 'INCLUSAO_REQUISICAO'){

            //Evento de carregamento ajax dos dados dos setores
            $('#areaReq').change(function(){

                if( $(this).val() && $('#areaReq').val() != '' ) {
                    
                    var emp = {!! json_encode($glo_os_empresa) !!};
                    var area = $(this).val();

                    var url = "{{ route('requisicaoOS.carregaSetAjax', [':area',':emp']) }}";
                    url = url.replace(':area', area);
                    url = url.replace(':emp', emp);

                    console.log(url);

                    $.ajax({
                        url: url,
                        dataType: "JSON",
                        type: 'GET',
                        data: {
                            '_token': $('meta[name=csrf-token]').attr("content"),
                            '_method': 'GET',
                            "area": area,
                            "emp": emp
                        },
                        success: function (data)
                        {
                            if(data.setores_ajax_existe == 'S'){

                                var options = '<option value="">Selecione...</option>';	

                                for (var i = 0; i < data.setores_ajax.length; i++) {

                                    options += '<option value="' + data.setores_ajax[i].id + '">' + data.setores_ajax[i].cod_setor + '</option>';
                                }	

                                $('#setorReq').html(options);

                            }else{
                                $('#setorReq').html('<option value="">Selecione...</option>');
                            }
                        }
                    });
                } else {
                    $('#setorReq').html('<option value="">Selecione...</option>');
                }

                if( $(this).val() && $('#areaReq').val() != '' ) {
                    
                    var emp = {!! json_encode($glo_os_empresa) !!};
                    var area = $(this).val();
                    var cat = $("#categoriaReq").val();

                    var url = "{{ route('requisicaoOS.carregaTipSrvAjax', [':area',':emp',':cat']) }}";
                    url = url.replace(':area', area);
                    url = url.replace(':emp', emp);
                    url = url.replace(':cat', cat);

                    console.log(url);

                    $.ajax({
                        url: url,
                        dataType: "JSON",
                        type: 'GET',
                        data: {
                            '_token': $('meta[name=csrf-token]').attr("content"),
                            '_method': 'GET',
                            "area": area,
                            "emp": emp,
                            "cat": cat
                        },
                        success: function (data)
                        {
                            if(data.tos_ajax_existe == 'S'){

                                var options = '<option value="">Selecione...</option>';	

                                for (var i = 0; i < data.tos_ajax.length; i++) {

                                    options += '<option value="' + data.tos_ajax[i].id + '">' + data.tos_ajax[i].cod_tipo + '</option>';
                                }	

                                $('#tipoServicoReq').html(options);

                            }else{
                                $('#tipoServicoReq').html('<option value="">Selecione...</option>');
                            }
                        }
                    });
                } else {
                    $('#tipoServicoReq').html('<option value="">Selecione...</option>');
                }
            });


        }
    });
</script>



<!--
|--------------------------------------------------------------------------
| Eventos onClick da app
|--------------------------------------------------------------------------
-->

<script>
    $(document).ready(function() {

        var estagioAPP = {!! json_encode($glo_os_estagioAPP) !!};
        var subEstagioRequisica = {!! json_encode($glo_os_subEstagioRequisicao) !!};

        /* **************************************** Eventos onClick dos blocos - INCLUSAO_SERVICO   **************************************** */

        if(estagioAPP == 'INCLUSAO_SERVICO'){

            //Ao clicar no botão incluir TMO retira o disabled do campo para não ter problema no request do update do campo
            $(".btn_hide_incluir_tmo").click(function(){
                $("#valTotHrTMO").prop('disabled', false);
                $("#qtdHrTMO").prop('disabled', false);
                $("#valUniHrTMO").prop('disabled', false);
                $("#descricaoTMO").prop('disabled', false);
                $("#tipoTMO").prop('disabled', false);
                $("#valCustoTMO").prop('disabled', false);
                $("#valLiqTMO").prop('disabled', false);
            });
        }

        /* **************************************** Eventos onClick dos blocos - MANUTENCAO_SERVICO  **************************************** */

        if(estagioAPP == 'MANUTENCAO_SERVICO'){

            //Ao clicar no botão atualizar TMO retira o disabled do campo para não ter problema no request do update do campo
            $(".btn_hide_atualizar_tmo").click(function(){
                $("#valTotHrTMO").prop('disabled', false);
                $("#qtdHrTMO").prop('disabled', false);
                $("#valUniHrTMO").prop('disabled', false);
                $("#descricaoTMO").prop('disabled', false);
                $("#tipoTMO").prop('disabled', false);
                $("#valCustoTMO").prop('disabled', false);
                $("#valLiqTMO").prop('disabled', false);
            });
        }
        
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

    jQuery.validator.addMethod("maxpercent", function(value, element) {
        return this.optional(element) || /^(\d{1,2}|\d{1,2}\,\d{1,2}|100\,[0]{1,2}|100)$/i.test(value);
    }, "Porcentagem máxima de 100,00 %");

    jQuery.validator.addMethod("maxqtdhr", function(value, element) {
        return this.optional(element) || /^(\d{1,3}|\d{1,3}\.\d{1,3}|999\.[0]{1,2}|999)$/i.test(value);
    }, "Quantidade de horas máxima de 999.99");

    //Inserção da TMO na requisição
    $('#quickForm-ins-upd-servico').validate({
        rules: {
            descricaoTMO: {
                required: true,
                maxlength: 40
            },
            tipoTMO: {
                required: true
            },
            qtdHrTMO: {
                required: true,
                maxqtdhr: true
            },
            valUniHrTMO: {
                required: true,
                maxlength: 20
            },
            valTotHrTMO: {
                required: true,
                maxlength: 20
            },
            perCustoTMO: {
                maxpercent: true
            },
            valCustoTMO: {
                maxlength: 20
            },
            numNfTerceiroTMO: {
                maxlength: 9
            },
            serNfTerceiroTMO: {
                maxlength: 5
            },
        },
        messages: {
            descricaoTMO: {
                required: "Por Favor informe a Descrição da TMO",
                maxlength: "Infome no máximo 40 caracteres"
            },
            tipoTMO: {
                required: "Por Favor informe um Tipo da Tarefa"
            },
            qtdHrTMO: {
                required: "Por Favor informe a Quantidade de Horas"
            },
            valUniHrTMO: {
                required: "Por Favor informe o Valor Unitário"
            },
            valTotHrTMO: {
                required: "Por Favor informe o Valor Total"
            },
            valCustoTMO: {
                maxlength: "Limite máximo do valor é de 15 digitos"
            },
            numNfTerceiroTMO: {
                maxlength: "Infome no máximo 9 dígitos"
            },
            serNfTerceiroTMO: {
                maxlength: "Infome no máximo 5 caracteres"
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

    //Atualização do desconto da requisição
    $('#formulario-desconto-os').validate({
        rules: {
            perDescontoOS: {
                maxpercent: true
            },
            valDescontoOS: {
                maxlength: 20
            },
        },
        messages: {
            valDescontoOS: {
                maxlength: "Limite máximo do valor é de 15 digitos"
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

    //Atualização da previsão de entrega
    $('#quickForm-upd-prev-entrega').validate({
        rules: {
            qtdHoraOS: {
                required: true,
                maxqtdhr: true
            },
            dataPrevEnt: {
                required: true
            },
            horaPrevEnt: {
                required: true
            },
        },
        messages: {
            qtdHoraOS: {
                required: "Por Favor informe a Quantidade de Horas"
            },
            dataPrevEnt: {
                required: "Por Favor informe a Data da Previsão de Entrega "
            },
            horaPrevEnt: {
                required: "Por Favor informe a Hora da Previsão de Entrega "
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

    //Atualização da observação da os
    $('#formulario-observacao-os').validate({
        rules: {
            observacaoOS: {
                required: true,
                maxlength: 255
            }
        },
        messages: {
            observacaoOS: {
                required: "Por Favor informe a Observação",
                maxlength: "Informe no máximo 255 caracteres"
            }
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

    //Atualização do cliente da fatura da os
    $('#formulario-troca-cli-fat').validate({
        rules: {
            cliFatura: {
                required: true
            }
        },
        messages: {
            cliFatura: {
                required: "Por Favor informe o Cliente"
            }
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

    @if(Session::has('info'))
        Swal.fire({
            confirmButtonColor: "#007bff",
            title: "Aviso!",
            text: "{{ session('info') }}",
            icon: "info",
            customClass: {
                icon: "no-before-icon",
            }
        });
    @endif

    @if(Session::has('success2'))
        Swal.fire({
            confirmButtonColor: "#007bff",
            title: "Sucesso!",
            text: "{{ session('success2') }}",
            icon: "success",
            customClass: {
                icon: "no-before-icon",
            }
        });
    @endif
</script>
@stop
