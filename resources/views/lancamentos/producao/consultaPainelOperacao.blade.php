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
    'lengthMenu' => [5, 10, 25, 50, 100],
    'pageLength' => 10,
    'language' => Helper::dataTableLangPtBR(),
    'order' => [[2, 'desc']],
    'columns' => [['orderable' => false],['orderable' => false],['orderable' => false], ['orderable' => false], ['orderable' => false], ['orderable' => false], ['orderable' => false]],
];

$headsPrincipal = [
    'OS',
    ['label' => '', 'no-export' => true, 'width' => '5'],
    ['label' => '', 'no-export' => true, 'width' => '5'],
    'Agenda',
    'OS',
    'Data',
    'Cliente',
    'Consultor',
    'Previsão Entrega'
];
$configPrincipal = [
    'lengthMenu' => [5, 10, 25, 50, 100],
    'pageLength' => 10,
    'language' => Helper::dataTableLangPtBR(),
    'order' => [
        [0, 'desc']
    ],
    'pagingType' => 'full_numbers', // Adiciona os botões de "Primeiro" e "Último"
    'columns' => [
        ['orderable' => false, 'visible' => false], // Esconder primeira coluna
        ['orderable' => false],
        ['orderable' => false],
        ['orderable' => false],
        ['orderable' => false], 
        ['orderable' => false], 
        ['orderable' => false], 
        ['orderable' => false], 
        ['orderable' => false]
    ],
];


//Define as variaveis de sessão aqui na view por que depois de 2 redirect elas são destruidas
$_SESSION['where_consulta_painelOperador'] = $empresa_os;
$_SESSION['empresaOS_consulta_painelOperador'] = $where_app;
@endphp

