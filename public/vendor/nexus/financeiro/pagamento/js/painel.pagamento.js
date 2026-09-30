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

    /* **************************************** Eventos Iniciais do bloco  - PAINEL_PAGAMENTO **************************************** */

    if(estagioAPP == 'PAINEL_PAGAMENTO' ){
        
        const link = document.getElementById("linkCartaoCorporativo");

        if (link) {
            link.addEventListener("click", function (e) {

                if (cntPagCO > 0) {  
                    e.preventDefault(); // bloqueia o redirect

                    Swal.fire({
                        icon: 'info',
                        title: 'Atenção!',
                        html: 'Não é possível pagar contas dos seguintes tipos usando o <b>Cartão Corporativo</b>:<br><br><b>AC, CO e DV</b>',
                        confirmButtonText: 'OK',
                        confirmButtonColor: "#007bff",
                    });
                }

            });
        }
    }

    /* **************************************** Eventos Iniciais do bloco  - PAG_DINHEIRO **************************************** */

    if(estagioAPP == 'PAG_DINHEIRO' ){

        /* ******************** Mascaras de campos float ******************** */

        //Mascaras dos campos de valores
        $('#valDin').mask('#.##0,00', {reverse: true});
    }

    /* **************************************** Eventos Iniciais do bloco  - PAG_PIX **************************************** */

    if(estagioAPP == 'PAG_PIX' ){

        /* ******************** Mascaras de campos float ******************** */

        //Mascaras dos campos de valores
        $('#valPIX').mask('#.##0,00', {reverse: true});
    }

    /* **************************************** Eventos Iniciais do bloco  - PAG_C_CORPORATIVO **************************************** */

    if(estagioAPP == 'PAG_C_CORPORATIVO' ){

        /* ******************** Mascaras de campos float ******************** */

        //Mascaras dos campos de valores
        $('#valCCO').mask('#.##0,00', {reverse: true});

        if(subEstagioPag == 'NEW'){
            $('.bloco-adm-par').hide();
            $('.bloco-dados-cc').hide();
            $('.btn-salvar-pag-cco').hide();
            $('.btn-edit-pag-cco').hide();
        }else{
            $('#valCCO').prop('disabled', true);
            $('.btn-edit-pag-cco').show();
            $('.bloco-adm-par').show();
            $('.bloco-dados-cc').show();
            $('.btn-salvar-pag-cco').show();
            $('.btn-prox-pag-cco').hide();
        }
    }

    /* **************************************** Eventos Iniciais do bloco  - PAG_CHEQUE **************************************** */

    if(estagioAPP == 'PAG_CHEQUE' ){

        /* ******************** Mascaras de campos float ******************** */

        //Mascaras dos campos de valores
        $('#valCHQ').mask('#.##0,00', {reverse: true});

        if(subEstagioPag == 'NEW'){
            $('.bloco-dados-ba').hide();
        }else{
            $('.bloco-dados-ba').show();
        }
    }

    /* **************************************** Eventos Iniciais do bloco  - PAG_FIL_C_CORRENTE **************************************** */

    if(estagioAPP == 'PAG_FIL_C_CORRENTE' ){

        /* ******************** Mascaras de campos float ******************** */

        //Mascaras dos campos de valores
        $('#vlrIniFiltro').mask('#.##0,00', {reverse: true});
        $('#vlrFinFiltro').mask('#.##0,00', {reverse: true});
    }

    /* **************************************** Eventos Iniciais do bloco  - PAG_CONTA_CORRENTE **************************************** */

    if(estagioAPP == 'PAG_CONTA_CORRENTE' ){

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

    /* **************************************** Eventos de click do bloco  - PAG_C_CORPORATIVO **************************************** */

    if(estagioAPP == 'PAG_C_CORPORATIVO' ){

        $('.btn-prox-pag-cco').click(function() {

            // Verifica se o campo #valCCO está preenchido e se não é 0
            var valCCO = $('#valCCO').val().replace(/\./g, '').replace(',', '.'); // Remove o ponto e substitui a vírgula por ponto (caso tenha máscara)

            if (valCCO == '' || parseFloat(valCCO) === 0) {
                Swal.fire({
                    confirmButtonColor: "#007bff",
                    title: "Atenção!!!",
                    text: "Por favor, informe o valor do recebimento!",
                    icon: "info"
                });
                return;
            }

            $('#valCCO').prop('disabled', true);
            
            $('.bloco-adm-par').show();
            $('.bloco-dados-cc').hide();
            $('.btn-salvar-pag-cco').show();
            $('.btn-edit-pag-cco').show();
            $('.btn-prox-pag-cco').hide();
        });

        $('.btn-edit-pag-cco').click(function() {

            $('#valCCO').prop('disabled', false);

            $('#admCCO').val('');
            $('#observacao').val('');
            $('#parcelaCCO').html('<option value="">Selecione...</option>');

            $('.bloco-adm-par').hide();
            $('.bloco-dados-cc').hide();
            $('.btn-salvar-pag-cco').hide();
            $('.btn-edit-pag-cco').hide();
            $('.btn-prox-pag-cco').show();
        });
    }
});

