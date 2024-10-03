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
                <a href="{{route('home.usuarios')}}">Usuários</a>
            </li>
            <li class="breadcrumb-item active">Usuários Cadastrados</li>
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
    'Empresa',
    'Usuário',
    'Email',
    'Tipo do Usuário',
    'Data de Inclusão',
    'Status',
    ['label' => 'Detalhes', 'no-export' => true, 'width' => 12],
];
$config = [
    'lengthMenu' => [ 5, 10, 25, 50],
    'pageLength' => 10,
    'language' => Helper::dataTableLangPtBR(),
    'order' => [
        [0, 'asc'],
        [5, 'asc'],
        [3, 'asc'],
        [1, 'asc'],
    ],
    'columns' => [null, null, null, null, null, null, ['orderable' => false]],
];

if($tipo == 'T'){
    $titulo = 'Usuários Cadastrados';
}elseif($tipo == 'A'){
    $titulo = 'Usuários Ativos Cadastrados';
}elseif($tipo == 'D'){
    $titulo = 'Usuários Desativados Cadastrados';
}elseif($tipo == 'ADM'){
    $titulo = 'Usuários Administradores Cadastrados';
}elseif($tipo == 'PR'){
    $titulo = 'Usuários Prestadores Cadastrados';
}elseif($tipo == 'CX'){
    $titulo = 'Usuários Caixa Cadastrados';
}else{
    $titulo = 'Usuários Consultores Cadastrados';
}
@endphp

<x-adminlte-card :title="$titulo" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable with-buttons>
        @foreach ($usuarios as $usuario)
            @php
                $tip_usu = Helper::formataTipoUsuario($usuario->usuario_tipo);
                
                if($usuario->usuario_status == 'A'){
                    $sts_usu = 'Ativo';
                }else{
                    $sts_usu = 'Desativado';
                }

                $data = date('d/m/Y', strtotime($usuario->created_at));

                $dataEmp = DB::table('cadastro_empresas')->where('empresa_codigo', $usuario->usuario_empresa)->get();

            @endphp
            <tr>
                <td>{{ $usuario->usuario_empresa.' - '.$dataEmp[0]->empresa_nome }}</td>
                <td>{{ $usuario->usuario_codigo.' - '.$usuario->name }}</td>
                <td>{{ $usuario->email }}</td>
                <td>{{ $tip_usu }}</td>
                <td>{{ $data }}</td>
                <td>{{ $sts_usu }}</td>
                <td>
                    <nobr class="d-flex justify-content-center">
                        <form method="get" action="{{ route('usuario.editarCadastro', ['dadosUsuario' => $usuario->usuario_codigo, 'tipo' => $tipo]) }}" style="float: left;">
                            @csrf 
                            <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Editar Registro" value="Edit" type="submit">
                                <i class="fa fa-lg fa-fw fa-pen"></i>
                            </button>
                        </form>
                        <form method="post" action="{{route('usuario.destroy', ['usuario' => $usuario])}}" style="float: left;">
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
            <form method="get" action="{{ route('usuario.cadastro', ['tipo' => $tipo]) }}">
            @csrf 
                <x-adminlte-button class="btn-nexus" label="Novo Usuário" theme="" icon="fas fa-user-plus" type="submit"/>
            </form>
            <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.usuarios') }}'" label="Voltar" theme="" icon=""/>
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
