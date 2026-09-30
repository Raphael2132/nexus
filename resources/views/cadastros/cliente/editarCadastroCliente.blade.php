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
            @if($tipo != 'C' && $tipo != 'H')
            <li class="breadcrumb-item active">
                <a href="{{route('cadastroCliente.show', ['cadastroCliente' => $tipo])}}">Clientes Cadastrados</a>
            </li>
            @endif
            <li class="breadcrumb-item active">Manutenção do Cliente</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="col-12 col-sm-12">
    <div class="card card-tabs">
        <div class="card-header card-nexus p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                <li class="pt-2 px-3"><h3 class="card-title">Manutenção do Cliente</h3></li>
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-two-dados-pessoais-tab" data-toggle="pill" href="#custom-tabs-two-dados-pessoais" role="tab" aria-controls="custom-tabs-two-dados-pessoais" aria-selected="true">Dados Pessoais</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-two-contato-tab" data-toggle="pill" href="#custom-tabs-two-contato" role="tab" aria-controls="custom-tabs-two-contato" aria-selected="false">Contato</a>
                 </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-two-endereco-tab" data-toggle="pill" href="#custom-tabs-two-endereco" role="tab" aria-controls="custom-tabs-two-endereco" aria-selected="false">Endereço</a>
                </li>
                <div class="card-tools ml-auto">          
                    <button type="button" class="btn btn-tool" data-card-widget="maximize">
                        <i class="fas fa-lg fa-expand"></i>     
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-lg fa-minus"></i>    
                    </button>
                </div>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-two-tabContent">

                <!-- Aba Dados Pessoais -->
                <div class="tab-pane fade show active" id="custom-tabs-two-dados-pessoais" role="tabpanel" aria-labelledby="custom-tabs-two-dados-pessoais-tab">
                    <form method="post" action="{{route('cadastroCliente.update', ['cadastroCliente' => $dadosCliente, 'atualiza' => 'dados', 'tipo' => $tipo])}}" id="formulario-dados" novalidate="novalidate">
                    @csrf 
                    @method('put')

                        <div class="row">
                            <!-- Tipo de Cadastro -->
                            <x-adminlte-select name="tipoCadastro" fgroup-class="col-md-5">
                                <x-slot name="label">
                                    Tipo de Cadastro <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="['C' => 'Cliente', 'F' => 'Fornecedor']" empty-option="Selecione..." selected="{{$dadosCliente->cliente_tipo_cadastro }}"/>
                            </x-adminlte-select>

                            <!-- Tipo de Pessoa -->
                            <x-adminlte-select name="tipoPessoa" fgroup-class="col-md-5">
                                <x-slot name="label">
                                    Tipo de Pessoa <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="['F' => 'Física', 'J' => 'Jurídica']" empty-option="Selecione..." selected="{{$dadosCliente->cliente_tipo_pessoa }}"/>
                            </x-adminlte-select>

                            <!-- Código do cliente -->
                            <x-adminlte-input name="codCliente" type="text" placeholder="Nome Completo" fgroup-class="col-md-2" value="{{$dadosCliente->cliente_codigo }}">
                                <x-slot name="label">
                                    Código do Cliente <span style="color:red;">*</span>
                                </x-slot>
                            </x-adminlte-input>
                        </div>

                        <div class="row">
                            <!-- Nome -->
                            <x-adminlte-input name="nome" type="text" placeholder="Nome Completo" fgroup-class="col-md-8" value="{{$dadosCliente->cliente_nome }}">
                                <x-slot name="label">
                                    Nome <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fa-solid fa-id-badge fa-lg"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>

                            <!-- CPF / CNPJ -->
                            <x-adminlte-input name="cpfCnpj" type="text" fgroup-class="col-md-4" value="{{$dadosCliente->cliente_cpf_cnpj }}">
                                <x-slot name="label">
                                    CPF / CNPJ <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fa-regular fa-id-card"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>

                        <!-- Dados do Cliente Fisico -->
                        <div id="dadosPessoal">
                            <div class="row">
                                <!-- RG -->
                                <x-adminlte-input name="rg" type="text" label="RG" fgroup-class="col-md-4" value="{{$dadosCliente->cliente_rg }}">
                                    <x-slot name="prependSlot">
                                        <div class="input-group-text x-slot-nexus">
                                            <i class="fa-solid fa-id-card"></i>
                                        </div>
                                    </x-slot>
                                </x-adminlte-input>

                                @php
                                    $config = Helper::dtRangeDataPtBR();

                                    if(!empty($dadosCliente->cliente_data_nascimento)){
                                        $data_nascimento = date('d/m/Y', strtotime($dadosCliente->cliente_data_nascimento));
                                    }else{
                                        $data_nascimento = '';
                                    }
                                @endphp
                                <!-- Data de Nascimento -->
                                <x-adminlte-date-range name="dataNascimento" label="Data de Nascimento" :config="$config" placeholder="Formato dia/mês/ano" fgroup-class="col-md-4">
                                    <x-slot name="prependSlot">
                                        <div class="input-group-text x-slot-nexus">
                                            <i class="far fa-lg fa-calendar-alt"></i>
                                        </div>
                                    </x-slot>
                                </x-adminlte-date-range>
                                @push('js')<script>$(() => $("#dataNascimento").val('{{ $data_nascimento }}'))</script>@endpush

                                <!-- Sexo -->
                                <x-adminlte-select name="sexo" label="Sexo" fgroup-class="col-md-4">
                                    <x-adminlte-options :options="['M' => 'Masculino', 'F' => 'Feminino']" empty-option="Selecione..." selected="{{$dadosCliente->cliente_sexo }}" />
                                </x-adminlte-select>
                            </div>
                        </div>

                        <!-- Dados do cliente Juridico -->
                        <div id="dadosJuridicos">
                            <div class="row">
                                <!-- Inscrição Estadual -->
                                <x-adminlte-input name="insEstadual" type="text" label="Inscrição Estadual" fgroup-class="col-md-6" value="{{$dadosCliente->cliente_insc_estadual }}"></x-adminlte-input>

                                <!-- Inscrição Municipal -->
                                <x-adminlte-input name="insMunicipal" type="number" label="Inscrição Municipal" fgroup-class="col-md-6" value="{{$dadosCliente->cliente_insc_municipal }}"></x-adminlte-input>
                            </div>

                            <div class="row">
                                @php
                                    $config = Helper::dtRangeDataPtBR();
                                    
                                    if(!empty($dadosCliente->cliente_dt_fundacao)){
                                        $data_fundacao = date('d/m/Y', strtotime($dadosCliente->cliente_dt_fundacao));
                                    }else{
                                        $data_fundacao = '';
                                    }
                                @endphp
                                <!-- Data de Fundação da Empresa -->
                                <x-adminlte-date-range name="dataFundacao" label="Data de Fundação" :config="$config" placeholder="Formato dia/mês/ano" fgroup-class="col-md-3">
                                    <x-slot name="prependSlot">
                                        <div class="input-group-text x-slot-nexus">
                                            <i class="far fa-lg fa-calendar-alt"></i>
                                        </div>
                                    </x-slot>
                                </x-adminlte-date-range>
                                @push('js')<script>$(() => $("#dataFundacao").val('{{ $data_fundacao }}'))</script>@endpush

                                <x-adminlte-select name="microEmp" label="Micro Empresa" fgroup-class="col-md-3">
                                    <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" empty-option="Selecione..." selected="{{$dadosCliente->cliente_micro_emp }}" />
                                </x-adminlte-select>

                                <x-adminlte-select name="ramoAtiv" label="Ramo de Atividade" fgroup-class="col-md-3">
                                    <x-adminlte-options :options="['C' => 'Comércio', 'I' => 'Indústria', 'S' => 'Serviços', 'O' => 'Outros']" empty-option="Selecione..." selected="{{$dadosCliente->cliente_ramo_atividade }}" />
                                </x-adminlte-select>

                                <x-adminlte-select name="orgPub" label="Órgão Público" fgroup-class="col-md-3">
                                    <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" empty-option="Selecione..." selected="{{$dadosCliente->cliente_org_publico }}" />
                                </x-adminlte-select>
                            </div>
                            <div class="row">
                                @php 
                                    $array_div = HelperArraySelect::arrayCNAEDivisoes(1,1);

                                    if(!empty($dadosCliente->cliente_cnae)){

                                        $dados_cnaeCod = DB::table('cnae_codigos')->where('cnaesub_cod', $dadosCliente->cliente_cnae)->first();
                                        
                                        $array_grp = HelperArraySelect::arrayCNAEGrupos($dados_cnaeCod->cnaesub_div,1,1);

                                        $divCNAE = $dados_cnaeCod->cnaesub_div;
                                        $grpCNAE = $dados_cnaeCod->cnaesub_grp;

                                        $array_cod = HelperArraySelect::arrayCNAECodigos($divCNAE, $grpCNAE, 1, 1);

                                    }else{
                                        $array_cod = null;
                                        $array_grp = null;
                                        $divCNAE = null;
                                        $grpCNAE = null;
                                    }
                                @endphp
                                <x-adminlte-select name="cnaeDiv" fgroup-class="col-md-4">
                                    <x-slot name="label">
                                        CNAE Divisão
                                    </x-slot>
                                    <x-adminlte-options :options="$array_div" empty-option="Selecione..." selected="{{ $divCNAE }}"/>
                                </x-adminlte-select>

                                <x-adminlte-select name="cnaeGrp" fgroup-class="col-md-4">
                                    <x-slot name="label">
                                        CNAE Grupo
                                    </x-slot>
                                    <x-adminlte-options :options="$array_grp" empty-option="Selecione..." selected="{{ $grpCNAE }}"/>
                                </x-adminlte-select>

                                <x-adminlte-select name="cnaeCod" fgroup-class="col-md-4">
                                    <x-slot name="label">
                                        CNAE Código
                                    </x-slot>
                                    <x-adminlte-options :options="$array_cod" empty-option="Selecione..." selected="{{ $dadosCliente->cliente_cnae }}"/>
                                </x-adminlte-select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <x-adminlte-button class="btn-nexus" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                        </div>
                    </form>
                </div>

                <!-- Aba dos dados do Contato do cliente -->
                <div class="tab-pane fade" id="custom-tabs-two-contato" role="tabpanel" aria-labelledby="custom-tabs-two-contato-tab">
                    <form method="post" action="{{route('cadastroCliente.update', ['cadastroCliente' => $dadosCliente, 'atualiza' => 'contato', 'tipo' => $tipo])}}" id="quickForm2" novalidate="novalidate">
                    @csrf 
                    @method('put')
                        <div class="row">
                            <x-adminlte-select name="prefContato" fgroup-class="col-md-4">
                                <x-slot name="label">
                                    Preferência de Contato <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="['EMA' => 'Email', 'TCO' => 'Telefone Comercial', 'TCE' => 'Telefone Celular', 'TRE' => 'Telefone Residencial', 'WTA' => 'WhatsApp']" empty-option="Selecione..." selected="{{ $dadosCliente->cliente_pref_contato }}"/>
                            </x-adminlte-select>

                            <!-- Tipo do Email -->
                            <x-adminlte-select name="tipoEmail" fgroup-class="col-md-4">
                                <x-slot name="label">
                                    Tipo do Email <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="['P' => 'Pessoal', 'C' => 'Comercial']" empty-option="Selecione..." selected="{{$dadosCliente->cliente_tipo_email}}"/>
                            </x-adminlte-select>

                            <!-- Email -->
                            <x-adminlte-input name="email" type="email" placeholder="email@exemplo.com" fgroup-class="col-md-4" value="{{$dadosCliente->cliente_email}}">
                                <x-slot name="label">
                                    Email <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fa-solid fa-envelope"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>

                        <div class="row">
                            <!-- Telefone Residencial -->
                            <x-adminlte-input name="telResidencial" type="text" label="Telefone Residencial" fgroup-class="col-md-4" value="{{ $dadosCliente->cliente_tel_residencial }}">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>

                            <!-- Telefone Celular -->
                            <x-adminlte-input name="telCelular" type="text" label="Telefone Celular" fgroup-class="col-md-4" value="{{ $dadosCliente->cliente_tel_celular }}">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fa-solid fa-mobile-retro"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>

                            <!-- Telefone Comercial -->
                            <x-adminlte-input name="telComercial" type="text" label="Telefone Comercial" fgroup-class="col-md-4" value="{{ $dadosCliente->cliente_tel_comercial }}">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fa-solid fa-shop"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                        <div class="d-flex justify-content-center">
                            <x-adminlte-button class="btn-nexus" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                        </div>
                    </form>
                </div>

                <!-- Aba dos dados do Endereço do cliente -->
                <div class="tab-pane fade" id="custom-tabs-two-endereco" role="tabpanel" aria-labelledby="custom-tabs-two-endereco-tab">
                    <div class="main col-md-12" style="display: flex;flex-direction: column;"> 

                        @php
                            //Busca os dados dos endereços cadastrados do cliente
                            $data = DB::table('cadastro_cliente_enderecos')->where('endereco_cliente_codigo', $dadosCliente->cliente_codigo)->orderBy('endereco_seq', 'asc')->get();
                                
                            if(empty($data[0])){
                        @endphp
                        <!-- Se ainda não foi cadastrado endereço para o cliente cria card vazio -->
                        <div class="col-md-4">
                            <x-adminlte-card title="Endereço" theme="" theme-mode="outline" header-class="card-outline-nexus">
                                <i>Registros não encontrados</i>
                            </x-adminlte-card>
                        </div>
                        @php
                            }else{
                                $cnt_end = 0;
                        @endphp
                        <!-- Cria os cards com os endereços cadastrados -->
                        <div class="col-md-12">
                            @foreach ($data as $endereco)

                                @php
                                    $cnt_end += 1;
                                
                                    if($endereco->endereco_principal == "S"){
                                        $titulo = "Endereço ".$cnt_end." - Principal";
                                        $icone = 'fa-solid fa-location-dot';
                                    }else{
                                        $titulo = "Endereço ".$cnt_end;
                                        $icone = '';
                                    }

                                    $cep = substr($endereco->endereco_cep,0,5).'-'.substr($endereco->endereco_cep,-3,3);
                                @endphp
                                <div class="col-md-4" style="float: left;">
                                    <!-- Card do Endereço do cliente -->
                                    <x-adminlte-card :title="$titulo" :icon="$icone" theme="" theme-mode="outline" header-class="card-outline-nexus">
                                        <i>{{ $endereco->endereco_logradouro }}, {{ $endereco->endereco_numero }}</br>
                                            @php
                                                if(!empty($endereco->endereco_complemento)){
                                            @endphp
                                            Complemento: {{ $endereco->endereco_complemento }}</br>
                                            @php
                                                }
                                            @endphp
                                            {{ $cep }}</br>
                                            {{ $endereco->endereco_bairro }}</br>
                                            {{ $endereco->endereco_cidade }} - {{ $endereco->endereco_uf }}</br>
                                            {{ $endereco->endereco_pais }}
                                        </i>
                                        <!-- Gera a div dos botões do card -->
                                        <div style="padding: 10px; height:30px;">
                                            <form method="post" action="{{ route('clienteEndereco.destroy', ['clienteEndereco' => $endereco->endereco_id, 'tipo' => $tipo]) }}" style="float: left;" >
                                            @csrf 
                                            @method('delete')
                                                <x-adminlte-button title="Excluir Endereço" class="btn-sm" theme="danger" icon="fa fa-lg fa-fw fa-trash" type="submit" style="margin-right: 5px;"/>
                                            </form>
                                            @php
                                                if($endereco->endereco_principal == "N"){
                                                    $endPrincipal = json_encode($endereco);
                                            @endphp
                                            <form method="post" action="{{ route('clienteEndereco.update', ['clienteEndereco' => $endereco->endereco_id, 'tipo' => $tipo]) }}" style="float: left;">
                                            @csrf 
                                            @method('put')
                                                <x-adminlte-button title="Tornar Principal" class="btn-sm" label="Tornar Principal" theme="success" icon="fa-solid fa-location-dot" type="submit"/>
                                            </form>
                                            @php 
                                                }
                                            @endphp
                                        </div>
                                    </x-adminlte-card>
                                </div>
                            @endforeach
                        </div>
                        <!-- Fecha o else da montagem dos cards do endereço -->
                        @php
                            }
                        @endphp
                           
                        <!-- Gera o Modal com os campos da inserção dos dados do endereço do cliente -->
                        <div>
                            <form method="post" action="{{route('clienteEndereco.store')}}" id="formulario-endereco" novalidate="novalidate">
                            @csrf 
                            @method('post')    
                                <!-- Criação do Modal -->                           
                                <x-adminlte-modal id="modalCustom" title="Novo Endereço" size="lg" theme="modal-nexus" icon="fa-solid fa-address-card" v-centered static-backdrop scrollable>
                                    <div style="height:400px;">
                                        <!-- Campos escondidoscom o id e codigo do cliente para o request -->  
                                        <input id="cliente_codigo" type="hidden" value="{{ $dadosCliente->cliente_codigo }}" name="cliente_codigo">
                                        <input id="ibgeCodMun" type="hidden" name="ibgeCodMun">
                                        <input id="tipo" type="hidden" value="{{ $tipo }}" name="tipo">
                                    
                                        <!-- CEP -->
                                        <x-adminlte-input name="cep" type="text" fgroup-class="col-md-4">
                                            <x-slot name="label">
                                                CEP <span style="color:red;">*</span>
                                            </x-slot>
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text x-slot-nexus">
                                                    <i class="fa-solid fa-location-dot"></i>
                                                </div>
                                            </x-slot>
                                        </x-adminlte-input>

                                        <div class="row">
                                            <!-- Logradouro -->
                                            <x-adminlte-input name="logradouro" type="text" fgroup-class="col-md-9">
                                                <x-slot name="label">
                                                    Logradouro <span style="color:red;">*</span>
                                                </x-slot>
                                                <x-slot name="prependSlot">
                                                    <div class="input-group-text x-slot-nexus">
                                                        <i class="fa-solid fa-address-book"></i>
                                                    </div>
                                                </x-slot>
                                            </x-adminlte-input>

                                            <!-- Numero -->
                                            <x-adminlte-input name="numero" type="text" fgroup-class="col-md-3">
                                                <x-slot name="label">
                                                    Número <span style="color:red;">*</span>
                                                </x-slot>
                                                <x-slot name="prependSlot">
                                                    <div class="input-group-text x-slot-nexus">
                                                        <i class="fa-solid fa-hashtag"></i>
                                                    </div>
                                                </x-slot>
                                            </x-adminlte-input>
                                        </div>
                                            
                                        <div class="row">
                                            <!-- Complemento -->
                                            <x-adminlte-input name="complemento" type="text" label="Complemento" placeholder="Exe.: Apto 1002, Casa A ou Chácara" fgroup-class="col-md-6"></x-adminlte-input>

                                            <!-- Bairro -->
                                            <x-adminlte-input name="bairro" type="text" fgroup-class="col-md-6">
                                                <x-slot name="label">
                                                    Bairro <span style="color:red;">*</span>
                                                </x-slot>
                                            </x-adminlte-input>
                                        </div>

                                        <div class="row">
                                            <!-- Cidade -->
                                            <x-adminlte-input name="cidade" type="text" fgroup-class="col-md-6">
                                                <x-slot name="label">
                                                    Cidade <span style="color:red;">*</span>
                                                </x-slot>
                                                <x-slot name="prependSlot">
                                                    <div class="input-group-text x-slot-nexus">
                                                        <i class="fa-solid fa-city"></i>
                                                    </div>
                                                </x-slot>
                                            </x-adminlte-input>

                                            @php
                                                $array_opt = HelperArraySelect::arrayEstados(1,1);
                                                $array_opt_pais = HelperArraySelect::arrayPaises(1,2);
                                            @endphp

                                            <!-- Estado -->
                                            <x-adminlte-select name="uf" fgroup-class="col-md-3">
                                                <x-slot name="label">
                                                    UF <span style="color:red;">*</span>
                                                </x-slot>
                                                <x-adminlte-options :options="$array_opt" empty-option="Selecione..."/>
                                            </x-adminlte-select>

                                            <!-- Pais -->
                                            <x-adminlte-select name="pais" fgroup-class="col-md-3">
                                                <x-slot name="label">
                                                    País <span style="color:red;">*</span>
                                                </x-slot>
                                                <x-adminlte-options :options="$array_opt_pais" empty-option="Selecione..."/>
                                            </x-adminlte-select>
                                        </div>
                                        <!-- Criação dos botões do Modal -->  
                                        <x-slot name="footerSlot">
                                            <x-adminlte-button class="btn-nexus mr-auto" theme="" label="Salvar" icon="fa-solid fa-share-from-square" type="submit"/>
                                            <x-adminlte-button class="btn-nexus" theme="" label="Voltar" data-dismiss="modal"/>
                                        </x-slot>
                                    </div>
                                </x-adminlte-modal>
                            </form>
                            <!-- Botão de chamada do Modal -->  
                            <div class="d-flex justify-content-center">
                                <x-adminlte-button class="btn-nexus" label="Novo Endereço" data-toggle="modal" theme="" data-target="#modalCustom" icon="fa-solid fa-address-book"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between w-100">
                <div class="d-flex">
                    <form method="get" action="{{ route('cadastroCliente.create') }}" style="float: left; margin-right: 2px;">
                    @csrf 
                        <x-adminlte-button class="btn-nexus" label="Novo Cliente" theme="" icon="fa-solid fa-plus" type="submit"/>
                    </form>
                    <form method="post" action="{{ route('cadastroCliente.destroy', ['cadastroCliente' => $dadosCliente]) }}" style="float: left;margin-left: 2px;">
                    @csrf 
                    @method('delete')
                        <x-adminlte-button class="btn-nexus" label="Excluir Cliente" theme="" icon="fa-solid fa-trash" type="submit"/>
                    </form>
                </div>
                <div class="d-flex">
                    @if($tipo != 'C')
                    <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('cadastroCliente.show', ['cadastroCliente' => $tipo]) }}'" label="Voltar" theme="" icon=""/>
                    @else
                    <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('cadastroCliente.index') }}'" label="Voltar" theme="" icon=""/>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('css')
