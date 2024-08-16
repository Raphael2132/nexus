@extends('adminlte::page')

@section('title', 'Painel de Operação')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Controle de Produção</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Painel de Operação</li>
        </ol>
    </div>
</div>
@stop

@section('content')

@php 

//Gera Cabeçalho e Config da Tabela Principal das OS
$heads = [
    ['label' => '', 'no-export' => true, 'width' => '3%'],
    'Cliente',
    'OS',
    'Consultor',
    'Agenda',
    'Situação da Produção',
    'Previsão Entrega'
];
$config = [
    'lengthMenu' => [ 10, 25, 50, 100],
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
    'order' => [[2, 'desc']],
    'columns' => [['orderable' => false],['orderable' => false],['orderable' => false], ['orderable' => false], ['orderable' => false], ['orderable' => false], ['orderable' => false]],
];

//Define as variaveis de sessão aqui na view por que depois de 2 redirect elas são destruidas
$_SESSION['where_consulta_painelOperador'] = $empresa_os;
$_SESSION['empresaOS_consulta_painelOperador'] = $where_app;
@endphp

<x-adminlte-card title="Painel de Operações da Produção" theme="navy" theme-mode="outline">
    <!-- Tabela Principal da OS -->
    <x-adminlte-datatable class="main-table" id="table1" :heads="$heads" :config="$config" head-theme="dark" theme="light" striped hoverable bordered compressed beautify>
        @foreach ($dadosOS as $os)
            @php 
                //Busca os dados dos Serviços relacionados a OS
                $dadosTMO = DB::table('lancamento_srv_exe_tarefas')
                ->where('exetrf_emp', $empresa_os)
                ->where('exetrf_nos', $os->os_nos)
                ->orderby('exetrf_req', 'asc')
                ->orderby('exetrf_seq', 'asc')
                ->get();

                //Gera os Dados Sobre o Cliente e o Usuário da OS
                $dadosCli =  DB::table('cadastro_clientes')->where('cliente_codigo', $os->os_cli)->get();
                $dadosUsu =  DB::table('users')->where('usuario_codigo', $os->os_res_abr)->get();
            @endphp
            <tr>
                <td>
                    <!-- Link para o Painel da OS -->
                    <a href="{{route('situacaoOS.carregaOS', ['empresa' => $os->os_emp, 'cliente' => $os->os_cli, 'nos' => $os->os_nos, 'estagioAPP' => 'PRINCIPAL'])}}" class="text-muted" title="Visualisar OS">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </a>
                </td>
                <td>{{ $os->os_cli.' - '.$dadosCli[0]->cliente_nome }}</td>
                <td>
                    <!-- Gera o Icone do Status da OS -->
                    @if($os->os_sts == 'A')
                    <i class="fa-solid fa-screwdriver-wrench fa-lg text-info mx-2"></i>
                    @elseif($os->os_sts == 'F')
                    <i class="fa-solid fa-thumbs-up fa-lg text-success mx-2"></i>
                    @else
                    <i class="fa-solid fa-ban fa-lg text-danger mx-2"></i>
                    @endif
                    <span class="mx-2">{{ $os->os_nos }}</span>
                </td>
                <td>{{ $os->os_res_abr.' - '.$dadosUsu[0]->name }}</td>
                <td>
                    <!-- Gera o botão que abre o Modal de Agenda dos Serviços da OS -->
                    <button type="button" class="btn btn-xl" title="Agenda da OS" 
                        onclick="openModalAgendaSrvOS('{{ $os->os_emp }}', '{{ $os->os_nos }}')">
                        <i class="fa-solid fa-clipboard-list fa-xl" style="color: #6610f2;"></i>
                    </button>
                </td>
                <td>
                    <!-- Se Existe Tarefas Aprovadas na OS gera a tabela dos serviços -->
                    @if(!empty($dadosTMO[0]))
                    <table class="table nested-table">
                        <thead>
                            <tr>
                                <th>Req.</th>
                                <th>Setor</th>
                                <th>Auxiliar</th>
                                <th>Prestador</th>
                                <th>TMO</th>
                                <th>Situação</th>
                                <th>Tempo</th>
                                <th>Hr Ini</th>
                                <th>Hr Fin</th>
                                <th>Hr Real</th>
                                <th>Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dadosTMO as $tmo)
                                @php 
                                    if(!empty($tmo->exetrf_prt)){
                                        $dadosPrest =  DB::table('cadastro_prestadores')->where('prestador_codigo', $tmo->exetrf_prt)->get();
                                        $prestador = $tmo->exetrf_prt.' - '.$dadosPrest[0]->prestador_nome;
                                    }else{
                                        $prestador = 'Não Alocado';
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $tmo->exetrf_req }}</td>
                                    <td>{{ $tmo->exetrf_set }}</td>
                                    <td>
                                        @php 
                                            $cntAux = DB::table('lancamento_srv_prt_auxiliares')->where('prtaux_emp', $tmo->exetrf_emp)->where('prtaux_nos', $tmo->exetrf_nos)->where('prtaux_req', $tmo->exetrf_req)->where('prtaux_srv', $tmo->exetrf_seq)->count('prtaux_prt');
                                        @endphp
                                        <button type="button" class="btn btn-xl" title="Adicionar Novo Auxiliar" 
                                            onclick="openModalAddAux('{{ $tmo->exetrf_emp }}', '{{ $tmo->exetrf_nos }}', '{{ $tmo->exetrf_req }}', '{{ $tmo->exetrf_seq }}')">
                                            @if($cntAux == 0)
                                            <i class="fa-solid fa-users-slash fa-lg" style="color: #808080;"></i>
                                            @else
                                            <i class="fa-solid fa-users fa-lg" style="color: #74C0FC;"></i>
                                            @endif
                                        </button>
                                    </td>
                                    <td style="display: flex; align-items: center;">
                                        <button type="button" class="btn btn-xl" title="Adicionar Novo Auxiliar"
                                            onclick="openModalAddChangePrt('{{ $tmo->exetrf_emp }}', '{{ $tmo->exetrf_nos }}', '{{ $tmo->exetrf_req }}', '{{ $tmo->exetrf_seq }}')">
                                            @if(!empty($tmo->exetrf_prt))
                                            <i class="fa-solid fa-user-pen fa-lg" style="color: #39cccc;"></i>
                                            @else
                                            <i class="fa-solid fa-user-plus fa-lg" style="color: #007bff;"></i>
                                            @endif
                                        </button>
                                        <span style="flex-grow: 1; text-align: center;">{{ $prestador }}</span>
                                    </td>
                                    <td>{{$tmo->exetrf_tmo.' - '.$tmo->exetrf_desc}}</td>
                                    <td>
                                        @if($tmo->exetrf_sts == 'F')
                                        <div class="btn-group" style="width: 100%;"style="width: 100%;">
                                            <button type="button" class="btn btn-success btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Finalizada <span class="caret"></span>
                                            </button>	
                                            <ul class="dropdown-menu">
                                                <li><a href="#" class="execucao-tarefa" onclick="openModalReopenService('{{ $tmo->exetrf_emp }}', '{{ $tmo->exetrf_nos }}', '{{ $tmo->exetrf_req }}', '{{ $tmo->exetrf_seq }}')">Reabrir</a></li>
                                            </ul>		
                                        </div>
                                        @elseif($tmo->exetrf_sts == 'C')
                                        <div class="btn-group" style="width: 100%;"style="width: 100%;">
                                            <button type="button" class="btn btn-danger btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Cancelada <span class="caret"></span>
                                            </button>	
                                            <ul class="dropdown-menu">
                                                <li><a href="#" class="execucao-tarefa" onclick="openModalReopenService('{{ $tmo->exetrf_emp }}', '{{ $tmo->exetrf_nos }}', '{{ $tmo->exetrf_req }}', '{{ $tmo->exetrf_seq }}')">Reabrir</a></li>
                                            </ul>		
                                        </div>
                                        @elseif($tmo->exetrf_sts == 'S')
                                        <div class="btn-group" style="width: 100%;"style="width: 100%;">
                                            <button type="button" class="btn btn-warning btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Suspensa <span class="caret"></span>
                                            </button>	
                                            <ul class="dropdown-menu">
                                                <li><a href="#" class="execucao-tarefa" onclick="openModalReopenService('{{ $tmo->exetrf_emp }}', '{{ $tmo->exetrf_nos }}', '{{ $tmo->exetrf_req }}', '{{ $tmo->exetrf_seq }}')">Reabrir</a></li>
                                                <li><a href="#" class="execucao-tarefa" onclick="openModalCancelService('{{ $tmo->exetrf_emp }}', '{{ $tmo->exetrf_nos }}', '{{ $tmo->exetrf_req }}', '{{ $tmo->exetrf_seq }}')">Cancelar</a></li>
                                            </ul>		
                                        </div>
                                        @elseif($tmo->exetrf_sts == 'A')
                                        <div class="btn-group" style="width: 100%;"style="width: 100%;">
                                            <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Em Andamento <span class="caret"></span>
                                            </button>	
                                            <ul class="dropdown-menu">
                                                <li><a href="#" class="execucao-tarefa" onclick="openModalFinishService('{{ $tmo->exetrf_emp }}', '{{ $tmo->exetrf_nos }}', '{{ $tmo->exetrf_req }}', '{{ $tmo->exetrf_seq }}')">Finalizar</a></li>
                                                <li><a href="#" class="execucao-tarefa" onclick="openModalCancelService('{{ $tmo->exetrf_emp }}', '{{ $tmo->exetrf_nos }}', '{{ $tmo->exetrf_req }}', '{{ $tmo->exetrf_seq }}')">Cancelar</a></li>
                                                <li><a href="#" class="execucao-tarefa" onclick="openModalSuspendService('{{ $tmo->exetrf_emp }}', '{{ $tmo->exetrf_nos }}', '{{ $tmo->exetrf_req }}', '{{ $tmo->exetrf_seq }}')">Suspender</a></li>
                                            </ul>		
                                        </div>
                                        @elseif($tmo->exetrf_sts == 'E')
                                        <div class="btn-group" style="width: 100%;"style="width: 100%;">
                                            <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Em Espera <span class="caret"></span>
                                            </button>	
                                            <ul class="dropdown-menu">
                                                <li><a href="#" class="execucao-tarefa" onclick="openModalStartService('{{ $tmo->exetrf_emp }}', '{{ $tmo->exetrf_nos }}', '{{ $tmo->exetrf_req }}', '{{ $tmo->exetrf_seq }}')">Iniciar</a></li>
                                                <li><a href="#" class="execucao-tarefa" onclick="openModalCancelService('{{ $tmo->exetrf_emp }}', '{{ $tmo->exetrf_nos }}', '{{ $tmo->exetrf_req }}', '{{ $tmo->exetrf_seq }}')">Cancelar</a></li>
                                                <li><a href="#" class="execucao-tarefa" onclick="openModalSuspendService('{{ $tmo->exetrf_emp }}', '{{ $tmo->exetrf_nos }}', '{{ $tmo->exetrf_req }}', '{{ $tmo->exetrf_seq }}')">Suspender</a></li>
                                            </ul>		
                                        </div>
                                        @endif
                                    </td>
                                    <td>{{ $tmo->exetrf_qhr }}</td>
                                    <td>{{ $tmo->exetrf_sts != 'E' && !empty($tmo->exetrf_dt_ini_srv) ? Helper::formataHoraMinuto($tmo->exetrf_hr_ini_srv) : '' }}</td>
                                    <td>{{ $tmo->exetrf_sts == 'F' ? Helper::formataHoraMinuto($tmo->exetrf_hr_fin_srv) : '' }}</td>
                                    <td>{{ $tmo->exetrf_sts == 'F' ? $tmo->exetrf_qhr_real : '' }}</td>
                                    <td>
                                        @if($tmo->exetrf_sts == 'F')
                                            @if($tmo->exetrf_qhr_saldo > 0)
                                                <span class="text-primary">{{ $tmo->exetrf_qhr_saldo }} <i class="fa-solid fa-angles-right"></i></span>
                                            @elseif($tmo->exetrf_qhr_saldo < 0)
                                                <span class="text-danger">{{ $tmo->exetrf_qhr_saldo }} <i class="fa-solid fa-angles-left"></i></span>
                                            @else
                                                <span class="text-success">{{ $tmo->exetrf_qhr_saldo }} <i class="fa-solid fa-stop"></i></span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    OS Sem TMO Aprovadas
                    @endif
                </td>
                <td> {{ !empty($os->os_dpe) ? Helper::formataData($os->os_dpe) : '' }} {{ !empty($os->os_hpe) ? Helper::formataHoraMinuto($os->os_hpe) : '' }}</td>
            </tr>
        @endforeach
    </x-adminlte-datatable>

    <!-- Modal Único - Agenda dos Serviços da OS -->
    <x-adminlte-modal id="modalAgendaSrv" title="Agenda Programada dos Serviços da OS" size="xl" theme="navy" icon="fa-solid fa-clipboard-list" v-centered scrollable>
        <!-- O conteúdo será carregado via AJAX -->
        <div id="modalContentAgendaSrv"></div>
        <x-slot name="footerSlot">
            <x-adminlte-button theme="info" label="Voltar" data-dismiss="modal"/>
        </x-slot>
    </x-adminlte-modal>

    <!-- ***** Modal Único - Adição de Auxiliar - Seleção da TMO ***** -->
    <form method="post" action="" id="addAuxForm" novalidate="novalidate">
        @csrf 
        @method('post')
        <x-adminlte-modal id="modalAddAux" title="Inclusão de Prestador Auxiliar da TMO" size="xl" theme="navy" icon="fa-solid fa-people-carry-box" v-centered scrollable>
            <!-- O conteúdo será carregado via AJAX -->
            <div id="modalContentAddAux"></div>
            <x-slot name="footerSlot">
                <input type="hidden" id="selected-prtAux" name="selected_prtAux">
                <x-adminlte-button type="submit" label="Incluir" theme="info" icon="fa-solid fa-user-plus"/>
                <x-adminlte-button theme="info" label="Voltar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </form>

    <!-- ***** Modal Único - Adição/Alteração do Prestador da TMO ***** -->
    <form method="post" action="" id="addChangePrtForm" novalidate="novalidate">
        @csrf 
        @method('post')
        <x-adminlte-modal id="modalAddChangePrt" title="Inclusão / Alteração do Prestador da TMO" size="xl" theme="navy" icon="fa-solid fa-people-arrows" v-centered scrollable>
            <!-- O conteúdo será carregado via AJAX -->
            <div id="modalContentAddChangePrt"></div>
            <x-slot name="footerSlot">
                <input type="hidden" id="selected-prt" name="selected_prt">
                <x-adminlte-button type="submit" label="Incluir / Alterar" theme="info" icon="fa-solid fa-pencil"/>
                <x-adminlte-button theme="info" label="Voltar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </form>

    <!-- ***** Modal Único - Iniciar Serviço ***** -->
    <form method="post" action="" id="startServiceForm" novalidate="novalidate">
        @csrf 
        @method('post')
        <x-adminlte-modal id="modalStartService" title="Iniciar Serviço" size="xl" theme="navy" icon="fa-solid fa-person-digging" v-centered scrollable>
            <!-- O conteúdo será carregado via AJAX -->
            <div id="modalContentStartService"></div>
            <x-slot name="footerSlot">
                <x-adminlte-button type="submit" label="Iniciar" theme="info" icon="fa-solid fa-circle-play"/>
                <x-adminlte-button theme="info" label="Voltar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </form>

    <!-- ***** Modal Único - Finalizar Serviço ***** -->
    <form method="post" action="" id="finishServiceForm" novalidate="novalidate">
        @csrf 
        @method('post')
        <x-adminlte-modal id="modalFinishService" title="Finalizar Serviço" size="xl" theme="navy" icon="fa-solid fa-person-digging" v-centered scrollable>
            <!-- O conteúdo será carregado via AJAX -->
            <div id="modalContentFinishService"></div>
            <x-slot name="footerSlot">
                <x-adminlte-button type="submit" label="Finalizar" theme="info" icon="fa-solid fa-circle-stop"/>
                <x-adminlte-button theme="info" label="Voltar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </form>

    <!-- ***** Modal Único - Cancelar Serviço ***** -->
    <form method="post" action="" id="cancelServiceForm" novalidate="novalidate">
        @csrf 
        @method('post')
        <x-adminlte-modal id="modalCancelService" title="Cancelar Serviço" size="xl" theme="navy" icon="fa-solid fa-person-digging" v-centered scrollable>
            <!-- O conteúdo será carregado via AJAX -->
            <div id="modalContentCancelService"></div>
            <x-slot name="footerSlot">
                <x-adminlte-button type="submit" label="Cancelar" theme="info" icon="fa-solid fa-ban"/>
                <x-adminlte-button theme="info" label="Voltar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </form>

    <!-- ***** Modal Único - Suspender Serviço ***** -->
    <form method="post" action="" id="suspendServiceForm" novalidate="novalidate">
        @csrf 
        @method('post')
        <x-adminlte-modal id="modalSuspendService" title="Suspender Serviço" size="xl" theme="navy" icon="fa-solid fa-person-digging" v-centered scrollable>
            <!-- O conteúdo será carregado via AJAX -->
            <div id="modalContentSuspendService"></div>
            <x-slot name="footerSlot">
                <x-adminlte-button type="submit" label="Suspender" theme="info" icon="fa-solid fa-triangle-exclamation"/>
                <x-adminlte-button theme="info" label="Voltar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </form>

    <!-- ***** Modal Único - Reabrir Serviço ***** -->
    <form method="post" action="" id="reopenServiceForm" novalidate="novalidate">
        @csrf 
        @method('post')
        <x-adminlte-modal id="modalReopenService" title="Reabrir Serviço" size="xl" theme="navy" icon="fa-solid fa-person-digging" v-centered scrollable>
            <!-- O conteúdo será carregado via AJAX -->
            <div id="modalContentReopenService"></div>
            <x-slot name="footerSlot">
                <x-adminlte-button type="submit" label="Reabrir" theme="info" icon="fa-regular fa-folder-open"/>
                <x-adminlte-button theme="info" label="Voltar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </form>

    <!-- ***** Modal Único - Finalizar Requisição ***** -->
    <form method="post" action="" id="finishRequisicaoForm" novalidate="novalidate">
        @csrf 
        @method('post')
        <x-adminlte-modal id="modalFinishRequisicao" title="Finalizar Requisição" size="xl" theme="navy" icon="fa-solid fa-clipboard-check" v-centered scrollable>
            <!-- O conteúdo será carregado via AJAX -->
            <div id="modalContentFinishRequisicao"></div>
            <x-slot name="footerSlot">
                <x-adminlte-button type="submit" label="Finalizar" theme="info" icon="fa-solid fa-check"/>
                <x-adminlte-button theme="info" label="Voltar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </form>

    <!-- ***** Modal Único - Reabrir Requisição ***** -->
    <form method="post" action="" id="reopenRequisicaoForm" novalidate="novalidate">
        @csrf 
        @method('post')
        <x-adminlte-modal id="modalReopenRequisicao" title="Reabertura da Requisição" size="xl" theme="navy" icon="fa-solid fa-clipboard-list" v-centered scrollable>
            <!-- O conteúdo será carregado via AJAX -->
            <div id="modalContentReopenRequisicao"></div>
            <x-slot name="footerSlot">
                <x-adminlte-button type="submit" label="Reabrir" theme="info" icon="fa-regular fa-folder-open"/>
                <x-adminlte-button theme="info" label="Voltar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </form>
