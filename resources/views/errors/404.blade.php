@extends('adminlte::page')

@section('title', __('Not Found'))

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Página de Erro</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home')}}">Página Inicial</a>
            </li>
            <li class="breadcrumb-item active">Página de Erro 404</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="error-page">
    <h2 class="headline text-warning"> 404</h2>

    <div class="error-content">
        <h3><i class="fas fa-exclamation-triangle text-warning"></i> Ops! Página não encontrada.</h3>

        <p>
        Não conseguimos encontrar a página que você estava procurando.
        Enquanto isso, você pode <a href="{{route('home')}}">retornar à Página Inicial</a> e voltar a trabalhar em outras tarefas.
        </p>

        <p>
            Se o problema persistir você pode entrar em contato preferencialmente através da <a href="{{ route('contato') }}">Página de Contato</a> 
            ou enviar um e-mail diretamente para <a href="mailto:suporte@fusiontechsystems.com.br">suporte@fusiontechsystems.com.br</a>.
        </p>

    </div>
</div>
@stop