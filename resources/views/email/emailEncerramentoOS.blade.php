<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Encerramento de Ordem de Serviço</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        h1 {
            background-color: #00abab;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .footer {
            background-color: #06023e;
            color: white;
            padding: 20px;
            border-radius: 5px;
            margin-top: 30px;
            text-align: center;
        }
        .footer .linha1, .footer .linha2 {
            justify-content: space-between;
        }
        .footer .linha1 {
            font-size: 16px;
        }
        .footer .logo {
            width: 40%;
        }
        .footer .text {
            text-align: center;
        }
        .footer .linha2 {
            text-align: center;
            font-size: 12px;
        }
        .footer a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
@php 
    $dadosCli = DB::table('cadastro_clientes')->where('cliente_codigo', $ordemServico->os_cli)->first();
    $dadosEmp = DB::table('cadastro_empresas')->where('empresa_codigo', $ordemServico->os_emp)->first();

    if(empty($dadosEmp->empresa_email)){
        $email = '';
    }else{
        $email = $dadosEmp->empresa_email;
    }

    $telefone = "";

    if(empty($dadosEmp->empresa_tel_celular)){
        $telefone = '';
    }else{
        $telefone = Helper::mascaraTelCelular($dadosEmp->empresa_tel_celular);
    }

    if(!empty($dadosEmp->empresa_tel_comercial)){
        if(empty($telefone)){
            $telefone = Helper::mascaraTelComercial($dadosEmp->empresa_tel_comercial);
        }else{
            $telefone .= " / ".Helper::mascaraTelComercial($dadosEmp->empresa_tel_comercial);
        }
    }

    $dadosReq = DB::table('lancamento_srv_os_requisicoes')->where('req_emp', $ordemServico->os_emp)->where('req_nos', $ordemServico->os_nos)->get();
    $servicos = '';
    foreach($dadosReq as $req){
        if(empty($servicos)){
            $servicos = $req->req_dsc;
        }else{
            $servicos .= " | ".$req->req_dsc;
        }
    }

    // Obter o nome do host do servidor
    if(!empty($_SERVER['SERVER_NAME'])){
        $serverName = $_SERVER['SERVER_NAME'];
    }else{
        $serverName = '';
    }

    // Verificar se está rodando no localhost
    if (!empty($serverName)) {
        $logoEmp = "https://".$serverName."/logo/".$dadosEmp->empresa_cnpj."/".$ordemServico->os_emp."_logo.png"; 
        $logoNexus = "https://".$serverName."/img/sistema/logo_nexus_v2.png"; 
    }
@endphp
<body>
    <div class="container">
        <!-- Header com o logo da empresa -->
        <div class="header">
            <img style="width:70%; height:auto; margin-top:10px;" class="client_logo" src="{{$logoEmp}}" alt="Logo da Empresa"/>
        </div>

        <!-- Conteúdo do email -->
        <h1>Encerramento de Ordem de Serviço</h1>
        
        <p>Olá, <strong>{{ $dadosCli->cliente_nome }}</strong>,</p>

        <p>É com satisfação que informamos que sua ordem de serviço <strong>Nº {{ str_pad($ordemServico->os_nos, 6, '0', STR_PAD_LEFT) }}</strong> foi encerrada com sucesso!</p>
        
        <p>Abaixo estão os detalhes da OS:</p>
        <ul>
            <li><strong>Serviços:</strong> {{ $servicos }}</li>
            <li><strong>Valor Total:</strong> R${{ Helper::formataValorMonetario($ordemServico->os_vlt) }}</li>
            <li><strong>Data de Abertura:</strong> {{ Helper::formataDataHora($ordemServico->os_dha) }}</li>
            <li><strong>Data de Encerramento:</strong> {{ Helper::formataDataHora($ordemServico->os_dhf) }}</li>
            <li><strong>Empresa Prestadora:</strong> {{ $dadosEmp->empresa_nome }}</li>
        </ul>

        <p>Se você tiver qualquer dúvida ou precisar de suporte adicional, nossa equipe está à disposição para ajudá-lo. Entre em contato conosco pelo email ou telefone abaixo:</p>
        
        <p><strong>Email:</strong> {{ $email }}</p>
        <p><strong>Telefone:</strong> {{ $telefone }}</p>

        <p>Atenciosamente, <strong>{{ $dadosEmp->empresa_nome }}</strong>.</p>

        <!-- Rodapé -->
        <div class="footer">
            <!-- Linha 1 -->
            <div class="linha1">
                <img class="logo" src="{{$logoNexus}}" alt="Logo da Empresa"/>
                <div class="text">
                    <p>Ordem de Serviço emitida pelo sistema <a href="https://nexuserpcloud.com.br/" target="_blank"><strong>Nexus ERP Cloud</strong></a></p>
                </div>
            </div>
            <!-- Linha 2 -->
            <div class="linha2">
                <p>Software desenvolvido por <a href="https://fusiontechsystems.com.br/" target="_blank"><strong>FusionTech Systems</strong></a></p>
            </div>
        </div>
    </div>
</body>
</html>
