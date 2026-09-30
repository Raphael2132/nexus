@extends('adminlte::page')

@section('title', 'Transferência')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Relatório de Transfências</h4>
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
    <div class="col-md-6">
        <form method="post" action="{{route('transferencia.consultaTransfRealizadas')}}" id="form-transf" novalidate="novalidate">
            @csrf 
            @method('post')
            <x-adminlte-card title="Filtro do Relatório de Trasferências" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
                @php
                    $array_cx = HelperArraySelect::arrayRazoesPorTipo(1,1,'','CX');

                    $ano = date('Y');

                    $query = "
                        SELECT distinct 
                            razao_codigo, 
                            razao_nome
                        FROM financeiro_razoes
                        WHERE 
                            razao_tipo in('CX','TE') AND 
                            razao_ano = ".$ano."
                        ORDER BY 
                            razao_codigo ASC
                    ";
                    $data = DB::select($query);

                    $new_array1 =[];
                    $new_array2 =[];

                    foreach ($data as $razao) {
                        $new_array1[] = $razao->razao_codigo;
                        $new_array2[] = $razao->razao_codigo.' - '.$razao->razao_nome;
                    }

                    $array_opt = array_combine($new_array1, $new_array2);
                @endphp
                <div class="row"> 
                    <x-adminlte-select name="razOrigem" label="Razão de Origem" fgroup-class="col-md-12" igroup-size="sm">
                        <x-adminlte-options :options="$array_cx" empty-option="Selecione..." selected=""/>
                    </x-adminlte-select>
                </div>

                <div class="row"> 
                    <x-adminlte-select name="razDestino" label="Razão de Destino" fgroup-class="col-md-12" igroup-size="sm">
                        <x-adminlte-options :options="$array_opt" empty-option="Selecione..." selected=""/>
                    </x-adminlte-select>
                </div>

                <div class="row"> 
                    <div class="col-md-12 check-nexus">
                        <label class="form-label">Situação</label>

                        <div class="check-nexus-wrapper">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input"
                                    type="radio"
                                    name="transf"
                                    id="transf_cx"
                                    value="T"
                                    {{ old('transf', 'T') == 'T' ? 'checked' : '' }}>
                                <label class="form-check-label" for="transf_cx">
                                    Transferência de Caixa Operacional
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input"
                                    type="radio"
                                    name="transf"
                                    id="reforco_cx"
                                    value="R"
                                    {{ old('transf') == 'R' ? 'checked' : '' }}>
                                <label class="form-check-label" for="reforco_cx">
                                    Reforço de Caixa Operacional
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    @php
                        $config = Helper::dtRangeDataPtBR();
                    @endphp
                    <x-adminlte-date-range name="dtIni" label="Data da Transferência Inicial" :config="$config" placeholder="de dia/mês/ano" fgroup-class="col-md-6" igroup-size="sm">
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="far fa-lg fa-calendar-alt"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-date-range>
                    <x-adminlte-date-range name="dtFin" label="Data da Transferência Final" :config="$config" placeholder="até dia/mês/ano" fgroup-class="col-md-6" igroup-size="sm">
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="far fa-lg fa-calendar-alt"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-date-range>
                </div>
                
                <x-slot name="footerSlot">
                    <x-adminlte-button id="btn-confirmar" class="btn-nexus" type="submit" label="Pesquisar" theme="info" icon="fa-solid fa-magnifying-glass"/>
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
<!--
|--------------------------------------------------------------------------
| Eventos Iniciais da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() { 

    });
</script>

<!--
|--------------------------------------------------------------------------
| Eventos Validate da app
|--------------------------------------------------------------------------
-->
<script>
$(function () {

    $('#form-transf').validate({
        rules: {
        },
        messages: {
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
            const dtIni = $('#dtIni').val();
            const dtFin = $('#dtFin').val();

            if (dtIni && dtFin) {
                const [d1, m1, y1] = dtIni.split('/');
                const [d2, m2, y2] = dtFin.split('/');

                const dataIni = new Date(Number(y1), Number(m1) - 1, Number(d1));
                const dataFin = new Date(Number(y2), Number(m2) - 1, Number(d2));

                if (dataFin.getTime() < dataIni.getTime()) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Aviso!',
                        text: 'A data de transferência final deve ser maior ou igual à data de transferência inicial.',
                        confirmButtonColor: '#007bff',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }
            }

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
