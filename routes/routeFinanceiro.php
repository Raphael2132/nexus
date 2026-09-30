<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Área do Financeiro do Sistema
|--------------------------------------------------------------------------
|
| Área destina as rotas envolvidas no cadastramento, manutenção e outras atividades relacionada ao financeiro.
|
*/

// -------------------- Razão -------------------- //

/* ********** Rotas do Cadastro de Razões - Rotas REST ********** */
Route::resource('/cadastros/financeiro/cadastroRazao', App\Http\Controllers\Financeiro\FinanceiroRazoesController::class);

// -------------------- Recebimento -------------------- //

/* ********** Rotas da Inserção de Valores do Recebimento - Rotas REST ********** */
Route::resource('/financeiro/recebimento/valorRecebimento', App\Http\Controllers\Financeiro\Recebimento\FinanceiroRecebimentoValorController::class);

// -------------------- Adiantamento de Clientes -------------------- //

/* ********** Rotas do Recebimento de Contas Correntes - CCT ********** */
Route::get('/financeiro/recebimento/fin_cnt001_HomeCCT', [App\Http\Controllers\Financeiro\Recebimento\PainelRecebimentoCCTController::class, 'filtroRecCCT'])->name('home.filtroRecCCT');

Route::post('/financeiro/recebimento/inicia/fin_pnl001_PainelCCT', [App\Http\Controllers\Financeiro\Recebimento\PainelRecebimentoCCTController::class, 'iniciaRecCCT'])->name('recebimentoCCT.iniciaRecCCT');

Route::get('/financeiro/recebimento/principal/fin_pnl001_PainelCCT/{empresa}/{cliente}/{idRecebimento}', [App\Http\Controllers\Financeiro\Recebimento\PainelRecebimentoCCTController::class, 'painelPrincipalCCT'])->name('recebimentoCCT.painelPrincipalCCT');
Route::get('/financeiro/recebimento/contasAbertas/fin_pnl001_PainelCCT/{empresa}/{cliente}/{idRecebimento}', [App\Http\Controllers\Financeiro\Recebimento\PainelRecebimentoCCTController::class, 'consultaCCTAberta'])->name('recebimentoCCT.consultaCCTAberta');
Route::get('/financeiro/recebimento/novaConta/fin_pnl001_PainelCCT/{empresa}/{cliente}/{idRecebimento}', [App\Http\Controllers\Financeiro\Recebimento\PainelRecebimentoCCTController::class, 'novaCCT'])->name('recebimentoCCT.novaCCT');
Route::get('/financeiro/recebimento/contaSelecionada/fin_pnl001_PainelCCT/{empresa}/{cliente}/{idRecebimento}/{tipoCCR}/{numCCR}', [App\Http\Controllers\Financeiro\Recebimento\PainelRecebimentoCCTController::class, 'contaSelecionada'])->name('recebimentoCCT.contaSelecionada');
Route::get('/financeiro/recebimento/excluirConta/fin_pnl001_PainelCCT/{empresa}/{cliente}/{idRecebimento}/{tipoCCR}/{numCCR}', [App\Http\Controllers\Financeiro\Recebimento\PainelRecebimentoCCTController::class, 'excluirCCT'])->name('recebimentoCCT.excluirCCT');
Route::get('/financeiro/recebimento/reabreSelecao/fin_pnl001_PainelCCT/{empresa}/{cliente}/{idRecebimento}', [App\Http\Controllers\Financeiro\Recebimento\PainelRecebimentoCCTController::class, 'reabreSelecao'])->name('recebimentoCCT.reabreSelecao');

