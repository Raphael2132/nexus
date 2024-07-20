@extends('adminlte::page')

@section('title', 'Cadastro de Clientes')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Cadastros</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.prestadores')}}">Prestadores</a>
            </li>
            <li class="breadcrumb-item active">Prestadores Cadastrados</li>
        </ol>
    </div>
</div>
@stop


@section('content')

@php
$heads = [
    ['label' => '', 'no-export' => true, 'width' => 5],
    'Código',
    'Nome',
    'CPF',
    'Status',
    ['label' => 'Opções', 'no-export' => true, 'width' => 10],
];
$config = [
    'lengthMenu' => [ 5, 10, 25, 50],
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
    'columns' => [['orderable' => false], null, null, null, null, ['orderable' => false]],
];

if($tipo == 'A'){
    $titulo = 'Prestadores Ativos';
}elseif($tipo == 'D'){
    $titulo = 'Prestadores Demitidos';
}else{
    $titulo = 'Todos os Prestadores';
}

@endphp

<x-adminlte-card :title="$titulo" theme="navy" collapsible maximizable>
    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable with-buttons>
        @foreach ($prestadores as $prestador)
            <tr>
                <td>
                    <nobr class="d-flex justify-content-center">
                        <!-- Gera o icone da lupa que abre o modal -->
                        <a class="text-muted" data-toggle="modal" title="Visualisar Detalhes" data-target="#modalCustom_{{$prestador->prestador_codigo}}">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </a>
                    </nobr>
                </td>
                @php 
                    if($prestador->prestador_status == 'A'){
                        $status = 'Ativo';
                    }else{
                        $status = 'Demitido';
                    }
                @endphp
                <td>{{ $prestador->prestador_codigo }}</td>
                <td>{{ $prestador->prestador_nome }}</td>
                <td>{{ Helper::mascaraCPF($prestador->prestador_cpf) }}</td>
                <td>{{ $status }}</td>
                <td>
                    <nobr class="d-flex justify-content-center">
                        <form method="get" action="{{route('prestador.editarCadastro', ['dadosPrestador' => $prestador->prestador_codigo, 'empresa' => $prestador->prestador_empresa, 'tipo' => $tipo])}}" style="float: left;">
                            @csrf
                            <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Editar Registros" value="Edit" type="submit">
                                <i class="fa fa-lg fa-fw fa-pen"></i>
                            </button>
                        </form>
                        <form method="post" action="{{ route('prestador.destroy', ['prestador' => $prestador]) }}" style="float: left;">
                            @csrf 
                            @method('delete')
                            <button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Excluir Registros" value="Delete" type="submit" >
                                <i class="fa fa-lg fa-fw fa-trash"></i>
                            </button>
                        </form>
                    </nobr>
                </td>                
            </tr>
        @endforeach
    </x-adminlte-datatable>
    <x-slot name="footerSlot">
        <div class="d-flex justify-content-between w-100">
            <form method="get" action="{{ route('prestador.cadastro') }}">
                @csrf 
                <x-adminlte-button label="Novo Prestador" theme="info" icon="fas fa-user-plus" type="submit"/>
            </form>
            <x-adminlte-button type="button" onclick="window.location='{{ route('home.prestadores') }}'" label="Voltar" theme="info" icon=""/>
        </div>
    </x-slot>
</x-adminlte-card>
@stop

@section('css')
@stop

@section('js')
@stop
