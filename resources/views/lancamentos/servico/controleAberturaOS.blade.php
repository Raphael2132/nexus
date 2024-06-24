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
                <li class="breadcrumb-item active">Abertura de OS</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-12">
        <form method="post" action="{{route('emissaoOS.abreOS', ['empresa' => $empresa, 'cliente' => $cliente])}}" id="quickForm2" novalidate="novalidate">
        @csrf 
        @method('post')
            <x-adminlte-card title="Abertura de Ordem de Serviço" theme="navy" theme-mode="outline" collapsible maximizable>
                @php
                    $data_emp = DB::table('cadastro_empresas')->where('empresa_codigo', $empresa)->get();

                    $empresa_os = $empresa.' - '.$data_emp[0]->empresa_nome;
                    $cnpj = substr($data_emp[0]->empresa_cnpj,0,2).'.'.substr($data_emp[0]->empresa_cnpj,2,3).'.'.substr($data_emp[0]->empresa_cnpj,5,3).'/'.substr($data_emp[0]->empresa_cnpj,8,4).'-'.substr($data_emp[0]->empresa_cnpj,12,2);

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
                            $cnpj_cli = substr($data_cli[0]->cliente_cpf_cnpj,0,2).'.'.substr($data_cli[0]->cliente_cpf_cnpj,2,3).'.'.substr($data_cli[0]->cliente_cpf_cnpj,5,3).'/'.substr($data_cli[0]->cliente_cpf_cnpj,8,4).'-'.substr($data_cli[0]->cliente_cpf_cnpj,12,2);
                            if(!empty($data_cli[0]->cliente_insc_estadual)){
                                $inscEst_cli = $data_cli[0]->cliente_insc_estadual;
                            }
                            if(!empty($data_cli[0]->cliente_insc_municipal)){
                                $inscMun_cli = $data_cli[0]->cliente_insc_municipal;
                            }
                        }else{
                            if(!empty($data_cli[0]->cliente_rg)){
                                $rg_cli = substr($data_cli[0]->cliente_rg,0,2).'.'.substr($data_cli[0]->cliente_rg,2,3).'.'.substr($data_cli[0]->cliente_rg,5,3).'-'.substr($data_cli[0]->cliente_rg,-1,1);
                            }

                            $cpf_cli = substr($data_cli[0]->cliente_cpf_cnpj,0,3).'.'.substr($data_cli[0]->cliente_cpf_cnpj,3,3).'.'.substr($data_cli[0]->cliente_cpf_cnpj,6,3).'-'.substr($data_cli[0]->cliente_cpf_cnpj,9,2);

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
                    }

                    if($data_cli[0]->cliente_tipo_pessoa == 'F'){
                        $tipoPes = 'Fisíca';
                    }else{
                        $tipoPes = 'Jurídica';
                    }

                    if(!empty($data_cli[0]->cliente_tel_residencial)){
                        $telRes_cli = "(".substr($data_cli[0]->cliente_tel_residencial,0,2).") ".substr($data_cli[0]->cliente_tel_residencial,2,4)."-".substr($data_cli[0]->cliente_tel_residencial,-4,4);
                    }
                    
                    if(!empty($data_cli[0]->cliente_tel_celular)){
                        $telCel_cli = "(".substr($data_cli[0]->cliente_tel_celular,0,2).") ".substr($data_cli[0]->cliente_tel_celular,2,1)." ".substr($data_cli[0]->cliente_tel_celular,3,4)."-".substr($data_cli[0]->cliente_tel_celular,-4,4);
                    }

                    if(!empty($data_cli[0]->cliente_tel_comercial)){
                        $telCom_cli = "(".substr($data_cli[0]->cliente_tel_comercial,0,2).") ".substr($data_cli[0]->cliente_tel_comercial,2,4)."-".substr($data_cli[0]->cliente_tel_comercial,-4,4);
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

                <x-adminlte-callout theme="info" title-class="text-info text-uppercase" icon="fa-solid fa-building" title="Dados da Empresa">
                    <div class="text-muted">
                        <div class="row quebra-linha">
                            <p class="text-sm col-md-4">Empresa
                                <b class="d-block">{{ $empresa_os }}</b>
                            </p>
                            <p class="text-sm col-md-4">CNPJ
                                <b class="d-block">{{ $cnpj }}</b>
                            </p>
                            <p class="text-sm col-md-4">Simples Nacional
                                <b class="d-block">Sim</b>
                            </p>
                        </div>
                        <div class="row quebra-linha">
                            <p class="text-sm col-md-4">Inscrição Estadual
                                <b class="d-block">{{ $inscEstadual }}</b>
                            </p>
                            <p class="text-sm col-md-4">Inscrição Municipal
                                <b class="d-block">{{ $inscMunicipal }}</b>
                            </p>
                            <p class="text-sm col-md-4">Simples Nacional
                                <b class="d-block">Sim</b>
                            </p>
                        </div>
                    </div>
                </x-adminlte-callout>

                <x-adminlte-callout theme="info" title-class="text-info text-uppercase" icon="fa-solid fa-user-tie" title="Dados do Cliente">
                    <div class="text-muted">
                        <div class="row quebra-linha">
                            <p class="text-sm col-md-4">Cliente
                                <b class="d-block">{{ $cliente_os }}</b>
                            </p>
                            <p class="text-sm col-md-4">Tipo de Cadastro
                                <b class="d-block">{{ $tipoCad }}</b>
                            </p>
                            <p class="text-sm col-md-4">Tipo de Pessoa
                                <b class="d-block">{{ $tipoPes }}</b>
                            </p>
                        </div>
                        @if($data_cli[0]->cliente_tipo_pessoa == 'J')
                        <div class="row quebra-linha">
                            <p class="text-sm col-md-4">CNPJ
                                <b class="d-block">{{ $cnpj_cli }}</b>
                            </p>
                            <p class="text-sm col-md-4">Tipo de Cadastro
                                <b class="d-block">{{ $inscEst_cli }}</b>
                            </p>
                            <p class="text-sm col-md-4">Tipo de Pessoa
                                <b class="d-block">{{ $inscMun_cli }}</b>
                            </p>
                        </div>
                        @else
                        <div class="row quebra-linha">
                            <p class="text-sm col-md-4">CPF
                                <b class="d-block">{{ $cpf_cli }}</b>
                            </p>
                            <p class="text-sm col-md-4">RG
                                <b class="d-block">{{ $rg_cli }}</b>
                            </p>
                            <p class="text-sm col-md-4">Sexo
                                <b class="d-block">{{ $sexo_cli }}</b>
                            </p>
                        </div>
                        @endif
                        <div class="row quebra-linha">
                            <p class="text-sm col-md-4">Telefone Residencial
                                <b class="d-block">{{ $telRes_cli }}</b>
                            </p>
                            <p class="text-sm col-md-4">Telefone Celular
                                <b class="d-block">{{ $telCel_cli }}</b>
                            </p>
                            <p class="text-sm col-md-4">Telefone Comercial
                                <b class="d-block">{{ $telCom_cli }}</b>
                            </p>
                        </div>
                        <div class="row quebra-linha">
                            <p class="text-sm col-md-4">Tipo do Email
                                <b class="d-block">{{ $tipEma_cli }}</b>
                            </p>
                            <p class="text-sm col-md-4">Email
                                <b class="d-block">{{ $email_cli }}</b>
                            </p>
                        </div>
                    </div>

                    
                    <div class="post">
                        <h4 class="text-secondary font-weight-bold">Endereços Cadastrados</h4>
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
                            <x-adminlte-card theme="info" title="Endereço">
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
                                <label for="form-group">Endereço do Cliente para a OS</label>
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
                                            <input type="radio" id="radioPrimary_{{$endereco->endereco_seq}}" name="enderecoCliOS" checked value="{{$endereco->endereco_seq}}">
                                            <label for="radioPrimary_{{$endereco->endereco_seq}}">Endereço {{$cnt_end.$principal}}</label>
                                        </div>
                                        @else
                                        
                                        <div class="icheck-primary d-inline" style="margin-right: 25px;">
                                            <input type="radio" id="radioPrimary_{{$endereco->endereco_seq}}" name="enderecoCliOS" value="{{$endereco->endereco_seq}}">
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
                    <x-adminlte-button class="btn-flat" type="submit" label="Abrir OS" theme="info" icon="fa-solid fa-file-circle-plus"/>
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
$(function () {
  $('#quickForm').validate({
    rules: {
      empresa: {
        required: true
      },
      cliente: {
        required: true
      },
    },
    messages: {
      empresa: {
        required: "Por Favor informe a Empresa"
      },
      cliente: {
        required: "Por Favor informe o Cliente"
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
