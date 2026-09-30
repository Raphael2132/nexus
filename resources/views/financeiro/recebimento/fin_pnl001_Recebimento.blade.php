<!-- ***** Início do painel principal do recebimento - Bloco: PAINEL_RECEBIMENTO ***** -->
@if($estagio_app == 'PAINEL_RECEBIMENTO')
@php
    if(Session::get('glo_recebimento_origem') == 'NFV'){
        $titulo = 'Valores do Recebimento de Notas Fiscais';
    }elseif(Session::get('glo_recebimento_origem') == 'CCT'){
        $titulo = 'Valores do Recebimento de Contas Correntes';
    }elseif(Session::get('glo_recebimento_origem') == 'DUP'){
        $titulo = 'Valores do Recebimento de Duplicatas';
    }else{
        $titulo = 'Valores do Recebimento';
    }
@endphp
<x-adminlte-card :title="$titulo" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
    <div class="row">  
        <div class="col-md-12">       
            <table class="table tabela-valores tabela-custom-nexus">
                <tbody>
                    <tr>
                        <th>Valores Recebidos</th>
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
                'Tipo do Recebimento',
                'Descrição',
                'Valor',
            ];
        
        @endphp
        <x-adminlte-datatable id="tabela-valores" :heads="$heads1" theme="light" striped hoverable beautify>
            @foreach($dadosRecebimento as $valores)
                @php 
                    $tipo = Helper::formataTipoValRecebimento($valores->recval_tipo);
                    $valor = Helper::formataValorMonetario($valores->recval_valor);

                    if($valores->recval_tipo == 'DIN'){

                        $desc = 'Recebimento em Dinheiro';

                    }elseif($valores->recval_tipo == 'PIX'){

                        $desc = 'Banco: '.$valores->recval_pix_bco.' / Comprovante: '.$valores->recval_pix_doc;

                    }elseif($valores->recval_tipo == 'CHQ'){

                        if(!empty($valores->recval_ch_age_dv)){
                            $age = $valores->recval_ch_age.'-'.$valores->recval_ch_age_dv;
                        }else{
                            $age = $valores->recval_ch_age;
                        }

                        if(!empty($valores->recval_ch_ccr_dv)){
                            $ccr = $valores->recval_ch_ccr.'-'.$valores->recval_ch_ccr_dv;
                        }else{
                            $ccr = $valores->recval_ch_ccr;
                        }

                        $vct = Helper::formataData($valores->recval_ch_vct);

                        $desc = 'Banco: '.$valores->recval_ch_bco.' / Age.: '.$age.' / C.C.: '.$ccr.' / Venc.: '.$vct;

                    }elseif($valores->recval_tipo == 'CCR'){

                        $desc = 'Adm.: '.$valores->recval_crt_adm.' / Comp.: '.$valores->recval_crt_com.' / Qtd. Parcelas: '.$valores->recval_crt_prc;

                    }elseif($valores->recval_tipo == 'CDB'){

                        $desc = 'Adm.: '.$valores->recval_crt_adm.' / Comprovante: '.$valores->recval_crt_com;

                    }elseif($valores->recval_tipo == 'CCT'){

                        $desc = 'Tipo: '.$valores->recval_cct_tcc.' / Resp.: '.$valores->recval_cct_res.' / Conta: '.$valores->recval_cct_ncc;

                    }else{
                        $desc = '';
                    }
                @endphp
                <tr>
                    <td>{{$valores->recval_seq}}</td>
                    <td>
                        <nobr class="d-flex justify-content-center">
                            <form method="post" action="{{route('valorRecebimento.destroy', ['valorRecebimento' => $valores->recval_id])}}">
                                @csrf 
                                @method('delete')
                                <button class="btn btn-xs btn-outline-danger mx-1" title="Excluir Registro" value="Delete" type="submit" >
                                    <i class="fa fa-lg fa-fw fa-trash-can"></i>
                                </button>
                            </form>

                            <form method="get" action="{{route('valorRecebimento.edit', ['valorRecebimento' => $valores->recval_seq])}}">
                                @csrf 

                                <!-- Informa os valores extra para a Edição -->
                                <input id="tipoRec" type="hidden" value="{{ $valores->recval_tipo }}" name="tipoRec">

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
    @if($appOrigem == 'NFV')
    <form method="post" action="{{ route('emissaoNF.finalizaRecebimento', ['empresa' => $empresaREC, 'cliente' => $clienteREC]) }}" id="formularioFinal" novalidate="novalidate">
        @csrf 
        @method('post')
    @elseif($appOrigem == 'CCT')
    <form method="post" action="{{ route('recebimentoCCT.finalizarRecCCT', ['empresa' => $empresaREC, 'cliente' => $clienteREC]) }}" id="formularioFinal" novalidate="novalidate">
        @csrf 
        @method('post')
    @elseif($appOrigem == 'DUP')
    <form method="post" action="{{ route('recebimentoDUP.finalizarRecDUP', ['empresa' => $empresaREC, 'cliente' => $clienteREC]) }}" id="formularioFinal" novalidate="novalidate">
        @csrf 
        @method('post')
    @elseif($appOrigem == 'OUT')
    <form method="post" action="{{ route('recebimentoOUT.finalizarRecOUT', ['empresa' => $empresaREC, 'cliente' => $clienteREC]) }}" id="formularioFinal" novalidate="novalidate">
        @csrf 
        @method('post')
    @endif
        <div class="row">
            <div class="col-md-6">       
                <table class="table tabela-resumo tabela-custom-nexus" style="padding: .25rem;">
                    <tbody>
                        <tr>
                            <th colspan="2">Resumo do Recebimento</th>
                        </tr>
                        <tr>
                            <td style="width: 40%;"><strong>Valor à Receber:</strong></td>
                            <td style="text-align: left">{{ Helper::formataValorMonetario($valorTotalRec) }}</td>
                        </tr>
                        <tr>
                            <td style="width: 40%;"><strong>Valor Recebido:</strong></td>
                            <td style="text-align: left">{{ Helper::formataValorMonetario($valorRecebido) }}</td>
                        </tr>
                        <tr>
                            <td style="width: 40%;"><strong>Saldo Restante:</strong></td>
                            <td style="text-align: left">{{ Helper::formataValorMonetario($saldoRestante) }}</td>
                        </tr>
                        <tr>
                            <td style="width: 40%;"><strong>Troco:</strong></td>
                            <td style="text-align: left">{{ Helper::formataValorMonetario($trocoRecebimento) }}</td>
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
                    </tbody>
                </table> 
            </div>
            <div class="col-md-6">       
                <table class="table tabela-forma-pgt tabela-custom-nexus" style="width: 100%; table-layout: fixed;">
                    <tbody>
                        <tr>
                            <th colspan="3">Formas de Pagamento</th>
                        </tr>
                        <tr>
                            <td>
                                <a href="{{ route('valorRecebimento.create', ['tipoValor' => 'DIN']) }}" title="Recebimento em Dinheiro">
                                    <div class="icon-text">
                                        <i class="fas fa-money-bill-wave fa-2x" style="color: #008040;"></i>
                                        <span>Dinheiro</span>
                                    </div>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('valorRecebimento.create', ['tipoValor' => 'PIX']) }}" title="PIX / Crédito Bancário">
                                    <div class="icon-text">
                                        <i class="fa-brands fa-pix fa-2x" style="color: #00E1D6;"></i>
                                        <span>PIX</span>
                                    </div>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('valorRecebimento.create', ['tipoValor' => 'CHQ']) }}" title="Cheque">
                                    <div class="icon-text">
                                        <i class="fas fa-money-check fa-2x" style="color: #800040;"></i>
                                        <span>Cheque</span>
                                    </div>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="{{ route('valorRecebimento.create', ['tipoValor' => 'CCR']) }}" title="Cartão de Crédito">
                                    <div class="icon-text">
                                        <i class="fas fa-credit-card fa-2x" style="color: #004080;"></i>
                                        <span>Cartão Crédito</span>
                                    </div>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('valorRecebimento.create', ['tipoValor' => 'CDB']) }}" title="Cartão de Débito">
                                    <div class="icon-text">
                                        <i class="fas fa-credit-card-front fa-2x" style="color: #8000ff;"></i>
                                        <span>Cartão Débito</span>
                                    </div>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('valorRecebimento.show', ['valorRecebimento' => 'CCT', 'etapa' => 'FILTRO']) }}" title="Conta Corrente">
                                    <div class="icon-text">
                                        <i class="fas fa-building-columns fa-2x" style="color: #808080;"></i>
                                        <span>Conta Corrente</span>
                                    </div>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
    <x-slot name="footerSlot">
        <div style="text-align: center;">
            <x-adminlte-button class="btn-nexus" label="Finalizar Recebimento" theme="" icon="fa-solid fa-check" onclick="document.getElementById('formularioFinal').submit()"/>
        </div>
    </x-slot>
