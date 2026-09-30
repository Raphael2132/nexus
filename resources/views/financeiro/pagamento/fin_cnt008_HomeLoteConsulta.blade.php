@extends('adminlte::page')

@section('title', 'Conta Corrente')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Pagamento de Conta Corrente</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Filtro</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-8">
        <form method="post" action="{{route('pagamentoPCC.consultaLote')}}" id="form-pag-pcc" novalidate="novalidate">
            @csrf 
            @method('post')
            <x-adminlte-card title="Consulta de Lotes Gerados" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
                @php
                    $array_emp = HelperArraySelect::arrayEmpresas(1,1);
                    $array_cx = null;
                @endphp
                <div class="row"> 
                    <x-adminlte-select name="empresa" fgroup-class="col-md-6" igroup-size="sm">
                        <x-slot name="label">
                            Empresa <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_emp" empty-option="Selecione..." selected=""/>
                    </x-adminlte-select>
                    
                    <x-adminlte-select name="local" label="Caixa" fgroup-class="col-md-6" igroup-size="sm">
                        <x-adminlte-options :options="$array_cx" empty-option="Selecione..." selected=""/>
                    </x-adminlte-select>
                </div>
                <div class="row"> 
                    <x-adminlte-input name="recibo" label="Recibo" placeholder="Número do Recibo" type="number" fgroup-class="col-md-6" igroup-size="sm" oninput="this.value = this.value.replace(/[^0-9]/g, '')"></x-adminlte-input>
                    
                    <div class="col-md-6 check-nexus">
                        <label class="form-label">Situação</label>

                        <div class="check-nexus-wrapper">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input"
                                    type="checkbox"
                                    name="situacao[]"
                                    id="situacao_finalizado"
                                    value="F"
                                    {{ in_array('F', old('situacao', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="situacao_finalizado">
                                    Finalizado
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input"
                                    type="checkbox"
                                    name="situacao[]"
                                    id="situacao_cancelado"
                                    value="C"
                                    {{ in_array('C', old('situacao', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="situacao_cancelado">
                                    Cancelado
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    @php
                        $config = Helper::dtRangeDataPtBR();
                    @endphp
                    <x-adminlte-date-range name="dtIniGer" label="Data de Geração Inicial" :config="$config" placeholder="de dia/mês/ano" fgroup-class="col-md-6" igroup-size="sm">
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="far fa-lg fa-calendar-alt"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-date-range>
                    <x-adminlte-date-range name="dtFinGer" label="Data de Geração Final" :config="$config" placeholder="até dia/mês/ano" fgroup-class="col-md-6" igroup-size="sm">
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="far fa-lg fa-calendar-alt"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-date-range>
                </div>
                <div class="row">
                    @php
                        $config = Helper::dtRangeDataPtBR();
                    @endphp
                    <x-adminlte-date-range name="dtIniCan" label="Data de Cancelamento Inicial" :config="$config" placeholder="de dia/mês/ano" fgroup-class="col-md-6" igroup-size="sm">
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="far fa-lg fa-calendar-alt"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-date-range>
                    <x-adminlte-date-range name="dtFinCan" label="Data de Cancelamento Final" :config="$config" placeholder="até dia/mês/ano" fgroup-class="col-md-6" igroup-size="sm">
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="far fa-lg fa-calendar-alt"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-date-range>
                </div>
                
                <x-slot name="footerSlot">
                    <div class="d-flex justify-content-end">
                        <x-adminlte-button class="btn-nexus" type="submit" label="Confirmar" theme="info" icon="fa-solid fa-check"/>
                    </div>
                </x-slot>
            </x-adminlte-card>
        </form>
    </div>
</div>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.Sweetalert2', true)
@section('plugins.jqueryValidation', true)
@section('plugins.Select2', true)
@section('plugins.BootstrapSelect', true)
@section('plugins.DateRangePicker', true)
@section('plugins.icheckBootstrap', true)

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
        // Função para limpar o select
        $('#btnClearSelect').on('click', function() {
            $('#selBsConta').selectpicker('deselectAll'); // limpa seleção
            $('#selBsConta').selectpicker('refresh');     // atualiza visual
        });

        //Mascaras de campos float
        $('#vlrIni').mask('#.##0,00', {reverse: true});
        $('#vlrFin').mask('#.##0,00', {reverse: true});        
    });
</script>

<!--
|--------------------------------------------------------------------------
| Eventos onChange da app
|--------------------------------------------------------------------------
-->
<script>
$(document).ready(function() {

    //Evento de carregamento ajax da Administradora do cartão de crédito
    $('#empresa').change(function(){

        if( $(this).val() ) {
            
            var empresa = $(this).val();
            var url = "{{ route('ajax.getCaixasEmpAjax', [':empresa']) }}";
            var url = url.replace(':empresa', empresa);

            $.ajax({
                url: url,
                dataType: "JSON",
                type: 'GET',
                data: {
                    '_token': $('meta[name=csrf-token]').attr("content"),
                    '_method': 'GET',
                    "empresa": empresa
                },
                success: function (data)
                {
                    if(data.caixas_ajax_existe == 'S'){

                        var options = '<option value="">Selecione...</option>';	

                        for (var i = 0; i < data.caixas_ajax.length; i++) {

                            options += '<option value="' + data.caixas_ajax[i].id + '">' + data.caixas_ajax[i].caixa + '</option>';
                        }	

                        $('#local').html(options);

                    }else{
                        $('#local').html('<option value="">Selecione...</option>');
                    }
                }
            });
        }
    });
});
</script>

<!--
|--------------------------------------------------------------------------
| Eventos Validate da app
|--------------------------------------------------------------------------
-->
<script>
$(function () {

    //Inserção / Atualização Recebimento com Cartão de Débito
    $('#form-pag-pcc').validate({
        rules: {
            empresa: {
                required: true
            },
            cliente: {
                required: true
            }
        },
        messages: {
            empresa: {
                required:  "Por Favor informe a Empresa"
            },
            cliente: {
                required:  "Por Favor informe o Beneficiário"
            }
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
        },
        // Ao submeter o formulário, reativar os campos desativados
        submitHandler: function (form) {

            // ---- Verificação de Data ----
            const dtIniGer = $('#dtIniGer').val();
            const dtFinGer = $('#dtFinGer').val();

            if (dtIniGer && dtFinGer) {
                const [d1, m1, y1] = dtIniGer.split('/').reverse();
                const [d2, m2, y2] = dtFinGer.split('/').reverse();
                const dataIniGer = new Date(`${y1}-${m1}-${d1}`);
                const dataFinGer = new Date(`${y2}-${m2}-${d2}`);

                if (dataFinGer < dataIniGer) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Aviso!',
                        text: 'A data de geração final deve ser maior ou igual à data de geração inicial.',
                        confirmButtonColor: '#007bff',
                        confirmButtonText: 'OK'
                    });
                    return false; // impede envio
                }
            }

            // ---- Verificação de Data ----
            const dtIniCan = $('#dtIniCan').val();
            const dtFinCan = $('#dtFinCan').val();

            if (dtIniCan && dtFinCan) {
                const [d1, m1, y1] = dtIniCan.split('/').reverse();
                const [d2, m2, y2] = dtFinCan.split('/').reverse();
                const dataIniCan = new Date(`${y1}-${m1}-${d1}`);
                const dataFinCan = new Date(`${y2}-${m2}-${d2}`);

                if (dataFinCan < dataIniCan) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Aviso!',
                        text: 'A data de cancelamento final deve ser maior ou igual à data de cancelamento inicial.',
                        confirmButtonColor: '#007bff',
                        confirmButtonText: 'OK'
                    });
                    return false; // impede envio
                }
            }

            // ---- Verificação de Valor ----
            const vlrIni = $('#vlrIni').val();
            const vlrFin = $('#vlrFin').val();

            if (vlrIni && vlrFin) {
                const v1 = parseFloat(vlrIni.replace(/\./g, '').replace(',', '.')) || 0;
                const v2 = parseFloat(vlrFin.replace(/\./g, '').replace(',', '.')) || 0;

                if (v2 < v1) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Aviso!',
                        text: 'O valor final deve ser maior ou igual ao valor inicial.',
                        confirmButtonColor: '#007bff',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }
            }

            // Ativar campos desativados antes de enviar
            $(':disabled').each(function () {
                $(this).removeAttr('disabled');
            });
            form.submit();
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

    @if(Session::has('info'))
        Swal.fire({
            confirmButtonColor: "#007bff",
            title: "Aviso!",
            text: "{{ session('info') }}",
            icon: "info",
            customClass: {
                icon: "no-before-icon",
            }
        });
    @endif

    @if(Session::has('success2'))
        Swal.fire({
            confirmButtonColor: "#007bff",
            title: "Sucesso!",
            text: "{{ session('success2') }}",
            icon: "success",
            customClass: {
                icon: "no-before-icon",
            }
        });
    @endif
</script>
@stop
