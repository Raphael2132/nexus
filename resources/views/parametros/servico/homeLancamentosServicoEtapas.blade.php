@extends('adminlte::page')

@section('title', 'Etapas de Atendimento')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Parâmetros Gerais</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Etapas de Atendimento</li>
            @php   
                $dadosEmp = Helper::buscaDadosEmpresa(session('glo_empresa_exibicao_home'));
            @endphp
            <li class="breadcrumb-item active">{{ $dadosEmp->empresa_codigo.' - '.$dadosEmp->empresa_nome}}</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="col-md-4">
    <x-adminlte-small-box title="Etapas" text="Atendimento" icon="fas fa-building-flag" theme="primary" url="{{route('lancamentosSrvEtapas.cadastro')}}" url-text="Cadastrar"/>
</div>
<div class="col-md-12">
    {{-- Setup data for datatables --}}
    @php    
    $heads = [
        'Empresa',
        'Categoria',
        'Grupo Atendimento',
        'Ordem',
        'Descrição',
        ['label' => 'Editar', 'no-export' => true, 'width' => 10],
    ];

    $config = [
        'lengthMenu' => [ 5, 10, 25, 50],
        'language' => Helper::dataTableLangPtBR(),
        'pagingType' => 'full_numbers',
        'order' => [[0, 'asc']],
        'columns' => [null, null, null, null, null, ['orderable' => false]],
    ];
    @endphp
    <x-adminlte-card title="Parametrização das Etapas de Atendimento" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
        <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable with-buttons>
            @foreach ($etapas as $etapa)
                @php 
                    $data = DB::table('cadastro_empresas')->select('empresa_codigo', 'empresa_nome')->where('empresa_codigo', $etapa->eat_emp)->get();
                    $empresa = $etapa->eat_emp.' - '.$data[0]->empresa_nome;

                    $data_cat = DB::table('lancamento_srv_categorias')->select('categoria_desc')->where('categoria_codigo', $etapa->eat_cat)->get();
                    $categoria = $etapa->eat_cat.' - '.$data_cat[0]->categoria_desc;
                @endphp
                <tr>
                    <td>{{ $empresa }}</td>
                    <td>{{ $categoria }}</td>
                    <td>{{ $etapa->eat_cod }}</td>
                    <td>{{ $etapa->eat_ord }}</td>
                    <td>{{ $etapa->eat_nom }}</td>
                    <td>
                        <nobr class="d-flex justify-content-center">
                            <form method="get" action="{{route('lancamentosSrvEtapas.editarCadastro', ['codigo' => $etapa->eat_cod, 'empresa' =>$etapa->eat_emp])}}" style="float: left;">
                                @csrf
                                <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Editar Registro" value="Edit" type="submit">
                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                </button>
                            </form>
                            <form method="post" action="{{ route('lancamentosSrvEtapas.destroy',['etapa'=>$etapa, 'origem'=>'home']) }}" style="float: left;">
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
