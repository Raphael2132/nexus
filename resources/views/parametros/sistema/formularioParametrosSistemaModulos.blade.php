@extends('adminlte::page')

@section('title', 'Módulos do sistema')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Parâmetros do Sistema</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('modulosSistema.index')}}">Módulos do Sistema</a>
            </li>
            <li class="breadcrumb-item active">Manutenção dos Módulos do Sistema</li>
        </ol>
    </div>
</div>
@stop

@section('content')
@php 
    //Monta variaveis para o Script Ini
    $dadosPlano = DB::table('parametros_sis_planos')->where('plano_codigo',$dadosModulo[0]['modulo_plano'])->first();
    $mod_srv = $dadosPlano->plano_mod_srv; 
    $mod_srv_cp = $dadosPlano->plano_mod_srv_cp; 
    $mod_nfs = $dadosPlano->plano_mod_nfs; 
    $mod_nfs_simp = $dadosPlano->plano_mod_nfs_simp; 
    $planoCliente = $dadosModulo[0]['modulo_plano'];
@endphp
<div class="d-flex justify-content-center">
    <div class="col-md-10">
        <form method="post" action="{{route('modulosSistema.update', [ 'modulosSistema' => $dadosModulo[0]['modulo_empresa_codigo'] ] )}}" id="quickForm" novalidate="novalidate">
            @csrf 
            @method('put')
            <x-adminlte-card title="Parametrização dos Módulos do Sistema" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>

                <div class="row">
                    @php
                        $nomeEmpresa = HelperFormatSelect::formataEmpresaCodigoNome($dadosModulo[0]->modulo_empresa_codigo);
                    @endphp
                    <!-- Nome -->
                    <x-adminlte-input name="empresa" type="text" value="{{$nomeEmpresa}}" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Empresa <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>
                </div>

                <div class="row">
                    @php
                        $config = Helper::dtRangeDataPtBR();
                        
                        if(!empty($dadosModulo[0]->modulo_dt_validade)){
                            $dataValidade = date('d/m/Y', strtotime($dadosModulo[0]->modulo_dt_validade));
                        }else{
                            $dataValidade = '';
                        }

                        $arraySelPlano = HelperArraySelect::arrayPlanos(1,1);
                    @endphp
                    <!-- Plano Contratado -->
                    <x-adminlte-select name="plano" fgroup-class="col-md-3">
                        <x-slot name="label">
                            Plano Contratado <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$arraySelPlano" empty-option="Selecione..." selected="{{$dadosModulo[0]->modulo_plano}}"/>
                    </x-adminlte-select>

                    <!-- Data de Expiração -->
                    <x-adminlte-date-range name="dataValidade" :config="$config" placeholder="Formato dia/mês/ano" fgroup-class="col-md-3">
                        <x-slot name="label">
                            Data de Validade da Licença <span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="far fa-lg fa-calendar-alt"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-date-range>
                    @push('js')<script>$(() => $("#dataValidade").val('{{ $dataValidade }}'))</script>@endpush

                    <!-- Quantidade de Usuários -->
                    <x-adminlte-input name="qtdUsu" type="number" value="{{$dadosModulo[0]->modulo_qtd_usuarios}}" fgroup-class="col-md-3">
                        <x-slot name="label">
                            Qtd. de Usuários <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>

                    <!-- Quantidade de Usuários Extras -->
                    <x-adminlte-input name="qtdUsuEx" type="number" value="{{$dadosModulo[0]->modulo_qtd_usuarios_ext}}" fgroup-class="col-md-3">
                        <x-slot name="label">
                            Qtd. de Usuários Extras <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>
                </div>

                <div class="row">
                    <!-- Utiliza Módulo Serviços -->
                    <x-adminlte-select name="modSrv" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Módulo de Serviços <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" selected="{{$dadosModulo[0]['modulo_servico']}}"/>
                    </x-adminlte-select>

                    <!-- Utiliza Módulo Emissão RPS -->
                    <x-adminlte-select name="contProd" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Módulo de Controle de Produção <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" selected="{{$dadosModulo[0]['modulo_controle_producao']}}"/>
                    </x-adminlte-select>

                    <!-- Utiliza Módulo Emissão RPS -->
                    <x-adminlte-select name="emiRps" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Módulo de Emissão RPS <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" selected="{{$dadosModulo[0]['modulo_emissao_rps']}}"/>
                    </x-adminlte-select>
                </div>

                <div class="row">
                    <!-- Utiliza Módulo Emissão NF-e -->
                    <x-adminlte-select name="emiNfs" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Módulo de Emissão NFS-e <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" selected="{{$dadosModulo[0]['modulo_emissao_nfs']}}"/>
                    </x-adminlte-select>

                    <!-- Utiliza Módulo Emissão Simplificada NFS-e -->
                    <x-adminlte-select name="emiNfsSimp" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Módulo de Emissão Simplificada NFS-e <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" selected="{{$dadosModulo[0]['modulo_emissao_nfs_simp']}}"/>
                    </x-adminlte-select>
                </div>

                <!-- /.card -->
                <x-slot name="footerSlot">
                    <div class="d-flex justify-content-between w-100">
                        <x-adminlte-button class="btn-nexus" type="submit" label="Salvar" theme="info" icon="fa-solid fa-share-from-square"/>
                        <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('modulosSistema.index') }}'" label="Voltar" theme="info" icon=""/>
                    </div>
                </x-slot>
            </x-adminlte-card>
        </form>
    </div>
