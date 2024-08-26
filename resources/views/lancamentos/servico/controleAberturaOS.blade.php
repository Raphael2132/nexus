@extends('adminlte::page')

@section('title', 'Emissão de OS')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Lançamento de Serviços</h4>
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
        <form method="post" action="{{route('emissaoOS.abreOS', ['empresa' => $empresa, 'cliente' => $cliente])}}" id="quickForm" novalidate="novalidate">
        @csrf 
        @method('post')
            <x-adminlte-card title="Abertura de Ordem de Serviço" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
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

                    $dataParEmp = DB::table('parametros_fat_empresas')->where('parfat_emp', $empresa)->get();

                    if($dataParEmp[0]->parfat_sim == "S"){
                        $optSimples = 'Sim';
                    }else{
                        $optSimples = 'Não';
                    }
                @endphp

                <x-adminlte-callout theme="" class="callout-nexus" title-class="text-uppercase" icon="fa-solid fa-building" title="Dados da Empresa">
                    <div class="text-muted">
                        <div class="row quebra-linha">
                            <p class="text-sm col-md-2">Empresa
                                <b class="d-block">{{ $empresa_os }}</b>
                            </p>
                            <p class="text-sm col-md-2">CNPJ
                                <b class="d-block">{{ $cnpj }}</b>
                            </p>
                            <p class="text-sm col-md-2">Simples Nacional
                                <b class="d-block">{{$optSimples}}</b>
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

                <x-adminlte-callout theme="" class="callout-nexus" title-class="text-uppercase" icon="fa-solid fa-user-tie" title="Dados do Cliente">
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
                            <p class="text-sm col-md-2">Email
                                <b class="d-block">{{ $email_cli }}</b>
                            </p>
                        </div>
                    </div>
                    <div class="post">
                        <h5 class="text-secondary font-weight-bold">Endereços Cadastrados do Cliente</h5>
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
                            <x-adminlte-card title="Endereço" theme="" theme-mode="outline" header-class="card-outline-nexus">
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
                                        <div class="icheck-nexus d-inline" style="margin-right: 25px;">
                                            <input type="radio" id="radioPrimary_{{$endereco->endereco_seq}}" name="enderecoCliOS" checked value="{{$endereco->endereco_seq}}">
                                            <label for="radioPrimary_{{$endereco->endereco_seq}}">Endereço {{$cnt_end.$principal}}</label>
                                        </div>
                                        @else
                                        
                                        <div class="icheck-nexus d-inline" style="margin-right: 25px;">
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
                <!-- Dados do Local do Serviço -->
                <x-adminlte-callout theme="" class="callout-nexus" title-class="text-uppercase" icon="fa-solid fa-location-dot" title="Dados do Local do Serviço">
                    <div class="row">
                        <label for="form-group">Local da Prestação do Serviço</label>
                    </div>
                    <div class="row">
                        <div class="form-group clearfix">
                            <div class="icheck-nexus d-inline" style="margin-right: 25px;">
                                <input type="radio" id="radio_empresa" name="enderecoLocSrv" checked value="1">
                                <label for="radio_empresa">Endereço da Empresa</label>
                            </div>
                            <div class="icheck-nexus d-inline" style="margin-right: 25px;">
                                <input type="radio" id="radio_cliente" name="enderecoLocSrv" value="2">
                                <label for="radio_cliente">Endereço do Cliente</label>
                            </div>
                            <div class="icheck-nexus d-inline" style="margin-right: 25px;">
                                <input type="radio" id="radio_outro" name="enderecoLocSrv" value="3">
                                <label for="radio_outro">Outro Endereço</label>
                            </div>
                        </div>
                    </div>
                    <!-- Dados do serviço -->
                    <x-adminlte-callout class="callout-nexus bloco-endereco" theme="" title-class="text-uppercase" icon="fa-solid fa-file-invoice-dollar" title="Local da prestação do serviço">
                        <div class="row">
                            <!-- Campo escondido para tratamento interno -->
                            <input id="ibgeCodMun" type="hidden" name="ibgeCodMun">
                            
                            <!-- CEP -->
                            <x-adminlte-input name="cep" type="text" label="CEP" fgroup-class="col-md-2">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                        <div class="row">
                            <!-- Logradouro -->
                            <x-adminlte-input name="logradouro" type="text" label="Logradouro" fgroup-class="col-md-5">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fa-solid fa-address-book"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>

                            <!-- Numero -->
                            <x-adminlte-input name="numero" type="text" label="Número" fgroup-class="col-md-2">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fa-solid fa-hashtag"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>

                            <!-- Complemento -->
                            <x-adminlte-input name="complemento" type="text" label="Complemento" fgroup-class="col-md-5"></x-adminlte-input>
                        </div>
                        <div class="row">
                            <!-- Bairro -->
                            <x-adminlte-input name="bairro" type="text" label="Bairro" fgroup-class="col-md-4"></x-adminlte-input>

                            <!-- Cidade -->
                            <x-adminlte-input name="cidade" type="text" label="Cidade" fgroup-class="col-md-4">
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
                            <x-adminlte-select name="uf" label="UF" fgroup-class="col-md-2">
                                <x-adminlte-options :options="$array_opt" empty-option="Selecione..."/>
                            </x-adminlte-select>

                            <!-- Pais -->
                            <x-adminlte-input name="pais" type="text" label="Pais" fgroup-class="col-md-2"></x-adminlte-input>
                        </div>
                    </x-adminlte-callout>
                </x-adminlte-callout>
                <x-slot name="footerSlot">
                    <div class="d-flex justify-content-between w-100">
                        <x-adminlte-button class="btn-nexus" type="submit" label="Abrir OS" theme="info" icon="fa-solid fa-file-circle-plus"/>
                        <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.emissaoOS') }}'" label="Voltar" theme="info" icon=""/>
                    </div>
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
@section('plugins.Inputmask', true)

