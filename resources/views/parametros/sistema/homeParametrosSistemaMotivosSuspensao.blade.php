@extends('adminlte::page')

@section('title', 'Motivos de Suspensão')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Parâmetros Gerais</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Motivos de Suspensão</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="col-md-4">
    <x-adminlte-small-box title="Motivos" text="Suspensão" icon="fas fa-triangle-exclamation" theme="primary" url="{{route('parametrosSisMotSus.cadastroMotSus',['acao' => 'N', 'dadosMotSus' => ' '])}}" url-text="Cadastrar"/>
</div>
<div class="col-md-12">
    {{-- Setup data for datatables --}}
    @php
    $heads = [
        'Código',
        'Descrição',
        ['label' => 'Editar', 'no-export' => true, 'width' => 10],
    ];

    $config = [
        'lengthMenu' => [ 5, 10, 25, 50],
        'pageLength' => 10,
        'language' => Helper::dataTableLangPtBR(),
        'pagingType' => 'full_numbers',
        'columns' => [null, null, ['orderable' => false]],
    ];
    @endphp
    <x-adminlte-card title="Motivos de Suspensão do Sistema" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
        <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable with-buttons>
            @foreach ($motivos as $motivo)
                <tr>
                    <td>{{ $motivo->susmot_codigo }}</td>
                    <td>{{ $motivo->susmot_desc }}</td>
                    <td>
                        <nobr class="d-flex justify-content-center">
                            <form method="get" action="{{route('parametrosSisMotSus.cadastroMotSus',['acao' => 'E', 'dadosMotSus' => $motivo->susmot_codigo])}}" style="float: left;">
                                @csrf
                                <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Editar Registro" value="Edit" type="submit">
                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                </button>
                            </form>
                            <form method="post" action="{{ route('parametrosSisMotSus.destroy', ['motivo' => $motivo, 'origem' => 'home']) }}" style="float: left;">
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
    </x-adminlte-card>
</div>
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
