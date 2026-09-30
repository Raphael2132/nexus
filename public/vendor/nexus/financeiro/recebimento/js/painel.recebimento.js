/* *****
|--------------------------------------------------------------------------
| Eventos de geração dos Datatable
|--------------------------------------------------------------------------
|
| Adicionamos aqui todos os eventos relacionados à criação e manipulação de eventos do datatable.
|
***** */

$(() => {
    
    /* ********** Inicializa o Datatable ********** */
    var tableValores = $('#tabela-valores').DataTable({
        pageLength: 3,
        searching: false,
        lengthChange: false,
        language: dataTableLangPtBR,
        order: [
            [0, 'asc']
        ],
        pagingType: 'full_numbers',
        processing: true,
        columns: [
            { orderable: false, visible: false },
            { orderable: false },
            { orderable: false },
            { orderable: false },
            { orderable: false },
        ],
    });

    /* ********** Inicializa o Datatable ********** */
    var tableValores = $('#tabela-sel-cct').DataTable({
        pageLength: 10,
        searching: false,
        lengthChange: false,
        language: dataTableLangPtBR,
        order: [
            [1, 'asc'],
            [3, 'asc']
        ],
        pagingType: 'full_numbers',
        processing: true,
        columns: [
            { orderable: false },
            { orderable: false },
            { orderable: false, visible: false },
            { orderable: false },
            { orderable: false },
            { orderable: false }
        ],
    });

});

/* ------------------------------ Final da Inicialização do Datatable ------------------------------ */

/*
|--------------------------------------------------------------------------
| Eventos Iniciais da app
|--------------------------------------------------------------------------
*/
$(document).ready(function() { 

    /* **************************************** Eventos Iniciais do bloco  - REC_DINHEIRO **************************************** */

    if(estagioAPP == 'REC_DINHEIRO' ){

        /* ******************** Mascaras de campos float ******************** */

        //Mascaras dos campos de valores
        $('#valDin').mask('#.##0,00', {reverse: true});
    }

    /* **************************************** Eventos Iniciais do bloco  - REC_PIX **************************************** */

    if(estagioAPP == 'REC_PIX' ){

        /* ******************** Mascaras de campos float ******************** */

        //Mascaras dos campos de valores
        $('#valPIX').mask('#.##0,00', {reverse: true});
    }

    /* **************************************** Eventos Iniciais do bloco  - REC_C_CREDITO **************************************** */

    if(estagioAPP == 'REC_C_CREDITO' ){

        /* ******************** Mascaras de campos float ******************** */

        //Mascaras dos campos de valores
        $('#valCCR').mask('#.##0,00', {reverse: true});

        if(subEstagioRec == 'NEW'){
            $('.bloco-dados-cc').hide();
            $('.btn-salvar-rec-ccr').hide();
            $('.btn-edit-rec-ccr').hide();
        }else{
            $('#valCCR').prop('disabled', true);
            $('.btn-edit-rec-ccr').show();
            $('.bloco-dados-cc').show();
            $('.btn-salvar-rec-ccr').show();
            $('.btn-prox-rec-ccr').hide();
        }
    }

    if(estagioAPP == 'REC_C_DEBITO' ){

        /* ******************** Mascaras de campos float ******************** */

        //Mascaras dos campos de valores
        $('#valCDB').mask('#.##0,00', {reverse: true});

    }

    /* **************************************** Eventos Iniciais do bloco  - REC_CHEQUE **************************************** */

    if(estagioAPP == 'REC_CHEQUE' ){

        /* ******************** Mascaras de campos float ******************** */

        //Mascaras dos campos de valores
        $('#valCHQ').mask('#.##0,00', {reverse: true});
    }

    /* **************************************** Eventos Iniciais do bloco  - REC_FIL_C_CORRENTE **************************************** */

    if(estagioAPP == 'REC_FIL_C_CORRENTE' ){

        /* ******************** Mascaras de campos float ******************** */

        //Mascaras dos campos de valores
        $('#vlrIniFiltro').mask('#.##0,00', {reverse: true});
        $('#vlrFinFiltro').mask('#.##0,00', {reverse: true});
    }

    /* **************************************** Eventos Iniciais do bloco  - REC_CONTA_CORRENTE **************************************** */

    if(estagioAPP == 'REC_CONTA_CORRENTE' ){

        /* ******************** Mascaras de campos float ******************** */

        //Mascaras dos campos de valores
        $('#valCCT').mask('#.##0,00', {reverse: true});

        // Função para remover máscara e converter para número
        function limparMoeda(valor) {
            return parseFloat(valor.replace(/\./g, '').replace(',', '.')) || 0;
        }

        // Função para formatar número como moeda brasileira
        function formatarMoeda(valor) {
            return valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        }

        // Evento ao alterar o campo
        $("#valCCT").on("input", function() {
            let valorDigitado = limparMoeda($(this).val()); // Remove máscara e converte para número
            let saldoRestante = saldoInicial - valorDigitado; // Calcula o saldo restante

            // Atualiza o saldo formatado
            $("#saldoRestante").text(formatarMoeda(saldoRestante));
        });
    }
});