</x-adminlte-card>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.Sweetalert2', true)
@section('plugins.toastr', true)
@section('plugins.jqueryValidation', true)
@section('plugins.DateRangePicker', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.BootstrapSwitch', true)

@section('css')
<style>
    .nested-table {
        margin: 0;
        background: none;
        border-collapse: collapse; /* Colapsa as bordas da tabela */
    }
    .nested-table th, .nested-table td {
        padding: 8px; /* Adiciona um pouco de espaço interno */
        background: none;
        border: 1px solid #dee2e6; /* Adiciona uma borda para melhor visualização */
    }

    .nested-table thead th {
        background-color: none; /* Cor de fundo para o cabeçalho da tabela aninhada */
        border-bottom: 2px solid #dee2e6; /* Linha inferior para separar o cabeçalho */
    }

    .dropdown-menu {
        text-align: center; /* Centraliza o texto no dropdown */
        color: black; /* Define a cor do texto como preto */
    }
    .dropdown-menu a {
        color: black; /* Define a cor do link como preto */
        display: block; /* Faz com que o link ocupe toda a largura do dropdown */
    }
    .dropdown-menu a:hover {
        background-color: #f8f9fa; /* Cor de fundo ao passar o mouse */
        color: black; /* Mantém a cor do texto como preto ao passar o mouse */
    }

    .badge-custom {
        font-size: 0.85rem !important; /* Aumenta o tamanho do texto */
    }

    #modalAgendaSrv .modal-xl {
        max-width: 85% !important; /* Aumenta o tamanho do modal */
    }

    #modalAddChangePrt .modal-xl {
        max-width: 75% !important; /* Aumenta o tamanho do modal */
    }

    #modalAddAux .modal-xl {
        max-width: 75% !important; /* Aumenta o tamanho do modal */
    }
