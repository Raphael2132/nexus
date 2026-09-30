@extends('adminlte::page')

@section('title', 'Parâmetros da NFS-e')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Parâmetros Gerais</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.parFatNfs')}}">Parâmetros da NFS-e</a>
            </li>
            <li class="breadcrumb-item active">Provedores da NFS-e</li>
        </ol>
    </div>
</div>
@stop

@section('content')
@php
    $heads = [
        'Código',
        'Provedor',
        'Estado'
    ];
    $arraySelUF = HelperArraySelect::arrayEstados(2,1);
@endphp
<div class="row">
    <div class="esquerdo col-md-6">
        <x-adminlte-card title="Provedores Cadastrados" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
            <x-adminlte-datatable id="tabela-provedores" :heads="$heads" theme="light" striped hoverable>
                @foreach ($provedores as $provedor)
                    <tr>
                        <td>{{ $provedor->provedor_codigo }}</td>
                        <td>{{ $provedor->provedor_desc }}</td>     
                        <td>{{ HelperFormatSelect::formataEstadoDesc($provedor->provedor_uf) }}</td>            
                    </tr>
                @endforeach
            </x-adminlte-datatable>
        </x-adminlte-card>
    </div>
    <div class="direito col-md-6">
        <form method="post" action="{{route('provedorNFSe.store')}}" id="quickForm" novalidate="novalidate">
            @csrf 
            <x-adminlte-card title="Cadastrar Novo Provedor" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>

                @php
                    $array_opt = HelperArraySelect::arrayEstados(1,1);

                    //Faz o lookup do campo de cidades
                    $html = HelperDataList::geraDatalistMunicipios('cidades');

                    //Echo adiciona o html ao campo das cidades
                    echo $html;

                    $codigo = DB::table('parametros_fat_nfs_provedores')->max('provedor_codigo') +1;
                @endphp

                <!-- Código do Provedor -->
                <x-adminlte-input name="codigo" type="number" placeholder="Código do Provedor" value="{{$codigo}}" fgroup-class="col-md-12">
                    <x-slot name="label">
                        Código <span style="color:red;">*</span>
                    </x-slot>
                </x-adminlte-input>

                <!-- Estado -->
                <x-adminlte-select name="uf" fgroup-class="col-md-12">
                    <x-slot name="label">
                        Estado do Provedor <span style="color:red;">*</span>
                    </x-slot>
                    <x-adminlte-options :options="$array_opt" empty-option="Selecione..."/>
                </x-adminlte-select>

                <!-- Cidade do Provedor -->
                <x-adminlte-input name="cidade" type="search" list="cidades" autocomplete="off" placeholder="Nome do Provedor" autocomplete="off" fgroup-class="col-md-12">
                    <x-slot name="label">
                        Cidade <span style="color:red;">*</span>
                    </x-slot>
                    <x-slot name="prependSlot">
                        <div class="input-group-text x-slot-nexus">
                            <i class="fa-solid fa-city"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>

                <x-slot name="footerSlot">
                    <div class="d-flex justify-content-between w-100">
                        <x-adminlte-button class="btn-nexus" type="submit" label="Incluir" theme="info" icon="fa-solid fa-share-from-square"/>
                        <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.parFatNfs') }}'" label="Voltar" theme="info" icon=""/>
                    </div>
                </x-slot>
            </x-adminlte-card>
        </form>
    </div>
</div>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.jqueryValidation', true)

