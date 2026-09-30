<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Área de Faturamento
|--------------------------------------------------------------------------
|
| Área destina as rotas envolvidas no lançamento de informações de faturamento.
|
*/

// -------------------- Emissão Simplificada de NF -------------------- //

/* ********** Rotas de Emissao simplificada de NFS-e ********** */
Route::get('/faturamento/notas/simplificada/homeEmissaoSimplificadaNFS', [App\Http\Controllers\HomeController::class, 'homeEmissaoSimpNFS'])->name('home.emissaoSimpNFS');

Route::get('/faturamento/notas/simplificada/formularioEmissaoSimplificadaNFS/{empresa}/{cliente}', [App\Http\Controllers\EmissaoSimplificadaNFSController::class, 'inicio'])->name('emissaoSimpNFS.inicioErro');
Route::get('/faturamento/notas/simplificada/formularioEmissaoSimplificadaNFSEtapa2/{empresa}/{cliente}/{enderecoCli}', [App\Http\Controllers\EmissaoSimplificadaNFSController::class, 'etapa2'])->name('emissaoSimpNFS.etapa2Erro');
Route::get('/faturamento/notas/simplificada/formularioEmissaoSimplificadaNFS/ajax/{codigo}', [App\Http\Controllers\EmissaoSimplificadaNFSController::class, 'carregaCodSrvAjax'])->name('emissaoSimpNFS.carregaCodSrvAjax');
Route::get('/faturamento/notas/simplificada/formularioEmissaoSimplificadaNFS/{empresa}/{cliente}', [App\Http\Controllers\EmissaoSimplificadaNFSController::class, 'inicioGet'])->name('emissaoSimpNFS.inicioGet');

Route::post('/faturamento/notas/simplificada/formularioEmissaoSimplificadaNFS', [App\Http\Controllers\EmissaoSimplificadaNFSController::class, 'inicio'])->name('emissaoSimpNFS.inicio');
Route::post('/faturamento/notas/simplificada/formularioEmissaoSimplificadaNFSEtapa2/etapa2/{empresa}/{cliente}', [App\Http\Controllers\EmissaoSimplificadaNFSController::class, 'etapa2'])->name('emissaoSimpNFS.etapa2');
Route::post('/faturamento/notas/simplificada/formularioEmissaoSimplificadaNFSE/emissao/{empresa}/{cliente}/{enderecoCli}/{enderecoLocSrv}', [App\Http\Controllers\EmissaoSimplificadaNFSController::class, 'emitirNFS'])->name('emissaoSimpNFS.emitirNFS');

/* ********** Rotas de Reemissao simplificada de NFS-e ********** */
Route::get('/faturamento/notas/simplificada/controleReemissaoSimpNF', [App\Http\Controllers\HomeController::class, 'reemissaoSimpNF'])->name('home.reemissaoSimpNF');

Route::get('/faturamento/notas/simplificada/consultaReemissaoSimpNF/{appOrigem}', [App\Http\Controllers\FaturamentoNfsSimplificadaController::class, 'consultaReemissaoSimpNF'])->name('reemissaoSimpNF.consultaReemissaoSimpNF');
Route::get('/faturamento/notas/simplificada/consultaReemissaoSimpNF/redir', [App\Http\Controllers\FaturamentoNfsSimplificadaController::class, 'redirConsultaReemissaoSimpNF'])->name('reemissaoSimpNF.redirConsultaReemissaoSimpNF');

/* ********** Rotas de Impressão de NFS-e ********** */
Route::get('/faturamento/notas/impressao/nfse/{empresa}/{numControle}/{appOrigem}', [App\Http\Controllers\FaturamentoNotasImpressaoController::class, 'nfseGerarPDF'])->name('impresaoNF.nfsePDF');
Route::get('/faturamento/notas/email/nfse/{empresa}/{numControle}', [App\Http\Controllers\FaturamentoNotasImpressaoController::class, 'enviaNfsEmail'])->name('impresaoNF.nfseEmail');

/* ********** Rotas de Impressão de RPS ********** */
Route::get('/faturamento/notas/impressao/validaRPS/{empresa}/{numOS}/{appOrigem}', [App\Http\Controllers\FaturamentoNotasImpressaoController::class, 'validaRPS'])->name('impresaoRPS.validaRPS');
Route::get('/faturamento/notas/impressao/rps/{empresa}/{numControle}/{appOrigem}', [App\Http\Controllers\FaturamentoNotasImpressaoController::class, 'rpsGerarPDF'])->name('impresaoRPS.rpsPDF');
Route::get('/faturamento/notas/email/rps/{empresa}/{numControle}', [App\Http\Controllers\FaturamentoNotasImpressaoController::class, 'enviaRpsEmail'])->name('impresaoRPS.rpsEmail');


