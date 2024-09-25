<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Impressão da NFS-e</title>

    <style type="text/css">
        @media print {
            @page {
                margin-left: 15mm;
                margin-right: 15mm;
            }

            footer {
                page-break-after: always;
            }
        }

        * {
            margin: 0;
        }

        .ui-widget-content {
            border: none !important;
        }

        .nfe-square {
            margin: 0 auto 2cm;
            box-sizing: border-box;
            width: 2cm;
            height: 1cm;
            border: 1px solid #000;
        }

        .nfeArea.page {
            width: 18cm;
            position: relative;
            font-family: "Times New Roman", serif;
            color: #000;
            margin: 0 auto;
            overflow: hidden;
        }

        .nfeArea .font-11 {
            font-size: 11pt;
        }

        .nfeArea .font-12 {
            font-size: 12pt;
        }

        .nfeArea .font-8 {
            font-size: 8pt;
        }

        .nfeArea .bold {
            font-weight: bold;
        }
        /* == TABELA == */
        .nfeArea .area-name {
            font-family: "Times New Roman", serif;
            color: #000;
            font-weight: bold;
            margin: 5px 0 0;
            font-size: 6pt;
            text-transform: uppercase;
        }

        .nfeArea .txt-upper {
            text-transform: uppercase;
        }

        .nfeArea .txt-center {
            text-align: center;
        }

        .nfeArea .txt-right {
            text-align: right;
        }

        .nfeArea .nf-label {
            text-transform: uppercase;
            margin-bottom: 3px;
            display: block;
        }

        .nfeArea .nf-label.label-small {
            letter-spacing: -0.5px;
            font-size: 4pt;
        }

        .nfeArea .info {
            font-weight: bold;
            font-size: 8pt;
            display: block;
            line-height: 1em;
        }

        .nfeArea table {
            font-family: "Times New Roman", serif;
            color: #000;
            font-size: 5pt;
            border-collapse: collapse;
            width: 100%;
            border-color: #000;
            border-radius: 5px;
        }

        .nfeArea .no-top {
            margin-top: -1px;
        }

        .nfeArea .mt-table {
            margin-top: 3px;
        }

        .nfeArea .valign-middle {
            vertical-align: middle;
        }

        .nfeArea td {
            vertical-align: top;
            box-sizing: border-box;
            overflow: hidden;
            border-color: #000;
            padding: 1px;
            height: 5mm;
        }

        .nfeArea .tserie {
            width: 32.2mm;
            vertical-align: middle;
            font-size: 8pt;
            font-weight: bold;
        }

        .nfeArea .tserie span {
            display: block;
        }

        .nfeArea .tserie h3 {
            display: inline-block;
        }

        .nfeArea .entradaSaida .legenda {
            text-align: left;
            margin-left: 2mm;
            display: block;
        }

            .nfeArea .entradaSaida .legenda span {
                display: block;
            }

        .nfeArea .entradaSaida .identificacao {
            float: right;
            margin-right: 2mm;
            border: 1px solid black;
            width: 5mm;
            height: 5mm;
            text-align: center;
            padding-top: 0;
            line-height: 5mm;
        }

        .nfeArea .hr-dashed {
            border: none;
            border-top: 1px dashed #444;
            margin: 5px 0;
        }

        .nfeArea .client_logo {
            height: 27.5mm;
            width: 28mm;
            margin: 0.5mm;
        }

        .nfeArea .title {
            font-size: 10pt;
            margin-bottom: 2mm;
        }

        .nfeArea .txtc {
            text-align: center;
        }

        .nfeArea .pd-0 {
            padding: 0;
        }

        .nfeArea .mb2 {
            margin-bottom: 2mm;
        }

        .nfeArea table table {
            margin: -1pt;
            width: 100.5%;
        }

        .nfeArea .wrapper-table {
            margin-bottom: 2pt;
        }

        .nfeArea .wrapper-table table {
            margin-bottom: 0;
        }

        .nfeArea .wrapper-table table + table {
            margin-top: -1px;
        }

        .nfeArea .boxImposto {
            table-layout: fixed;
        }

        .nfeArea .boxImposto td {
            width: 11.11%;
        }

        .nfeArea .boxImposto .nf-label {
            font-size: 5pt;
        }

        .nfeArea .boxImposto .info {
            text-align: right;
        }

        .nfeArea .wrapper-border {
            border: 1px solid #000;
            border-width: 0 1px 1px;
            height: 55mm;
        }

        .nfeArea .wrapper-border table {
            margin: 0 -1px;
            width: 100.4%;
        }

        .nfeArea .content-spacer {
            display: block;
            height: 10px;
        }

        .nfeArea .titles th {
            padding: 3px 0;
        }

        .nfeArea .listProdutoServico td {
            padding: 0;
        }

        .nfeArea .codigo {
            display: block;
            text-align: center;
            margin-top: 5px;
        }

        .nfeArea .boxProdutoServico tr td:first-child {
            border-left: none;
        }

        .nfeArea .boxProdutoServico td {
            font-size: 6pt;
            height: auto;
        }

        .nfeArea .boxFatura span {
            display: block;
        }

        .nfeArea .boxFatura td {
            border: 1px solid #000;
        }

        .nfeArea .freteConta .border {
            width: 5mm;
            height: 5mm;
            float: right;
            text-align: center;
            line-height: 5mm;
            border: 1px solid black;
        }

        .nfeArea .freteConta .info {
            line-height: 5mm;
        }

        .page .boxFields td p {
            font-family: "Times New Roman", serif;
            font-size: 5pt;
            line-height: 1.2em;
            color: #000;
        }

        .nfeArea .imgCanceled {
            position: absolute;
            top: 75mm;
            left: 30mm;
            z-index: 3;
            opacity: 0.8;
            display: none;
        }

        .nfeArea.invoiceCanceled .imgCanceled {
            display: block;
        }

        .nfeArea .imgNull {
            position: absolute;
            top: 75mm;
            left: 20mm;
            z-index: 3;
            opacity: 0.8;
            display: none;
        }

        .nfeArea.invoiceNull .imgNull {
            display: block;
        }

        .nfeArea.invoiceCancelNull .imgCanceled {
            top: 100mm;
            left: 35mm;
            display: block;
        }

        .nfeArea.invoiceCancelNull .imgNull {
            top: 65mm;
            left: 15mm;
            display: block;
        }

        .nfeArea .page-break {
            page-break-before: always;
        }

        .nfeArea .block {
            display: block;
        }

        .label-mktup {
            font-family: Arial !important;
            font-size: 8px !important;
            padding-top: 8px !important;
        }

        .tabela {

            border-top: 0px;
        }
    </style>