@section('css')
<style>
    .esquerdo {
        float: left;
    }

    .direito {
        float: right;
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
        var table = $('#tabela-provedores').DataTable({
            dom: getDatatableDom(),
            buttons: getDatatableButtons(),
            lengthMenu: [5, 10, 25, 50, 100],
            pageLength: 5,
            language: dataTableLangPtBR,
            order: [
                [0, 'asc']
            ],
            pagingType: 'full_numbers',
            processing: true,
            columns: [
                null,
                null,
                null
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
                customSearchingField('#tabela-provedores_filter');

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
                $('#tabela-provedores_wrapper .btn-datatable-dir .tool-bar').prepend(buttonsTooBarHTML);

                /* ******************** Cards dos Botões Principais ******************** */

                /* ********** Botões do Card da Coluna ********** */
                let btnColunaHTML = `
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="0" href="#">Código</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="1" href="#">Provedor</a>
                    <a class="btn btn-nexus toggle-vis mr-2" data-column="2" href="#">Estado</a>
                `;

                /* ********** Monta o Card da Coluna ********** */
                let cardColunaHTML = getCardColunas(btnColunaHTML);

                /* ********** Adiciona o Card da Coluna ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-provedores_wrapper .linha-cards-menu').append(cardColunaHTML);

                /* ********** Campos do Card de Filtro ********** */
                let fieldFiltroHTML = `
                    <div class="row">
                        <x-adminlte-input name="filterCod" type="text" label="Código" class="form-control" placeholder="Filtrar Código" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-input name="filterPro" type="text" label="Provedor" class="form-control" placeholder="Filtrar Provedor" igroup-size="sm" fgroup-class="col-md-3"></x-adminlte-input>
                        <x-adminlte-select name="filterEst" label="Estado" igroup-size="sm" fgroup-class="col-md-3">
                            <x-adminlte-options :options="$arraySelUF" empty-option="Selecione..."/>
                        </x-adminlte-select>
                    </div>
                `;

                /* ********** Monta o Card de Filtro ********** */
                let cardFiltroHTML = getCardFiltros(fieldFiltroHTML);

                /* ********** Adiciona o Card de Filtro ********** */
                // #id = ID_TABELA_wrapper
                $('#tabela-provedores_wrapper .linha-cards-menu').append(cardFiltroHTML);
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
                $('#tabela-provedores_wrapper').each(function() {
                    var $wrapper = $(this);  // Armazena o contexto atual do wrapper

                    $wrapper.find('#filterCod').on('keyup', function() {
                        table.column(0).search(this.value).draw();
                    });

                    $wrapper.find('#filterPro').on('keyup', function() {
                        table.column(1).search(this.value).draw();
                    });

                    $wrapper.find('#filterEst').on('change', function() {
                        var selectedValue = $(this).val();
                        table.column(2).search(selectedValue).draw();
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
        setupActionsColuna(table, '#tabela-provedores_wrapper');

        /* ------------------------------ Final dos Eventos de Controle do Card de Colunas e Funcionalidades ------------------------------ */

        /* *****
        |----------------------------------------------------------------------------------------------------
        | Controle do Card de Filtro e Funcionalidades
        |----------------------------------------------------------------------------------------------------
        ***** */

        // Chama a função de configuração de filtros
        setupActionsFiltro(table, '#tabela-provedores_wrapper');

        /* ------------------------------ Final dos Eventos de Controle do Card de Filtro e Funcionalidades ------------------------------ */

    });

    /* ------------------------------ Final da Inicialização do Datatable ------------------------------ */
</script>

<!--
|--------------------------------------------------------------------------
| Eventos onChange da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() {

        //Evento de carregamento ajax dos dados dos setores
        $('#uf').change(function(){

            if( $(this).val() && $('#uf').val() != '' ) {
                
                var uf = $(this).val();

                var url = "{{ route('ajax.carregaCidAjax', [':uf']) }}";
                url = url.replace(':uf', uf);

                $.ajax({
                    url: url,
                    dataType: "JSON",
                    type: 'GET',
                    data: {
                        '_token': $('meta[name=csrf-token]').attr("content"),
                        '_method': 'GET',
                        "uf": uf
                    },
                    success: function (data)
                    {
                        if(data.cidade_ajax_existe == 'S'){

                            var options = '<';	

                            for (var i = 0; i < data.cidade_ajax.length; i++) {

                                options += '<option value="' + data.cidade_ajax[i].cidade + '">' + data.cidade_ajax[i].cidade + '</option>';
                            }	


                            $('#cidade').val('');
                            $('#cidades').html(options);

                        }
                    }
                });
            }
        });
    });
</script>

<script>
$(function () {
    $('#quickForm').validate({
        rules: {
            codigo: {
                required: true
            },
            cidade: {
                required: true,
                maxlength: 80
            },
            uf: {
                required: true
            },
        },
        messages: {
            cidade: {
                required: "Por Favor informe o Nome da Cidade Provedor",
                maxlength: "Infome no máximo 80 caracteres"
            },
            uf: {
                required: "Por Favor informe o Estado do Provedor"
            },
            codigo: {
                required: "Por Favor informe o Código"
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