// -------------------- Emissão de NF -------------------- //

/* ********** Rotas de Emissao de NF ********** */
Route::get('/faturamento/notas/fat_cnt001_EmissaoNF', [App\Http\Controllers\Faturamento\Nota\Emissao\PainelEmissaoNfController::class, 'emissaoNF'])->name('home.emissaoNF');

Route::get('/faturamento/notas/fat_cns001_EmissaoNF/filtro', [App\Http\Controllers\Faturamento\Nota\Emissao\PainelEmissaoNfController::class, 'filtroConsultaNF'])->name('emissaoNF.filtroConsultaNF');
Route::get('/faturamento/notas/fat_cns001_EmissaoNF', [App\Http\Controllers\Faturamento\Nota\Emissao\PainelEmissaoNfController::class, 'consultaNF'])->name('emissaoNF.consultaNF');
Route::get('/faturamento/notas/fat_pnl001_EmissaoNF/{empresa}/{cliente}', [App\Http\Controllers\Faturamento\Nota\Emissao\PainelEmissaoNfController::class, 'painelNF'])->name('emissaoNF.painelNF');
Route::get('/faturamento/notas/fat_pnl001_EmissaoNF/selecaoNF/{empresa}/{cliente}', [App\Http\Controllers\Faturamento\Nota\Emissao\PainelEmissaoNfController::class, 'painelSelecaoNF'])->name('emissaoNF.painelSelecaoNF');
Route::get('/faturamento/notas/fat_pnl001_EmissaoNF/selecaoNF/reabertura/{empresa}/{cliente}', [App\Http\Controllers\Faturamento\Nota\Emissao\PainelEmissaoNfController::class, 'painelReabreSelNF'])->name('emissaoNF.painelReabreSelNF');
Route::get('/faturamento/notas/fat_pnl001_EmissaoNF/opcao/{empresa}/{cliente}/{origemOpc}', [App\Http\Controllers\Faturamento\Nota\Emissao\PainelEmissaoNfController::class, 'opcaoNF'])->name('emissaoNF.opcaoNF');

Route::post('/faturamento/notas/fat_pnl001_EmissaoNF/finalizar/{empresa}/{cliente}', [App\Http\Controllers\Faturamento\Nota\Emissao\PainelEmissaoNfController::class, 'finalizaRecebimento'])->name('emissaoNF.finalizaRecebimento');

/* ***** Rotas de Inserção/Exclusão de NF para Recebimento ***** */
Route::get('/faturamento/notas/fat_pnl001_EmissaoNF/notas/insert/{empresa}/{numNF}/{cliente}', [App\Http\Controllers\Financeiro\Recebimento\FinanceiroRecebimentoNotaController::class, 'insert'])->name('emissaoNF.inserirNotas');
Route::get('/faturamento/notas/fat_pnl001_EmissaoNF/notas/delete/{empresa}/{numNF}/{cliente}', [App\Http\Controllers\Financeiro\Recebimento\FinanceiroRecebimentoNotaController::class, 'delete'])->name('emissaoNF.desmarcarNotas');

//antiga, depois que alterar a reemissão excluir essa rota
Route::get('/faturamento/notas/fat_cnt001_GeracaoNF/{origem}/{empresa}/{nfReemissao}', [App\Http\Controllers\FaturamentoGeracaoNfController::class, 'gerarNF'])->name('emissaoNF.gerarNF');

Route::get('/faturamento/notas/fat_cnt001_GeracaoNF/{origem}/{empresa}', [App\Http\Controllers\FaturamentoGeracaoNfController::class, 'emissaoNF'])->name('emissaoNF.emissaoNF');

// -------------------- Reemissao de NF -------------------- //

/* ********** Rotas de Reemissao de NF ********** */
Route::get('/faturamento/notas/controleReemissaoNF', [App\Http\Controllers\HomeController::class, 'reemissaoNF'])->name('home.reemissaoNF');

Route::get('/faturamento/notas/fat_cns001_ReemissaoNF/{appOrigem}', [App\Http\Controllers\Faturamento\Nota\Emissao\PainelEmissaoNfController::class, 'consultaReemissaoNF'])->name('reemissaoNF.consultaReemissaoNF');
Route::get('/faturamento/notas/fat_cns001_ReemissaoNF/redir', [App\Http\Controllers\Faturamento\Nota\Emissao\PainelEmissaoNfController::class, 'redirConsultaReemissaoNF'])->name('reemissaoNF.redirConsultaReemissaoNF');