</x-adminlte-card>
@endif
<!-- ***** Final do painel principal do recebimento - Bloco: PAINEL_RECEBIMENTO ***** -->

<!-- ***** Início dos dados de Recebimento em Dinheiro - Bloco: REC_DINHEIRO ***** -->
@if($estagio_app == 'REC_DINHEIRO')
@if($subEstagioRec == "NEW")
<form method="post" action="{{ route('valorRecebimento.store') }}" id="form-rec-din" novalidate="novalidate">
    @csrf 
    @method('post')
@else
<form method="post" action="{{ route('valorRecebimento.update', ['valorRecebimento' => $dadosValRec]) }}" id="form-rec-din" novalidate="novalidate">
    @csrf 
    @method('put')
@endif
    <x-adminlte-card title="Recebimento em Dinheiro" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
        <div class="text-muted">
            <div class="row">
                <p class="text-sm col-md-3">Data do Recebimento
                    <b class="d-block">{{ date('d/m/Y') }}</b>
                </p>
                @if(Session::get('glo_recebimento_origem') == 'NFV')
                <p class="text-sm col-md-3">Qtd. de Notas
                    <b class="d-block">{{ $totalNF }}</b>
                </p>
                @elseif(Session::get('glo_recebimento_origem') == 'CCT')
                <p class="text-sm col-md-3">Qtd. de Contas
                    <b class="d-block">{{ $totalCCT }}</b>
                </p>
                @elseif(Session::get('glo_recebimento_origem') == 'DUP')
                <p class="text-sm col-md-3">Qtd. de Duplicatas
                    <b class="d-block">{{ $totalDUP }}</b>
                </p>
                @elseif(Session::get('glo_recebimento_origem') == 'OUT')
                <p class="text-sm col-md-3">Qtd. de Recebimento
                    <b class="d-block">{{ $totalOUT }}</b>
                </p>
                @endif
                <p class="text-sm col-md-3">Valor Total
                    <b class="d-block">{{ Helper::formataValorMonetario($valorTotalRec) }}</b>
                </p>
                <p class="text-sm col-md-3">Saldo Restante
                    <b class="d-block">{{ Helper::formataValorMonetario($saldoRestante) }}</b>
                </p>
            </div>
        </div>
        <h6 class="h6-nexus-b">Dados do Recebimento</h6>
        @php 
            if($subEstagioRec == "NEW"){
                $valorRec = '';
                $observacao = '';
            }else{
                $valorRec = $dadosValRec->recval_valor;
                $observacao = $dadosValRec->recval_obs;
            }
        @endphp
        <div class="row">
            <x-adminlte-input name="valDin" type="text" value="{{ $valorRec }}" placeholder="0,00" fgroup-class="col-md-12" igroup-size="sm">
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
                <a class="btn btn-nexus" title="Voltar" href="{{route('recebimento.painelRecebimento',['empresa' => $empresaREC, 'cliente' => $clienteREC, 'idRecebimento' => $idRecebimento])}}">Voltar</a>
            </div>
        </x-slot>
    </x-adminlte-card>
