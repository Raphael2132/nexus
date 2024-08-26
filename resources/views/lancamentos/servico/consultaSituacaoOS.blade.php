@extends('adminlte::page')

@section('title', 'Situação de OS')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Lançamento de Serviços</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.situacaoOS')}}">Situação de OS</a>
            </li>
            <li class="breadcrumb-item active">Consulta Situação de OS</li>
        </ol>
    </div>
</div>
@stop


@section('content')

@php
$heads = [
    'Empresa',
    'Cliente',
    'OS / Data',
    'Situação',
    'Valor',
    'Responsável'
];
$config = [
    'lengthMenu' => [ 5, 10, 25, 50, 100],
    'pageLength' => 10,
    'language' => Helper::dataTableLangPtBR(),
    'order' => [[0, 'asc'],[2, 'asc']],
];
@endphp

<x-adminlte-card title="Consulta da Situação das OS Emitidas" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable with-buttons>
        @foreach ($dadosOS as $os)
            @php
                $emp = DB::table('cadastro_empresas')->where('empresa_codigo',$os->os_emp)->get();
                $empresa = $os->os_emp.' - '.$emp[0]->empresa_nome;

                $cli = DB::table('cadastro_clientes')->where('cliente_codigo',$os->os_cli)->get();
                $cliente = $os->os_cli.' - '.$cli[0]->cliente_nome;

                $data_os = date('d/m/Y H:i:s', strtotime($os->os_dha));

                $res = DB::table('users')->where('usuario_codigo',$os->os_res_abr)->get();
                $responsavel = $os->os_res_abr.' - '.$res[0]->name;

            @endphp
            <tr>
                <td>{{ $empresa }}</td>
                <td>{{ $cliente }}</td>
                <td><a href="{{route('situacaoOS.carregaOS', ['empresa' => $os->os_emp, 'cliente' => $os->os_cli, 'nos' => $os->os_nos, 'estagioAPP' => 'PRINCIPAL'])}}">{{ $os->os_nos.' - '.$data_os }}</a></td>
                @if($os->os_sts == 'F')
                <td class="text-xl-center" style="text-align: center;"><span class="text-xl-center badge badge-success">Finalizada</span></td>
                @elseif($os->os_sts == 'A')
                <td class="text-xl-center" style="text-align: center;"><span class="text-xl-center badge badge-info">Andamento</span></td>
                @else
                <td class="text-xl-center" style="text-align: center;"><span class="text-xl-center badge badge-danger">Cancelada</span></td>
                @endif         
                <td>{{ Helper::formataValorMonetario($os->os_vlt) }}</td>   
                <td>{{ $responsavel }}</td>         
            </tr>
        @endforeach
    </x-adminlte-datatable>
    <x-slot name="footerSlot">
        <div class="d-flex justify-content-between w-100">
            <form method="get" action="{{ route('home.emissaoOS') }}">
                <x-adminlte-button class="btn-nexus" label="Nova OS" theme="" icon="fa-solid fa-file-circle-plus" type="submit"/>
            </form>
            <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.situacaoOS') }}'" label="Voltar" theme="" icon=""/>
        </div>
    </x-slot>
</x-adminlte-card>
@stop

@section('css')
@stop

@section('js')
@stop