<x-adminlte-card title="Painel de Operações da Produção" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
    <!-- Tabela Principal da OS -->
    <x-adminlte-datatable id="tabela-principal" :heads="$headsPrincipal" :config="$configPrincipal" theme="light" striped hoverable beautify compressed with-buttons>
        @foreach ($dadosOS as $os)
            @php 
                //Gera os Dados Sobre o Cliente e o Usuário da OS
                $dadosCli =  DB::table('cadastro_clientes')->where('cliente_codigo', $os->os_cli)->get();
                $dadosUsu =  DB::table('users')->where('usuario_codigo', $os->os_res_abr)->get();
            @endphp
            <tr id="row_{{$os->os_id}}" data-os="{{ $os->os_nos }}" data-empresa="{{ $os->os_emp }}">
                <td>{{ $os->os_nos }}</td>
                <td class='icone-row-sub'>
                    <i class="fas fa-chevron-right fa-lg"></i>
                </td>
                <td>
                    <!-- Link para o Painel da OS -->
                    <a href="{{route('situacaoOS.carregaOS', ['empresa' => $os->os_emp, 'cliente' => $os->os_cli, 'nos' => $os->os_nos, 'estagioAPP' => 'PRINCIPAL'])}}" class="text-muted" title="Detalhes da OS">
                        <i class="fa-solid fa-magnifying-glass fa-lg" style="color: #74C0FC;"></i>
                    </a>
                </td>
                <td>
                    <!-- Gera o botão que abre o Modal de Agenda dos Serviços da OS -->
                    <button type="button" class="btn btn-xl" title="Agenda da OS" 
                        onclick="openModalAgendaSrvOS('{{ $os->os_emp }}', '{{ $os->os_nos }}')">
                        <i class="fa-solid fa-clipboard-list fa-xl" style="color: #39cccc;"></i>
                    </button>
                </td>
                <td>
                    <!-- Gera o Icone do Status da OS -->
                    @if($os->os_sts == 'A')
                    <i class="fa-solid fa-screwdriver-wrench fa-xl text-info mx-2"></i>
                    @elseif($os->os_sts == 'F')
                    <i class="fa-solid fa-thumbs-up fa-xl text-success mx-2"></i>
                    @else
                    <i class="fa-solid fa-ban fa-xl text-danger mx-2"></i>
                    @endif
                    <span class="mx-2">{{ $os->os_nos }}</span>
                </td>
                <td>{{ Helper::formataDataHora($os->os_dha) }}</td>
                <td>{{ $os->os_cli.' - '.$dadosCli[0]->cliente_nome }}</td>
                <td>{{ $os->os_res_abr.' - '.$dadosUsu[0]->name }}</td>
                @php 
                    if(!empty($os->os_dpe)){
                        $dataHora = date('Y-m-d H:i:s');
                        $prevEnt = 'S';

                        if($os->os_sts == 'F' && $os->os_dpe.' '.Helper::formataHoraMinuto($os->os_hpe).':00' < $os->os_dhf){
                            $cor = "danger";
                        }elseif($os->os_sts == 'F' && $os->os_dpe.' '.Helper::formataHoraMinuto($os->os_hpe).':00' >= $os->os_dhf){
                            $cor = "success";
                        }elseif($os->os_sts == 'A' && $os->os_dpe.' '.Helper::formataHoraMinuto($os->os_hpe).':00' < $dataHora){
                            $cor = "danger";
                        }elseif($os->os_sts == 'A' && $os->os_dpe.' '.Helper::formataHoraMinuto($os->os_hpe).':00' >= $dataHora){
                            $cor = "success";
                        }else{
                            $cor = "danger";
                        }
                    }else{
                        $prevEnt = 'N';
                    }
                @endphp
                @if($prevEnt == 'S')
                <td><span class="badge badge-pill badge-{{$cor}} badge-custom">{{Helper::formataData($os->os_dpe).' '.Helper::formataHoraMinuto($os->os_hpe)}}</span></td>
                @else 
                <td></td>
                @endif
            </tr>
        @endforeach
    </x-adminlte-datatable>

    <!-- Modal Único - Agenda dos Serviços da OS -->
    <x-adminlte-modal id="modalAgendaSrv" title="Agenda Programada dos Serviços da OS" size="xl" theme="modal-nexus" icon="fa-solid fa-clipboard-list" v-centered scrollable>
        <!-- O conteúdo será carregado via AJAX -->
        <div id="modalContentAgendaSrv"></div>
        <x-slot name="footerSlot">
            <x-adminlte-button class="btn-nexus" theme="" label="Voltar" data-dismiss="modal"/>
        </x-slot>
    </x-adminlte-modal>

    <!-- ***** Modal Único - Adição de Auxiliar - Seleção da TMO ***** -->
    <form method="post" action="" id="addAuxForm" novalidate="novalidate">
        @csrf 
        @method('post')
        <x-adminlte-modal id="modalAddAux" title="Inclusão de Prestador Auxiliar da TMO" size="xl" theme="modal-nexus" icon="fa-solid fa-people-carry-box" v-centered scrollable>
            <!-- O conteúdo será carregado via AJAX -->
            <div id="modalContentAddAux"></div>
            <x-slot name="footerSlot">
                <input type="hidden" id="selected-prtAux" name="selected_prtAux">
                <x-adminlte-button class="btn-nexus" type="submit" label="Incluir" theme="" icon="fa-solid fa-user-plus"/>
                <x-adminlte-button class="btn-nexus" theme="" label="Voltar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </form>

    <!-- ***** Modal Único - Adição/Alteração do Prestador da TMO ***** -->
    <form method="post" action="" id="addChangePrtForm" novalidate="novalidate">
        @csrf 
        @method('post')
        <x-adminlte-modal id="modalAddChangePrt" title="Inclusão / Alteração do Prestador da TMO" size="xl" theme="modal-nexus" icon="fa-solid fa-people-arrows" v-centered scrollable>
            <!-- O conteúdo será carregado via AJAX -->
            <div id="modalContentAddChangePrt"></div>
            <x-slot name="footerSlot">
                <input type="hidden" id="selected-prt" name="selected_prt">
                <x-adminlte-button class="btn-nexus" type="submit" label="Incluir / Alterar" theme="" icon="fa-solid fa-pencil"/>
                <x-adminlte-button class="btn-nexus" theme="" label="Voltar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </form>

    <!-- ***** Modal Único - Iniciar Serviço ***** -->
    <form method="post" action="" id="startServiceForm" novalidate="novalidate">
        @csrf 
        @method('post')
        <x-adminlte-modal id="modalStartService" title="Iniciar Serviço" size="xl" theme="modal-nexus" icon="fa-solid fa-person-digging" v-centered scrollable>
            <!-- O conteúdo será carregado via AJAX -->
            <div id="modalContentStartService"></div>
            <x-slot name="footerSlot">
                <x-adminlte-button class="btn-nexus" type="submit" label="Iniciar" theme="" icon="fa-solid fa-circle-play"/>
                <x-adminlte-button class="btn-nexus" theme="" label="Voltar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </form>

    <!-- ***** Modal Único - Finalizar Serviço ***** -->
    <form method="post" action="" id="finishServiceForm" novalidate="novalidate">
        @csrf 
        @method('post')
        <x-adminlte-modal id="modalFinishService" title="Finalizar Serviço" size="xl" theme="modal-nexus" icon="fa-solid fa-person-digging" v-centered scrollable>
            <!-- O conteúdo será carregado via AJAX -->
            <div id="modalContentFinishService"></div>
            <x-slot name="footerSlot">
                <x-adminlte-button class="btn-nexus" type="submit" label="Finalizar" theme="" icon="fa-solid fa-circle-stop"/>
                <x-adminlte-button class="btn-nexus" theme="" label="Voltar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </form>

    <!-- ***** Modal Único - Cancelar Serviço ***** -->
    <form method="post" action="" id="cancelServiceForm" novalidate="novalidate">
        @csrf 
        @method('post')
        <x-adminlte-modal id="modalCancelService" title="Cancelar Serviço" size="xl" theme="modal-nexus" icon="fa-solid fa-person-digging" v-centered scrollable>
            <!-- O conteúdo será carregado via AJAX -->
            <div id="modalContentCancelService"></div>
            <x-slot name="footerSlot">
                <x-adminlte-button class="btn-nexus" type="submit" label="Cancelar" theme="" icon="fa-solid fa-ban"/>
                <x-adminlte-button class="btn-nexus" theme="" label="Voltar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </form>

    <!-- ***** Modal Único - Suspender Serviço ***** -->
    <form method="post" action="" id="suspendServiceForm" novalidate="novalidate">
        @csrf 
        @method('post')
        <x-adminlte-modal id="modalSuspendService" title="Suspender Serviço" size="xl" theme="modal-nexus" icon="fa-solid fa-person-digging" v-centered scrollable>
            <!-- O conteúdo será carregado via AJAX -->
            <div id="modalContentSuspendService"></div>
            <x-slot name="footerSlot">
                <x-adminlte-button class="btn-nexus" type="submit" label="Suspender" theme="" icon="fa-solid fa-triangle-exclamation"/>
                <x-adminlte-button class="btn-nexus" theme="" label="Voltar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </form>

    <!-- ***** Modal Único - Reabrir Serviço ***** -->
    <form method="post" action="" id="reopenServiceForm" novalidate="novalidate">
        @csrf 
        @method('post')
        <x-adminlte-modal id="modalReopenService" title="Reabrir Serviço" size="xl" theme="modal-nexus" icon="fa-solid fa-person-digging" v-centered scrollable>
            <!-- O conteúdo será carregado via AJAX -->
            <div id="modalContentReopenService"></div>
            <x-slot name="footerSlot">
                <x-adminlte-button class="btn-nexus" type="submit" label="Reabrir" theme="" icon="fa-regular fa-folder-open"/>
                <x-adminlte-button class="btn-nexus" theme="" label="Voltar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </form>

    <!-- ***** Modal Único - Finalizar Requisição ***** -->
    <form method="post" action="" id="finishRequisicaoForm" novalidate="novalidate">
        @csrf 
        @method('post')
        <x-adminlte-modal id="modalFinishRequisicao" title="Finalizar Requisição" size="xl" theme="modal-nexus" icon="fa-solid fa-clipboard-check" v-centered scrollable>
            <!-- O conteúdo será carregado via AJAX -->
            <div id="modalContentFinishRequisicao"></div>
            <x-slot name="footerSlot">
                <x-adminlte-button class="btn-nexus" type="submit" label="Finalizar" theme="" icon="fa-solid fa-check"/>
                <x-adminlte-button class="btn-nexus" theme="" label="Voltar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </form>

    <!-- ***** Modal Único - Reabrir Requisição ***** -->
    <form method="post" action="" id="reopenRequisicaoForm" novalidate="novalidate">
        @csrf 
        @method('post')
        <x-adminlte-modal id="modalReopenRequisicao" title="Reabertura da Requisição" size="xl" theme="modal-nexus" icon="fa-solid fa-clipboard-list" v-centered scrollable>
            <!-- O conteúdo será carregado via AJAX -->
            <div id="modalContentReopenRequisicao"></div>
            <x-slot name="footerSlot">
                <x-adminlte-button class="btn-nexus" type="submit" label="Reabrir" theme="" icon="fa-regular fa-folder-open"/>
                <x-adminlte-button class="btn-nexus" theme="" label="Voltar" data-dismiss="modal"/>
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
|----------------------------------------------------------------------------------------------------
| Eventos Iniciais da app
|----------------------------------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() { 

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Eventos da Sub Consulta
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Função para buscar a view via AJAX
        function format(tr) {

            // Obtendo os parâmetros da OS e da empresa a partir do 'tr' (linha da tabela)
            let os = $(tr).data('os');
            let empresa = $(tr).data('empresa');

            //Gerando a URL da rota da Sub Consulta
            var url = "{{ route('painelOperacao.carregarDadosSubConsultaPainelOperador', [':emp',':os']) }}";
            url = url.replace(':os', os);
            url = url.replace(':emp', empresa);


            // Evento AJAX para buscar o conteúdo da view através da rota
            return $.ajax({
                url: url, 
                type: 'GET',
                data: {
                    empresa: empresa,
                    os: os
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Adiciona o token CSRF
                },
                success: function(response) {
                    
                    return response;
                },
                error: function(xhr) {

                    return 'Erro ao buscar detalhes da OS.';
                }
            });
        }

        //Array com os dados da linha
        const detailRows = [];

        // Assumindo que a tabela já está inicializada
        var table = $('#tabela-principal').DataTable();

        // Event listener para expandir/recolher as linhas
        $('#tabela-principal').on('click', 'tbody td.icone-row-sub', function (event) {
            
            let tr = $(this).closest('tr'); // Use jQuery para selecionar o 'tr'
            let row = table.row(tr);
            let icon = $(this).find('i'); // Buscando o ícone

            if (row.child.isShown()) {

                // Ocultar a linha filha
                tr.removeClass('details');
                row.child.hide();
                icon.removeClass('fa-chevron-down').addClass('fa-chevron-right'); // Ícone de "fechado"

                // Remover do array 'detailRows'
                detailRows.splice(detailRows.indexOf(tr.attr('id')), 1);

            } else {

                // Mostrar a linha filha com o conteúdo da view via AJAX
                format(tr).done(function(response) {

                    tr.addClass('details');
                    row.child(response).show();
                    icon.removeClass('fa-chevron-right').addClass('fa-chevron-down'); // Ícone de "aberto"
                });

                // Adicionar ao array 'detailRows'
                if (detailRows.indexOf(tr.attr('id')) === -1) {

                    detailRows.push(tr.attr('id'));
                }
            }
        });

        // Em cada 'draw', mostre novamente as linhas filhas que estavam abertas
        $('#tabela-principal').on('draw', () => {

            detailRows.forEach((id) => {
                let el = document.querySelector('#' + id + ' td.icone-row-sub');
                if (el) {
                    el.dispatchEvent(new Event('click', { bubbles: true }));
                }
            });
        });
        /* ------------------------------ Final dos Eventos da Sub Consulta ------------------------------ */

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Evento de seleção dos Prestadores para Inclusão/Alteração
        |----------------------------------------------------------------------------------------------------
        ***** */

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

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Evento de seleção dos Prestadores Auxiliares da TMO
        |----------------------------------------------------------------------------------------------------
        ***** */

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
|----------------------------------------------------------------------------------------------------
| Eventos das Aberturas dos Modais da app
|----------------------------------------------------------------------------------------------------
-->
<script>
    /* ******************** Fazer requisição AJAX para carregar os dados do modal de Adição de Prestador Auxiliar na TMO ******************** */
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

    /* ******************** Fazer requisição AJAX para carregar os dados do modal de agenda de serviços da OS ******************** */
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

    /* ******************** Fazer requisição AJAX para carregar os dados do modal de inicio da TMO ******************** */
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

    /* ******************** Fazer requisição AJAX para carregar os dados do modal de inicio da TMO ******************** */
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

    /* ******************** Fazer requisição AJAX para carregar os dados do modal de Adição/alteração de Prestador na TMO ******************** */
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

    /* ******************** Fazer requisição AJAX para carregar os dados do modal de cancelar a TMO ******************** */
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

    /* ******************** Fazer requisição AJAX para carregar os dados do modal de suspender a TMO ******************** */
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

    /* ******************** Fazer requisição AJAX para carregar os dados do modal de reabrir a TMO ******************** */
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

    /* ******************** Fazer requisição AJAX para carregar os dados do modal de finalizar a requisição ******************** */
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

    /* ******************** Fazer requisição AJAX para carregar os dados do modal de finalizar a requisição ******************** */
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
    /* ------------------------------ Final dos Eventos de Abertura dos Modais ------------------------------ */
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