Route::post('/financeiro/recebimento/inserirConta/existente/fin_pnl001_PainelCCT/{empresa}/{cliente}/{idRecebimento}', [App\Http\Controllers\Financeiro\Recebimento\PainelRecebimentoCCTController::class, 'inserirCCT'])->name('recebimentoCCT.inserirCCT');
Route::post('/financeiro/recebimento/inserirConta/nova/fin_pnl001_PainelCCT/{empresa}/{cliente}/{idRecebimento}', [App\Http\Controllers\Financeiro\Recebimento\PainelRecebimentoCCTController::class, 'inserirNovaCCT'])->name('recebimentoCCT.inserirNovaCCT');
Route::post('/financeiro/recebimento/contaCorrente/finalizar/fin_pnl001_PainelCCT/{empresa}/{cliente}', [App\Http\Controllers\Financeiro\Recebimento\PainelRecebimentoCCTController::class, 'finalizarRecCCT'])->name('recebimentoCCT.finalizarRecCCT');

// -------------------- Adiantamento de Clientes -------------------- //

/* ********** Rotas do Recebimento de Contas a Receber de Clientes - DUP ********** */
Route::get('/financeiro/recebimento/duplicata/fin_cnt002_HomeDUP', [App\Http\Controllers\Financeiro\Recebimento\PainelRecebimentoDUPController::class, 'filtroRecDUP'])->name('home.filtroRecDUP');

Route::get('/financeiro/recebimento/duplicata/abertas/fin_cns002_DupAberta', [App\Http\Controllers\Financeiro\Recebimento\PainelRecebimentoDUPController::class, 'dupAbertas'])->name('recebimentoDUP.dupAbertas');
Route::get('/financeiro/recebimento/duplicata/inicia/selecionada/fin_pnl002_PainelDUP/{empresa}/{cliente}/{numDUP}/{seqDUP}', [App\Http\Controllers\Financeiro\Recebimento\PainelRecebimentoDUPController::class, 'iniciaDUPSel'])->name('recebimentoDUP.iniciaDUPSel');
Route::get('/financeiro/recebimento/duplicata/painel/fin_pnl002_PainelDUP/{empresa}/{cliente}/{idRecebimento}', [App\Http\Controllers\Financeiro\Recebimento\PainelRecebimentoDUPController::class, 'painelPrincipalDUP'])->name('recebimentoDUP.painelPrincipalDUP');
Route::get('/financeiro/recebimento/duplicata/painel/selecionarDuplicata/fin_pnl002_PainelDUP/{empresa}/{cliente}/{idRecebimento}', [App\Http\Controllers\Financeiro\Recebimento\PainelRecebimentoDUPController::class, 'selecionarDUP'])->name('recebimentoDUP.selecionarDUP');
Route::get('/financeiro/recebimento/duplicata/modal/inclusaoManutencao/{empresa}/{cliente}/{numDUP}/{seqDUP}/{origem}', [App\Http\Controllers\Financeiro\Recebimento\PainelRecebimentoDUPController::class, 'carregarDadosModalIncManDuplicata'])->name('recebimentoDUP.carregarDadosModalIncManDuplicata');
Route::get('/financeiro/recebimento/duplicata/reabrirSelecao/{empresa}/{cliente}/{idRecebimento}', [App\Http\Controllers\Financeiro\Recebimento\PainelRecebimentoDUPController::class, 'reabreSelecao'])->name('recebimentoDUP.reabreSelecao');

Route::post('/financeiro/recebimento/duplicata/inicia/fin_pnl002_PainelDUP', [App\Http\Controllers\Financeiro\Recebimento\PainelRecebimentoDUPController::class, 'iniciaRecDUP'])->name('recebimentoDUP.iniciaRecDUP');
Route::post('/financeiro/recebimento/duplicata/incluir/fin_pnl002_PainelDUP/{empresa}/{cliente}/{idRecebimento}', [App\Http\Controllers\Financeiro\Recebimento\FinanceiroRecebimentoDuplicatasController::class, 'insertModal'])->name('recebimentoDUP.insertModal');
Route::post('/financeiro/recebimento/duplicata/atualizar/fin_pnl002_PainelDUP/{empresa}/{cliente}/{idRecebimento}', [App\Http\Controllers\Financeiro\Recebimento\FinanceiroRecebimentoDuplicatasController::class, 'updateModal'])->name('recebimentoDUP.updateModal');
Route::post('/financeiro/recebimento/duplicata/finalizar/fin_pnl002_PainelDUP/{empresa}/{cliente}', [App\Http\Controllers\Financeiro\Recebimento\PainelRecebimentoDUPController::class, 'finalizarRecDUP'])->name('recebimentoDUP.finalizarRecDUP');

