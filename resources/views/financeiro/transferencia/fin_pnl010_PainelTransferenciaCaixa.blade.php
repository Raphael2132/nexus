@extends('adminlte::page')

@section('title', 'Transferência')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Transferência de Caixa</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.homeTransfCaixa')}}">Filtro</a>
            </li>
            <li class="breadcrumb-item active">Painel de Transferência</li>
        </ol>
    </div>
</div>
@stop


@section('content')
<div class="col-md-12">

    <!-- ********** Painel Principal da Emissão de NF ********** -->
    <x-adminlte-card title="Painel da Transferência de Caixa" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>

        @php 
            $empresa = HelperFormatSelect::formataEmpresaCodigoNome(Auth::user()->usuario_empresa);

            $origemFormat = HelperFormatSelect::formataTipoCC($dadosOrigem->razao_tipo);
            $destFormat = HelperFormatSelect::formataTipoCC($dadosDestino->razao_tipo);
        @endphp
        <div class="row">
            <div style="width:100%; margin: 0 10px 10px 10px; color:#fff">
                <table style="width:100%; font-style: normal; border-collapse: separate; border-spacing: 5px 5px;">
                    <tbody>
                        <tr style="text-align: center;">
                            <td style="background-color: #00abab; border-radius: 8px; border: 1px solid #008f8f;"><strong>Empresa</strong></td>
                            <td style="background-color: #00abab; border-radius: 8px; border: 1px solid #008f8f;"><strong>Usuário</strong></td>
                        </tr>
                        <tr style="border: 1px solid #008f8f; background-color: #fff; color:#008f8f; text-align: center;">
                            <td style="border: 1px solid #008f8f; border-radius: 8px; text-align: center;">{{ $empresa }}</td>
                            <td style="border: 1px solid #008f8f; border-radius: 8px; text-align: center;">{{ Auth::user()->usuario_codigo.' - '.Auth::user()->name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">      
            <div class="col-md-6">  
                <table class="table tabela-saldo-te tabela-custom-nexus" style="padding: .25rem;">
                    <tbody>
                        <tr>
                            <th colspan="5">Saldo em Dinheiro, Cartão e Cheque do Caixa Operacional</th>
                        </tr>
                        <tr>
                            <td style="width: 40%;"><strong>Cartão de Crédito:</strong></td>
                            <td style="text-align: left">{{ 'R$'.Helper::formataValorMonetario($vlrCredito) }}</td>
                            <td style="width: 20%;"><strong>Quantidade:</strong></td>
                            <td style="text-align: left">{{ $qtdCredito }}</td>
                            <td style="text-align: center;">
                                <a href="{{route('transferencia.consultaItensTransf',['tipo' => '3'])}}" title="Editar">
                                    <i class="fa-solid fa-pen-to-square text-primary"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 40%;"><strong>Cartão de Débito:</strong></td>
                            <td style="text-align: left">{{ 'R$'.Helper::formataValorMonetario($vlrDebito) }}</td>
                            <td style="width: 20%;"><strong>Quantidade:</strong></td>
                            <td style="text-align: left">{{ $qtdDebito }}</td>
                            <td style="text-align: center;">
                                <a href="{{route('transferencia.consultaItensTransf',['tipo' => '2'])}}" title="Editar">
                                    <i class="fa-solid fa-pen-to-square text-primary"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 40%;"><strong>Cheque:</strong></td>
                            <td style="text-align: left">{{ 'R$'.Helper::formataValorMonetario($vlrCheque) }}</td>
                            <td style="width: 20%;"><strong>Quantidade:</strong></td>
                            <td style="text-align: left">{{ $qtdCheque }}</td>
                            <td style="text-align: center;">
                                <a href="{{route('transferencia.consultaItensTransf',['tipo' => '1'])}}" title="Editar">
                                    <i class="fa-solid fa-pen-to-square text-primary"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 40%;"><strong>Dinheiro:</strong></td>
                            <td style="text-align: left">{{ 'R$'.Helper::formataValorMonetario($dadosOrigem->razao_saldo_din) }}</td>
                            <td style="width: 20%;"><strong>Transferir:</strong></td>
                            <td style="text-align: left">{{ 'R$'.Helper::formataValorMonetario($vlrDinheiro) }}</td>
                            <td style="text-align: center;">
                                <a href="javascript:void(0)"
                                    title="Editar"
                                    data-toggle="modal"
                                    data-target="#modalDinheiro">
                                    <i class="fa-solid fa-pen-to-square text-primary"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table> 
            </div>
            <div class="col-md-6">       
                <table class="table tabela-saldo-te tabela-custom-nexus" style="padding: .25rem;">
                    <tbody>
                        <tr>
                            <th colspan="1">Dados da Origem e Destino</th>
                        </tr>
                    </tbody>
                </table> 
                <div class="text-muted">
                    <div class="row">
                        <p class="text-sm col-md-6">Origem
                            <b class="d-block">{{ $origemFormat }}</b>
                        </p>
                        <p class="text-sm col-md-6">Destino
                            <b class="d-block">{{ $destFormat }}</b>
                        </p>
                    </div>
                    <div class="row">
                        <p class="text-sm col-md-6">Razão de Origem
                            <b class="d-block">{{ $dadosOrigem->razao_codigo.' - '.$dadosOrigem->razao_nome }}</b>
                        </p>
                        <p class="text-sm col-md-6">Razão de Destino
                            <b class="d-block">{{ $dadosDestino->razao_codigo.' - '.$dadosDestino->razao_nome }}</b>
                        </p>
                    </div>
                    <div class="row">
                        <p class="text-sm col-md-6">Saldo Atual
                            <b class="d-block">{{ 'R$'.Helper::formataValorMonetario($dadosOrigem->razao_saldo_atu) }}</b>
                        </p>
                        <p class="text-sm col-md-6">Saldo Atual
                            <b class="d-block">{{ 'R$'.Helper::formataValorMonetario($dadosDestino->razao_saldo_atu) }}</b>
                        </p>
                    </div>
                </div> 
            </div>
        </div>
        <div class="row">      
            <table class="table tabela-saldo-te tabela-custom-nexus" style="padding: .25rem;">
                <tbody>
                    <tr>
                        <th colspan="2">Total da Transferência</th>
                    </tr>
                    <tr>
                        <td style="width: 20%;"><strong>Valor da Transferência:</strong></td>
                        <td id="te-saldo-atual" style="text-align: left">{{ 'R$'.Helper::formataValorMonetario($totalTrans) }}</td>
                    </tr>
                </tbody>
            </table> 
        </div>
        <form method="post" action="{{route('transferencia.finalizarTransf')}}" id="formulario-finalizar" novalidate="novalidate">
        @csrf 
        @method('post')
            <div class="row">
                <x-adminlte-textarea name="observacao" label="Observações" rows=3 igroup-size="sm" label-class="text-dark" placeholder="Escreva sua menssagem..." fgroup-class="col-md-12" >
                    <x-slot name="prependSlot">
                        <div class="input-group-text x-slot-nexus">
                            <i class="fas fa-lg fa-file-alt text-white"></i>
                        </div>
                    </x-slot>
                </x-adminlte-textarea>
            </div>
        </form>
        
        <!-- Modal da edição do valor em dinheiro -->
        <form method="post" action="{{route('transferencia.transfUpDinheiro')}}" id="formulario-dinheiro" novalidate="novalidate">
            @csrf 
            @method('post')
            <x-adminlte-modal id="modalDinheiro" title="Valor da Transferência em Dinheiro" size="xl" theme="modal-nexus" icon="fa-solid fa-cash-register" v-centered scrollable>
                
                <div class="col-md-12" style="height:auto;">
                    <div class="row">
                        <x-adminlte-input name="saldoDinheiro" label="Saldo em Dinheiro" type="text" value="{{ $dadosOrigem->razao_saldo_din }}" placeholder="0,00" fgroup-class="col-md-12" igroup-size="sm" disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text x-slot-nexus">
                                    <i class="fa-solid fa-brazilian-real-sign"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                    </div>
                    <div class="row">
                        <x-adminlte-input name="valorDinheiro" label="Valor da Transferência" type="text" value="{{ Helper::formataValorMonetario($vlrDinheiro) }}" placeholder="0,00" fgroup-class="col-md-12" igroup-size="sm">
                            <x-slot name="prependSlot">
                                <div class="input-group-text x-slot-nexus">
                                    <i class="fa-solid fa-brazilian-real-sign"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                    </div>
                </div>
                <!-- Criação dos botões do Modal -->  
                <x-slot name="footerSlot">
                    <x-adminlte-button class="btn-nexus mr-auto" theme="" label="Salvar" icon="fa-solid fa-share-from-square" type="submit"/>
                    <x-adminlte-button class="btn-nexus" theme="" label="Voltar" data-dismiss="modal"/>
                </x-slot>
                
            </x-adminlte-modal>
        </form>
        
        <x-slot name="footerSlot">
            <div style="float: left;">
                <x-adminlte-button class="btn-nexus" type="submit" form="formulario-finalizar" label="Confirmar" theme="" icon="fa-solid fa-check"/>
            </div>
            <div style="float: right;">
                <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.homeTransfCaixa') }}'" label="Voltar" theme="" icon=""/>
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
        $('#valorDinheiro').mask('#.##0,00', {reverse: true});
        $('#saldoDinheiro').mask('#.##0,00', {reverse: true});

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

    });
