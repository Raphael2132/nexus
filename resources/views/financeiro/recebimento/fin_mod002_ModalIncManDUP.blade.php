<!-- Cabeçalho do Modal -->
<div class="text-muted">
    @php
        $empresaFormat = HelperFormatSelect::formataEmpresaCodigoNome($dadosCRC->conrec_empresa);
        $clienteFormat = HelperFormatSelect::formataClientes($dadosCRC->conrec_cliente);

        $codigoFormatado = str_pad($dadosCRC->conrec_codigo, 8, '0', STR_PAD_LEFT);
        $sequenciaFormatada = str_pad($dadosCRC->conrec_sequencia, 2, '0', STR_PAD_LEFT);
        $concatenado = $codigoFormatado . '/' . $sequenciaFormatada;

        $valorAberto = $dadosCRC->conrec_val_dup - $dadosCRC->conrec_val_pag;

        $tipoCob = DB::table('parametros_fin_tipo_cobrancas')->where('tipcob_codigo', $dadosCRC->conrec_tipo_cob)->first();
        $origem = DB::table('parametros_sis_origens')->where('origem_codigo', $dadosCRC->conrec_origem)->first();

        if(!empty($dadosCRC->conrec_portador)){
            $portador = DB::table('financeiro_razoes')->where('razao_codigo', $dadosCRC->conrec_portador)->first();

            $codPort = $dadosCRC->conrec_portador.' - '.$portador->razao_nome;
        }else{
            $codPort = '';
        }

        $calculo = HelperFinanceiro::calculaMoraDiaAtrasoDuplicata($dadosCRC->conrec_val_dup, $dadosCRC->conrec_val_pag, $dadosCRC->conrec_val_mora, $dadosCRC->conrec_dt_vencimento);
        $valorRec = $calculo['saldo'] + $calculo['mora_total'];

        $diaAtraso = $calculo['dias_atraso'];
        $moraDiaria = $dadosCRC->conrec_val_mora;
        
        if($origemAPP == 'INC'){

            $tipoOpr = 'MORA';
            $tipoDesc = 'FIN';
            $valorReceber = Helper::formataValorMonetario($valorAberto);
            $valorTotalReceber = Helper::formataValorMonetario($valorRec);
            $moraTotal = Helper::formataValorMonetario($calculo['mora_total']);
            
            $valDesconto = '';
            $valorPIS = '';
            $valorCOFINS = '';
            $valorCSLL = '';
            $valorIRRF = '';
            $valorIRRFNS = '';
            $valorISSQN = '';
            $valorTotImp = '';

        }else{

            $valorPIS = '';
            $valorCOFINS = '';
            $valorCSLL = '';
            $valorIRRF = '';
            $valorIRRFNS = '';
            $valorISSQN = '';
            $valorTotImp = '';
            $valDesconto = '';
            $tipoDesc = 'FIN';
            $moraTotal = '';

            $valorReceber = Helper::formataValorMonetario($dadosREC->recdup_vlr_bxa);

            if($dadosREC->recdup_tip_opr == 'M'){

                $tipoOpr = 'MORA';

                $valorTotalReceber = $dadosREC->recdup_vlr_bxa + $dadosREC->recdup_vlr_jmt;
                $valorTotalReceber = Helper::formataValorMonetario($valorTotalReceber);

                $moraTotal = Helper::formataValorMonetario($dadosREC->recdup_vlr_jmt);

            }else{

                $tipoOpr = 'DESC';
                
                $valorTotalReceber = $dadosREC->recdup_vlr_bxa - $dadosREC->recdup_vlr_des;
                $valorTotalReceber = Helper::formataValorMonetario($valorTotalReceber);
                $valDesconto = Helper::formataValorMonetario($dadosREC->recdup_vlr_des);

                if($dadosREC->recdup_tip_opr == 'F'){

                    $tipoDesc = 'FIN';

                }else{

                    $tipoDesc = 'RET';

                    $valorPIS = Helper::formataValorMonetario($dadosREC->recdup_vlr_pis);
                    $valorCOFINS = Helper::formataValorMonetario($dadosREC->recdup_vlr_cofins);
                    $valorCSLL = Helper::formataValorMonetario($dadosREC->recdup_vlr_csll);
                    $valorIRRF = Helper::formataValorMonetario($dadosREC->recdup_vlr_irrf);
                    $valorIRRFNS = Helper::formataValorMonetario($dadosREC->recdup_vlr_irrf_ns);
                    $valorISSQN = Helper::formataValorMonetario($dadosREC->recdup_vlr_issqn);

                    $valorTotImp = $dadosREC->recdup_vlr_pis + $dadosREC->recdup_vlr_cofins + $dadosREC->recdup_vlr_csll + $dadosREC->recdup_vlr_irrf + $dadosREC->recdup_vlr_irrf_ns + $dadosREC->recdup_vlr_issqn;
                    $valorTotImp = Helper::formataValorMonetario($valorTotImp);
                }
            }
        }

    @endphp
    
    <!-- Campos escondidos para o request -->
    <input id="numDUP" type="hidden" value="{{$dadosCRC->conrec_codigo}}" name="numDUP">
    <input id="seqDUP" type="hidden" value="{{$dadosCRC->conrec_sequencia}}" name="seqDUP">

    <div class="row">
        <p class="text-sm col-md-4">Emitente
            <b class="d-block">{{ $empresaFormat }}</b>
        </p>
        <p class="text-sm col-md-4">Cliente
            <b class="d-block">{{ $clienteFormat }}</b>
        </p>
        <p class="text-sm col-md-4">Data de Emissão
            <b class="d-block">{{ Helper::formataData($dadosCRC->conrec_dt_emissao) }}</b>
        </p>
    </div>
    <div class="row">
        <p class="text-sm col-md-4">Duplicata
            <b class="d-block">{{ $concatenado }}</b>
        </p>
        <p class="text-sm col-md-4">Situação
            <b class="d-block">Aberta</b>
        </p>
        <p class="text-sm col-md-4">Data de Vencimento
            <b class="d-block">{{ Helper::formataData($dadosCRC->conrec_dt_vencimento) }}</b>
        </p>
    </div>
    <div class="row">
        <p class="text-sm col-md-4">Tipo de Cobrança
            <b class="d-block">{{ $tipoCob->tipcob_codigo.' - '.$tipoCob->tipcob_descricao }}</b>
        </p>
        <p class="text-sm col-md-4">Portador
            <b class="d-block">{{ $codPort }}</b>
        </p>
        <p class="text-sm col-md-4">Origem
            <b class="d-block">{{ $origem->origem_codigo.' - '.$origem->origem_desc }}</b>
        </p>
    </div>
    <div class="row">
        <p class="text-sm col-md-4">Valor da Duplicata
            <b class="d-block">{{ Helper::formataValorMonetario($dadosCRC->conrec_val_dup) }}</b>
        </p>
        <p class="text-sm col-md-4">Valor Pago
            <b class="d-block">{{ Helper::formataValorMonetario($dadosCRC->conrec_val_pag) }}</b>
        </p>
        <p class="text-sm col-md-4">Saldo Restante
            <b class="d-block">{{ Helper::formataValorMonetario($valorAberto) }}</b>
        </p>
    </div>

    <div class="row d-flex justify-content-center">  
        <div class="bloco-valores col-md-4">  
            <x-adminlte-card title="Valores do Recebimento" theme="" theme-mode="outline" header-class="card-outline-nexus">
               
                <div class="row">
                    <x-adminlte-input name="saldoAbe" label="Saldo em Aberto" type="text" value="" placeholder="0,00" fgroup-class="col-md-12" igroup-size="sm" value="{{ Helper::formataValorMonetario($valorAberto) }}" disabled>
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-brazilian-real-sign"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                         
                <div class="row">
                    <x-adminlte-input name="valRec" type="text" value="" placeholder="0,00" fgroup-class="col-md-12" igroup-size="sm" value="{{ $valorReceber }}">
                        <x-slot name="label">
                            Valor a Receber <span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-brazilian-real-sign"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>

                <div class="row">
                    <x-adminlte-input name="valTotRec" label="Valor Total a Receber" type="text" value="" placeholder="0,00" fgroup-class="col-md-12" igroup-size="sm" value="{{$valorTotalReceber}}" disabled>
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-brazilian-real-sign"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>

            </x-adminlte-card>
        </div>
        <div class="bloco-juros-desc col-md-4">  
            <x-adminlte-card title="Juros / Desconto da Duplicata" theme="" theme-mode="outline" header-class="card-outline-nexus">

                <div class="row">
                    <x-adminlte-select name="tipoOpr" label="Tipo da Operação" fgroup-class="col-md-12" igroup-size="sm">
                        <x-adminlte-options :options="['MORA' => 'Mora' , 'DESC' => 'Desconto']" selected="{{ $tipoOpr }}"/>
                    </x-adminlte-select>
                </div>

                <div class="dados-mora">
                    <div class="row">
                        <x-adminlte-input name="moraDia" type="text" value="" label="Mora Diaria" placeholder="0,00" fgroup-class="col-md-6" igroup-size="sm" value="{{ Helper::formataValorMonetario($moraDiaria) }}" disabled></x-adminlte-input>
                        <x-adminlte-input name="diaAtraso" type="text" value="" label="Dias em Atraso" placeholder="0,00" fgroup-class="col-md-6" igroup-size="sm" value="{{ $diaAtraso }}" disabled></x-adminlte-input>
                    </div>

                    <div class="row">
                        <x-adminlte-input name="jurMoraRec" label="Valor de Juros a Receber" type="text" value="" placeholder="0,00" fgroup-class="col-md-12" igroup-size="sm" value="{{$moraTotal}}">
                            <x-slot name="prependSlot">
                                <div class="input-group-text x-slot-nexus">
                                    <i class="fa-solid fa-brazilian-real-sign"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                    </div>
                </div>

                <div class="dados-desconto">
                    <div class="row">
                        <x-adminlte-select name="tipoDesc" label="Tipo de Desconto" fgroup-class="col-md-12" igroup-size="sm">
                            <x-adminlte-options :options="['FIN' => 'Financeiro' , 'RET' => 'Retenção']" selected="{{ $tipoDesc }}"/>
                        </x-adminlte-select>
                    </div>

                    <div class="row">
                        <x-adminlte-input name="valDesc" type="text" value="" placeholder="0,00" fgroup-class="col-md-12" igroup-size="sm" value="{{ $valDesconto }}">
                            <x-slot name="label">
                                Valor de Desconto <span style="color:red;">*</span>
                            </x-slot>
                            <x-slot name="prependSlot">
                                <div class="input-group-text x-slot-nexus">
                                    <i class="fa-solid fa-brazilian-real-sign"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                    </div>
                </div>
            </x-adminlte-card>
        </div>
        <div class="bloco-impostos col-md-4">  
            <x-adminlte-card title="Impostos Retidos" theme="" theme-mode="outline" header-class="card-outline-nexus">
                <div class="row">
                    <x-adminlte-input name="valPIS" label="PIS" type="text" value="" placeholder="0,00" fgroup-class="col-md-6" igroup-size="sm" value="{{ $valorPIS }}">
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-brazilian-real-sign"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <x-adminlte-input name="valCOFINS" label="COFINS" type="text" value="" placeholder="0,00" fgroup-class="col-md-6" igroup-size="sm" value="{{ $valorCOFINS }}">
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-brazilian-real-sign"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="row">
                    <x-adminlte-input name="valCSLL" label="CSLL" type="text" value="" placeholder="0,00" fgroup-class="col-md-6" igroup-size="sm" value="{{ $valorCSLL }}">
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-brazilian-real-sign"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                    
                    <x-adminlte-input name="valISSQN" label="ISSQN" type="text" value="" placeholder="0,00" fgroup-class="col-md-6" igroup-size="sm" value="{{ $valorISSQN }}">
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-brazilian-real-sign"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="row">
                    <x-adminlte-input name="valIRRF" label="IRRF" type="text" value="" placeholder="0,00" fgroup-class="col-md-6" igroup-size="sm" value="{{ $valorIRRF }}">
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-brazilian-real-sign"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                    
                    <x-adminlte-input name="valIRRFNS" label="IRRF NFS-e" type="text" value="" placeholder="0,00" fgroup-class="col-md-6" igroup-size="sm" value="{{ $valorIRRFNS }}">
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-brazilian-real-sign"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="row">
                    <x-adminlte-input name="valTotImp" label="Valor Total de Impostos" type="text" value="" placeholder="0,00" fgroup-class="col-md-6" igroup-size="sm" value="{{ $valorTotImp }}" disabled>
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-brazilian-real-sign"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
            </x-adminlte-card>
        </div>
    </div>
</div>