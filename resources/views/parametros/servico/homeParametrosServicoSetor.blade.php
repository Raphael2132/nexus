@extends('adminlte::page')

@section('title', 'Parametrização dos Setores')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h4 style="margin-bottom: 0px !important;">Parâmetros Gerais</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Setor</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="col-md-4">
    <x-adminlte-small-box title="Setores" text="Empresa" icon="fas fa-folder-tree" theme="primary" url="{{route('parametrosSrvSetor.cadastro')}}" url-text="Cadastrar"/>
</div>
<div class="col-md-12">
    {{-- Setup data for datatables --}}
    @php
    $heads = [
        'Empresa',
        'Área',
        'Código do Setor',
        'Setor',
        ['label' => 'Editar', 'no-export' => true, 'width' => 10],
    ];

    $config = [
        'lengthMenu' => [ 5, 10, 25, 50],
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
        'columns' => [null, null, null, null, ['orderable' => false]],
    ];
    @endphp
    <x-adminlte-card title="Parametrização dos Setores" theme="navy" theme-mode="outline" collapsible maximizable>
        <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable with-buttons>
            @foreach ($setores as $setor)
                @php
                    $data_emp = DB::table('cadastro_empresas')->where('empresa_codigo','=',$setor->setor_empresa)->get();
                    $empresa = $setor->setor_empresa.' - '.$data_emp[0]->empresa_nome;

                    $data_area = DB::table('parametros_sistema_areas')->where('area_codigo','=',$setor->setor_area)->get();
                    $area = $setor->setor_area.' - '.$data_area[0]->area_desc;
                @endphp
                <tr>
                    <td>{{ $empresa }}</td>
                    <td>{{ $area }}</td>
                    <td>{{ $setor->setor_codigo }}</td>
                    <td>{{ $setor->setor_desc }}</td>
                    <td>
                        <nobr class="d-flex justify-content-center">
                            <form method="get" action="{{route('parametrosSrvSetor.editarCadastro', ['codigo' => $setor->setor_codigo, 'empresa' => $setor->setor_empresa, 'area' => $setor->setor_area])}}" style="float: left;">
                                @csrf
                                <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" value="Edit" type="submit">
                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                </button>
                            </form>
                            <form method="post" action="{{ route('parametrosSrvSetor.destroy',['setor'=>$setor, 'origem'=>'home']) }}" style="float: left;">
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
