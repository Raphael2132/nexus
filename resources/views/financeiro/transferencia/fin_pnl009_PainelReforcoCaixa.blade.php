@extends('adminlte::page')

@section('title', 'Transferência')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Reforço de Caixa</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.homeReforcoCaixa')}}">Filtro</a>
            </li>
            <li class="breadcrumb-item active">Painel de Transferência</li>
        </ol>
    </div>
</div>
@stop


@section('content')
<div class="col-md-12">

    <!-- ********** Painel Principal da Emissão de NF ********** -->
    <x-adminlte-card title="Painel do Reforço de Caixa" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
        
        <!-- Posiciona os blocos do lado esquerdo e direito na mesma linha -->
        <div class="row">

            <!-- ************************************************** Bloco do Lado Esquerdo do Painel Principal ************************************************** -->
            <div class="col-md-6">
                <form method="post" action="{{route('transferencia.painelReforcoCaixa')}}" id="form-transf" novalidate="novalidate">
                    @csrf 
                    @method('post')
                    <x-adminlte-card title="Dados da Transferência" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
                        @php
                            $dataFormat = date('d/m/Y');
                        @endphp
                        <div class="text-muted">
                            <div class="row">
                                <p class="text-sm col-md-4">Tesouraria de Origem
                                    <b class="d-block">{{ $dadosTE->razao_codigo.' - '.$dadosTE->razao_nome }}</b>
                                </p>
                                <p class="text-sm col-md-4">Caixa de Destino
                                    <b class="d-block">{{ $dadosCX->razao_codigo.' - '.$dadosCX->razao_nome }}</b>
                                </p>
                                <p class="text-sm col-md-4">Data da Transferência
                                    <b class="d-block">{{ $dataFormat }}</b>
                                </p>
                            </div>
                        </div>    
                        <div class="row">
                            <x-adminlte-input name="valorTransf" label="Valor Inicial" type="text" value="" placeholder="0,00" fgroup-class="col-md-12" igroup-size="sm">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fa-solid fa-brazilian-real-sign"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                        <div class="row">
                            <x-adminlte-textarea name="observacao" label="Observações" rows=3 igroup-size="sm" label-class="text-dark" placeholder="Escreva sua menssagem..." fgroup-class="col-md-12" >
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fas fa-lg fa-file-alt text-white"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-textarea>
                        </div>
                    </x-adminlte-card> 
                </form>  
            </div>

            <!-- ************************************************** Bloco do Lado Direito do Painel Principal ************************************************** -->
            <div class="col-md-6">
                <x-adminlte-card title="Saldos" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
                    <div class="row">
                        <div class="col-md-6">       
                            <table class="table tabela-saldo-te tabela-custom-nexus" style="padding: .25rem;">
                                <tbody>
                                    <tr>
                                        <th colspan="2">Tesouraria</th>
                                    </tr>
                                    <tr>
                                        <td style="width: 40%;"><strong>Saldo Atual:</strong></td>
                                        <td id="te-saldo-atual" style="text-align: left">{{ Helper::formataValorMonetario($dadosTE->razao_saldo_atu) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 40%;"><strong>Transferência:</strong></td>
                                        <td id="te-transferencia" style="text-align: left">{{ Helper::formataValorMonetario(0) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 40%;"><strong>Novo Saldo:</strong></td>
                                        <td id="te-novo-saldo" style="text-align: left">{{ Helper::formataValorMonetario($dadosTE->razao_saldo_atu) }}</td>
                                    </tr>
                                </tbody>
                            </table> 
                        </div>
                        <div class="col-md-6">       
                            <table class="table tabela-saldo-cx tabela-custom-nexus" style="padding: .25rem;">
                                <tbody>
                                    <tr>
                                        <th colspan="2">Caixa Operacional</th>
                                    </tr>
                                    <tr>
                                        <td style="width: 40%;"><strong>Saldo Atual:</strong></td>
                                        <td id="cx-saldo-atual" style="text-align: left">{{ Helper::formataValorMonetario($dadosCX->razao_saldo_atu) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 40%;"><strong>Transferência:</strong></td>
                                        <td id="cx-transferencia" style="text-align: left">{{ Helper::formataValorMonetario(0) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 40%;"><strong>Novo Saldo:</strong></td>
                                        <td id="cx-novo-saldo" style="text-align: left">{{ Helper::formataValorMonetario($dadosCX->razao_saldo_atu) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </x-adminlte-card>
            </div>

        </div>
        <x-slot name="footerSlot">
            <div style="float: left;">
                <x-adminlte-button class="btn-nexus" type="submit" label="Confirmar" theme="" icon="fa-solid fa-check"/>
            </div>
            <div style="float: right;">
                <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.homeReforcoCaixa') }}'" label="Voltar" theme="" icon=""/>
            </div>
        </x-slot>
    </x-adminlte-card>

</div>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.Sweetalert2', true)
@section('plugins.jqueryValidation', true)

@section('css')
@stop

@section('js')
<script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script>

<!--
|--------------------------------------------------------------------------
| Eventos Iniciais da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() { 

        /* ******************** Mascaras de campos float ******************** */

        //Mascaras dos campos de valores
        $('#valorTransf').mask('#.##0,00', {reverse: true});

    });
</script>

<!--
|--------------------------------------------------------------------------
| Eventos onClick da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() {
        
    });
</script>

<!--
|--------------------------------------------------------------------------
| Eventos onChange da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function () {

        const $input = $('#valorTransf');

        const $teSaldoAtual = $('#te-saldo-atual');
        const $teTransferencia = $('#te-transferencia');
        const $teNovoSaldo = $('#te-novo-saldo');

        const $cxSaldoAtual = $('#cx-saldo-atual');
        const $cxTransferencia = $('#cx-transferencia');
        const $cxNovoSaldo = $('#cx-novo-saldo');

        function parseMoney(valor) {
            if (!valor) return 0;
            return parseFloat(
                valor.replace(/\./g, '').replace(',', '.')
            ) || 0;
        }

        function formatMoney(valor) {
            return valor.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        const teSaldoAtual = parseMoney($teSaldoAtual.text());
        const cxSaldoAtual = parseMoney($cxSaldoAtual.text());

        $input.on('input keyup change', function () {

            const valorTransf = parseMoney($(this).val());

            // TESOURARIA → SUBTRAI
            $teTransferencia.text(formatMoney(valorTransf));
            $teNovoSaldo.text(formatMoney(teSaldoAtual - valorTransf));

            // CAIXA → SOMA
            $cxTransferencia.text(formatMoney(valorTransf));
            $cxNovoSaldo.text(formatMoney(cxSaldoAtual + valorTransf));
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

    // Adiciona uma regra personalizada para não permitir zero
    $.validator.addMethod("notZero", function (value, element) {
        // Remove possíveis vírgulas e pontos para converter corretamente
        let val = value.replace(/\./g, '').replace(',', '.');
        return parseFloat(val) > 0;
    }, "O valor não pode ser zero.");

    //Inserção da Conta Corrente no Recebimento
    $('#form-edit-cc').validate({
        rules: {
            valorPagamento: {
                required: true,
                maxlength: 20,
                notZero: true
            },
            valorAcres: {
                maxlength: 20
            },
            valorDesc: {
                maxlength: 20
            },
            valorMulta: {
                maxlength: 20
            },
            variacaoMonet: {
                maxlength: 20
            },
        },
        messages: {
            valorPagamento: {
                required:  "Por Favor informe o Valor do Pagamento",
                maxlength: "Limite máximo do valor é de 15 digitos",
                notZero: "O valor do pagamento deve ser maior que zero"
            },
            valorAcres: {
                maxlength: "Limite máximo do valor é de 15 digitos"
            },
            valorDesc: {
                maxlength: "Limite máximo do valor é de 15 digitos"
            },
            valorMulta: {
                maxlength: "Limite máximo do valor é de 15 digitos"
            },
            variacaoMonet: {
                maxlength: "Limite máximo do valor é de 15 digitos"
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
        },
        // Ao submeter o formulário, reativar os campos desativados
        submitHandler: function (form) {

            // Pega os valores dos campos
            let pagamento = $('#valorPagamento').val().replace(/\./g, '').replace(',', '.');
            let desconto = $('#valorDesc').val().replace(/\./g, '').replace(',', '.');
            let saldoCC = $('#saldoCC').val().replace(/\./g, '').replace(',', '.');

            // Converte para número (se vazio vira 0)
            let valPagamento = parseFloat(pagamento) || 0;
            let valDesc = parseFloat(desconto) || 0;
            let valSaldoCC = parseFloat(saldoCC) || 0;

            // Verifica se o desconto é igual ou maior que o pagamento
            if (valDesc >= valPagamento) {
                Swal.fire({
                    icon: 'info',
                    title: 'Aviso!',
                    text: 'O valor do desconto não pode ser igual ou maior que o valor do pagamento.',
                    confirmButtonColor: "#007bff",
                    confirmButtonText: 'Ok'
                });
                return false; // Impede o envio do formulário
            }

             // Verifica o valor pago da conta é maior que o saldo restante
            if (valPagamento > valSaldoCC) {
                Swal.fire({
                    icon: 'info',
                    title: 'Aviso!',
                    text: 'O Valor do Pagamento não pode ser maior que o Saldo Restante da conta corrente.',
                    confirmButtonColor: "#007bff",
                    confirmButtonText: 'Ok'
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
| Eventos de Menssagens da APP
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
            text: '{!! session("error") !!}',
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
            html: "{!! session('success2') !!}",
            icon: "success",
            customClass: {
                icon: "no-before-icon",
            }
        });
    @endif
</script>
@stop

