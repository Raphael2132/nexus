@extends('adminlte::page')

@section('title', 'Contas Correntes')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Pagamento de Adiantamento de Fornecedor</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Inclusão</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-8">
        <form method="post" action="{{route('pagamentoPCC.iniciaPCCIncAF')}}" id="form-af" novalidate="novalidate">
            @csrf 
            @method('post')
            <x-adminlte-card title="Inclusão de Adiantamento de Fornecedor" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
                @php
                    $array_emp = HelperArraySelect::arrayEmpresas(1,1);
                    $array_tcc = HelperArraySelect::arrayTipoContasPagamento(1,1);
                    $htmlCli = HelperDataList::geraDatalistGeralFornecedores('fornecedores');
                    $array_scc = HelperArraySelect::arraySubtipo(1,1,'AF');

                    //Echo adiciona o html ao campo dos fornecedores
                    echo $htmlCli;

                @endphp
                <div class="row"> 
                    <x-adminlte-select name="empresa" fgroup-class="col-md-6" igroup-size="sm">
                        <x-slot name="label">
                            Empresa <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_emp" empty-option="Selecione..." selected=""/>
                    </x-adminlte-select>
                      
                    <x-adminlte-input name="cliente" type="search" list="fornecedores" autocomplete="off" value="" fgroup-class="col-md-6" igroup-size="sm">
                        <x-slot name="label">
                            Beneficiário <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="row">
                    @php
                        $config = Helper::dtRangeDataPtBR();
                    @endphp
                    <x-adminlte-date-range name="dataEmi" :config="$config" placeholder="de dia/mês/ano" fgroup-class="col-md-6" igroup-size="sm">
                        <x-slot name="label">
                            Data de Emissão <span style="color:red;">*</span>
                        </x-slot>    
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="far fa-lg fa-calendar-alt"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-date-range>

                    <x-adminlte-date-range name="dataVct" :config="$config" placeholder="de dia/mês/ano" fgroup-class="col-md-6" igroup-size="sm">
                        <x-slot name="label">
                            Data de Vencimento <span style="color:red;">*</span>
                        </x-slot>     
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="far fa-lg fa-calendar-alt"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-date-range>
                </div>
                <div class="row"> 
                    <x-adminlte-select name="subTipAF" fgroup-class="col-md-6" igroup-size="sm">
                        <x-slot name="label">
                            Sub Tipo <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_scc" empty-option="Selecione..." selected=""/>
                    </x-adminlte-select>
                    
                    <x-adminlte-input name="compAF" label="Complemento" type="text" value="" placeholder="Complemento" fgroup-class="col-md-6" igroup-size="sm"></x-adminlte-input>
                </div>
                <div class="row">
                    <x-adminlte-input name="valorAF" type="text" value="" placeholder="0,00" fgroup-class="col-md-6" igroup-size="sm">
                        <x-slot name="label">
                            Valor da Conta<span style="color:red;">*</span>
                        </x-slot>
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

                <!-- Campo escondido da pergunta -->
                <input type="hidden" name="pagar_agora" id="pagar_agora" value="N">

                <x-slot name="footerSlot">
                    <x-adminlte-button class="btn-nexus" type="submit" label="Inserir" theme="" icon="fa-solid fa-share-from-square"/>
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
        $('#valorAF').mask('#.##0,00', {reverse: true});
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

    //Inserção / Atualização Recebimento com Cartão de Débito
    $('#form-af').validate({
        rules: {
            empresa: {
                required: true
            },
            cliente: {
                required: true
            },
            dataEmi: {
                required: true
            },
            dataVct: {
                required: true
            },
            subTipAF: {
                required: true
            },
            compAF: {
                maxlength: 40
            },
            valorAF: {
                required: true,
                maxlength: 20,
                notZero: true
            },
        },
        messages: {
            empresa: {
                required:  "Por Favor informe a Empresa"
            },
            cliente: {
                required:  "Por Favor informe o Beneficiário"
            },
            dataEmi: {
                required:  "Por Favor informe a Data de Emissão"
            },
            dataVct: {
                required:  "Por Favor informe a Data de Vencimento"
            },
            subTipAF: {
                required:  "Por Favor informe o Sub Tipo",
            },
            compAF: {
                maxlength: "Limite máximo do Complemento é de 40 caracteres"
            },
            valorAF: {
                required:  "Por Favor informe o Valor do Pagamento",
                maxlength: "Limite máximo do valor é de 15 digitos",
                notZero: "O valor do pagamento deve ser maior que zero"
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

            // ---- Verificação de Data ----
            const dtIni = $('#dataEmi').val();
            const dtFin = $('#dataVct').val();

            if (dtIni && dtFin) {

                const [d1, m1, y1] = dtIni.split('/');
                const [d2, m2, y2] = dtFin.split('/');

                // Date(year, monthIndex, day)
                const dataIni = new Date(y1, m1 - 1, d1);
                const dataFin = new Date(y2, m2 - 1, d2);

                if (dataFin < dataIni) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Aviso!',
                        text: 'A data de vencimento deve ser maior ou igual à data de emissão.',
                        confirmButtonColor: '#007bff',
                        confirmButtonText: 'OK'
                    });
                    return false; // bloqueia envio
                }
            }

            // Pergunta se deseja pagar agora
            Swal.fire({
                title: 'Deseja pagar agora?',
                text: 'O adiantamento será lançado para pagamento imediato.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sim, pagar agora',
                cancelButtonText: 'Não, pagar depois',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#007bff'
            }).then((result) => {

                if (result.isConfirmed) {
                    $('#pagar_agora').val('S');
                } else {
                    $('#pagar_agora').val('N');
                }

                // reativa campos desabilitados
                $(':disabled').prop('disabled', false);

                form.submit();
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
            html: "{!! session('success2') !!}",
            icon: "success",
            customClass: {
                icon: "no-before-icon",
            }
        });
    @endif
</script>
@stop
