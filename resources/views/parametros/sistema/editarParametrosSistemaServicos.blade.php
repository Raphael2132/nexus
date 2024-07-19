@extends('adminlte::page')

@section('title', 'Manutenção do Serviço da NFS-e')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h1>Parâmetros do Sistema</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">
                    <a href="{{route('home.parSisServico')}}">Grupos e Serviços da NFS-e</a>
                </li>
                <li class="breadcrumb-item active">Manutenção do Serviço da NFS-e</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-8">
        <form method="post" action="{{route('parametrosSistemaServico.atualizar', [ 'grupo' => $dadosServico[0]['servico_grupo'],'servico' => $dadosServico[0]['servico_codigo'] ] )}}" id="quickForm" novalidate="novalidate">
            @csrf 
            @method('post')
            <x-adminlte-card title="Serviço da NFS-e" theme="navy">

                <div class="row">
                    @php
                        $grupo_desc = DB::table('parametros_sistema_servico_grupos')->select('grupo_desc')->where('grupo_codigo','=',$dadosServico[0]->servico_grupo)->get();
                        $desc_drupo = $dadosServico[0]->servico_grupo.' - '.$grupo_desc[0]->grupo_desc;
                    @endphp
                    <!-- Grupo do serviço -->
                    <x-adminlte-input name="grupo" label="Grupo" type="text" value="{{$desc_drupo}}" fgroup-class="col-md-10" disabled/>
                    <!-- Código do Serviço -->
                    <x-adminlte-input name="codigo" label="Código" type="number" value="{{$dadosServico[0]->servico_codigo}}" fgroup-class="col-md-2" disabled/>
                </div>
                
                <div class="row">
                    <!-- Empresa do Serviço -->
                    <x-adminlte-textarea name="descricao" label="Descriçao" rows=3 label-class="text-dark" igroup-size="sm" placeholder="Informe a descrição do serviço..." fgroup-class="col-md-12">
                        {{$dadosServico[0]->servico_desc}}
                        <x-slot name="prependSlot">
                            <div class="input-group-text bg-navy">
                                <i class="fas fa-lg fa-file-alt text-white"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-textarea>
                </div>

                <div class="row">
                    <!-- Aliquota ISS -->
                    <x-adminlte-input name="aliquotaIss" label="Alíquota ISS" type="text" value="{{$dadosServico[0]->servico_aliquota_iss}}" placeholder="0,00" fgroup-class="col-md-6"/>
                    <!-- Código IBPT -->
                    <x-adminlte-input name="codigoIBPT" label="Código IBPT" type="text" value="{{$dadosServico[0]->servico_codigo_ibpt}}" fgroup-class="col-md-6"/>
                </div>

                <!-- /.card -->
                <x-slot name="footerSlot">
                    <div class="d-flex justify-content-between w-100">
                        <x-adminlte-button class="btn-flat" type="submit" label="Salvar" theme="info" icon="fa-solid fa-share-from-square"/>
                        <x-adminlte-button class="btn-flat" type="button" onclick="window.location='{{ route('home.parSisServico') }}'" label="Voltar" theme="info" icon=""/>
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
@section('plugins.Sweetalert2', true)
@section('plugins.toastr', true)
@section('plugins.jqueryValidation', true)

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
            descricao: {
                required: true,
                maxlength: 800
            },       
            aliquotaIss: {
                maxpercent: true

            }     
        },
        messages: {
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
