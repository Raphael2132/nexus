@extends('adminlte::page')

@section('title', 'Cadastro de Setores')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h4 style="margin-bottom: 0px !important;">Parâmetros Gerais</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">
                    <a href="{{route('home.parSrvSetor')}}">Setor</a>
                </li>
                @if($acao == 'N')
                    <li class="breadcrumb-item active">Cadastro de Setor</li>
                @else
                    <li class="breadcrumb-item active">Manutenção do Setor</li>
                @endif
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-8">
        <!-- Define se o formulario é edição ou novo -->
        @if($acao == 'N')
        <form method="post" action="{{route('parametrosSrvSetor.insert')}}" id="quickForm" novalidate="novalidate">
        @else
        <form method="post" action="{{route('parametrosSrvSetor.update')}}" id="quickForm" novalidate="novalidate">
        @endif
            @csrf 
            <x-adminlte-card title="Cadastro de Novo Setor" theme="navy">
                <div class="row"> 
                    @php
                        $data = DB::table('cadastro_empresas')->selectRaw('empresa_codigo, empresa_nome')->orderBy('empresa_codigo', 'asc')->get();

                        $new_array1 =[];
                        $new_array2 =[];

                        foreach ($data as $empresa) {
                            $new_array1[] = $empresa->empresa_codigo;
                            $new_array2[] = $empresa->empresa_codigo.' - '.$empresa->empresa_nome;
                        }
                        $array_opt = array_combine($new_array1, $new_array2);

                        if(!empty($dadosSetor[0]['setor_empresa'])){
                            $emp_sel = $dadosSetor[0]['setor_empresa'];
                        }else{
                            $emp_sel = '';
                        }
                    @endphp
                    <!-- Empresa do Setor -->
                    <x-adminlte-select name="empresa" label="Empresa" fgroup-class="col-md-12">
                        <x-adminlte-options :options="$array_opt" empty-option="Selecione..." selected="{{$emp_sel}}"/>
                    </x-adminlte-select>
                </div>

                <div class="row">
                    @php
                        $areas = DB::table('parametros_sistema_areas')->selectRaw('area_codigo, area_desc')->orderBy('area_codigo', 'asc')->get();

                        $new_array1 =[];
                        $new_array2 =[];

                        foreach ($areas as $area) {
                            $new_array1[] = $area->area_codigo;
                            $new_array2[] = $area->area_codigo.' - '.$area->area_desc;
                        }
                        $array_opt2 = array_combine($new_array1, $new_array2);

                        if(!empty($dadosSetor[0]['setor_area'])){
                            $area_sel = $dadosSetor[0]['setor_area'];
                        }else{
                            $area_sel = '';
                        }
                    @endphp
                    <!-- Descrição -->
                    <x-adminlte-select name="area" label="Área" fgroup-class="col-md-12">
                        <x-adminlte-options :options="$array_opt2" empty-option="Selecione..." selected="{{$area_sel}}"/>
                    </x-adminlte-select>
                </div>

                <div class="row cod-set">
                    @php
                        if(!empty($dadosSetor[0]['setor_codigo'])){
                            $codigo_sel = $dadosSetor[0]['setor_codigo'];
                        }else{
                            $codigo_sel = '';
                        }
                    @endphp
                    <!-- Código -->
                    <x-adminlte-input name="codigo" label="Código" type="text" value="{{$codigo_sel}}" fgroup-class="col-md-12" readonly/>
                </div>

                <div class="row">
                    @php
                        if(!empty($dadosSetor[0]['setor_desc'])){
                            $desc_sel = $dadosSetor[0]['setor_desc'];
                        }else{
                            $desc_sel = '';
                        }
                    @endphp
                    <!-- Descrição -->
                    <x-adminlte-input name="descricao" label="Setor" type="text" placeholder="Informe a descrição do setor" value="{{$desc_sel}}" fgroup-class="col-md-12"/>
                </div>

                <!-- /.card -->
                <x-slot name="footerSlot">
                    @php
                        if(!empty($dadosSetor[0])){
                            $setor = $dadosSetor[0]->setor_id;
                        }else{
                            $setor = '';
                        }
                    @endphp
                    <div class="d-flex justify-content-between w-100">
                        <div class="d-flex">
                            <x-adminlte-button class="btn-flat btn_novo mr-2" type="button" onclick="window.location='{{ route('parametrosSrvSetor.cadastro') }}'" label="Novo Setor" theme="info" icon="fa-solid fa-plus"/>
                            <x-adminlte-button class="btn-flat btn_salvar mr-2" type="submit" label="Salvar" theme="info" icon="fa-solid fa-share-from-square"/>
                            <x-adminlte-button class="btn-flat btn_excluir" type="button" data-id="{{$setor}}" data-token="{{ csrf_token() }}" label="Excluir" theme="info" icon="fa-solid fa-trash"/>
                        </div>
                        <div class="d-flex">
                            <x-adminlte-button class="btn-flat" type="button" onclick="window.location='{{ route('home.parSrvSetor') }}'" label="Voltar" theme="info" icon=""/>
                        </div>
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
@section('plugins.Select2', true)

@section('css')
@stop

@section('js')
<script>

    $(".btn_excluir").click(function(){
        var id = $(this).attr("data-id");

        var url = "{{ route('parametrosSrvSetor.destroy', [':id', 'ajax']) }}";
        url = url.replace(':id', id);

        $.ajax({
            url: url,
            dataType: "JSON",
            type: 'POST',
            data: {
                '_token': $('meta[name=csrf-token]').attr("content"),
                '_method': 'DELETE',
                "id": id
            },
            success: function ()
            {
                window.location = "{{ route('parametrosSrvSetor.homeAjax') }}";
            }
        });
    });

    $(document).ready(function() {

        //Verifica de onde veio a app, cadastro ou edição
        var acao = {!! json_encode($acao) !!};

        if(acao == 'E'){
            $("#area").attr("disabled", true);
            $("#empresa").attr("disabled", true);
        }else{
            $(".btn_novo").hide();
            $(".btn_excluir").hide();
            $(".cod-set").hide();
        }

        //Ao clicar no botão salvar retira o disabled do campo para não ter problema no request do update do campo
        $(".btn_salvar").click(function(){
            $("#area").attr("disabled", false);
            $("#empresa").attr("disabled", false);
        });
    });
</script>

<script>
$(function () {
    $('#quickForm').validate({
        rules: {
            empresa: {
                required: true
            },
            area: {
                required: true
            },
            descricao: {
                required: true,
                maxlength: 40
            }, 
        },
        messages: {
            empresa: {
                required: "Por Favor informe uma Empresa"
            },
            area: {
                required: "Por Favor informe uma Área"
            },
            descricao: {
                required: "Por Favor informe a Descrição",
                maxlength: "Infome no máximo 40 caracteres"
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