/*
|--------------------------------------------------------------------------
| Eventos onClick da app
|--------------------------------------------------------------------------
*/
$(document).ready(function() {

    /* **************************************** Eventos de click do bloco  - REC_C_CREDITO **************************************** */

    if(estagioAPP == 'REC_C_CREDITO' ){

        $('.btn-prox-rec-ccr').click(function() {

            // Verifica se o campo #valCCR está preenchido e se não é 0
            var valCCR = $('#valCCR').val().replace(/\./g, '').replace(',', '.'); // Remove o ponto e substitui a vírgula por ponto (caso tenha máscara)

            if (valCCR == '' || parseFloat(valCCR) === 0) {
                Swal.fire({
                    confirmButtonColor: "#007bff",
                    title: "Atenção!!!",
                    text: "Por favor, informe o valor do recebimento!",
                    icon: "info"
                });
                return;
            }

            $('#valCCR').prop('disabled', true);
            
            $('.bloco-dados-cc').show();
            $('.btn-salvar-rec-ccr').show();
            $('.btn-edit-rec-ccr').show();
            $('.btn-prox-rec-ccr').hide();
        });

        $('.btn-edit-rec-ccr').click(function() {

            $('#valCCR').prop('disabled', false);

            $('#admCCR').val('');
            $('#bandeiraCCR').val('');
            $('#numDocCCR').val('');
            //$('#parcelaCCR').val('');
            $('#compCCR').val('');
            $('#observacao').val('');
            console.log('aki');
            $('#parcelaCCR').html('<option value="">Selecione...</option>');

            $('.bloco-dados-cc').hide();
            $('.btn-salvar-rec-ccr').hide();
            $('.btn-edit-rec-ccr').hide();
            $('.btn-prox-rec-ccr').show();
        });
    }
});

/*
|--------------------------------------------------------------------------
| Eventos onChange da app
|--------------------------------------------------------------------------
*/
$(document).ready(function() {

    /* **************************************** Eventos de change do bloco  - REC_C_CREDITO **************************************** */

    if(estagioAPP == 'REC_C_CREDITO' ){

        //Evento de carregamento ajax da Administradora do cartão de crédito
        $('#admCCR').change(function(){

            if( $(this).val() ) {
                
                var emp = empresaREC;
                var adm = $(this).val();
                var val = $('#valCCR').val();

                var url = getParcelasUrl.replace(':adm', adm).replace(':emp', emp).replace(':val', val);

                $.ajax({
                    url: url,
                    dataType: "JSON",
                    type: 'GET',
                    data: {
                        '_token': $('meta[name=csrf-token]').attr("content"),
                        '_method': 'GET',
                        "adm": adm,
                        "emp": emp,
                        "val": val
                    },
                    success: function (data)
                    {
                        if(data.parcelas_ajax_existe == 'S'){

                            var options = '<option value="">Selecione...</option>';	

                            for (var i = 0; i < data.parcelas_ajax.length; i++) {

                                options += '<option value="' + data.parcelas_ajax[i].qtd + '">' + data.parcelas_ajax[i].qtd_valor + '</option>';
                            }	

                            $('#parcelaCCR').html(options);

                        }else{
                            $('#parcelaCCR').html('<option value="">Selecione...</option>');
                        }
                    }
                });
            } else {
                $('#parcelaCCR').html('<option value="">Selecione...</option>');
            }
        });
    }
});


