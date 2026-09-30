@extends('adminlte::page')

@section('title', 'Outros Recebimentos')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Recebimento</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.filtroRecOUT')}}">Filtro de Outros Recebimentos</a>
            </li>
            <li class="breadcrumb-item active">Outros Recebimentos</li>
        </ol>
    </div>
</div>
@stop


@section('content')
<div class="col-md-12">

    <!-- ********** Painel Principal da Emissão de NF ********** -->
    <x-adminlte-card title="Painel de Outros Recebimentos" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
        @php 
            $dadosUsuFin = DB::table('financeiro_tab_usuarios')->where('tabusu_usuario', Auth::user()->usuario_codigo)->where('tabusu_empresa', Auth::user()->usuario_empresa)->first();
            $dadosRaz = DB::table('financeiro_razoes')->where('razao_codigo', $dadosUsuFin->tabusu_razao)->where('razao_empresa', $dadosUsuFin->tabusu_empresa)->where('razao_tipo', $dadosUsuFin->tabusu_tipo_razao)->first();
            $empresaFormat = HelperFormatSelect::formataEmpresaCodigoNome($empresaREC);
        @endphp
        <div class="row">
            <div style="width:100%; margin: 0 10px 10px 10px; color:#fff">
                <table style="width:100%; font-style: normal; border-collapse: separate; border-spacing: 5px 5px;">
                    <tbody>
                        <tr style="text-align: center;">
                            <td style="background-color: #00abab; border-radius: 8px; border: 1px solid #008f8f;"><strong>Empresa</strong></td>
                            <td style="background-color: #00abab; border-radius: 8px; border: 1px solid #008f8f;"><strong>Usuário</strong></td>
                            <td style="background-color: #00abab; border-radius: 8px; border: 1px solid #008f8f;"><strong>Caixa Operacional</strong></td>
                        </tr>
                        <tr style="border: 1px solid #008f8f; background-color: #fff; color:#008f8f; text-align: center;">
                            <td style="border: 1px solid #008f8f; border-radius: 8px; text-align: center;">{{ $empresaFormat }}</td>
                            <td style="border: 1px solid #008f8f; border-radius: 8px; text-align: center;">{{ Auth::user()->usuario_codigo.' - '.Auth::user()->name }}</td>
                            <td style="border: 1px solid #008f8f; border-radius: 8px; text-align: center;">{{ $dadosUsuFin->tabusu_razao.' - '.$dadosRaz->razao_nome }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Posiciona os blocos do lado esquerdo e direito na mesma linha -->
        <div class="row">

            <!-- ************************************************** Bloco do Lado Esquerdo do Painel Principal ************************************************** -->
            <div class="col-md-6">

                <!-- ***** Início dos dados de Recebimento - Bloco: PAINEL_PRINCIPAL ***** -->
                @if($estagio_app == 'PAINEL_PRINCIPAL')
                <form method="post" action="{{ route('recebimentoOUT.atualizaRec',['empresa' => $empresaREC, 'subEstagio' => $subEstagio]) }}" id="form-atu-rec" novalidate="novalidate">
                    @csrf 
                    @method('post')
                    <x-adminlte-card title="Informações do Recebimento" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
                        @php
                            $array_emp = HelperArraySelect::arrayEmpresas(1,1);
                            $array_tipo_rec = HelperArraySelect::arrayTipoCreditoFin(1,1,'T3');
                            $htmlCli = HelperDataList::geraDatalistGeralClientes('clientes');

                            //Echo adiciona o html ao campo dos clientes
                            echo $htmlCli;

                            if($subEstagio == 'EDIT'){
                                $dadosCli = DB::table('cadastro_clientes')->where('cliente_codigo', $dadosOUT->recout_cli)->first();
                                $clienteSel = $dadosOUT->recout_cli.' - '.$dadosCli->cliente_nome;
                                $numDocSel = $dadosOUT->recout_num_com;
                                $cmpSel = $dadosOUT->recout_cmp;
                                $dataComSel = $dadosOUT->recout_dtc;
                            }else{
                                $clienteSel = '';
                                $numDocSel = '';
                                $cmpSel = '';
                                $dataComSel = '';
                            }
                        @endphp    
                        <div class="row"> 
                            <x-adminlte-select name="tipCred" fgroup-class="col-md-6" igroup-size="sm" disabled>
                                <x-slot name="label">
                                    Tipo do Crédito <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="$array_tipo_rec" empty-option="Selecione..." selected="{{ $dadosOUT->recout_tip_cre }}"/>
                            </x-adminlte-select>
                            
                            <x-adminlte-select name="empresa" fgroup-class="col-md-6" igroup-size="sm" disabled>
                                <x-slot name="label">
                                    Empresa <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="$array_emp" empty-option="Selecione..." selected="{{ $dadosOUT->recout_emp }}"/>
                            </x-adminlte-select>
                        </div>
                        <div class="row">
                            <x-adminlte-input name="cliente" type="search" list="clientes" placeholder="Código e Nome do Cliente" autocomplete="off" value="{{ $clienteSel }}" igroup-size="sm" fgroup-class="col-md-6">
                                <x-slot name="label">
                                    Cliente <span style="color:red;">*</span>
                                </x-slot>
                            </x-adminlte-input>
                            
                            <x-adminlte-input name="numDocOUT" type="text" value="{{ $numDocSel }}" placeholder="Número do Comprovante" fgroup-class="col-md-6" igroup-size="sm">
                                <x-slot name="label">
                                    Número do Comprovante <span style="color:red;">*</span>
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                        <div class="row">
                            <x-adminlte-input name="compOUT" type="text" label="Complemento" value="{{ $cmpSel }}" placeholder="Complemento" fgroup-class="col-md-6" igroup-size="sm"></x-adminlte-input>
                        
                            @php
                                $config = Helper::dtRangeDataPtBR();

                                if(!empty($dataComSel)){
                                    $dataComp = date('d/m/Y', strtotime($dataComSel));
                                }else{
                                    $dataComp = '';
                                }
                            @endphp
                            <x-adminlte-date-range name="dataComp" :config="$config" placeholder="Formato dia/mês/ano" igroup-size="sm" fgroup-class="col-md-6">
                                <x-slot name="label">
                                    Data do Comprovante <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="appendSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="far fa-lg fa-calendar-alt"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-date-range>
                            @push('js')<script>$(() => $("#dataComp").val('{{ $dataComp }}'))</script>@endpush
                        </div>
                        <div class="row">
                            <x-adminlte-input name="valorRecebimento" type="text" value="{{ Helper::FormataValorMonetario($dadosOUT->recout_vlr) }}" placeholder="0,00" fgroup-class="col-md-6" igroup-size="sm" disabled>
                                <x-slot name="label">
                                    Valor do Recebimento <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fa-solid fa-brazilian-real-sign"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                            
                            @php
                                $config = Helper::dtRangeDataPtBR();
                                $dataPagamento = date('d/m/Y', strtotime($dadosOUT->recout_dtp));
                            @endphp
                            <x-adminlte-date-range name="dataPagamento" :config="$config" placeholder="Formato dia/mês/ano" fgroup-class="col-md-6" igroup-size="sm" disabled>
                                <x-slot name="label">
                                    Data do Pagamento <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="appendSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="far fa-lg fa-calendar-alt"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-date-range>
                            @push('js')<script>$(() => $("#dataPagamento").val('{{ $dataPagamento }}'))</script>@endpush
                        </div>
                        <x-slot name="footerSlot">
                            <div style="float: right;">
                                <x-adminlte-button class="btn-nexus" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                                @if($subEstagio == 'EDIT')
                                <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('recebimentoOUT.abreConfirmacao', ['empresa' => $empresaREC]) }}'" label="Voltar" theme="" icon=""/>
                                @endif
                            </div>
                        </x-slot>
                    </x-adminlte-card>
                </form>
                @endif
                <!-- ***** Final dos dados de Recebimento - Bloco: PAINEL_PRINCIPAL ***** -->

                <!-- ***** Início dos dados de Recebimento - Bloco: PAINEL_CONFIRMACAO ***** -->
                @if($estagio_app == 'PAINEL_CONFIRMACAO' || $estagio_app == 'PAINEL_RECEBIMENTO' || $estagio_app == 'REC_DINHEIRO' || $estagio_app == 'REC_PIX' || 
                    $estagio_app == 'REC_CHEQUE' || $estagio_app == 'REC_C_CREDITO' || $estagio_app == 'REC_C_DEBITO' || $estagio_app == 'REC_FIL_C_CORRENTE' || 
                    $estagio_app == 'REC_SEL_C_CORRENTE' || $estagio_app == 'REC_CONTA_CORRENTE')
                <x-adminlte-card title="Informações do Recebimento" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
                    @php
                        $dataTipCre = DB::table('financeiro_tab_tipo_creditos')->where('tabtcr_tab', 'T3')->where('tabtcr_cod', $dadosOUT->recout_tip_cre)->first();
                        $dadosEmp = HelperDataSelect::buscaDadosEmpresa($dadosOUT->recout_emp);
                        $dadosCli = DB::table('cadastro_clientes')->where('cliente_codigo', $dadosOUT->recout_cli)->first();
                    @endphp    
                    <div class="row">
                        <p class="text-sm col-md-6">Tipo do Crédito
                            <b class="d-block">{{ $dadosOUT->recout_tip_cre.' - '.$dataTipCre->tabtcr_des }}</b>
                        </p>
                        <p class="text-sm col-md-6">Empresa
                            <b class="d-block">{{ $dadosOUT->recout_emp.' - '.$dadosEmp->empresa_nome }}</b>
                        </p>
                    </div>
                    <div class="row">
                        <p class="text-sm col-md-6">Cliente
                            <b class="d-block">{{ $dadosOUT->recout_cli.' - '.$dadosCli->cliente_nome }}</b>
                        </p>
                        <p class="text-sm col-md-6">Número do Comprovante 
                            <b class="d-block">{{ $dadosOUT->recout_num_com }}</b>
                        </p>
                    </div>
                    <div class="row">
                        <p class="text-sm col-md-6">Complemento
                            <b class="d-block">{{ $dadosOUT->recout_cmp }}</b>
                        </p>
                        <p class="text-sm col-md-6">Data do Comprovante
                            <b class="d-block">{{ Helper::FormataData($dadosOUT->recout_dtc) }}</b>
                        </p>
                    </div>
                    <div class="row">
                        <p class="text-sm col-md-6">Valor do Recebimento
                            <b class="d-block">{{ Helper::FormataValorMonetario($dadosOUT->recout_vlr) }}</b>
                        </p>
                        <p class="text-sm col-md-6">Data do Pagamento
                            <b class="d-block">{{ Helper::FormataData($dadosOUT->recout_dtp) }}</b>
                        </p>
                    </div>
                    <x-slot name="footerSlot">
                        <div style="float: right;">
                            <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('recebimentoOUT.abreFormulario', ['empresa' => $empresaREC, 'subEstagio' => 'EDIT']) }}'" label="Editar Recebimento" theme="" icon=""/>
                            @if($estagio_app == 'PAINEL_CONFIRMACAO')
                            <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('recebimento.painelRecebimento', ['empresa' => $empresaREC, 'cliente' => $clienteREC, 'idRecebimento' => $idRecebimento, 'tipoRecebimento' => 'OUT']) }}'" label="Receber" theme="" icon="fa-solid fa-sack-dollar"/>
                            @endif
                        </div>
                    </x-slot>
                </x-adminlte-card>
                @endif
                <!-- ***** Final dos dados de Recebimento - Bloco: PAINEL_CONFIRMACAO ***** -->
            </div>

            <!-- ************************************************** Bloco do Lado Direito do Painel Principal ************************************************** -->
            <div class="col-md-6">
                @include('financeiro.recebimento.fin_pnl001_Recebimento')
            </div>

        </div>
        <x-slot name="footerSlot">
            <div style="float: right;">
                <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.filtroRecOUT') }}'" label="Voltar" theme="" icon=""/>
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
| Eventos Iniciais da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() { 

        var estagioAPP = {!! json_encode($estagio_app) !!};

        /* **************************************** Eventos Iniciais do bloco  - PAINEL_SEL_DUP **************************************** */
        if( estagioAPP == 'PAINEL_SEL_DUP' ){
        }
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

        var estagioAPP = {!! json_encode($estagio_app) !!};

        /* **************************************** Eventos Iniciais do bloco  - PAINEL_SEL_DUP **************************************** */
        if( estagioAPP == 'PAINEL_SEL_DUP' ){
            
        }
        
    });
