<?php

use Illuminate\Support\Facades\Route;

/*
|------------------------------------------------------------------------------------------
| Rotas dos Controllers Utility
|------------------------------------------------------------------------------------------
|
| Área destina as rotas envolvidas nos controllers da pasta Utility.
|
*/

/* ********** Rotas ligadas aos métodos AJAX ********** */

/* ********** Rotas de carregamento AJAX de Cidades da UF para campo Auto-Complete ********** */
Route::get('/utility/ajax/cidade/{uf}', [App\Http\Controllers\Utility\EventosAjaxController::class, 'getCidadeIbgeAjax'])->name('ajax.carregaCidAjax');

/* ********** Rotas de carregamento AJAX de Serviços da NFS-e do Grupo Selecionado para campo Select ********** */
Route::get('/utility/ajax/grupo/servicoNFS/{grupo}', [App\Http\Controllers\Utility\EventosAjaxController::class, 'getServicoNfsAjax'])->name('ajax.carregaServicoNfsAjax');

/* ********** Rotas de carregamento AJAX dos Setores da Empresa e Área Selecionada para campo Select ********** */
Route::get('/utility/ajax/empresa/area/setores/{area}/{empresa}', [App\Http\Controllers\Utility\EventosAjaxController::class, 'getEmpAreaSetoresAjax'])->name('ajax.carregaSetoresEmpAreAjax');

/* ********** Rotas de carregamento AJAX dos Setores da Empresa e Área Selecionada para campo Select ********** */
Route::get('/utility/ajax/setor/prestador/{area}/{setor}/{empresa}', [App\Http\Controllers\Utility\EventosAjaxController::class, 'getPrestadorSetorAjax'])->name('ajax.carregaPrestadorSetorAjax');

/* ********** Rotas de carregamento AJAX dos Grupos do CNAE para campo Select ********** */
Route::get('/utility/ajax/cnae/grupos/{divisao}', [App\Http\Controllers\Utility\EventosAjaxController::class, 'getCnaeGrupos'])->name('ajax.carregaGruposCnaeAjax');

/* ********** Rotas de carregamento AJAX dos Códigos CNAE para campo Select ********** */
Route::get('/utility/ajax/cnae/codigos/{divisao}/{grupo}', [App\Http\Controllers\Utility\EventosAjaxController::class, 'getCnaeCodigos'])->name('ajax.carregaCodigosCnaeAjax');

/* ********** Rotas de carregamento AJAX dos Usuários para campo Select ********** */
Route::get('/utility/ajax/usuarios', [App\Http\Controllers\Utility\EventosAjaxController::class, 'getUsuarios'])->name('ajax.carregaUsuariosAjax');

/* ********** Rotas de carregamento AJAX dos Turnos da Empresa para campo Select ********** */
Route::get('/utility/ajax/turnos/{empresa}', [App\Http\Controllers\Utility\EventosAjaxController::class, 'getTurnosEmpresa'])->name('ajax.carregaTurnosEmpresaAjax');

/* ********** Rotas de carregamento AJAX dos Turnos da Empresa para campo Select ********** */
Route::get('/utility/ajax/empresa/diaFuncionamento/{empresa}', [App\Http\Controllers\Utility\EventosAjaxController::class, 'getDiaFuncionamentoEmpresa'])->name('ajax.carregaDiaFunEmpresaAjax');

/* ********** Rotas de carregamento AJAX dos Razões da Empresa pelo Tipo para campo Select ********** */
Route::get('/utility/ajax/razao/{razao}/{empresa}', [App\Http\Controllers\Utility\EventosAjaxController::class, 'getRazoes'])->name('ajax.carregaRazaoAjax');

/* ********** Rotas de carregamento AJAX da verificação se a condição de pagamento utiliza valor de entrada ********** */
Route::get('/utility/ajax/condPGT/valorEntrada/{condPgt}', [App\Http\Controllers\Utility\EventosAjaxController::class, 'getValEntCondPgt'])->name('ajax.getValEntCondPgt');

/* ********** Rotas de carregamento AJAX do preenchimento do select de parcelas do cartão de crédito ********** */
Route::get('/utility/ajax/cartaoCredito/parcelas/{adm}/{emp}/{valor}', [App\Http\Controllers\Utility\EventosAjaxController::class, 'getParcelasCartaoCredito'])->name('ajax.getParcelasCartaoCredito');

/* ********** Rotas de carregamento AJAX do preenchimento do select de parcelas do cartão corporativo ********** */
Route::get('/utility/ajax/cartaoCorporativo/parcelas/{adm}/{emp}/{valor}', [App\Http\Controllers\Utility\EventosAjaxController::class, 'getParcelasCartaoCorporativo'])->name('ajax.getParcelasCartaoCorporativo');

/* ********** Rotas de carregamento AJAX do preenchimento do select de parcelas do cartão corporativo ********** */
Route::get('/utility/ajax/cartaoCorporativo/dados/{adm}/{emp}', [App\Http\Controllers\Utility\EventosAjaxController::class, 'getDadosCartaoCorporativo'])->name('ajax.getDadosCartaoCorporativo');

/* ********** Rotas de carregamento AJAX do tipo de crédito de pequenas despesas / receitas do financeiro ********** */
Route::get('/utility/ajax/tipoCredito/financeiro/{tabela}', [App\Http\Controllers\Utility\EventosAjaxController::class, 'getTipoCreditoPagRec'])->name('ajax.getTipoCreditoPagRec');

/* ********** Rotas de carregamento AJAX dos dados do Banco do Razão ********** */
Route::get('/utility/ajax/dadosBancoRazao/financeiro/{bco}/{emp}', [App\Http\Controllers\Utility\EventosAjaxController::class, 'getDadosBanco'])->name('ajax.getDadosBanco');

/* ********** Rotas de carregamento AJAX dos dados dos Caixas da Empresa ********** */
Route::get('/utility/ajax/caixasEmpresa/{empresa}', [App\Http\Controllers\Utility\EventosAjaxController::class, 'getCaixasEmpAjax'])->name('ajax.getCaixasEmpAjax');

/* ********** Rotas de carregamento AJAX da verificação se existe transferência em aberto no caixa de origem ********** */
Route::get('/utility/ajax/transfAberta/{origem}', [App\Http\Controllers\Utility\EventosAjaxController::class, 'getTransfCaixaAbertoAjax'])->name('ajax.getTransfCaixaAbertoAjax');