</div>
@stop

@section('css')
@stop

<!-- Chamada dos Plugins usados na app -->  
@section('plugins.jqueryValidation', true)
@section('plugins.Sweetalert2', true)
@section('plugins.DateRangePicker', true)

@section('js')
<!--
|--------------------------------------------------------------------------
| Eventos Iniciais da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() {
        $('#empresa').prop('disabled', true);
        $('#qtdUsu').prop('disabled', true);

        //Ao iniciar app bloqueia os campos caso não usar o Módulo de Serviços
        if($("#modSrv").val() == 'N'){
            $("#contProd").prop('disabled', true);
            $("#emiNfs").prop('disabled', true);
        }

        //Ao iniciar a app bloqueia os campos caso não usar Emissão de NFS-e e a Emissão Simplificada de NFS-e
        if($("#emiNfsSimp").val() == 'N' && $("#emiNfs").val() == 'N'){
            $("#emiRps").prop('disabled', true);
        }

        var planoCliente = {!! json_encode($planoCliente) !!};

        if(planoCliente == 'BS01'){

            $("#modSrv").prop('disabled', true);
            $("#contProd").prop('disabled', true);
            $("#emiNfs").prop('disabled', true);

        }else if(this.value == 'BE02'){

            $("#contProd").prop('disabled', true);
            $("#emiNfsSimp").prop('disabled', true);

        }else if(this.value == 'BP03'){

            $("#contProd").prop('disabled', true);

        }else if(this.value == 'BP04'){
            //Não bloqueia nada
        }else{

            $("#modSrv").prop('disabled', true);
            $("#contProd").prop('disabled', true);
            $("#emiRps").prop('disabled', true);
            $("#emiNfs").prop('disabled', true);
            $("#emiNfsSimp").prop('disabled', true);

        }
    });
</script>

