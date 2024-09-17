@extends('adminlte::page')

@section('title', 'Geral da Empresa')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Parâmetros Gerais</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.parametrosFatEmp')}}">Geral da Empresa</a>
            </li>
            <li class="breadcrumb-item active">Manutenção Geral da Empresa</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-8">
        <!-- Define se o formulario é edição ou novo -->
        <form method="post" action="{{route('parametrosFatEmp.update')}}" id="quickForm" novalidate="novalidate">
            @csrf 
            <x-adminlte-card title="Manutenção dos Parâmetros Gerais de Faturamento" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>

                <div class="row">
                    @php
                        $data = DB::table('cadastro_empresas')->where('empresa_codigo', $parametrosEmp[0]->parfat_emp)->get();

                        $new_array1 =[];
                        $new_array2 =[];

                        foreach ($data as $empresa) {
                            $new_array1[] = $empresa->empresa_codigo;
                            $new_array2[] = $empresa->empresa_codigo.' - '.$empresa->empresa_nome;
                        }
                        $array_opt = array_combine($new_array1, $new_array2);

                    @endphp

                    <!-- Empresa do Setor -->
                    <x-adminlte-select name="empresa" fgroup-class="col-md-12">
                        <x-slot name="label">
                            Empresa <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_opt" empty-option="Selecione..." selected="{{$parametrosEmp[0]->parfat_emp}}"/>
                    </x-adminlte-select>
                </div>

                <div class="row">              
                    <!-- Optante do Simples Nacional -->
                    <x-adminlte-select name="optSimples" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Optante do Simples Nacional <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" empty-option="Selecione..." selected="{{ $parametrosEmp[0]->parfat_sim }}" />
                    </x-adminlte-select>
                    <!-- Optante do Simples Nacional -->
                    <x-adminlte-select name="envNfse" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Envia NFS-e por Email <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" empty-option="Selecione..." selected="{{ $parametrosEmp[0]->parfat_env_nfs_email }}" />
                    </x-adminlte-select>
                </div>

                <!-- /.card -->
                <x-slot name="footerSlot">
                    <div class="d-flex justify-content-between w-100">
                        <x-adminlte-button class="btn-nexus btn_salvar" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                        <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.parametrosFatEmp') }}'" label="Voltar" theme="" icon=""/>
                    </div>
                </x-slot>
            </x-adminlte-card>
        </form>
    </div>
</div>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.jqueryValidation', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)

@section('css')
@stop

@section('js')

<!--
|--------------------------------------------------------------------------
| Eventos Inicial da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() {    
        $("#empresa").attr("disabled", true);
    });
</script>

<!--
|--------------------------------------------------------------------------
| Eventos Validate da app
|--------------------------------------------------------------------------
-->
<script>
$(function () {
    $('#quickForm').validate({
        rules: {
            empresa: {
                required: true
            },
            optSimples: {
                required: true
            },
            envNfse: {
                required: true
            },
        },
        messages: {
            empresa: {
                required: "Por Favor informe a Empresa"
            },
            optSimples: {
                required: "Por Favor informe o campo Optante do Simples Nacional "
            },
            envNfse: {
                required: "Por Favor informe o campo Envia NFS-e por Email"
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

<!--
|--------------------------------------------------------------------------
| Eventos de Messagem da app
|--------------------------------------------------------------------------
-->
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