</form>
@endif
<!-- ***** Final dos dados de Recebimento em Dinheiro - Bloco: REC_DINHEIRO ***** -->

<!-- ***** Início dos dados de Recebimento em PIX - Bloco: REC_PIX ***** -->
@if($estagio_app == 'REC_PIX')
@if($subEstagioRec == "NEW")
<form method="post" action="{{ route('valorRecebimento.store') }}" id="form-rec-pix" novalidate="novalidate">
    @csrf 
    @method('post')
@else
<form method="post" action="{{ route('valorRecebimento.update', ['valorRecebimento' => $dadosValRec]) }}" id="form-rec-pix" novalidate="novalidate">
    @csrf 
    @method('put')
@endif
    <x-adminlte-card title="Recebimento em PIX" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
        <div class="text-muted">
            <div class="row">
                <p class="text-sm col-md-3">Data do Recebimento
                    <b class="d-block">{{ date('d/m/Y') }}</b>
                </p>
                @if(Session::get('glo_recebimento_origem') == 'NFV')
                <p class="text-sm col-md-3">Qtd. de Notas
                    <b class="d-block">{{ $totalNF }}</b>
                </p>
                @elseif(Session::get('glo_recebimento_origem') == 'CCT')
                <p class="text-sm col-md-3">Qtd. de Contas
                    <b class="d-block">{{ $totalCCT }}</b>
                </p>
                @elseif(Session::get('glo_recebimento_origem') == 'DUP')
                <p class="text-sm col-md-3">Qtd. de Duplicatas
                    <b class="d-block">{{ $totalDUP }}</b>
                </p>
                @elseif(Session::get('glo_recebimento_origem') == 'OUT')
                <p class="text-sm col-md-3">Qtd. de Recebimento
                    <b class="d-block">{{ $totalOUT }}</b>
                </p>
                @endif
                <p class="text-sm col-md-3">Valor Total
                    <b class="d-block">{{ Helper::formataValorMonetario($valorTotalRec) }}</b>
                </p>
                <p class="text-sm col-md-3">Saldo Restante
                    <b class="d-block">{{ Helper::formataValorMonetario($saldoRestante) }}</b>
                </p>
            </div>
        </div>
        <h6 class="h6-nexus-b">Dados do Recebimento</h6>
        @php 
            if($subEstagioRec == "NEW"){
                $valorRec = '';
                $observacao = '';
                $codBcoPIX = null;
                $numDocPIX = '';
            }else{
                $valorRec = $dadosValRec->recval_valor;
                $observacao = $dadosValRec->recval_obs;
                $codBcoPIX = $dadosValRec->recval_pix_bco;
                $numDocPIX = $dadosValRec->recval_pix_doc;
            }
            $array_opt_bco = HelperArraySelect::arrayRazoesPorTipo(1, 1, $empresaREC, 'BA');
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
            <x-adminlte-input name="valPIX" type="text" value="{{ $valorRec }}" placeholder="0,00" fgroup-class="col-md-12" igroup-size="sm">
                <x-slot name="label">
                    Valor do PIX <span style="color:red;">*</span>
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
        <input id="tipoValor" type="hidden" value="PIX" name="tipoValor">

        <x-slot name="footerSlot">
            <div style="text-align: center;">
                <x-adminlte-button class="btn-nexus" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                <a class="btn btn-nexus" title="Voltar" href="{{route('recebimento.painelRecebimento',['empresa' => $empresaREC, 'cliente' => $clienteREC, 'idRecebimento' => $idRecebimento])}}">Voltar</a>
            </div>
        </x-slot>
    </x-adminlte-card>
</form>
@endif
<!-- ***** Final dos dados de Recebimento em PIX - Bloco: REC_PIX ***** -->

<!-- ***** Início dos dados de Recebimento em Cheque - Bloco: REC_CHEQUE ***** -->
@if($estagio_app == 'REC_CHEQUE')
@if($subEstagioRec == "NEW")
<form method="post" action="{{ route('valorRecebimento.store') }}" id="form-rec-cheque" novalidate="novalidate">
    @csrf 
    @method('post')
@else
<form method="post" action="{{ route('valorRecebimento.update', ['valorRecebimento' => $dadosValRec]) }}" id="form-rec-cheque" novalidate="novalidate">
    @csrf 
    @method('put')
