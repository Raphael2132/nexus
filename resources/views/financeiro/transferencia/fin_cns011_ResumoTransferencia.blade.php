@extends('adminlte::page')

@section('title', 'Transferência')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Relatório de Transfências</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.homeConsultaTransf')}}">Filtro</a>
            </li>
            <li class="breadcrumb-item active">
                <a href="">Consulta</a>
            </li>
            <li class="breadcrumb-item active">Resumo</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-12">
        <x-adminlte-card title="Resumo da Trasferência" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
    
            @php
                $heads = [
                    'Tipo Pagamento',
                    'Administradora',
                    'Agência',
                    'Número da Conta',
                    'Número Cheque/Cartão',
                    'Responsável',
                    'Data Emissão',
                    'Data Vencimento',
                    'Valor',
                ];
            @endphp
            <x-adminlte-datatable id="tabela-transf" :heads="$heads" theme="light" striped hoverable>
                @foreach ($dadosTransf as $transf)
                    @php 
                        if($transf->tranitm_tipo == '0'){
                            $tipo = '0 - Dinheiro';
                            $adm = '';
                        }elseif($transf->tranitm_tipo == '1'){
                            $tipo = '1 - Cheque';
                            $adm = HelperFormatSelect::formataBancoCncCodNom($transf->tranitm_cc_res);
                        }elseif($transf->tranitm_tipo == '2'){
                            $tipo = '2 - Cartão de Débito';
                            $adm = HelperFormatSelect::formataRazaoCodNom($transf->tranitm_cc_res);
                        }else{
                            $tipo = '3 - Cartão de Crédito';
                            $adm = HelperFormatSelect::formataRazaoCodNom($transf->tranitm_cc_res);
                        }

                        if(!empty($transf->conta_dt_emissao)){
                            $dataEmi = Helper::formataData($transf->conta_dt_emissao);
                        }else{
                            $dataEmi = '';
                        }

                        if(!empty($transf->conta_dt_vencimento)){
                            $dataVct = Helper::formataData($transf->conta_dt_vencimento);
                        }else{
                            $dataVct = '';
                        }
                    @endphp
                    <tr>
                        <td>{{  $tipo }}</td> 
                        <td>{{  $adm }}</td> 
                        <td>{{  $transf->conta_ag_cheque }}</td> 
                        <td>{{  $transf->conta_cc_cheque }}</td> 
                        <td>{{  $transf->conta_comprovante }}</td> 
                        <td>{{  $transf->conta_emitente }}</td> 
                        <td>{{  $dataEmi }}</td> 
                        <td>{{  $dataVct }}</td> 
                        <td>{{  Helper::formataValorMonetario($transf->tranitm_valor) }}</td> 
                    </tr>
                @endforeach
            </x-adminlte-datatable>

            <x-slot name="footerSlot">
                <div class="d-flex justify-content-between w-100">
                    <div>
                        <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.homeConsultaTransf') }}'" label="Voltar" theme="info" icon=""/>
                    </div>
                </div>
            </x-slot>
        </x-adminlte-card>
    </div>
</div>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('css')
<style>.subtotal-level-0 td {
    background: #f8f9fa;
    font-weight: 600;
}

.subtotal-level-1 td {
    background: #f1f3f5;
}

.subtotal-level-2 td {
    background: #e9ecef;
}

.grand-total-row td {
    background: #dfe6e9;
    font-size: 15px;
}

</style>
@stop

