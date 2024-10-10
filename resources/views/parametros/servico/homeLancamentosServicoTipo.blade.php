@extends('adminlte::page')

@section('title', 'Tipos de Serviço')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Parâmetros Gerais</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Tipos de Serviço</li>
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
    <x-adminlte-small-box title="Tipos" text="Serviço" icon="fas fa-list-ol" theme="primary" url="{{route('lancamentosSrvTipo.cadastro')}}" url-text="Cadastrar"/>
</div>
<div class="col-md-12">
    {{-- Setup data for datatables --}}
    @php    
    $heads = [
        'Empresa',
        'Código',
        'Descrição',
        'Categoria',
        'Área',
        'Status',
        ['label' => 'Editar', 'no-export' => true, 'width' => 10],
    ];

    $config = [
        'lengthMenu' => [ 5, 10, 25, 50],
        'language' => Helper::dataTableLangPtBR(),
        'pagingType' => 'full_numbers',
        'order' => [[0, 'asc'],[3, 'asc'],[1, 'asc']],
        'columns' => [null, null, null, null, null, null, ['orderable' => false]],
    ];
    @endphp
    <x-adminlte-card title="Parametrização dos Tipos de Serviço" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
        <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable with-buttons>
            @foreach ($tipos as $tipo)
                @php 
                    $data = DB::table('cadastro_empresas')->select('empresa_codigo', 'empresa_nome')->where('empresa_codigo', $tipo->tipsrv_emp)->get();

                    $empresa = $tipo->tipsrv_emp.' - '.$data[0]->empresa_nome;

                    $data_cat = DB::table('lancamento_srv_categorias')->select('categoria_codigo', 'categoria_desc')->where('categoria_codigo', $tipo->tipsrv_cat)->get();

                    $categoria = $tipo->tipsrv_cat.' - '.$data_cat[0]->categoria_desc;

                    $data_are = DB::table('parametros_sis_areas')->select('area_codigo', 'area_desc')->where('area_codigo', $tipo->tipsrv_are)->get();

                    $area = $tipo->tipsrv_are.' - '.$data_are[0]->area_desc;

                    if($tipo->tipsrv_sts == 'A'){
                        $status = 'Ativado';
                    }else{
                        $status = 'Desativado';
                    }
                @endphp
                <tr>
                    <td>{{ $empresa }}</td>
                    <td>{{ $tipo->tipsrv_cod }}</td>
                    <td>{{ $tipo->tipsrv_nom }}</td>
                    <td>{{ $categoria }}</td>
                    <td>{{ $area }}</td>
                    <td>{{ $status }}</td>
                    <td>
                        <nobr class="d-flex justify-content-center">
                            <form method="get" action="{{route('lancamentosSrvTipo.editarCadastro', ['codigo' => $tipo->tipsrv_cod, 'empresa' => $tipo->tipsrv_emp])}}" style="float: left;">
                                @csrf
                                <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Editar Registro" value="Edit" type="submit">
                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                </button>
                            </form>
                            <form method="post" action="{{ route('lancamentosSrvTipo.destroy',['tipo'=>$tipo, 'origem'=>'home']) }}" style="float: left;">
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
