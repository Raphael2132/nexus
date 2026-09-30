@extends('adminlte::page')

@section('title', 'Painel Emissão de NF')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Faturamento de Notas</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.emissaoNF')}}">Filtro Emissão de NF</a>
            </li>
            <li class="breadcrumb-item active">
                <a href="{{ route('emissaoNF.consultaNF') }}">Consulta Emissão de NF</a>
            </li>
            <li class="breadcrumb-item active">Painel de Emissãode de NF</li>
        </ol>
    </div>
</div>
@stop


@section('content')
<div class="col-md-12">

    <!-- ********** Painel Principal da Emissão de NF ********** -->
    <x-adminlte-card title="Painel de Emissão de Notas Fiscais" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
        @php 
            $data = DB::table('cadastro_empresas')->where('empresa_codigo', $empresaREC)->get();

            $data_cli = DB::table('cadastro_clientes')->where('cliente_codigo', $clienteREC)->get();
        @endphp
        <div class="row">
            <div style="width:100%; margin: 0 10px 10px 10px; color:#fff">
                <table style="width:100%; font-style: normal; border-collapse: separate; border-spacing: 5px 5px;">
                    <tbody>
                        <tr style="text-align: center;">
                            <td style="background-color: #00abab; border-radius: 8px; border: 1px solid #008f8f;"><strong>Empresa</strong></td>
                            <td style="background-color: #00abab; border-radius: 8px; border: 1px solid #008f8f;"><strong>Cliente</strong></td>
                        </tr>
                        <tr style="border: 1px solid #008f8f; background-color: #fff; color:#008f8f; text-align: center;">
                            <td style="border: 1px solid #008f8f; border-radius: 8px; text-align: center;">{{$empresaREC.' - '.$data[0]->empresa_nome}}</td>
                            <td style="border: 1px solid #008f8f; border-radius: 8px; text-align: center;">{{$clienteREC.' - '.$data_cli[0]->cliente_nome}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Posiciona os blocos do lado esquerdo e direito na mesma linha -->
        <div class="row">

            <!-- ************************************************** Bloco do Lado Esquerdo do Painel Principal ************************************************** -->
            <div class="col-md-6">
                @php
                    // Monta os dados da tabela do bloco
                    $heads = [
                        ['label' => '', 'no-export' => true, 'width' => 10],
                        'Pedido / OS',
                        'Data',
                        'Origem',
                        'Tipo NF',
                        'Total NF',
                        'Entrada / À Vista',
                        ['label' => '', 'no-export' => true, 'width' => 10],
                    ];
                @endphp
                <x-adminlte-card title="Lista de Notas Disponíveis" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
                    <x-adminlte-datatable id="tabelaNotas" :heads="$heads" theme="light" striped hoverable>
                        @foreach($dadosCliente as $cliente)
                            @php 
                                $data = DB::table('cadastro_empresas')->where('empresa_codigo', $cliente->nfhdr_emp)->get();

                                $data_cli = DB::table('cadastro_clientes')->where('cliente_codigo', $cliente->nfhdr_cli)->get();

                                if($cliente->nfhdr_tor == 'S'){
                                    $origem = "Serviços";
                                }else{
                                    $origem = "Peças";
                                }

                                //Verifica se a nota para o recebimento já foi selecionada
                                $notaReceb = DB::table('financeiro_recebimento_notas')->where('recnf_cod_rec', $idRecebimento)->where('recnf_emp', $empresaREC)->where('recnf_num', $cliente->nfhdr_num)->count();
                            @endphp
                            <tr>
                                <td>
                                    <nobr class="d-flex justify-content-center">
                                        @if($notaReceb == 0)
                                            @if($estagio_app == 'SELECAO_NF')
                                            <a class="btn btn-nexus btn-sm" title="Selecionar NF" href="{{route('emissaoNF.inserirNotas',['empresa' => $empresaREC, 'numNF' => $cliente->nfhdr_num, 'cliente' => $clienteREC])}}">Selecionar</a>
                                            @endif
                                        @else
                                        <a class="text-muted" title="NF Selecionada" href="#">
                                            <i class="fa-solid fa-circle-check fa-lg text-success"></i>
                                        </a>
                                        @endif
                                    </nobr>
                                </td>
                                <td>{{$cliente->nfhdr_num_ped}}</td>
                                <td>{{Helper::formataData($cliente->nfhdr_dt_ped)}}</td>
                                <td>{{$origem}}</td>
                                <td>{{$cliente->tipo_nf}}</td>
                                <td>{{Helper::formataValorMonetario($cliente->nfhdr_vlr_tot_nf)}}</td>
                                <td>{{Helper::formataValorMonetario($cliente->nfhdr_vlr_ent)}}</td>
                                <td>
                                    <nobr class="d-flex justify-content-center">
                                        @if($notaReceb > 0 && $estagio_app == 'SELECAO_NF')
                                        <a class="btn btn-nexus btn-sm" title="Selecionar NF" href="{{route('emissaoNF.desmarcarNotas',['empresa' => $empresaREC, 'numNF' => $cliente->nfhdr_num, 'cliente' => $clienteREC])}}">Desmarcar</a>
                                        @endif
                                    </nobr>
                                </td>
                            </tr>
                        @endforeach
                    </x-adminlte-datatable>
                    
                    <x-slot name="footerSlot">
                        @if($estagio_app == 'SELECAO_NF')
                        <a class="btn btn-nexus mr-auto" title="Faturar Notas Selecionadas" href="{{route('recebimento.painelRecebimento',['empresa' => $empresaREC, 'cliente' => $clienteREC, 'idRecebimento' => $idRecebimento])}}">
                            <i class="fa-solid fa-file-invoice-dollar"></i> Faturar Notas
                        </a>
                        @else
                        <a class="btn btn-nexus mr-auto" title="Reabrir Seleção de NF" href="{{route('emissaoNF.painelReabreSelNF',['empresa' => $empresaREC, 'cliente' => $clienteREC])}}">
                            <i class="fa-solid fa-folder-open"></i> Reabrir Seleção
                        </a>
                        @endif
                    </x-slot>
                    
                </x-adminlte-card>
            </div>

            <!-- ************************************************** Bloco do Lado Direito do Painel Principal ************************************************** -->
            <div class="col-md-6">

                <!-- ***** Inclusão dos quadros do recebimento ***** -->
                @include('financeiro.recebimento.fin_pnl001_Recebimento')

                <!-- ***** Início de Botões da Geração da NF-e/NFS-e/Cupom - Bloco: GERACAO_NF ***** -->
                @if($estagio_app == 'GERACAO_NF')
                <x-adminlte-card title="Gerar Nota Fiscal" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
                    <div class="d-flex justify-content-between w-100">
                        <!-- Espaço reservado para centralização -->
                        <div class="flex-grow-1 d-flex justify-content-center">
                            <a class="btn btn-nexus mr-2" title="Gerar DANFE / NFS-e" href="{{ route('emissaoNF.emissaoNF',['origem' => 'EMISSAO', 'empresa' => $cliente->nfhdr_emp]) }}">DANFE / NFS-e</a>
                            <a class="btn btn-nexus mr-2" title="Gerar NFC-e" href="">NFC-e</a>
                            <a class="btn btn-nexus mr-2" title="Gerar Cupom de Venda" href="">Cupom de Venda</a>
                        </div>
                        @if($origemOpc == 'VISTA')
                        <!-- Botão Voltar alinhado à direita -->
                        <div>
                            <a class="btn btn-nexus" title="Voltar" href="{{route('recebimento.painelRecebimento',['empresa' => $empresaREC, 'cliente' => $clienteREC, 'idRecebimento' => $idRecebimento])}}">Voltar</a>
                        </div>
                        @endif
                    </div>
                </x-adminlte-card>
                @endif
                <!-- ***** Final de Botões da Geração da NF-e/NFS-e/Cupom - Bloco: GERACAO_NF ***** -->
            </div>

        </div>
        <x-slot name="footerSlot">
            <div style="float: right;">
                <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('emissaoNF.consultaNF') }}'" label="Voltar" theme="" icon=""/>
            </div>
        </x-slot>
    </x-adminlte-card>

