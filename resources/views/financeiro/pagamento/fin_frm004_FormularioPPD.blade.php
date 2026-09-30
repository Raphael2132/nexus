@extends('adminlte::page')

@section('title', 'Pequenas Despesas')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Pagamento</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.homePPD')}}">Pequenas Despesas</a>
            </li>
            <li class="breadcrumb-item active">Formulário de Pagamento</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-12">
        <form method="post" action="{{route('pagamentoPPD.finalizarPPD')}}" id="form-pag-ppd" novalidate="novalidate">
            @csrf 
            @method('post')
            <x-adminlte-card title="Pagamento de Pequenas Despesas" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
                @php 
                    $dadosUsuFin = DB::table('financeiro_tab_usuarios')->where('tabusu_usuario', Auth::user()->usuario_codigo)->where('tabusu_empresa', Auth::user()->usuario_empresa)->first();
                    $dadosRaz = DB::table('financeiro_razoes')->where('razao_codigo', $dadosUsuFin->tabusu_razao)->where('razao_empresa', $dadosUsuFin->tabusu_empresa)->where('razao_tipo', $dadosUsuFin->tabusu_tipo_razao)->first();
                @endphp
                <div class="row">
                    <div style="width:100%; margin: 0 10px 10px 10px; color:#fff">
                        <table style="width:100%; font-style: normal; border-collapse: separate; border-spacing: 5px 5px;">
                            <tbody>
                                <tr style="text-align: center;">
                                    <td style="background-color: #00abab; border-radius: 8px; border: 1px solid #008f8f;"><strong>Usuário</strong></td>
                                    <td style="background-color: #00abab; border-radius: 8px; border: 1px solid #008f8f;"><strong>Caixa Operacional</strong></td>
                                </tr>
                                <tr style="border: 1px solid #008f8f; background-color: #fff; color:#008f8f; text-align: center;">
                                    <td style="border: 1px solid #008f8f; border-radius: 8px; text-align: center;">{{ Auth::user()->usuario_codigo.' - '.Auth::user()->name }}</td>
                                    <td style="border: 1px solid #008f8f; border-radius: 8px; text-align: center;">{{ $dadosUsuFin->tabusu_razao.' - '.$dadosRaz->razao_nome }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                @php
                    $array_emp = HelperArraySelect::arrayEmpresas(1,1);
                    $array_banco = HelperArraySelect::arrayRazoesPorTipo(1,1,$empresaPAG,'BA');
                    $cliente = HelperFormatSelect::formataClientes($clientePAG);

                    if(!empty($dadosPagamento)){
                        
                        $operacaoSel = $dadosPagamento->pagpqd_tip_opr;
                        $despesaSel = $dadosPagamento->pagpqd_cod_des;
                        $numDocSel = $dadosPagamento->pagpqd_num_com;
                        $compSel = $dadosPagamento->pagpqd_cmp;

                        if($operacaoSel == 'DEP'){
                            $array_desp = HelperArraySelect::arrayTipoCreditoFin(1,1,'T1');
                        }else{
                            $array_desp = HelperArraySelect::arrayTipoCreditoFin(1,1,'T4');
                        }

                        $valorSel = $dadosPagamento->pagpqd_vlr;
                        $dtPag = Helper::formatadata($dadosPagamento->pagpqd_dtp);
                        $bancoSel = $dadosPagamento->pagpqd_bco;

                        $dadosHDR = DB::table('financeiro_pagamento_headers')
                        ->where('paghdr_emp', $empresaPAG)
                        ->where('paghdr_cod_pag', $idPagamento)
                        ->first();

                        if(!empty($dadosHDR->paghdr_obs)){
                            $observacao = $dadosHDR->paghdr_obs;
                        }else{
                            $observacao = null;
                        }
                    }else{
                        $operacaoSel = null;
                        $despesaSel = null;
                        $numDocSel = null;
                        $compSel = null;
                        $array_desp = null;
                        $valorSel = null;
                        $dtPag = date('d/m/Y');
                        $bancoSel = null;
                        $observacao = null;
                    }
                @endphp
                <div class="row"> 
                    <x-adminlte-select name="empresa" fgroup-class="col-md-6" disabled>
                        <x-slot name="label">
                            Empresa <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_emp" empty-option="Selecione..." selected="{{ $empresaPAG }}"/>
                    </x-adminlte-select>
                    
                    <x-adminlte-input name="cliente" type="text" value="{{ $cliente }}" fgroup-class="col-md-6" disabled>
                        <x-slot name="label">
                            Beneficiário <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="row"> 
                    <x-adminlte-select name="operacao" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Operação <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="['DEP' => 'Despesas', 'OUT' => 'Outros Débitos']" empty-option="Selecione..." selected="{{ $operacaoSel }}"/>
                    </x-adminlte-select>
                    
                    <x-adminlte-select name="despesa" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Despesa <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_desp" empty-option="Selecione..." selected="{{ $despesaSel }}"/>
                    </x-adminlte-select>
                </div>
                <div class="row bloco-banco"> 
                    <x-adminlte-select name="banco" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Banco <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_banco" empty-option="Selecione..." selected="{{ $bancoSel }}"/>
                    </x-adminlte-select>
                </div>
                <div class="row">
                    <x-adminlte-input name="numComprovante" type="text" value="" placeholder="Número do Comprovante" value="{{ $numDocSel }}" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Número do Comprovante <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>
                    
                    <x-adminlte-input name="complemento" label="Complemento" type="text" value="" placeholder="Complemento" value="{{ $compSel }}" fgroup-class="col-md-6"></x-adminlte-input>
                </div>
                <div class="row">
                    <x-adminlte-input name="valor" label="Valor do Pagamento" type="text" value="{{ $valorSel }}" placeholder="0,00" fgroup-class="col-md-6">
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
                    <x-adminlte-date-range name="dataPag" :config="$config" placeholder="Formato dia/mês/ano" fgroup-class="col-md-6" disabled>
                        <x-slot name="label">
                            Data de Pagamento <span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="far fa-lg fa-calendar-alt"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-date-range>
                    @push('js')<script>$(() => $("#dataPag").val('{{ $dtPag }}'))</script>@endpush
                </div>
                <div class="row">
                    <x-adminlte-textarea name="observacao" label="Observação" rows=3 label-class="text-dark" placeholder="Informe a Observação..." fgroup-class="col-md-6">
                        {{ $observacao }}
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fas fa-lg fa-file-alt text-white"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-textarea>
                </div>
                <x-slot name="footerSlot">
                    <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('pagamentoPPD.excluiPPD', ['empresa' => $empresaPAG, 'idPagamento' => $idPagamento]) }}'" label="Cancelar" theme="info" icon="fa-solid fa-trash"/>
                    <x-adminlte-button class="btn-nexus" type="submit" label="Pagar" theme="info" icon="fa-solid fa-money-check-dollar-pen"/>
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
| Eventos Iniciais da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() { 

        /* ******************** Mascaras de campos float ******************** */

        //Mascaras dos campos de valores
        $('#valor').mask('#.##0,00', {reverse: true});

        var operacao = {!! json_encode($operacaoSel ?? 'DEP') !!};
        
        if( operacao == 'DEP'){
            $('.bloco-banco').hide();
        }else{
            $('.bloco-banco').show();
        }

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
        $('#operacao').change(function(){

            if( $(this).val() ) {
                
                var opr = $(this).val();

                if(opr == 'DEP'){
                    var tab = 'T1';
                    $('.bloco-banco').hide();
                    $('#banco').val('');
                }else{
                    var tab = 'T4';
                    $('.bloco-banco').show();
                    $('#banco').val('');
                }

                var url = "{{ route('ajax.getTipoCreditoPagRec', [':tab']) }}";
                var url = url.replace(':tab', tab);

                $.ajax({
                    url: url,
                    dataType: "JSON",
                    type: 'GET',
                    data: {
                        '_token': $('meta[name=csrf-token]').attr("content"),
                        '_method': 'GET',
                        "tab": tab
                    },
                    success: function (data)
                    {
                        if(data.credito_ajax_existe == 'S'){

                            var options = '<option value="">Selecione...</option>';	

                            for (var i = 0; i < data.credito_ajax.length; i++) {

                                options += '<option value="' + data.credito_ajax[i].cod + '">' + data.credito_ajax[i].desc + '</option>';
                            }	

                            $('#despesa').html(options);

                        }else{
                            $('#despesa').html('<option value="">Selecione...</option>');
                        }
                    }
                });
            } else {
                $('.bloco-banco').hide();
                $('#banco').val('');
                $('#despesa').html('<option value="">Selecione...</option>');
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
    $('#form-pag-ppd').validate({
        rules: {
            empresa: {
                required: true
            },
            cliente: {
                required: true
            },
            operacao: {
                required: true
            },
            despesa: {
                required: true
            },
            numComprovante: {
                required: true,
                maxlength: 20
            },
            valor: {
                required: true,
                maxlength: 20
            },
            complemento: {
                maxlength: 40
            },
            observacao: {
                maxlength: 255
            },
            banco: {
                required: {
                    depends: function(element) {
                        return $('#operacao').val() === 'OUT';
                    }
                },
            },
        },
        messages: {
            empresa: {
                required:  "Por Favor informe a Empresa"
            },
            cliente: {
                required:  "Por Favor informe o Beneficiário"
            },
            operacao: {
                required: "Por Favor informe a Operação"
            },
            despesa: {
                required: "Por Favor informe a Despesa"
            },
            numComprovante: {
                required: "Por Favor informe o Número do Comprovante",
                maxlength: "Limite máximo do Número do Comprovante é de 20 caracteres"
            },
            valor: {
                required: "Por Favor informe o Valor do Pagamento",
                maxlength: "Limite máximo é de 15 digitos"
            },
            complemento: {
                maxlength: "Limite máximo do Complemento é de 40 caracteres"
            },
            observacao: {
                maxlength: "Limite máximo da Observação é de 255 caracteres"
            },
            banco: {
                required:  "Por Favor informe o Banco"
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

            //Confirma se vai realizar o pagamento
            Swal.fire({
                title: 'Deseja realmente realizar o pagamento?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim',
                confirmButtonColor: "#007bff",
                cancelButtonColor: '#dc3545',
                cancelButtonText: 'Não',
                reverseButtons: true
            }).then((result) => {

                //Se confirmou vamos validar o valor informado
                if (result.isConfirmed) {

                    function limparValorBR(valor) {
                        return parseFloat(valor.replace(/\./g, '').replace(',', '.')) || 0;
                    }

                    let valor = limparValorBR($('#valor').val());

                    // Verifica se Valor a Receber foi informado
                    if (isNaN(valor) || valor === 0) {
                        Swal.fire({
                            confirmButtonColor: "#007bff",
                            title: "Aviso!",
                            text: "Obrigatório informar o Valor a Receber!",
                            icon: "info"
                        });
                        return false;
                    }

                    // Ativar campos desativados antes de enviar
                    $(':disabled').each(function () {
                        $(this).removeAttr('disabled');
                    });

                    form.submit(); // envia o formulário normalmente
                }else{
                    return false;
                }
            });
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