@endif
    <x-adminlte-card title="Recebimento em Cheque" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
        <div class="text-muted">
            <div class="row">
                <p class="text-sm col-md-3">Data do Recebimento
                    <b class="d-block">{{ date('d/m/Y') }}</b>
                </p>
                @if(Session::get('glo_recebimento_origem') == 'NFV')
                <p class="text-sm col-md-3">Qtd. de Notas
                    <b class="d-block">{{ $totalNF }}</b>
                </p>
                @elseif(Session::get('glo_recebimento_origem') == 'CCT')
                <p class="text-sm col-md-3">Qtd. de Contas
                    <b class="d-block">{{ $totalCCT }}</b>
                </p>
                @elseif(Session::get('glo_recebimento_origem') == 'DUP')
                <p class="text-sm col-md-3">Qtd. de Duplicatas
                    <b class="d-block">{{ $totalDUP }}</b>
                </p>
                @elseif(Session::get('glo_recebimento_origem') == 'OUT')
                <p class="text-sm col-md-3">Qtd. de Recebimento
                    <b class="d-block">{{ $totalOUT }}</b>
                </p>
                @endif
                <p class="text-sm col-md-3">Valor Total
                    <b class="d-block">{{ Helper::formataValorMonetario($valorTotalRec) }}</b>
                </p>
                <p class="text-sm col-md-3">Saldo Restante
                    <b class="d-block">{{ Helper::formataValorMonetario($saldoRestante) }}</b>
                </p>
            </div>
        </div>
        <h6 class="h6-nexus-b">Dados do Recebimento</h6>
        @php 
            if($subEstagioRec == "NEW"){
                $valorRec = '';
                $observacao = '';
                $codBcoCHQ = null;
                $numAgeCHQ = '';
                $numAgeDvCHQ = '';
                $numCcrCHQ = '';
                $numCcrDvCHQ = '';
                $numCHQ = '';
                $resCHQ = '';
            }else{
                $valorRec = $dadosValRec->recval_valor;
                $observacao = $dadosValRec->recval_obs;
                $codBcoCHQ = $dadosValRec->recval_ch_bco;
                $numAgeCHQ = $dadosValRec->recval_ch_age;
                $numAgeDvCHQ = $dadosValRec->recval_ch_age_dv;
                $numCcrCHQ = $dadosValRec->recval_ch_ccr;
                $numCcrDvCHQ = $dadosValRec->recval_ch_ccr_dv;
                $numCHQ = $dadosValRec->recval_ch_num;
                $resCHQ = $dadosValRec->recval_ch_res;
            }
            $array_opt_bco = HelperArraySelect::arrayCncBancos(1, 1);
        @endphp
        <div class="row">
            <x-adminlte-select name="codBcoCHQ" fgroup-class="col-md-12" igroup-size="sm">
                <x-slot name="label">
                    Banco <span style="color:red;">*</span>
                </x-slot>
                <x-adminlte-options :options="$array_opt_bco" empty-option="Selecione..." selected="{{$codBcoCHQ}}"/>
            </x-adminlte-select>
        </div>
        <div class="row">
            <x-adminlte-input name="numAgeCHQ" type="text" value="{{ $numAgeCHQ }}" placeholder="Agência" fgroup-class="col-md-3" igroup-size="sm">
                <x-slot name="label">
                    Agência <span style="color:red;">*</span>
                </x-slot>
            </x-adminlte-input>
            <x-adminlte-input name="numAgeDvCHQ" type="text" value="{{ $numAgeDvCHQ }}" placeholder="Dígito da Agência" fgroup-class="col-md-3" igroup-size="sm">
                <x-slot name="label">
                    Dígito da Agência
                </x-slot>
            </x-adminlte-input>
            <x-adminlte-input name="numCcrCHQ" type="text" value="{{ $numCcrCHQ }}" placeholder="Conta Corrente" fgroup-class="col-md-3" igroup-size="sm">
                <x-slot name="label">
                    Conta Corrente <span style="color:red;">*</span>
                </x-slot>
            </x-adminlte-input>
            <x-adminlte-input name="numCcrDvCHQ" type="text" value="{{ $numCcrDvCHQ }}" placeholder="Dígito da Conta Corrente" fgroup-class="col-md-3" igroup-size="sm">
                <x-slot name="label">
                    Dígito da Conta
                </x-slot>
            </x-adminlte-input>
        </div>
        <div class="row">
            <x-adminlte-input name="numCHQ" type="text" value="{{ $numCHQ }}" placeholder="Número do Cheque" fgroup-class="col-md-6" igroup-size="sm">
                <x-slot name="label">
                    Número do Cheque <span style="color:red;">*</span>
                </x-slot>
            </x-adminlte-input>

            @php
                $config = Helper::dtRangeDataPtBR();

                if(!empty($dadosValRec->recval_ch_vct)){
                    $data_vct = date('d/m/Y', strtotime($dadosValRec->recval_ch_vct));
                }else{
                    $data_vct = '';
                }
            @endphp
            <!-- Data de Nascimento -->
            <x-adminlte-date-range name="dataVctCHQ" label="Data de Vencimento" :config="$config" placeholder="Formato dia/mês/ano" fgroup-class="col-md-6" igroup-size="sm">
                <x-slot name="prependSlot">
                    <div class="input-group-text x-slot-nexus">
                        <i class="far fa-lg fa-calendar-alt"></i>
                    </div>
                </x-slot>
            </x-adminlte-date-range>
            @push('js')<script>$(() => $("#dataVctCHQ").val('{{ $data_vct }}'))</script>@endpush
        </div>
        <div class="row">
            <x-adminlte-input name="valCHQ" type="text" value="{{ $valorRec }}" placeholder="0,00" fgroup-class="col-md-6" igroup-size="sm">
                <x-slot name="label">
                    Valor do Cheque <span style="color:red;">*</span>
                </x-slot>
                <x-slot name="prependSlot">
                    <div class="input-group-text x-slot-nexus">
                        <i class="fa-solid fa-brazilian-real-sign"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>
            <x-adminlte-input name="resCHQ" type="text" value="{{ $resCHQ }}" placeholder="Responsável do Cheque" fgroup-class="col-md-6" igroup-size="sm">
                <x-slot name="label">
                    Responsável do Cheque <span style="color:red;">*</span>
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
        <input id="tipoValor" type="hidden" value="CHQ" name="tipoValor">

        <x-slot name="footerSlot">
            <div style="text-align: center;">
                <x-adminlte-button class="btn-nexus" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                <a class="btn btn-nexus" title="Voltar" href="{{route('recebimento.painelRecebimento',['empresa' => $empresaREC, 'cliente' => $clienteREC, 'idRecebimento' => $idRecebimento])}}">Voltar</a>
            </div>
        </x-slot>
    </x-adminlte-card>
