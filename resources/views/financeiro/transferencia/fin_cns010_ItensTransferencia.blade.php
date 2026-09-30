@extends('adminlte::page')

@section('title', 'Transferência')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Transferência de Caixa</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('transferencia.painelTransfCaixa')}}">Filtro</a>
            </li>
            <li class="breadcrumb-item active">
                <a href="{{route('transferencia.painelTransfCaixa')}}">Painel</a>
            </li>
            <li class="breadcrumb-item active">Consulta</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-12">
        <x-adminlte-card title="Duplicatas em Aberto" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
    
            @php
                $heads = [
                    ['label' => '', 'no-export' => true, 'width' => 5],
                    'Status',
                    'Tipo Pagamento',
                    'Tipo Conta',
                    'Responsável',
                    'Número',
                    'Valor',
                ];
            @endphp
            <x-adminlte-datatable id="tabela-itens" :heads="$heads" theme="light" striped hoverable>
                @foreach ($itens as $item)
                    @php
                        if($item->tranitm_situacao == 'T'){
                            $status = "Incluída";
                        }else{
                            $status = "Removida";
                        }

                        if($item->tranitm_tipo == '1'){
                            $tipoTrans = "Cheque";
                        }elseif($item->tranitm_tipo == '2'){
                            $tipoTrans = "Cartão de Débito";
                        }elseif($item->tranitm_tipo == '3'){
                            $tipoTrans = "Cartão de Crédito";
                        }else{
                            $tipoTrans = "Dinheiro";
                        }

                        $tipoCC = HelperFormatSelect::formataTipoCC($item->tranitm_cc_tipo);

                        if($item->tranitm_tipo == '1'){
                            $resp = HelperFormatSelect::formataBancoCncCodNom($item->tranitm_cc_res);
                        }else{
                            $resp = HelperFormatSelect::formataRazaoCodNom($item->tranitm_cc_res);
                        }
                    @endphp
                    <tr>
                        <td><input type="checkbox" class="record-checkbox-sel" value="{{ $item->tranitm_sequencia }}"></td>   
                        <td>{{ $status }}</td> 
                        <td>{{ $tipoTrans }}</td> 
                        <td>{{ $tipoCC }}</td> 
                        <td>{{ $resp }}</td> 
                        <td>{{ $item->tranitm_cc_cod }}</td> 
                        <td>{{ Helper::formataValorMonetario($item->tranitm_valor) }}</td> 
                    </tr>
                @endforeach
            </x-adminlte-datatable>

            <x-slot name="footerSlot">
                <div class="d-flex justify-content-between w-100">
                    <div class="d-flex">
                        <form id="update-form-contas" action="{{ route('transferencia.transfUpConta', ['origem' => $origemConsulta]) }}" method="POST">
                            @csrf

                            <input type="hidden" name="selected_contas" id="selected-contas">
                            <input type="hidden" name="opcao" id="opcao">

                            <x-adminlte-button class="btn-nexus mr-2" theme="" label="Transferir" icon="fa-solid fa-right-left" type="button" onclick="setOpcaoAndSubmit('T')"/>
                            <x-adminlte-button class="btn-nexus" theme="" label="Remover" icon="fa-solid fa-xmark" type="button" onclick="setOpcaoAndSubmit('N')"/>
                        </form>
                    </div>
                    <div>
                        <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('transferencia.painelTransfCaixa') }}'" label="Voltar" theme="info" icon=""/>
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

        /* ********** Inicializa o Datatable ********** */
        var table = $('#tabela-itens').DataTable({
            dom: getDatatableDom(),
            buttons: getDatatableButtons(),
            lengthMenu: [5, 10, 25, 50, 100],
            pageLength: 10,
            language: dataTableLangPtBR,
            order: [
                [4, 'asc'],
                [5, 'asc']
            ],
            pagingType: 'full_numbers',
            processing: true,
            columns: [
                { orderable: false },
                null,
                null,
                null,
                null,
                null,
                null,
            ],
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
                customSearchingField('#tabela-itens_filter');

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
                let buttonsTooBarHTML = getButtonsTollBar(['colunas','filtro']);

                /* ********** Adicionando os botões na barra de ferramentas ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-itens_wrapper .btn-datatable-dir .tool-bar').prepend(buttonsTooBarHTML);

                /* ******************** Cards dos Botões Principais ******************** */

                /* ********** Botões do Card da Coluna ********** */
                let btnColunaHTML = `
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="1" href="#">Status</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="2" href="#">Tipo Pagamento</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="3" href="#">Tipo Conta</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="4" href="#">Responsável</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="5" href="#">Número</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="6" href="#">Valor</a>
                `;

                /* ********** Monta o Card da Coluna ********** */
                let cardColunaHTML = getCardColunas(btnColunaHTML);

                /* ********** Adiciona o Card da Coluna ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-itens_wrapper .linha-cards-menu').append(cardColunaHTML);

                /* ********** Campos do Card de Filtro ********** */
                let fieldFiltroHTML = `
                    <div class="row">
                        <x-adminlte-input name="filterRes" type="text" label="Responsável" class="form-control" placeholder="Filtrar Responsável" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterNum" type="text" label="Número da Conta" class="form-control" placeholder="Filtrar Número da Conta" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterValor" type="text" label="Valor da Conta" class="form-control" placeholder="Filtrar Valor da Conta" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                    </div>
                `;

                /* ********** Monta o Card de Filtro ********** */
                let cardFiltroHTML = getCardFiltros(fieldFiltroHTML);

                /* ********** Adiciona o Card de Filtro ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-itens_wrapper .linha-cards-menu').append(cardFiltroHTML);
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
                $('#tabela-itens_wrapper').each(function() {
                    var $wrapper = $(this);  // Armazena o contexto atual do wrapper


                    // Filtrando por Responsável
                    $wrapper.find('#filterRes').on('keyup', function() {
                        table.column(4).search(this.value).draw();
                    });

                    // Filtrando por Número da Conta
                    $wrapper.find('#filterNum').on('keyup', function() {
                        table.column(5).search(this.value).draw();
                    });

                    // Filtrando por Valor da Conta
                    $wrapper.find('#filterValor').on('keyup', function() {
                        table.column(6).search(this.value).draw();
                    });
                });
                /* ------------------------------ Final dos Eventos dos Filtros Individuais ------------------------------ */
                
            }
        });

        /* ------------------------------ Final a Inicialização do Datatable ------------------------------ */

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Controle do Card de Colunas e Funcionalidades
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Chama a função para adicionar os eventos de visibilidade das colunas
        setupActionsColuna(table, '#tabela-itens_wrapper');

        /* ------------------------------ Final dos Eventos de Controle do Card de Colunas e Funcionalidades ------------------------------ */

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Controle do Card de Filtro e Funcionalidades
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Chama a função de configuração de filtros
        setupActionsFiltro(table, '#tabela-itens_wrapper');

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
        /* ******************** Evento de seleção dos serviços a serem aprovados - CONSULTA_REQUISICAO ******************** */

        // Checkbox "Selecionar todos"
        $('#tabela-itens thead th:first-child')
            .html('<input type="checkbox" id="select-all-sel" class="select-all-checkbox">');

        $('#select-all-sel').on('change', function () {
            const isChecked = $(this).is(':checked');
            $('.record-checkbox-sel').prop('checked', isChecked);
        });

        // Submit do form único
        document.getElementById('update-form-contas').addEventListener('submit', function (e) {
            e.preventDefault();

            let selectedContas = [];
            document.querySelectorAll('.record-checkbox-sel:checked').forEach(function (checkbox) {
                selectedContas.push(checkbox.value);
            });

            if (selectedContas.length === 0) {
                Swal.fire({
                    confirmButtonColor: "#007bff",
                    title: "Erro!!!",
                    text: "Por favor, selecione pelo menos uma conta.",
                    icon: "error"
                });
                return;
            }

            // garante que algum botão foi clicado
            const opcao = document.getElementById('opcao').value;
            if (!opcao) {
                Swal.fire({
                    title: "Atenção",
                    text: "Escolha Transferir ou Remover.",
                    icon: "info"
                });
                return;
            }

            document.getElementById('selected-contas').value = selectedContas.join(',');

            this.submit();
        });

        window.setOpcaoAndSubmit = function (valor) {
            document.getElementById('opcao').value = valor;
            document.getElementById('update-form-contas').requestSubmit();
        };
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
