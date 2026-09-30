@extends('adminlte::page')

@section('title', 'Conta Corrente')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Pagamento de Conta Corrente</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.homePCCLoteConsulta')}}">Filtro</a>
            </li>
            <li class="breadcrumb-item active">Consulta</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-12">
        @csrf 
        @method('post')
        <x-adminlte-card title="Lotes de Pagamento Gerados" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
            @php
                // Monta os dados da tabela do bloco
                $heads = [
                    ['label' => '', 'no-export' => true, 'width' => 5],
                    ['label' => '', 'no-export' => true, 'width' => 5],
                    'Lote',
                    'Empresa',
                    'Data Geração',
                    'Caixa',
                    'Observação',
                    'Data Cancelamento',
                    'Usuário de Cancelamento',
                ];

            @endphp
            <x-adminlte-datatable id="table-lotes" :heads="$heads" theme="light" striped hoverable>
                @foreach($dadosLote as $lote)
                    <tr>
                        <td><input type="checkbox" class="record-checkbox-sel" value="{{$lote->paghdr_cod_pag}}"></td>
                        <td>
                            <a href="" class="lupa-consulta" title="Detalhes do Lote">
                                <i class="fa-solid fa-magnifying-glass fa-lg" style="color: #74C0FC;"></i>
                            </a>
                        </td>
                        <td>{{ $lote->paghdr_cod_pag }}</td>
                        <td>{{ $lote->paghdr_emp }}</td>
                        <td>{{ $lote->paghdr_dti }}</td>
                        <td>{{ $lote->paghdr_raz }}</td>
                        <td>{{ $lote->paghdr_obs }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach
            </x-adminlte-datatable>
            <x-slot name="footerSlot">
                <div class="d-flex justify-content-between w-100">
                    <a class="btn btn-nexus" title="Voltar" href="{{ route('home.homePCCLoteConsulta') }}">
                        Voltar
                    </a>
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
<!--
|--------------------------------------------------------------------------
| Eventos de geração dos Datatable
|--------------------------------------------------------------------------
-->
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
        var table = $('#table-lotes').DataTable({
            dom: getDatatableDom(),
            buttons: getDatatableButtons(),
            lengthMenu: [5, 10, 25, 50, 100],
            pageLength: 10,
            language: dataTableLangPtBR,
            order: [
                [2, 'desc']
            ],
            pagingType: 'full_numbers',
            processing: true,
            columns: [
                { orderable: false },
                { orderable: false },
                { orderable: true },
                { orderable: true },
                { orderable: true },
                { orderable: true },
                { orderable: true },
                { orderable: true },
                { orderable: true }
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
                customSearchingField('#table-lotes_filter');

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
                $('#table-lotes_wrapper .btn-datatable-dir .tool-bar').prepend(buttonsTooBarHTML);

                /* ******************** Cards dos Botões Principais ******************** */

                /* ********** Botões do Card da Coluna ********** */
                let btnColunaHTML = `
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="2" href="#">Tipo da Conta</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="3" href="#">Cliente</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="4" href="#">Conta</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="5" href="#">Subtipo</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="6" href="#">Data de Vencimento</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="7" href="#">Valor da Conta</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="8" href="#">Valor a Pagar</a>
                `;

                /* ********** Monta o Card da Coluna ********** */
                let cardColunaHTML = getCardColunas(btnColunaHTML);

                /* ********** Adiciona o Card da Coluna ********** */
                // #id = ID_TABELA_wrapper
                $('#table-lotes_wrapper .linha-cards-menu').append(cardColunaHTML);

                /* ********** Campos do Card de Filtro ********** */
                let fieldFiltroHTML = `
                    <div class="row">
                        <x-adminlte-input name="filterCliente" type="text" label="Cliente" class="form-control" placeholder="Filtrar Cliente" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterConta" type="text" label="Conta" class="form-control" placeholder="Filtrar Conta" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterData" type="text" label="Data de Vencimento" class="form-control" placeholder="Filtrar Data de Vencimento" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterValor" type="text" label="Valor da Conta" class="form-control" placeholder="Filtrar Valor da Conta" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                    </div>
                    <div class="row">
                        <x-adminlte-input name="filterSaldo" type="text" label="Valor a Pagar" class="form-control" placeholder="Filtrar Valor a Pagar" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                    </div>
                `;

                /* ********** Monta o Card de Filtro ********** */
                let cardFiltroHTML = getCardFiltros(fieldFiltroHTML);

                /* ********** Adiciona o Card de Filtro ********** */
                // #id = ID_TABELA_wrapper
                $('#table-lotes_wrapper .linha-cards-menu').append(cardFiltroHTML);
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
                $('#table-lotes_wrapper').each(function() {
                    var $wrapper = $(this);  // Armazena o contexto atual do wrapper

                    $wrapper.find('#filterCliente').on('keyup', function() {
                        table.column(3).search(this.value).draw();
                    });

                    $wrapper.find('#filterConta').on('keyup', function() {
                        table.column(4).search(this.value).draw();
                    });

                    $wrapper.find('#filterData').on('keyup', function() {
                        table.column(6).search(this.value).draw();
                    });

                    $wrapper.find('#filterValor').on('keyup', function() {
                        table.column(7).search(this.value).draw();
                    });

                    $wrapper.find('#filterSaldo').on('keyup', function() {
                        table.column(8).search(this.value).draw();
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
        setupActionsColuna(table, '#table-lotes_wrapper');

        /* ------------------------------ Final dos Eventos de Controle do Card de Colunas e Funcionalidades ------------------------------ */

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Controle do Card de Filtro e Funcionalidades
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Chama a função de configuração de filtros
        setupActionsFiltro(table, '#table-lotes_wrapper');

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

        /* ******************** Evento de seleção dos lotes a serem cancelados ******************** */

        // Adicionar o checkbox "Selecionar todos" ao cabeçalho da tabela de seleção de lotes
        $('#table-lotes thead th:first-child').html('<input type="checkbox" id="select-all-sel" class="select-all-checkbox">');

        // Selecionar/Deselecionar todos os checkboxes da tabela de seleção de lotes
        $('#select-all-sel').on('change', function() {
            const isChecked = $(this).is(':checked');
            $('.record-checkbox-sel').prop('checked', isChecked);
        });

        // Gera o submit enviando os dados dos serviços que serão aprovados
        document.getElementById('update-form-sel').addEventListener('submit', function (e) {
            e.preventDefault();

            let selectedLoteCan = [];
            document.querySelectorAll('.record-checkbox-sel:checked').forEach(function (checkbox) {
                selectedLoteCan.push(checkbox.value);
            });

            if (selectedLoteCan.length === 0) {
                Swal.fire({
                    confirmButtonColor: "#007bff",
                    title: "Erro!!!",
                    text: "Por favor, selecione pelo menos um lote para cancelar!",
                    icon: "error"
                });
                return;
            }

            document.getElementById('selected-LoteCan').value = selectedLoteCan.join(',');

            this.submit();
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
