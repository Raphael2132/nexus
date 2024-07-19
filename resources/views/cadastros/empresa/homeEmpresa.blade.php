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
<div class="row">
    <div class="col-md-8"> 
        {{-- Setup data for datatables --}}
        @php
        $heads = [
            ['label' => '', 'no-export' => true, 'width' => 5],
            'Código',
            'Nome',
            'CNPJ',
            ['label' => 'Opções', 'no-export' => true, 'width' => 15],
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
            'columns' => [['orderable' => false], null, null, null,['orderable' => false]],
        ];
        @endphp
        <x-adminlte-card  title="Empresas Cadastradas" theme="navy" theme-mode="outline">
            <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable compressed>
                @foreach ($empresas as $empresa)
                    @php
                        $cnpj = substr($empresa->empresa_cnpj,0,2).'.'.substr($empresa->empresa_cnpj,2,3).'.'.substr($empresa->empresa_cnpj,5,3).'/'.substr($empresa->empresa_cnpj,8,4).'-'.substr($empresa->empresa_cnpj,12,2);
                    @endphp
                    <tr>
                        <td>
                            <nobr class="d-flex justify-content-center">
                                <!-- Cria o modal dos detalhes da empresa -->
                                <x-adminlte-modal id="modalCustom_{{$empresa->empresa_codigo}}" title="Detalhes da Empresa" size="xl" theme="navy" icon="fa-solid fa-building" v-centered scrollable>
                                    <div class="row" style="height:auto;">
                                        <!-- Conteudo da esquerda do modal -->
                                        <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                                            <div class="post">
                                                <h4 class="text-primary">Dados Gerais da Empresa</h4>
                                                @php
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
                                                <div class="text-muted">
                                                    <p class="text-sm">Empresa
                                                        <b class="d-block">{{ $empresa->empresa_codigo }} - {{ $empresa->empresa_nome }}</b>
                                                    </p>
                                                    <p class="text-sm">CNPJ
                                                        <b class="d-block">{{ $cnpj }}</b>
                                                    </p>
                                                    <p class="text-sm">Inscrição Estadual
                                                        <b class="d-block">{{ $inscEstad }}</b>
                                                    </p>
                                                    <p class="text-sm">Inscrição Municipal
                                                        <b class="d-block">{{ $inscMuni }}</b>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="post">
                                                <h4 class="text-primary">Parametrização</h4>
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
                                <a class="text-muted" data-toggle="modal" title="Detalhes do Registro" data-target="#modalCustom_{{$empresa->empresa_codigo}}">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </a>
                            </nobr>
                        </td>
                        <td>{{ $empresa->empresa_codigo }}</td>
                        <td>{{ $empresa->empresa_nome }}</td>
                        <td>{{ $cnpj }}</td>
                        <td>
                            <nobr class="d-flex justify-content-center">
                                <form method="get" action="{{ route('empresa.editarCadastro', ['dadosEmpresa' => $empresa->empresa_codigo]) }}" style="float: left;">
                                    @csrf
                                    <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Editar Registro" value="Edit" type="submit">
                                        <i class="fa fa-lg fa-fw fa-pen"></i>
                                    </button>
                                </form>
                                <form method="post" action="{{route('empresa.destroy', ['empresa' => $empresa])}}" style="float: left;">
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
        </x-adminlte-card>
    </div>
    <div class="col-md-4"> 
        <x-adminlte-small-box title="Cadastro" text="Empresa" icon="fas fa-user-plus" theme="primary" url="{{ route('empresa.cadastro') }}" url-text="Cadastrar Empresa"/>
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
</script>
@stop
