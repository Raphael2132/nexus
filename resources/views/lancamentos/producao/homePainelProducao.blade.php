@extends('adminlte::page')

@section('title', 'Painel de Produção')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h4 style="margin-bottom: 0px !important;">Painel de Produção</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Filtro do Painel</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-8">
        <form method="post" action="{{route('painelProducao.painelProducao')}}" id="quickForm" novalidate="novalidate">
            @csrf 
            @method('post')
            <x-adminlte-card title="Filtro do Painel de Produção" theme="navy" collapsible maximizable>
                @php
                    $data = DB::table('cadastro_empresas')->select('empresa_codigo', 'empresa_nome')->orderBy('empresa_codigo', 'asc')->get();

                    $new_array1 =[];
                    $new_array2 =[];

                    foreach ($data as $empresa) {
                        $new_array1[] = $empresa->empresa_codigo;
                        $new_array2[] = $empresa->empresa_codigo.' - '.$empresa->empresa_nome;
                    }
                    $array_opt = array_combine($new_array1, $new_array2);
                @endphp
                <div class="row"> 
                    <!-- Empresa -->
                    <x-adminlte-select name="empresa" fgroup-class="col-md-12">
                        <x-slot name="label">
                            Empresa <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_opt" empty-option="Selecione..."/>
                    </x-adminlte-select>
                </div>
                <div class="row"> 
                    @php
                        $array_opt_set = null;
                    @endphp
                    <!-- Setor -->
                    <x-adminlte-select name="setor" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Setor <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_opt_set" empty-option="Selecione..."/>
                    </x-adminlte-select>
                    <!-- Tipo do Painel -->
                    <x-adminlte-select name="tipoPainel" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Tipo do Painel <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="['PR' => 'Prestador', 'OS' => 'OS']" empty-option="Selecione..."/>
                    </x-adminlte-select>
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
                            "locale" => [
                                "format" => "DD/MM/YYYY",
                                "separator" => " - ",
                                "applyLabel" => "Aplicar",
                                "cancelLabel" => "Cancelar",
                                "fromLabel" => "De",
                                "toLabel" => "Até",
                                "customRangeLabel" => "Personalizado",
                                "weekLabel" => "Sm",
                                "daysOfWeek" => ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
                                "monthNames" => ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
                                "firstDay" => 0
                            ],
                        ];
                    @endphp
                    <!-- Data do Painel -->
                    <x-adminlte-date-range name="dataPainel" :config="$config" placeholder="Formato dia/mês/ano" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Data <span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="prependSlot">
                        <div class="input-group-text">
                                <i class="far fa-lg fa-calendar-alt"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-date-range>
                </div>

                <div class="row"> 
                    @php
                        $array_opt_set = null;
                    @endphp
                    <!-- Linhas por página -->
                    <x-adminlte-input name="linhaPagina" placeholder="Informe a Quantidade de Linhas" fgroup-class="col-md-6" enable-old-support type="number">
                        <x-slot name="label">
                            Quantidade de Linhas por Página <span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="appendSlot">
                            <div class="input-group-text">
                                <i class="fa-solid fa-list-ol"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                    <!-- Tempo de atualização de pagina -->
                    <x-adminlte-input name="tempoPagina" placeholder="Informe os Segundos para a Troca de Página" fgroup-class="col-md-6" enable-old-support type="number">
                        <x-slot name="label">
                            Tempo da Troca da Página em Segundos<span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="appendSlot">
                            <div class="input-group-text">
                                <i class="fa-solid fa-stopwatch-20"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>

                <x-slot name="footerSlot">
                    <x-adminlte-button class="btn-flat" type="submit" label="Abrir Painel" theme="info" icon="fa-solid fa-table-list"/>
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

<!--
|--------------------------------------------------------------------------
| Eventos onChange da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() {

        //Evento de carregamento ajax dos dados dos setores
        $('#empresa').change(function(){

            if( $(this).val() ) {
                var emp = $(this).val();

                var url = "{{ route('painelProducao.carregaSetAjax', [':emp']) }}";
                url = url.replace(':emp', emp);

                $.ajax({
                    url: url,
                    dataType: "JSON",
                    type: 'GET',
                    data: {
                        '_token': $('meta[name=csrf-token]').attr("content"),
                        '_method': 'GET',
                        "emp": emp
                    },
                    success: function (data)
                    {
                        if(data.setores_ajax_existe == 'S'){

                            var options = '<option value="">Selecione...</option>';	

                            for (var i = 0; i < data.setores_ajax.length; i++) {

                                options += '<option value="' + data.setores_ajax[i].id + '">' + data.setores_ajax[i].cod_setor + '</option>';
                            }	

                            $('#setor').html(options);

                        }else{
                            $('#setor').html('<option value="">Selecione...</option>');
                        }
                    }
                });
            } else {
                $('#setor').html('<option value="">Selecione...</option>');
            }
        });
    });
</script>

<script>
$(function () {
    $('#quickForm').validate({
        rules: {
            empresa: {
                required: true
            },
            setor: {
                required: true
            },
            tipoPainel: {
                required: true
            },
            dataPainel: {
                required: true
            },
            tempoPagina: {
                required: true
            },
            linhaPagina: {
                required: true
            },
        },
        messages: {
            empresa: {
                required: "Por Favor informe a Empresa"
            },
            setor: {
                required: "Por Favor informe o Setor"
            },
            tipoPainel: {
                required: "Por Favor informe o Tipo do Painel"
            },
            dataPainel: {
                required: "Por Favor informe a Data do Painel"
            },
            linhaPagina: {
                required: "Por Favor informe a Qtd. de Linhas por Página"
            },
            tempoPagina: {
                required: "Por Favor informe o Tempo de Troca da Página em Segundos"
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

