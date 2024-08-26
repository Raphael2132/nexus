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
            <li class="breadcrumb-item active">Provedores da NFS-e</li>
        </ol>
    </div>
</div>
@stop

@section('content')
@php
$heads = [
    ['label' => 'Código', 'no-export' => true, 'width' => 10],
    'Provedor',
    'Estado'
];
$config = [
    'lengthMenu' => [ 5, 10, 25, 50],
    'pageLength' => 10,
    'language' => Helper::dataTableLangPtBR(),
    'order' => [[0, 'asc']],
];
@endphp
<div class="row">
    <div class="esquerdo col-md-6">
        <x-adminlte-card title="Provedores Cadastrados de Emissão da NFS-e" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
            <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable with-buttons>
                @foreach ($provedores as $provedor)
                    <tr>
                        <td>{{ $provedor->provedor_codigo }}</td>
                        <td>{{ $provedor->provedor_desc }}</td>     
                        <td>{{ Helper::buscaEstadoUF($provedor->provedor_uf) }}</td>            
                    </tr>
                @endforeach
            </x-adminlte-datatable>
        </x-adminlte-card>
    </div>
    <div class="direito col-md-6">
        <form method="post" action="{{route('parametrosNfsProvedor.inserir')}}" id="quickForm" novalidate="novalidate">
            @csrf 
            <x-adminlte-card title="Cadastrar Novo Provedor" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>

                @php
                    $dados_ibge = DB::table('ibge_estados')->orderby('ibge_sigla')->get();

                    $new_array1 =[];
                    $new_array2 =[];

                    foreach ($dados_ibge as $ibge) {
                        $new_array1[] = $ibge->ibge_sigla;
                        $new_array2[] = $ibge->ibge_sigla.' - '.$ibge->ibge_nome;
                    }
                    $array_opt = array_combine($new_array1, $new_array2);

                    //Faz o lookup do campo de cidades 
                    $dataIBGE = DB::table('ibge_municipios')->select('ibge_mun_codigo', 'ibge_mun_nome')->orderBy('ibge_mun_uf_codigo', 'asc')->orderBy('ibge_mun_codigo', 'asc')->get();
                    $html = '<datalist id="cidades">';
                    foreach($dataIBGE as $cidade){
                        $html .= '<option value="'.$cidade->ibge_mun_nome.'">'.$cidade->ibge_mun_nome.'</option>';
                    }
                    $html .='</datalist>';
                    //Echo adiciona o html ao campo das cidades
                    echo $html;

                    $codigo = DB::table('parametros_fat_nfs_provedores')->max('provedor_codigo') +1;
                @endphp

                <!-- Código do Provedor -->
                <x-adminlte-input name="codigo" type="number" placeholder="Código do Provedor" value="{{$codigo}}" fgroup-class="col-md-12">
                    <x-slot name="label">
                        Código <span style="color:red;">*</span>
                    </x-slot>
                </x-adminlte-input>

                <!-- Estado -->
                <x-adminlte-select name="uf" fgroup-class="col-md-12">
                    <x-slot name="label">
                        Estado do Provedor <span style="color:red;">*</span>
                    </x-slot>
                    <x-adminlte-options :options="$array_opt" empty-option="Selecione..."/>
                </x-adminlte-select>

                <!-- Nome do Provedor -->
                <x-adminlte-input name="cidade" type="search" list="cidades" placeholder="Nome do Provedor" fgroup-class="col-md-12">
                    <x-slot name="label">
                        Cidade <span style="color:red;">*</span>
                    </x-slot>
                </x-adminlte-input>

                <x-slot name="footerSlot">
                    <div class="d-flex justify-content-between w-100">
                        <x-adminlte-button class="btn-nexus" type="submit" label="Incluir" theme="info" icon="fa-solid fa-share-from-square"/>
                        <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.parFatNfs') }}'" label="Voltar" theme="info" icon=""/>
                    </div>
                </x-slot>
            </x-adminlte-card>
        </form>
    </div>
</div>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.jqueryValidation', true)

@section('css')
<style>
    .esquerdo {
        float: left;
    }

    .direito {
        float: right;
    }
</style>
@stop

@section('js')
<!--
|--------------------------------------------------------------------------
| Eventos onChange da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() {

        //Evento de carregamento ajax dos dados dos setores
        $('#uf').change(function(){

            if( $(this).val() && $('#uf').val() != '' ) {
                
                var uf = $(this).val();

                var url = "{{ route('parametrosNfsProvedor.carregaCidAjax', [':uf']) }}";
                url = url.replace(':uf', uf);

                $.ajax({
                    url: url,
                    dataType: "JSON",
                    type: 'GET',
                    data: {
                        '_token': $('meta[name=csrf-token]').attr("content"),
                        '_method': 'GET',
                        "uf": uf
                    },
                    success: function (data)
                    {
                        if(data.cidade_ajax_existe == 'S'){

                            var options = '<';	

                            for (var i = 0; i < data.cidade_ajax.length; i++) {

                                options += '<option value="' + data.cidade_ajax[i].cidade + '">' + data.cidade_ajax[i].cidade + '</option>';
                            }	


                            $('#cidade').val('');
                            $('#cidades').html(options);

                        }
                    }
                });
            }
        });
    });
</script>

<script>
$(function () {
    $('#quickForm').validate({
        rules: {
            codigo: {
                required: true
            },
            cidade: {
                required: true,
                maxlength: 80
            },
            uf: {
                required: true
            },
        },
        messages: {
            cidade: {
                required: "Por Favor informe o Nome da Cidade Provedor",
                maxlength: "Infome no máximo 80 caracteres"
            },
            uf: {
                required: "Por Favor informe o Estado do Provedor"
            },
            codigo: {
                required: "Por Favor informe o Código"
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
