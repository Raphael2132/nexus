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
            <li class="breadcrumb-item active">Emissão da NFS-e</li>
        </ol>
    </div>
</div>
@stop

@section('content')
@php
$heads = [
    'Empresa',
    'Gera NFS-e',
    'Provedor',
    'Numeração',
    'Série',
    'Imprime NFS-e',
    'Imprime RPS',
    ['label' => 'Editar', 'no-export' => true, 'width' => 5],
];
$config = [
    'lengthMenu' => [ 5, 10, 25, 50],
    'pageLength' => 10,
    'language' => Helper::dataTableLangPtBR(),
    'order' => [[0, 'asc']],
    'columns' => [null, null, null, null, null, null, null, ['orderable' => false]],
];
@endphp
<x-adminlte-card title="Parametros da Emissão de NFS-e" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable with-buttons>
        @foreach ($emissoes as $emissao)
            @php
                $data_emp = DB::table('cadastro_empresas')->where('empresa_codigo','=',$emissao->parnfs_empresa)->get();
                $empresa = $emissao->parnfs_empresa.' - '.$data_emp[0]->empresa_nome;
                
                if(!empty($emissao->parnfs_provedor)){
                    $nomePro = DB::table('parametros_fat_nfs_provedores')->where('provedor_codigo','=',$emissao->parnfs_provedor)->get();
                    $nomeProvedor = $emissao->parnfs_provedor.' - '.$nomePro[0]->provedor_desc;
                }else{
                    $nomeProvedor ='Não Cadastrado';
                }

                if($emissao->parnfs_utiliza_nfs =='S'){
                    $geraNfs = 'Sim';
                }else{
                    $geraNfs = 'Não';
                }

                if($emissao->parnfs_impressao_nfs =='S'){
                    $impNfs = 'Sim';
                }else{
                    $impNfs = 'Não';
                }

                if($emissao->parnfs_impressao_rps =='S'){
                    $impRps = 'Sim';
                }else{
                    $impRps = 'Não';
                }
            @endphp
            <tr>
                <td>{{ $empresa }}</td>
                <td>{{ $geraNfs }}</td>  
                <td>{{ $nomeProvedor }}</td>  
                <td>{{ $emissao->parnfs_numeracao }}</td>  
                <td>{{ $emissao->parnfs_serie }}</td>    
                <td>{{ $impNfs }}</td>  
                <td>{{ $impRps }}</td>     
                <td>
                    <nobr>
                        <form method="get" action="{{ route('parmetrosNfsEmi.editarCadastro', ['dadosEmissao' => $emissao->parnfs_empresa, 'appOrigem' => 'parametrosNfsEmissao']) }}" style="float: left;">
                            @csrf
                            <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Editar Registro" value="Edit" type="submit">
                                <i class="fa fa-lg fa-fw fa-pen"></i>
                            </button>
                        </form>
                    </nobr>
                </td>     
            </tr>
        @endforeach
    </x-adminlte-datatable>
    <x-slot name="footerSlot">
        <div class="d-flex justify-content-between w-100">
            <div class="d-flex">
            </div>
            <div class="d-flex">
                <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.parFatNfs') }}'" label="Voltar" theme="info" icon=""/>
            </div>
        </div>
    </x-slot>
</x-adminlte-card>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('css')
@stop

@section('js')
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
</script>
@stop
