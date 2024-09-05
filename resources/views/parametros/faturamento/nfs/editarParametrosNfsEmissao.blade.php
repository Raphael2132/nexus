@extends('adminlte::page')

@section('title', 'Parâmetros da NFS-e')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Parâmetros Gerais</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.parFatNfs')}}">Parâmetros da NFS-e</a>
            </li>
            @if($appOrigem == 'parametrosNfsEmissao')
                <li class="breadcrumb-item active">
                    <a href="{{route('parametrosNfsEmissao')}}">Emissão da NFS-e</a>
                </li>
            @endif
            <li class="breadcrumb-item active">Manutenção Emissão da NFS-e</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-8">
        <form method="post" action="{{route('parmetrosNfsEmi.atualizar', [ 'empresa' => $dadosEmissao[0]['parnfs_empresa'] ] )}}" id="quickForm" novalidate="novalidate">
            @csrf 
            @method('post')
            <x-adminlte-card title="Manutenção da Parametrização de Emissão da NFS-e" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>

                <div class="row">
                    @php
                        $nomeEmp = DB::table('cadastro_empresas')->selectRaw('empresa_nome')->where('empresa_codigo','=',$dadosEmissao[0]->parnfs_empresa)->get();

                        $nomeEmpresa = $dadosEmissao[0]->parnfs_empresa.' - '.$nomeEmp[0]->empresa_nome;
                    @endphp
                    <!-- Empresa -->
                    <x-adminlte-input name="empresa" type="text" value="{{$nomeEmpresa}}" fgroup-class="col-md-12" readonly>
                        <x-slot name="label">
                            Empresa <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                
                <div class="row">
                    <!-- Gera NFS-e -->
                    <x-adminlte-select name="geraNFS" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Gera NFS-e <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" empty-option="Selecione..." selected="{{$dadosEmissao[0]['parnfs_utiliza_nfs']}}"/>
                    </x-adminlte-select>

                    @php
                        $data = DB::table('parametros_fat_nfs_provedores')->orderBy('provedor_codigo', 'asc')->get();

                        $new_array1 =[];
                        $new_array2 =[];

                        foreach ($data as $provedor) {
                            $new_array1[] = $provedor->provedor_codigo;
                            $new_array2[] = $provedor->provedor_codigo.' - '.$provedor->provedor_desc;
                        }
                        $array_opt = array_combine($new_array1, $new_array2);
                    @endphp
                    <!-- Provedor -->
                    <x-adminlte-select name="provedor" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Provedor da NFS-e <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_opt" empty-option="Selecione..." selected="{{$dadosEmissao[0]['parnfs_provedor']}}"/>
                    </x-adminlte-select>
                </div>

                <div class="row">
                    <!-- Número do RPS -->
                    <x-adminlte-input name="numeroNFS" type="number" fgroup-class="col-md-6" value="{{$dadosEmissao[0]['parnfs_numeracao']}}">
                        <x-slot name="label">
                            Numeração do RPS <span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-hashtag"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <!-- Série do RPS -->
                    <x-adminlte-input name="serieNFS" type="text" fgroup-class="col-md-6" value="{{$dadosEmissao[0]['parnfs_serie']}}">
                        <x-slot name="label">
                            Série do RPS <span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-font"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>

                <div class="row">
                    <!-- Imprime NFS-e -->
                    <x-adminlte-select name="imprimeNFS" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Gera Impressão NFS-e <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" empty-option="Selecione..." selected="{{$dadosEmissao[0]['parnfs_impressao_nfs']}}"/>
                    </x-adminlte-select>

                    <!-- Imprime RPS -->
                    <x-adminlte-select name="imprimeRPS" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Gera Impressão de RPS <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" empty-option="Selecione..." selected="{{$dadosEmissao[0]['parnfs_impressao_rps']}}"/>
                    </x-adminlte-select>
                </div>

                <!-- /.card -->
                <x-slot name="footerSlot">
                    <div class="d-flex justify-content-between w-100">
                        <x-adminlte-button class="btn-nexus" type="submit" label="Salvar" theme="info" icon="fa-solid fa-share-from-square"/>
                        @if($appOrigem == 'parametrosNfsEmissao')
                        <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('parametrosNfsEmissao') }}'" label="Voltar" theme="info" icon=""/>
                        @else
                        <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.parFatNfs') }}'" label="Voltar" theme="info" icon=""/>
                        @endif
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

@section('js')
<script>
$(function () {
    $('#quickForm').validate({
        rules: {
            empresa: {
                required: true
            },
            geraNFS: {
                required: true
            },
            provedor: {
                required: true
            },
            numeroNFS: {
                required: true,
                maxlength: 9
            },
            serieNFS: {
                required: true,
                maxlength: 5
            },
            imprimeNFS: {
                required: true
            },
            imprimeRPS: {
                required: true
            },
        },
        messages: {
            empresa: {
                required: "Por Favor informe a Empresa"
            },
            geraNFS: {
                required: "Por Favor informe se a empresa gera NFS-e"
            },
            provedor: {
                required: "Por Favor informe o Provedor"
            },
            numeroNFS: {
                required: "Por Favor informe o a Numeração da NFS-e",
                maxlength: "Informe no máximo 9 dígitos"
            },
            serieNFS: {
                required: "Por Favor informe a Série da NFS-e",
                minlength: "Informe no máximo 5 caracteres"
            },
            imprimeNFS: {
                required: "Por Favor informe se a Empresa imprime NFS-e",
            },
            imprimeRPS: {
                required: "Por Favor informe se a Empresa imprime RPS"
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
