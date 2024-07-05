@extends('adminlte::page')

@section('title', 'Parametrização da Emissão de NFS-e')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h1>Parâmetros Gerais</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">
                    <a href="{{route('home.parFatNfs')}}">Emissão de NFS-e</a>
                </li>
                @if($appOrigem == 'parametrosNfsEmissao')
                  <li class="breadcrumb-item active">
                      <a href="{{route('parametrosNfsEmissao')}}">Emissões Parâmetrizadas</a>
                  </li>
                @endif
                <li class="breadcrumb-item active">Manutenção</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-8">
        <form method="post" action="{{route('parmetrosNfsEmi.atualizar', [ 'empresa' => $dadosEmissao[0]['parnfs_empresa'] ] )}}" id="quickForm" novalidate="novalidate">
            @csrf 
            @method('post')
            <x-adminlte-card title="Parametrização de Emissão de NFS-e" theme="navy">

                <div class="row">
                    @php
                        $nomeEmp = DB::table('cadastro_empresas')->selectRaw('empresa_nome')->where('empresa_codigo','=',$dadosEmissao[0]->parnfs_empresa)->get();

                        $nomeEmpresa = $dadosEmissao[0]->parnfs_empresa.' - '.$nomeEmp[0]->empresa_nome;
                    @endphp
                    <!-- Empresa -->
                    <x-adminlte-input name="empresa" label="Empresa" type="text" value="{{$nomeEmpresa}}" fgroup-class="col-md-12" readonly/>
                </div>
                
                <div class="row">
                    <!-- Gera NFS-e -->
                    <x-adminlte-select name="geraNFS" label="Gera NFS-e" fgroup-class="col-md-6">
                        <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" empty-option="Selecione..." selected="{{$dadosEmissao[0]['parnfs_utiliza_nfs']}}"/>
                    </x-adminlte-select>

                    @php
                        $data = DB::table('parametros_fat_nfs_provedores')->orderBy('provedor_codigo', 'asc')->get();

                        $new_array1 =[];
                        $new_array2 =[];

                        foreach ($data as $provedor) {
                            $new_array1[] = $provedor->provedor_codigo;
                            $new_array2[] = $provedor->provedor_codigo.' - '.$provedor->provedor_desc;
                        }
                        $array_opt = array_combine($new_array1, $new_array2);
                    @endphp
                    <!-- Provedor -->
                    <x-adminlte-select name="provedor" label="Provedor da NFS-e" fgroup-class="col-md-6">
                        <x-adminlte-options :options="$array_opt" empty-option="Selecione..." selected="{{$dadosEmissao[0]['parnfs_provedor']}}"/>
                    </x-adminlte-select>
                </div>

                <div class="row">
                    <!-- Número da NFS-e -->
                    <x-adminlte-input name="numeroNFS" type="number" label="Numeração da NFS-e" fgroup-class="col-md-6" value="{{$dadosEmissao[0]['parnfs_numeracao']}}"></x-adminlte-input>

                    <!-- Série da NFS-e -->
                    <x-adminlte-input name="serieNFS" type="text" label="Série da NFS-e" fgroup-class="col-md-6" value="{{$dadosEmissao[0]['parnfs_serie']}}"></x-adminlte-input>
                </div>

                <div class="row">
                    <!-- Imprime NFS-e -->
                    <x-adminlte-select name="imprimeNFS" label="Gera Impressão NFS-e" fgroup-class="col-md-6">
                        <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" empty-option="Selecione..." selected="{{$dadosEmissao[0]['parnfs_impressao_nfs']}}"/>
                    </x-adminlte-select>

                    <!-- Imprime RPS -->
                    <x-adminlte-select name="imprimeRPS" label="Gera Impressão de RPS" fgroup-class="col-md-6">
                        <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" empty-option="Selecione..." selected="{{$dadosEmissao[0]['parnfs_impressao_rps']}}"/>
                    </x-adminlte-select>
                </div>

                <!-- /.card -->
                <x-slot name="footerSlot">
                    <x-adminlte-button class="btn-flat" type="submit" label="Salvar" theme="info" icon="fa-solid fa-share-from-square"/>
                </x-slot>
            </x-adminlte-card>
        </form>
    </div>
</div>
@stop

@section('css')
@stop

<!-- Chamada dos Plugins usados na app -->  
@section('plugins.jqueryValidation', true)
@section('plugins.Sweetalert2', true)
@section('plugins.toastr', true)

@section('js')
<script>
$(function () {
  $('#quickForm').validate({
    rules: {
      geraNFS: {
        required: true
      },
      provedor: {
        required: true
      },
      numeroNFS: {
        required: true,
        maxlength: 9
      },
      serieNFS: {
        required: true,
        maxlength: 5
      },
      imprimeNFS: {
        required: true
      },
      imprimeRPS: {
        required: true
      },
    },
    messages: {
      geraNFS: {
        required: "Por Favor informe se a empresa gera NFS-e"
      },
      provedor: {
        required: "Por Favor informe o Provedor"
      },
      numeroNFS: {
        required: "Por Favor informe o a Numeração da NFS-e",
        maxlength: "Informe no máximo 9 dígitos"
      },
      serieNFS: {
        required: "Por Favor informe a Série da NFS-e",
        minlength: "Informe no máximo 5 caracteres"
      },
      imprimeNFS: {
        required: "Por Favor informe se a Empresa imprime NFS-e",
      },
      imprimeRPS: {
        required: "Por Favor informe se a Empresa imprime RPS"
      },
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });
});
</script>

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
</script>
@stop