Route::delete('/financeiro/recebimento/duplicata/excluir/fin_pnl002_PainelDUP/{empresa}/{cliente}/{numDUP}/{seqDUP}', [App\Http\Controllers\Financeiro\Recebimento\FinanceiroRecebimentoDuplicatasController::class, 'delete'])->name('recebimentoDUP.delete');

// -------------------- Outros Recebimentos -------------------- //

/* ********** Rotas de Outras Recebimentos - OUT ********** */
Route::get('/financeiro/recebimento/outros/fin_cnt003_HomeOUT', [App\Http\Controllers\Financeiro\Recebimento\PainelRecebimentoOUTController::class, 'filtroRecOUT'])->name('home.filtroRecOUT');

Route::get('/financeiro/recebimento/outros/abreFormulario/fin_cnt003_HomeOUT/{empresa}/{subEstagio}', [App\Http\Controllers\Financeiro\Recebimento\PainelRecebimentoOUTController::class, 'abreFormulario'])->name('recebimentoOUT.abreFormulario');
Route::get('/financeiro/recebimento/outros/abreConfirmacao/fin_cnt003_HomeOUT/{empresa}', [App\Http\Controllers\Financeiro\Recebimento\PainelRecebimentoOUTController::class, 'abreConfirmacao'])->name('recebimentoOUT.abreConfirmacao');

Route::post('/financeiro/recebimento/outros/inicia/fin_pnl003_PainelOUT', [App\Http\Controllers\Financeiro\Recebimento\PainelRecebimentoOUTController::class, 'iniciaRecOUT'])->name('recebimentoOUT.iniciaRecOUT');
Route::post('/financeiro/recebimento/outros/atualiza/fin_pnl003_PainelOUT/{empresa}/{subEstagio}', [App\Http\Controllers\Financeiro\Recebimento\PainelRecebimentoOUTController::class, 'atualizaRec'])->name('recebimentoOUT.atualizaRec');
Route::post('/financeiro/recebimento/outros/finalizar/fin_pnl003_PainelOUT/{empresa}/{cliente}', [App\Http\Controllers\Financeiro\Recebimento\PainelRecebimentoOUTController::class, 'finalizarRecOUT'])->name('recebimentoOUT.finalizarRecOUT');

/* ********** Rotas do Painel de Recebimento de Valores ********** */
Route::get('/financeiro/recebimento/painelRecebimento/principal/fin_pnl001_Recebimento/{empresa}/{cliente}/{idRecebimento}', [App\Http\Controllers\Financeiro\Recebimento\PainelRecebimentoValoresController::class, 'painelRecebimento'])->name('recebimento.painelRecebimento');

// -------------------- Pagamento -------------------- //

// -------------------- Conta Corrente -------------------- //

/* ********** Rotas do Pagamento de Contas Correntes - PCC (Individual em Dinheiro) ********** */
Route::get('/financeiro/pagamento/fin_cnt005_HomePCC_InDin', [App\Http\Controllers\Financeiro\Pagamento\PainelPagamentoPCCIndDinController::class, 'homePCCIndDin'])->name('home.homePCCIndDin');

