@extends('adminlte::page')

@section('title', 'Contas Correntes')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Pagamento de Contas Correntes</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.homePCCIndDin')}}">Individual em Dinheiro</a>
            </li>
            <li class="breadcrumb-item active">
                <a href="{{route('pagamentoPCC.consultaContas')}}">Contas</a>
            </li>
            <li class="breadcrumb-item active">Formulário de Pagamento</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-12">
        <form method="post" action="{{route('pagamentoPCC.finalizarPCC')}}" id="form-pag-din" novalidate="novalidate">
            @csrf 
            @method('post')
            <x-adminlte-card title="Formulário de Pagamento Individual em Dinheiro" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
                @php
                    $emp = HelperFormatSelect::formataEmpresaCodigoNome($dadosContaPag->conta_empresa);
                    $cli = HelperFormatSelect::formataClientes($dadosContaPag->conta_responsavel);
                    $tcc = HelperFormatSelect::formataTipoCC($dadosContaPag->conta_tipo);
                    
                    $dtPag = date('d/m/Y');

                    $saldoPag = $dadosContaPag->conta_valor - $dadosContaPag->conta_val_rec;
                    $saldoPag = Helper::formataValorMonetario($saldoPag);

                @endphp
                <div class="row">
                    <div class="text-muted col-md-3">
                        <p class="text-sm">Empresa
                            <b class="d-block">{{ $emp }}</b>
                        </p>
                    </div>
                    <div class="text-muted col-md-3">
                        <p class="text-sm">Cliente
                            <b class="d-block">{{ $cli }}</b>
                        </p>
                    </div>
                    <div class="text-muted col-md-3">
                        <p class="text-sm">Tipo da Conta
                            <b class="d-block">{{ $tcc }}</b>
                        </p>
                    </div>
                    <div class="text-muted col-md-3">
                        <p class="text-sm">Número da Conta
                            <b class="d-block">{{ $dadosContaPag->conta_num_conta }}</b>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="text-muted col-md-3">
                        <p class="text-sm">Valor da Conta
                            <b class="d-block">{{ Helper::formataValorMonetario($dadosContaPag->conta_valor) }}</b>
                        </p>
                    </div>
                    <div class="text-muted col-md-3">
                        <p class="text-sm">Valor Pago
                            <b class="d-block">{{ Helper::formataValorMonetario($dadosContaPag->conta_val_rec) }}</b>
                        </p>
                    </div>
                    <div class="text-muted col-md-3">
                        <p class="text-sm">Data de Emissão
                            <b class="d-block">{{ Helper::formataData($dadosContaPag->conta_dt_emissao) }}</b>
                        </p>
                    </div>
                    <div class="text-muted col-md-3">
                        <p class="text-sm">Data de Vencimento
                            <b class="d-block">{{ Helper::formataData($dadosContaPag->conta_dt_vencimento) }}</b>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="text-muted col-md-3">
                        <p class="text-sm">Complemento
                            <b class="d-block">{{ $dadosContaPag->conta_complemento }}</b>
                        </p>
                    </div>
                    <div class="text-muted col-md-9">
                        <p class="text-sm">Observações
                            <b class="d-block">{{ $dadosContaPag->conta_obs }}</b>
                        </p>
                    </div>
                </div>
                <div class="post">
                    <h5 class="text-secondary font-weight-bold">Valores do Pagamento</h5>
                </div>
                <div class="row">
                    <x-adminlte-input name="saldo" label="Saldo Restante" type="text" value="{{ $saldoPag }}" placeholder="0,00" fgroup-class="col-md-6" igroup-size="sm" disabled>
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-brazilian-real-sign"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    @php
                        $config = Helper::dtRangeDataPtBR();
                    @endphp
                    <!-- Data de Nascimento -->
                    <x-adminlte-date-range name="dataPag" :config="$config" label="Data do Pagamento" placeholder="Formato dia/mês/ano" fgroup-class="col-md-6" igroup-size="sm" disabled>
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="far fa-lg fa-calendar-alt"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-date-range>
                    @push('js')<script>$(() => $("#dataPag").val('{{ $dtPag }}'))</script>@endpush
                </div>
                <div class="row">
                    <x-adminlte-input name="valorPag" type="text" value="" placeholder="0,00" fgroup-class="col-md-4" igroup-size="sm">
                        <x-slot name="label">
                            Valor do Pagamento <span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-brazilian-real-sign"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                    
                    <x-adminlte-input name="valorDes" type="text" label="Valor de Desconto" value="" placeholder="0,00" fgroup-class="col-md-4" igroup-size="sm">
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-brazilian-real-sign"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                    
                    <x-adminlte-input name="valorAcre" type="text" label="Valor de Acréscimo" value="" placeholder="0,00" fgroup-class="col-md-4" igroup-size="sm">
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-brazilian-real-sign"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="row">
                    <x-adminlte-input name="valorTotpag" label="Valor Total" type="text" value="" placeholder="0,00" fgroup-class="col-md-6" igroup-size="sm" disabled>
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-brazilian-real-sign"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <x-adminlte-input name="saldoRest" label="Saldo Restante da Conta" type="text" value="" placeholder="0,00" fgroup-class="col-md-6" igroup-size="sm" disabled>
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-brazilian-real-sign"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="row">
                    <x-adminlte-textarea name="observacao" label="Observação" rows=3 label-class="text-dark" placeholder="Informe a Observação..." fgroup-class="col-md-12">
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fas fa-lg fa-file-alt text-white"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-textarea>
                </div>

                <!-- Campos escondidos para o request -->
                <input id="tipoConta" type="hidden" value="{{ $dadosContaPag->conta_tipo }}" name="tipoConta">
                <input id="numConta" type="hidden" value="{{ $dadosContaPag->conta_num_conta }}" name="numConta">

                <x-slot name="footerSlot">
                    <div class="d-flex justify-content-between w-100">
                        <div>
                            <x-adminlte-button class="btn-nexus me-2" type="button"
                                onclick="window.location='{{ route('pagamentoPCC.excluiPCCDin', ['empresa' => $empresaPAG, 'idPagamento' => $idPagamento]) }}'"
                                label="Cancelar" theme="info" icon="fa-solid fa-xmark"/>

                            <x-adminlte-button class="btn-nexus" type="submit"
                                label="Confirmar" theme="info" icon="fa-solid fa-check"/>
                        </div>

                        <div>
                            <x-adminlte-button class="btn-nexus" type="button"
                                onclick="window.location='{{ route('pagamentoPCC.consultaContas') }}'"
                                label="Voltar" theme="info" icon=""/>
                        </div>
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
@section('plugins.DateRangePicker', true)

