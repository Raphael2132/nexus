@extends('adminlte::page')

@section('title', 'Duplicatas')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Recebimento</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.filtroRecDUP')}}">Filtro de Duplicatas</a>
            </li>
            <li class="breadcrumb-item active">Duplicatas em Aberto</li>
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
                    'Empresa Hide',
                    'Cliente Hide',
                    'Duplicata Hide',
                    'Sequencia Hide',
                    ['label' => '', 'no-export' => true, 'width' => 5],
                    'Empresa',
                    'Cliente',
                    'Duplicata',
                    'Data de Emissão',
                    'Data de Vencimento',
                    'Valor',
                    'Valor a Receber',
                ];

                //Variavel para o filtro
                $arraySelEmp = HelperArraySelect::arrayEmpresas(2,1);
            @endphp
            <x-adminlte-datatable id="tabela-duplicatas" :heads="$heads" theme="light" striped hoverable>
                @foreach ($duplicatas as $duplicata)
                    @php
                        $saldo = $duplicata->conrec_val_dup - $duplicata->conrec_val_pag;

                        $codigoFormatado = str_pad($duplicata->conrec_codigo, 8, '0', STR_PAD_LEFT);
                        $sequenciaFormatada = str_pad($duplicata->conrec_sequencia, 2, '0', STR_PAD_LEFT);
                        $concatenado = $codigoFormatado . '/' . $sequenciaFormatada;

                        $empresa = HelperFormatSelect::formataEmpresaCodigoNome($duplicata->conrec_empresa);
                        $cliente = HelperFormatSelect::formataClientes($duplicata->conrec_cliente);

                        $calculo = HelperFinanceiro::calculaMoraDiaAtrasoDuplicata($duplicata->conrec_val_dup, $duplicata->conrec_val_pag, $duplicata->conrec_val_mora, $duplicata->conrec_dt_vencimento);
                        $valorRec = $calculo['saldo'] + $calculo['mora_total'];
                    @endphp
                    <tr>
                        <td>{{ $duplicata->conrec_empresa }}</td>
                        <td>{{ $duplicata->conrec_cliente }}</td>
                        <td>{{ $duplicata->conrec_codigo }}</td>
                        <td>{{ $duplicata->conrec_sequencia }}</td> 
                        <td>
                            <nobr class="d-flex justify-content-center">
                                <a class="btn btn-nexus btn-sm" title="Selecionar" href="{{ route('recebimentoDUP.iniciaDUPSel', ['empresa' => $duplicata->conrec_empresa, 'cliente' => $duplicata->conrec_cliente, 'numDUP' => $duplicata->conrec_codigo, 'seqDUP' => $duplicata->conrec_sequencia]) }}">Selecionar</a>
                            </nobr>                       
                        </td>
                        <td>{{ $empresa }}</td>
                        <td>{{ $cliente }}</td>
                        <td>{{ $concatenado }}</td>
                        <td>{{ Helper::formataData($duplicata->conrec_dt_emissao) }}</td>      
                        <td>{{ Helper::formataData($duplicata->conrec_dt_vencimento) }}</td> 
                        <td>{{ Helper::formataValorMonetario($calculo['saldo']) }}</td> 
                        <td>{{ Helper::formataValorMonetario($valorRec) }}</td>         
                    </tr>
                @endforeach
            </x-adminlte-datatable>

            <x-slot name="footerSlot">
                <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.filtroRecDUP') }}'" label="Voltar" theme="info" icon=""/>
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
        var table = $('#tabela-duplicatas').DataTable({
            dom: getDatatableDom(),
            buttons: getDatatableButtons(),
            lengthMenu: [5, 10, 25, 50, 100],
            pageLength: 10,
            language: dataTableLangPtBR,
            order: [
                [0, 'asc'],
                [1, 'asc'],
                [2, 'asc'],
                [3, 'asc']
            ],
            pagingType: 'full_numbers',
            processing: true,
            columns: [
                { visible: false },
                { visible: false },
                { visible: false },
                { visible: false },
                { orderable: false },
                null,
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
                customSearchingField('#tabela-duplicatas_filter');

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
                $('#tabela-duplicatas_wrapper .btn-datatable-dir .tool-bar').prepend(buttonsTooBarHTML);

                /* ******************** Cards dos Botões Principais ******************** */

                /* ********** Botões do Card da Coluna ********** */
                let btnColunaHTML = `
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="5" href="#">Empresa</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="6" href="#">cliente</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="7" href="#">Duplicata</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="8" href="#">Data de Emissão</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="9" href="#">Data de Vencimento</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="10" href="#">Valor</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="11" href="#">Valor a Receber</a>
                `;

                /* ********** Monta o Card da Coluna ********** */
                let cardColunaHTML = getCardColunas(btnColunaHTML);

                /* ********** Adiciona o Card da Coluna ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-duplicatas_wrapper .linha-cards-menu').append(cardColunaHTML);

                /* ********** Campos do Card de Filtro ********** */
                let fieldFiltroHTML = `
                    <div class="row">
                        <x-adminlte-select name="filterEmp" label="Empresa" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="$arraySelEmp" empty-option="Selecione..."/>
                        </x-adminlte-select>
                        <x-adminlte-input name="filterCli" type="text" label="Cliente" class="form-control" placeholder="Filtrar Cliente" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterDup" type="text" label="Duplicata" class="form-control" placeholder="Filtrar Duplicata" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterDtEmi" type="text" label="Data de Emissão" class="form-control" placeholder="Filtrar Data de Emissão" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                    </div>
                    <div class="row">
                        <x-adminlte-input name="filterDtVct" type="text" label="Data de Vencimento" class="form-control" placeholder="Filtrar Data de Vencimento" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterVal" type="text" label="Valor" class="form-control" placeholder="Filtrar Valor" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterValRec" type="text" label="Valor a Receber" class="form-control" placeholder="Filtrar Valor a Receber" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                    </div>
                `;

                /* ********** Monta o Card de Filtro ********** */
                let cardFiltroHTML = getCardFiltros(fieldFiltroHTML);

                /* ********** Adiciona o Card de Filtro ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-duplicatas_wrapper .linha-cards-menu').append(cardFiltroHTML);
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
                $('#tabela-duplicatas_wrapper').each(function() {
                    var $wrapper = $(this);  // Armazena o contexto atual do wrapper

                    // Filtrando por Data
                    $wrapper.find('#filterEmp').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(5).search(selectedValue).draw();
                    });

                    // Filtrando por Cliente
                    $wrapper.find('#filterCli').on('keyup', function() {
                        table.column(6).search(this.value).draw();
                    });

                    // Filtrando por Consultor
                    $wrapper.find('#filterDup').on('keyup', function() {
                        table.column(7).search(this.value).draw();
                    });

                    // Filtrando por Previsão de Entrega
                    $wrapper.find('#filterDtEmi').on('keyup', function() {
                        table.column(8).search(this.value).draw();
                    });

                    // Filtrando por Cliente
                    $wrapper.find('#filterDtVct').on('keyup', function() {
                        table.column(9).search(this.value).draw();
                    });

                    // Filtrando por Consultor
                    $wrapper.find('#filterVal').on('keyup', function() {
                        table.column(10).search(this.value).draw();
                    });

                    // Filtrando por Previsão de Entrega
                    $wrapper.find('#filterValRec').on('keyup', function() {
                        table.column(11).search(this.value).draw();
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
        setupActionsColuna(table, '#tabela-duplicatas_wrapper');

        /* ------------------------------ Final dos Eventos de Controle do Card de Colunas e Funcionalidades ------------------------------ */

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Controle do Card de Filtro e Funcionalidades
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Chama a função de configuração de filtros
        setupActionsFiltro(table, '#tabela-duplicatas_wrapper');

        /* ------------------------------ Final dos Eventos de Controle do Card de Filtro e Funcionalidades ------------------------------ */

    });

    /* ------------------------------ Final da Inicialização do Datatable ------------------------------ */
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
