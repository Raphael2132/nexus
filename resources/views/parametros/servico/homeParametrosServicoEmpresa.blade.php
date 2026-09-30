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
                'Aliq. ISS',
                'CFOP de Serviços',
                'Exigibilidade do ISS',
                'ISS Retido',
                'Código de Atividade',
                ['label' => 'Opções', 'no-export' => true, 'width' => 5],
            ];

            //Variavel para o filtro
            $arraySelEmp = HelperArraySelect::arrayEmpresas(2,1);
            $arraySelExiISS = HelperArraySelect::arrayExigibilidadeISS(2,1);
        @endphp
        <x-adminlte-card  title="Empresas Cadastradas" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
            <x-adminlte-datatable id="tabela-home" :heads="$heads" theme="light" striped hoverable>
                @foreach ($dataParSrvEmp as $empresa)
                    @php
                        $dataEmp = DB::table('cadastro_empresas')->where('empresa_codigo',$empresa->parsrv_emp)->get();
                        $emp = $empresa->parsrv_emp.' - '.$dataEmp[0]->empresa_nome;

                        $exISS = DB::table('parametros_sis_exi_iss')->where('exiiss_codigo', $empresa->parsrv_exg_iss)->get();

                        if($empresa->parsrv_iss_ret == '2'){
                            $issRet = 'Não';
                        }else{
                            $issRet = 'Sim';
                        }
                    @endphp
                    <tr>
                        <td>{{ $emp }}</td>
                        <td>{{ Helper::formataPorcentagem($empresa->parsrv_alq_iss) }}</td>
                        <td>{{ $empresa->parsrv_cfop }}</td>
                        <td>{{ $empresa->parsrv_exg_iss.' - '.$exISS[0]->exiiss_desc }}</td>
                        <td>{{ $issRet }}</td>
                        <td>{{ $empresa->parsrv_cod_srv }}</td>
                        <td>
                            <nobr class="d-flex justify-content-center">
                                <form method="get" action="{{ route('geralServico.edit', ['geralServico' => $empresa->parsrv_emp]) }}" style="float: left;">
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
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="1" href="#">Aliq. ISS</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="2" href="#">CFOP de Serviços</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="3" href="#">Exigibilidade do ISS</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="4" href="#">ISS Retido</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="5" href="#">Código de Atividade</a>
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
                        <x-adminlte-input name="filterAlqISS" type="text" label="Aliq. ISS" class="form-control" placeholder="Filtrar Aliq. ISS" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterCFOP" type="text" label="CFOP de Serviços" class="form-control" placeholder="Filtrar CFOP de Serviços" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-select name="filterExiISS" label="Exigibilidade do ISS" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="$arraySelExiISS" empty-option="Selecione..."/>
                        </x-adminlte-select>
                    </div>
                    <div class="row">
                        <x-adminlte-select name="filterIssRet" label="ISS Retido" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="['Sim' => 'Sim', 'Não' => 'Não']" empty-option="Selecione..."/>
                        </x-adminlte-select>
                        <x-adminlte-input name="filterAtiv" type="text" label="Código de Atividade" class="form-control" placeholder="Filtrar Código de Atividade" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
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

                    $wrapper.find('#filterAlqISS').on('keyup', function() {
                        table.column(1).search(this.value).draw();
                    });

                    $wrapper.find('#filterCFOP').on('keyup', function() {
                        table.column(2).search(this.value).draw();
                    });

                    $wrapper.find('#filterExiISS').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(3).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterIssRet').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(4).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterAtiv').on('keyup', function() {
                        table.column(5).search(this.value).draw();
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