@section('css')
@stop

@section('js')
<script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script> 

<!--
|--------------------------------------------------------------------------
| Eventos Validate da app
|--------------------------------------------------------------------------
-->
<script>
function parseValorBR(valor) {
    if (!valor) return 0;
    return parseFloat(valor.replace(/\./g, '').replace(',', '.')) || 0;
}

$(function () {

    //Inserção / Atualização Recebimento com Cartão de Débito
    $('#form-pag-din').validate({
        rules: {
            valorPag: {
                required: true
            }
        },
        messages: {
            valorPag: {
                required:  "Por Favor informe o Valor do Pagamento"
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

            let valorPag = parseValorBR($('#valorPag').val());
            let valorAcre = parseValorBR($('#valorAcre').val());
            let valorDes = parseValorBR($('#valorDes').val());
            let total = parseValorBR($('#valorTotpag').val());
            let saldoRest = parseValorBR($('#saldoRest').val());

            // Regra 1: Saldo não pode ser negativo
            if (saldoRest < 0) {
                Swal.fire({
                    confirmButtonColor: "#007bff",
                    title: "Aviso!",
                    text: "O valor informado não pode ser maior que o saldo da conta!",
                    icon: "info"
                });
                return false;
            }

            // Regra 2: Apenas um de acréscimo ou desconto pode estar preenchido
            if (valorAcre > 0 && valorDes > 0) {
                Swal.fire({
                    confirmButtonColor: "#007bff",
                    title: "Aviso!",
                    text: "Não é possível informar Acréscimo e Desconto no mesmo pagamento!",
                    icon: "info"
                });
                return false;
            }

            // Regra 3: Total não pode ser zero
            if (total <= 0) {
                Swal.fire({
                    confirmButtonColor: "#007bff",
                    title: "Aviso!",
                    text: "O valor total do pagamento não pode ser zero!",
                    icon: "info"
                });
                return false;
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

<!--
|--------------------------------------------------------------------------
| Eventos Iniciais da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() { 

        // Máscaras dos campos de valores
        $('#saldo').mask('#.##0,00', {reverse: true});
        $('#valorPag').mask('#.##0,00', {reverse: true});
        $('#valorAcre').mask('#.##0,00', {reverse: true});
        $('#valorDes').mask('#.##0,00', {reverse: true});
        $('#valorTotpag').mask('#.##0,00', {reverse: true});
        $('#saldoRest').mask('#.##0,00', {reverse: true});

        function parseValorBR(valor) {
            if (!valor) return 0;
            return parseFloat(valor.replace(/\./g, '').replace(',', '.')) || 0;
        }

        function atualizarTotalPagamento() {
            let valorPag = parseValorBR($('#valorPag').val());
            let valorAcre = parseValorBR($('#valorAcre').val());
            let valorDes = parseValorBR($('#valorDes').val());
            let saldo = parseValorBR($('#saldo').val());

            // Total considera todos os campos
            let total = valorPag + valorAcre - valorDes;
            $('#valorTotpag').val(total.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));

            // Saldo restante considera apenas o valorPag
            let saldoRest = saldo - valorPag;
            $('#saldoRest').val(saldoRest.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
        }

        // Escuta apenas os campos alteráveis
        $('#valorPag, #valorAcre, #valorDes').on('input', atualizarTotalPagamento);

        // Executa o cálculo ao carregar a página
        atualizarTotalPagamento();
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
