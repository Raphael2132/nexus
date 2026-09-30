<!-- ***** Início do painel principal do Pagamento - Bloco: PAINEL_PAGAMENTO ***** -->
@if($estagio_app == 'PAINEL_PAGAMENTO')
<x-adminlte-card title="Valores do Pagamento" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
    <div class="row">  
        <div class="col-md-12">       
            <table class="table tabela-valores tabela-custom-nexus">
                <tbody>
                    <tr>
                        <th>Valores Pagos</th>
                    </tr>
                </tbody>
            </table> 
        </div>
    </div>
    <div class="row">     
        @php

            $heads1 = [
                'Seq.',
                ['label' => '', 'no-export' => true, 'width' => 5],
                'Tipo do Pagamento',
                'Descrição',
                'Valor',
            ];
        
        @endphp
        <x-adminlte-datatable id="tabela-valores" :heads="$heads1" theme="light" striped hoverable beautify>
            @foreach($dadosPagamento as $valores)
                @php 
                    $tipo = Helper::formataTipoValPagamento($valores->pagval_tipo);
                    $valor = Helper::formataValorMonetario($valores->pagval_valor);

                    if($valores->pagval_tipo == 'DIN'){

                        $desc = 'Pagamento Único em Dinheiro';

                    }elseif($valores->pagval_tipo == 'PIX'){

                        $desc = 'Banco: '.$valores->pagval_pix_bco.' / Comprovante: '.$valores->pagval_pix_doc;

                    }elseif($valores->pagval_tipo == 'CHQ'){

                        if(!empty($valores->pagval_ch_age_dv)){
                            $age = $valores->pagval_ch_age.'-'.$valores->pagval_ch_age_dv;
                        }else{
                            $age = $valores->pagval_ch_age;
                        }

                        if(!empty($valores->pagval_ch_ccr_dv)){
                            $ccr = $valores->pagval_ch_ccr.'-'.$valores->pagval_ch_ccr_dv;
                        }else{
                            $ccr = $valores->pagval_ch_ccr;
                        }

                        $desc = 'Banco: '.$valores->pagval_ch_bco.' / Age.: '.$age.' / C.C.: '.$ccr.' / Cheque: '.$valores->pagval_ch_num;

                    }elseif($valores->pagval_tipo == 'CCO'){

                        $desc = 'Adm.: '.$valores->pagval_crt_adm.' / Cartão: '.$valores->pagval_crt_com.' / Qtd. Parcelas: '.$valores->pagval_crt_prc;

                    }elseif($valores->pagval_tipo == 'CCT'){

                        $desc = 'Tipo: '.$valores->pagval_cct_tcc.' / Resp.: '.$valores->pagval_cct_res.' / Conta: '.$valores->pagval_cct_ncc;

                    }else{
                        $desc = '';
                    }
                @endphp
                <tr>
                    <td>{{$valores->pagval_seq}}</td>
                    <td>
                        <nobr class="d-flex justify-content-center">
                            <form method="post" action="{{route('valorPagamentoLote.destroy', ['appOrigem' => $appOrigem, 'valorPagamento' => $valores->pagval_id])}}">
                                @csrf 
                                @method('delete')
                                <button class="btn btn-xs btn-outline-danger mx-1" title="Excluir Registro" value="Delete" type="submit" >
                                    <i class="fa fa-lg fa-fw fa-trash-can"></i>
                                </button>
                            </form>

                            <form method="get" action="{{route('valorPagamentoLote.edit', ['appOrigem' => $appOrigem, 'valorPagamento' => $valores->pagval_seq])}}">
                                @csrf 

                                <!-- Informa os valores extra para a Edição -->
                                <input id="tipoPag" type="hidden" value="{{ $valores->pagval_tipo }}" name="tipoPag">

                                <button class="btn btn-xs btn-outline-primary mx-1" title="Editar Registro" value="Edit" type="submit">
                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                </button>
                            </form>
                        </nobr>
                    </td>
                    <td>{{$tipo}}</td>
                    <td>{{$desc}}</td>
                    <td>{{$valor}}</td>  
                </tr>
            @endforeach
        </x-adminlte-datatable>
    </div>
    <form method="post" action="{{ route('pagamentoPCC.finalizaPagamentoLote', ['appOrigem' => $appOrigem]) }}" id="formularioFinal" novalidate="novalidate">
        @csrf 
        @method('post')
        <div class="row">
            <div class="col-md-6">       
                <table class="table tabela-resumo tabela-custom-nexus" style="padding: .25rem;">
                    <tbody>
                        <tr>
                            <th colspan="2">Resumo do Pagamento</th>
                        </tr>
                        <tr>
                            <td style="width: 40%;"><strong>Valor do Pagamento:</strong></td>
                            <td style="text-align: left">{{ Helper::formataValorMonetario($valorPagamento) }}</td>
                        </tr>
                        <tr>
                            <td style="width: 40%;"><strong>Valor Pago:</strong></td>
                            <td style="text-align: left">{{ Helper::formataValorMonetario($valorPago) }}</td>
                        </tr>
                        <tr>
                            <td style="width: 40%;"><strong>Saldo Restante:</strong></td>
                            <td style="text-align: left">{{ Helper::formataValorMonetario($saldoRestante) }}</td>
                        </tr>
                        <tr>
                            <td colspan=2 style="text-align: left">
                                <x-adminlte-textarea name="observacao" label="Observações" rows=3 igroup-size="sm" label-class="text-dark" placeholder="Escreva sua menssagem..." fgroup-class="col-md-12" >
                                    {{$observacoes}}
                                    <x-slot name="prependSlot">
                                        <div class="input-group-text x-slot-nexus">
                                            <i class="fas fa-lg fa-file-alt text-white"></i>
                                        </div>
                                    </x-slot>
                                </x-adminlte-textarea>
                            </td>
                        </tr>

                        <!-- Campos escondidos para o request -->
                        <input id="valorPagamento" type="hidden" value="{{$valorPagamento}}" name="valorPagamento">
                        <input id="valorPago" type="hidden" value="{{$valorPago}}" name="valorPago">

                    </tbody>
                </table> 
            </div>
            <div class="col-md-6">       
                <table class="table tabela-forma-pgt tabela-custom-nexus" style="width: 100%; table-layout: fixed;">
                    <tbody>
                        @php 
                            $parCC = DB::table('parametros_fin_contas_correntes')
                            ->where('parcct_emp', $empresaLote)
                            ->where('parcct_tcc', 'CO')
                            ->first();
                        @endphp
                        @if($parCC && $parCC->parcct_con === 'N')
                        <tr>
                            <th colspan="4">Formas de Pagamento</th>
                        </tr>
                        @else
                        <tr>
                            <th colspan="3">Formas de Pagamento</th>
                        </tr>
                        @endif
                        <tr>
                            <td>
                                <a href="{{ route('valorPagamentoLote.create', ['appOrigem' => $appOrigem, 'tipoValor' => 'DIN']) }}" title="Pagamento em Dinheiro">
                                    <div class="icon-text">
                                        <i class="fas fa-money-bill-wave fa-2x" style="color: #008040;"></i>
                                        <span>Dinheiro</span>
                                    </div>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('valorPagamentoLote.create', ['appOrigem' => $appOrigem, 'tipoValor' => 'PIX']) }}" title="PIX / Crédito Bancário">
                                    <div class="icon-text">
                                        <i class="fa-brands fa-pix fa-2x" style="color: #00E1D6;"></i>
                                        <span>PIX</span>
                                    </div>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('valorPagamentoLote.create', ['appOrigem' => $appOrigem, 'tipoValor' => 'CHQ']) }}" title="Cheque">
                                    <div class="icon-text">
                                        <i class="fas fa-money-check fa-2x" style="color: #800040;"></i>
                                        <span>Cheque</span>
                                    </div>
                                </a>
                            </td>
                            @if($parCC && $parCC->parcct_con === 'N')
                            <td>
                                <a href="{{ route('valorPagamentoLote.show', ['appOrigem' => $appOrigem, 'valorPagamento' => 'CCT', 'etapa' => 'FILTRO']) }}" title="Conta Corrente">
                                    <div class="icon-text">
                                        <i class="fas fa-building-columns fa-2x" style="color: #808080;"></i>
                                        <span style="white-space: nowrap;">C. Corrente</span>
                                    </div>
                                </a>
                            </td>
                            @endif
                        </tr>
                        @if($parCC && $parCC->parcct_con === 'S')
                        <tr>                    
                            <td>
                                <a href="{{ route('valorPagamentoLote.create', ['appOrigem' => $appOrigem, 'tipoValor' => 'CCO']) }}" title="Cartão Corporativo" id="linkCartaoCorporativo">
                                    <div class="icon-text">
                                        <i class="fas fa-credit-card fa-2x" style="color: #004080;"></i>
                                        <span style="white-space: nowrap;">Cartão Corporativo</span>
                                    </div>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('valorPagamentoLote.show', [ 'appOrigem' => $appOrigem, 'valorPagamento' => 'CCT', 'etapa' => 'FILTRO']) }}" title="Conta Corrente">
                                    <div class="icon-text">
                                        <i class="fas fa-building-columns fa-2x" style="color: #808080;"></i>
                                        <span style="white-space: nowrap;">C. Corrente</span>
                                    </div>
                                </a>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </form>
    <x-slot name="footerSlot">
        <div style="text-align: center;">
            <x-adminlte-button class="btn-nexus" label="Finalizar Pagamento" theme="" icon="fa-solid fa-check" onclick="document.getElementById('formularioFinal').submit()"/>
        </div>
    </x-slot>