</style>
@stop

@section('js')

<!--
|--------------------------------------------------------------------------
| Eventos Iniciais da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() { 

        /* ******************** Evento de seleção dos Prestadores para Inclusão/Alteração ******************** */

        // Gera o submit enviando os dados do prestador selecionado
        document.getElementById('addChangePrtForm').addEventListener('submit', function (e) {
            e.preventDefault();

            // Captura o prestador selecionado pelo radio button
            let selectedPrestador = document.querySelector('input[name="selected_prestador"]:checked');

            // Verifica se algum prestador foi selecionado
            if (!selectedPrestador) {
                Swal.fire({
                    confirmButtonColor: "#007bff",
                    title: "Erro!!!",
                    text: "Por favor, selecione um prestador para continuar!",
                    icon: "error"
                });
                return;
            }

            // Define o valor selecionado no campo hidden
            document.getElementById('selected-prt').value = selectedPrestador.value;

            // Envia o formulário
            this.submit();
        });

        /* ******************** Evento de seleção dos Prestadores Auxiliares da TMO ******************** */

        // Gera o submit enviando os dados dos serviços que serão aprovados
        document.getElementById('addAuxForm').addEventListener('submit', function (e) {
            e.preventDefault();

            let selectedPrtAux = [];
            document.querySelectorAll('.record-checkbox-prtAux:checked').forEach(function (checkbox) {
                selectedPrtAux.push(checkbox.value);
            });

            if (selectedPrtAux.length === 0) {
                Swal.fire({
                    confirmButtonColor: "#007bff",
                    title: "Erro!!!",
                    text: "Por favor, selecione pelo menos um prestado para incluir.",
                    icon: "error"
                });
                return;
            }

            document.getElementById('selected-prtAux').value = selectedPrtAux.join(',');

            this.submit();
        });

    });
