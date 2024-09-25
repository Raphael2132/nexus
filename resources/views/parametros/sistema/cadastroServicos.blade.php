@extends('adminlte::page')

@section('title', 'Grupos e Serviços da NFS-e')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Parâmetros do Sistema</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.parSisServico')}}">Grupos e Serviços da NFS-e</a>
            </li>
            <li class="breadcrumb-item active">Cadastro de Serviço</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-8">
        <form method="post" action="{{route('parametrosSistemaServico.inserir')}}" id="quickForm" novalidate="novalidate">
            @csrf 
            <x-adminlte-card title="Cadastro de Novo Grupo de Serviço" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
                <div class="row">
                    @php
                        $data = DB::table('parametros_sis_servico_grupos')->orderBy('grupo_codigo', 'asc')->get();

                        $new_array1 =[];
                        $new_array2 =[];

                        foreach ($data as $grupo) {
                            $new_array1[] = $grupo->grupo_codigo;
                            $new_array2[] = $grupo->grupo_codigo.' - '.$grupo->grupo_desc;
                        }
                        $array_opt = array_combine($new_array1, $new_array2);
                    @endphp
                    <!-- Grupo do Serviço -->
                    <x-adminlte-select name="grupo" fgroup-class="col-md-9">
                        <x-slot name="label">
                            Grupo do Serviço <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_opt" empty-option="Selecione..."/>
                    </x-adminlte-select>
                    <!-- Código do Serviço -->
                    <x-adminlte-input name="codigo" type="number" fgroup-class="col-md-3">
                        <x-slot name="label">
                            Código do Serviço <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                
                <div class="row">
                    <!-- Empresa do Serviço -->
                    <x-adminlte-textarea name="descricao" rows=3 label-class="text-dark" igroup-size="sm" placeholder="Informe a descrição do serviço..." fgroup-class="col-md-12">
                        <x-slot name="label">
                            Descrição <span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fas fa-lg fa-file-alt text-white"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-textarea>
                </div>

                <div class="row">
                    <!-- Aliquota ISS -->
                    <x-adminlte-input name="aliquotaIss" label="% Alíquota ISS" type="text" placeholder="0,00" fgroup-class="col-md-6"/>
                    <!-- Código IBPT -->
                    <x-adminlte-input name="codigoIBPT" label="Código IBPT" type="text" fgroup-class="col-md-6"/>
                </div>

                <!-- /.card -->
                <x-slot name="footerSlot">
                    <div class="d-flex justify-content-between w-100">
                        <x-adminlte-button class="btn-nexus" type="submit" label="Salvar" theme="info" icon="fa-solid fa-share-from-square"/>
                        <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.parSisServico') }}'" label="Voltar" theme="info" icon=""/>
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
@section('plugins.toastr', true)

@section('css')
@stop

@section('js')
<script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script> 

<script>
    $(document).ready(function() {

        $('#aliquotaIss').mask('#.##0,00', {reverse: true});
    });
</script>

<script>
$(function () {
    jQuery.validator.addMethod("maxpercent", function(value, element) {
        return this.optional(element) || /^(\d{1,2}|\d{1,2}\,\d{1,2}|100\,[0]{1,2}|100)$/i.test(value);
    }, "Porcentagem máxima de 100,00 %");

    $('#quickForm').validate({
        rules: {
            grupo: {
                required: true
            },
            codigo: {
                required: true,
                maxlength: 4
            },
            descricao: {
                required: true,
                maxlength: 800
            },       
            aliquotaIss: {
                maxpercent: true

            }     
        },
        messages: {
            grupo: {
                required: "Por Favor informe um Grupo"
            },
            codigo: {
                required: "Por Favor informe um Código",
                maxlength: "Informe no máximo 5 dígitos"
            },
            descricao: {
                required: "Por Favor informe a Descrição",
                maxlength: "Infome no máximo 800 caracteres"
            }
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
