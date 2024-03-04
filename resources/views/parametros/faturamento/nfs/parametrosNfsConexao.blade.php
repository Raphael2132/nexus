@extends('adminlte::page')

@section('title', 'Cadastro de Conexão da NFS-e')

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
                <li class="breadcrumb-item active">Conexões Parâmetrizadas</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
@php
$heads = [
    'Empresa',
    'Provedor',
    'Usuario',
    'Senha',
    'Token',
    'URL / WSDL',
    'Ambiente',
    ['label' => 'Editar', 'no-export' => true, 'width' => 5],
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
    'columns' => [null, null, null, null, null, null, null, ['orderable' => false]],
];
@endphp
<x-adminlte-card title="Conexões Parametrizadas" theme="navy" collapsible maximizable>
    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable with-buttons>
        @foreach ($conexoes as $conexao)
            @php
                $data_emp = DB::table('cadastro_empresas')->where('empresa_codigo','=',$conexao->conexao_empresa)->get();
                $empresa = $conexao->conexao_empresa.' - '.$data_emp[0]->empresa_nome;
                
                if(!empty($conexao->conexao_provedor)){
                    $nomePro = DB::table('parametros_fat_nfs_provedores')->selectRaw('provedor_desc')->where('provedor_id','=',$conexao->conexao_provedor)->get();
                    $nomeProvedor = $conexao->conexao_provedor.' - '.$nomePro[0]->provedor_desc;
                }else{
                    $nomeProvedor ='Não Cadastrado';
                }

                if($conexao->conexao_ambiente =='H'){
                    $ambiente = 'Homologação';
                }else{
                    $ambiente = 'Produção';
                }
            @endphp
            <tr>
                <td>{{ $empresa }}</td>
                <td>{{ $nomeProvedor }}</td>  
                <td>{{ $conexao->conexao_usuario }}</td>  
                <td>{{ $conexao->conexao_senha }}</td>  
                <td>{{ $conexao->conexao_token }}</td>    
                <td>{{ $conexao->conexao_wsdl }}</td>  
                <td>{{ $ambiente }}</td> 
                <td>
                    <nobr>
                        <form method="get" action="{{ route('parmetrosNfsCon.editarCadastro', ['dadosConexao' => $conexao->conexao_empresa, 'appOrigem' => 'parametrosNfsConexao']) }}" style="float: left;">
                            @csrf
                            <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" value="Edit" type="submit">
                                <i class="fa fa-lg fa-fw fa-pen"></i>
                            </button>
                        </form>
                    </nobr>
                </td>        
            </tr>
        @endforeach
    </x-adminlte-datatable>
</x-adminlte-card>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.Sweetalert2', true)
@section('plugins.toastr', true)

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
