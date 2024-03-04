@extends('adminlte::page')

@section('title', 'Cadastro de Provedores de Geração da NFS-e')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h1>Parâmetros Gerais</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">
                    <a href="{{route('home.parFatNfs')}}">Emissão de NFS-e</a>
                </li>
                <li class="breadcrumb-item active">Provedores Parâmetrizadas</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
@php
$heads = [
    ['label' => 'Código', 'no-export' => true, 'width' => 10],
    'Provedor',
];
$config = [
    'searching' => false,
    'lengthChange' => false,
    'lengthMenu' => 5,
    'language' => [
        'decimal' =>        '',
        'emptyTable' =>     'Sem dados disponíveis na tabela',
        'info' =>           'Mostrando _START_ a _END_ de _TOTAL_ registros',
        'infoEmpty' =>      'Mostrando 0 a 0 de 0 registros',
        'infoFiltered' =>   '(Filtrado do total de _MAX_ registros)',
        'infoPostFix' =>    '',
        'thousands' =>      ',',
        'lengthMenu' =>     'Mostrar _MENU_ registros',
        'loadingRecords' => 'Carregando...',
        'processing' =>     '',
        'search' =>         'Pesquisar:',
        'zeroRecords' =>    'Nenhum registro correspondente encontrado',
        'paginate' => [
            'first' =>      'Primeiro',
            'last' =>       'Último',
            'next' =>       'Próximo',
            'previous' =>   'Anterior'
        ],
        'aria' => [
            'sortAscending' =>  ': ativar para classificar a coluna em ordem crescente',
            'sortDescending' => ': ativar para classificar a coluna em ordem decrescente'
        ],
    ],
];
@endphp
<div class="esquerdo col-md-6">
    <x-adminlte-card title="Provedores Cadastrados de Geração da NFS-e" theme="navy" theme-mode="outline" collapsible maximizable>
        <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable>
            @foreach ($provedores as $provedor)
                <tr>
                    <td>{{ $provedor->provedor_id }}</td>
                    <td>{{ $provedor->provedor_desc }}</td>             
                </tr>
            @endforeach
        </x-adminlte-datatable>
    </x-adminlte-card>
</div>
<div class="direito col-md-6">
    <form method="post" action="{{route('parametrosNfsProvedor.inserir')}}" id="quickForm" novalidate="novalidate">
        @csrf 
        <x-adminlte-card title="Cadastrar Novo Provedor" theme="navy" collapsible maximizable>
            <!-- Nome do Provedor -->
            <x-adminlte-input name="descricao" label="Provedor" type="text" placeholder="Nome do Provedor" fgroup-class="col-md-12"/>

            <x-slot name="footerSlot">
                <x-adminlte-button class="btn-flat" type="submit" label="Incluir" theme="info" icon="fa-solid fa-share-from-square"/>
            </x-slot>
        </x-adminlte-card>
    </form>
</div>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.Sweetalert2', true)
@section('plugins.toastr', true)
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
<script>
$(function () {
    $('#quickForm').validate({
        rules: {
            descricao: {
                required: true,
                maxlength: 80
            },
        },
        messages: {
            descricao: {
                required: "Por Favor informe o Nome do Provedor",
                maxlength: "Infome no máximo 80 caracteres"
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