</x-adminlte-card>
@endif
<!-- ***** Final do painel principal do Pagamento - Bloco: PAINEL_PAGAMENTO ***** -->

<!-- ***** Início dos dados de Pagamento em Dinheiro - Bloco: PAG_DINHEIRO ***** -->
@if($estagio_app == 'PAG_DINHEIRO')
@if($subEstagioPag == "NEW")
<form method="post" action="{{ route('valorPagamentoLote.store',['appOrigem' => $appOrigem]) }}" id="form-pag-din" novalidate="novalidate">
    @csrf 
    @method('post')
@else
<form method="post" action="{{ route('valorPagamentoLote.update', ['appOrigem' => $appOrigem, 'valorPagamento' => $dadosValPag]) }}" id="form-pag-din" novalidate="novalidate">
    @csrf 
    @method('put')
@endif
    <x-adminlte-card title="Pagamento em Dinheiro" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
        <div class="text-muted">
            <div class="row">
                <p class="text-sm col-md-3">Data do Pagamento
                    <b class="d-block">{{ date('d/m/Y') }}</b>
                </p>
                <p class="text-sm col-md-3">Qtd. de Contas
                    <b class="d-block">{{ $totalCC }}</b>
                </p>
                <p class="text-sm col-md-3">Valor Total
                    <b class="d-block">{{ Helper::formataValorMonetario($valorTotalPag) }}</b>
                </p>
                <p class="text-sm col-md-3">Saldo Restante
                    <b class="d-block">{{ Helper::formataValorMonetario($saldoRestante) }}</b>
                </p>
            </div>
        </div>
        <h6 class="h6-nexus-b">Dados do Pagamento</h6>
        @php 
            if($subEstagioPag == "NEW"){
                $valorPag = '';
                $observacao = '';
            }else{
                $valorPag = $dadosValPag->pagval_valor;
                $observacao = $dadosValPag->pagval_obs;
            }
        @endphp
        <div class="row">
            <x-adminlte-input name="valDin" type="text" value="{{ $valorPag }}" placeholder="0,00" fgroup-class="col-md-12" igroup-size="sm">
                <x-slot name="label">
                    Valor em Dinheiro <span style="color:red;">*</span>
                </x-slot>
                <x-slot name="prependSlot">
                    <div class="input-group-text x-slot-nexus">
                        <i class="fa-solid fa-brazilian-real-sign"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>
        </div>
        <div class="row">
            <x-adminlte-textarea name="observacao" label="Observação" rows=3 label-class="text-dark" igroup-size="sm" placeholder="Informe a Observação..." fgroup-class="col-md-12">
                {{ $observacao }}
                <x-slot name="prependSlot">
                    <div class="input-group-text x-slot-nexus">
                        <i class="fas fa-lg fa-file-alt text-white"></i>
                    </div>
                </x-slot>
            </x-adminlte-textarea>
        </div>

        <!-- Campos escondidos para o request -->
        <input id="tipoValor" type="hidden" value="DIN" name="tipoValor">

        <x-slot name="footerSlot">
            <div style="text-align: center;">
                <x-adminlte-button class="btn-nexus" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                <a class="btn btn-nexus" title="Voltar" href="{{route('pagamentoPCC.pagarLote',['appOrigem' => $appOrigem])}}">Voltar</a>
            </div>
        </x-slot>
    </x-adminlte-card>
