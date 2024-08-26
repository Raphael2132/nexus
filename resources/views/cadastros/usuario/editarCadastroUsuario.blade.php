@extends('adminlte::page')

@section('title', 'Cadastro de Usuarios')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Cadastros</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.usuarios')}}">Usuários</a>
            </li>
            @if($tipo != 'newCad' && $tipo != 'editCad')
                <li class="breadcrumb-item active">
                    <a href="{{route('usuarios', ['tipo' => $tipo])}}">Usuários Cadastrados</a>
                </li>
            @endif
            <li class="breadcrumb-item active">Manutenção do Usuário</li>
        </ol>
    </div>
</div>
@stop

@section('content')
@php
    $altera_permissoes_acesso = Auth::user()->usuario_altera_permissoes_acesso;
    $tipo_usuario = Auth::user()->usuario_tipo;
@endphp
<div class="col-12 col-sm-12">
    
    <!-- Criação do Card com Abas -->
    <div class="card card-navy card-tabs">
        <div class="card-header card-nexus p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                <li class="pt-2 px-3"><h3 class="card-title">Manutenção do Usuário</h3></li>
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-two-dados-gerais-tab" data-toggle="pill" href="#custom-tabs-two-dados-gerais" role="tab" aria-controls="custom-tabs-two-dados-gerais" aria-selected="true">Dados Gerais</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-two-senha-tab" data-toggle="pill" href="#custom-tabs-two-senha" role="tab" aria-controls="custom-tabs-two-senha" aria-selected="true">Senha</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-two-contato-tab" data-toggle="pill" href="#custom-tabs-two-contato" role="tab" aria-controls="custom-tabs-two-contato" aria-selected="false">Contato</a>
                 </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-two-endereco-tab" data-toggle="pill" href="#custom-tabs-two-endereco" role="tab" aria-controls="custom-tabs-two-endereco" aria-selected="false">Endereco</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-two-permissoes-tab" data-toggle="pill" href="#custom-tabs-two-permissoes" role="tab" aria-controls="custom-tabs-two-permissoes" aria-selected="false">Permissões</a>
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

                <!-- Aba Dados Gerais -->
                <div class="tab-pane fade show active" id="custom-tabs-two-dados-gerais" role="tabpanel" aria-labelledby="custom-tabs-two-dados-gerais-tab">
                    <form method="post" action="{{route('usuario.atualizar', ['usuario' => $dadosUsuario[0]['id'], 'usuario_cod' => $dadosUsuario[0]['usuario_codigo'], 'atualiza' => 'dados', 'tipo' => $tipo])}}" id="formulario-dados" novalidate="novalidate">
                    @csrf 
                    @method('post')
                        <div class="row">
                            @php 
                                $data = DB::table('cadastro_empresas')->select('empresa_codigo', 'empresa_nome')->where('empresa_codigo', $dadosUsuario[0]['usuario_empresa'])->get();

                                $new_array1 =[];
                                $new_array2 =[];

                                foreach ($data as $empresa) {
                                    $new_array1[] = $empresa->empresa_codigo;
                                    $new_array2[] = $empresa->empresa_codigo.' - '.$empresa->empresa_nome;
                                }
                                $array_opt = array_combine($new_array1, $new_array2);
                            @endphp
                            <!-- Empresa -->
                            <x-adminlte-select name="empUsuario" fgroup-class="col-md-4">
                                <x-slot name="label">
                                    Empresa <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="$array_opt" empty-option="Selecione..." selected="{{$dadosUsuario[0]['usuario_empresa']}}"/>
                            </x-adminlte-select>

                            <!-- Tipo Usuario -->
                            <x-adminlte-select name="tipoUsuario" fgroup-class="col-md-4" disabled>
                                <x-slot name="label">
                                    Tipo de Usuário <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="['A' => 'Administrador', 'P' => 'Padrão']" empty-option="Selecione..." selected="{{$dadosUsuario[0]['usuario_tipo']}}"/>
                            </x-adminlte-select>

                            <!-- Status do Usuario -->
                            <x-adminlte-select name="statusUsuario" fgroup-class="col-md-4">
                                <x-slot name="label">
                                    Status <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="['A' => 'Ativo', 'D' => 'Desativado']" empty-option="Selecione..." selected="{{$dadosUsuario[0]['usuario_status']}}"/>
                            </x-adminlte-select>
                        </div>

                        <div class="row">
                            <!-- Código do Usuário -->
                            <x-adminlte-input name="codigo" type="text" fgroup-class="col-md-4" value="{{$dadosUsuario[0]['usuario_codigo']}}" readonly>
                                <x-slot name="label">
                                    Código do Usuário <span style="color:red;">*</span>
                                </x-slot>
                            </x-adminlte-input>

                            <!-- Nome -->
                            <x-adminlte-input name="nome" type="text" placeholder="Nome do Usuário" fgroup-class="col-md-4" value="{{$dadosUsuario[0]['name']}}">
                                <x-slot name="label">
                                    Nome do Usuário <span style="color:red;">*</span>
                                </x-slot>
                            </x-adminlte-input>

                            <!-- CPF -->
                            <x-adminlte-input name="cpf" type="text" fgroup-class="col-md-4" value="{{$dadosUsuario[0]['usuario_cpf'] }}">
                                <x-slot name="label">
                                    CPF <span style="color:red;">*</span>
                                </x-slot>
                            </x-adminlte-input>
                        </div>

                        <div class="row">

                            <!-- RG -->
                            <x-adminlte-input name="rg" type="text" label="RG" fgroup-class="col-md-4" value="{{$dadosUsuario[0]['usuario_rg'] }}"></x-adminlte-input>

                            @php
                                $config = Helper::dtRangeDataPtBR();
                                
                                if(!empty($dadosUsuario[0]['usuario_data_nascimento'])){
                                    $data_nascimento = date('d/m/Y', strtotime($dadosUsuario[0]['usuario_data_nascimento']));
                                }else{
                                    $data_nascimento = '';
                                }
                            @endphp
                            <!-- Data de Nascimento -->
                            <x-adminlte-date-range name="dataNascimento" label="Data de Nascimento" :config="$config" placeholder="Formato dia/mês/ano" fgroup-class="col-md-4">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="far fa-lg fa-calendar-alt"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-date-range>
                            @push('js')<script>$(() => $("#dataNascimento").val('{{ $data_nascimento }}'))</script>@endpush

                            <!-- Sexo -->
                            <x-adminlte-select name="sexo" label="Sexo" fgroup-class="col-md-4">
                                <x-adminlte-options :options="['M' => 'Masculino', 'F' => 'Feminino']" empty-option="Selecione..." selected="{{$dadosUsuario[0]['usuario_sexo'] }}" />
                            </x-adminlte-select>
                        </div>

                        <div class="d-flex justify-content-center">
                            <x-adminlte-button class="btn-nexus" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                        </div>
                    </form>
                </div>

                <!-- Aba dos dados da senha do usuario -->
                <div class="tab-pane fade" id="custom-tabs-two-senha" role="tabpanel" aria-labelledby="custom-tabs-two-senha">
                    <form method="post" action="{{route('usuario.atualizar', ['usuario' => $dadosUsuario[0]['id'], 'usuario_cod' => $dadosUsuario[0]['usuario_codigo'], 'atualiza' => 'senha', 'tipo' => $tipo])}}" id="formulario-senha" novalidate="novalidate">
                    @csrf 
                    @method('post')
                        <div class="row">
                            <div class="d-flex justify-content-center col-md-12">
                                <div class="col-md-4">
                                    <x-adminlte-callout title="Redefinir Senha do Usuário" theme="" class="callout-nexus">
                                        <div class="text-muted">
                                            <div class="row">
                                                <p class="text-sm col-md-6">Usuário
                                                    <b class="d-block">{{ $dadosUsuario[0]['usuario_codigo'].' - '.$dadosUsuario[0]['name'] }}</b>
                                                </p>
                                                <p class="text-sm col-md-6">Email
                                                    <b class="d-block">{{ $dadosUsuario[0]['email'] }}</b>
                                                </p>
                                            </div>
                                        </div>
                                        </br>
                                        <!-- Senha -->
                                        <x-adminlte-input name="novaSenha" type="password" placeholder="Nova Senha" igroup-size="md" fgroup-class="col-md-12" autocomplete="new-password">
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text x-slot-nexus">
                                                    <i class="fa-solid fa-key"></i>
                                                </div>
                                            </x-slot>
                                            <x-slot name="label">
                                                Nova Senha <span style="color:red;">*</span>
                                            </x-slot>
                                            <x-slot name="appendSlot">
                                                <x-adminlte-button class="toggle-password btn-outline-nexus" theme="" data-target="novaSenha" icon="fa fa-eye"/>
                                            </x-slot>
                                        </x-adminlte-input>
                                        <x-adminlte-input name="novaSenha2" type="password" placeholder="Confirmar Nova Senha" igroup-size="md" fgroup-class="col-md-12" autocomplete="new-password">
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text x-slot-nexus">
                                                    <i class="fa-solid fa-key"></i>
                                                </div>
                                            </x-slot>
                                            <x-slot name="label">
                                                Confirmar Nova Senha <span style="color:red;">*</span>
                                            </x-slot>
                                            <x-slot name="appendSlot">
                                                <x-adminlte-button class="toggle-password btn-outline-nexus" theme="" data-target="novaSenha2" icon="fa fa-eye"/>
                                            </x-slot>
                                        </x-adminlte-input>
                                    </x-adminlte-callout>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center">
                            <x-adminlte-button class="btn-nexus" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                        </div>
                    </form>
                </div>

                <!-- Aba dos dados do Contato do cliente -->
                <div class="tab-pane fade" id="custom-tabs-two-contato" role="tabpanel" aria-labelledby="custom-tabs-two-contato-tab">
                    <form method="post" action="{{route('usuario.atualizar', ['usuario' => $dadosUsuario[0]['id'], 'usuario_cod' => $dadosUsuario[0]['usuario_codigo'], 'atualiza' => 'contato', 'tipo' => $tipo])}}" id="formulario-contato" novalidate="novalidate">
                    @csrf 
                    @method('post')
                        <div class="row">
                            <!-- Telefone Residencial -->
                            <x-adminlte-input name="telResidencial" type="text" label="Telefone Residencial" fgroup-class="col-md-6" value="{{$dadosUsuario[0]['usuario_tel_residencial'] }}">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>

                            <!-- Telefone Celular -->
                            <x-adminlte-input name="telCelular" type="text" label="Telefone Celular" fgroup-class="col-md-6" value="{{$dadosUsuario[0]['usuario_tel_celular'] }}">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fa-solid fa-mobile-retro"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>

                        <div class="row">
                            <!-- Tipo do Email -->
                            <x-adminlte-select name="tipoEmail" fgroup-class="col-md-4">
                                <x-slot name="label">
                                    Tipo do Email <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="['P' => 'Pessoal', 'C' => 'Comercial']" empty-option="Selecione..." selected="{{$dadosUsuario[0]['usuario_tipo_email']}}"/>
                            </x-adminlte-select>

                            <!-- Email -->
                            <x-adminlte-input name="email" type="email" placeholder="email@exemplo.com" fgroup-class="col-md-8" value="{{$dadosUsuario[0]['email']}}">
                                <x-slot name="label">
                                    Email <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fa-solid fa-envelope"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>

                        <div class="d-flex justify-content-center">
                            <x-adminlte-button class="btn-nexus" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                        </div>
                    </form>
                </div>

                <!-- Aba dos dados do Endereço do usuario -->
                <div class="tab-pane fade" id="custom-tabs-two-endereco" role="tabpanel" aria-labelledby="custom-tabs-two-endereco-tab">
                    <div class="main col-md-12" style="display: flex;flex-direction: column;"> 

                        @php
                            //Busca os dados dos endereços cadastrados do usuario
                            $data = DB::table('cadastro_usuario_enderecos')->where('endereco_usuario_codigo','=',$dadosUsuario[0]['usuario_codigo'])->orderBy('endereco_principal', 'desc')->orderBy('endereco_seq', 'asc')->get();

                            if(empty($data[0])){
                        @endphp
                        <!-- Se ainda não foi cadastrado endereço para o usuario cria card vazio -->
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

                                    if(empty($endereco->endereco_numero)){
                                        $numero = 'S/N';
                                    }else{
                                        $numero = $endereco->endereco_numero;
                                    }
                                @endphp
                                <div class="col-md-4" style="float: left;">
                                    <!-- Card do Endereço do usuario -->
                                    <x-adminlte-card :title="$titulo" :icon="$icone" theme="" theme-mode="outline" header-class="card-outline-nexus">
                                        <i>{{ $endereco->endereco_logradouro }}, {{ $numero }}</br>
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
                                            <form method="post" action="{{ route('enderecoUsuario.destroy', ['endereco' => $endereco->endereco_id, 'tipo' => $tipo]) }}" style="float: left;" >
                                            @csrf 
                                            @method('delete')
                                                <x-adminlte-button class="btn-sm" theme="danger" icon="fa fa-lg fa-fw fa-trash" type="submit" title="Excluir Endereço" style="margin-right: 5px;"/>
                                            </form>
                                            @php
                                                if($endereco->endereco_principal == "N"){
                                                    $endPrincipal = json_encode($endereco);
                                            @endphp
                                            <form method="get" action="{{ route('enderecoUsuario.principal', ['endereco' => $endereco->endereco_id, 'usuario_cod' => $endereco->endereco_usuario_codigo, 'tipo' => $tipo]) }}" style="float: left;">
                                            @csrf 
                                            @method('get')
                                                <x-adminlte-button class="btn-sm" label="Tornar Principal" theme="success" icon="fa-solid fa-location-dot" type="submit" title="Tornar Principal"/>
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
                           
                        <!-- Gera o Modal com os campos da inserção dos dados do endereço do usuario -->
                        <div>
                            <form method="post" action="{{route('enderecoUsuario.inserir', ['tipo' => $tipo])}}" id="formulario-endereco" novalidate="novalidate">
                            @csrf 
                            @method('post')    
                                <!-- Criação do Modal -->                           
                                <x-adminlte-modal id="modalCustom" title="Novo Endereço" size="lg" theme="modal-nexus" icon="fa-solid fa-address-book" v-centered static-backdrop scrollable>
                                    <div style="height:400px;">
                                        <!-- Campos escondidoscom o id e codigo do usuario para o request -->  
                                        <input id="usuario_codigo" type="hidden" value="{{ $dadosUsuario[0]['usuario_codigo'] }}" name="usuario_codigo">
                                        <input id="ibgeCodMun" type="hidden" name="ibgeCodMun">
                                    
                                        <!-- CEP -->
                                        <x-adminlte-input name="cep" type="text" fgroup-class="col-md-4">
                                            <x-slot name="label">
                                                CEP <span style="color:red;">*</span>
                                            </x-slot>
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text">
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
                                                    <div class="input-group-text">
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
                                                    <div class="input-group-text">
                                                        <i class="fa-solid fa-hashtag"></i>
                                                    </div>
                                                </x-slot>
                                            </x-adminlte-input>
                                        </div>
                                            
                                        <div class="row">
                                            <!-- Complemento -->
                                            <x-adminlte-input name="complemento" type="text" label="Complemento" fgroup-class="col-md-6"></x-adminlte-input>

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
                                                    <div class="input-group-text">
                                                        <i class="fa-solid fa-city"></i>
                                                    </div>
                                                </x-slot>
                                            </x-adminlte-input>

                                            @php
                                                $dados_ibge = DB::table('ibge_estados')->orderby('ibge_sigla')->get();

                                                $new_array1 =[];
                                                $new_array2 =[];

                                                foreach ($dados_ibge as $ibge) {
                                                    $new_array1[] = $ibge->ibge_sigla;
                                                    $new_array2[] = $ibge->ibge_sigla.' - '.$ibge->ibge_nome;
                                                }
                                                $array_opt = array_combine($new_array1, $new_array2);
                                            @endphp

                                            <!-- Estado -->
                                            <x-adminlte-select name="uf" fgroup-class="col-md-3">
                                                <x-slot name="label">
                                                    UF <span style="color:red;">*</span>
                                                </x-slot>
                                                <x-adminlte-options :options="$array_opt" empty-option="Selecione..."/>
                                            </x-adminlte-select>

                                            <!-- Pais -->
                                            <x-adminlte-input name="pais" type="text" fgroup-class="col-md-3">
                                                <x-slot name="label">
                                                    País <span style="color:red;">*</span>
                                                </x-slot>
                                            </x-adminlte-input>
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
                                <x-adminlte-button label="Novo Endereço" data-toggle="modal" data-target="#modalCustom" class="btn-nexus" icon="fa-solid fa-address-book"/>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aba das permissões do usuario -->
                <div class="tab-pane fade" id="custom-tabs-two-permissoes" role="tabpanel" aria-labelledby="custom-tabs-two-permissoes-tab">
                    <form method="post" action="{{route('usuario.atualizar', ['usuario' => $dadosUsuario[0]['id'], 'usuario_cod' => $dadosUsuario[0]['usuario_codigo'], 'atualiza' => 'permissao', 'tipo' => $tipo])}}" id="formulario-permissao" novalidate="novalidate">
                    @csrf 
                    @method('post')

                        <div class="post">
                            <h4 class="text-secondary font-weight-bold">Módulos do Sistema</h4>
                        </div>

                        <div class="row">
                            <!-- Usuario tem acesso aos parametros gerais -->
                            <x-adminlte-select name="acessoParametros" fgroup-class="col-md-6">
                                <x-slot name="label">
                                    Acessa Área de Parametrização Geral <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" empty-option="Selecione..." selected="{{$dadosUsuario[0]['usuario_acesso_pararametros']}}"/>
                            </x-adminlte-select>

                            <!-- Usuario tem acesso aos cadastros -->
                            <x-adminlte-select name="acessoCadastros" label="Acessa Área de Cadastros" fgroup-class="col-md-6">
                                <x-slot name="label">
                                    Acessa Área de Cadastros <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" empty-option="Selecione..." selected="{{$dadosUsuario[0]['usuario_acesso_cadastros']}}"/>
                            </x-adminlte-select>
                        </div>

                        <div class="row">
                            <!-- Usuario tem acesso ao modulo de serviços -->
                            <x-adminlte-select name="acessoModSrv" label="Acessa o Módulo de Serviços" fgroup-class="col-md-6">
                                <x-slot name="label">
                                    Acessa o Módulo de Serviços <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" empty-option="Selecione..." selected="{{$dadosUsuario[0]['usuario_acesso_mod_servicos']}}"/>
                            </x-adminlte-select>

                            <!-- Usuario tem acesso ao modulo de emissão de NF -->
                            <x-adminlte-select name="acessoModNf" fgroup-class="col-md-6">
                                <x-slot name="label">
                                    Acessa o Módulo de Emissão de NFS-e <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" empty-option="Selecione..." selected="{{$dadosUsuario[0]['usuario_acesso_mod_nf']}}"/>
                            </x-adminlte-select>
                        </div>

                        <div class="post">
                            <h4 class="text-secondary font-weight-bold">Permissões no Sistema</h4>
                        </div>

                        <div class="row">
                            <!-- Usuario Tem permissão de autorizar desconto acima do permitido -->
                            <x-adminlte-select name="autorizaDesconto" fgroup-class="col-md-6">
                                <x-slot name="label">
                                    Permissão de Autorização de Desconto <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" empty-option="Selecione..." selected="{{$dadosUsuario[0]['usuario_aut_desc']}}"/>
                            </x-adminlte-select>

                            <!-- Usuario tem permissão de alterar permissoes -->
                            <x-adminlte-select name="altPerAcesso" fgroup-class="col-md-6">
                                <x-slot name="label">
                                    Altera Permissões de Acesso dos Usuários <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" empty-option="Selecione..." selected="{{$dadosUsuario[0]['usuario_altera_permissoes_acesso']}}"/>
                            </x-adminlte-select>
                        </div>

                        <div class="d-flex justify-content-center">
                            <x-adminlte-button class="btn-nexus" id="btn-submit-permissao" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between w-100">
                <div class="d-flex">
                    <form method="get" action="{{ route('usuario.cadastro', ['tipo' => $tipo]) }}" style="float: left; margin-right: 2px;">
                    @csrf 
                        <x-adminlte-button class="btn-nexus" label="Novo Usuário" theme="" icon="fas fa-user-plus" type="submit"/>
                    </form>
                    <form method="post" action="{{ route('usuario.destroy', ['usuario' => $dadosUsuario[0]]) }}" style="float: left;margin-left: 2px;">
                    @csrf 
                    @method('delete')
                        <x-adminlte-button class="btn-nexus" label="Excluir Usuário" theme="" icon="fa-solid fa-user-xmark" type="submit"/>
                    </form>
                </div>
                <div class="d-flex">
                    <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.usuarios') }}'" label="Voltar" theme="" icon=""/>
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
@section('plugins.Sweetalert2', true)
@section('plugins.toastr', true)
@section('plugins.jqueryValidation', true)
@section('plugins.DateRangePicker', true)
@section('plugins.Inputmask', true)

@section('js')
<script>
    $(document).ready(function() {

        // Init input mask on the target element.

        $('#telCelular').inputmask({
            "mask": "(99) 9 9999-9999",
             // Specify other options...
        });

        $('#telResidencial').inputmask({
            "mask": "(99) 9999-9999",
            // Specify other options...
        });

        $('#cep').inputmask({
            "mask": "99999-999",
            // Specify other options...
        });

        $('#cpf').inputmask({
            "mask": "999.999.999-99",
            // Specify other options...
        });

        $('#rg').inputmask({
            "mask": "99.999.999-9",
            // Specify other options...
        });

        $("#acessoCadastros").change(function(){

            if($("#acessoCadastros").val() == 'N'){
                $("#altPerAcesso").val('N');
                $("#altPerAcesso").attr("disabled", true);
            }else{
                $("#altPerAcesso").val('N');
                $("#altPerAcesso").attr("disabled", false);
            }
        });

        //Verifica se o usuario logado pode alterar as permissões
        var altera_permissoes_acesso = {!! json_encode($altera_permissoes_acesso) !!};
        var tipo_usuario = {!! json_encode($tipo_usuario) !!};

        if(altera_permissoes_acesso == 'N' || tipo_usuario != 'A'){
            $("#btn-submit-permissao").hide();
            $("#acessoCadastros").attr("disabled", true);
            $("#altPerAcesso").attr("disabled", true);
            $("#acessoParametros").attr("disabled", true);
            $("#acessoModSrv").attr("disabled", true);
            $("#acessoModNf").attr("disabled", true);
            $("#autorizaDesconto").attr("disabled", true);
        }else{
            if($("#acessoCadastros").val() == 'N'){
                $("#altPerAcesso").val('N');
                $("#altPerAcesso").attr("disabled", true);
            }
        }

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
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePasswordButtons = document.querySelectorAll('.toggle-password');

        togglePasswordButtons.forEach(button => {
            button.addEventListener('click', function () {
                const targetInput = document.querySelector(`input[name="${this.getAttribute('data-target')}"]`);
                const icon = this.querySelector('i');

                if (targetInput.type === 'password') {
                    targetInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    targetInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    });
</script>

<script>
$(function () {
    $('#formulario-dados').validate({
        rules: {
            statusUsuario: {
                required: true
            },
            nome: {
                required: true,
                minlength: 5
            },
            tipoUsuario: {
                required: true
            },
            tipoEmail: {
                required: true
            },
            empUsuario: {
                required: true
            },
            cpf: {
                required: true
            },
            codigo: {
                required: true
            },
        },
        messages: {
            statusUsuario: {
                required: "Por Favor informe um Tipo de Cadastro"
            },
            nome: {
                required: "Por Favor informe o Nome do Usuario",
                minlength: "Infome no mínimo 5 caracteres"
            },
            tipoUsuario: {
                required: "Por Favor o Tipo do Usuário"
            },
            empUsuario: {
                required: "Por Favor informe a Empresa"
            },
            cpf: {
                required: "Por Favor informe o CPF"
            },
            codigo: {
                required: "Por Favor informe o Código do Usuário"
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

$(document).ready(function() {
    // Adiciona a validação ao formulário
    $("#formulario-senha").validate({
        rules: {
            novaSenha: {
                required: true,
                minlength: 6
            },
            novaSenha2: {
                required: true,
                minlength: 6,
                equalTo: "#novaSenha"
            }
        },
        messages: {
            novaSenha: {
                required: "Por favor, insira a nova senha.",
                minlength: "A senha deve ter pelo menos 6 caracteres."
            },
            novaSenha2: {
                required: "Por favor, confirme a nova senha.",
                minlength: "A senha deve ter pelo menos 6 caracteres.",
                equalTo: "As senhas não coincidem."
            }
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.input-group').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
});

$(function () {
    $('#formulario-contato').validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            tipoEmail: {
                required: true,
            },
        },
        messages: {
            email: {
                required: "Por Favor informe um Email",
                email: "Informe um email válido"
            },
            tipoEmail: {
                required: "Por Favor informe o Tipo do Email"
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

$(function () {
    $('#formulario-permissao').validate({
        rules: {
            acessoCadastros: {
                required: true
            },
            altPerAcesso: {
                required: true
            },
            acessoParametros: {
                required: true
            },
            acessoModSrv: {
                required: true
            },
            acessoModNf: {
                required: true
            },
            autorizaDesconto: {
                required: true
            },
        },
        messages: {
            acessoCadastros: {
                required: "Por Favor informe se o Usuário Acessa a Área de Cadastros"
            },
            altPerAcesso: {
                required: "Por Favor informe se o Usuário Altera Permissões de Acesso"
            },
            acessoParametros: {
                required: "Por Favor informe se o Usuário Acessa a Área de Parametrização Geral"
            },
            acessoModSrv: {
                required: "Por Favor informe se o Usuário Acessa o Módulo de Serviços"
            },
            acessoModNf: {
                required: "Por Favor informe se o Usuário Acessa o Módulo de NF"
            },
            autorizaDesconto: {
                required: "Por Favor informe se o Usuário tem Permissão de Autorização de Desconto"
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
        required: "Por Favor informe o Número para o Endereço",
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