</div>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.Sweetalert2', true)
@section('plugins.jqueryValidation', true)
@section('plugins.DateRangePicker', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('css')
<!-- Importa o CSS referente a app do painel de recebimento - fin_pnl001_Recebimento.blade.php -->
<link rel="stylesheet" href="{{ asset('vendor/nexus/financeiro/recebimento/css/painel.recebimento.css') }}">
@stop

@section('js')
<script>
    var estagioAPP = {!! json_encode($estagio_app) !!};

    if(estagioAPP == 'REC_C_CREDITO' ){  
        var subEstagioRec = {!! json_encode($subEstagioRec ?? '') !!};
        var empresaREC = {!! json_encode($empresaREC ?? '') !!};
        var getParcelasUrl = "{{ route('ajax.getParcelasCartaoCredito', [':adm',':emp',':val']) }}";
    }

    if(estagioAPP == 'REC_CONTA_CORRENTE' ){
        var saldoInicial = {!! json_encode($dadosCCT->conta_saldo ?? '0') !!};
    }
</script>
<!-- Importa o JS referente a app do painel de recebimento - fin_pnl001_Recebimento.blade.php -->
<script src="{{ asset('vendor/nexus/financeiro/recebimento/js/painel.recebimento.js') }}"></script>

<script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script> 

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
        
        var estagioAPP = {!! json_encode($estagio_app) !!};

        if(estagioAPP == 'SELECAO_NF'){  

            /* ********** Inicializa o Datatable ********** */
            var tableNotas = $('#tabelaNotas').DataTable({
                pageLength: 10,
                searching: false,
                lengthChange: false,
                language: dataTableLangPtBR,
                order: [
                    [1, 'asc']
                ],
                pagingType: 'full_numbers',
                processing: true,
                columns: [
                    { orderable: false },
                    { orderable: false },
                    { orderable: false },
                    { orderable: false },
                    { orderable: false },
                    { orderable: false },
                    { orderable: false },
                    { orderable: false },
                ],
            });

        }else{

            /* ********** Inicializa o Datatable ********** */
            var tableNotas = $('#tabelaNotas').DataTable({
                pageLength: 10,
                searching: false,
                lengthChange: false,
                language: dataTableLangPtBR,
                order: [
                    [1, 'asc']
                ],
                pagingType: 'full_numbers',
                processing: true,
                columns: [
                    { orderable: false },
                    { orderable: false },
                    { orderable: false },
                    { orderable: false },
                    { orderable: false },
                    { orderable: false },
                    { orderable: false },
                    { orderable: false, visible: false },
                ],
            });
        }
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

<!--
|--------------------------------------------------------------------------
| Eventos onClick da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() {

    });
</script>

<!--
|--------------------------------------------------------------------------
| Eventos onChange da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() {

    });
</script>

<!--
|--------------------------------------------------------------------------
| Eventos Validate da app
|--------------------------------------------------------------------------
-->
<script>

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
            text: '{!! session("error") !!}',
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

