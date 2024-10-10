@extends('adminlte::page')

@section('title', 'Grupos e Serviços da NFS-e')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Parâmetros do Sistema</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Grupos e Serviços da NFS-e</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-md-3">
    <x-adminlte-small-box title="Grupo" text="Serviços de NFS-e" icon="fas fa-users-rays" theme="primary" url="{{ route('parametrosSistemaGrpServico.cadastro') }}" url-text="Cadastrar"/>
    </div>
    <div class="col-md-3">
    <x-adminlte-small-box title="Serviços" text="NFS-e" icon="fas fa-people-carry-box" theme="success" url="{{ route('parametrosSistemaServico.cadastro') }}" url-text="Cadastrar"/>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        {{-- Setup data for datatables --}}
        @php
        $heads = [
            'Grupo',
            'Descrição',
            ['label' => 'Editar', 'no-export' => true, 'width' => 10],
        ];

        $config = [
            'lengthMenu' => [ 5, 10, 25, 50],
            'pageLength' => 5,
            'language' => Helper::dataTableLangPtBR(),
            'pagingType' => 'full_numbers',
            'columns' => [null, null, ['orderable' => false]],
        ];
        @endphp
        <x-adminlte-card title="Parametrização dos Grupos de Serviço da NFS-e" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
            <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable with-buttons>
                @foreach ($grupos as $grupo)
                    <tr>
                        <td>{{ $grupo->grupo_codigo }}</td>
                        <td>{{ $grupo->grupo_desc }}</td>
                        <td>
                            <nobr class="d-flex justify-content-center">
                                <form method="get" action="{{ route('parametrosSistemaGrpServico.editarCadastro' , ['dadosGrupo' => $grupo->grupo_codigo]) }}" style="float: left;">
                                    @csrf
                                    <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Editar Registro" value="Edit" type="submit">
                                        <i class="fa fa-lg fa-fw fa-pen"></i>
                                    </button>
                                </form>
                                <form method="post" action="{{route('parametrosSistemaGrpServico.destroy', ['grupo' => $grupo])}}" style="float: left;">
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
    <div class="col-md-6">
    {{-- Setup data for datatables --}}
        @php
        $heads2 = [
            'Grupo',
            'Descrição Grupo',
            'Código',
            'Descrição',
            ['label' => 'Editar', 'no-export' => true, 'width' => 10],
        ];

        $config2 = [
            'lengthMenu' => [ 5, 10, 25, 50],
            'pageLength' => 5,
            'language' => Helper::dataTableLangPtBR(),
            'pagingType' => 'full_numbers',
            'columns' => [null, null, null, null, ['orderable' => false]],
        ];
        @endphp
        <x-adminlte-card title="Parametrização dos Serviços da NFS-e" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
            <x-adminlte-datatable id="table2" :heads="$heads2" :config="$config2" theme="light" striped hoverable with-buttons>
                @foreach ($servicos as $servico)
                    @php
                        $data_dsc = DB::table('parametros_sis_servico_grupos')->where('grupo_codigo','=',$servico->servico_grupo)->get();
                        $grupo_completo = $servico->servico_grupo.' - '.$data_dsc[0]->grupo_desc;
                    @endphp
                    <tr>
                        <td>{{ $servico->servico_grupo }}</td>
                        <td>{{ $data_dsc[0]->grupo_desc }}</td>
                        <td>{{ $servico->servico_codigo }}</td>
                        <td>{{ $servico->servico_desc }}</td>
                        <td>
                            <nobr class="d-flex justify-content-center">
                                <form method="get" action="{{ route('parametrosSistemaServico.editarCadastro', ['grupo' => $servico->servico_grupo, 'servico' => $servico->servico_codigo]) }}" style="float: left;">
                                    @csrf
                                    <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Editar Registro" value="Edit" type="submit">
                                        <i class="fa fa-lg fa-fw fa-pen"></i>
                                    </button>
                                </form>
                                <form method="post" action="{{route('parametrosSistemaServico.destroy', ['servico' => $servico])}}" style="float: left;">
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
