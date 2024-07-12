@extends('adminlte::page')

@section('title', 'Emissão de Simplificada de NFS-e')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h1>Faturamento de Notas</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active"><a href="{{route('home.emissaoSimpNFS')}}">Emissão Simplificada</a></li>
                <li class="breadcrumb-item active">Formulario Emissão Simplificada</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-12">
        <form method="post" action="{{ route('emissaoSimpNFS.etapa2', ['empresa' => $empresa, 'cliente' => $cliente]) }}" id="quickForm" novalidate="novalidate">
            @csrf 
            @method('post')
            <x-adminlte-card title="Emissão Simplificada de NFS-e" theme="navy" collapsible maximizable>
                @php

                    //Dados da Empresa
                    $data_emp = DB::table('cadastro_empresas')->where('empresa_codigo', $empresa)->get();

                    $empresa_os = $empresa.' - '.$data_emp[0]->empresa_nome;
                    $cnpj = Helper::mascaraCNPJ($data_emp[0]->empresa_cnpj);

                    if(!empty($data_emp[0]->empresa_insc_estadual)){
                        $inscEstadual = $data_emp[0]->empresa_insc_estadual;
                    }else{
                        $inscEstadual = '';
                    }

                    if(!empty($data_emp[0]->empresa_insc_municipal)){
                        $inscMunicipal = $data_emp[0]->empresa_insc_municipal;
                    }else{
                        $inscMunicipal = '';
                    }

                    $dataParEmp = DB::table('parametros_fat_empresas')->where('parfat_emp', $empresa)->get();

                    if($dataParEmp[0]->parfat_sim == "S"){
                        $optSimples = 'Sim';
                    }else{
                        $optSimples = 'Não';
                    }

                    //Dados do Cliente
                    $data_cli = DB::table('cadastro_clientes')->where('cliente_codigo', $cliente)->get();

                    $cliente_os = $cliente.' - '.$data_cli[0]->cliente_nome;
                    $inscEst_cli = '';
                    $inscMun_cli = '';
                    $cnpj_cli = '';
                    $rg_cli = '';
                    $sexo_cli = '';
                    $telRes_cli = '';
                    $telCel_cli = '';
                    $telCom_cli = '';
                    $tipEma_cli = '';
                    $email_cli = '';

                    if($data_cli[0]->cliente_tipo_cadastro == 'C'){

                        $tipoCad = 'Cliente';

                        if($data_cli[0]->cliente_tipo_pessoa == 'J'){

                            $tipoPes = 'Jurídica';

                            $cnpj_cli = Helper::mascaraCNPJ($data_cli[0]->cliente_cpf_cnpj);

                            if(!empty($data_cli[0]->cliente_insc_estadual)){
                                $inscEst_cli = $data_cli[0]->cliente_insc_estadual;
                            }
                            if(!empty($data_cli[0]->cliente_insc_municipal)){
                                $inscMun_cli = $data_cli[0]->cliente_insc_municipal;
                            }
                        }else{

                            $tipoPes = 'Fisíca';

                            if(!empty($data_cli[0]->cliente_rg)){
                                $rg_cli = Helper::mascaraRG($data_cli[0]->cliente_rg);
                            }

                            $cpf_cli = Helper::mascaraCPF($data_cli[0]->cliente_cpf_cnpj);

                            if(!empty($data_cli[0]->cliente_sexo)){
                                if($data_cli[0]->cliente_sexo == 'M'){
                                    $sexo_cli = "Masculino";
                                }else{
                                    $sexo_cli = "Feminino";
                                }
                            }
                        }
                    }else{
                        $tipoCad = 'Fornecedor';
                        $tipoPes = 'Jurídica';

                        $cnpj_cli = Helper::mascaraCNPJ($data_cli[0]->cliente_cpf_cnpj);
                    }

                    if(!empty($data_cli[0]->cliente_tel_residencial)){
                        $telRes_cli = Helper::mascaraTelResidencial($data_cli[0]->cliente_tel_residencial);
                    }
                    
                    if(!empty($data_cli[0]->cliente_tel_celular)){
                        $telCel_cli = Helper::mascaraTelCelular($data_cli[0]->cliente_tel_celular);
                    }

                    if(!empty($data_cli[0]->cliente_tel_comercial)){
                        $telCom_cli = Helper::mascaraTelComercial($data_cli[0]->cliente_tel_comercial);
                    }

                    if(!empty($data_cli[0]->cliente_tipo_email)){
                        if($data_cli[0]->cliente_tipo_email == 'P'){
                            $tipEma_cli = "Pessoal";
                        }else{
                            $tipEma_cli = "Comercial";
                        }
                    }
                    if(!empty($data_cli[0]->cliente_email)){
                        $email_cli = $data_cli[0]->cliente_email;
                    }
                @endphp
                <!-- Dados da Empresa -->
                <x-adminlte-callout theme="info" title-class="text-info text-uppercase" icon="fa-solid fa-building" title="Dados da Empresa">
                    <div class="text-muted">
                        <div class="row quebra-linha">
                            <p class="text-sm col-md-3">Empresa
                                <b class="d-block">{{ $empresa_os }}</b>
                            </p>
                            <p class="text-sm col-md-3">CNPJ
                                <b class="d-block">{{ $cnpj }}</b>
                            </p>
                            <p class="text-sm col-md-2">Simples Nacional
                                <b class="d-block">{{ $optSimples }}</b>
                            </p>
                            <p class="text-sm col-md-2">Inscrição Estadual
                                <b class="d-block">{{ $inscEstadual }}</b>
                            </p>
                            <p class="text-sm col-md-2">Inscrição Municipal
                                <b class="d-block">{{ $inscMunicipal }}</b>
                            </p>
                        </div>
                    </div>
                </x-adminlte-callout>
                <!-- Dados Do Cliente -->
                <x-adminlte-callout theme="info" title-class="text-info text-uppercase" icon="fa-solid fa-user-tie" title="Dados do Cliente">
                    <div class="text-muted">
                        <div class="row quebra-linha">
                            <p class="text-sm col-md-2">Cliente
                                <b class="d-block">{{ $cliente_os }}</b>
                            </p>
                            <p class="text-sm col-md-2">Tipo de Cadastro
                                <b class="d-block">{{ $tipoCad }}</b>
                            </p>
                            <p class="text-sm col-md-2">Tipo de Pessoa
                                <b class="d-block">{{ $tipoPes }}</b>
                            </p>
                            @if($data_cli[0]->cliente_tipo_pessoa == 'J')
                            <p class="text-sm col-md-2">CNPJ
                                <b class="d-block">{{ $cnpj_cli }}</b>
                            </p>
                            <p class="text-sm col-md-2">Inscrição Estadual
                                <b class="d-block">{{ $inscEst_cli }}</b>
                            </p>
                            <p class="text-sm col-md-2">Inscrição Municipal
                                <b class="d-block">{{ $inscMun_cli }}</b>
                            </p>
                            @else
                            <p class="text-sm col-md-2">CPF
                                <b class="d-block">{{ $cpf_cli }}</b>
                            </p>
                            <p class="text-sm col-md-2">RG
                                <b class="d-block">{{ $rg_cli }}</b>
                            </p>
                            <p class="text-sm col-md-2">Sexo
                                <b class="d-block">{{ $sexo_cli }}</b>
                            </p>
                            @endif
                        </div>
                        <div class="row quebra-linha">
                            <p class="text-sm col-md-2">Telefone Residencial
                                <b class="d-block">{{ $telRes_cli }}</b>
                            </p>
                            <p class="text-sm col-md-2">Telefone Celular
                                <b class="d-block">{{ $telCel_cli }}</b>
                            </p>
                            <p class="text-sm col-md-2">Telefone Comercial
                                <b class="d-block">{{ $telCom_cli }}</b>
                            </p>
                            <p class="text-sm col-md-2">Tipo do Email
                                <b class="d-block">{{ $tipEma_cli }}</b>
                            </p>
                            <p class="text-sm col-md-4">Email
                                <b class="d-block">{{ $email_cli }}</b>
                            </p>
                        </div>
                    </div>

                    <div class="post">
                        <h5 class="text-secondary font-weight-bold">Endereços Cadastrados</h5>
                    </div>

                    <div class="main col-md-12" style="display: flex;flex-direction: column;"> 

                        @php
                            //Busca os dados dos endereços cadastrados do cliente
                            $end_cli = DB::table('cadastro_cliente_enderecos')->where('endereco_cliente_codigo', $cliente)->orderby('endereco_seq')->get();
                            $cnt_end = 0;
                        @endphp
                        @if(empty($end_cli[0]))
                        <!-- Se ainda não foi cadastrado endereço para o cliente cria card vazio -->
                        <div class="col-md-4">
                            <x-adminlte-card theme="info" title="Endereço" theme-mode="outline">
                                <i>Registros não encontrados</i>
                            </x-adminlte-card>
                        </div>
                        @else
                        <!-- Cria os cards com os endereços cadastrados -->
                        <div class="col-md-12 d-flex justify-content-center">
                            @foreach ($end_cli as $endereco)
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
                                    <!-- Card do Endereço da empresa -->
                                    <x-adminlte-card theme="info" :title="$titulo" :icon="$icone" theme-mode="outline">
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
                                    </x-adminlte-card>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-md-12">
                            <div class="col-sm-6">
                                <label for="form-group">Endereço do Cliente para a NFS-e</label>
                                <div class="form-group clearfix">
                                    @php
                                        $cnt_end = 0;
                                    @endphp
                                    @foreach ($end_cli as $endereco)
                                        @php
                                            $cnt_end += 1;

                                            if($endereco->endereco_principal == "S"){
                                                $principal = ' - Principal';
                                            }else{
                                                $principal = '';
                                            }

                                        @endphp
                                        @if($endereco->endereco_principal == "S")
                                        <div class="icheck-primary d-inline" style="margin-right: 25px;">
                                            <input type="radio" id="radioPrimary_{{$endereco->endereco_seq}}" name="enderecoCli" checked value="{{$endereco->endereco_seq}}">
                                            <label for="radioPrimary_{{$endereco->endereco_seq}}">Endereço {{$cnt_end.$principal}}</label>
                                        </div>
                                        @else
                                        
                                        <div class="icheck-primary d-inline" style="margin-right: 25px;">
                                            <input type="radio" id="radioPrimary_{{$endereco->endereco_seq}}" name="enderecoCli" value="{{$endereco->endereco_seq}}">
                                            <label for="radioPrimary_{{$endereco->endereco_seq}}">Endereço {{$cnt_end.$principal}}</label>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </x-adminlte-callout>
                <x-slot name="footerSlot">
                    <x-adminlte-button class="btn-flat" type="submit" label="Prosseguir" theme="info" icon="fa-solid fa-diagram-next"/>
                </x-slot>
            </x-adminlte-card>
        </form>
    </div>
</div>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.Sweetalert2', true)
@section('plugins.toastr', true)
@section('plugins.jqueryValidation', true)
@section('plugins.icheckBootstrap', true)

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