@stop

<!-- Chamada dos Plugins usados na app -->  
@section('plugins.Select2', true)
@section('plugins.DateRangePicker', true)
@section('plugins.Inputmask', true)
@section('plugins.Sweetalert2', true)
@section('plugins.toastr', true)
@section('plugins.jqueryValidation', true)

@section('js')
<script>

    $(document).ready(function() {

        // Init input mask on the target element.

        $('#telCelular').inputmask({
            "mask": "(99) 9 9999-9999",
             // Specify other options...
        });
        
        // Init input mask on the target element.

        $('#telResidencial').inputmask({
            "mask": "(99) 9999-9999",
            // Specify other options...
        });

        // Init input mask on the target element.

        $('#telComercial').inputmask({
            "mask": "(99) 9999-9999",
            // Specify other options...
        });

        // Init input mask on the target element.

        $('#cep').inputmask({
            "mask": "99999-999",
            // Specify other options...
        });

        $("#tipoCadastro").prop('disabled', true);
        $("#tipoPessoa").prop('disabled', true);
        $("#codCliente").prop('disabled', true);

        if($("#tipoPessoa").val() == 'F'){

            $("#dadosPessoal").show();
            $("#dadosJuridicos").hide();

            // Init input mask on the target element.

            $('#rg').inputmask({
                "mask": "99.999.999-9",
                // Specify other options...
            });

            $('#cpfCnpj').inputmask({
                "mask": "999.999.999-99",
                // Specify other options...
            });

        }else{

            $("#dadosPessoal").hide();
            $("#dadosJuridicos").show();

            $('#cpfCnpj').inputmask({
                "mask": " 99.999.999/9999-99",
                // Specify other options...
            });
        }
    });

    // Busca os dados do CEP informado
    $("#cep").blur(function(){

        // Remove tudo o que não é número para fazer a pesquisa
        var cep = this.value.replace(/[^0-9]/, "");
        
        // Validação do CEP; caso o CEP não possua 8 números, então cancela
        // a consulta
        if(cep.length != 8){
            return false;
        }
        
        // A url de pesquisa consiste no endereço do webservice + o cep que
        // o usuário informou + o tipo de retorno desejado (entre "json",
        // "jsonp", "xml", "piped" ou "querty")
        var url = "https://viacep.com.br/ws/"+cep+"/json/";
        
        // Faz a pesquisa do CEP, tratando o retorno com try/catch para que
        // caso ocorra algum erro (o cep pode não existir, por exemplo) a
        // usabilidade não seja afetada, assim o usuário pode continuar//
        // preenchendo os campos normalmente
        $.getJSON(url, function(dadosRetorno){
            try{
                // Preenche os campos de acordo com o retorno da pesquisa
                $("#logradouro").val(dadosRetorno.logradouro);
                $("#bairro").val(dadosRetorno.bairro);
                $("#cidade").val(dadosRetorno.localidade);
                $("#uf").val(dadosRetorno.uf);
                $("#complemento").val(dadosRetorno.complemento);
                $("#ibgeCodMun").val(dadosRetorno.ibge);
                $("#numero").focus();
            }catch(ex){}
        });
    });