@section('css')

@stop

@section('js')
<!--
|--------------------------------------------------------------------------
| Eventos Iniciais da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() {

        // Init input mask on the target element.
        $('#cep').inputmask({
            "mask": "99999-999",
            // Specify other options...
        });

        //Bloco ao iniciar app fica escondido
        $('.bloco-endereco').hide();

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
                    //console.log(dadosRetorno);
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

<!--
|--------------------------------------------------------------------------
| Eventos onClick da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() {

        $("#radio_empresa").click(function(){
            $('.bloco-endereco').hide();

            $('#cep').val('');
            $('#logradouro').val('');
            $('#numero').val('');
            $('#complemento').val('');
            $('#bairro').val('');
            $('#cidade').val('');
            $('#uf').val('');
            $('#pais').val('');
            $('#ibgeCodMun').val('');
        });

        $("#radio_cliente").click(function(){
            $('.bloco-endereco').hide();

            $('#cep').val('');
            $('#logradouro').val('');
            $('#numero').val('');
            $('#complemento').val('');
            $('#bairro').val('');
            $('#cidade').val('');
            $('#uf').val('');
            $('#pais').val('');
            $('#ibgeCodMun').val('');
        });

        $("#radio_outro").click(function(){
            $('.bloco-endereco').show();
        });
    });
</script>

<script>
$(function () {
  $('#quickForm').validate({
    rules: {
        cep: {
            required: {
                depends: function(element) {
                    return $("input[name='enderecoLocSrv']:checked").val() == "3";
                }
            },
            minlength: 9,
            maxlength: 9
        },
        logradouro: {
            required: {
                depends: function(element) {
                    return $("input[name='enderecoLocSrv']:checked").val() == "3";
                }
            },
            maxlength: 100
        },
        numero: {
            required: {
                depends: function(element) {
                    return $("input[name='enderecoLocSrv']:checked").val() == "3";
                }
            },
            maxlength: 5
        },
        complemento: {
            maxlength: 60
        },
        bairro: {
            required: {
                depends: function(element) {
                    return $("input[name='enderecoLocSrv']:checked").val() == "3";
                }
            },
            maxlength: 60
        },
        cidade: {
            required: {
                depends: function(element) {
                    return $("input[name='enderecoLocSrv']:checked").val() == "3";
                }
            },
            maxlength: 80
        },
        uf: {
            required: {
                depends: function(element) {
                    return $("input[name='enderecoLocSrv']:checked").val() == "3";
                }
            },
        },
        pais: {
            required: {
                depends: function(element) {
                    return $("input[name='enderecoLocSrv']:checked").val() == "3";
                }
            },
            maxlength: 40
        },
    },
    messages: {
        cep: {
            required: "Por Favor informe o CEP do Local da Prestação do Serviço",
            minlength: "O CEP deve ter 8 dígitos",
            maxlength: "O CEP deve ter 8 dígitos"
        },
        logradouro: {
            required: "Por Favor informe o Logradouro do Local da Prestação do Serviço",
            maxlength: "Limite máximo do Logradouro é de 100 caracteres"
        }, 
        numero: {
            required: "Por Favor informe o Número do Local da Prestação do Serviço",
            maxlength: "Limite máximo do numero é de 5 caracteres"
        }, 
        complemento: {
            maxlength: "Limite máximo do Complemento é de 60 caracteres"
        },        
        bairro: {
            required: "Por Favor informe o Bairro do Local da Prestação do Serviço",
            maxlength: "Limite máximo do Bairro é de 60 caracteres"
        }, 
        cidade: {
            required: "Por Favor informe a Cidade do Local da Prestação do Serviço",
            maxlength: "Limite máximo do Bairro é de 80 caracteres"
        },
        uf: {
            required: "Por Favor informe a UF do Local da Prestação do Serviço",
        },
        pais: {
            required: "Por Favor informe o País do Local da Prestação do Serviço",
            maxlength: "Limite máximo do País é de 40 caracteres"
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
