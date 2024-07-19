@extends('adminlte::page')

@section('title', 'Manutenção de Grupos de Serviço da NFS-e')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h1>Parâmetros do Sistema</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">
                    <a href="{{route('home.parSisServico')}}">Grupos e Serviços da NFS-e</a>
                </li>
                <li class="breadcrumb-item active">Manutenção do Grupo de Serviço da NFS-e</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-8">
        <form method="post" action="{{route('parametrosSistemaGrpServico.atualizar', [ 'grupo' => $dadosGrupo[0]['grupo_codigo'] ] )}}" id="quickForm" novalidate="novalidate">
            @csrf 
            @method('post')
            <x-adminlte-card title="Grupo do Serviço da NFS-e" theme="navy">

                <div class="row">
                    <!-- Código do Grupo do Serviço -->
                    <x-adminlte-input name="codigo" label="Código" type="number" value="{{$dadosGrupo[0]->grupo_codigo}}" fgroup-class="col-md-6" disabled/>
                </div>
                
                <div class="row">
                    <!-- Empresa do Serviço -->
                    <x-adminlte-textarea name="descricao" label="Descriçao" rows=3 label-class="text-dark" igroup-size="sm" placeholder="Informe a descrição do grupo..." fgroup-class="col-md-12">
                        {{$dadosGrupo[0]->grupo_desc}}
                        <x-slot name="prependSlot">
                            <div class="input-group-text bg-navy">
                                <i class="fas fa-lg fa-file-alt text-white"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-textarea>
                </div>

                <!-- /.card -->
                <x-slot name="footerSlot">
                    <div class="d-flex justify-content-between w-100">
                        <x-adminlte-button class="btn-flat" type="submit" label="Salvar" theme="info" icon="fa-solid fa-share-from-square"/>
                        <x-adminlte-button class="btn-flat" type="button" onclick="window.location='{{ route('home.parSisServico') }}'" label="Voltar" theme="info" icon=""/>
                    </div>
                </x-slot>
            </x-adminlte-card>
        </form>
    </div>
</div>
@stop

@section('css')
@stop

<!-- Chamada dos Plugins usados na app -->  
@section('plugins.Sweetalert2', true)
@section('plugins.toastr', true)
@section('plugins.jqueryValidation', true)

@section('js')
<script>
    @if(Session::has('success'))
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        Toast.fire({
            icon: "success",
            title: "{{ session('success') }}"
        });
    @endif

    @if(Session::has('error'))
        Swal.fire({
        confirmButtonColor: "#007bff",
        title: "Erro!!!",
        text: "{{ session('error') }}",
        icon: "error"
    });
    @endif
</script>
@stop