</script>

<!--
|--------------------------------------------------------------------------
| Eventos onChange da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() {

        //Evento de carregamento ajax dos dados dos grupos CNAE
        $('#cnaeDiv').change(function(){

            if( $(this).val() ) {
                var cod = $(this).val();

                var url = "{{ route('ajax.carregaGruposCnaeAjax', [':cod']) }}";
                url = url.replace(':cod', cod);

                $.ajax({
                    url: url,
                    dataType: "JSON",
                    type: 'GET',
                    data: {
                        '_token': $('meta[name=csrf-token]').attr("content"),
                        '_method': 'GET',
                        "cod": cod
                    },
                    success: function (data)
                    {
                        var options = '<option value="">Selecione...</option>';	

						for (var i = 0; i < data.grupos_ajax.length; i++) {

							options += '<option value="' + data.grupos_ajax[i].id + '">' + data.grupos_ajax[i].cod_grupo + '</option>';
						}	
						$('#cnaeGrp').html(options);
                        $('#cnaeCod').html('<option value="">Selecione...</option>');
                    }
                });
            } else {
				$('#cnaeGrp').html('<option value="">Selecione...</option>');
                $('#cnaeCod').html('<option value="">Selecione...</option>');
			}
        });

        //Evento de carregamento ajax dos dados dos grupos CNAE
        $('#cnaeGrp').change(function(){

            if( $(this).val() ) {
                var grp = $(this).val();
                var div = $('#cnaeDiv').val();

                var url = "{{ route('ajax.carregaCodigosCnaeAjax', [':div', ':grp']) }}";
                url = url.replace(':grp', grp);
                url = url.replace(':div', div);

                $.ajax({
                    url: url,
                    dataType: "JSON",
                    type: 'GET',
                    data: {
                        '_token': $('meta[name=csrf-token]').attr("content"),
                        '_method': 'GET',
                        "div": div,
                        "grp": grp
                    },
                    success: function (data)
                    {
                        var options = '<option value="">Selecione...</option>';	

                        for (var i = 0; i < data.codigos_ajax.length; i++) {

                            options += '<option value="' + data.codigos_ajax[i].id + '">' + data.codigos_ajax[i].cod_cnae + '</option>';
                        }	
                        $('#cnaeCod').html(options);
                    }
                });
            } else {
                $('#cnaeCod').html('<option value="">Selecione...</option>');
            }
        });
    });