/*
|--------------------------------------------------------------------------
| Eventos onChange da app
|--------------------------------------------------------------------------
*/
$(document).ready(function() {

    /* **************************************** Eventos de change do bloco  - PAG_C_CORPORATIVO **************************************** */

    if(estagioAPP == 'PAG_C_CORPORATIVO' ){

        //Evento de carregamento ajax da Administradora do cartão de crédito
        $('#admCCO').change(function(){

            if( $(this).val() ) {
                
                var emp = empresaLote;
                var adm = $(this).val();
                var val = $('#valCCO').val();

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

                            $('#parcelaCCO').html(options);

                        }else{
                            $('#parcelaCCO').html('<option value="">Selecione...</option>');
                        }
                    }
                });

                var url2 = getDadosUrl.replace(':adm', adm).replace(':emp', emp);

                $.ajax({
                    url: url2,
                    dataType: "JSON",
                    type: 'GET',
                    data: {
                        '_token': $('meta[name=csrf-token]').attr("content"),
                        '_method': 'GET',
                        "adm": adm,
                        "emp": emp
                    },
                    success: function (data)
                    {
                        if(data.cartao_ajax_existe == 'S'){

                            // Pegando o primeiro registro retornado
                            let card = data.cartao_ajax[0];

                            // Preenche os campos HTML
                            $('.labelNumCCO').text(card.num);
                            $('#numCCO').val(card.num);
                            $('.diaVct').text(card.diaVct);
                            $('.diaFec').text(card.diaFec);

                        } else {
                            // Se não existir, limpa os campos
                            $('.labelNumCCO').text('');
                            $('#numCCO').val('');
                            $('.diaVct').text('');
                            $('.diaFec').text('');
                        }
                    }
                });

                $('.bloco-dados-cc').show();

            } else {
                $('#parcelaCCO').html('<option value="">Selecione...</option>');
                $('.bloco-dados-cc').hide();
            }
        });
    }

    /* **************************************** Eventos de change do bloco  - PAG_CHEQUE **************************************** */

    if(estagioAPP == 'PAG_CHEQUE' ){

        //Evento de carregamento ajax dos dados do banco do cheque
        $('#codBcoCHQ').change(function(){

            if( $(this).val() ) {
                
                var emp = empresaLote;
                var bco = $(this).val();

                var url = getDadosBancoUrl.replace(':bco', bco).replace(':emp', emp);

                $.ajax({
                    url: url,
                    dataType: "JSON",
                    type: 'GET',
                    data: {
                        '_token': $('meta[name=csrf-token]').attr("content"),
                        '_method': 'GET',
                        "bco": bco,
                        "emp": emp
                    },
                    success: function (data)
                    {
                        if (data.banco_ajax_existe === 'S') {

                            let banco = data.banco_ajax[0];

                            $('.cnc').text(banco.cnc);
                            $('.age').text(banco.age);
                            $('.conta').text(banco.conta);
                            $('.emitente').text(banco.emi);

                            $('.bloco-dados-cc').fadeIn();

                            $('.bloco-dados-ba').show();

                        } else {

                            $('.cnc, .age, .conta, .emitente').text('');

                            $('.bloco-dados-ba').hide();
                        }
                    }
                });

            } else {
                $('.cnc, .age, .conta, .emitente').text('');
                $('.bloco-dados-ba').hide();
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
    $('#form-pag-din').validate({
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
    $('#form-pag-pix').validate({
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
            complemento: {
                maxlength: 40
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
            complemento: {
                maxlength: "Limite máximo do Complemento é 40 caracteres"
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
    $('#form-pag-cheque').validate({
        rules: {
            codBcoCHQ: {
                required: true
            },
            numCHQ: {
                required: true,
                maxlength: 20
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
    $('#form-pag-cco').validate({
        rules: {
            admCCO: {
                required: true
            },
            parcelaCCO: {
                required: true
            },
            valCCO: {
                required: true,
                maxlength: 20
            },
            observacao: {
                maxlength: 255
            },
        },
        messages: {
            admCCO: {
                required:  "Por Favor informe a Administradora do Cartão"
            },
            parcelaCCO: {
                required:  "Por Favor informe o Parcelamento do Cartão"
            },
            valCCO: {
                required:  "Por Favor informe o Valor do Cartão",
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

    //Filtro conta corrente
    $('#form-pag-filtro-cct').validate({
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
    $('#form-pag-cct').validate({
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