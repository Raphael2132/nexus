<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Impressão do Orçamento</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">


    <style>
    .text-sm{
        margin-bottom: 0px;
    }

    body{
        font-size: 5pt !important;
    }

    table {
        width: 100%;
    }

    table th{
        text-align: center;
        background-color: #000;
        color: #fff;
    }

    .sub-table th {
        color: #495057;
        background-color: #e9ecef;
        border-color: #dee2e6;
    }

    .tabela-orcamento th{
        text-align: center;
    }
    
    .assinatura-cliente{
        text-align: center;
        margin-top: 75px;
    }
    .assinatura-consultor{
        text-align: center;
        border-top: 1px solid black;
    }
    .assinatura-meio{
        border-top: 1px solid black;
    }

    .tabela-orc-pre{
        text-align: center;
        margin-top: 5px;
    }

    .tabela-totais{
        text-align: center;
    }
</style>
</head>
<body>

<div class="tabela-orcamento" style="width: 100%">

    <table>
        <tbody>
            <tr>
                @php 
                    $logo_emp = "img/".$glo_os_dadosEmpresa[0]->empresa_cnpj."/".$glo_os_dadosEmpresa[0]->empresa_codigo."_logo.png";
                    $fullpath = public_path($logo_emp); 
                @endphp
                <td rowspan="4" style="width: 40%;text-align: center;"><img src="data:image/png;base64, <?php echo base64_encode(file_get_contents($fullpath)); ?>" style="max-width: 60%; max-height: 60%;" /></td>
                <td rowspan="4" style="width: 30%;">
                    <p class="text-sm">Endereço
                        <b class="d-block">{{$glo_os_dadosEmpresaEndereco[0]->endereco_logradouro.', '.$glo_os_dadosEmpresaEndereco[0]->endereco_numero}}</b>
                        <b class="d-block">{{Helper::mascaraCEP($glo_os_dadosEmpresaEndereco[0]->endereco_cep)}}</b>
                        <b class="d-block">{{$glo_os_dadosEmpresaEndereco[0]->endereco_bairro}}</b>
                        <b class="d-block">{{$glo_os_dadosEmpresaEndereco[0]->endereco_cidade.' - '.$glo_os_dadosEmpresaEndereco[0]->endereco_uf}}</b>
                    </p>
                    @php 
                        if(!empty($glo_os_dadosEmpresa[0]->empresa_tel_celular)){
                            $telCelular = Helper::mascaraTelCelular($glo_os_dadosEmpresa[0]->empresa_tel_celular);
                        }else{
                            $telCelular = "Não Cadastrado";
                        }

                        if(!empty($glo_os_dadosEmpresa[0]->empresa_tel_comercial)){
                            $telComercial = Helper::mascaraTelComercial($glo_os_dadosEmpresa[0]->empresa_tel_comercial);
                        }else{
                            $telComercial = "Não Cadastrado";
                        }

                        if(!empty($glo_os_dadosEmpresa[0]->empresa_cnpj)){
                            $cnpjEmpresa = Helper::mascaraCNPJ($glo_os_dadosEmpresa[0]->empresa_cnpj);
                        }else{
                            $cnpjEmpresa = "Não Cadastrado";
                        }

                        $dataConsultor = DB::table('users')->select('name')->where('usuario_codigo',$glo_os_dadosOS[0]->os_res_abr)->get();
                        $consultorAbertura = $glo_os_dadosOS[0]->os_res_abr.' - '.$dataConsultor[0]->name;

                        $dataHoraAbertura = Helper::formataDataHora($glo_os_dadosOS[0]->os_dha);

                    @endphp
                    <p class="text-sm">Telefone
                        <b class="d-block">{{$telCelular.' / '.$telComercial}}</b>
                    </p>
                    <p class="text-sm">Email
                        <b class="d-block">{{$glo_os_dadosEmpresa[0]->empresa_email}}</b>
                    </p>
                </td>
                <td rowspan="4" style="width: 30%;">
                    <p class="text-sm">Empresa
                        <b class="d-block">{{$glo_os_dadosEmpresa[0]->empresa_codigo.' - '.$glo_os_dadosEmpresa[0]->empresa_nome}}</b>
                    </p>
                    <p class="text-sm">CNPJ
                        <b class="d-block">{{$cnpjEmpresa}}</b>
                    </p>
                    <p class="text-sm">Inscrição Estadual
                        <b class="d-block">{{$glo_os_dadosEmpresa[0]->empresa_insc_estadual}}</b>
                    </p>
                    <p class="text-sm">Consultor Técnico
                        <b class="d-block">{{$consultorAbertura}}</b>
                    </p>
                    <p class="text-sm">Data e Hora da Abertura da OS
                        <b class="d-block">{{$dataHoraAbertura}}</b>
                    </p>
                </td>
            </tr>
        </tbody>
    </table> </div>
    <table class="table table-sm table-bordered tabela-orc-pre">
        <tbody>
            <tr>
            @php 
                $dataPreEnt = Helper::formataData($glo_os_dadosOS[0]->os_dpe);
                $horaPreEnt = Helper::formataHoraMinuto($glo_os_dadosOS[0]->os_hpe);

                $dataOrcamento = Helper::formataData($glo_os_dadosOS[0]->os_dt_orc);
            @endphp
                <td>
                    <p class="text-sm">Número da OS
                        <b class="d-block">{{str_pad($glo_os_dadosOS[0]->os_nos, 8, "0", STR_PAD_LEFT)}}</b>
                    </p>
                </td>
                <td>
                    <p class="text-sm">Data e Hora da Previsão de Entrega
                        <b class="d-block">{{$dataPreEnt.' '.$horaPreEnt}}</b>
                    </p>
                </td>
                <td>
                    <p class="text-sm">Número do Orçamento
                        <b class="d-block">{{str_pad($glo_os_dadosOS[0]->os_num_orc, 8, "0", STR_PAD_LEFT)}}</b>
                    </p>
                </td>
                <td>
                    <p class="text-sm">Data do Orçamento
                        <b class="d-block">{{$dataOrcamento}}</b>
                    </p>
                </td>
            </tr>
        </tbody>
    </table> 
    <table class="table table-sm table-bordered">
        <tbody>
            <tr>
                <th scope="col" colspan="3">Informações do Cliente</th>
            </tr>
            <tr>
                <td>
                    <p class="text-sm">Cliente
                        <b class="d-block">{{$glo_os_dadosCliente[0]->cliente_codigo.' - '.$glo_os_dadosCliente[0]->cliente_nome}}</b>
                    </p>
                </td>
                <td>
                    <p class="text-sm">CPF / CNPJ
                        <b class="d-block">@if($glo_os_dadosCliente[0]->cliente_tipo_pessoa == 'J') 
                                                {{Helper::mascaraCNPJ($glo_os_dadosCliente[0]->cliente_cpf_cnpj)}} 
                                            @else 
                                            {{Helper::mascaraCPF($glo_os_dadosCliente[0]->cliente_cpf_cnpj)}} 
                                            @endif</b>
                    </p>
                </td>
                <td>
                    <p class="text-sm">RG
                        <b class="d-block">@if($glo_os_dadosCliente[0]->cliente_tipo_pessoa == 'F' && !empty($glo_os_dadosCliente[0]->cliente_rg)) 
                                                {{Helper::mascaraRG($glo_os_dadosCliente[0]->cliente_rg)}} 
                                            @endif</b>
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="text-sm">Endereço
                        <b class="d-block">{{$glo_os_dadosClienteEndereco[0]->endereco_logradouro.', '.$glo_os_dadosClienteEndereco[0]->endereco_numero}}</b>
                    </p>
                </td>
                <td>
                    <p class="text-sm">Complemento
                        <b class="d-block">{{$glo_os_dadosClienteEndereco[0]->endereco_complemento}}</b>
                    </p>
                </td>
                <td>
                    <p class="text-sm">Bairro
                        <b class="d-block">{{$glo_os_dadosClienteEndereco[0]->endereco_bairro}}</b>
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="text-sm">CEP
                        <b class="d-block">{{Helper::mascaraCEP($glo_os_dadosClienteEndereco[0]->endereco_cep)}}</b>
                    </p>
                </td>
                <td>
                    <p class="text-sm">Cidade
                        <b class="d-block">{{$glo_os_dadosClienteEndereco[0]->endereco_cidade}}</b>
                    </p>
                </td>
                <td>
                    <p class="text-sm">UF
                        <b class="d-block">{{$glo_os_dadosClienteEndereco[0]->endereco_uf}}</b>
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="text-sm">Telefone Celular
                        <b class="d-block">@if(!empty($glo_os_dadosCliente[0]->cliente_tel_celular)) {{Helper::mascaraTelCelular($glo_os_dadosCliente[0]->cliente_tel_celular)}} @endif</b>
                    </p>
                </td>
                <td>
                    <p class="text-sm">Telefone Comercial
                        <b class="d-block">@if(!empty($glo_os_dadosCliente[0]->cliente_tel_comercial)) {{Helper::mascaraTelComercial($glo_os_dadosCliente[0]->cliente_tel_comercial)}} @endif</b>
                    </p>
                </td>
                <td>
                    <p class="text-sm">Telefone Residencial
                        <b class="d-block">@if(!empty($glo_os_dadosCliente[0]->cliente_tel_residencial)) {{Helper::mascaraTelResidencial($glo_os_dadosCliente[0]->cliente_tel_residencial)}} @endif</b>
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <p class="text-sm">Email
                        <b class="d-block">{{$glo_os_dadosCliente[0]->cliente_email}}</b>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="col" colspan="3">Solicitações de Servicos</th>
            </tr>
            <tr>
                <td colspan="3">
                    <table class="table table-sm table-bordered sub-table" style="margin: 0px;">
                        <tbody>
                            <tr>
                                <th scope="col">Item</th>
                                <th scope="col">Etapa</th>
                                <th scope="col">Descrição</th>
                                <th scope="col">Área</th>
                                <th scope="col">Setor</th>
                                <th scope="col">Tipo de Serviço</th>
                                <th scope="col">Valor</th>
                            </tr>
                            @foreach($glo_os_dadosRequisicoes as $requisicao)
                            <tr>
                                @php 
                                    $dataEtapa = DB::table('lancamento_srv_etapa_atendimentos')->select('eat_nom')->where('eat_emp',$glo_os_empresa)->where('eat_cod',$requisicao->req_eat)->get();
                                    $etapa = $requisicao->req_eat.' - '.$dataEtapa[0]->eat_nom;

                                    $dataArea = DB::table('parametros_sistema_areas')->select('area_desc')->where('area_codigo',$requisicao->req_are)->get();
                                    $area = $requisicao->req_are.' - '.$dataArea[0]->area_desc;

                                    $dataSet = DB::table('parametros_srv_setores')->select('setor_desc')->where('setor_empresa',$glo_os_empresa)->where('setor_codigo',$requisicao->req_set)->where('setor_area',$requisicao->req_are)->get();
                                    $setor = $requisicao->req_set.' - '.$dataSet[0]->setor_desc;

                                    $dataTOS = DB::table('lancamento_srv_tipo_servicos')->select('tipsrv_nom')->where('tipsrv_emp', $glo_os_empresa)->where('tipsrv_are', $requisicao->req_are)->where('tipsrv_cod', $requisicao->req_tos)->get();
                                    $tos = $requisicao->req_tos.' - '.$dataTOS[0]->tipsrv_nom;
                                @endphp
                                <td>{{$requisicao->req_seq}}</td>
                                <td>{{$etapa}}</td>
                                <td>{{$requisicao->req_dsc}}</td>
                                <td>{{$area}}</td>
                                <td>{{$setor}}</td>
                                <td>{{$tos}}</td>
                                <td>{{Helper::formataValorMonetario($requisicao->req_vlr)}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <th scope="col" colspan="3">Serviços de Mão de Obra</th>
            </tr>
            <tr>
                <td colspan="3">
                    <table class="table table-sm table-bordered sub-table" style="margin: 0px;">
                        <tbody>
                            <tr>
                                <th scope="col">Requisição</th>
                                <th scope="col">Item</th>
                                <th scope="col">Código Serviço</th>
                                <th scope="col">Descrição</th>
                                <th scope="col">Tipo</th>
                                <th scope="col">Qtd. Horas</th>
                                <th scope="col">Val. Hora</th>
                                <th scope="col">Valor Total</th>
                            </tr>
                            @foreach($glo_os_dadosServicos as $servico)
                            <tr>
                                @php 
                                    if($servico->srv_ths == 'F'){
                                        $tipoHR = 'Valor Fixo';
                                    }elseif($servico->srv_ths == 'P'){
                                        $tipoHR = 'Hora Padrão';
                                    }elseif($servico->srv_ths == 'T'){
                                        $tipoHR = 'Terceiros';
                                    }elseif($servico->srv_ths == 'R'){
                                        $tipoHR = 'Hora Real';
                                    }else{
                                        $tipoHR = 'Hora Informada';
                                    }
                                @endphp
                                <td>{{$servico->srv_req}}</td>
                                <td>{{$servico->srv_seq}}</td>
                                <td>{{$servico->srv_tmo}}</td>
                                <td>{{$servico->srv_dsc}}</td>
                                <td>{{$tipoHR}}</td>
                                <td>{{$servico->srv_qhr}}</td>
                                <td>{{Helper::formataValorMonetario($servico->srv_vhr)}}</td>
                                <td>{{Helper::formataValorMonetario($servico->srv_vts)}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table> 
    <table class="table table-sm table-bordered tabela-totais">
        <tbody>
            <tr>
                <th scope="col" colspan="4">Totais da OS</th>
            </tr>
            <tr>
                <td>
                    <p class="text-sm">Valor Total Bruto
                        <b class="d-block">{{Helper::formataValorMonetario($glo_os_dadosOS[0]->os_vos)}}</b>
                    </p>
                </td>
                <td>
                    <p class="text-sm">Descontos
                        <b class="d-block">{{Helper::formataValorMonetario($glo_os_dadosOS[0]->os_val_des)}}</b>
                    </p>
                </td>
                <td>
                    <p class="text-sm">Valor Total de Serviços
                        <b class="d-block">{{Helper::formataValorMonetario($glo_os_dadosOS[0]->os_vls)}}</b>
                    </p>
                </td>
                <td>
                    <p class="text-sm">Valor Total Liquido
                        <b class="d-block">{{Helper::formataValorMonetario($glo_os_dadosOS[0]->os_vlt)}}</b>
                    </p>
                </td>
            </tr>
        </tbody>
    </table> 
    <table class="table table-sm table-bordered">
        <tbody>
            <tr>
                <th scope="col">Informações Adicionais</th>
            </tr>
            <tr>
                <td>O cliente reconhece ter conhecimento prévio das condições gerais da realização dos serviços solicitados e autoriza a realização dos serviços constantes desta Ordem de Serviço / Orçamento e a Emissão da respectiva Nota Fiscal para pagamento</td>
            </tr>
        </tbody>
    </table> 
    <div style="margin-top: 75px;width: 100%;">
        <div style="width: 15%;float:left"></div>
        <div class="assinatura-consultor" style="width: 70%; float:left">
            <p class="text-sm">Assinatura Consultor</p>
        </div>
        <div style="width: 15%;float:left"></div>
    </div>
    <div class="assinatura-cliente">
        <div style="width: 15%;float:left"></div>
        <div class="assinatura-meio" style="width: 70%; float:left">
            <p class="text-sm">Assinatura do Cliente ou Responsável Autorizado</p>
        </div>
        <div style="width: 15%;float:left"></div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>


</body>
</html>