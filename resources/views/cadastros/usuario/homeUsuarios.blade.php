@extends('adminlte::page')

@section('title', 'Informações Gerais dos Clientes')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h1>Cadastros</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Usuarios</li>
            </ol>
        </div>
    </div>
@stop


@section('content')

<div class="row">
    <div class="col-md-3"> 
        <x-adminlte-small-box :title="$usuTot" text="Total" icon="fas fa-users" theme="info" url="{{ route('usuarios',['tipo' => 'T']) }}" url-text="Detalhes de Todos Usuários"/>
    </div>
    <div class="col-md-3"> 
        <x-adminlte-small-box :title="$usuAtivo" text="Ativos" icon="fas fa-user-check" theme="success" url="{{ route('usuarios',['tipo' => 'A']) }}" url-text="Detalhes de Usuários Ativos"/>
    </div>
    <div class="col-md-3"> 
        <x-adminlte-small-box :title="$usuDesat" text="Desativados" icon="fas fa-user-lock" theme="danger" url="{{ route('usuarios',['tipo' => 'D']) }}" url-text="Detalhes de Usuários Desativados"/>
    </div>
    <div class="col-md-3"> 
        <x-adminlte-small-box title="Cadastro" text="Usuários" icon="fas fa-user-plus" theme="primary" url="{{ route('usuario.cadastro', ['tipo' => 'newCad']) }}" url-text="Cadastrar Usuário"/>
    </div>
</div>
    
<div class="row">
    <div class="col-md-9"> 
        {{-- Setup data for datatables --}}
        @php
        $heads = [
            ['label' => '', 'no-export' => true, 'width' => 5],
            'Código',
            'Nome',
            'Tipo do Usuário',
            'Status',
            ['label' => 'Opção', 'no-export' => true, 'width' => 5],
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
            'columns' => [['orderable' => false], null, null, null, null, ['orderable' => false]],
        ];
        @endphp

        <x-adminlte-card title="Lista de Usuários Cadastrados" theme="navy" theme-mode="outline">
            <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable>
                @foreach ($usuarios as $usuario)
                    @php
                        if($usuario->usuario_tipo == 'A'){
                            $tip_usu = 'Administrador';
                        }else{
                            $tip_usu = 'Padrão';
                        }
                        if($usuario->usuario_status == 'A'){
                            $sts_usu = 'Ativo';
                        }else{
                            $sts_usu = 'Desativado';
                        }
                    @endphp
                    <tr>
                        <td>
                            <nobr class="d-flex justify-content-center">
                                <!-- Cria o modal dos detalhes do usuario -->
                                <x-adminlte-modal id="modalCustom_{{$usuario->usuario_codigo}}" title="Detalhes do Usuario" size="xl" theme="navy" icon="fa-solid fa-building" v-centered scrollable>
                                    <div class="row" style="height:auto;">
                                        <!-- Conteudo da esquerda do modal -->
                                        <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                                            <div class="post">
                                                <h4 class="text-primary">Dados Gerais do Usuário</h4>
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

                                                    if($usuario->usuario_tipo == 'A'){
                                                        $tipo = "Administrador";
                                                    }else{
                                                        $tipo = "Padrão";
                                                    }

                                                    if($usuario->usuario_status == 'A'){
                                                        $status = "Ativo";
                                                    }else{
                                                        $status = "Desativado";
                                                    }

                                                    if($usuario->usuario_acesso_cadastros == 'S'){
                                                        $acessaCadastro = "Sim";
                                                    }else{
                                                        $acessaCadastro = "Não";
                                                    }

                                                    if($usuario->usuario_altera_permissoes_acesso == 'S'){
                                                        $alteraPermissao = "Sim";
                                                    }else{
                                                        $alteraPermissao = "Não";
                                                    }

                                                    if($usuario->usuario_acesso_pararametros == 'S'){
                                                        $acessaParametros = "Sim";
                                                    }else{
                                                        $acessaParametros = "Não";
                                                    }
                                                @endphp
                                                <div class="text-muted">
                                                    <div class="row">
                                                        <p class="text-sm col-md-6">Tipo do Usuário
                                                            <b class="d-block">{{ $tipo }}</b>
                                                        </p>
                                                        <p class="text-sm col-md-6">Status do Usuário
                                                            <b class="d-block">{{ $status }}</b>
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
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="post">
                                                <h4 class="text-primary">Parametrização</h4>
                                                <div class="text-muted">
                                                    <div class="row">
                                                        <p class="text-sm col-md-6">Acessa Área de Cadastros
                                                            <b class="d-block">{{ $acessaCadastro }}</b>
                                                        </p>
                                                        <p class="text-sm col-md-6">Altera Permissões de Acesso dos Usuários
                                                            <b class="d-block">{{ $alteraPermissao }}</b>
                                                        </p>
                                                    </div>
                                                    <div class="row">
                                                        <p class="text-sm col-md-6">Acessa Área de Parametrização Geral
                                                            <b class="d-block">{{ $acessaParametros }}</b>
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
                                <a class="text-muted" data-toggle="modal" title="Visualisar Detalhes" data-target="#modalCustom_{{$usuario->usuario_codigo}}">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </a>
                            </nobr>
                        </td>   
                        <td>{{ $usuario->usuario_codigo }}</td>
                        <td>{{ $usuario->name }}</td>
                        <td>{{ $tip_usu }}</td>
                        <td>{{ $sts_usu }}</td>
                        <td>
                            <nobr class="d-flex justify-content-center">
                                <form method="get" action="{{ route('usuario.editarCadastro', ['dadosUsuario' => $usuario->usuario_codigo, 'tipo' => 'editCad']) }}" style="float: left;">
                                    @csrf 
                                    <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Editar Registro" value="Edit" type="submit">
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

    <div class="col-md-3"> 
        <x-adminlte-info-box title="Administrador" :text="$usuAdm" icon="fa-solid fa-user-shield text-dark" url="{{ route('usuarios',['tipo' => 'ADM']) }}" theme="warning"/>
        <x-adminlte-info-box title="Usuario Padrão" :text="$usuPdr" icon="fa-solid fa-circle-user text-dark" url="{{ route('usuarios',['tipo' => 'P']) }}" theme="gradient-teal"/>
    </div>
</div>
@stop

<!-- Chamada dos Plugins usados na app -->  
@section('plugins.Sweetalert2', true)
@section('plugins.toastr', true)

@section('css')
@stop

@section('js')
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
