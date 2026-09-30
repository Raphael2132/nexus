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
            <li class="breadcrumb-item active">Consulta</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-12">
        <x-adminlte-card title="Relatório de Trasferências Realizadas" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
    
            @php
                $heads = [
                    ['label' => '', 'no-export' => true, 'width' => 5],
                    'ID',
                    'Data',
                    'Tipo Transferência',
                    'Usuário',
                    'Origem',
                    'Razão Origem',
                    'Destino',
                    'Razão Destino',
                    'Observação',
                ];
            @endphp
            <x-adminlte-datatable id="tabela-itens" :heads="$heads" theme="light" striped hoverable>
                @foreach ($dadosTransf as $transf)

                    @php
                        if($transf->transf_tipo = '1'){
                            $tipTransf = "Transferência de Caixa Operacional";
                        }else{
                            $tipTransf = "Reforço de Caixa Operacional";
                        }

                        $dadosUsuario = DB::table('users')->where('usuario_codigo',$transf->transf_usuario)->first();    
                        $usuario = $transf->transf_usuario." - ".$dadosUsuario->name;

                        $tipRazOri = HelperFormatSelect::formataTipoCC($transf->transf_tipo_ori);
                        $tipRazDest = HelperFormatSelect::formataTipoCC($transf->transf_tipo_dest);

                        $razOri = HelperFormatSelect::formataRazaoCodNom($transf->transf_codigo_ori);
                        $razDest = HelperFormatSelect::formataRazaoCodNom($transf->transf_codigo_dest);
                    @endphp
                    <tr>
                        <td>
                            <a href="{{route('transferencia.resumoTransfRealizada',['idTransf' => $transf->transf_id])}}" class="lupa-consulta" title="Detalhes da Transferência">
                                <i class="fa-solid fa-magnifying-glass fa-lg" style="color: #74C0FC;"></i>
                            </a></td>   
                        <td>{{  $transf->transf_id }}</td> 
                        <td>{{  Helper::formataData($transf->transf_data) }}</td> 
                        <td>{{  $tipTransf }}</td> 
                        <td>{{  $usuario }}</td> 
                        <td>{{  $tipRazOri }}</td> 
                        <td>{{  $razOri }}</td> 
                        <td>{{  $tipRazDest }}</td> 
                        <td>{{  $razDest }}</td> 
                        <td>{{  $transf->transf_observacao }}</td>
                    </tr>
                @endforeach
            </x-adminlte-datatable>

            <x-slot name="footerSlot">
                <div class="d-flex justify-content-end">
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
                [1, 'asc']
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
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="1" href="#">ID</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="2" href="#">Data</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="3" href="#">Tipo Transferência</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="4" href="#">Usuário</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="5" href="#">Origem</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="6" href="#">Razão Origem</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="7" href="#">Destino</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="8" href="#">Razão Destino</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="9" href="#">Observação</a>
                `;

                /* ********** Monta o Card da Coluna ********** */
                let cardColunaHTML = getCardColunas(btnColunaHTML);

                /* ********** Adiciona o Card da Coluna ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-itens_wrapper .linha-cards-menu').append(cardColunaHTML);

                /* ********** Campos do Card de Filtro ********** */
                let fieldFiltroHTML = `
                    <div class="row">
                        <x-adminlte-input name="filterID" type="text" label="ID" class="form-control" placeholder="Filtrar ID" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterData" type="text" label="Data" class="form-control" placeholder="Filtrar Data" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-select name="filterTip" label="Tipo Transferência" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="['Transferência de Caixa Operaciona' => 'Transferência de Caixa Operaciona', 'Reforço de Caixa Operacional']" empty-option="Selecione..."/>
                        </x-adminlte-select>
                        <x-adminlte-input name="filterUsu" type="text" label="Usuário" class="form-control" placeholder="Filtrar Usuário" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                    </div>
                    <div class="row">
                        <x-adminlte-input name="filterOri" type="text" label="Origem" class="form-control" placeholder="Filtrar Origem" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterRazOri" type="text" label="Razão Origem" class="form-control" placeholder="Filtrar Razão Origem" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterDest" type="text" label="Destino" class="form-control" placeholder="Filtrar Destino" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterRazDest" type="text" label="Razão Destino" class="form-control" placeholder="Filtrar Razão Destino" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                    </div>
                    <div class="row">
                        <x-adminlte-input name="filterObs" type="text" label="Observação" class="form-control" placeholder="Filtrar Observação" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
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

                    $wrapper.find('#filterID').on('keyup', function() {
                        table.column(1).search(this.value).draw();
                    });

                    $wrapper.find('#filterData').on('keyup', function() {
                        table.column(2).search(this.value).draw();
                    });

                    $wrapper.find('#filterTip').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(3).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterUsu').on('keyup', function() {
                        table.column(4).search(this.value).draw();
                    });

                    $wrapper.find('#filterOri').on('keyup', function() {
                        table.column(5).search(this.value).draw();
                    });

                    $wrapper.find('#filterRazOri').on('keyup', function() {
                        table.column(6).search(this.value).draw();
                    });

                    $wrapper.find('#filterDest').on('keyup', function() {
                        table.column(7).search(this.value).draw();
                    });

                    $wrapper.find('#filterRazDest').on('keyup', function() {
                        table.column(8).search(this.value).draw();
                    });

                    $wrapper.find('#filterObs').on('keyup', function() {
                        table.column(9).search(this.value).draw();
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