Route::get('/financeiro/pagamento/fin_frm005_FormularioPagDin/formulario/{numCC}', [App\Http\Controllers\Financeiro\Pagamento\PainelPagamentoPCCIndDinController::class, 'abreFormularioPagInDin'])->name('pagamentoPCC.abreFormularioPagInDin');
Route::get('/financeiro/pagamento/fin_frm005_FormularioPagDin/cancelar/{empresa}/{idPagamento}', [App\Http\Controllers\Financeiro\Pagamento\PainelPagamentoPCCIndDinController::class, 'excluiPCCDin'])->name('pagamentoPCC.excluiPCCDin');
Route::get('/financeiro/pagamento/fin_cns005_ContasPagamento/consulta', [App\Http\Controllers\Financeiro\Pagamento\PainelPagamentoPCCIndDinController::class, 'consultaContas'])->name('pagamentoPCC.consultaContas');

Route::post('/financeiro/pagamento/fin_cns005_ContasPagamento/selecao', [App\Http\Controllers\Financeiro\Pagamento\PainelPagamentoPCCIndDinController::class, 'iniciaPCCDin'])->name('pagamentoPCC.iniciaPCCDin');
Route::post('/financeiro/pagamento/fin_frm005_FormularioPagDin/finalizarPCC', [App\Http\Controllers\Financeiro\Pagamento\PainelPagamentoPCCIndDinController::class, 'finalizarPCC'])->name('pagamentoPCC.finalizarPCC');

/* ********** Rotas da Inserção de Valores do Pagamento - Rotas REST ********** */
//Route::resource('/financeiro/pagamento/valorPagamento', App\Http\Controllers\Financeiro\Pagamento\FinanceiroPagamentoValorController::class);
Route::resource(
    '/financeiro/pagamento/fin_pnl006_PainelLote/{appOrigem}/valorPagamento',
    App\Http\Controllers\Financeiro\Pagamento\FinanceiroPagamentoValorController::class
)->names('valorPagamentoLote');

/* ********** Rotas do Pagamento de Lotes - PCC (Lote) ********** */
Route::get('/financeiro/pagamento/fin_cnt006_HomePCC_Lote/{appOrigem}', [App\Http\Controllers\Financeiro\Pagamento\PainelPagamentoPCCLoteController::class, 'homePCCLote'])->name('home.homePCCLote');

Route::prefix('/financeiro/pagamento/fin_cns006_ContasPagamento/{appOrigem}')->group(function () {

    Route::get('/insert/{numCC}/{tipoCC}/{saldoCC}', 
        [App\Http\Controllers\Financeiro\Pagamento\PainelPagamentoPCCLoteController::class, 'insertCCLote']
    )->name('pagamentoPCC.insertCCLote');

    Route::get('/delete/{numCC}/{tipoCC}', 
        [App\Http\Controllers\Financeiro\Pagamento\PainelPagamentoPCCLoteController::class, 'deleteCCLote']
    )->name('pagamentoPCC.deleteCCLote');

    Route::get('/Lote/Selecao', 
        [App\Http\Controllers\Financeiro\Pagamento\PainelPagamentoPCCLoteController::class, 'selecaoCCLote']
    )->name('pagamentoPCC.selecaoCCLote');

    Route::post('/selecao', 
        [App\Http\Controllers\Financeiro\Pagamento\PainelPagamentoPCCLoteController::class, 'iniciaPCCLote']
    )->name('pagamentoPCC.iniciaPCCLote');
});

