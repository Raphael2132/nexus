<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Emissão da NFS-e</title>
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
    $dadosCli = DB::table('cadastro_clientes')->where('cliente_codigo', $dadosNFS->nfs_cli)->first();
    $dadosEmp = DB::table('cadastro_empresas')->where('empresa_codigo', $dadosNFS->nfs_emp)->first();

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

    // Obter o nome do host do servidor
    if(!empty($_SERVER['SERVER_NAME'])){
        $serverName = $_SERVER['SERVER_NAME'];
    }else{
        $serverName = '';
    }

    // Verificar se está rodando no localhost
    if (!empty($serverName)) {
        $logoEmp = "https://".$serverName."/logo/".$dadosEmp->empresa_cnpj."/".$dadosNFS->nfs_emp."_logo.png"; 
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
        <h1>Emissão da NFS-e</h1>
        
        <p>Olá, <strong>{{ $dadosCli->cliente_nome }}</strong>,</p>

        @if($dadosNFS->nfs_origem == 'ES')
        <p>É com satisfação que informamos que a NFS-e <strong>Nº {{ str_pad($dadosNFS->nfs_nnfs, 6, '0', STR_PAD_LEFT) }}</strong> foi gerada!</p>
        @else
        <p>É com satisfação que informamos que a NFS-e referente a OS <strong>Nº {{ str_pad($dadosNFS->nfs_nfhdr_num_ped, 6, '0', STR_PAD_LEFT) }}</strong> foi gerada!</p>
        @endif
        
        <p>Abaixo estão os detalhes da NFS-e:</p>
        <ul>
            <li><strong>NFS-e:</strong> {{ str_pad($dadosNFS->nfs_nnfs, 6, '0', STR_PAD_LEFT) }}</li>
            <li><strong>Valor Total:</strong> R${{ Helper::formataValorMonetario($dadosNFS->nfs_vlr_tot) }}</li>
            <li><strong>Data da Emissão:</strong> {{ Helper::formataData($dadosNFS->nfs_dt_emi).' '.Helper::formataHoraMinuto($dadosNFS->nfs_hr_emi) }}</li>
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
                    <p>NFS-e emitida pelo sistema <a href="https://nexuserpcloud.com.br/" target="_blank"><strong>Nexus ERP Cloud</strong></a></p>
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
