<?php

use Illuminate\Support\Facades\Route;

/*
|------------------------------------------------------------------------------------------
| Área de Lançamentos do Sistema
|------------------------------------------------------------------------------------------
|
| Área destina as rotas envolvidas na no lançamento de serviços do sistema.
|
*/

// -------------------- Emissão de OS -------------------- //

/* ********** Rotas do Header Lançamento de Serviço - Rotas REST ( lancamento_srv_os ) ********** */
Route::resource('/lancamentos/servico/lancamentoOrdemServico', App\Http\Controllers\Lancamentos\Servico\LancamentoSrvOsController::class);

/* ********** Rotas de Emissao de OS ********** */
Route::get('/lancamentos/servico/homeEmissaoOS', [App\Http\Controllers\Lancamentos\Servico\HomeServicoController::class, 'homeEmissaoOS'])->name('home.emissaoOS');















Route::get('/lancamentos/servico/controleAberturaOS/{empresa}/{cliente}', [App\Http\Controllers\LancamentoSrvOsController::class, 'inicio'])->name('emissaoOS.inicioErro');

Route::post('/lancamentos/servico/controleAberturaOS', [App\Http\Controllers\LancamentoSrvOsController::class, 'inicio'])->name('emissaoOS.inicio');
Route::post('/lancamentos/servico/painelAberturaOS/{empresa}/{cliente}', [App\Http\Controllers\LancamentoSrvOsController::class, 'abreOS'])->name('emissaoOS.abreOS');

/* ********** Rotas de Situação de OS ********** */
Route::get('/lancamentos/servico/homeSituacaoOS', [App\Http\Controllers\HomeController::class, 'homeSituacaoOS'])->name('home.situacaoOS');

Route::get('/lancamentos/servico/consultaSituacaoOS/{statusOS}', [App\Http\Controllers\LancamentoSrvOsController::class, 'consultaSituacaoOS'])->name('situacaoOS.consulta');
Route::get('/lancamentos/servico/consultaSituacaoOS/painelAberturaOS/{empresa}/{cliente}/{nos}/{estagioAPP}', [App\Http\Controllers\LancamentoSrvOsController::class, 'carregaOS'])->name('situacaoOS.carregaOS');

/* ********** Rotas do painel de Abertura de OS - Contrele Utilizado: LancamentoSrvOsController ********** */
Route::post('/lancamentos/servico/painelAberturaOS/orcamento/gerar/{empresa}/{numOS}/{stsOS}/{stsOrc}/{cliente}/{dtOS}', [App\Http\Controllers\LancamentoSrvOsController::class, 'abrirOrcamento'])->name('painelOS.abrirOrcamento');
Route::post('/lancamentos/servico/painelAberturaOS/previsaoEntrega/atualizar/{empresa}/{numOS}/{cliente}', [App\Http\Controllers\LancamentoSrvOsController::class, 'atualizaPrevEntrega'])->name('lancamentoOS.atualizaPrevEntrega');
Route::post('/lancamentos/servico/painelAberturaOS/observacao/{empresa}/{numOS}', [App\Http\Controllers\LancamentoSrvOsController::class, 'atualizaObservacao'])->name('lancamentoOS.atualizaObservacao');
Route::post('/lancamentos/servico/painelAberturaOS/trocaClienteFatura/{empresa}/{numOS}', [App\Http\Controllers\LancamentoSrvOsController::class, 'atualizaCliFatura'])->name('lancamentoOS.atualizaCliFatura');
Route::post('/lancamentos/servico/painelAberturaOS/descontoOS/{empresa}/{numOS}', [App\Http\Controllers\LancamentoSrvOsController::class, 'atualizaDescontoOS'])->name('lancamentoOS.atualizaDescontoOS');
Route::post('/lancamentos/servico/painelAberturaOS/atualizaLocSrv/{empresa}/{numOS}/{cliente}', [App\Http\Controllers\LancamentoSrvOsController::class, 'atualizaLocSrv'])->name('lancamentoOS.atualizaLocSrv');