Route::prefix('/financeiro/pagamento/fin_pnl006_PainelLote/{appOrigem}')->group(function () {

    Route::get('/', 
        [App\Http\Controllers\Financeiro\Pagamento\PainelPagamentoPCCLoteController::class, 'fecharLote']
    )->name('pagamentoPCC.fecharLote');
    
    Route::get('/Editar/{numCC},{tipoCC}', 
        [App\Http\Controllers\Financeiro\Pagamento\PainelPagamentoPCCLoteController::class, 'loteEditarCC']
    )->name('pagamentoPCC.loteEditarCC');

    Route::get('/Reabrir', 
        [App\Http\Controllers\Financeiro\Pagamento\PainelPagamentoPCCLoteController::class, 'reabreLote']
    )->name('pagamentoPCC.reabreLote');

    Route::get('/Pagamento', 
        [App\Http\Controllers\Financeiro\Pagamento\PainelPagamentoPCCLoteController::class, 'pagarLote']
    )->name('pagamentoPCC.pagarLote');

    Route::get('/Deletar/{numCC},{tipoCC}', 
        [App\Http\Controllers\Financeiro\Pagamento\PainelPagamentoPCCLoteController::class, 'loteDeletarCC']
    )->name('pagamentoPCC.loteDeletarCC');

    Route::post('/Salvar', 
        [App\Http\Controllers\Financeiro\Pagamento\PainelPagamentoPCCLoteController::class, 'loteSalvarEdicaoCC']
    )->name('pagamentoPCC.loteSalvarEdicaoCC');
    
    Route::post('/Finalizar', 
        [App\Http\Controllers\Financeiro\Pagamento\PainelPagamentoPCCLoteController::class, 'finalizaPagamentoLote']
    )->name('pagamentoPCC.finalizaPagamentoLote');

});

/* ********** Rotas da Consulta de Lotes Gerados ********** */
Route::get('/financeiro/pagamento/fin_cnt008_HomeLoteConsulta', [App\Http\Controllers\Financeiro\Pagamento\PainelPagamentoPCCLoteController::class, 'homePCCLoteConsulta'])->name('home.homePCCLoteConsulta');

Route::post('/financeiro/pagamento/fin_cns008_ConsultaLote/consulta', [App\Http\Controllers\Financeiro\Pagamento\PainelPagamentoPCCLoteController::class, 'consultaLote'])->name('pagamentoPCC.consultaLote');

// -------------------- Inclusão de Adiantamento de Fornecedor -------------------- //

/* ********** Rotas da Inclusão de Adiantamento de Fornecedor ********** */
Route::get('/financeiro/pagamento/fin_frm007_FormularioInclusaoAF', [App\Http\Controllers\Financeiro\Pagamento\PainelPagamentoPCCAdiantFornecedorController::class, 'homePCCIncAF'])->name('home.homePCCIncAF');

Route::post('/financeiro/pagamento/fin_frm007_FormularioInclusaoAF/incluir', [App\Http\Controllers\Financeiro\Pagamento\PainelPagamentoPCCAdiantFornecedorController::class, 'iniciaPCCIncAF'])->name('pagamentoPCC.iniciaPCCIncAF');

// -------------------- Pagamento de Adiantamento de Fornecedor -------------------- //

/* ********** Rotas do Pagamento de Adiantamento de Fornecedor ********** */
Route::get('/financeiro/pagamento/fin_cnt008_HomePCC_AF', [App\Http\Controllers\Financeiro\Pagamento\PainelPagamentoPCCAdiantFornecedorController::class, 'homePCCPgamentoAF'])->name('home.homePCCPgamentoAF');

// -------------------- Pequenas Despesas (Obrigações / Outros Débitos) -------------------- //

/* ********** Rotas do Pagamento de Pequenas Despesas - PPD ********** */
Route::get('/financeiro/pagamento/fin_cnt004_HomePPD', [App\Http\Controllers\Financeiro\Pagamento\PainelPagamentoPPDController::class, 'homePPD'])->name('home.homePPD');

Route::get('/financeiro/pagamento/fin_frm004_FormularioPPD/exclui/{empresa}/{idPagamento}', [App\Http\Controllers\Financeiro\Pagamento\PainelPagamentoPPDController::class, 'excluiPPD'])->name('pagamentoPPD.excluiPPD');
Route::get('/financeiro/pagamento/fin_frm004_FormularioPPD/formulario', [App\Http\Controllers\Financeiro\Pagamento\PainelPagamentoPPDController::class, 'formularioPPD'])->name('pagamentoPPD.formularioPPD');

