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
                <a href="{{route('home.parSisModulo')}}">Módulos do Sistema</a>
            </li>
            <li class="breadcrumb-item active">Manutenção dos Módulos do Sistema</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-10">
        <form method="post" action="{{route('parametrosSistemaModulos.atualizar', [ 'empresa' => $dadosModulo[0]['modulo_empresa_codigo'] ] )}}" id="quickForm" novalidate="novalidate">
            @csrf 
            @method('post')
            <x-adminlte-card title="Parametrização dos Módulos do Sistema" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>

                <div class="row">
                    @php
                        $nomeEmp = DB::table('cadastro_empresas')->select('empresa_nome')->where('empresa_codigo','=',$dadosModulo[0]->modulo_empresa_codigo)->get();
                        $nomeEmpresa = $dadosModulo[0]->modulo_empresa_codigo.' - '.$nomeEmp[0]->empresa_nome;
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
                    @endphp
                    <!-- Data de Expiração -->
                    <x-adminlte-date-range name="dataValidade" label="Data de Expiração" :config="$config" placeholder="Formato dia/mês/ano" fgroup-class="col-md-3">
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
                </div>

                <div class="row">
                    <!-- Utiliza Módulo Serviços -->
                    <x-adminlte-select name="modSrv" fgroup-class="col-md-3">
                        <x-slot name="label">
                            Módulo de Serviços <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" empty-option="Selecione..." selected="{{$dadosModulo[0]['modulo_servico']}}"/>
                    </x-adminlte-select>

                    <!-- Utiliza Módulo Emissão RPS -->
                    <x-adminlte-select name="emiRps" fgroup-class="col-md-3">
                        <x-slot name="label">
                            Módulo de Emissão RPS <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" empty-option="Selecione..." selected="{{$dadosModulo[0]['modulo_emissao_rps']}}"/>
                    </x-adminlte-select>

                    <!-- Utiliza Módulo Emissão NF-e -->
                    <x-adminlte-select name="emiNfs" fgroup-class="col-md-3">
                        <x-slot name="label">
                            Módulo de Emissão NFS-e <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" empty-option="Selecione..." selected="{{$dadosModulo[0]['modulo_emissao_nfs']}}"/>
                    </x-adminlte-select>

                    <!-- Utiliza Módulo Emissão Simplificada NFS-e -->
                    <x-adminlte-select name="emiNfsSimp" fgroup-class="col-md-3">
                        <x-slot name="label">
                            Módulo de Emissão Simplificada NFS-e <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" empty-option="Selecione..." selected="{{$dadosModulo[0]['modulo_emissao_nfs_simp']}}"/>
                    </x-adminlte-select>
                </div>

                <!-- /.card -->
                <x-slot name="footerSlot">
                    <div class="d-flex justify-content-between w-100">
                        <x-adminlte-button class="btn-nexus" type="submit" label="Salvar" theme="info" icon="fa-solid fa-share-from-square"/>
                        <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.parSisModulo') }}'" label="Voltar" theme="info" icon=""/>
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
                required: "Por Favor informe a Data de Expiração da licença"
            },
            qtdUsu: {
                required: "Por Favor informe a Qtd. de Usuários permitida"
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
            text: "{{ session('info') }}",
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
