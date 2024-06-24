@extends('adminlte::page')

@section('title', 'Emissão de Ordem de Serviço')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h1>Lançamentos</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Emissão de OS</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-8">
        <form method="post" action="{{route('emissaoOS.inicio')}}" id="quickForm" novalidate="novalidate">
            @csrf 
            @method('post')
            <x-adminlte-card title="Emissão de Ordem de Serviço" theme="navy" collapsible maximizable>
                @php
                    $data = DB::table('cadastro_empresas')->select('empresa_codigo', 'empresa_nome')->orderBy('empresa_codigo', 'asc')->get();

                    $new_array1 =[];
                    $new_array2 =[];

                    foreach ($data as $empresa) {
                        $new_array1[] = $empresa->empresa_codigo;
                        $new_array2[] = $empresa->empresa_codigo.' - '.$empresa->empresa_nome;
                    }
                    $emp_sel = '';
                    $array_opt = array_combine($new_array1, $new_array2);

                    //Faz o lookup do campo de clientes 
                    $data_cli = DB::table('cadastro_clientes')->selectRaw('cliente_codigo, cliente_nome')->orderBy('cliente_codigo', 'asc')->get();
                    $html = '<datalist id="clientes">';
                    foreach($data_cli as $cliente){
                        $html .= '<option value="'.$cliente->cliente_codigo.' - '.$cliente->cliente_nome.'">'.$cliente->cliente_codigo.' - '.$cliente->cliente_nome.'</option>';
                    }
                    $html .='</datalist>';
                    //Echo adiciona o html ao campo dos clientes
                    echo $html;
                @endphp
                <div class="row"> 
                    <!-- Empresa do Setor -->
                    <x-adminlte-select name="empresa" label="Empresa" fgroup-class="col-md-12">
                        <x-adminlte-options :options="$array_opt" empty-option="Selecione..." selected="{{$emp_sel}}"/>
                    </x-adminlte-select>
                </div>
                <div class="row">
                    <!-- Prestador responsavel da TMO -->
                    <x-adminlte-input name="cliente" label="Cliente" type="search" list="clientes" value="" fgroup-class="col-md-12"/>
                </div>
                <x-slot name="footerSlot">
                    <x-adminlte-button class="btn-flat" type="submit" label="Prosseguir" theme="info" icon="fa-solid fa-share-from-square"/>
                </x-slot>
            </x-adminlte-card>
        </form>
    </div>
</div>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.Sweetalert2', true)
@section('plugins.toastr', true)
@section('plugins.jqueryValidation', true)

@section('css')
@stop

@section('js')
<script>
$(function () {
  $('#quickForm').validate({
    rules: {
      empresa: {
        required: true
      },
      cliente: {
        required: true
      },
    },
    messages: {
      empresa: {
        required: "Por Favor informe a Empresa"
      },
      cliente: {
        required: "Por Favor informe o Cliente"
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
