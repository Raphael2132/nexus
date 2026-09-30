@extends('adminlte::page')

@section('title', 'Geral da Empresa')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Parâmetros Gerais</h4>
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

            //Variavel para o filtro
            $arraySelEmp = HelperArraySelect::arrayEmpresas(2,1);
            $arraySelDiaFun = HelperArrayFixo::arrayDiaFuncionamentoEmpresa(2,1);
        @endphp
        <x-adminlte-card  title="Empresas Cadastradas" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
            <x-adminlte-datatable id="tabela-home" :heads="$heads" theme="light" striped hoverable>
                @foreach ($dataParGerEmp as $empresa)
                    @php
                        $dataEmp = DB::table('cadastro_empresas')->where('empresa_codigo',$empresa->parger_emp)->first();
                        $emp = $empresa->parger_emp.' - '.$dataEmp->empresa_nome;

                        $diaFun = Helper::formataDiaFuncionamentoEmpresa($empresa->parger_dia_fun);
                    @endphp
                    <tr>
                        <td>{{ $emp }}</td>
                        <td>{{ $empresa->parger_dia_fun.' - '.$diaFun }}</td>
                        <td>{{ Helper::formataHoraMinuto($empresa->parger_hr_ini_fun) }}</td>
                        <td>{{ Helper::formataHoraMinuto($empresa->parger_hr_fin_fun) }}</td>
                        <td>{{ Helper::formataSimNao($empresa->parger_int_fun) }}</td>
                        <td>{{ Helper::formataHoraMinuto($empresa->parger_hr_ini_int) }}</td>
                        <td>{{ Helper::formataHoraMinuto($empresa->parger_hr_fin_int) }}</td>
                        <td>{{ Helper::formataSimNao($empresa->parger_tur_srv) }}</td>
                        <td>
                            <nobr class="d-flex justify-content-center">
                                <form method="get" action="{{ route('geralEmpresa.edit', ['geralEmpresa' => $empresa->parger_emp]) }}" style="float: left;">
                                    @csrf
                                    <button class="btn btn-xs btn-outline-primary mx-1" title="Editar Registro" value="Edit" type="submit">
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
        var table = $('#tabela-home').DataTable({
            dom: getDatatableDom(),
            buttons: getDatatableButtons(),
            lengthMenu: [5, 10, 25, 50, 100],
            pageLength: 10,
            language: dataTableLangPtBR,
            order: [
                [0, 'asc']
            ],
            pagingType: 'full_numbers',
            processing: true,
            columns: [
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                { orderable: false },
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
                customSearchingField('#tabela-home_filter');

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
                $('#tabela-home_wrapper .btn-datatable-dir .tool-bar').prepend(buttonsTooBarHTML);

                /* ******************** Cards dos Botões Principais ******************** */

                /* ********** Botões do Card da Coluna ********** */
                let btnColunaHTML = `
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="0" href="#">Empresa</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="1" href="#">Dias de Funcionamento</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="2" href="#">Hora Ini. Funcionamento</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="3" href="#">Hora Fin. Funcionamento</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="4" href="#">Intervalo Funcionamento</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="5" href="#">Hora Ini. Intervalo</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="6" href="#">Hora Fin. Intervalo</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="7" href="#">Turno de Serviço</a>
                `;

                /* ********** Monta o Card da Coluna ********** */
                let cardColunaHTML = getCardColunas(btnColunaHTML);

                /* ********** Adiciona o Card da Coluna ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-home_wrapper .linha-cards-menu').append(cardColunaHTML);

                /* ********** Campos do Card de Filtro ********** */
                let fieldFiltroHTML = `
                    <div class="row">
                        <x-adminlte-select name="filterEmp" label="Empresa" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="$arraySelEmp" empty-option="Selecione..."/>
                        </x-adminlte-select>
                        <x-adminlte-select name="filterDiaFun" label="Dias de Funcionamento" igroup-size="sm" fgroup-class="col-md-2">
                            <x-adminlte-options :options="$arraySelDiaFun" empty-option="Selecione..."/>
                        </x-adminlte-select>
                        <x-adminlte-input name="filterHrIni" type="text" label="Hora Ini. Funcionamento" class="form-control" placeholder="Filtrar Hora Ini. Funcionamento" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterHrFin" type="text" label="Hora Fin. Funcionamento" class="form-control" placeholder="Filtrar Hora Fin. Funcionamento" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                    </div>
                    <div class="row">
                        <x-adminlte-select name="filterInt" label="Intervalo Funcionamento" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="['Sim' => 'Sim', 'Não' => 'Não']" empty-option="Selecione..."/>
                        </x-adminlte-select>
                        <x-adminlte-input name="filterHrIniInt" type="text" label="Hora Ini. Intervalo" class="form-control" placeholder="Filtrar Hora Ini. Intervalo" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterHrFinInt" type="text" label="Hora Fin. Intervalo" class="form-control" placeholder="Filtrar Hora Fin. Intervalo" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-select name="filterTurno" label="Turno de Serviço" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="['Sim' => 'Sim', 'Não' => 'Não']" empty-option="Selecione..."/>
                        </x-adminlte-select>
                    </div>
                `;

                /* ********** Monta o Card de Filtro ********** */
                let cardFiltroHTML = getCardFiltros(fieldFiltroHTML);

                /* ********** Adiciona o Card de Filtro ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-home_wrapper .linha-cards-menu').append(cardFiltroHTML);
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
                $('#tabela-home_wrapper').each(function() {
                    var $wrapper = $(this);  // Armazena o contexto atual do wrapper

                    $wrapper.find('#filterEmp').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(0).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterDiaFun').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(1).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterHrIni').on('keyup', function() {
                        table.column(2).search(this.value).draw();
                    });

                    $wrapper.find('#filterHrFin').on('keyup', function() {
                        table.column(3).search(this.value).draw();
                    });

                    $wrapper.find('#filterInt').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(4).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterHrIniInt').on('keyup', function() {
                        table.column(5).search(this.value).draw();
                    });

                    $wrapper.find('#filterHrFinInt').on('keyup', function() {
                        table.column(6).search(this.value).draw();
                    });

                    $wrapper.find('#filterTurno').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(7).search(selectedValue).draw();
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
        setupActionsColuna(table, '#tabela-home_wrapper');

        /* ------------------------------ Final dos Eventos de Controle do Card de Colunas e Funcionalidades ------------------------------ */

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Controle do Card de Filtro e Funcionalidades
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Chama a função de configuração de filtros
        setupActionsFiltro(table, '#tabela-home_wrapper');

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
</script>
@stop
