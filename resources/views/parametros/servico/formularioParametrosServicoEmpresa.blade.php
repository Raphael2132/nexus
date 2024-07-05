@extends('adminlte::page')

@section('title', 'Cadastro de Etapas de Atendimento')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h1>Parâmetros Gerais de Serviços</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">
                    <a href="{{route('home.parametrosSrvEmp')}}">Geral da Empresa</a>
                </li>
                <li class="breadcrumb-item active">Manutenção de Parâmetros Gerais</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-8">
        <!-- Define se o formulario é edição ou novo -->
        <form method="post" action="{{route('parametrosSrvEmp.update')}}" id="quickForm" novalidate="novalidate">
            @csrf 
            <x-adminlte-card title="Cadastro de Nova Etapa de Atendimento" theme="navy">

                <div class="row">
                    @php
                        $data = DB::table('cadastro_empresas')->where('empresa_codigo', $parametrosEmp[0]->parsrv_emp)->get();

                        $new_array1 =[];
                        $new_array2 =[];

                        foreach ($data as $empresa) {
                            $new_array1[] = $empresa->empresa_codigo;
                            $new_array2[] = $empresa->empresa_codigo.' - '.$empresa->empresa_nome;
                        }
                        $array_opt = array_combine($new_array1, $new_array2);

                    @endphp

                    <!-- Empresa do Setor -->
                    <x-adminlte-select name="empresa" label="Empresa" fgroup-class="col-md-12">
                        <x-adminlte-options :options="$array_opt" empty-option="Selecione..." selected="{{$parametrosEmp[0]->parsrv_emp}}"/>
                    </x-adminlte-select>
                </div>

                <div class="row">              
                    <!-- Aliquota ISS -->
                    <x-adminlte-input name="aliqISS" label="Aliq. ISS" type="text" placeholder="0,00" value="{{Helper::formataPorcentagem($parametrosEmp[0]->parsrv_alq_iss)}}" fgroup-class="col-md-6"/>

                    <!-- CFOP Serviço -->
                    <x-adminlte-input name="srvCFOP" label="CFOP Serviço" type="number" placeholder="Informe o CFOP" value="{{$parametrosEmp[0]->parsrv_cfop}}" fgroup-class="col-md-6"/>
                </div>  
                <div class="row">  
                    @php
                        $horaIniEx = Helper::formataHoraMinuto($parametrosEmp[0]->parsrv_hr_ini_ex);

                        $config = [
                            "singleDatePicker" => true,
                            "showDropdowns" => true,
                            "minYear" => 2000,
                            "maxYear" => "js:parseInt(moment().format('YYYY'),10)",
                            "timePicker" => true,
                            "timePicker24Hour" => true,
                            "timePickerSeconds" => false,
                            "cancelButtonClasses" => "btn-danger",
                            "locale" => ["format" => "HH:mm"],
                        ];
                    @endphp
                    <x-adminlte-date-range name="horaIniEx" label="Hora Ini. Expediente" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-6">
                        <x-slot name="appendSlot">
                            <div class="input-group-text">
                                <i class="far fa-lg fa-clock"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-date-range>
                    @push('js')<script>$(() => $("#horaIniEx").val('{{ $horaIniEx }}'))</script>@endpush

                    @php
                        $horaFinEx = Helper::formataHoraMinuto($parametrosEmp[0]->parsrv_hr_fin_ex);

                        $config = [
                            "singleDatePicker" => true,
                            "showDropdowns" => true,
                            "minYear" => 2000,
                            "maxYear" => "js:parseInt(moment().format('YYYY'),10)",
                            "timePicker" => true,
                            "timePicker24Hour" => true,
                            "timePickerSeconds" => false,
                            "cancelButtonClasses" => "btn-danger",
                            "locale" => ["format" => "HH:mm"],
                        ];
                    @endphp
                    <x-adminlte-date-range name="horaFinEx" label="Hora Fin. Expediente" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-6">
                        <x-slot name="appendSlot">
                            <div class="input-group-text">
                                <i class="far fa-lg fa-clock"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-date-range>
                    @push('js')<script>$(() => $("#horaFinEx").val('{{ $horaFinEx }}'))</script>@endpush
                </div>

                <!-- /.card -->
                <x-slot name="footerSlot">
                    <x-adminlte-button class="btn-flat btn_salvar" type="submit" label="Salvar" theme="info" icon="fa-solid fa-share-from-square"/>
                </x-slot>
            </x-adminlte-card>
        </form>
    </div>
</div>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.jqueryValidation', true)
@section('plugins.Sweetalert2', true)
@section('plugins.toastr', true)
@section('plugins.Select2', true)
@section('plugins.DateRangePicker', true)

@section('css')
@stop

@section('js')
<script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script>

<!--
|--------------------------------------------------------------------------
| Eventos Inicial da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() {    

        $("#empresa").attr("disabled", true);

        $('#aliqISS').mask('#.##0,00', {reverse: true});

        //Esconde calendário de data
        $(function() { 
            $('#horaIniEx').on('showCalendar.daterangepicker', function(ev, picker) {

                $('.calendar-table').hide();

            }) 
        });

        //Esconde calendário de data
        $(function() { 
            $('#horaFinEx').on('showCalendar.daterangepicker', function(ev, picker) {

                $('.calendar-table').hide();

            }) 
        });
    });
</script>

<!--
|--------------------------------------------------------------------------
| Eventos onClick da app
|--------------------------------------------------------------------------
-->
<script>
    //Ao clicar no botão salvar retira o disabled do campo para não ter problema no request do update do campo
    $(".btn_salvar").click(function(){
        $("#empresa").attr("disabled", false);
    });
</script>

<!--
|--------------------------------------------------------------------------
| Eventos Validate da app
|--------------------------------------------------------------------------
-->
<script>
$(function () {

    jQuery.validator.addMethod("maxpercent", function(value, element) {
        return this.optional(element) || /^(\d{1,2}|\d{1,2}\,\d{1,2}|100\,[0]{1,2}|100)$/i.test(value);
    }, "Porcentagem máxima de 100,00 %");

    $('#quickForm').validate({
        rules: {
            empresa: {
                required: true
            },
            aliqISS: {
                required: true,
                maxpercent: true
            },
            srvCFOP: {
                required: true,
                maxlength: 4,
                minlength: 4,
            },
            horaIniEx: {
                required: true
            },
            horaFinEx: {
                required: true
            },
        },
        messages: {
            empresa: {
                required: "Por Favor informe a Empresa"
            },
            aliqISS: {
                required: "Por Favor informe uma Aliquota de ISS"
            },
            srvCFOP: {
                required: "Por Favor informe um CFOP",
                maxlength: "CFOP deve ter 4 digitos",
                minlength: "CFOP deve ter 4 digitos"
            },
            horaIniEx: {
                required: "Por Favor informe a hora de inicio do expediente"
            },
            horaFinEx: {
                required: "Por Favor informe a hora do final do expediente"
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

<!--
|--------------------------------------------------------------------------
| Eventos de Messagem da app
|--------------------------------------------------------------------------
-->
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
