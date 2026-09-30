@extends('adminlte::page')

@section('title', 'Reemissão de NF')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Faturamento de Notas</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.reemissaoNF')}}">Filtro Reemissão de NF</a>
            </li>
            <li class="breadcrumb-item active">Consulta Reemissão de NF</li>
        </ol>
    </div>
</div>
@stop


@section('content')

@php
    //Define as variaveis de sessão aqui na view por que depois de 2 redirect elas são destruidas
    session(['glo_where_reemissao_nf' => $glo_where_reemissao_nf]);

    $heads = [
        'Nota Oculta',
        'Empresa',
        'Cliente',
        'Data Emissao',
        'Pedido / OS',
        'Nota',
        'Valor',
        'Tipo',
        'Situação',
        ['label' => 'Impressão / Email', 'no-export' => true, 'width' => 15]
    ];

    //Variavel para o filtro
    $arraySelEmp = HelperArraySelect::arrayEmpresas(2,1);
@endphp

<x-adminlte-card title="Consulta de Notas para Reemissão" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
    <x-adminlte-datatable id="table-reemissao" :heads="$heads" theme="light" striped hoverable>
        @foreach($dadosHeader as $header)
            @php 
                $data = DB::table('cadastro_empresas')->where('empresa_codigo', $header->nfhdr_emp)->get();
                $data_cli = DB::table('cadastro_clientes')->where('cliente_codigo', $header->nfhdr_cli)->get();
                $dadosXmlNfsEnv = DB::table('faturamento_nfs_xml_envios')->where('nfsenv_emp',$header->nfhdr_emp)->where('nfsenv_num',$header->nfhdr_num_nf)->get();
                
                $moduloRPS = DB::table('parametros_sis_modulos')->where('modulo_empresa_codigo', $header->nfhdr_emp)->first();
                $geraRPS = DB::table('parametros_fat_nfs')->where('parnfs_empresa', $header->nfhdr_emp)->first();
                
                if(!empty($header->nfhdr_dt_nf)){
                    $dataNF = Helper::formataData($header->nfhdr_dt_nf);
                }else{
                    $dataNF = '';
                }

                if(empty($dadosXmlNfsEnv[0]->nfsenv_sts)){
                    $stsNF = 4;
                }elseif($dadosXmlNfsEnv[0]->nfsenv_sts == 1){
                    $stsNF = 1;
                }elseif($dadosXmlNfsEnv[0]->nfsenv_sts == 2){
                    $stsNF = 2;
                }else{
                    $stsNF = 3;
                }

                $estagio = DB::table('faturamento_nf_estagios')->where('nfetg_emp', $header->nfhdr_emp)->where('nfetg_num', $header->nfhdr_num)->first();

                if(!empty($estagio)){
                    if($header->nfhdr_vlr_lqs == 0 AND $header->nfhdr_vlr_srv == 0 AND $header->nfhdr_vlr_srv_ter == 0){

                        if($estagio->nfetg_cod == 2){
                            $errEtg = "Atualizar Documentos";
                        }elseif($estagio->nfetg_cod == 3){
                            $errEtg = "Gerar Faturamento";
                        }elseif($estagio->nfetg_cod == 4){
                            $errEtg = "Gerar Financeiro";
                        }else{
                            $errEtg = '';
                        }

                    }else{

                        if($estagio->nfetg_cod == 0){
                            $errEtg = "Gerar de NFS-e";
                        }elseif($estagio->nfetg_cod == 2){
                            $errEtg = "Atualizar Documentos";
                        }elseif($estagio->nfetg_cod == 3){
                            $errEtg = "Gerar Faturamento";
                        }elseif($estagio->nfetg_cod == 2){
                            $errEtg = "Gerar Financeiro";
                        }else{
                            $errEtg = '';
                        }
                    }
                }else{
                    $errEtg = '';
                }
            @endphp
            <tr>
                <td>{{$header->nfhdr_num_nf}}</td>
                <td>{{$header->nfhdr_emp.' - '.$data[0]->empresa_nome}}</td>
                <td>{{$header->nfhdr_cli.' - '.$data_cli[0]->cliente_nome}}</td>
                <td>{{$dataNF}}</td>
                <td>{{$header->nfhdr_num_ped}}</td>
                <td>{{$header->nfhdr_num_nf.'-'.$header->nfhdr_ser_nf}}</td>
                <td>{{Helper::formataValorMonetario($header->nfhdr_vlr_tot_nf)}}</td>
                <td>NFS-e</td>
                @if($stsNF == 1)
                <td class="max-width-sts"><span class="badge badge-danger">NFS-e Não Enviada: {{$dadosXmlNfsEnv[0]->nfsenv_obs}}</span> <a class="btn btn-outline-nexus btn-sm" href="{{route('emissaoNF.gerarNF', ['origem' => 'REEMISSAO', 'empresa' => $header->nfhdr_emp, 'nfReemissao' => $header->nfhdr_num])}}">Reenviar</a>
                    @if(!empty($errEtg))
                    <a class="" href=""><b class="text-md d-block"><span class="badge badge-warning">{{$errEtg}}</span></b></a>
                    @endif
                </td>
                @elseif($stsNF == 2)
                <td class="max-width-sts"><span class="badge badge-danger">NFS-e Rejeitada: Erro {{$dadosXmlNfsEnv[0]->nfsenv_sts_emi.' - '.$dadosXmlNfsEnv[0]->nfsenv_obs}}</span> <a class="btn btn-outline-nexus btn-sm" href="{{route('emissaoNF.gerarNF', ['origem' => 'REEMISSAO', 'empresa' => $header->nfhdr_emp, 'nfReemissao' => $header->nfhdr_num])}}">Reenviar</a>
                    @if(!empty($errEtg))
                    <a class="" href=""><b class="text-md d-block"><span class="badge badge-warning">{{$errEtg}}</span></b></a>
                    @endif
                </td>
                @elseif($stsNF == 3)
                <td class="max-width-sts"><span class="badge badge-success">{{$dadosXmlNfsEnv[0]->nfsenv_obs}}</span>
                    @if(!empty($errEtg))
                    <a class="" href=""><b class="text-md d-block"><span class="badge badge-warning">{{$errEtg}}</span></b></a>
                    @endif
                </td>
                @else
                <td class="max-width-sts"><span class="badge badge-info">Geração Iniciada</span> <a class="btn btn-outline-nexus btn-sm" href="{{route('emissaoNF.gerarNF', ['origem' => 'REEMISSAO', 'empresa' => $header->nfhdr_emp, 'nfReemissao' => $header->nfhdr_num])}}">Reenviar</a>
                    @if(!empty($errEtg))
                    <a class="" href=""><b class="text-md d-block"><span class="badge badge-warning">{{$errEtg}}</span></b></a>
                    @endif
                </td>
                @endif
                @if($stsNF == 3)
                <td class="d-flex justify-content-center"><a class="btn btn-outline-nexus btn-sm mr-2" href="{{route('impresaoNF.nfsePDF',['empresa' => $header->nfhdr_emp, 'numControle' => $header->nfhdr_num, 'appOrigem' => 'IMPRESSAO'])}}" target="_blank">Abrir NFS-e</a>
                <a class="btn btn-outline-nexus btn-sm" href="{{route('impresaoNF.nfseEmail',['empresa' => $header->nfhdr_emp, 'numControle' => $header->nfhdr_num])}}">Enviar Email</a></td>
                @elseif($stsNF == 2 || $stsNF == 1)
                    @if($moduloRPS->modulo_emissao_rps == "S" && $geraRPS->parnfs_impressao_rps == "S")
                    <td class="d-flex justify-content-center"><a class="btn btn-outline-nexus btn-sm mr-2" href="{{route('impresaoRPS.rpsPDF',['empresa' => $header->nfhdr_emp, 'numControle' => $header->nfhdr_num, 'appOrigem' => 'REEMISSAO'])}}" target="_blank">Abrir RPS</a>
                    <a class="btn btn-outline-nexus btn-sm" href="{{route('impresaoRPS.rpsEmail',['empresa' => $header->nfhdr_emp, 'numControle' => $header->nfhdr_num])}}">Enviar Email</a></td>
                    @else
                    <td></td>
                    @endif
                @else
                <td></td>
                @endif
            </tr>
        @endforeach
    </x-adminlte-datatable>
    <x-slot name="footerSlot">
        <div class="d-flex justify-content-between w-100">
            <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.emissaoNF') }}'" label="Emissão de NF" theme="" icon=""/>
            <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.reemissaoNF') }}'" label="Voltar" theme="" icon=""/>
        </div>
    </x-slot>
