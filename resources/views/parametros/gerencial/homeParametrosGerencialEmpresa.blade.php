@extends('adminlte::page')

@section('title', 'Parâmetros Gerais Gerencial')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h4 style="margin-bottom: 0px !important;">Parâmetros Gerais Gerencial</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Geral da Empresa</li>
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
            'Dias de Funcionamento',
            'Hora Ini. Funcionamento',
            'Hora Fin. Funcionamento',
            'Intervalo de Funcionamento',
            'Hora Ini. Intervalo',
            'Hora Fin. Intervalo',
            'Turno de Serviço',
            ['label' => 'Opções', 'no-export' => true, 'width' => 5],
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
            'columns' => [null, null, null, null, null,null, null, null,['orderable' => false]],
        ];
        @endphp
        <x-adminlte-card  title="Empresas Cadastradas" theme="navy" theme-mode="outline">
            <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable compressed>
                @foreach ($dataParGerEmp as $empresa)
                    @php
                        $dataEmp = DB::table('cadastro_empresas')->where('empresa_codigo',$empresa->parger_emp)->get();
                        $emp = $empresa->parger_emp.' - '.$dataEmp[0]->empresa_nome;

                        if($empresa->parger_dia_fun == '1'){
                            $diaFun = 'Segunda à Sexta';
                        }elseif($empresa->parger_dia_fun == '2'){
                            $diaFun = 'Segunda à Sábado';
                        }else{
                            $diaFun = 'Segunda à Domingo';
                        }
                    @endphp
                    <tr>
                        <td>{{ $emp }}</td>
                        <td>{{ $diaFun }}</td>
                        <td>{{ Helper::formataHoraMinuto($empresa->parger_hr_ini_fun) }}</td>
                        <td>{{ Helper::formataHoraMinuto($empresa->parger_hr_fin_fun) }}</td>
                        <td>{{ Helper::formataSimNao($empresa->parger_int_fun) }}</td>
                        <td>{{ Helper::formataHoraMinuto($empresa->parger_hr_ini_int) }}</td>
                        <td>{{ Helper::formataHoraMinuto($empresa->parger_hr_fin_int) }}</td>
                        <td>{{ Helper::formataSimNao($empresa->parger_tur_srv) }}</td>
                        <td>
                            <nobr class="d-flex justify-content-center">
                                <form method="get" action="{{ route('parametrosGerEmp.editarCadastro', ['empresa' => $empresa->parger_emp]) }}" style="float: left;">
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
</script>
@stop