</form>
@endif
<!-- ***** Final dos dados de Recebimento em Cheque - Bloco: REC_CHEQUE ***** -->

<!-- ***** Início dos dados de Recebimento com Cartão de Crédito - Bloco: REC_C_CREDITO ***** -->
@if($estagio_app == 'REC_C_CREDITO')
@if($subEstagioRec == "NEW")
<form method="post" action="{{ route('valorRecebimento.store') }}" id="form-rec-ccr" novalidate="novalidate">
    @csrf 
    @method('post')
@else
<form method="post" action="{{ route('valorRecebimento.update', ['valorRecebimento' => $dadosValRec]) }}" id="form-rec-ccr" novalidate="novalidate">
    @csrf 
    @method('put')
@endif
    <x-adminlte-card title="Recebimento com Cartão de Crédito" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
        <div class="text-muted">
            <div class="row">
                <p class="text-sm col-md-3">Data do Recebimento
                    <b class="d-block">{{ date('d/m/Y') }}</b>
                </p>
                @if(Session::get('glo_recebimento_origem') == 'NFV')
                <p class="text-sm col-md-3">Qtd. de Notas
                    <b class="d-block">{{ $totalNF }}</b>
                </p>
                @elseif(Session::get('glo_recebimento_origem') == 'CCT')
                <p class="text-sm col-md-3">Qtd. de Contas
                    <b class="d-block">{{ $totalCCT }}</b>
                </p>
                @elseif(Session::get('glo_recebimento_origem') == 'DUP')
                <p class="text-sm col-md-3">Qtd. de Duplicatas
                    <b class="d-block">{{ $totalDUP }}</b>
                </p>
                @elseif(Session::get('glo_recebimento_origem') == 'OUT')
                <p class="text-sm col-md-3">Qtd. de Recebimento
                    <b class="d-block">{{ $totalOUT }}</b>
                </p>
                @endif
                <p class="text-sm col-md-3">Valor Total
                    <b class="d-block">{{ Helper::formataValorMonetario($valorTotalRec) }}</b>
                </p>
                <p class="text-sm col-md-3">Saldo Restante
                    <b class="d-block">{{ Helper::formataValorMonetario($saldoRestante) }}</b>
                </p>
            </div>
        </div>
        <h6 class="h6-nexus-b">Dados do Recebimento</h6>
        @php 
            if($subEstagioRec == "NEW"){
                $valorRec = '';
                $observacao = '';
                $complemento = '';
                $admCCR = null;
                $bandCCR = null;
                $numDocCCR = '';
                $parcelaCCR = '';

            }else{
                $valorRec = $dadosValRec->recval_valor;
                $observacao = $dadosValRec->recval_obs;
                $complemento = $dadosValRec->recval_comp;
                $admCCR = $dadosValRec->recval_crt_adm;
                $bandCCR = $dadosValRec->recval_crt_ban;
                $numDocCCR = $dadosValRec->recval_crt_com;
                $parcelaCCR = $dadosValRec->recval_crt_prc;
            }
            $array_opt_adm = HelperArraySelect::arrayRazoesAdmCC(1, 1, $empresaREC, 'C');
            $array_opt_bandeira = HelperArraySelect::arrayBandeirasCartao(1, 1);
            $array_opt_parcela = HelperArraySelect::arrayQtdParcelaCartao(1,1,$empresaREC,$admCCR, $valorRec);
        @endphp
        <div class="row bloco-valor-cc">
            <x-adminlte-input name="valCCR" type="text" value="{{ $valorRec }}" placeholder="0,00" fgroup-class="col-md-12" igroup-size="sm">
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
        <div class="bloco-dados-cc">
            <div class="row">
                <x-adminlte-select name="admCCR" fgroup-class="col-md-12" igroup-size="sm">
                    <x-slot name="label">
                        Administradora <span style="color:red;">*</span>
                    </x-slot>
                    <x-adminlte-options :options="$array_opt_adm" empty-option="Selecione..." selected="{{$admCCR}}"/>
                </x-adminlte-select>
            </div>
            <div class="row">
                <x-adminlte-select name="bandeiraCCR" fgroup-class="col-md-12" igroup-size="sm">
                    <x-slot name="label">
                        Bandeira <span style="color:red;">*</span>
                    </x-slot>
                    <x-adminlte-options :options="$array_opt_bandeira" empty-option="Selecione..." selected="{{$bandCCR}}"/>
                </x-adminlte-select>
            </div>
            <div class="row">
                <x-adminlte-input name="numDocCCR" type="text" value="{{ $numDocCCR }}" placeholder="Número de Autorização" fgroup-class="col-md-12" igroup-size="sm">
                    <x-slot name="label">
                        Número do Comprovante <span style="color:red;">*</span>
                    </x-slot>
                </x-adminlte-input>
            </div>
            <div class="row">
                <x-adminlte-select name="parcelaCCR" fgroup-class="col-md-12" igroup-size="sm">
                    <x-slot name="label">
                        Parcelamento <span style="color:red;">*</span>
                    </x-slot>
                    <x-adminlte-options :options="$array_opt_parcela" empty-option="Selecione..." selected="{{$parcelaCCR}}"/>
                </x-adminlte-select>
            </div>
            <div class="row">
                <x-adminlte-input name="compCCR" label="Complemento" type="text" value="{{ $complemento }}" placeholder="Complemento" fgroup-class="col-md-12" igroup-size="sm"></x-adminlte-input>
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
        <input id="tipoValor" type="hidden" value="CCR" name="tipoValor">

        <x-slot name="footerSlot">
            <div style="text-align: center;">
                <a class="btn btn-nexus btn-prox-rec-ccr" title="Próximo">Próximo</a>
                <a class="btn btn-nexus btn-edit-rec-ccr" title="Editar Valor">Editar Valor</a>
                <x-adminlte-button class="btn-nexus btn-salvar-rec-ccr" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                <a class="btn btn-nexus" title="Voltar" href="{{route('recebimento.painelRecebimento',['empresa' => $empresaREC, 'cliente' => $clienteREC, 'idRecebimento' => $idRecebimento])}}">Voltar</a>
            </div>
        </x-slot>
    </x-adminlte-card>
