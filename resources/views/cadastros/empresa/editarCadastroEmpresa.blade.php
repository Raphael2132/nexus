@extends('adminlte::page')

@section('title', 'Cadastro de Empresas')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Cadastros</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.empresa')}}">Empresas</a>
            </li>
            <li class="breadcrumb-item active">Manutenção da Empresa</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="col-12 col-sm-12">
    
    <!-- Criação do Card com Abas -->
    <div class="card card-tabs">
        <div class="card-header card-nexus p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                <li class="pt-2 px-3"><h3 class="card-title">Manutenção da Empresa</h3></li>
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-two-dados-gerais-tab" data-toggle="pill" href="#custom-tabs-two-dados-gerais" role="tab" aria-controls="custom-tabs-two-dados-gerais" aria-selected="true">Dados Pessoais</a>
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

                <!-- Aba Dados Gerais -->
                <div class="tab-pane fade show active" id="custom-tabs-two-dados-gerais" role="tabpanel" aria-labelledby="custom-tabs-two-dados-gerais-tab">
                    <form method="post" action="{{route('empresa.atualizar', ['empresa' => $dadosEmpresa[0]['empresa_id'], 'empresa_cod' => $dadosEmpresa[0]['empresa_codigo'], 'atualiza' => 'dados'])}}" id="quickForm" novalidate="novalidate">
                    @csrf 
                    @method('post')

                        <div class="row">
                            <!-- Código -->
                            <x-adminlte-input name="codigo" type="text" fgroup-class="col-md-2" value="{{$dadosEmpresa[0]['empresa_codigo'] }}" readonly>
                                <x-slot name="label">
                                    Código <span style="color:red;">*</span>
                                </x-slot>
                            </x-adminlte-input>

                            <!-- Nome -->
                            <x-adminlte-input name="nome" type="text" placeholder="Nome Completo" fgroup-class="col-md-4" value="{{$dadosEmpresa[0]['empresa_nome'] }}">
                                <x-slot name="label">
                                    Nome da Empresa <span style="color:red;">*</span>
                                </x-slot>
                            </x-adminlte-input>

                            
                            <!-- Nome da Empresa Painel Administrador -->
                            <x-adminlte-input name="nomeLogo" type="text" fgroup-class="col-md-4" value="{{$dadosEmpresa[0]['empresa_nome_logo'] }}">
                                <x-slot name="label">
                                    Nome da Empresa Painel Administrador <span style="color:red;">*</span>
                                </x-slot>
                            </x-adminlte-input>

                            <!-- CNPJ -->
                            <x-adminlte-input name="cnpj" type="text" fgroup-class="col-md-2" value="{{$dadosEmpresa[0]['empresa_cnpj'] }}">
                                <x-slot name="label">
                                    CNPJ <span style="color:red;">*</span>
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                        <div class="row">
                            <!-- Inscrição Estadual -->
                            <x-adminlte-input name="insEstadual" type="number" label="Inscrição Estadual" fgroup-class="col-md-6" value="{{$dadosEmpresa[0]['empresa_insc_estadual'] }}"></x-adminlte-input>

                            <!-- Inscrição Municipal -->
                            <x-adminlte-input name="insMunicipal" type="number" label="Inscrição Municipal" fgroup-class="col-md-6" value="{{$dadosEmpresa[0]['empresa_insc_municipal'] }}"></x-adminlte-input>
                        </div>
                        <div class="d-flex justify-content-center">
                            <x-adminlte-button class="btn-nexus" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                        </div>
                    </form>
                </div>

                <!-- Aba dos dados do Contato da empresa -->
                <div class="tab-pane fade" id="custom-tabs-two-contato" role="tabpanel" aria-labelledby="custom-tabs-two-contato-tab">
                    <form method="post" action="{{route('empresa.atualizar', ['empresa' => $dadosEmpresa[0]['empresa_id'], 'empresa_cod' => $dadosEmpresa[0]['empresa_codigo'], 'atualiza' => 'contato'])}}" id="quickForm2" novalidate="novalidate">
                    @csrf 
                    @method('post')
                        <div class="row">
                            <!-- Email -->
                            <x-adminlte-input name="email" type="email" placeholder="email@exemplo.com" fgroup-class="col-md-6" value="{{$dadosEmpresa[0]['empresa_email'] }}">
                                <x-slot name="label">
                                    Email <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fa-solid fa-envelope"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>

                            <!-- Telefone Celular -->
                            <x-adminlte-input name="telCelular" type="text" label="Telefone Celular" fgroup-class="col-md-3" value="{{$dadosEmpresa[0]['empresa_tel_celular'] }}">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fa-solid fa-mobile-retro"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>

                            <!-- Telefone Comercial -->
                            <x-adminlte-input name="telComercial" type="text" label="Telefone Comercial" fgroup-class="col-md-3" value="{{$dadosEmpresa[0]['empresa_tel_comercial'] }}">
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

                <!-- Aba dos dados do Endereço da Empresa -->
                <div class="tab-pane fade" id="custom-tabs-two-endereco" role="tabpanel" aria-labelledby="custom-tabs-two-endereco-tab">
                    <div class="main col-md-12" style="display: flex;flex-direction: column;"> 

                        @php
                            //Busca os dados dos endereços cadastrados da empresa
                            $data = DB::table('cadastro_empresa_enderecos')->where('endereco_empresa_codigo','=',$dadosEmpresa[0]['empresa_codigo'])->orderby('endereco_seq')->get();
                                
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
                                        <!-- Gera a div dos botões do card -->
                                        <div style="padding: 10px; height:30px;">
                                            <form method="post" action="{{ route('enderecoEmpresa.destroy', ['endereco' => $endereco->endereco_id]) }}" style="float: left;" >
                                            @csrf 
                                            @method('delete')
                                                <x-adminlte-button class="btn-sm" title="Excluir Endereço" theme="danger" icon="fa fa-lg fa-fw fa-trash" type="submit" style="margin-right: 5px;"/>
                                            </form>
                                            @php
                                                if($endereco->endereco_principal == "N"){
                                                    $endPrincipal = json_encode($endereco);
                                            @endphp
                                            <form method="get" action="{{ route('enderecoEmpresa.principal', ['endereco' => $endereco->endereco_id, 'empresa_cod' => $endereco->endereco_empresa_codigo]) }}" style="float: left;">
                                            @csrf 
                                            @method('get')
                                                <x-adminlte-button class="btn-sm" label="Tornar Principal" title="Tornar Principal" theme="success" icon="fa-solid fa-location-dot" type="submit"/>
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
                           
                        <!-- Gera o Modal com os campos da inserção dos dados do endereço da empresa -->
                        <div>
                            <form method="post" action="{{route('enderecoEmpresa.inserir')}}" id="quickForm3" novalidate="novalidate">
                            @csrf 
                            @method('post')    
                                <!-- Criação do Modal -->                           
                                <x-adminlte-modal id="modalCustom" title="Novo Endereço" size="lg" theme="modal-nexus" icon="fa-solid fa-address-book" v-centered static-backdrop scrollable>
                                    <div style="height:400px;">
                                        <!-- Campos escondidos com o codigo da empresa para o request -->  
                                        <input id="empresa_codigo" type="hidden" value="{{ $dadosEmpresa[0]['empresa_codigo'] }}" name="empresa_codigo">
                                        <input id="ibgeCodMun" type="hidden" name="ibgeCodMun">

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
                                                    <div class="input-group-text x-slot-nexus">
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
                                                    Pais <span style="color:red;">*</span>
                                                </x-slot>
                                            </x-adminlte-input>
                                        </div>
                                        <!-- Criação dos botões do Modal -->  
                                        <x-slot name="footerSlot">
                                            <x-adminlte-button class="btn-nexus btn_salvar_end mr-auto" theme="" label="Salvar" icon="fa-solid fa-share-from-square" type="submit"/>
                                            <x-adminlte-button class="btn-nexus" theme="" label="Voltar" data-dismiss="modal"/>
                                        </x-slot>
                                    </div>
                                </x-adminlte-modal>
                            </form>
                            <!-- Botão de chamada do Modal -->  
                            <div class="d-flex justify-content-center">
                                <x-adminlte-button class="btn-nexus" label="Novo Endereço" data-toggle="modal" data-target="#modalCustom" theme="" icon="fa-solid fa-address-book"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between w-100">
                <div class="d-flex">
                    <form method="get" action="{{ route('empresa.cadastro') }}" style="float: left; margin-right: 2px;">
                    @csrf 
                        <x-adminlte-button label="Nova Empresa" theme="" class="btn-nexus" icon="fa-solid fa-plus" type="submit"/>
                    </form>
                    <form method="post" action="{{ route('empresa.destroy', ['empresa' => $dadosEmpresa[0]]) }}" style="float: left;margin-left: 2px;">
                    @csrf 
                    @method('delete')
                        <x-adminlte-button label="Excluir Empresa" theme="" class="btn-nexus" icon="fa-solid fa-trash" type="submit"/>
                    </form>
                </div>
                <div class="d-flex">
                    <x-adminlte-button type="button" onclick="window.location='{{ route('home.empresa') }}'" label="Voltar" theme="" class="btn-nexus" icon=""/>
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

        $('#telComercial').inputmask({
            "mask": "(99) 9999-9999",
            // Specify other options...
        });

        // Init input mask on the target element.

        $('#cep').inputmask({
            "mask": "99999-999",
            // Specify other options...
        });

        $('#cnpj').inputmask({
            "mask": " 99.999.999/9999-99",
            // Specify other options...
        });

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
</script>

<script>
$(function () {
  $('#quickForm').validate({
    rules: {
      nome: {
        required: true,
        minlength: 5,
		maxlength: 80
      },
      cnpj: {
        required: true,
      },
	  insEstadual: {
		maxlength: 14
      },
	  insMunicipal: {
		maxlength: 15
      },
      nomeLogo: {
		required: true,
        maxlength: 18
      },
    },
    messages: {
      nome: {
        required: "Por Favor informe um Nome para a Empresa",
        minlength: "Informe no mínimo 5 caracteres no Nome da Empresa",
		maxlength: "Informe no máximo 80 caracteres no Nome da Empresa"
      },
      cnpj: {
        required: "Por Favor informe o CNPJ da Empresa"
      },
	  insEstadual: {
		maxlength: "Informe no máximo 14 dígitos na Incrição Estadual"
      },
	  insMunicipal: {
		maxlength: "Informe no máximo 15 dígitos na Incrição Municipal"
      },
      nomeLogo: {
		required: "Por Favor informe o Nome da Empresa Painel Administrador",
        maxlength: "Informe no máximo 18 caracteres"
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
  $('#quickForm2').validate({
    rules: {
      email: {
        required: true,
		email: true,
        maxlength: 80
      },
    },
    messages: {
      email: {
        required: "Por Favor Informe o Email",
		email: "Formato do Email inválido",
        maxlength: "Informe no máximo 80 caracteres no Email"
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
  $('#quickForm3').validate({
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
