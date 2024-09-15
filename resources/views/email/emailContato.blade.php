<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $details['titulo'] }}</title>
</head>
<body>
    <h1>{{ $details['titulo'] }}</h1>
    <p><strong>Empresa: </strong>{{ $details['empresa'] }}</p>
    <p><strong>Email de Contato: </strong>{{ $details['emailCliente'] }}</p>
    <p><strong>Setor: </strong>{{ $details['setor'] }}</p>
    <p><strong>Setor: </strong>{{ $details['assunto'] }}</p>
    <h3>Menssagem do Cliente</h3>
    {!! $details['mensagem'] !!}
</body>
</html>