</script>

<!--
|--------------------------------------------------------------------------
| Eventos Validate da app
|--------------------------------------------------------------------------
-->
<script>
$(function () {

    //Inserção da Conta Corrente no Recebimento
    $('#formulario-dinheiro').validate({
        rules: {
            valorDinheiro: {
                required: true,
                maxlength: 20
            },
        },
        messages: {
            valorDinheiro: {
                required:  "Por Favor informe o Valor da Transferência",
                maxlength: "Limite máximo do valor é de 15 digitos",
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
            let dinheiro = $('#valorDinheiro').val().replace(/\./g, '').replace(',', '.');
            let saldo = $('#saldoDinheiro').val().replace(/\./g, '').replace(',', '.');

            // Converte para número (se vazio vira 0)
            let valDinheiro = parseFloat(dinheiro) || 0;
            let valSaldo = parseFloat(saldo) || 0;

            // Verifica se o desconto é igual ou maior que o pagamento
            if (valDinheiro > valSaldo) {
                Swal.fire({
                    icon: 'info',
                    title: 'Aviso!',
                    text: 'O Valor da Transferência não pode ser maior que o Saldo em Dinheiro.',
                    confirmButtonColor: "#007bff",
                    confirmButtonText: 'Ok'
                });
                return false; // Impede o envio do formulário
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