</script>

<script>
$(function () {
    $('#formulario-dados').validate({
        rules: {
            tipoCadastro: {
                required: true
            },
            tipoPessoa: {
                required: true
            },
            nome: {
                required: true,
                minlength: 5,
                maxlength: 80
            },
            cpfCnpj: {
                required: true
            },
            codCliente: {
                required: true
            },
        },
        messages: {
            tipoCadastro: {
                required: "Por Favor informe um Tipo de Cadastro"
            },
            tipoPessoa: {
                required: "Por Favor informe um Tipo de Pessoa"
            },
            nome: {
                required: "Por Favor informe o Nome do Cliente",
                minlength: "Infome no mínimo 5 caracteres",
                maxlength: "Infome no máximo 80 caracteres"
            },
            cpfCnpj: {
                required: "Por Favor informe um CPF / CNPJ"
            },
            codCliente: {
                required: "Por Favor informe o Código do Cliente"
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
        },
        // Ao submeter o formulário, reativar os campos desativados
        submitHandler: function (form) {
            // Ativar campos desativados antes de enviar
            $(':disabled').each(function () {
                $(this).removeAttr('disabled');
            });
            form.submit();
        }
    });
});
</script>

<script>
$(function () {
    $('#formulario-contato').validate({
        rules: {
            email: {
                required: true,
                email: true,
                maxlength: 80
            },
            tipoEmail: {
                required: true,
            },
            prefContato: {
                required: true,
            },
        },
        messages: {
            email: {
                required: "Por Favor informe um Email",
                email: "Informe um email válido",
                maxlength: "Infome no máximo 80 caracteres"
            },
            tipoEmail: {
                required: "Por Favor informe o Tipo do Email"
            },
            prefContato: {
                required: "Por Favor informe a Preferência de Contato"
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
$(function () {
  $('#formulario-endereco').validate({
    rules: {
      cep: {
        required: true
      },
      logradouro: {
        required: true,
        maxlength: 100
      },
	  numero: {
        required: true,
		maxlength: 5
      },
	  complemento: {
		maxlength: 60
      },
      bairro: {
		required: true,
        maxlength: 60
      },
      cidade: {
		required: true,
        maxlength: 80
      },
      uf: {
		required: true
      },
      pais: {
		required: true,
        maxlength: 40
      },
    },
    messages: {
      cep: {
        required: "Por Favor informe um CEP para o Endereço"
      },
      logradouro: {
        required: "Por Favor informe um Logradouro para o Endereço",
        maxlength: "Informe no máximo 100 caracteres para o Logradouro"
      },
	  numero: {
        required: "Por Favor informe o Número do Endereço",
		maxlength: "Informe no máximo 5 caracteres no Número"
      },
	  complemento: {
		maxlength: "Informe no máximo 60 caracteres no Complemento"
      },
      bairro: {
		required: "Por Favor informe um Bairro para o Endereço",
        maxlength: "Informe no máximo 60 caracteres no Email"
      },
      cidade: {
		required: "Por Favor informe uma Cidade para o Endereço",
        maxlength: "Informe no máximo 80 caracteres no Email"
      },
      uf: {
		required: "Por Favor informe uma UF para o Endereço"
      },
      pais: {
		required: "Por Favor informe um País para o Endereço",
        maxlength: "Informe no máximo 40 caracteres no Email"
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
