@extends('adminlte::page')

@section('title', __('Server Error'))

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
            <li class="breadcrumb-item active">Página de Erro 500</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="error-page">
    <h2 class="headline text-danger">500</h2>
    <div class="error-content">
        <h3><i class="fas fa-exclamation-triangle text-danger"></i> Ops! Algo deu errado.</h3>
        <p>
            Trabalharemos para consertar isso imediatamente.
            Enquanto isso, você pode <a href="{{route('home')}}">retornar à Página Inicial</a> e voltar a trabalhar em outras tarefas.
        </p>
        <p>
            Se o problema persistir, por favor entre em contato com o suporte técnico e forneça os seguintes detalhes para ajudar na solução:
        </p>
        <ul>
            <li><strong>Erro:</strong> 500 - Erro Interno do Servidor</li>
            <li><strong>URL:</strong> {{ url()->current() }}</li>
            <li><strong>Data e Hora:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</li>
        </ul>
        <p>
            Você pode entrar em contato preferencialmente através da <a href="{{ route('contato') }}">Página de Contato</a> 
            ou enviar um e-mail diretamente para <a href="mailto:suporte@fusiontechsystems.com.br">suporte@fusiontechsystems.com.br</a>.
        </p>
        <x-adminlte-alert theme="danger" title="Detalhes do erro:">
            {{ $exception->getMessage() }}
        </x-adminlte-alert>
    </div>
</div>
@stop