</head>
<body>

@php 
    $dadosNFS = DB::table('faturamento_nfs')->where('nfs_emp',$empresa)->where('nfs_nfhdr_num',$numControle)->get();
    $dadosSrvNFS = DB::table('faturamento_nfs_servicos')->where('nfssrv_emp',$empresa)->where('nfssrv_num',$numControle)->orderby('nfssrv_req')->orderby('nfssrv_seq')->get();
    $dadosXmlNfsEnv = DB::table('faturamento_nfs_xml_envios')->where('nfsenv_emp',$empresa)->where('nfsenv_num',$dadosNFS[0]->nfs_nrps)->get();
    $dadosEmp = DB::table('cadastro_empresas')->where('empresa_codigo',$empresa)->get();
    $dadosEmpEnd = DB::table('cadastro_empresa_enderecos')->where('endereco_empresa_codigo',$empresa)->get();
    $dadosCodSrv = DB::table('parametros_sis_servicos')->where('servico_codigo',$dadosNFS[0]->nfs_cod_srv)->get();
    $dadosConexao = DB::table('parametros_fat_nfs')->where('parnfs_empresa',$empresa)->get();
    $dadosParSrvEmp = DB::table('parametros_srv_empresas')->where('parsrv_emp',$empresa)->get();

    //O provedor é numeração fixa para todos os clientes, verifica qual provedor da Empresa e gera caminho da img
    if($dadosConexao[0]->parnfs_provedor == 1){
        $pathImgPrefeitura = "img/prefeitura/Brasao_SaoJoaoDaBoaVista.png";
    }else{
        $pathImgPrefeitura = '';
    }

    $pathImgEmpresa = $dadosEmp[0]->empresa_cnpj."/file/img/".$dadosEmp[0]->empresa_codigo."_logo.png";

    if(!empty($dadosEmp[0]->empresa_tel_comercial)){
        $telEmp = Helper::mascaraTelComercial($dadosEmp[0]->empresa_tel_comercial);
    }else if(!empty($dadosEmp[0]->empresa_tel_celular)){
        $telEmp = Helper::mascaraTelCelular($dadosEmp[0]->empresa_tel_celular);
    }else{
        $telEmp = '';
    }

    if($dadosNFS[0]->nfs_tip_tom == 'J'){
        $cpfCnpjTom = Helper::mascaraCNPJ($dadosNFS[0]->nfs_cpf_cnpj_tom);
    }else{
        $cpfCnpjTom = Helper::mascaraCPF($dadosNFS[0]->nfs_cpf_cnpj_tom);
    }

    if($dadosNFS[0]->nfs_tip_tom == 'J'){
        if(!empty($dadosNFS[0]->nfs_tel_com_tom)){
            $telTom = Helper::mascaraTelComercial($dadosNFS[0]->nfs_tel_com_tom);
        }else if(!empty($dadosNFS[0]->nfs_tel_cel_tom)){
            $telTom = Helper::mascaraTelCelular($dadosNFS[0]->nfs_tel_cel_tom);
        }else{
            $telTom = '';
        }
    }else{
        if(!empty($dadosNFS[0]->nfs_tel_cel_tom)){
            $telTom = Helper::mascaraTelCelular($dadosNFS[0]->nfs_tel_cel_tom);
        }else if(!empty($dadosNFS[0]->nfs_tel_res_tom)){
            $telTom = Helper::mascaraTelResidencial($dadosNFS[0]->nfs_tel_res_tom);
        }else if(!empty($dadosNFS[0]->nfs_tel_com_tom)){
            $telTom = Helper::mascaraTelComercial($dadosNFS[0]->nfs_tel_com_tom);
        }else{
            $telTom = '';
        }
    }

    $seq = 0;

    if($dadosNFS[0]->nfs_iss_ret == '1'){
        $vlrIssRet = $dadosNFS[0]->nfs_vlr_iss;
        $vlrIss = 0;
    }else{
        $vlrIssRet = 0;
        $vlrIss = $dadosNFS[0]->nfs_vlr_iss;
    }

    //Aqui era para vir do retorno da prefeitura mas em São João não existe a informação assim por hora fica branco
    if($dadosConexao[0]->parnfs_provedor == 1){
        $outInfo = '';
    }else{
        $outInfo = '';
    }

