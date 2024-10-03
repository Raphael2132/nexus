@extends('adminlte::page')

@section('title', 'Módulos do Sistema')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Parâmetros do Sistema</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Módulos do Sistema</li>
        </ol>
    </div>
</div>
@stop

@section('content')    
<div class="row">
    <div class="col-md-12"> 
        {{-- Setup data for datatables --}}
        @php
            $heads = [
                'Empresa',
                'Plano Contratado',
                'Data de Vencimento da Licença',
                'Qtd. de Usuários',
                'Qtd. de Usuários Extra',
                ['label' => 'Opção', 'no-export' => true, 'width' => 5],
            ];
            
            $config = [
                'lengthMenu' => [ 5, 10, 25, 50],
                'pageLength' => 5,
                'language' => Helper::dataTableLangPtBR(),
                'order' => [[0, 'asc']],
                'columns' => [null, null, null, null, null, ['orderable' => false]],
            ];
        @endphp

        <x-adminlte-card title="Módulos do Sistema" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
            <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable with-buttons>
                @foreach ($modulos as $modulo)
                    @php
                        $data_emp = DB::table('cadastro_empresas')->where('empresa_codigo','=',$modulo->modulo_empresa_codigo)->get();
                        $empresa = $modulo->modulo_empresa_codigo.' - '.$data_emp[0]->empresa_nome;

                        $dataPla = DB::table('parametros_sis_planos')->where('plano_codigo','=',$modulo->modulo_plano)->first();
                        $plano = $modulo->modulo_plano.' - '.$dataPla->plano_nome;
                    @endphp
                    <tr>   
                        <td>{{ $empresa }}</td>
                        <td>{{ $plano }}</td>
                        <td>{{ Helper::formataData($modulo->modulo_dt_validade) }}</td>
                        <td>{{ $modulo->modulo_qtd_usuarios }}</td>
                        <td>{{ $modulo->modulo_qtd_usuarios_ext }}</td>
                        <td>
                            <nobr class="d-flex justify-content-center">
                                <form method="get" action="{{ route('parametrosSistemaModulos.editarCadastro', ['dadosModulo' => $modulo->modulo_empresa_codigo]) }}" style="float: left;">
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
