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
                    <a href="{{route('home.clientes')}}">Clientes</a>
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
    'Tipo de Pessoa',
    'CPF/CNPJ',
    'Email',
    ['label' => 'Opções', 'no-export' => true, 'width' => 10],
];
$config = [
    'lengthMenu' => [ 5, 10, 25, 50],
    'pageLength' => 10,
    'processing' => true,
    'language' => Helper::dataTableLangPtBR(),
    'order' => [[1, 'asc']],
    'columns' => [['orderable' => false], null, null, null, null, ['orderable' => false]],
];

if($tipo == 'J'){
    $titulo = 'Clientes Jurídicos Cadastrados';
}elseif($tipo == 'F'){
    $titulo = 'Clientes Físicos Cadastrados';
}elseif($tipo == 'M'){
    $titulo = 'Clientes Cadastrados no Mês';
}else{
    $titulo = 'Clientes Cadastrados';
}
@endphp

<x-adminlte-card :title="$titulo" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable with-buttons>
        @foreach ($clientes as $cliente)
            @php
                if($cliente->cliente_tipo_pessoa == 'F'){
                    $tip_pess = 'Física';
                    $cpfcnpj = substr($cliente->cliente_cpf_cnpj,0,3).'.'.substr($cliente->cliente_cpf_cnpj,3,3).'.'.substr($cliente->cliente_cpf_cnpj,6,3).'-'.substr($cliente->cliente_cpf_cnpj,9,2);
                }else{
                    $tip_pess = 'Jurídica';
                    $cpfcnpj = substr($cliente->cliente_cpf_cnpj,0,2).'.'.substr($cliente->cliente_cpf_cnpj,2,3).'.'.substr($cliente->cliente_cpf_cnpj,5,3).'/'.substr($cliente->cliente_cpf_cnpj,8,4).'-'.substr($cliente->cliente_cpf_cnpj,12,2);
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
                        <a class="text-muted" data-toggle="modal" title="Visualisar Detalhes" data-target="#modalCustom_{{$cliente->cliente_codigo}}">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </a>
                    </nobr>
                </td>
                <td>{{ $cliente->cliente_codigo.' - '.$cliente->cliente_nome }}</td>
                <td>{{ $tip_pess }}</td>
                <td>{{ $cpfcnpj }}</td>
                <td>{{ $cliente->cliente_email }}</td>
                <td>
                    <nobr class="d-flex justify-content-center">
                        <form method="get" action="{{ route('cliente.editarCadastro', ['dadosCliente' => $cliente->cliente_codigo, 'tipo' => $tipo]) }}" style="float: left;">
                            @csrf
                            <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Editar Registro" value="Edit" type="submit">
                                <i class="fa fa-lg fa-fw fa-pen"></i>
                            </button>
                        </form>
                        <form method="post" action="{{route('cliente.destroy', ['cliente' => $cliente])}}" style="float: left;">
                            @csrf 
                            @method('delete')
                            <button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Excluir Registro" value="Delete" type="submit" >
                                <i class="fa fa-lg fa-fw fa-trash"></i>
                            </button>
                        </form>
                    </nobr>
                </td>                
            </tr>
        @endforeach
    </x-adminlte-datatable>
    <x-slot name="footerSlot">
        <div class="d-flex justify-content-between w-100">
            <form method="get" action="{{ route('cliente.cadastro') }}">
                <x-adminlte-button class="btn-nexus" label="Novo Cliente" theme="" icon="fas fa-user-plus" type="submit"/>
            </form>
            <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.clientes') }}'" label="Voltar" theme="" icon=""/>
        </div>
    </x-slot>
</x-adminlte-card>
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('css')
@stop

@section('js')
@stop