</x-adminlte-card>
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.Sweetalert2', true)

@section('css')
<style>
    .max-width-sts {
        max-width: 50ch;
        white-space: normal; /* Permite quebra de linha */
        overflow-wrap: break-word; /* Permite quebras de linha apenas em espaços */
        word-break: keep-all; /* Evita quebras de linha no meio de palavras */
    }
    .badge, .badge-success {
        white-space: normal; /* Permite quebra de linha */
        overflow-wrap: break-word; /* Permite quebras de linha apenas em espaços */
        word-break: keep-all; /* Evita quebras de linha no meio de palavras */
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
        var groupColumns = [];

        /* ********** Inicializa o Datatable ********** */
        var table = $('#table-reemissao').DataTable({
            dom: getDatatableDom(),
            buttons: getDatatableButtons(),
            lengthMenu: [5, 10, 25, 50, 100],
            pageLength: 10,
            language: dataTableLangPtBR,
            order: [
                [0, 'desc']
            ],
            pagingType: 'full_numbers',
            processing: true,
            columns: [
                {orderable: false, visible: false},
                null, 
                null, 
                null, 
                null, 
                null, 
                null, 
                null, 
                null, 
                {orderable: false}
            ],
            columnDefs: [
                { visible: false, targets: groupColumns },
            ],
            drawCallback: function (settings) {

                var api = this.api();
                var colspan = 10; // Ou qualquer valor que você precise para o colspan
                
                applyRowGrouping(api, groupColumns, colspan); // Chama a função externa
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
                customSearchingField('#table-reemissao_filter');

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
                $('#table-reemissao_wrapper .btn-datatable-dir .tool-bar').prepend(buttonsTooBarHTML);

                /* ******************** Cards dos Botões Principais ******************** */

                /* ********** Botões do Card da Coluna ********** */
                let btnColunaHTML = `
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="1" href="#">Empresa</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="2" href="#">Cliente</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="3" href="#">Data Emissão</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="4" href="#">Pedido/OS</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="5" href="#">Nota</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="6" href="#">Valor</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="7" href="#">Tipo</a>
                `;

                /* ********** Monta o Card da Coluna ********** */
                let cardColunaHTML = getCardColunas(btnColunaHTML);

                /* ********** Adiciona o Card da Coluna ********** */
                // #id = ID_TABELA_wrapper
                $('#table-reemissao_wrapper .linha-cards-menu').append(cardColunaHTML);

                /* ********** Campos do Card de Filtro ********** */
                let fieldFiltroHTML = `
                    <div class="row">
                        <x-adminlte-select name="filterEmp" label="Empresa" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="$arraySelEmp" empty-option="Selecione..."/>
                        </x-adminlte-select>
                        <x-adminlte-input name="filterCliente" type="text" label="Cliente" class="form-control" placeholder="Filtrar Cliente" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterData" type="text" label="Data Emissão" class="form-control" placeholder="Filtrar Data Emissão" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterPedido" type="text" label="Pedido / OS" class="form-control" placeholder="Filtrar Pedido / OS" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                    </div>
                    <div class="row">
                        <x-adminlte-input name="filterNF" type="text" label="Nota" class="form-control" placeholder="Filtrar Nota" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterValor" type="text" label="Valor" class="form-control" placeholder="Filtrar Valor" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                    </div>
                `;

                /* ********** Monta o Card de Filtro ********** */
                let cardFiltroHTML = getCardFiltros(fieldFiltroHTML);

                /* ********** Adiciona o Card de Filtro ********** */
                // #id = ID_TABELA_wrapper
                $('#table-reemissao_wrapper .linha-cards-menu').append(cardFiltroHTML);
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
                $('#table-reemissao_wrapper').each(function() {
                    var $wrapper = $(this);  // Armazena o contexto atual do wrapper

                    $wrapper.find('#filterEmp').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(1).search(selectedValue).draw();
                    });

                    $wrapper.find('#filterCliente').on('keyup', function() {
                        table.column(2).search(this.value).draw();
                    });

                    $wrapper.find('#filterData').on('keyup', function() {
                        table.column(3).search(this.value).draw();
                    });

                    $wrapper.find('#filterPedido').on('keyup', function() {
                        table.column(4).search(this.value).draw();
                    });

                    $wrapper.find('#filterNF').on('keyup', function() {
                        table.column(5).search(this.value).draw();
                    });

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
        setupActionsColuna(table, '#table-reemissao_wrapper');

        /* ------------------------------ Final dos Eventos de Controle do Card de Colunas e Funcionalidades ------------------------------ */

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Controle do Card de Filtro e Funcionalidades
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Chama a função de configuração de filtros
        setupActionsFiltro(table, '#table-reemissao_wrapper');

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