/* ********** Rotas do painel de Abertura de OS - Contrele Utilizado: PainelAberturaOSController ********** */
Route::get('/lancamentos/servico/painelAberturaOS/requisicao/consulta/{empresa}/{nos}/{estagioAPP}/{requisicao}', [App\Http\Controllers\Lancamentos\Servico\PainelAberturaOSController::class, 'consultaRequisicao'])->name('painelOS.consultaRequisicao');
Route::get('/lancamentos/servico/painelAberturaOS/requisicao/abrir/{empresa}/{nos}/{estagioAPP}/{glo_eat_cod}/{glo_eat_ord}', [App\Http\Controllers\Lancamentos\Servico\PainelAberturaOSController::class, 'abreRequisicao'])->name('painelOS.abreRequisicao');
Route::get('/lancamentos/servico/painelAberturaOS/requisicao/servico/abrir/{empresa}/{area}/{setor}/{estagioAPP}/{subEstagioRequisicao}/{requisicao}', [App\Http\Controllers\Lancamentos\Servico\PainelAberturaOSController::class, 'abrirServicoRequisicao'])->name('painelOS.abrirServicoRequisicao');
Route::get('/lancamentos/servico/painelAberturaOS/requisicao/servico/consulta/{empresa}/{numOS}/{requisicao}/{sequencia}/{codTMO}/{estagioAPP}/{subEstagioRequisicao}', [App\Http\Controllers\Lancamentos\Servico\PainelAberturaOSController::class, 'consultaServicoRequisicao'])->name('painelOS.consultaServicoRequisicao');
Route::get('/lancamentos/servico/painelAberturaOS/requisicao/servico/selecionar/{empresa}/{area}/{setor}/{codigo}/{estagioAPP}/{subEstagioRequisicao}/{requisicao}', [App\Http\Controllers\Lancamentos\Servico\PainelAberturaOSController::class, 'selecionarTMO'])->name('painelOS.selecionarTMO');
Route::get('/lancamentos/servico/painelAberturaOS/previsaoEntrega/{empresa}/{numOS}', [App\Http\Controllers\Lancamentos\Servico\PainelAberturaOSController::class, 'previsaoEntregaOS'])->name('painelOS.previsaoEntregaOS');
Route::get('/lancamentos/servico/painelAberturaOS/trocaLocSrv/{empresa}/{numOS}', [App\Http\Controllers\Lancamentos\Servico\PainelAberturaOSController::class, 'trocaLocSrv'])->name('painelOS.trocaLocSrv');
Route::get('/lancamentos/servico/painelAberturaOS/orcamento/{empresa}/{numOS}', [App\Http\Controllers\Lancamentos\Servico\PainelAberturaOSController::class, 'orcamentoOS'])->name('painelOS.orcamentoOS');
Route::get('/lancamentos/servico/painelAberturaOS/total/{empresa}/{numOS}', [App\Http\Controllers\Lancamentos\Servico\PainelAberturaOSController::class, 'totalOS'])->name('painelOS.totalOS');
Route::get('/lancamentos/servico/painelAberturaOS/encerraOS/{empresa}/{numOS}', [App\Http\Controllers\Lancamentos\Servico\PainelAberturaOSController::class, 'encerraOS'])->name('painelOS.encerraOS');
Route::get('/lancamentos/servico/painelAberturaOS/orcamento/impressao/pdf/{empresa}/{numOS}', [App\Http\Controllers\Lancamentos\Servico\PainelAberturaOSController::class, 'orcamentoGerarPDF'])->name('painelOS.orcamentoPDF');
Route::get('/lancamentos/servico/painelAberturaOS/previsaoEntrega/ajax/{empresa}/{numOS}/{qtdHoras}', [App\Http\Controllers\Lancamentos\Servico\PainelAberturaOSController::class, 'atualizaPrevEntregaAjax'])->name('painelOS.atualizaPrevEntregaAjax');

Route::post('/lancamentos/servico/painelAberturaOS/geraNF/{empresa}/{numOS}', [App\Http\Controllers\Lancamentos\Servico\PainelAberturaOSController::class, 'geraNF'])->name('painelOS.geraNF');
Route::post('/lancamentos/servico/painelAberturaOS/os/cancelar/{empresa}/{numOS}', [App\Http\Controllers\Lancamentos\Servico\PainelAberturaOSController::class, 'cancelarOS'])->name('requisicaoOS.cancelarOS');

/* ********** Rotas do painel de Abertura de OS - Contrele Utilizado: LancamentoSrvOsRequisicoesController ********** */
Route::get('/lancamentos/servico/painelAberturaOS/requisicao/reabrir/{empresa}/{numOS}/{requisicao}', [App\Http\Controllers\LancamentoSrvOsRequisicoesController::class, 'reabrirRequisicao'])->name('requisicaoOS.reabrir');
Route::get('/lancamentos/servico/painelAberturaOS/requisicao/finalizar/{empresa}/{numOS}/{requisicao}', [App\Http\Controllers\LancamentoSrvOsRequisicoesController::class, 'finalizarRequisicao'])->name('requisicaoOS.finalizar');
Route::get('/lancamentos/servico/painelAberturaOS/requisicao/ajaxSetor/{area}/{emp}', [App\Http\Controllers\LancamentoSrvOsRequisicoesController::class, 'carregaSetAjax'])->name('requisicaoOS.carregaSetAjax');
Route::get('/lancamentos/servico/painelAberturaOS/requisicao/ajaxSetor/{area}/{emp}/{cat}', [App\Http\Controllers\LancamentoSrvOsRequisicoesController::class, 'carregaTipSrvAjax'])->name('requisicaoOS.carregaTipSrvAjax');