</form>
@endif
<!-- ***** Final dos dados de Pagamento em Dinheiro - Bloco: PAG_DINHEIRO ***** -->

<!-- ***** Início dos dados de Pagamento em PIX - Bloco: PAG_PIX ***** -->
@if($estagio_app == 'PAG_PIX')
@if($subEstagioPag == "NEW")
<form method="post" action="{{ route('valorPagamentoLote.store',['appOrigem' => $appOrigem]) }}" id="form-pag-pix" novalidate="novalidate">
    @csrf 
    @method('post')
@else
<form method="post" action="{{ route('valorPagamentoLote.update', ['appOrigem' => $appOrigem, 'valorPagamento' => $dadosValPag]) }}" id="form-pag-pix" novalidate="novalidate">
    @csrf 
    @method('put')
@endif
    <x-adminlte-card title="Pagamento em PIX" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
        <div class="text-muted">
            <div class="row">
                <p class="text-sm col-md-3">Data do Pagamento
                    <b class="d-block">{{ date('d/m/Y') }}</b>
                </p>
                <p class="text-sm col-md-3">Qtd. de Contas
                    <b class="d-block">{{ $totalCC }}</b>
                </p>
                <p class="text-sm col-md-3">Valor Total
                    <b class="d-block">{{ Helper::formataValorMonetario($valorTotalPag) }}</b>
                </p>
                <p class="text-sm col-md-3">Saldo Restante
                    <b class="d-block">{{ Helper::formataValorMonetario($saldoRestante) }}</b>
                </p>
            </div>
        </div>
        <h6 class="h6-nexus-b">Dados do Pagamento</h6>
        @php 
            if($subEstagioPag == "NEW"){
                $valorPag = '';
                $complemento = '';
                $codBcoPIX = null;
                $numDocPIX = '';
            }else{
                $valorPag = $dadosValPag->pagval_valor;
                $complemento = $dadosValPag->pagval_comp;
                $codBcoPIX = $dadosValPag->pagval_pix_bco;
                $numDocPIX = $dadosValPag->pagval_pix_doc;
            }
            $array_opt_bco = HelperArraySelect::arrayRazoesPorTipo(1, 1, $empresaLote, 'BA');
        @endphp
        <div class="row">
            <x-adminlte-select name="codBcoPIX" fgroup-class="col-md-12" igroup-size="sm">
                <x-slot name="label">
                    Banco <span style="color:red;">*</span>
                </x-slot>
                <x-adminlte-options :options="$array_opt_bco" empty-option="Selecione..." selected="{{$codBcoPIX}}"/>
            </x-adminlte-select>
        </div>
        <div class="row">
            <x-adminlte-input name="numDocPIX" type="text" value="{{ $numDocPIX }}" placeholder="Número do Comprovante" fgroup-class="col-md-12" igroup-size="sm">
                <x-slot name="label">
                    Número do Comprovante <span style="color:red;">*</span>
                </x-slot>
            </x-adminlte-input>
        </div>
        <div class="row">
            <x-adminlte-input name="valPIX" type="text" value="{{ $valorPag }}" placeholder="0,00" fgroup-class="col-md-4" igroup-size="sm">
                <x-slot name="label">
                    Valor do PIX <span style="color:red;">*</span>
                </x-slot>
                <x-slot name="prependSlot">
                    <div class="input-group-text x-slot-nexus">
                        <i class="fa-solid fa-brazilian-real-sign"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>
            
            <x-adminlte-input name="complemento" type="text" label="Complemento" value="{{ $complemento }}" placeholder="Informe o complemento" fgroup-class="col-md-8" igroup-size="sm"></x-adminlte-input>
        </div>

        <!-- Campos escondidos para o request -->
        <input id="tipoValor" type="hidden" value="PIX" name="tipoValor">

        <x-slot name="footerSlot">
            <div style="text-align: center;">
                <x-adminlte-button class="btn-nexus" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                <a class="btn btn-nexus" title="Voltar" href="{{route('pagamentoPCC.pagarLote',['appOrigem' => $appOrigem])}}">Voltar</a>
            </div>
        </x-slot>
    </x-adminlte-card>
