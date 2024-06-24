@extends('adminlte::page')

@section('title', 'Informações Gerais dos Prestadores')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h1>Cadastros</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Prestadores</li>
            </ol>
        </div>
    </div>
@stop


@section('content')

<div class="row">
    <div class="col-md-3"> 
        <x-adminlte-small-box :title="$totalPrestadores" text="Total" icon="fas fa-users-gear" theme="info" url="{{ route('prestador.consulta',['tipo' => 'T']) }}" url-text="Detalhes de Todos Prestadores"/>
    </div>
    <div class="col-md-3"> 
        <x-adminlte-small-box :title="$prestadoresAtivos" text="Ativos" icon="fas fa-user-check" theme="success" url="{{ route('prestador.consulta',['tipo' => 'A']) }}" url-text="Detalhes de Prestadores Ativos"/>
    </div>
    <div class="col-md-3"> 
        <x-adminlte-small-box :title="$prestadoresDemitidos" text="Demitidos" icon="fas fa-user-xmark" theme="danger" url="{{ route('prestador.consulta',['tipo' => 'D']) }}" url-text="Detalhes de Prestadores Demitidos"/>
    </div>
    <div class="col-md-3"> 
        <x-adminlte-small-box title="Cadastro" text="Prestadores" icon="fas fa-user-plus" theme="primary" url="{{ route('prestador.cadastro') }}" url-text="Cadastrar Prestador"/>
    </div>
</div>
    
<div class="row">
    <div class="col-md-12"> 
        {{-- Setup data for datatables --}}
        @php
        $heads = [
            ['label' => '', 'no-export' => true, 'width' => 5],
            'Código',
            'Nome',
            'Tipo do Usuário',
            'Status',
            ['label' => 'Opção', 'no-export' => true, 'width' => 5],
        ];
        
        $config = [
            'searching' => false,
            'lengthChange' => false,
            'pageLength' => 5,
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
        @endphp

        <x-adminlte-card title="Lista de Prestadores Ativos" theme="navy" theme-mode="outline">
            <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable>
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
                        <td>{{ $prestador->prestador_codigo }}</td>
                        <td>{{ $prestador->prestador_nome }}</td>
                        <td>{{ $prestador->prestador_cpf }}</td>
                        <td>{{ $prestador->prestador_set }}</td>
                        <td>
                            <nobr class="d-flex justify-content-center">
                                <form method="get" action="{{route('prestador.editarCadastro', ['dadosPrestador' => $prestador->prestador_codigo, 'empresa' => $prestador->prestador_empresa])}}" style="float: left;">
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
        </x-adminlte-card>
    </div>
</div>
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