</script>

<!--
|--------------------------------------------------------------------------
| Eventos Validate da app
|--------------------------------------------------------------------------
-->
<script>
$(function () {

    //Inserção da Conta Corrente no Recebimento
    $('#form-atu-rec').validate({
        rules: {
            cliente: {
                required: true
            },
            numDocOUT: {
                required: true,
                maxlength: 20
            },
            dataComp: {
                required: true
            },
            compOUT: {
                maxlength: 40
            },
        },
        messages: {
            cliente: {
                required:  "Por Favor informe o Cliente"
            },
            numDocOUT: {
                required:  "Por Favor informe o Número do Comprovante",
                maxlength: "Limite máximo do Número do Comprovante é de 20 caracteres"
            },
            dataComp: {
                required:  "Por Favor informe a Data do Comprovante"
            },
            compOUT: {
                maxlength: "Limite máximo do Complemento é de 40 caracteres"
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
        },
        // Ao submeter o formulário, reativar os campos desativados
        submitHandler: function (form) {

            const dataCompStr = $('#dataComp').val();
            const dataPagamentoStr = $('#dataPagamento').val();

            // Verifica se ambos estão preenchidos
            if (dataCompStr && dataPagamentoStr) {
                // Converte de d/m/Y para Date
                const [diaC, mesC, anoC] = dataCompStr.split('/');
                const [diaP, mesP, anoP] = dataPagamentoStr.split('/');

                const dataComp = new Date(anoC, mesC - 1, diaC);
                const dataPagamento = new Date(anoP, mesP - 1, diaP);

                if (dataComp > dataPagamento) {
                    Swal.fire({
                        confirmButtonColor: "#007bff",
                        title: "Aviso!",
                        text: "A Data do Comprovante não pode ser maior que a Data do Pagamento!",
                        icon: "info"
                    });
                    return;
                }
            }

            // Ativar campos desativados antes de enviar
            $(':disabled').each(function () {
                $(this).removeAttr('disabled');
            });

            form.submit();
        }
    });
    
});
</script>

<!--
|--------------------------------------------------------------------------
| Eventos de Menssagens da APP
|--------------------------------------------------------------------------
-->
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

