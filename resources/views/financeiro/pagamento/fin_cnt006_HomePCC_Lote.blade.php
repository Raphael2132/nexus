@extends('adminlte::page')

@section('title', 'Conta Corrente')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Pagamento de Conta Corrente</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            @if($appOrigem == 'LOTE')
            <li class="breadcrumb-item active">Lote</li>
            @else
            <li class="breadcrumb-item active">Adiantamento de Fornecedor</li>
            @endif
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-8">
        <form method="post" action="{{route('pagamentoPCC.iniciaPCCLote',['appOrigem' => $appOrigem])}}" id="form-pag-pcc" novalidate="novalidate">
            @csrf 
            @method('post')
            <x-adminlte-card title="{{ $appOrigem === 'LOTE' ? 'Pagamento em Lote' : 'Pagamento de Adiantamento de Fornecedor' }}" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
                @php
                    $array_emp = HelperArraySelect::arrayEmpresas(1,1);
                    $array_tcc = HelperArraySelect::arrayTipoContasPagamento(1,1);
                    $htmlCli = HelperDataList::geraDatalistGeralResponsaveis('clientes','S','S','S','N');

                    //Echo adiciona o html ao campo dos clientes
                    echo $htmlCli;

                @endphp
                <div class="row"> 
                    <x-adminlte-select name="empresa" fgroup-class="col-md-12" igroup-size="sm">
                        <x-slot name="label">
                            Empresa <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_emp" empty-option="Selecione..." selected=""/>
                    </x-adminlte-select>
                </div>

                <div class="row">     
                    <x-adminlte-input name="cliente" type="search" list="clientes" autocomplete="off" value="" fgroup-class="col-md-12" igroup-size="sm">
                        <x-slot name="label">
                            Beneficiário <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>
                </div>

                @if($appOrigem == 'LOTE')
                @php
                    $config = [
                        "title" => "Selecione várias opções...",
                        "liveSearch" => true,
                        "liveSearchPlaceholder" => "Pesquisar...",
                        "showTick" => true,
                        "actionsBox" => true,
                        "noneSelectedText" => "Nada selecionado",
                        "selectAllText" => "Selecionar tudo",
                        "deselectAllText" => "Limpar seleção",
                        "doneButtonText" => "Fechar",
                    ];

                    $data = DB::table('financeiro_tab_contas')
                    ->select('tabcon_codigo', 'tabcon_nome')
                    ->whereIn('tabcon_codigo', ['GD', 'NF', 'NP', 'CP', 'DV', 'AC', 'IS', 'ID', 'CO', 'VU', 'GV', 'VC'])
                    ->orderBy('tabcon_codigo', 'asc')
                    ->get();
                @endphp
                <div class="row"> 
                    <!-- Tipo da CC -->
                    <x-adminlte-select-bs id="selBsConta" name="selBsConta[]" label="Conta Corrente" igroup-size="sm" :config="$config" fgroup-class="col-md-12" multiple>
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fas fa-wallet"></i>
                            </div>
                        </x-slot>
                        <x-slot name="appendSlot">
                            <x-adminlte-button id="btnClearSelect" class="btn-nexus" theme="" label="Limpar" icon="fas fa-lg fa-broom"/>
                        </x-slot>
                        @foreach ($data as $item)
                            @php
                                $icons = [
                                    'AC' => 'fa-hand-holding-usd text-success',
                                    'CO' => 'fa-credit-card text-primary',
                                    'CP' => 'fa-file-invoice-dollar text-warning',
                                    'DV' => 'fa-undo text-danger',
                                    'GD' => 'fa-receipt text-secondary',
                                    'GV' => 'fa-gas-pump text-info',
                                    'ID' => 'fa-percent text-dark',
                                    'IS' => 'fa-balance-scale text-dark',
                                    'NF' => 'fa-file-invoice text-primary',
                                    'NP' => 'fa-boxes text-success',
                                    'VC' => 'fa-car-side text-indigo',
                                    'VU' => 'fa-car text-muted',
                                ];
                                $icon = $icons[$item->tabcon_codigo] ?? 'fa-wallet text-info';
                            @endphp

                            <option value="{{ $item->tabcon_codigo }}"
                                    data-icon="fa fa-fw {{ $icon }}" 
                                    data-subtext="- {{ $item->tabcon_codigo }}">
                                {{ $item->tabcon_nome }}
                            </option>
                        @endforeach
                    </x-adminlte-select-bs>
                </div>
                @endif

                <div class="row">
                    @php
                        $config = Helper::dtRangeDataPtBR();
                    @endphp
                    <!-- Data da CC -->
                    <x-adminlte-date-range name="dtIni" label="Data de Vencimento Inicial" :config="$config" placeholder="de dia/mês/ano" fgroup-class="col-md-6" igroup-size="sm">
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="far fa-lg fa-calendar-alt"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-date-range>
                    <x-adminlte-date-range name="dtFin" label="Data de Vencimento Final" :config="$config" placeholder="até dia/mês/ano" fgroup-class="col-md-6" igroup-size="sm">
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="far fa-lg fa-calendar-alt"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-date-range>
                </div>
                <div class="row">
                    <!-- Valor da CC -->
                    <x-adminlte-input name="vlrIni" label="Valor Inicial" type="text" value="" placeholder="de 0,00" fgroup-class="col-md-6" igroup-size="sm">
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-brazilian-real-sign"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                    <x-adminlte-input name="vlrFin" label="Valor Final" type="text" value="" placeholder="até 0,00" fgroup-class="col-md-6" igroup-size="sm">
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-brazilian-real-sign"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                
                <x-slot name="footerSlot">
                    <x-adminlte-button class="btn-nexus" type="submit" label="Confirmar" theme="info" icon="fa-solid fa-check"/>
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
            const dtIni = $('#dtIni').val();
            const dtFin = $('#dtFin').val();

            if (dtIni && dtFin) {
                const [d1, m1, y1] = dtIni.split('/').reverse();
                const [d2, m2, y2] = dtFin.split('/').reverse();
                const dataIni = new Date(`${y1}-${m1}-${d1}`);
                const dataFin = new Date(`${y2}-${m2}-${d2}`);

                if (dataFin < dataIni) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Aviso!',
                        text: 'A data de vencimento final deve ser maior ou igual à data de vencimento inicial.',
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
