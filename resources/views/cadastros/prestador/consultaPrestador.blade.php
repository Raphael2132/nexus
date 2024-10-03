@extends('adminlte::page')

@section('title', 'Cadastro de Prestadores')

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
            @php   
                $dadosEmp = Helper::buscaDadosEmpresa(session('glo_empresa_exibicao_home'));
            @endphp
            <li class="breadcrumb-item active">{{ $dadosEmp->empresa_codigo.' - '.$dadosEmp->empresa_nome}}</li>
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
    'pageLength' => 10,
    'language' => Helper::dataTableLangPtBR(),
    'order' => [[1, 'asc']],
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

<x-adminlte-card :title="$titulo" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
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
                            <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Editar Registro" value="Edit" type="submit">
                                <i class="fa fa-lg fa-fw fa-pen"></i>
                            </button>
                        </form>
                        <form method="post" action="{{ route('prestador.destroy', ['prestador' => $prestador]) }}" style="float: left;">
                            @csrf 
                            @method('delete')
                            <button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Excluir Registro" value="Delete" type="submit" >
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
                <x-adminlte-button class="btn-nexus" label="Novo Prestador" theme="" icon="fas fa-user-plus" type="submit"/>
            </form>
            <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.prestadores') }}'" label="Voltar" theme="" icon=""/>
        </div>
    </x-slot>
</x-adminlte-card>
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('css')
@stop

@section('js')
@stop