</form>
@endif
<!-- ***** Final dos dados de Pagamento em PIX - Bloco: PAG_PIX ***** -->

<!-- ***** Início dos dados de Pagamento em Cheque - Bloco: PAG_CHEQUE ***** -->
@if($estagio_app == 'PAG_CHEQUE')
@if($subEstagioPag == "NEW")
<form method="post" action="{{ route('valorPagamentoLote.store',['appOrigem' => $appOrigem]) }}" id="form-pag-cheque" novalidate="novalidate">
    @csrf 
    @method('post')
@else
<form method="post" action="{{ route('valorPagamentoLote.update', ['appOrigem' => $appOrigem, 'valorPagamento' => $dadosValPag]) }}" id="form-pag-cheque" novalidate="novalidate">
    @csrf 
    @method('put')
@endif
    <x-adminlte-card title="Pagamento em Cheque" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
        <div class="text-muted">
            <div class="row">
                <p class="text-sm col-md-3">Data do Pagamento
                    <b class="d-block">{{ date('d/m/Y') }}</b>
                </p>
                <p class="text-sm col-md-3">Qtd. de Contas
                    <b class="d-block">{{ $totalCC }}</b>
                </p>
                <p class="text-sm col-md-3">Valor Total
                    <b class="d-block">{{ Helper::formataValorMonetario($valorTotalPag) }}</b>
                </p>
                <p class="text-sm col-md-3">Saldo Restante
                    <b class="d-block">{{ Helper::formataValorMonetario($saldoRestante) }}</b>
                </p>
            </div>
        </div>
        <h6 class="h6-nexus-b">Dados do Pagamento</h6>
        @php 
            if($subEstagioPag == "NEW"){
                $valorPag = '';
                $observacao = '';
                $codBcoCHQ = null;
                $numCHQ = '';
                $cnc = '';
                $age = '';
                $conta = '';
                $emitente = '';
            }else{
                $valorPag = $dadosValPag->pagval_valor;
                $observacao = $dadosValPag->pagval_obs;
                $codBcoCHQ = $dadosValPag->pagval_ch_raz;
                $numCHQ = $dadosValPag->pagval_ch_num;

                $ano = date('Y');
                $dadosRaz = HelperDataSelect::buscaDadosRazao($codBcoCHQ, 'BA', $ano);

                $cnc = $dadosRaz->razao_bco_cod.' - '.$dadosRaz->razao_bco_nom;
                // Agência
                $age = $dadosRaz->razao_bco_age;
                if (!empty($dadosRaz->razao_bco_age_dv)) {
                    $age .= '-' . $dadosRaz->razao_bco_age_dv;
                }

                // Conta Corrente
                $conta = $dadosRaz->razao_bco_ncc;
                if (!empty($dadosRaz->razao_bco_ncc_dv)) {
                    $conta .= '-' . $dadosRaz->razao_bco_ncc_dv;
                }

                $emitente = HelperFormatSelect::formataEmpresaCodigoNome($dadosRaz->razao_empresa);
            }
            $array_opt_bco = HelperArraySelect::arrayRazoesPorTipo(1, 1, $empresaLote, 'BA');
        @endphp
        <div class="row">
            <x-adminlte-select name="codBcoCHQ" fgroup-class="col-md-12" igroup-size="sm">
                <x-slot name="label">
                    Banco Responsável<span style="color:red;">*</span>
                </x-slot>
                <x-adminlte-options :options="$array_opt_bco" empty-option="Selecione..." selected="{{$codBcoCHQ}}"/>
            </x-adminlte-select>
        </div>
        <div class="bloco-dados-ba text-muted">
            <div class="row">
                <p class="text-sm col-md-4">Código CNC do Banco
                    <b class="cnc d-block">{{ $cnc }}</b>
                </p>
                <p class="text-sm col-md-4">Agência
                    <b class="age d-block">{{ $age }}</b>
                </p>
                <p class="text-sm col-md-4">Conta
                    <b class="conta d-block">{{ $conta }}</b>
                </p>
            </div>
        </div>
        <!--
        <div class="text-muted">
            <div class="row">
                </p>
                <p class="text-sm col-md-12">Emitente
                    <b class="emitente d-block">{{ $emitente }}</b>
                </p>
            </div>
        </div>
        -->
        <div class="row">
            <x-adminlte-input name="valCHQ" type="text" value="{{ $valorPag }}" placeholder="0,00" fgroup-class="col-md-4" igroup-size="sm">
                <x-slot name="label">
                    Valor do Cheque <span style="color:red;">*</span>
                </x-slot>
                <x-slot name="prependSlot">
                    <div class="input-group-text x-slot-nexus">
                        <i class="fa-solid fa-brazilian-real-sign"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>

            <x-adminlte-input name="numCHQ" type="text" value="{{ $numCHQ }}" placeholder="Número do Cheque" fgroup-class="col-md-4" igroup-size="sm">
                <x-slot name="label">
                    Número do Cheque <span style="color:red;">*</span>
                </x-slot>
            </x-adminlte-input>

            @php
                $config = Helper::dtRangeDataPtBR();

                if(!empty($dadosValPag->pagval_ch_vct)){
                    $data_vct = date('d/m/Y', strtotime($dadosValPag->pagval_ch_vct));
                }else{
                    $data_vct = '';
                }
            @endphp
            <!-- Data de Nascimento -->
            <x-adminlte-date-range name="dataVctCHQ" label="Data de Vencimento" :config="$config" placeholder="Formato dia/mês/ano" fgroup-class="col-md-4" igroup-size="sm">
                <x-slot name="prependSlot">
                    <div class="input-group-text x-slot-nexus">
                        <i class="far fa-lg fa-calendar-alt"></i>
                    </div>
                </x-slot>
            </x-adminlte-date-range>
            @push('js')<script>$(() => $("#dataVctCHQ").val('{{ $data_vct }}'))</script>@endpush
        </div>
        
        <div class="row">
            <x-adminlte-textarea name="observacao" label="Observação" rows=3 label-class="text-dark" igroup-size="sm" placeholder="Informe a Observação..." fgroup-class="col-md-12">
                {{ $observacao }}
                <x-slot name="prependSlot">
                    <div class="input-group-text x-slot-nexus">
                        <i class="fas fa-lg fa-file-alt text-white"></i>
                    </div>
                </x-slot>
            </x-adminlte-textarea>
        </div>

        <!-- Campos escondidos para o request -->
        <input id="tipoValor" type="hidden" value="CHQ" name="tipoValor">

        <x-slot name="footerSlot">
            <div style="text-align: center;">
                <x-adminlte-button class="btn-nexus" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                <a class="btn btn-nexus" title="Voltar" href="{{route('pagamentoPCC.pagarLote',['appOrigem' => $appOrigem])}}">Voltar</a>
            </div>
        </x-slot>
    </x-adminlte-card>