</form>
@endif
<!-- ***** Final dos dados de Recebimento com cartão de Crédito - Bloco: REC_C_CREDITO ***** -->

<!-- ***** Início dos dados de Recebimento com Cartão de Débito - Bloco: REC_C_DEBITO ***** -->
@if($estagio_app == 'REC_C_DEBITO')
@if($subEstagioRec == "NEW")
<form method="post" action="{{ route('valorRecebimento.store') }}" id="form-rec-cdb" novalidate="novalidate">
    @csrf 
    @method('post')
@else
<form method="post" action="{{ route('valorRecebimento.update', ['valorRecebimento' => $dadosValRec]) }}" id="form-rec-ccr" novalidate="novalidate">
    @csrf 
    @method('put')
@endif
    <x-adminlte-card title="Recebimento com Cartão de Débito" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
        <div class="text-muted">
            <div class="row">
                <p class="text-sm col-md-3">Data do Recebimento
                    <b class="d-block">{{ date('d/m/Y') }}</b>
                </p>
                @if(Session::get('glo_recebimento_origem') == 'NFV')
                <p class="text-sm col-md-3">Qtd. de Notas
                    <b class="d-block">{{ $totalNF }}</b>
                </p>
                @elseif(Session::get('glo_recebimento_origem') == 'CCT')
                <p class="text-sm col-md-3">Qtd. de Contas
                    <b class="d-block">{{ $totalCCT }}</b>
                </p>
                @elseif(Session::get('glo_recebimento_origem') == 'DUP')
                <p class="text-sm col-md-3">Qtd. de Duplicatas
                    <b class="d-block">{{ $totalDUP }}</b>
                </p>
                @elseif(Session::get('glo_recebimento_origem') == 'OUT')
                <p class="text-sm col-md-3">Qtd. de Recebimento
                    <b class="d-block">{{ $totalOUT }}</b>
                </p>
                @endif
                <p class="text-sm col-md-3">Valor Total
                    <b class="d-block">{{ Helper::formataValorMonetario($valorTotalRec) }}</b>
                </p>
                <p class="text-sm col-md-3">Saldo Restante
                    <b class="d-block">{{ Helper::formataValorMonetario($saldoRestante) }}</b>
                </p>
            </div>
        </div>
        <h6 class="h6-nexus-b">Dados do Recebimento</h6>
        @php 
            if($subEstagioRec == "NEW"){
                $valorRec = '';
                $observacao = '';
                $complemento = '';
                $admCDB = null;
                $bandCDB = null;
                $numDocCDB = '';
            }else{
                $valorRec = $dadosValRec->recval_valor;
                $observacao = $dadosValRec->recval_obs;
                $complemento = $dadosValRec->recval_comp;
                $admCDB = $dadosValRec->recval_crt_adm;
                $bandCDB = $dadosValRec->recval_crt_ban;
                $numDocCDB = $dadosValRec->recval_crt_com;
            }
            $array_opt_adm = HelperArraySelect::arrayRazoesAdmCC(1, 1, $empresaREC, 'D');
            $array_opt_bandeira = HelperArraySelect::arrayBandeirasCartao(1, 1);
        @endphp
        <div class="row">
            <x-adminlte-select name="admCDB" fgroup-class="col-md-12" igroup-size="sm">
                <x-slot name="label">
                    Administradora <span style="color:red;">*</span>
                </x-slot>
                <x-adminlte-options :options="$array_opt_adm" empty-option="Selecione..." selected="{{$admCDB}}"/>
            </x-adminlte-select>
        </div>
        <div class="row">
            <x-adminlte-select name="bandeiraCDB" fgroup-class="col-md-12" igroup-size="sm">
                <x-slot name="label">
                    Bandeira <span style="color:red;">*</span>
                </x-slot>
                <x-adminlte-options :options="$array_opt_bandeira" empty-option="Selecione..." selected="{{$bandCDB}}"/>
            </x-adminlte-select>
        </div>
        <div class="row">
            <x-adminlte-input name="numDocCDB" type="text" value="{{ $numDocCDB }}" placeholder="Número de Autorização" fgroup-class="col-md-12" igroup-size="sm">
                <x-slot name="label">
                    Número do Comprovante <span style="color:red;">*</span>
                </x-slot>
            </x-adminlte-input>
        </div>
        <div class="row">
            <x-adminlte-input name="valCDB" type="text" value="{{ $valorRec }}" placeholder="0,00" fgroup-class="col-md-12" igroup-size="sm">
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
            <x-adminlte-input name="compCDB" label="Complemento" type="text" value="{{ $complemento }}" placeholder="Complemento" fgroup-class="col-md-12" igroup-size="sm"></x-adminlte-input>
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
        <input id="tipoValor" type="hidden" value="CDB" name="tipoValor">

        <x-slot name="footerSlot">
            <div style="text-align: center;">
                <x-adminlte-button class="btn-nexus" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                <a class="btn btn-nexus" title="Voltar" href="{{route('recebimento.painelRecebimento',['empresa' => $empresaREC, 'cliente' => $clienteREC, 'idRecebimento' => $idRecebimento])}}">Voltar</a>
            </div>
        </x-slot>
    </x-adminlte-card>
