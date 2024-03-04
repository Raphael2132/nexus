@extends('adminlte::page')

@section('title', 'Cadastro de Etapas de Atendimento')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h1>Parâmetros Gerais</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">
                    <a href="{{route('home.lancSrvEtapas')}}">Etapas de Atendimento</a>
                </li>
                @if($acao == 'N')
                    <li class="breadcrumb-item active">Cadastro de Etapas de Atendimento</li>
                @else
                    <li class="breadcrumb-item active">Manutenção de Etapas de Atendimento</li>
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
        <form method="post" action="{{route('lancamentosSrvEtapas.insert')}}" id="quickForm" novalidate="novalidate">
        @else
        <form method="post" action="{{route('lancamentosSrvEtapas.update')}}" id="quickForm" novalidate="novalidate">
        @endif
            @csrf 
            <x-adminlte-card title="Cadastro de Nova Etapa de Atendimento" theme="navy">

                <div class="row">
                    @php
                        $data = DB::table('cadastro_empresas')->select('empresa_codigo', 'empresa_nome')->orderBy('empresa_codigo', 'asc')->get();

                        $new_array1 =[];
                        $new_array2 =[];

                        foreach ($data as $empresa) {
                            $new_array1[] = $empresa->empresa_codigo;
                            $new_array2[] = $empresa->empresa_codigo.' - '.$empresa->empresa_nome;
                        }
                        $array_opt = array_combine($new_array1, $new_array2);

                        if(!empty($dadosEAT[0]['eat_emp'])){
                            $emp_sel = $dadosEAT[0]['eat_emp'];
                        }else{
                            $emp_sel = '';
                        }
                    @endphp

                    <!-- Empresa do Setor -->
                    <x-adminlte-select name="empresa" label="Empresa" fgroup-class="col-md-6">
                        <x-adminlte-options :options="$array_opt" empty-option="Selecione..." selected="{{$emp_sel}}"/>
                    </x-adminlte-select>
                </div>

                <div class="row">
                    @php
                        if(!empty($dadosEAT[0]['eat_cod'])){
                            $codigo_sel = $dadosEAT[0]['eat_cod'];
                        }else{
                            $codigo_sel = '';
                        }

                        if(!empty($dadosEAT[0]['eat_ord'])){
                            $ordem_sel = $dadosEAT[0]['eat_ord'];
                        }else{
                            $ordem_sel = '';
                        }

                        if(!empty($dadosEAT[0]['eat_nom'])){
                            $descricao_sel = $dadosEAT[0]['eat_nom'];
                        }else{
                            $descricao_sel = '';
                        }
                    @endphp                    
                    <!-- Código -->
                    <x-adminlte-input name="codigo" label="Código" type="number" value="{{$codigo_sel}}" fgroup-class="col-md-4"/>

                    <!-- Ordem -->
                    <x-adminlte-input name="ordem" label="Ordem" type="number" value="{{$ordem_sel}}" fgroup-class="col-md-2"/>
                    
                    <!-- Descrição -->
                    <x-adminlte-input name="descricao" label="Descrição" type="text" value="{{$descricao_sel}}" fgroup-class="col-md-6"/>
                </div>

                <div class="row">
                    @php
                        $data_cat = DB::table('lancamento_srv_categorias')->select('categoria_codigo', 'categoria_desc')->orderBy('categoria_codigo', 'asc')->get();

                        $new_array1_cat =[];
                        $new_array2_cat =[];

                        foreach ($data_cat as $categoria) {
                            $new_array1_cat[] = $categoria->categoria_codigo;
                            $new_array2_cat[] = $categoria->categoria_codigo.' - '.$categoria->categoria_desc;
                        }
                        $array_opt_cat = array_combine($new_array1_cat, $new_array2_cat);

                        if(!empty($dadosEAT[0]['eat_cat'])){
                            $cat_sel = $dadosEAT[0]['eat_cat'];
                        }else{
                            $cat_sel = '';
                        }

                        $data_are = DB::table('parametros_sistema_areas')->select('area_codigo', 'area_desc')->orderby('area_codigo', 'asc')->get();

                        $new_array1_are =[];
                        $new_array2_are =[];

                        foreach ($data_are as $area) {
                            $new_array1_are[] = $area->area_codigo;
                            $new_array2_are[] = $area->area_codigo.' - '.$area->area_desc;
                        }
                        $array_opt_are = array_combine($new_array1_are, $new_array2_are);

                        if(!empty($dadosEAT[0]['eat_are'])){
                            $are_sel = $dadosEAT[0]['eat_are'];
                        }else{
                            $are_sel = '';
                        }
                    @endphp
                    <!-- Categoria -->
                    <x-adminlte-select name="categoria" label="Categoria" fgroup-class="col-md-6">
                        <x-adminlte-options :options="$array_opt_cat" empty-option="Selecione..." selected="{{$cat_sel}}"/>
                    </x-adminlte-select>

                    <!-- Area -->
                    <x-adminlte-select name="area" label="Área" fgroup-class="col-md-6">
                        <x-adminlte-options :options="$array_opt_are" empty-option="Selecione..." selected="{{$are_sel}}"/>
                    </x-adminlte-select>
                </div>

                <!-- /.card -->
                <x-slot name="footerSlot">
                    @php
                        if(!empty($dadosEAT[0])){
                            $tipoEAT = $dadosEAT[0]->eat_id;
                        }else{
                            $tipoEAT = '';
                        }
                    @endphp
                    <x-adminlte-button class="btn-flat btn_novo" type="button" onclick="window.location='{{ route('lancamentosSrvEtapas.cadastro') }}'" label="Nova Categoria" theme="info" icon="fa-solid fa-plus"/>
                    <x-adminlte-button class="btn-flat btn_salvar" type="submit" label="Salvar" theme="info" icon="fa-solid fa-share-from-square"/>
                    <x-adminlte-button class="btn-flat btn_excluir" type="button" data-id="{{$tipoEAT}}" data-token="{{ csrf_token() }}" label="Excluir" theme="info" icon="fa-solid fa-trash"/>
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
<!--
|--------------------------------------------------------------------------
| Eventos Inicial da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() {    

        //Verifica de onde veio a app, cadastro ou edição
        var acao = {!! json_encode($acao) !!};

        if(acao == 'E'){
            $("#empresa").attr("disabled", true);
            $("#codigo").attr("disabled", true);
        }else{
            $(".btn_novo").hide();
            $(".btn_excluir").hide();
        }
    });