@section('js')
<script>
    /* *****
    |----------------------------------------------------------------------------------------------------
    | Eventos da Inicialização do Datatable
    |----------------------------------------------------------------------------------------------------
    |
    | Adicionamos aqui todos os eventos relacionados a criação e manipulação de eventos do datatable
    |
    ***** */
    $(() => {

        // Variavel do agrupamento inicial do Datatable
        var groupColumns = [0,6,1];

        // Colunas que serão totalizadas
        var totalColumns = [8];

        /* ********** Inicializa o Datatable ********** */
        var table = $('#tabela-transf').DataTable({
            dom: getDatatableDom(),
            buttons: getDatatableButtons(),
            lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "Todos"]],
            pageLength: -1,
            language: dataTableLangPtBR,
            order: [
                [0, 'asc'],
                [6, 'asc'],
                [1, 'asc']
            ],
            pagingType: 'full_numbers',
            processing: true,
            columns: [
                { visible: false },
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
            ],
            columnDefs: [
                { visible: true, targets: groupColumns },
            ],
            drawCallback: function (settings) {

                var api = this.api();
                var colspan = 8; // Quantidade de colunas da tabela
                
                //Chama o agrupamento de Linhas para a quebra
                applyRowGrouping(api, groupColumns, colspan);

                // Aplica o SubTotal
                applyHierarchicalTotals(api, groupColumns, totalColumns);

                //Aplica a totalização Geral
                applyGrandTotal(api, totalColumns);
            },
            initComplete: function() {

                /* *****
                |----------------------------------------------------------------------------------------------------
                | Altera o Campo Searching Original
                |----------------------------------------------------------------------------------------------------
                |
                | Alteramos a aparencia do input encapsulando ele dentro de input-group estilizado para o Nexus
                |
                ***** */

                // Chama a função para customizar o filtro da tabela com base no ID
                // #id = ID_TABELA_filter
                customSearchingField('#tabela-transf_filter');

                /* ------------------------------ Final dos Eventos Altera o Campo Searching Original ------------------------------ */

                /* *****
                |----------------------------------------------------------------------------------------------------
                | Cards e Botões da Barra de Ferramentas do Datatable
                |----------------------------------------------------------------------------------------------------
                |
                | Adicionamos aqui a criação dos Cards e Botões utilizados na barra de ferramentas do datatable
                |
                ***** */

                /* ******************** Botões Principais ******************** */

                /* ********** Criação dos botões principais da barra de ferramentas ********** */
                let buttonsTooBarHTML = getButtonsTollBar(['quebra','colunas','filtro']);

                /* ********** Adicionando os botões na barra de ferramentas ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-transf_wrapper .btn-datatable-dir .tool-bar').prepend(buttonsTooBarHTML);

                /* ******************** Cards dos Botões Principais ******************** */

                /* ********** Botões do Card da Quebra ********** */
                let btnQuebraHTML = `
                    <a class="btn btn-nexus groupCol mr-2" data-column="0" href="#">Tipo</a>
                    <a class="btn btn-nexus groupCol mr-2" data-column="6" href="#">Data Emissão</a>
                    <a class="btn btn-nexus groupCol mr-2" data-column="1" href="#">Administradora</a>
                `;

                /* ********** Monta o Card da Quebra ********** */
                let cardQuebraHTML = getCardQuebras(btnQuebraHTML);

                /* ********** Adiciona o Card da Quebra ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-transf_wrapper .linha-cards-menu').append(cardQuebraHTML);

                /* ********** Botões do Card da Coluna ********** */
                let btnColunaHTML = `
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="1" href="#">Administradora</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="2" href="#">Agência</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="3" href="#">Número da Conta</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="4" href="#">Número do Cheque/Cartão</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="5" href="#">Responsável</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="6" href="#">Data Emissão</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="7" href="#">Data Vencimento</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="8" href="#">Valor</a>
                `;

                /* ********** Monta o Card da Coluna ********** */
                let cardColunaHTML = getCardColunas(btnColunaHTML);

                /* ********** Adiciona o Card da Coluna ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-transf_wrapper .linha-cards-menu').append(cardColunaHTML);

                /* ********** Campos do Card de Filtro ********** */
                let fieldFiltroHTML = `
                    <div class="row">
                        <x-adminlte-input name="filterAdm" type="text" label="Administradora" class="form-control" placeholder="Filtrar Administradora" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterAge" type="text" label="Agência" class="form-control" placeholder="Filtrar Agência" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterNumCC" type="text" label="Número da Conta" class="form-control" placeholder="Filtrar Número da Conta" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterNumChqCC" type="text" label="Número Cheque/Cartão" class="form-control" placeholder="Filtrar Número Cheque/Cartão" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                    </div>
                    <div class="row">
                        <x-adminlte-input name="filterRes" type="text" label="Responsável" class="form-control" placeholder="Filtrar Responsável" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterDte" type="text" label="Data Emissão" class="form-control" placeholder="Filtrar Data Emissão" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterDtv" type="text" label="Data Vencimento" class="form-control" placeholder="Filtrar Data Vencimento" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterValor" type="text" label="Valor" class="form-control" placeholder="Filtrar Valor" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                    </div>
                `;

                /* ********** Monta o Card de Filtro ********** */
                let cardFiltroHTML = getCardFiltros(fieldFiltroHTML);

                /* ********** Adiciona o Card de Filtro ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-transf_wrapper .linha-cards-menu').append(cardFiltroHTML);
                /* ------------------------------ Final dos Eventos Cards e Botões da Barra de Ferramentas do Datatable ------------------------------ */
                
                /* *****
                |----------------------------------------------------------------------------------------------------
                | Eventos dos Filtros Individuais
                |----------------------------------------------------------------------------------------------------
                |
                | Esses eventos devem ser feitos ao criar a tabela no "initComplete" para funcionar a busca dos dados
                |
                ***** */

                // Escopo para a tabela trabalhada
                // #id = ID_TABELA_wrapper
                $('#tabela-transf_wrapper').each(function() {
                    var $wrapper = $(this);  // Armazena o contexto atual do wrapper


                    // Filtrando por Responsável
                    $wrapper.find('#filterAdm').on('keyup', function() {
                        table.column(1).search(this.value).draw();
                    });

                    // Filtrando por Número da Conta
                    $wrapper.find('#filterAge').on('keyup', function() {
                        table.column(2).search(this.value).draw();
                    });

                    // Filtrando por Valor da Conta
                    $wrapper.find('#filterNumCC').on('keyup', function() {
                        table.column(3).search(this.value).draw();
                    });

                    // Filtrando por Responsável
                    $wrapper.find('#filterNumChqCC').on('keyup', function() {
                        table.column(4).search(this.value).draw();
                    });

                    // Filtrando por Número da Conta
                    $wrapper.find('#filterRes').on('keyup', function() {
                        table.column(5).search(this.value).draw();
                    });

                    // Filtrando por Valor da Conta
                    $wrapper.find('#filterDte').on('keyup', function() {
                        table.column(6).search(this.value).draw();
                    });

                    // Filtrando por Responsável
                    $wrapper.find('#filterDtv').on('keyup', function() {
                        table.column(7).search(this.value).draw();
                    });

                    // Filtrando por Número da Conta
                    $wrapper.find('#filterValor').on('keyup', function() {
                        table.column(8).search(this.value).draw();
                    });
                });
                /* ------------------------------ Final dos Eventos dos Filtros Individuais ------------------------------ */
                
            }
        });

        /* ------------------------------ Final a Inicialização do Datatable ------------------------------ */

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Controle do Card de Quebra e Funcionalidades
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Ordenação original (Secundária além do agrupamento)
        var originalOrder = [
            [0, 'asc'],
            [6, 'asc'],
            [1, 'asc']
        ];

        // Chama a função e passa as variáveis
        setupActionsQuebra(table, '#tabela-transf_wrapper', '#tabela-transf', originalOrder, groupColumns);

        // Agora ativa o clique nas linhas de grupo - collapse/expand
        setupGroupRowToggle(table, '#tabela-transf');

        /* ------------------------------ Final dos Eventos de Controle do Card de Quebra e Funcionalidades ------------------------------ */

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Controle do Card de Colunas e Funcionalidades
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Chama a função para adicionar os eventos de visibilidade das colunas
        setupActionsColuna(table, '#tabela-transf_wrapper');

        /* ------------------------------ Final dos Eventos de Controle do Card de Colunas e Funcionalidades ------------------------------ */

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Controle do Card de Filtro e Funcionalidades
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Chama a função de configuração de filtros
        setupActionsFiltro(table, '#tabela-transf_wrapper');

        /* ------------------------------ Final dos Eventos de Controle do Card de Filtro e Funcionalidades ------------------------------ */

    });

    /* ------------------------------ Final da Inicialização do Datatable ------------------------------ */
</script>

<!--
|--------------------------------------------------------------------------
| Eventos Iniciais da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() { 

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

    @if(Session::has('info'))
        Swal.fire({
            confirmButtonColor: "#007bff",
            title: "Aviso!",
            text: "{{ session('info') }}",
            icon: "info",
            customClass: {
                icon: "no-before-icon",
            }
        });
    @endif

    @if(Session::has('success2'))
        Swal.fire({
            confirmButtonColor: "#007bff",
            title: "Sucesso!",
            text: "{{ session('success2') }}",
            icon: "success",
            customClass: {
                icon: "no-before-icon",
            }
        });
    @endif
</script>
@stop
