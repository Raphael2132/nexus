@extends('adminlte::page')

@section('title', 'Painel de Agendamento')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h4 style="margin-bottom: 0px !important;">Painel de Agendamento</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Filtro Agendamento</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-8">
        <form method="post" action="{{route('agenda.calendarioPrt')}}" id="quickForm" novalidate="novalidate">
            @csrf 
            @method('post')
            <x-adminlte-card title="Filtro Agendamento Serviços do Prestador" theme="navy" collapsible maximizable>
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

                    //Faz o lookup do campo de prestadores 
                    $data_prt = DB::table('cadastro_prestadores')->selectRaw('prestador_codigo, prestador_nome')->orderBy('prestador_codigo', 'asc')->get();
                    $html = '<datalist id="prestadores">';
                    foreach($data_prt as $prestador){
                        $html .= '<option value="'.$prestador->prestador_codigo.' - '.$prestador->prestador_nome.'">'.$prestador->prestador_codigo.' - '.$prestador->prestador_nome.'</option>';
                    }
                    $html .='</datalist>';
                    //Echo adiciona o html ao campo dos prestadores
                    echo $html;
                @endphp
                <div class="row"> 
                    <!-- Empresa do Setor -->
                    <x-adminlte-select name="empresa" fgroup-class="col-md-12">
                        <x-slot name="label">
                            Empresa <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_opt" empty-option="Selecione..." selected="{{$emp_sel}}"/>
                    </x-adminlte-select>
                </div>
                <div class="row">
                    <!-- Prestador -->
                    <x-adminlte-input name="prestador" type="search" list="prestadores" value="" fgroup-class="col-md-12">
                        <x-slot name="label">
                            Prestador <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="row">
                    @php
                        $config = [
                            "singleDatePicker" => true,
                            "showDropdowns" => true,
                            "startDate" => "js:moment()",
                            "minYear" => 1900,
                            "maxYear" => "js:parseInt(moment().format('YYYY'),10)",
                            "timePicker" => false,
                            "timePicker24Hour" => false,
                            "timePickerSeconds" => false,
                            "cancelButtonClasses" => "btn-danger",
                            "locale" => ["format" => "DD/MM/YYYY"],
                        ];
                    @endphp
                    <!-- Data de Pedido / OS -->
                    <x-adminlte-date-range name="dtIniAge" :config="$config" placeholder="de dia/mês/ano" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Data Inicial <span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="prependSlot">
                        <div class="input-group-text">
                                <i class="far fa-lg fa-calendar-alt"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-date-range>
                    <x-adminlte-date-range name="dtFinAge" :config="$config" placeholder="até dia/mês/ano" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Data Final <span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="prependSlot">
                        <div class="input-group-text">
                                <i class="far fa-lg fa-calendar-alt"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-date-range>
                </div>
                <x-slot name="footerSlot">
                    <x-adminlte-button class="btn-flat" type="submit" label="Pesquisar" theme="info" icon="fa-solid fa-magnifying-glass"/>
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
@section('plugins.DateRangePicker', true)

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
      prestador: {
        required: true
      },
      dtIniAge: {
        required: true
      },
      dtFinAge: {
        required: true
      },
    },
    messages: {
      empresa: {
        required: "Por Favor informe a Empresa"
      },
      prestador: {
        required: "Por Favor informe o Prestador"
      },
      dtIniAge: {
        required: "Por Favor informe a Data Inicial"
      },
      dtFinAge: {
        required: "Por Favor informe a Data Final"
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