</form>
@endif
<!-- ***** Final dos dados de Recebimento em Cartão de Débito - Bloco: REC_C_DEBITO ***** -->

<!-- ***** Início do Filtro de Recebimento com Conta Corrente - Bloco: REC_FIL_C_CORRENTE ***** -->
@if($estagio_app == 'REC_FIL_C_CORRENTE')
<form method="get" action="{{ route('valorRecebimento.show', ['valorRecebimento' => 'CCT']) }}" id="form-rec-filtro-cct" novalidate="novalidate">
    @csrf 
    @method('get')
    @php
        $appOrigem = Session::get('glo_recebimento_origem');

        if ($appOrigem == 'CCT') {
            $array_opt = ['CB' => 'CB - Créditos Bancários a Classificar'];
        } else {
            $array_opt = [
                'AC' => 'AC - Adiantamento de Clientes',
                'DV' => 'DV - Devolução de Vendas'
            ];
        }
    @endphp
    <x-adminlte-card title="Filtro do Recebimento com Conta Corrente" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
        <div class="row"> 
            <x-adminlte-select name="tipoCCTFiltro" label="Tipo" fgroup-class="col-md-12" igroup-size="sm">
                <x-adminlte-options :options="$array_opt" empty-option="Selecione..." selected=""/>
            </x-adminlte-select>
        </div>
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
                <a class="btn btn-nexus" title="Voltar" href="{{route('recebimento.painelRecebimento',['empresa' => $empresaREC, 'cliente' => $clienteREC, 'idRecebimento' => $idRecebimento])}}">Voltar</a>
            </div>
        </x-slot>
    </x-adminlte-card>
</form>
@endif
<!-- ***** Final do Filtro de Recebimento com Conta Corrente - Bloco: REC_FIL_C_CORRENTE ***** -->

<!-- ***** Seleção da Conta do Recebimento com Conta Corrente - Bloco: REC_SEL_C_CORRENTE ***** -->
@if($estagio_app == 'REC_SEL_C_CORRENTE')
<x-adminlte-card title="Recebimento com Conta Corrente" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
    <div class="text-muted">
        <div class="row">
            <p class="text-sm col-md-3">Data do Recebimento
                <b class="d-block">{{ date('d/m/Y') }}</b>
            </p>
            @if(Session::get('glo_recebimento_origem') == 'NFV')
            <p class="text-sm col-md-3">Qtd. de Notas
                <b class="d-block">{{ $totalNF }}</b>
            </p>
            @elseif(Session::get('glo_recebimento_origem') == 'CCT')
            <p class="text-sm col-md-3">Qtd. de Contas
                <b class="d-block">{{ $totalCCT }}</b>
            </p>
            @elseif(Session::get('glo_recebimento_origem') == 'DUP')
            <p class="text-sm col-md-3">Qtd. de Duplicatas
                <b class="d-block">{{ $totalDUP }}</b>
            </p>
            @elseif(Session::get('glo_recebimento_origem') == 'OUT')
            <p class="text-sm col-md-3">Qtd. de Recebimento
                <b class="d-block">{{ $totalOUT }}</b>
            </p>
            @endif
            <p class="text-sm col-md-3">Valor Total
                <b class="d-block">{{ Helper::formataValorMonetario($valorTotalRec) }}</b>
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

                    $url = route('valorRecebimento.create', [
                        'tipoValor' => 'CCT',
                        'conta_num' => $conta->conta_num_conta,
                        'conta_tipo' => $conta->conta_tipo
                    ]);

                    $dadosTipCCT = DB::table('financeiro_tab_contas')->where('tabcon_codigo', $conta->conta_tipo)->first();
                    $dadosCli = DB::table('cadastro_clientes')->where('cliente_codigo', $clienteREC)->first();
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
            <a class="btn btn-nexus" title="Filtro" href="{{ route('valorRecebimento.show', ['valorRecebimento' => 'CCT', 'etapa' => 'FILTRO']) }}">
                <i class="fa-solid fa-magnifying-glass"></i> Filtro
            </a>
            <a class="btn btn-nexus" title="Voltar" href="{{route('recebimento.painelRecebimento',['empresa' => $empresaREC, 'cliente' => $clienteREC, 'idRecebimento' => $idRecebimento])}}">Voltar</a>
        </div>
    </x-slot>