</form>
@endif
<!-- ***** Final dos dados de Pagamento em Cheque - Bloco: PAG_CHEQUE ***** -->

<!-- ***** Início dos dados de Pagamento com Cartão Corporativo - Bloco: PAG_C_CORPORATIVO ***** -->
@if($estagio_app == 'PAG_C_CORPORATIVO')
@if($subEstagioPag == "NEW")
<form method="post" action="{{ route('valorPagamentoLote.store', ['appOrigem' => $appOrigem]) }}" id="form-pag-cco" novalidate="novalidate">
    @csrf 
    @method('post')
@else
<form method="post" action="{{ route('valorPagamentoLote.update', ['appOrigem' => $appOrigem, 'valorPagamento' => $dadosValPag]) }}" id="form-pag-cco" novalidate="novalidate">
    @csrf 
    @method('put')
@endif
    <x-adminlte-card title="Pagamento com Cartão Corporativo" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
        <div class="text-muted">
            <div class="row">
                <p class="text-sm col-md-3">Data do Pagamento
                    <b class="d-block">{{ date('d/m/Y') }}</b>
                </p>
                <p class="text-sm col-md-3">Qtd. de Contas
                    <b class="d-block">{{ $totalCC }}</b>
                </p>
                <p class="text-sm col-md-3">Valor Total
                    <b class="d-block">{{ Helper::formataValorMonetario($valorTotalPag) }}</b>
                </p>
                <p class="text-sm col-md-3">Saldo Restante
                    <b class="d-block">{{ Helper::formataValorMonetario($saldoRestante) }}</b>
                </p>
            </div>
        </div>
        <h6 class="h6-nexus-b">Dados do Pagamento</h6>
        @php 
            if($subEstagioPag == "NEW"){
                $valorPag = '';
                $observacao = '';
                $admCCO = null;
                $numCCO = '';
                $parcelaCCO = '';
                $diaVct = 0;
                $diaFec = 0;
            }else{
                $valorPag = $dadosValPag->pagval_valor;
                $observacao = $dadosValPag->pagval_obs;
                $admCCO = $dadosValPag->pagval_crt_adm;
                $numCCO = $dadosValPag->pagval_crt_com;
                $parcelaCCO = $dadosValPag->pagval_crt_prc;

                $data = DB::table('parametros_fin_corporativo_cartoes')
                ->where('parcco_adm', $admCCO)
                ->where('parcco_emp', $empresaLote)
                ->first();

                $diaVct = $data->parcco_div;
                $diaFec = $data->parcco_dif;
            }

            $array_opt_adm = HelperArraySelect::arrayCartaoCorporativo(1, 1, $empresaLote);
            $array_opt_parcela = HelperArraySelect::arrayQtdParcelaCartaoCorporativo(1,1,$admCCO,$empresaLote, $valorPag);
        @endphp
        <div class="row bloco-valor-cc">
            <x-adminlte-input name="valCCO" type="text" value="{{ $valorPag }}" placeholder="0,00" fgroup-class="col-md-6" igroup-size="sm">
                <x-slot name="label">
                    Valor <span style="color:red;">*</span>
                </x-slot>
                <x-slot name="prependSlot">
                    <div class="input-group-text x-slot-nexus">
                        <i class="fa-solid fa-brazilian-real-sign"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>
        </div>
        <div class="bloco-adm-par">
            <div class="row">
                <x-adminlte-select name="admCCO" fgroup-class="col-md-6" igroup-size="sm">
                    <x-slot name="label">
                        Administradora <span style="color:red;">*</span>
                    </x-slot>
                    <x-adminlte-options :options="$array_opt_adm" empty-option="Selecione..." selected="{{$admCCO}}"/>
                </x-adminlte-select>

                <x-adminlte-select name="parcelaCCO" fgroup-class="col-md-6" igroup-size="sm">
                    <x-slot name="label">
                        Parcelamento <span style="color:red;">*</span>
                    </x-slot>
                    <x-adminlte-options :options="$array_opt_parcela" empty-option="Selecione..." selected="{{$parcelaCCO}}"/>
                </x-adminlte-select>
            </div>
        </div>
        <div class="bloco-dados-cc">
            <div class="text-muted">
                <div class="row">
                    <p class="text-sm col-md-4">Número do Cartão
                        <b class="labelNumCCO d-block">{{ $numCCO }}</b>
                    </p>
                    <p class="text-sm col-md-4">Dia de Fechamento
                        <b class="diaFec d-block">{{ $diaFec}}</b>
                    </p>
                    <p class="text-sm col-md-4">Dia de Vencimento
                        <b class="diaVct d-block">{{ $diaVct }}</b>
                    </p>
                </div>
            </div>
            <div class="row">
                <x-adminlte-textarea name="observacao" label="Observação" rows=3 label-class="text-dark" igroup-size="sm" placeholder="Informe a Observação..." fgroup-class="col-md-12">
                    {{ $observacao }}
                    <x-slot name="prependSlot">
                        <div class="input-group-text x-slot-nexus">
                            <i class="fas fa-lg fa-file-alt text-white"></i>
                        </div>
                    </x-slot>
                </x-adminlte-textarea>
            </div>
        </div>

        <!-- Campos escondidos para o request -->
        <input id="tipoValor" type="hidden" value="CCO" name="tipoValor">
        <input id="numCCO" type="hidden" value="{{$numCCO}}" name="numCCO">

        <x-slot name="footerSlot">
            <div style="text-align: center;">
                <a class="btn btn-nexus btn-prox-pag-cco" title="Próximo">Próximo</a>
                <a class="btn btn-nexus btn-edit-pag-cco" title="Editar Valor">Editar Valor</a>
                <x-adminlte-button class="btn-nexus btn-salvar-pag-cco" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                <a class="btn btn-nexus" title="Voltar" href="{{route('pagamentoPCC.pagarLote',['appOrigem' => $appOrigem])}}">Voltar</a>
            </div>
        </x-slot>
    </x-adminlte-card>