</script>

<!--
|--------------------------------------------------------------------------
| Eventos onClick da app
|--------------------------------------------------------------------------
-->
<script>
    $(".btn_excluir").click(function(){
        var id = $(this).attr("data-id");

        var url = "{{ route('lancamentosSrvEtapas.destroy', [':id', 'ajax']) }}";
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
                window.location = "{{ route('lancamentosSrvEtapas.homeAjax') }}";
            }
        });
    });

    //Ao clicar no botão salvar retira o disabled do campo para não ter problema no request do update do campo
    $(".btn_salvar").click(function(){
        $("#empresa").attr("disabled", false);
        $("#codigo").attr("disabled", false);
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
            codigo: {
                required: true,
                maxlength: 9
            },
            ordem: {
                required: true,
                maxlength: 3
            },
            descricao: {
                required: true,
                maxlength: 80
            },
            categoria: {
                required: true
            },
            tipoSRV: {
                required: true
            },
        },
        messages: {
            empresa: {
                required: "Por Favor informe a Empresa"
            },
            codigo: {
                required: "Por Favor informe um Código",
                maxlength: "O Código deve ter no máximo 9 digitos"
            },
            ordem: {
                required: "Por Favor informe uma Ordem",
                maxlength: "A Ordem deve ter no máximo 3 digitos"
            },
            descricao: {
                required: "Por Favor informe a Descrição",
                maxlength: "Infome no máximo 80 caracteres"
            },
            categoria: {
                required: "Por Favor informe a Categoria"
            },
            tipoSRV: {
                required: "Por Favor informe o Tipo de Serviço"
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