</script>

<!--
|--------------------------------------------------------------------------
| Eventos De Abertura de Modal da app
|--------------------------------------------------------------------------
-->
<script>
    //Fazer requisição AJAX para carregar os dados do modal de Adição de Prestador Auxiliar na TMO
    function openModalAddAux(emp, nos, req, seq) {

        var url = "{{ route('painelOperacao.carregarDadosModalAddAxuTMO', [':emp',':nos',':req', ':seq']) }}";
        url = url.replace(':emp', emp);
        url = url.replace(':nos', nos);
        url = url.replace(':req', req);
        url = url.replace(':seq', seq);

        // Atualiza a url do formulário
        var formActionUrl = "{{ route('painelOperacao.addPrtAuxTMO', ['empresa' => ':emp', 'numOS' => ':nos', 'requisicao' => ':req', 'servico' => ':seq']) }}";
        formActionUrl = formActionUrl.replace(':emp', emp)
                                    .replace(':nos', nos)
                                    .replace(':req', req)
                                    .replace(':seq', seq);

        $.ajax({
            url: url,
            type: 'GET',
            data: { 
                '_token': $('meta[name=csrf-token]').attr("content"),
                '_method': 'GET',
                "emp": emp,
                "nos": nos,
                "req": req,
                "seq": seq
            },
            success: function(data) {
                // Preenche o conteúdo do modal
                $('#modalContentAddAux').html(data);

                // Inicializa ou reinicializa o DataTable
                $('#tabelaModalAddAux').DataTable({
                    paging: false,
                    searching: false,
                    autoWidth: false,
                    order: [[0, 'asc']],
                    columns: [
                        { orderable: false }, 
                        { orderable: false }, 
                        { orderable: false }, 
                        { orderable: false }, 
                        { orderable: false }, 
                        { orderable: false }, 
                        { orderable: false }, 
                        { orderable: false }
                    ],
                    language: {
                        decimal: '',
                        emptyTable: 'Sem dados disponíveis na tabela',
                        info: '',
                        infoEmpty: '',
                        infoFiltered: '(Filtrado do total de _MAX_ registros)',
                        thousands: ',',
                        loadingRecords: 'Carregando...',
                        search: 'Pesquisar:',
                        zeroRecords: 'Nenhum registro correspondente encontrado',
                        paginate: {
                            first: 'Primeiro',
                            last: 'Último',
                            next: 'Próximo',
                            previous: 'Anterior'
                        },
                        aria: {
                            sortAscending: ': ativar para classificar a coluna em ordem crescente',
                            sortDescending: ': ativar para classificar a coluna em ordem decrescente'
                        }
                    }
                });

                // Atualiza a ação do formulário
                $('#addAuxForm').attr('action', formActionUrl);

                // Abre o modal
                $('#modalAddAux').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar dados:', error);
            }
        });
    }

    //Fazer requisição AJAX para carregar os dados do modal de agenda de serviços da OS
    function openModalAgendaSrvOS(emp, nos) {

        var url = "{{ route('painelOperacao.carregarDadosModalAgendaSrvOS', [':emp',':nos']) }}";
        url = url.replace(':emp', emp);
        url = url.replace(':nos', nos);

        $.ajax({
            url: url,
            type: 'GET',
            data: { 
                '_token': $('meta[name=csrf-token]').attr("content"),
                '_method': 'GET',
                "emp": emp,
                "nos": nos
            },
            success: function(data) {
                // Preenche o conteúdo do modal
                $('#modalContentAgendaSrv').html(data);
                
                // Inicializa ou reinicializa o DataTable
                $('#tableModalAgendaSrvOS').DataTable({
                    paging: false,
                    searching: false,
                    autoWidth: false,
                    order: [[1, 'asc'],[0, 'asc'],[2, 'asc']],
                    columns: [
                        { orderable: false, visible: false }, 
                        { orderable: false, visible: false }, 
                        { orderable: false, visible: false }, 
                        { orderable: false, render: $.fn.dataTable.render.text() }, 
                        { orderable: false, render: $.fn.dataTable.render.text() }, 
                        { orderable: false }, 
                        { orderable: false },
                        { orderable: false }, 
                        { orderable: false }, 
                        { orderable: false }, 
                        { orderable: false }, 
                        { orderable: false }, 
                        { orderable: false }, 
                        { orderable: false }, 
                        { orderable: false }, 
                        { orderable: false }, 
                        { orderable: false }, 
                        { orderable: false }, 
                        { orderable: false }
                    ],
                    language: {
                        decimal: '',
                        emptyTable: 'Sem dados disponíveis na tabela',
                        info: '',
                        infoEmpty: '',
                        infoFiltered: '(Filtrado do total de _MAX_ registros)',
                        thousands: ',',
                        loadingRecords: 'Carregando...',
                        search: 'Pesquisar:',
                        zeroRecords: 'Nenhum registro correspondente encontrado',
                        paginate: {
                            first: 'Primeiro',
                            last: 'Último',
                            next: 'Próximo',
                            previous: 'Anterior'
                        },
                        aria: {
                            sortAscending: ': ativar para classificar a coluna em ordem crescente',
                            sortDescending: ': ativar para classificar a coluna em ordem decrescente'
                        }
                    }
                });
                
                // Abre o modal
                $('#modalAgendaSrv').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar dados:', error);
            }
        });
    }

    //Fazer requisição AJAX para carregar os dados do modal de inicio da TMO
    function openModalStartService(emp, nos, req, seq) {

        var url = "{{ route('painelOperacao.carregarDadosModalStartService', [':emp',':nos',':req', ':seq']) }}";
        url = url.replace(':emp', emp);
        url = url.replace(':nos', nos);
        url = url.replace(':req', req);
        url = url.replace(':seq', seq);

        // Atualiza a url do formulário
        var formActionUrl = "{{ route('painelOperacao.iniciarTMO', ['empresa' => ':emp', 'numOS' => ':nos', 'requisicao' => ':req', 'servico' => ':seq']) }}";
        formActionUrl = formActionUrl.replace(':emp', emp)
                                    .replace(':nos', nos)
                                    .replace(':req', req)
                                    .replace(':seq', seq);

        $.ajax({
            url: url,
            type: 'GET',
            data: { 
                '_token': $('meta[name=csrf-token]').attr("content"),
                '_method': 'GET',
                "emp": emp,
                "nos": nos,
                "req": req,
                "seq": seq
            },
            success: function(data) {
                // Preenche o conteúdo do modal
                $('#modalContentStartService').html(data);

                // Reativa o DateRangePicker para o campo horaIniSrv
                $('#horaIniSrv').daterangepicker({
                    singleDatePicker: true,
                    showDropdowns: true,
                    minYear: 2000,
                    maxYear: parseInt(moment().format('YYYY'),10),
                    timePicker: true,
                    timePicker24Hour: true,
                    timePickerSeconds: false,
                    cancelButtonClasses: "btn-danger",
                    locale: {
                        format: "HH:mm"
                    },
                    startDate: moment(),
                });

                // Atualiza a ação do formulário
                $('#startServiceForm').attr('action', formActionUrl);

                // Abre o modal
                $('#modalStartService').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar dados:', error);
            }
        });
    }

    //Fazer requisição AJAX para carregar os dados do modal de inicio da TMO
    function openModalFinishService(emp, nos, req, seq) {

        var url = "{{ route('painelOperacao.carregarDadosModalFinishService', [':emp',':nos',':req', ':seq']) }}";
        url = url.replace(':emp', emp);
        url = url.replace(':nos', nos);
        url = url.replace(':req', req);
        url = url.replace(':seq', seq);

        // Atualiza a url do formulário
        var formActionUrl = "{{ route('painelOperacao.finalizarTMO', ['empresa' => ':emp', 'numOS' => ':nos', 'requisicao' => ':req', 'servico' => ':seq']) }}";
        formActionUrl = formActionUrl.replace(':emp', emp)
                                    .replace(':nos', nos)
                                    .replace(':req', req)
                                    .replace(':seq', seq);

        $.ajax({
            url: url,
            type: 'GET',
            data: { 
                '_token': $('meta[name=csrf-token]').attr("content"),
                '_method': 'GET',
                "emp": emp,
                "nos": nos,
                "req": req,
                "seq": seq
            },
            success: function(data) {
                // Preenche o conteúdo do modal
                $('#modalContentFinishService').html(data);

                // Reativa o DateRangePicker para o campo horaIniSrv
                $('#horaFinSrv').daterangepicker({
                    singleDatePicker: true,
                    showDropdowns: true,
                    minYear: 2000,
                    maxYear: parseInt(moment().format('YYYY'),10),
                    timePicker: true,
                    timePicker24Hour: true,
                    timePickerSeconds: false,
                    cancelButtonClasses: "btn-danger",
                    locale: {
                        format: "HH:mm"
                    },
                    startDate: moment(),
                });

                // Atualiza a ação do formulário
                $('#finishServiceForm').attr('action', formActionUrl);

                // Abre o modal
                $('#modalFinishService').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar dados:', error);
            }
        });
    }

    //Fazer requisição AJAX para carregar os dados do modal de Adição/alteração de Prestador na TMO
    function openModalAddChangePrt(emp, nos, req, seq) {

        var url = "{{ route('painelOperacao.carregarDadosModalAddChangePrt', [':emp',':nos',':req', ':seq']) }}";
        url = url.replace(':emp', emp);
        url = url.replace(':nos', nos);
        url = url.replace(':req', req);
        url = url.replace(':seq', seq);

        // Atualiza a url do formulário
        var formActionUrl = "{{ route('painelOperacao.addChangePrtTMO', ['empresa' => ':emp', 'numOS' => ':nos', 'requisicao' => ':req', 'servico' => ':seq']) }}";
        formActionUrl = formActionUrl.replace(':emp', emp)
                                    .replace(':nos', nos)
                                    .replace(':req', req)
                                    .replace(':seq', seq);

        $.ajax({
            url: url,
            type: 'GET',
            data: { 
                '_token': $('meta[name=csrf-token]').attr("content"),
                '_method': 'GET',
                "emp": emp,
                "nos": nos,
                "req": req,
                "seq": seq
            },
            success: function(data) {
                // Preenche o conteúdo do modal
                $('#modalContentAddChangePrt').html(data);

                // Inicializa ou reinicializa o DataTable
                $('#tabelaModalAddChangePrt').DataTable({
                    paging: false,
                    searching: false,
                    autoWidth: false,
                    order: [[0, 'asc']],
                    columns: [
                        { orderable: false }, 
                        { orderable: false }, 
                        { orderable: false }, 
                        { orderable: false }, 
                        { orderable: false }, 
                        { orderable: false }, 
                        { orderable: false }, 
                        { orderable: false }
                    ],
                    language: {
                        decimal: '',
                        emptyTable: 'Sem dados disponíveis na tabela',
                        info: '',
                        infoEmpty: '',
                        infoFiltered: '(Filtrado do total de _MAX_ registros)',
                        thousands: ',',
                        loadingRecords: 'Carregando...',
                        search: 'Pesquisar:',
                        zeroRecords: 'Nenhum registro correspondente encontrado',
                        paginate: {
                            first: 'Primeiro',
                            last: 'Último',
                            next: 'Próximo',
                            previous: 'Anterior'
                        },
                        aria: {
                            sortAscending: ': ativar para classificar a coluna em ordem crescente',
                            sortDescending: ': ativar para classificar a coluna em ordem decrescente'
                        }
                    }
                });

                // Atualiza a ação do formulário
                $('#addChangePrtForm').attr('action', formActionUrl);

                // Abre o modal
                $('#modalAddChangePrt').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar dados:', error);
            }
        });
    }

    //Fazer requisição AJAX para carregar os dados do modal de cancelar a TMO
    function openModalCancelService(emp, nos, req, seq) {

        var url = "{{ route('painelOperacao.carregarDadosModalCancelService', [':emp',':nos',':req', ':seq']) }}";
        url = url.replace(':emp', emp);
        url = url.replace(':nos', nos);
        url = url.replace(':req', req);
        url = url.replace(':seq', seq);

        // Atualiza a url do formulário
        var formActionUrl = "{{ route('painelOperacao.cancelarTMO', ['empresa' => ':emp', 'numOS' => ':nos', 'requisicao' => ':req', 'servico' => ':seq']) }}";
        formActionUrl = formActionUrl.replace(':emp', emp)
                                    .replace(':nos', nos)
                                    .replace(':req', req)
                                    .replace(':seq', seq);

        $.ajax({
            url: url,
            type: 'GET',
            data: { 
                '_token': $('meta[name=csrf-token]').attr("content"),
                '_method': 'GET',
                "emp": emp,
                "nos": nos,
                "req": req,
                "seq": seq
            },
            success: function(data) {
                // Preenche o conteúdo do modal
                $('#modalContentCancelService').html(data);

                // Atualiza a ação do formulário
                $('#cancelServiceForm').attr('action', formActionUrl);

                // Abre o modal
                $('#modalCancelService').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar dados:', error);
            }
        });
    }

    //Fazer requisição AJAX para carregar os dados do modal de suspender a TMO
    function openModalSuspendService(emp, nos, req, seq) {

        var url = "{{ route('painelOperacao.carregarDadosModalSuspendService', [':emp',':nos',':req', ':seq']) }}";
        url = url.replace(':emp', emp);
        url = url.replace(':nos', nos);
        url = url.replace(':req', req);
        url = url.replace(':seq', seq);

        // Atualiza a url do formulário
        var formActionUrl = "{{ route('painelOperacao.suspenderTMO', ['empresa' => ':emp', 'numOS' => ':nos', 'requisicao' => ':req', 'servico' => ':seq']) }}";
        formActionUrl = formActionUrl.replace(':emp', emp)
                                    .replace(':nos', nos)
                                    .replace(':req', req)
                                    .replace(':seq', seq);

        $.ajax({
            url: url,
            type: 'GET',
            data: { 
                '_token': $('meta[name=csrf-token]').attr("content"),
                '_method': 'GET',
                "emp": emp,
                "nos": nos,
                "req": req,
                "seq": seq
            },
            success: function(data) {
                // Preenche o conteúdo do modal
                $('#modalContentSuspendService').html(data);

                // Atualiza a ação do formulário
                $('#suspendServiceForm').attr('action', formActionUrl);

                // Abre o modal
                $('#modalSuspendService').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar dados:', error);
            }
        });
    }

    //Fazer requisição AJAX para carregar os dados do modal de reabrir a TMO
    function openModalReopenService(emp, nos, req, seq) {

        var url = "{{ route('painelOperacao.carregarDadosModalReopenService', [':emp',':nos',':req', ':seq']) }}";
        url = url.replace(':emp', emp);
        url = url.replace(':nos', nos);
        url = url.replace(':req', req);
        url = url.replace(':seq', seq);

        // Atualiza a url do formulário
        var formActionUrl = "{{ route('painelOperacao.reabrirTMO', ['empresa' => ':emp', 'numOS' => ':nos', 'requisicao' => ':req', 'servico' => ':seq']) }}";
        formActionUrl = formActionUrl.replace(':emp', emp)
                                    .replace(':nos', nos)
                                    .replace(':req', req)
                                    .replace(':seq', seq);

        $.ajax({
            url: url,
            type: 'GET',
            data: { 
                '_token': $('meta[name=csrf-token]').attr("content"),
                '_method': 'GET',
                "emp": emp,
                "nos": nos,
                "req": req,
                "seq": seq
            },
            success: function(data) {
                // Preenche o conteúdo do modal
                $('#modalContentReopenService').html(data);

                // Atualiza a ação do formulário
                $('#reopenServiceForm').attr('action', formActionUrl);

                // Abre o modal
                $('#modalReopenService').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar dados:', error);
            }
        });
    }

    //Fazer requisição AJAX para carregar os dados do modal de finalizar a requisição
    function openModalFinishRequisicao(emp, nos, req) {

        var url = "{{ route('painelOperacao.carregarDadosModalFinishRequisicao', [':emp',':nos',':req']) }}";
        url = url.replace(':emp', emp);
        url = url.replace(':nos', nos);
        url = url.replace(':req', req);

        // Atualiza a url do formulário
        var formActionUrl = "{{ route('painelOperacao.finalizarReqPO', ['empresa' => ':emp', 'numOS' => ':nos', 'requisicao' => ':req']) }}";
        formActionUrl = formActionUrl.replace(':emp', emp)
                                    .replace(':nos', nos)
                                    .replace(':req', req);

        $.ajax({
            url: url,
            type: 'GET',
            data: { 
                '_token': $('meta[name=csrf-token]').attr("content"),
                '_method': 'GET',
                "emp": emp,
                "nos": nos,
                "req": req
            },
            success: function(data) {
                // Preenche o conteúdo do modal
                $('#modalContentFinishRequisicao').html(data);

                // Atualiza a ação do formulário
                $('#finishRequisicaoForm').attr('action', formActionUrl);

                // Abre o modal
                $('#modalFinishRequisicao').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar dados:', error);
            }
        });
    }

    //Fazer requisição AJAX para carregar os dados do modal de finalizar a requisição
    function openModalReopenRequisicao(emp, nos, req) {

        var url = "{{ route('painelOperacao.carregarDadosModalReopenRequisicao', [':emp',':nos',':req']) }}";
        url = url.replace(':emp', emp);
        url = url.replace(':nos', nos);
        url = url.replace(':req', req);

        // Atualiza a url do formulário
        var formActionUrl = "{{ route('painelOperacao.reabrirReqPO', ['empresa' => ':emp', 'numOS' => ':nos', 'requisicao' => ':req']) }}";
        formActionUrl = formActionUrl.replace(':emp', emp)
                                    .replace(':nos', nos)
                                    .replace(':req', req);

        $.ajax({
            url: url,
            type: 'GET',
            data: { 
                '_token': $('meta[name=csrf-token]').attr("content"),
                '_method': 'GET',
                "emp": emp,
                "nos": nos,
                "req": req
            },
            success: function(data) {
                // Preenche o conteúdo do modal
                $('#modalContentReopenRequisicao').html(data);

                // Atualiza a ação do formulário
                $('#reopenRequisicaoForm').attr('action', formActionUrl);

                // Abre o modal
                $('#modalReopenRequisicao').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar dados:', error);
            }
        });
    }
</script>

<!--
|--------------------------------------------------------------------------
| Eventos Validate da app
|--------------------------------------------------------------------------
-->

<script>
$(function () {
    $('#suspendServiceForm').validate({
        rules: {
            motivoSus: {
                required: true
            },
        },
        messages: {
            motivoSus: {
                required: "Por Favor informe o Motivo da Suspensão"
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        // Ao submeter o formulário, reativar os campos desativados
        submitHandler: function (form) {
            // Ativar campos desativados antes de enviar
            $(':disabled').each(function () {
                $(this).removeAttr('disabled');
            });
            form.submit();
        }
    });
});

$(function () {
    $('#cancelServiceForm').validate({
        rules: {
            motivoCan: {
                required: true
            },
        },
        messages: {
            motivoCan: {
                required: "Por Favor informe o Motivo do Cancelamento"
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        // Ao submeter o formulário, reativar os campos desativados
        submitHandler: function (form) {
            // Ativar campos desativados antes de enviar
            $(':disabled').each(function () {
                $(this).removeAttr('disabled');
            });
            form.submit();
        }
    });
});
</script>

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