</x-adminlte-card>
@endif
<!-- ***** Final dos dados da Seleção da Conta Corrente - Bloco: REC_SEL_C_CORRENTE ***** -->

<!-- ***** Início dos dados de Recebimento com Conta Corrente - Bloco: REC_CONTA_CORRENTE ***** -->
@if($estagio_app == 'REC_CONTA_CORRENTE')
@if($subEstagioRec == "NEW")
<form method="post" action="{{ route('valorRecebimento.store') }}" id="form-rec-cct" novalidate="novalidate">
    @csrf 
    @method('post')
@else
<form method="post" action="{{ route('valorRecebimento.update', ['valorRecebimento' => $dadosValRec]) }}" id="form-rec-cct" novalidate="novalidate">
    @csrf 
    @method('put')
@endif
    <x-adminlte-card title="Recebimento com Conta Corrente" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
        <div class="text-muted">
            <div class="row">
                <p class="text-sm col-md-3">Data do Recebimento
                    <b class="d-block">{{ date('d/m/Y') }}</b>
                </p>
                @if(Session::get('glo_recebimento_origem') == 'NFV')
                <p class="text-sm col-md-3">Qtd. de Notas
                    <b class="d-block">{{ $totalNF }}</b>
                </p>
                @elseif(Session::get('glo_recebimento_origem') == 'CCT')
                <p class="text-sm col-md-3">Qtd. de Contas
                    <b class="d-block">{{ $totalCCT }}</b>
                </p>
                @elseif(Session::get('glo_recebimento_origem') == 'DUP')
                <p class="text-sm col-md-3">Qtd. de Duplicatas
                    <b class="d-block">{{ $totalDUP }}</b>
                </p>
                @elseif(Session::get('glo_recebimento_origem') == 'OUT')
                <p class="text-sm col-md-3">Qtd. de Recebimento
                    <b class="d-block">{{ $totalOUT }}</b>
                </p>
                @endif
                <p class="text-sm col-md-3">Valor Total
                    <b class="d-block">{{ Helper::formataValorMonetario($valorTotalRec) }}</b>
                </p>
                <p class="text-sm col-md-3">Saldo Restante
                    <b class="d-block">{{ Helper::formataValorMonetario($saldoRestante) }}</b>
                </p>
            </div>
        </div>
        <h6 class="h6-nexus-b">Dados do Recebimento</h6>
        @php 
            $dadosCli = DB::table('cadastro_clientes')->where('cliente_codigo', $clienteREC)->first();
            $dadosTipCCT = DB::table('financeiro_tab_contas')->where('tabcon_codigo', $tipoCctSel)->first();
            $valorCCT = $dadosCCT->conta_saldo;

            if($subEstagioRec == "NEW"){
                $valorRec = '';
                $observacao = '';
                $saldoRestCCT = $valorCCT;
            }else{
                $valorRec = $dadosValRec->recval_valor;
                $observacao = $dadosValRec->recval_obs;
                $saldoRestCCT = $valorCCT - $dadosValRec->recval_valor;
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
            <x-adminlte-input name="valCCT" type="text" value="{{ $valorRec }}" placeholder="0,00" fgroup-class="col-md-12" igroup-size="sm">
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
        <input id="resCCT" type="hidden" value="{{$clienteREC}}" name="resCCT">
        <input id="numCCT" type="hidden" value="{{$contaCctSel}}" name="numCCT">
        <input id="valTotCCT" type="hidden" value="{{$valorCCT}}" name="valTotCCT">

        <x-slot name="footerSlot">
            <div style="text-align: center;">
                @if($subEstagioRec == "NEW")
                <a class="btn btn-nexus" title="Filtro" href="{{ route('valorRecebimento.show', ['valorRecebimento' => 'CCT', 'etapa' => 'FILTRO']) }}">
                    <i class="fa-solid fa-magnifying-glass"></i> Filtro
                </a>
                <a class="btn btn-nexus" title="Selecionar Conta" href="{{ route('valorRecebimento.show', ['valorRecebimento' => 'CCT', 'etapa' => 'SELECAO_VOLTAR']) }}">
                    <i class="fa-solid fa-list"></i> Selecionar Conta
                </a>
                @endif
                <x-adminlte-button class="btn-nexus" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                <a class="btn btn-nexus" title="Voltar" href="{{route('recebimento.painelRecebimento',['empresa' => $empresaREC, 'cliente' => $clienteREC, 'idRecebimento' => $idRecebimento])}}">Voltar</a>
            </div>
        </x-slot>
    </x-adminlte-card>
</form>
@endif
<!-- ***** Final dos dados de Recebimento com Conta Corrente - Bloco: REC_CONTA_CORRENTE ***** -->