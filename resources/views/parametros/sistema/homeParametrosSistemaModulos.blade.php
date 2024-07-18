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
            'Módulo de Serviços',
            'Módulo Emissão de RPS',
            'Módulo Emissão de NFS-e',
            'Módulo Emissão Simplificada de NFS-e',
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
            'columns' => [null, null, null, null, null, ['orderable' => false]],
        ];
        @endphp

        <x-adminlte-card title="Módulos do Sistema Parametrizados" theme="navy" theme-mode="outline">
            <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable with-buttons>
                @foreach ($modulos as $modulo)
                    @php
                        $data_emp = DB::table('cadastro_empresas')->where('empresa_codigo','=',$modulo->modulo_empresa_codigo)->get();
                        $empresa = $modulo->modulo_empresa_codigo.' - '.$data_emp[0]->empresa_nome;
                        
                        $emiNFS = Helper::formataSimNao($modulo->modulo_emissao_nfs);
                        $emiRPS = Helper::formataSimNao($modulo->modulo_emissao_rps);
                        $modSrv = Helper::formataSimNao($modulo->modulo_servico);
                        $emiSimpNFS = Helper::formataSimNao($modulo->modulo_emissao_nfs_simp);
                    @endphp
                    <tr>   
                        <td>{{ $empresa }}</td>
                        <td>{{ $modSrv }}</td>
                        <td>{{ $emiRPS }}</td>
                        <td>{{ $emiNFS }}</td>
                        <td>{{ $emiSimpNFS }}</td>
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
