@extends('adminlte::page')

@section('title', 'Parametrização dos Setores')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h1>Parâmetros Gerais</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Tarefas Mão de Obra</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="col-md-4">
    <x-adminlte-small-box title="Serviço" text="Tarefas de Mão de Obra" icon="fas fa-people-roof" theme="primary" url="{{route('parametrosSrvTMO.cadastro')}}" url-text="Cadastrar"/>
</div>
<div class="col-md-12">
    {{-- Setup data for datatables --}}
    @php
    $heads = [
        ['label' => '', 'no-export' => true, 'width' => 5],
        'Empresa',
        'Setor',
        'Tarefa',
        'Tipo',
        'Qtd. Horas',
        'Valor Hora',
        'Valor Total',
        ['label' => 'Editar', 'no-export' => true, 'width' => 10],
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
        'columns' => [['orderable' => false], null, null, null, null, null, null, null, ['orderable' => false]],
    ];
    @endphp
    <x-adminlte-card title="Parametrização das Tarefas de Mão de Obra" theme="navy" theme-mode="outline" collapsible maximizable>
        <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable with-buttons>
            @foreach ($tarefas as $tarefa)
                @php
                    $data_emp = DB::table('cadastro_empresas')->where('empresa_codigo','=',$tarefa->tmo_emp)->get();
                    $empresa = $tarefa->tmo_emp.' - '.$data_emp[0]->empresa_nome;

                    $data_set = DB::table('parametros_srv_setores')->where('setor_codigo','=',$tarefa->tmo_set)->get();
                    $setor = $tarefa->tmo_set.' - '.$data_set[0]->setor_desc;

                    $tarefa_tmo = $tarefa->tmo_cod.' - '.$tarefa->tmo_dsc;

                    if($tarefa->tmo_tip == 'P'){
                        $tipo_tmo = 'Padrão';
                    }elseif($tarefa->tmo_tip == 'I'){
                        $tipo_tmo = 'Hora Informada';
                    }elseif($tarefa->tmo_tip == 'R'){
                        $tipo_tmo = 'Hora Real';
                    }elseif($tarefa->tmo_tip == 'F'){
                        $tipo_tmo = 'Valor Fixo';
                    }else{
                        $tipo_tmo = 'Terceiros';
                    }

                    $qtd_hr = number_format($tarefa->tmo_qtd_hr,2,",",".");
                    $val_hr = 'R$'.number_format($tarefa->tmo_val_hr,2,",",".");
                    $val_tot = 'R$'.number_format($tarefa->tmo_val_tot,2,",",".");

                    if($tarefa->tmo_tip_val_cgt == '1'){
                        $tip_val_cgt = 'Valor';
                    }else{
                        $tip_val_cgt = 'Porcentagem';
                    }

                    $per_cgt = number_format($tarefa->tmo_per_cgt,2,",",".").'%';
                    $val_cgt = 'R$'.number_format($tarefa->tmo_val_cgt,2,",",".");

                    if($tarefa->tmo_alt_val == 'S'){
                        $alt_val = 'Sim';
                    }else{
                        $alt_val = 'Não';
                    }

                    if($tarefa->tmo_sts == 'A'){
                        $sts = 'Ativo';
                    }else{
                        $sts = 'Desativado';
                    }
                    
                    if(!empty($tarefa->tmo_for_cgt)){
                        $data_for = DB::table('cadastro_clientes')->where('cliente_codigo','=',$tarefa->tmo_for_cgt)->get();
                        $fornecedor = $tarefa->tmo_for_cgt.' - '.$data_for[0]->cliente_nome;
                    }else{
                        $fornecedor = '';
                    }

                    if(!empty($tarefa->tmo_cmp)){
                        $complemento = $tarefa->tmo_cmp;
                    }else{
                        $complemento = '';
                    }

                    if(!empty($tarefa->tmo_srv_grp)){
                        $data_grp = DB::table('parametros_sistema_servico_grupos')->where('grupo_codigo',$tarefa->tmo_srv_grp)->get();
                        $srv_grp_tmo = $tarefa->tmo_srv_grp.' - '.$data_grp[0]->grupo_desc;

                        $data_srv = DB::table('parametros_sistema_servicos')->where('servico_grupo',$tarefa->tmo_srv_grp)->where('servico_codigo',$tarefa->tmo_srv_cod)->get();
                        $srv_cod_tmo = $tarefa->tmo_srv_cod.' - '.$data_srv[0]->servico_desc;
                    }else{
                        $srv_grp_tmo = '';
                        $srv_cod_tmo = '';
                    }
                @endphp
                <tr>
                    <td>
                        <nobr class="d-flex justify-content-center">
                            <!-- Cria o modal dos Detalhes da Tarefa de Mão de Obra -->
                            <x-adminlte-modal id="modalCustom_{{$tarefa->tmo_id}}" title="Detalhes da Tarefa de Mão de Obra" size="xl" theme="navy" v-centered scrollable>
                                <div class="row" style="height:auto;">
                                    <!-- Conteudo da esquerda do modal -->
                                    <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                                        <div class="post">
                                            <h4 class="text-primary">Dados Gerais</h4>
                                            <div class="text-muted">
                                                <div class="row quebra-linha">
                                                    <p class="text-sm col-md-6">Empresa
                                                        <b class="d-block">{{ $empresa }}</b>
                                                    </p>
                                                    <p class="text-sm col-md-6">Setor
                                                        <b class="d-block">{{ $setor }}</b>
                                                    </p>
                                                </div>
                                                <div class="row quebra-linha">
                                                    <p class="text-sm col-md-6">Tarefa
                                                        <b class="d-block">{{ $tarefa_tmo }}</b>
                                                    </p>
                                                    <p class="text-sm col-md-6">Complemento
                                                        <b class="d-block">{{ $complemento }}</b>
                                                    </p>
                                                </div>
                                                <div class="row quebra-linha">
                                                    <p class="text-sm col-md-6">Grupo do Serviço
                                                        <b class="d-block">{{ $srv_grp_tmo }}</b>
                                                    </p>
                                                    <p class="text-sm col-md-6">Código do Serviço
                                                        <b class="d-block">{{ $srv_cod_tmo }}</b>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="post">
                                            <h4 class="text-primary">Valores</h4>
                                            <div class="text-muted">
                                                <div class="row">
                                                    <p class="text-sm col-md-4">Tipo
                                                        <b class="d-block">{{ $tipo_tmo }}</b>
                                                    </p>
                                                    @if(!empty($tarefa->tmo_for_cgt))
                                                    <p class="text-sm col-md-8">Fornecedor
                                                        <b class="d-block">{{ $fornecedor }}</b>
                                                    </p> 
                                                    @endif
                                                </div>
                                                <div class="row">
                                                    <p class="text-sm col-md-4">Quantidade Horas 
                                                        <b class="d-block">{{ $qtd_hr }}</b>
                                                    </p>
                                                    <p class="text-sm col-md-4">Valor Hora
                                                        <b class="d-block">{{ $val_hr }}</b>
                                                    </p>
                                                    <p class="text-sm col-md-4">Valor Total
                                                        <b class="d-block">{{ $val_tot }}</b>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Conteudo da direita do modal -->
                                    <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                                        <h4 class="text-primary">Custo da Tarefa</h4>
                                        <div class="col-sm-4 invoice-col">
                                            <div class="text-muted">
                                                <p class="text-sm">Tipo do Custo
                                                    <b class="d-block">{{$tip_val_cgt}}</b>
                                                </p>
                                                <p class="text-sm">Valor do Custo
                                                    <b class="d-block">{{$val_cgt}}</b>
                                                </p>
                                                @if($tarefa->tmo_tip_val_cgt == '2')
                                                <p class="text-sm">Percentual do Custo
                                                    <b class="d-block">{{$per_cgt}}</b>
                                                </p>
                                                @endif
                                            </div>                                                
                                        </div>
                                        <h4 class="text-primary">Status</h4>
                                        <!-- Dados do contato -->
                                        <div class="text-muted">
                                            <p class="text-sm">Alteração do Valor na OS
                                                <b class="d-block">{{ $alt_val }}</b>
                                            </p>
                                            <p class="text-sm">Status
                                                <b class="d-block">{{ $sts }}</b>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <x-slot name="footerSlot">
                                    <x-adminlte-button theme="info" label="Voltar" data-dismiss="modal"/>
                                </x-slot>
                            </x-adminlte-modal>
                            <!-- Gera o icone da lupa que abre o modal -->
                            <a class="text-muted" data-toggle="modal" title="Detalhes da TMO" data-target="#modalCustom_{{$tarefa->tmo_id}}">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </a>
                        </nobr>
                    </td>
                    <td>{{ $empresa }}</td>
                    <td>{{ $setor }}</td>
                    <td>{{ $tarefa_tmo }}</td>
                    <td>{{ $tipo_tmo }}</td>
                    <td>{{ $qtd_hr }}</td>
                    <td>{{ $val_hr }}</td>
                    <td>{{ $val_tot }}</td>
                    <td>
                        <nobr class="d-flex justify-content-center">
                            <form method="get" action="{{ route('parametrosSrvTMO.editarCadastro',['empresa' => $tarefa->tmo_emp, 'setor' => $tarefa->tmo_set, 'codigo' => $tarefa->tmo_cod]) }}" style="float: left;">
                                @csrf
                                <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" value="Edit" type="submit">
                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                </button>
                            </form>
                            <form method="post" action="{{ route('parametrosSrvTMO.destroy', ['tarefa'=>$tarefa, 'origem'=>'home']) }}" style="float: left;">
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
<style>
    .quebra-linha{
        word-wrap: break-word;      /* IE 5.5-7 */
        white-space: -moz-pre-wrap; /* Firefox 1.0-2.0 */
        white-space: pre-wrap;      /* current browsers */
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
