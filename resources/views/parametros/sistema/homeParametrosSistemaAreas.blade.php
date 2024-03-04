@extends('adminlte::page')

@section('title', 'Módulos do Sistema')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h1>Parâmetros do Sistema</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Áreas</li>
            </ol>
        </div>
    </div>
@stop

@section('content')    
<div class="esquerdo col-md-9">
    {{-- Setup data for datatables --}}
    @php
    $heads = [
        'Código',
        'Área',
        ['label' => 'Opção', 'no-export' => true, 'width' => 10],
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
        'columns' => [null, null, ['orderable' => false]],
    ];
    @endphp

    <x-adminlte-card title="Áreas do Sistema Parametrizadas" theme="navy" theme-mode="outline">
        <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable with-buttons>
            @foreach ($areas as $area)
                <tr>   
                    <td>{{ $area->area_codigo }}</td>
                    <td>{{ $area->area_desc }}</td>
                    <td>
                        <nobr class="d-flex justify-content-center">
                            <form method="get" action="{{route('parametrosSistemaAreas.editarCadastro', ['area' => $area->area_codigo])}}" style="float: left;">
                                @csrf 
                                <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Editar Registro" value="Edit" type="submit">
                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                </button>
                            </form>
                            <form method="post" action="{{route('parametrosSistemaAreas.destroy', ['area' => $area])}}" style="float: left;">
                                @csrf 
                                @method('delete')
                                <button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" value="Delete" type="submit" >
                                    <i class="fa fa-lg fa-fw fa-trash"></i>
                                </button>
                            </form>
                        </nobr>
                    </td>             
                </tr>
            @endforeach
        </x-adminlte-datatable>
    </x-adminlte-card>
</div>
<div class="direito col-md-3">
    <x-adminlte-small-box title="Áreas" text="Sistema" icon="fas fa-globe" theme="primary" url="{{route('parametrosSistemaAreas.cadastro')}}" url-text="Cadastrar"/>
</div>
@stop

<!-- Chamada dos Plugins usados na app -->  
@section('plugins.Sweetalert2', true)
@section('plugins.toastr', true)

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