<!--
|--------------------------------------------------------------------------
| Eventos onChange da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() {

        /* ***** Verifica se o Modulo de Serviço for Alterado Fazer Validação ***** */
        $("#plano").change(function(){

            //Se Não usar o Módulo de Serviços Bloquear os campos de Controle de Produção e Emissão de NFS-e deixando com valor = N
            if(this.value == 'BS01'){

                $("#modSrv").prop('disabled', true);
                $("#contProd").prop('disabled', true);
                $("#emiRps").prop('disabled', false);
                $("#emiNfs").prop('disabled', true);
                $("#emiNfsSimp").prop('disabled', false);

                $("#modSrv").val('N');
                $("#contProd").val('N');
                $("#emiRps").val('S');
                $("#emiNfs").val('N');
                $("#emiNfsSimp").val('S');

                $('#qtdUsu').val('2');

            }else if(this.value == 'BE02'){

                $("#modSrv").prop('disabled', false);
                $("#contProd").prop('disabled', true);
                $("#emiRps").prop('disabled', false);
                $("#emiNfs").prop('disabled', false);
                $("#emiNfsSimp").prop('disabled', true);

                $("#modSrv").val('S');
                $("#contProd").val('N');
                $("#emiRps").val('S');
                $("#emiNfs").val('S');
                $("#emiNfsSimp").val('N');

                $('#qtdUsu').val('5');

            }else if(this.value == 'BP03'){

                $("#modSrv").prop('disabled', false);
                $("#contProd").prop('disabled', true);
                $("#emiRps").prop('disabled', false);
                $("#emiNfs").prop('disabled', false);
                $("#emiNfsSimp").prop('disabled', false);

                $("#modSrv").val('S');
                $("#contProd").val('N');
                $("#emiRps").val('S');
                $("#emiNfs").val('S');
                $("#emiNfsSimp").val('S');

                $('#qtdUsu').val('5');

            }else if(this.value == 'BP04'){

                $("#modSrv").prop('disabled', false);
                $("#contProd").prop('disabled', false);
                $("#emiRps").prop('disabled', false);
                $("#emiNfs").prop('disabled', false);
                $("#emiNfsSimp").prop('disabled', false);

                $("#modSrv").val('S');
                $("#contProd").val('S');
                $("#emiRps").val('S');
                $("#emiNfs").val('S');
                $("#emiNfsSimp").val('S');

                $('#qtdUsu').val('10');

            }else{

                $("#modSrv").prop('disabled', true);
                $("#contProd").prop('disabled', true);
                $("#emiRps").prop('disabled', true);
                $("#emiNfs").prop('disabled', true);
                $("#emiNfsSimp").prop('disabled', true);

                $("#modSrv").val('N');
                $("#contProd").val('N');
                $("#emiRps").val('N');
                $("#emiNfs").val('N');
                $("#emiNfsSimp").val('N');

                $('#qtdUsu').val('1');

            }
        });
        
        /* ***** Verifica se o Modulo de Serviço for Alterado Fazer Validação ***** */
        $("#modSrv").change(function(){

            //Se Não usar o Módulo de Serviços Bloquear os campos de Controle de Produção e Emissão de NFS-e deixando com valor = N
            if(this.value == 'N'){
                $("#contProd").val('N');
                $("#emiNfs").val('N');
                $("#contProd").prop('disabled', true);
                $("#emiNfs").prop('disabled', true);

                //Se também não estiver usando a Emissão Simplificada Bloquear a Emissão de RPS e deixar valor = N
                if($("#emiNfsSimp").val() == 'N'){
                    $("#emiRps").val('N');
                    $("#emiRps").prop('disabled', true);
                }
            }else{
                $("#contProd").prop('disabled', false);
                $("#emiNfs").prop('disabled', false);
            }
        });

        /* ***** Verifica se o Modulo de Emissão de NFS-e for Alterado Fazer Validação ***** */
        $("#emiNfs").change(function(){

            //Se também não estiver usando a Emissão Simplificada Bloquear a Emissão de RPS e deixar valor = N
            //Caso Ativar a Emissão de NFS-e garantir o desbloqueio do campo de RPS e com valor = S
            if(this.value == 'N' && $("#emiNfsSimp").val() == 'N'){
                $("#emiRps").val('N');
                $("#emiRps").prop('disabled', true);
            }else{
                $("#emiRps").val('S');
                $("#emiRps").prop('disabled', false);
            }
        });

        /* ***** Verifica se o Modulo de Emissão Simplificada de NFS-e for Alterado Fazer Validação ***** */
        $("#emiNfsSimp").change(function(){

            //Se também não estiver usando a Emissão de NFS-e Bloquear a Emissão de RPS e deixar valor = N
            //Caso Ativar a Emissão Simplificada de NFS-e garantir o desbloqueio do campo de RPS e com valor = S
            if(this.value == 'N' && $("#emiNfs").val() == 'N'){
                $("#emiRps").val('N');
                $("#emiRps").prop('disabled', true);
            }else{
                $("#emiRps").val('S');
                $("#emiRps").prop('disabled', false);
            }
        });

    });
</script>

<script>
$(function () {
    $('#quickForm').validate({
        rules: {
            modSrv: {
                required: true
            },
            emiRps: {
                required: true
            },
            emiNfs: {
                required: true
            },
            emiNfsSimp: {
                required: true
            },
            dataValidade: {
                required: true
            },
            qtdUsu: {
                required: true
            },
            contProd: {
                required: true
            },
            qtdUsuEx: {
                required: true
            },
            plano: {
                required: true
            },
        },
        messages: {
            modSrv: {
                required: "Por Favor informe se utiliza o Módulo de Serviços"
            },
            emiRps: {
                required: "Por Favor informe se utiliza o Módulo de Emissão RPS"
            },
            emiNfs: {
                required: "Por Favor informe se utiliza o Módulo de Emissão NFS-e"
            },
            emiNfsSimp: {
                required: "Por Favor informe se utiliza o Módulo de Emissão Simplificada NFS-e"
            },
            dataValidade: {
                required: "Por Favor informe a Data de Vencimento da Licença"
            },
            qtdUsu: {
                required: "Por Favor informe a Qtd. de Usuários permitida"
            },
            contProd: {
                required: "Por Favor informe se utiliza o Módulo de Controle de Produção"
            },
            qtdUsuEx: {
                required: "Por Favor informe a Qtd. de Usuários Extras"
            },
            plano: {
                required: "Por Favor informe o Plano Contratado"
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

    @if(Session::has('info'))
        Swal.fire({
            confirmButtonColor: "#007bff",
            title: "Aviso!",
            html: "{!! session('info') !!}",
            icon: "info",
            customClass: {
                icon: "no-before-icon",
            }
        });
    @endif

    @if(Session::has('success2'))
        Swal.fire({
            confirmButtonColor: "#007bff",
            title: "Sucesso!",
            text: "{{ session('success2') }}",
            icon: "success",
            customClass: {
                icon: "no-before-icon",
            }
        });
    @endif
</script>
@stop