</form>
@endif
<!-- ***** Final dos dados de Pagamento com cartão de Corporativo - Bloco: PAG_C_CORPORATIVO ***** -->

<!-- ***** Início do Filtro de Pagamento com Conta Corrente - Bloco: PAG_FIL_C_CORRENTE ***** -->
@if($estagio_app == 'PAG_FIL_C_CORRENTE')
<form method="get" action="{{ route('valorPagamentoLote.show', ['appOrigem' => $appOrigem, 'valorPagamento' => 'CCT']) }}" id="form-pag-filtro-cct" novalidate="novalidate">
    @csrf 
    @method('get')
    @php        
        $descAF = DB::table('financeiro_tab_contas')->where('tabcon_codigo', 'AF')->first();
        $descDC = DB::table('financeiro_tab_contas')->where('tabcon_codigo', 'DC')->first();

        $array_opt = [
            'AF' => 'AF - '.$descAF->tabcon_nome,
            'DC' => 'DC - '.$descDC->tabcon_nome
        ];
            
    @endphp
    <x-adminlte-card title="Filtro do Pagamento com Conta Corrente" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
        @if($appOrigem == 'LOTE')
        <div class="row"> 
            <x-adminlte-select name="tipoCCTFiltro" label="Tipo" fgroup-class="col-md-12" igroup-size="sm">
                <x-adminlte-options :options="$array_opt" empty-option="Selecione..." selected=""/>
            </x-adminlte-select>
        </div>
        @endif
        <div class="row">
            <x-adminlte-input name="numCCTFiltro" label="Número da Conta" type="text" value="" placeholder="Número da Conta Corrente" fgroup-class="col-md-12" igroup-size="sm"></x-adminlte-input>
        </div>
        <div class="row">
            @php
                $config = Helper::dtRangeDataPtBR();
            @endphp
            <!-- Data de Pedido / OS -->
            <x-adminlte-date-range name="dtIniFiltro" label="Data de Vencimento Inicial" :config="$config" placeholder="de dia/mês/ano" fgroup-class="col-md-6" igroup-size="sm">
                <x-slot name="prependSlot">
                    <div class="input-group-text x-slot-nexus">
                        <i class="far fa-lg fa-calendar-alt"></i>
                    </div>
                </x-slot>
            </x-adminlte-date-range>
            <x-adminlte-date-range name="dtFinFiltro" label="Data de Vencimento Final" :config="$config" placeholder="até dia/mês/ano" fgroup-class="col-md-6" igroup-size="sm">
                <x-slot name="prependSlot">
                    <div class="input-group-text x-slot-nexus">
                        <i class="far fa-lg fa-calendar-alt"></i>
                    </div>
                </x-slot>
            </x-adminlte-date-range>
        </div>
        <div class="row">
            <x-adminlte-input name="vlrIniFiltro" label="Valor Inicial" type="text" value="" placeholder="de 0,00" fgroup-class="col-md-6" igroup-size="sm">
                <x-slot name="prependSlot">
                    <div class="input-group-text x-slot-nexus">
                        <i class="fa-solid fa-brazilian-real-sign"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>
            <x-adminlte-input name="vlrFinFiltro" label="Valor Final" type="text" value="" placeholder="até 0,00" fgroup-class="col-md-6" igroup-size="sm">
                <x-slot name="prependSlot">
                    <div class="input-group-text x-slot-nexus">
                        <i class="fa-solid fa-brazilian-real-sign"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>
        </div>
        
        <!-- Campos escondidos para o request -->
        <input id="etapa" type="hidden" value="SELECAO" name="etapa">
        
        <x-slot name="footerSlot">
            <div style="text-align: center;">
                <x-adminlte-button class="btn-nexus" type="submit" label="Pesquisar" theme="" icon="fa-solid fa-magnifying-glass"/>
                <a class="btn btn-nexus" title="Voltar" href="{{route('pagamentoPCC.pagarLote',['appOrigem' => $appOrigem])}}">Voltar</a>
            </div>
        </x-slot>
    </x-adminlte-card>