@endphp

<div class="page nfeArea">
    <div class="boxFields" style="padding-top: 20px;">
        <table cellpadding="0" cellspacing="0" class="boxDestinatario" border="1">
            <tbody>
                <tr>
                    <td class="pd-0">
                        <table cellpadding="0" cellspacing="0" border="1">
                            <tbody>
                                <tr>
                                    @php 
                                        $fullpath = public_path($pathImgPrefeitura); 
                                    @endphp
                                    <td rowspan="3" style="width: 75%; text-align: center; vertical-align: middle; height: 25mm;">
                                        <table style="width: 100%; border-collapse: collapse; border: none;">
                                            <tr style="border: none;">
                                                <!-- Bloco da Imagem -->
                                                <td style="width: 30%; text-align: center; vertical-align: middle; border: none;">
                                                    <img style="width:120px; height: auto;" src="data:image/png;base64, <?php echo base64_encode(file_get_contents($fullpath)); ?>" />
                                                </td>
                                                <!-- Bloco do Texto -->
                                                <td style="width: 70%; text-align: center; vertical-align: middle; border: none;">
                                                    <span class="font-11" style="display: block;">Prefeitura Municipal de {{$dadosEmpEnd[0]->endereco_cidade}}</span>
                                                    <span class="font-12" style="display: block; font-weight: bold;">Secretaria Municipal de Nota Fiscal de Serviços Eletrônica</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="txt-upper" style="width: 25%;">
                                        <span class="nf-label">Número da Nota</span>
                                        <span class="info">{{str_pad($dadosNFS[0]->nfs_nnfs, 9, "0", STR_PAD_LEFT)}}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="nf-label">Data e Hora da Emissão</span>
                                        <span class="info">{{Helper::formataDataHora($dadosXmlNfsEnv[0]->nfsenv_dt_atu)}}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="nf-label">Código de Verificação</span>
                                        <span class="info">{{$dadosXmlNfsEnv[0]->nfsenv_cod_ver}}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="pd-0">
                        <table cellpadding="0" cellspacing="0" style="margin-bottom: -1px;" border="1">
                            <tbody>
                                <tr>
                                    <td style="width: 32mm">
                                        <span class="nf-label">Competência</span>
                                        <span class="info">{{Helper::formataData($dadosNFS[0]->nfs_dt_emi)}}</span>
                                    </td>
                                    <td style="width: 32mm">
                                        <span class="nf-label">Número / Série do RPS</span>
                                        <span class="info">{{str_pad($dadosNFS[0]->nfs_nrps, 9, "0", STR_PAD_LEFT).'-'.$dadosNFS[0]->nfs_srps}}</span>
                                    </td>
                                    <td style="width: 32mm">
                                        <span class="nf-label">Número da NFS Substituída</span>
                                        <span class="info">{{str_pad('0', 9, "0", STR_PAD_LEFT)}}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

        <p class="area-name">DADOS DO(S) SERVIÇO(S)</p>
        <table class="tabela" cellpadding="0" cellspacing="0" border="1">
            <tbody>
                <tr>
                    @php 
                        if($dadosParSrvEmp[0]->parsrv_exg_iss == '1'){
                            $exg_iss = '1 - Exigível';
                        }elseif($dadosParSrvEmp[0]->parsrv_exg_iss == '2'){
                            $exg_iss = '2 - Não Incidência';
                        }elseif($dadosParSrvEmp[0]->parsrv_exg_iss == '3'){
                            $exg_iss = '3 - Isenção';
                        }elseif($dadosParSrvEmp[0]->parsrv_exg_iss == '4'){
                            $exg_iss = '4 - Exportação';
                        }elseif($dadosParSrvEmp[0]->parsrv_exg_iss == '5'){
                            $exg_iss = '5 - Imunidade';
                        }elseif($dadosParSrvEmp[0]->parsrv_exg_iss == '6'){
                            $exg_iss = '6 - Exigibilidade';
                        }elseif($dadosParSrvEmp[0]->parsrv_exg_iss == '7'){
                            $exg_iss = '7 - Suspensa por Decisão Judicial';
                        }else{
                            $exg_iss = '8 - Exigibilidade Suspensa por Processo Administrativo';
                        }
                    @endphp
                    <td style="width: 32mm">
                        <span class="nf-label">EXIGIBILIDADE DO ISS / NATUREZA DA OPERAÇÃO</span>
                        <span class="info">{{$exg_iss}} / Prestação de Serviços</span>
                    </td>
                    <td style="width: 32mm">
                        <span class="nf-label">LOCAL DA PRESTAÇÃO DO(S) SERVIÇO(S)</span>
                        <span class="info">{{$dadosNFS[0]->nfs_loc_srv_cidade.' / '.$dadosNFS[0]->nfs_loc_srv_uf}}</span>
                    </td>
                    <td style="width: 32mm">
                        <span class="nf-label">LOCAL DA INCIDÊNCIA DO(S) SERVIÇO(S)</span>
                        <span class="info">{{$dadosEmpEnd[0]->endereco_cidade.' / '.$dadosEmpEnd[0]->endereco_uf}}</span>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Destinatário/Emitente -->
        <p class="area-name">PRESTADOR DO(S) SERVIÇO(S)</p>
        <div style="width:100%; height: 11%;">
            <div style="width:20%;float:left;display: flex; align-items: center; justify-content: center;">
                @php 
                    // Obter o nome do host do servidor
                    if(!empty($_SERVER['SERVER_NAME'])){
                        $serverName = $_SERVER['SERVER_NAME'];
                    }else{
                        $serverName = '';
                    }

                    // Verificar se está rodando no localhost
                    if ($serverName == '127.0.0.1' || stripos($serverName, 'localhost') !== false) {
                        
                        $fullpath = public_path($pathImgEmpresa); 
                    
                    } else {
                        
                        $fullpath = '/home/'.$pathImgEmpresa;
                    } 
                @endphp
                <img style="width:95%; height: auto;margin-top: 10px;" class="client_logo" src="data:image/png;base64, <?php echo base64_encode(file_get_contents($fullpath)); ?>" />
            </div>
            <div style="width:80%;float:right;">
                                
                <table cellpadding="0" cellspacing="0" class="boxDestinatario" border="1">
                    <tbody>
                        <tr>
                            <td class="pd-0">
                                <table cellpadding="0" cellspacing="0" border="1">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <span class="nf-label">NOME/RAZÃO SOCIAL</span>
                                                <span class="info">{{$dadosEmp[0]->empresa_nome}}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="pd-0">
                                <table cellpadding="0" cellspacing="0" border="1">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <span class="nf-label">ENDEREÇO</span>
                                                <span class="info">{{$dadosEmpEnd[0]->endereco_logradouro.', '.$dadosEmpEnd[0]->endereco_numero}}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="pd-0">
                                <table cellpadding="0" cellspacing="0" border="1">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <span class="nf-label">Bairro</span>
                                                <span class="info">{{$dadosEmpEnd[0]->endereco_bairro}}</span>
                                            </td>
                                            <td style="width: 15%;">
                                                <span class="nf-label">CEP</span>
                                                <span class="info">{{Helper::mascaraCEP($dadosEmpEnd[0]->endereco_cep)}}</span>
                                            </td>
                                            <td style="width: 35%;">
                                                <span class="nf-label">MUNICÍPIO</span>
                                                <span class="info">{{$dadosEmpEnd[0]->endereco_cidade}}</span>
                                            </td>
                                            <td style="width: 10%;">
                                                <span class="nf-label">UF</span>
                                                <span class="info">{{$dadosEmpEnd[0]->endereco_uf}}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="pd-0">
                                <table cellpadding="0" cellspacing="0" border="1">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <span class="nf-label">Complemento</span>
                                                <span class="info">{{$dadosEmpEnd[0]->endereco_complemento}}</span>
                                            </td>
                                            <td style="width: 35%;">
                                                <span class="nf-label">Telefone</span>
                                                <span class="info">{{$telEmp}}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="pd-0">
                                <table cellpadding="0" cellspacing="0" style="margin-bottom: -1px;" border="1">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <span class="nf-label">CNPJ</span>
                                                <span class="info">{{Helper::mascaraCNPJ($dadosEmp[0]->empresa_cnpj)}}</span>
                                            </td>
                                            <td style="width: 30%;">
                                                <span class="nf-label">INSCRIÇÃO MUNICIPAL</span>
                                                <span class="info">{{$dadosEmp[0]->empresa_insc_municipal}}</span>
                                            </td>
                                            <td style="width: 40%;">
                                                <span class="nf-label">Email</span>
                                                <span class="info">{{$dadosEmp[0]->empresa_email}}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <p class="area-name">TOMADOR DO(S) SERVIÇO(S)</p>
        <table cellpadding="0" cellspacing="0" class="boxDestinatario" border="1">
            <tbody>
                <tr>
                    <td class="pd-0">
                        <table cellpadding="0" cellspacing="0" border="1">
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="nf-label">NOME/RAZÃO SOCIAL</span>
                                        <span class="info">{{$dadosNFS[0]->nfs_nom_tom}}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="pd-0">
                        <table cellpadding="0" cellspacing="0" border="1">
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="nf-label">ENDEREÇO</span>
                                        <span class="info">{{$dadosNFS[0]->nfs_end_tom.', '.$dadosNFS[0]->nfs_num_end_tom}}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="pd-0">
                        <table cellpadding="0" cellspacing="0" border="1">
                            <tbody>
                                <tr>
                                        <td>
                                            <span class="nf-label">Bairro</span>
                                            <span class="info">{{$dadosNFS[0]->nfs_bai_tom}}</span>
                                        </td>
                                        <td style="width: 15%;">
                                            <span class="nf-label">CEP</span>
                                            <span class="info">{{Helper::mascaraCEP($dadosNFS[0]->nfs_cep_tom)}}</span>
                                        </td>
                                        <td style="width: 35%;">
                                            <span class="nf-label">MUNICÍPIO</span>
                                            <span class="info">{{$dadosNFS[0]->nfs_cid_tom}}</span>
                                        </td>
                                        <td style="width: 10%;">
                                            <span class="nf-label">UF</span>
                                            <span class="info">{{$dadosNFS[0]->nfs_uf_tom}}</span>
                                        </td>
                                    </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td class="pd-0">
                        <table cellpadding="0" cellspacing="0" border="1">
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="nf-label">Complemento</span>
                                        <span class="info">{{$dadosNFS[0]->nfs_com_end_tom}}</span>
                                    </td>
                                    <td style="width: 25%;">
                                        <span class="nf-label">Telefone</span>
                                        <span class="info">{{$telTom}}</span>
                                    </td>
                                    <td style="width: 25%;">
                                        <span class="nf-label">CPF / CNPJ</span>
                                        <span class="info">{{$cpfCnpjTom}}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="pd-0">
                        <table cellpadding="0" cellspacing="0" style="margin-bottom: -1px;" border="1">
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="nf-label">INSCRIÇÃO ESTADUAL</span>
                                        <span class="info">{{$dadosNFS[0]->nfs_ins_est_tom}}</span>
                                    </td>
                                    <td style="width: 30%;">
                                        <span class="nf-label">INSCRIÇÃO MUNICIPAL</span>
                                        <span class="info">{{$dadosNFS[0]->nfs_ins_mun_tom}}</span>
                                    </td>
                                    <td style="width: 40%;">
                                        <span class="nf-label">Email</span>
                                        <span class="info">{{$dadosNFS[0]->nfs_email_tom}}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

        <p class="area-name">DISCRIMINAÇÃO DO(S) SERVIÇO(S)</p>
		<div class="wrapper-border">
            <table cellpadding="0" cellspacing="0" border="1" class="boxProdutoServico" style="border-bottom: 0px !important;">
                <thead class="listProdutoServico" id="table">
                    <tr class="titles">
						<th class="cod" style="width: 5%">SEQ.</th>
                        <th class="cod" style="width: 10%">CÓDIGO</th>
                        <th class="descrit">DESCRIÇÃO DO SERVIÇO</th>
                        <th class="amount" style="width: 5%;">QTD.</th>
                        <th class="valUnit" style="width: 10%;">VALOR UNI.</th>
                        <th class="valTotal" style="width: 10%;">VALOR TOTAL</th>
                        <th class="valDes" style="width: 10%;">DESCONTO</th>
                        <th class="valLiq" style="width: 10%;">VALOR LIQUIDO</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dadosSrvNFS as $servico)
                        @php  
                            $seq += 1;

                            if($servico->nfssrv_emi_simp == 'N'){
                                $desc_srv = $servico->nfssrv_tmo_dsc;
                                $codigo = $servico->nfssrv_tmo;
                            }else{
                                $desc_srv = $servico->nfssrv_srv_desc;
                                $codigo = 'SIM'.$dadosNFS[0]->nfs_nfhdr_num_ped;
                                if(!empty($servico->nfssrv_inf_com)){
                                    $desc_srv .= ' | '.$servico->nfssrv_inf_com;
                                }
                            }
                        @endphp
                        <tr>
                            <td style="width: 5%;border: 0px;text-align: center;">
                                <span class="info">{{$seq}}</span>
                            </td>
                            <td style="width: 10%;border: 0px;text-align: center;">
                                <span class="info">{{$codigo}}</span>
                            </td>
                            <td style="border: 0px;">
                                <span style="border: 0px;" class="info">{{$desc_srv}}</span>
                            </td>
                            <td style="width: 5%;border: 0px;text-align: center;">
                                <span class="info">{{$servico->nfssrv_qtd_hr}}</span>
                            </td>
                            <td style="width: 10%;border: 0px;text-align: center;">
                                <span class="info">{{Helper::formataValorMonetario($servico->nfssrv_vlr_hr)}}</span>
                            </td>
                            <td style="width: 10%;border: 0px;text-align: center;">
                                <span class="info">{{Helper::formataValorMonetario($servico->nfssrv_vlr_tot)}}</span>
                            </td>
                            <td style="width: 10%;border: 0px;text-align: center;">
                                <span class="info">{{Helper::formataValorMonetario($servico->nfssrv_vlr_dsc)}}</span>
                            </td>
                            <td style="width: 10%;border: 0px;text-align: center;">
                                <span class="info">{{Helper::formataValorMonetario($servico->nfssrv_vlr_liq)}}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <table class="tabela" cellpadding="0" cellspacing="0" border="1">
            <tbody>
                <tr>
                    <td colspan="4" style="border-top: 0px;">
                        <span class="nf-label">Observações</span>
                        <span class="info">{{$dadosNFS[0]->nfs_obs}}</span>
                    </td>
                </tr>
            </tbody>
        </table>
	   
        <!-- Calculo do Imposto -->
        <p class="area-name">Calculo do imposto</p>
        <div class="wrapper-table">
            <table cellpadding="0" cellspacing="0" border="1" class="boxImposto">
                <tbody>
                    <tr>
                        <td colspan="4">
                            <span class="nf-label">CÓDIGO DE CLASSIFICAÇÃO DO SERVIÇO</span>
                            <span class="info" style="text-align: left !important;">{{$dadosCodSrv[0]->servico_codigo.' - '.$dadosCodSrv[0]->servico_desc}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="nf-label">VALOR DO(S) SERVIÇO(S) </span>
                            <span class="info">{{Helper::formataValorMonetario($dadosNFS[0]->nfs_vlr_srv)}}</span>
                        </td>
                        <td>
                            <span class="nf-label">VALOR DEDUÇÕES</span>
                            <span class="info">{{Helper::formataValorMonetario($dadosNFS[0]->nfs_vlr_ded)}}</span>
                        </td>
                        <td>
                            <span class="nf-label">DESCONTO INCONDICIONADO</span>
                            <span class="info">{{Helper::formataValorMonetario($dadosNFS[0]->nfs_vlr_dsc)}}</span>
                        </td>
                        <td>
                            <span class="nf-label">BASE DE CÁLCULO ISS</span>
                            <span class="info">{{Helper::formataValorMonetario($dadosNFS[0]->nfs_vlr_tot)}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="nf-label">ALÍQUOTA ISS (%)</span>
                            <span class="info">{{Helper::formataValorMonetario($dadosNFS[0]->nfs_alq_nfs)}}</span>
                        </td>
                        <td>
                            <span class="nf-label">VALOR DO ISS</span>
                            <span class="info">{{Helper::formataValorMonetario($vlrIss)}}</span>
                        </td>
                        <td>
                            <span class="nf-label">VALOR DO ISS RETIRDO</span>
                            <span class="info">{{Helper::formataValorMonetario($vlrIssRet)}}</span>
                        </td>
                        <td>
                            <span class="nf-label">DESCONTO CONDICIONADO</span>
                            <span class="info">{{Helper::formataValorMonetario(0)}}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

         <!-- Calculo do Imposto -->
        <p class="area-name">RETENÇÕES FEDERAIS</p>
        <div class="wrapper-table">
            <table cellpadding="0" cellspacing="0" border="1" class="boxImposto">
                <tbody>
                    <tr>
                        <td>
                            <span class="nf-label">IMPOSTO DE RENDA</span>
                            <span class="info">{{Helper::formataValorMonetario($dadosNFS[0]->nfs_ir_ret)}}</span>
                        </td>
                        <td>
                            <span class="nf-label">PIS</span>
                            <span class="info">{{Helper::formataValorMonetario($dadosNFS[0]->nfs_pis_ret)}}</span>
                        </td>
                        <td>
                            <span class="nf-label">COFINS</span>
                            <span class="info">{{Helper::formataValorMonetario($dadosNFS[0]->nfs_cofins_ret)}}</span>
                        </td>
                        <td>
                            <span class="nf-label">CSLL</span>
                            <span class="info">{{Helper::formataValorMonetario($dadosNFS[0]->nfs_csll_ret)}}</span>
                        </td>
                        <td>
                            <span class="nf-label">INSS</span>
                            <span class="info">{{Helper::formataValorMonetario($dadosNFS[0]->nfs_inss_ret)}}</span>
                        </td>
                        <td>
                            <span class="nf-label">OUTRAS RENTENÇÕES</span>
                            <span class="info">{{Helper::formataValorMonetario(0)}}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

         <!-- Calculo do Imposto -->
        <p class="area-name">TOTAIS</p>
        <div class="wrapper-table">
            <table cellpadding="0" cellspacing="0" border="1" class="boxImposto">
                <tbody>
                    <tr>
                        <td>
                            <span class="nf-label">TOTAL DO(S) SERVIÇO(S)</span>
                            <span class="info">{{Helper::formataValorMonetario($dadosNFS[0]->nfs_vlr_srv)}}</span>
                        </td>
                        <td>
                            <span class="nf-label">TOTAL LÍQUIDO</span>
                            <span class="info">{{Helper::formataValorMonetario($dadosNFS[0]->nfs_vlr_tot)}}</span>
                        </td>
                        <td>
                            <span class="nf-label">TOTAL DA NOTA</span>
                            <span class="info">{{Helper::formataValorMonetario($dadosNFS[0]->nfs_vlr_tot)}}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Dados adicionais -->
        <p class="area-name">OUTRAS INFORMAÇÕES</p>
        <table cellpadding="0" cellspacing="0" border="1" class="boxDadosAdicionais">
            <tbody>
                <tr>
                    <td class="field infoComplementar" style="height: 24mm;">
                        <span class="nf-label">{{$outInfo}}</span>
                    </td>
                </tr>
            </tbody>
        </table>

        <footer>
            <table cellpadding="0" cellspacing="0">
                <tbody>
                    <tr>
                        <td style="text-align: left"><strong>Data e Hora da Impressão: {{date('d/m/Y H:i:s')}}</strong></td>
                        <td style="text-align: right"><strong>Desenvolvido por FusionTech Systems - https://www.fusiontechsystems.com.br/</strong></td>
                    </tr>
                </tbody>
            </table>
        </footer>
    </div>
</div>

</body>
</html>
