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
            @php   
                $dadosEmp = Helper::buscaDadosEmpresa(session('glo_empresa_exibicao_home'));
            @endphp
            <li class="breadcrumb-item active">{{ $dadosEmp->empresa_codigo.' - '.$dadosEmp->empresa_nome}}</li>
        </ol>
    </div>
</div>
@stop


@section('content')

<div class="row">
    <div class="col-md-3"> 
        <x-adminlte-small-box :title="$totalPrestadores" text="Total" icon="fas fa-users-gear" theme="info" url="{{ route('prestador.consulta',['tipo' => 'T']) }}" url-text="Detalhes de Todos Prestadores"/>
    </div>
    <div class="col-md-3"> 
        <x-adminlte-small-box :title="$prestadoresAtivos" text="Ativos" icon="fas fa-user-check" theme="success" url="{{ route('prestador.consulta',['tipo' => 'A']) }}" url-text="Detalhes de Prestadores Ativos"/>
    </div>
    <div class="col-md-3"> 
        <x-adminlte-small-box :title="$prestadoresDemitidos" text="Demitidos" icon="fas fa-user-xmark" theme="danger" url="{{ route('prestador.consulta',['tipo' => 'D']) }}" url-text="Detalhes de Prestadores Demitidos"/>
    </div>
    <div class="col-md-3"> 
        <x-adminlte-small-box title="Cadastro" text="Prestadores" icon="fas fa-user-plus" theme="primary" url="{{ route('prestador.cadastro') }}" url-text="Cadastrar Prestador"/>
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
        
        $config = [
            'lengthMenu' => [ 5, 10, 25, 50],
            'pageLength' => 5,
            'language' => Helper::dataTableLangPtBR(),
            'order' => [[1, 'asc'],[2, 'asc']],
            'columns' => [['orderable' => false], null, null, null, null, null, null, ['orderable' => false]],
        ];
        @endphp

        <x-adminlte-card title="Lista de Prestadores Ativos" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
            <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable with-buttons>
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
                                <a class="text-muted" data-toggle="modal" title="Visualisar Detalhes" data-target="#modalCustom_{{$prestador->prestador_codigo}}">
                                    <i class="fa-solid fa-magnifying-glass"></i>
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
                                <form method="get" action="{{route('prestador.editarCadastro', ['dadosPrestador' => $prestador->prestador_codigo, 'empresa' => $prestador->prestador_empresa, 'tipo' => ' '])}}" style="float: left;">
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