</form>
@endif
<!-- ***** Final do Filtro de Pagamento com Conta Corrente - Bloco: PAG_FIL_C_CORRENTE ***** -->

<!-- ***** Seleção da Conta do Pagamento com Conta Corrente - Bloco: PAG_SEL_C_CORRENTE ***** -->
@if($estagio_app == 'PAG_SEL_C_CORRENTE')
<x-adminlte-card title="Pagamento com Conta Corrente" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
    <div class="text-muted">
        <div class="row">
            <p class="text-sm col-md-3">Data do Pagamento
                <b class="d-block">{{ date('d/m/Y') }}</b>
            </p>
            <p class="text-sm col-md-3">Qtd. de Contas
                <b class="d-block">{{ $totalCC }}</b>
            </p>
            <p class="text-sm col-md-3">Valor Total
                <b class="d-block">{{ Helper::formataValorMonetario($valorTotalPag) }}</b>
            </p>
            <p class="text-sm col-md-3">Saldo Restante
                <b class="d-block">{{ Helper::formataValorMonetario($saldoRestante) }}</b>
            </p>
        </div>
    </div>
    <h6 class="h6-nexus-b">Contas Disponíveis</h6>
    <div class="row">     
        @php

            $headsSelCCT = [
                ['label' => '', 'no-export' => true, 'width' => 5],
                'Tipo',
                'Responsável',
                'Número',
                'Vencimento',
                'Saldo'
            ];
        
        @endphp
        <x-adminlte-datatable id="tabela-sel-cct" :heads="$headsSelCCT" theme="light" striped hoverable beautify>
            @foreach($contasSel as $conta)
                @php 
                    $valor = Helper::formataValorMonetario($conta->conta_saldo);

                    $url = route('valorPagamentoLote.create', [
                        'appOrigem' => $appOrigem,
                        'tipoValor' => 'CCT',
                        'conta_num' => $conta->conta_num_conta,
                        'conta_tipo' => $conta->conta_tipo
                    ]);

                    $dadosTipCCT = DB::table('financeiro_tab_contas')->where('tabcon_codigo', $conta->conta_tipo)->first();
                    $dadosCli = DB::table('cadastro_clientes')->where('cliente_codigo', $clienteLote)->first();
                @endphp
                <tr>
                    <td>
                        <nobr class="d-flex justify-content-center">
                            <a href="{{ $url }}" class="btn btn-nexus btn-sm">
                                <i class="fa-solid fa-check"></i> Selecionar
                            </a>
                        </nobr>
                    </td>
                    <td>{{$conta->conta_tipo.' - '.$dadosTipCCT->tabcon_nome}}</td>
                    <td>{{$conta->conta_responsavel.' - '. $dadosCli->cliente_nome}}</td>
                    <td>{{$conta->conta_num_conta}}</td>  
                    <td>{{ Helper::formataData($conta->conta_dt_vencimento) }}</td>
                    <td>{{$valor}}</td> 
                </tr>
            @endforeach
        </x-adminlte-datatable>
    </div>

    <x-slot name="footerSlot">
        <div style="text-align: center;">
            <a class="btn btn-nexus" title="Filtro" href="{{ route('valorPagamentoLote.show', ['appOrigem' => $appOrigem, 'valorPagamento' => 'CCT', 'etapa' => 'FILTRO']) }}">
                <i class="fa-solid fa-magnifying-glass"></i> Filtro
            </a>
            <a class="btn btn-nexus" title="Voltar" href="{{route('pagamentoPCC.pagarLote',['appOrigem' => $appOrigem])}}">Voltar</a>
        </div>
    </x-slot>
</x-adminlte-card>
@endif
<!-- ***** Final dos dados da Seleção da Conta Corrente - Bloco: PAG_SEL_C_CORRENTE ***** -->

<!-- ***** Início dos dados de Pagamento com Conta Corrente - Bloco: PAG_CONTA_CORRENTE ***** -->
@if($estagio_app == 'PAG_CONTA_CORRENTE')
@if($subEstagioPag == "NEW")
<form method="post" action="{{ route('valorPagamentoLote.store', ['appOrigem' => $appOrigem]) }}" id="form-pag-cct" novalidate="novalidate">
    @csrf 
    @method('post')
@else
<form method="post" action="{{ route('valorPagamentoLote.update', ['appOrigem' => $appOrigem, 'valorPagamento' => $dadosValPag]) }}" id="form-pag-cct" novalidate="novalidate">
    @csrf 
    @method('put')