Route::post('/lancamentos/servico/painelAberturaOS/requisicao/inserir/{empresa}/{numOS}/{glo_eat_cod}', [App\Http\Controllers\LancamentoSrvOsRequisicoesController::class, 'inserir'])->name('requisicaoOS.inserir');

Route::delete('/lancamentos/servico/painelAberturaOS/requisicao/{requisicaoOS}/{empresa}/{cliente}/{numOS}/destroy', [App\Http\Controllers\LancamentoSrvOsRequisicoesController::class, 'destroy'])->name('requisicaoOS.destroy');

/* ********** Rotas do painel de Abertura de OS - Contrele Utilizado: LancamentoSrvOsServicoController ********** */
Route::get('/lancamentos/servico/painelAberturaOS/requisicao/servico/aprovar/{empresa}/{numOS}/{requisicao}/{sequencia}/{codTMO}', [App\Http\Controllers\LancamentoSrvOsServicoController::class, 'aprovaServico'])->name('servicoOS.aprovar');
Route::post('/lancamentos/servico/painelAberturaOS/requisicao/servico/aprovar/{empresa}/{numOS}/{requisicao}', [App\Http\Controllers\LancamentoSrvOsServicoController::class, 'aprovaServicoBtn'])->name('servicoOS.aprovarBtn');
Route::post('/lancamentos/servico/painelAberturaOS/requisicao/servico/iniciar/{empresa}/{numOS}/{requisicao}', [App\Http\Controllers\LancamentoSrvOsServicoController::class, 'iniciarServico'])->name('servicoOS.iniciar');
Route::post('/lancamentos/servico/painelAberturaOS/requisicao/servico/finalizar/{empresa}/{numOS}/{requisicao}', [App\Http\Controllers\LancamentoSrvOsServicoController::class, 'finalizarServico'])->name('servicoOS.finalizar');
Route::post('/lancamentos/servico/painelAberturaOS/requisicao/servico/suspender/{empresa}/{numOS}/{requisicao}/{sequencia}/{codTMO}', [App\Http\Controllers\LancamentoSrvOsServicoController::class, 'suspenderServico'])->name('servicoOS.suspender');
Route::post('/lancamentos/servico/painelAberturaOS/requisicao/servico/cancelar/{empresa}/{numOS}/{requisicao}/{sequencia}/{codTMO}', [App\Http\Controllers\LancamentoSrvOsServicoController::class, 'cancelarServico'])->name('servicoOS.cancelar');
Route::get('/lancamentos/servico/painelAberturaOS/requisicao/servico/reabrir/{empresa}/{numOS}/{requisicao}/{sequencia}/{codTMO}', [App\Http\Controllers\LancamentoSrvOsServicoController::class, 'reabrirServico'])->name('servicoOS.reabrir');

Route::post('/lancamentos/servico/painelAberturaOS/requisicao/servico/inserir/{empresa}/{numOS}/{requisicao}/{codTMO}/{estagioAPP}', [App\Http\Controllers\LancamentoSrvOsServicoController::class, 'inserir'])->name('servicoOS.inserir');
Route::post('/lancamentos/servico/painelAberturaOS/requisicao/servico/atualizar/{empresa}/{numOS}/{requisicao}/{sequencia}/{codTMO}/{estagioAPP}/{tos}', [App\Http\Controllers\LancamentoSrvOsServicoController::class, 'update'])->name('servicoOS.atualizar');
Route::post('/lancamentos/servico/painelAberturaOS/requisicao/servico/desconto/autoriza/{empresa}/{numOS}/{requisicao}/{sequencia}/{tmo}', [App\Http\Controllers\LancamentoSrvOsServicoController::class, 'autorizaDescontoTMO'])->name('requisicaoOS.autorizaDescTMO');

Route::delete('/lancamentos/servico/painelAberturaOS/requisicao/servico/{servicoOS}/{empresa}/{numOS}/{requisicao}/destroy', [App\Http\Controllers\LancamentoSrvOsServicoController::class, 'destroy'])->name('servicoOS.destroy');

/* ********** Rotas de Emissao de OS ********** */
Route::get('/lancamentos/servico/homeOrcamentoOS', [App\Http\Controllers\HomeController::class, 'homeOrcamentoOS'])->name('home.orcamentoOS');

Route::get('/lancamentos/servico/consultaOrcamentoOS', [App\Http\Controllers\LancamentoSrvOsOrcamentosController::class, 'consultaOrcamentoOS'])->name('orcamento.consultaOrcamento');