/*
|--------------------------------------------------------------------------
| Eventos Validate da app
|--------------------------------------------------------------------------
*/
$(function () {

    //Inserção / Atualização Recebimento em Dinheiro
    $('#form-rec-din').validate({
        rules: {
            valDin: {
                required: true,
                maxlength: 20
            },
            observacao: {
                maxlength: 255
            },
        },
        messages: {
            valDin: {
                required:  "Por Favor informe o Valor em Dinheiro",
                maxlength: "Limite máximo do valor é de 15 digitos"
            },
            observacao: {
                maxlength: "Limite máximo da Observação é 255 caracteres"
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
            // Ativar campos desativados antes de enviar
            $(':disabled').each(function () {
                $(this).removeAttr('disabled');
            });
            form.submit();
        }
    });

    //Inserção / Atualização Recebimento em PIX
    $('#form-rec-pix').validate({
        rules: {
            codBcoPIX: {
                required: true
            },
            numDocPIX: {
                required: true,
                maxlength: 20
            },
            valPIX: {
                required: true,
                maxlength: 20
            },
            observacao: {
                maxlength: 255
            },
        },
        messages: {
            codBcoPIX: {
                required:  "Por Favor informe o Banco do PIX"
            },
            numDocPIX: {
                required:  "Por Favor informe o Número do Comprovante do PIX",
                maxlength: "Limite máximo do Número do Comprovante é 20 caracteres"
            },
            valPIX: {
                required:  "Por Favor informe o Valor do PIX",
                maxlength: "Limite máximo do valor é de 15 digitos"
            },
            observacao: {
                maxlength: "Limite máximo da Observação é 255 caracteres"
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
            // Ativar campos desativados antes de enviar
            $(':disabled').each(function () {
                $(this).removeAttr('disabled');
            });
            form.submit();
        }
    });

    //Inserção / Atualização Recebimento em Cheque
    $('#form-rec-cheque').validate({
        rules: {
            codBcoCHQ: {
                required: true
            },
            numAgeCHQ: {
                required: true,
                maxlength: 5
            },
            numAgeDvCHQ: {
                maxlength: 1
            },
            numCcrCHQ: {
                required: true,
                maxlength: 10
            },
            numCcrDvCHQ: {
                maxlength: 1
            },
            numCHQ: {
                required: true,
                maxlength: 20
            },
            resCHQ: {
                required: true,
                maxlength: 30
            },
            valCHQ: {
                required: true,
                maxlength: 20
            },
            observacao: {
                maxlength: 255
            },
            dataVctCHQ: {
                required: true
            },
        },
        messages: {
            codBcoCHQ: {
                required:  "Por Favor informe o Banco do Cheque"
            },
            numAgeCHQ: {
                required:  "Por Favor informe a Agência do Cheque",
                maxlength: "Limite máximo da Agência é 5 caracteres"
            },
            numAgeDvCHQ: {
                maxlength: "Limite máximo do Dígito da Agência é 1 caracteres"
            },
            numCcrCHQ: {
                required:  "Por Favor informe a Conta Corrente do Cheque",
                maxlength: "Limite máximo da Conta Corrente é 10 caracteres"
            },
            numCcrDvCHQ: {
                maxlength: "Limite máximo do Dígito da Conta Corrente é 1 caracteres"
            },
            numCHQ: {
                required:  "Por Favor informe o Número do Cheque",
                maxlength: "Limite máximo do Número é 20 caracteres"
            },
            resCHQ: {
                required:  "Por Favor informe o Responsável do Cheque",
                maxlength: "Limite máximo do Responsável é 30 caracteres"
            },
            valCHQ: {
                required:  "Por Favor informe o Valor do Cheque",
                maxlength: "Limite máximo do valor é de 15 digitos"
            },
            observacao: {
                maxlength: "Limite máximo da Observação é 255 caracteres"
            },
            dataVctCHQ: {
                required:  "Por Favor informe a Data de Vencimento do Cheque"
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
            // Ativar campos desativados antes de enviar
            $(':disabled').each(function () {
                $(this).removeAttr('disabled');
            });
            form.submit();
        }
    });

    //Inserção / Atualização Recebimento com Cartão de Crédito
    $('#form-rec-ccr').validate({
        rules: {
            admCCR: {
                required: true
            },
            bandeiraCCR: {
                required: true
            },
            numDocCCR: {
                required: true,
                maxlength: 20
            },
            parcelaCCR: {
                required: true
            },
            valCCR: {
                required: true,
                maxlength: 20
            },
            compCCR: {
                maxlength: 40
            },
            observacao: {
                maxlength: 255
            },
        },
        messages: {
            admCCR: {
                required:  "Por Favor informe a Administradora do Cartão"
            },
            bandeiraCCR: {
                required:  "Por Favor informe a Bandeira do Cartão"
            },
            numDocCCR: {
                required:  "Por Favor informe o Número do Comprovante do Cartão",
                maxlength: "Limite máximo do Número do Comprovante é 20 caracteres"
            },
            parcelaCCR: {
                required:  "Por Favor informe o Parcelamento do Cartão"
            },
            valCCR: {
                required:  "Por Favor informe o Valor do Cartão",
                maxlength: "Limite máximo do valor é de 15 digitos"
            },
            compCCR: {
                maxlength: "Limite máximo do Complemento é 40 caracteres"
            },
            observacao: {
                maxlength: "Limite máximo da Observação é 255 caracteres"
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
            // Ativar campos desativados antes de enviar
            $(':disabled').each(function () {
                $(this).removeAttr('disabled');
            });
            form.submit();
        }
    });

    //Inserção / Atualização Recebimento com Cartão de Débito
    $('#form-rec-cdb').validate({
        rules: {
            admCDB: {
                required: true
            },
            bandeiraCDB: {
                required: true
            },
            numDocCDB: {
                required: true,
                maxlength: 20
            },
            valCDB: {
                required: true,
                maxlength: 20
            },
            compCDB: {
                maxlength: 40
            },
            observacao: {
                maxlength: 255
            },
        },
        messages: {
            admCDB: {
                required:  "Por Favor informe a Administradora do Cartão"
            },
            bandeiraCDB: {
                required:  "Por Favor informe a Bandeira do Cartão"
            },
            numDocCDB: {
                required:  "Por Favor informe o Número do Comprovante do Cartão",
                maxlength: "Limite máximo do Número do Comprovante é 20 caracteres"
            },
            valCDB: {
                required:  "Por Favor informe o Valor do Cartão",
                maxlength: "Limite máximo do valor é de 15 digitos"
            },
            compCDB: {
                maxlength: "Limite máximo do Complemento é 40 caracteres"
            },
            observacao: {
                maxlength: "Limite máximo da Observação é 255 caracteres"
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
            // Ativar campos desativados antes de enviar
            $(':disabled').each(function () {
                $(this).removeAttr('disabled');
            });
            form.submit();
        }
    });

    //Filtro conta corrente
    $('#form-rec-filtro-cct').validate({
        rules: {
            vlrIniFiltro: {
                maxlength: 20
            },
            vlrFinFiltro: {
                maxlength: 20
            },
        },
        messages: {
            vlrIniFiltro: {
                maxlength: "Limite máximo do valor é de 15 digitos"
            },
            vlrFinFiltro: {
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
            // Ativar campos desativados antes de enviar
            $(':disabled').each(function () {
                $(this).removeAttr('disabled');
            });
            form.submit();
        }
    });

    //Inclusão de conta corrente
    $('#form-rec-cct').validate({
        rules: {
            valCCT: {
                required: true,
                maxlength: 20
            },
            observacao: {
                maxlength: 255
            },
        },
        messages: {
            valCCT: {
                required:  "Por Favor informe o Valor da Conta Corrente",
                maxlength: "Limite máximo do valor é de 15 digitos"
            },
            observacao: {
                maxlength: "Limite máximo da Observação é 255 caracteres"
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
            // Ativar campos desativados antes de enviar
            $(':disabled').each(function () {
                $(this).removeAttr('disabled');
            });
            form.submit();
        }
    });
});