@endif
    <x-adminlte-card title="Pagamento com Conta Corrente" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
        <div class="text-muted">
            <div class="row">
                <p class="text-sm col-md-3">Data do Pagamento
                    <b class="d-block">{{ date('d/m/Y') }}</b>
                </p>
                <p class="text-sm col-md-3">Qtd. de Contas
                    <b class="d-block">{{ $totalCC }}</b>
                </p>
                <p class="text-sm col-md-3">Valor Total
                    <b class="d-block">{{ Helper::formataValorMonetario($valorTotalPag) }}</b>
                </p>
                <p class="text-sm col-md-3">Saldo Restante
                    <b class="d-block">{{ Helper::formataValorMonetario($saldoRestante) }}</b>
                </p>
            </div>
        </div>
        <h6 class="h6-nexus-b">Dados do Pagamento</h6>
        @php 
            $dadosCli = DB::table('cadastro_clientes')->where('cliente_codigo', $clienteLote)->first();
            $dadosTipCCT = DB::table('financeiro_tab_contas')->where('tabcon_codigo', $tipoCctSel)->first();
            $valorCCT = $dadosCCT->conta_saldo;

            if($subEstagioPag == "NEW"){
                $valorPag = '';
                $observacao = '';
                $saldoRestCCT = $valorCCT;
            }else{
                $valorPag = $dadosValPag->pagval_valor;
                $observacao = $dadosValPag->pagval_obs;
                $saldoRestCCT = $valorCCT - $dadosValPag->pagval_valor;
            }
        @endphp
        <div class="text-muted">
            <div class="row">
                <p class="text-sm col-md-4">Responsável
                    <b class="d-block">{{ $dadosCCT->conta_responsavel.' - '. $dadosCli->cliente_nome }}</b>
                </p>
                <p class="text-sm col-md-4">Tipo da Conta
                    <b class="d-block">{{ $dadosCCT->conta_tipo.' - '.$dadosTipCCT->tabcon_nome }}</b>
                </p>
                <p class="text-sm col-md-4">Número da Conta
                    <b class="d-block">{{ $dadosCCT->conta_num_conta }}</b>
                </p>
            </div>
        </div>
        <div class="text-muted">
            <div class="row">
                <p class="text-sm col-md-4">Valor
                    <b class="d-block">{{ 'R$ '.Helper::formataValorMonetario($dadosCCT->conta_saldo) }}</b>
                </p>
                <p class="text-sm col-md-4">Saldo Restante
                    <b class="d-block" id="saldoRestante">{{ 'R$ '.Helper::formataValorMonetario($saldoRestCCT) }}</b>
                </p>
                <p class="text-sm col-md-4">Vencimento
                    <b class="d-block">{{ Helper::formataData($dadosCCT->conta_dt_vencimento) }}</b>
                </p>
            </div>
        </div>
        <div class="row">
            <x-adminlte-input name="valCCT" type="text" value="{{ $valorPag }}" placeholder="0,00" fgroup-class="col-md-12" igroup-size="sm">
                <x-slot name="label">
                    Valor <span style="color:red;">*</span>
                </x-slot>
                <x-slot name="prependSlot">
                    <div class="input-group-text x-slot-nexus">
                        <i class="fa-solid fa-brazilian-real-sign"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>
        </div>
        <div class="row">
            <x-adminlte-textarea name="observacao" label="Observação" rows=3 label-class="text-dark" igroup-size="sm" placeholder="Informe a Observação..." fgroup-class="col-md-12">
                {{ $observacao }}
                <x-slot name="prependSlot">
                    <div class="input-group-text x-slot-nexus">
                        <i class="fas fa-lg fa-file-alt text-white"></i>
                    </div>
                </x-slot>
            </x-adminlte-textarea>
        </div>

        <!-- Campos escondidos para o request -->
        <input id="tipoValor" type="hidden" value="CCT" name="tipoValor">
        <input id="tipoCCT" type="hidden" value="{{$tipoCctSel}}" name="tipoCCT">
        <input id="resCCT" type="hidden" value="{{$clienteLote}}" name="resCCT">
        <input id="numCCT" type="hidden" value="{{$contaCctSel}}" name="numCCT">
        <input id="valTotCCT" type="hidden" value="{{$valorCCT}}" name="valTotCCT">

        <x-slot name="footerSlot">
            <div style="text-align: center;">
                @if($subEstagioPag == "NEW")
                <a class="btn btn-nexus" title="Filtro" href="{{ route('valorPagamentoLote.show', ['appOrigem' => $appOrigem, 'valorPagamento' => 'CCT', 'etapa' => 'FILTRO']) }}">
                    <i class="fa-solid fa-magnifying-glass"></i> Filtro
                </a>
                <a class="btn btn-nexus" title="Selecionar Conta" href="{{ route('valorPagamentoLote.show', ['appOrigem' => $appOrigem, 'valorPagamento' => 'CCT', 'etapa' => 'SELECAO_VOLTAR']) }}">
                    <i class="fa-solid fa-list"></i> Selecionar Conta
                </a>
                @endif
                <x-adminlte-button class="btn-nexus" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                <a class="btn btn-nexus" title="Voltar" href="{{route('pagamentoPCC.pagarLote',['appOrigem' => $appOrigem])}}">Voltar</a>
            </div>
        </x-slot>
    </x-adminlte-card>
</form>
@endif
<!-- ***** Final dos dados de Pagamento com Conta Corrente - Bloco: PAG_CONTA_CORRENTE ***** -->