Route::post('/financeiro/pagamento/fin_frm004_FormularioPPD/inicia', [App\Http\Controllers\Financeiro\Pagamento\PainelPagamentoPPDController::class, 'iniciaPPD'])->name('pagamentoPPD.iniciaPPD');
Route::post('/financeiro/pagamento/fin_frm004_FormularioPPD/finalizarPPD', [App\Http\Controllers\Financeiro\Pagamento\PainelPagamentoPPDController::class, 'finalizarPPD'])->name('pagamentoPPD.finalizarPPD');

// -------------------- Transferência -------------------- //

/* ********** Rotas do Reforço de Caixa ********** */
Route::get('/financeiro/transferencia/fin_cnt009_HomeReforcoCaixa', [App\Http\Controllers\Financeiro\Transferencia\PainelTransferenciaController::class, 'homeReforcoCaixa'])->name('home.homeReforcoCaixa');

Route::post('/financeiro/transferencia/fin_pnl009_PainelReforcoCaixa', [App\Http\Controllers\Financeiro\Transferencia\PainelTransferenciaController::class, 'painelReforcoCaixa'])->name('transferencia.painelReforcoCaixa');

/* ********** Rotas da Transferência de Caixa ********** */
Route::get('/financeiro/transferencia/fin_cnt010_HomeTransferenciaCaixa', [App\Http\Controllers\Financeiro\Transferencia\PainelTransferenciaController::class, 'homeTransfCaixa'])->name('home.homeTransfCaixa');

Route::prefix('/financeiro/transferencia/fin_pnl010_PainelTransferenciaCaixa')->group(function () {

    Route::get('/Painel', 
        [App\Http\Controllers\Financeiro\Transferencia\PainelTransferenciaController::class, 'painelTransfCaixa']
    )->name('transferencia.painelTransfCaixa');

    Route::get('/Consulta/{tipo}', 
        [App\Http\Controllers\Financeiro\Transferencia\PainelTransferenciaController::class, 'consultaItensTransf']
    )->name('transferencia.consultaItensTransf');

    Route::post('/iniciar/{opcao}', 
        [App\Http\Controllers\Financeiro\Transferencia\PainelTransferenciaController::class, 'iniciaTransfCaixa']
    )->name('transferencia.iniciaTransfCaixa');
    
    Route::post('/dinheiro/update', 
        [App\Http\Controllers\Financeiro\Transferencia\PainelTransferenciaController::class, 'transfUpDinheiro']
    )->name('transferencia.transfUpDinheiro');

    Route::post('/conta/update/{origem}', 
        [App\Http\Controllers\Financeiro\Transferencia\PainelTransferenciaController::class, 'transfUpConta']
    )->name('transferencia.transfUpConta');

    Route::post('/finalizar', 
        [App\Http\Controllers\Financeiro\Transferencia\PainelTransferenciaController::class, 'finalizarTransf']
    )->name('transferencia.finalizarTransf');

});

/* ********** Rotas da Consulta de Transferências ********** */
Route::get('/financeiro/transferencia/fin_cnt011_HomeConsultaTransferencias', [App\Http\Controllers\Financeiro\Transferencia\PainelTransferenciaController::class, 'homeConsultaTransf'])->name('home.homeConsultaTransf');

Route::get('/financeiro/transferencia/fin_cns011_ResumoTransferencia/{idTransf}', [App\Http\Controllers\Financeiro\Transferencia\PainelTransferenciaController::class, 'resumoTransfRealizada'])->name('transferencia.resumoTransfRealizada');

Route::post('/financeiro/transferencia/fin_cns011_RelatorioTransferencias', [App\Http\Controllers\Financeiro\Transferencia\PainelTransferenciaController::class, 'consultaTransfRealizadas'])->name('transferencia.consultaTransfRealizadas');