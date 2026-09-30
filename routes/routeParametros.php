<?php

use Illuminate\Support\Facades\Route;

/*
|------------------------------------------------------------------------------------------
| Área de Parâmetros do Sistema
|------------------------------------------------------------------------------------------
|
| Área destina as rotas envolvidas na parâmetrização do sistema.
|
*/

// -------------------- Módulos do Sistema -------------------- //

/* ********** Rotas do Módulos do Sistema - Rotas REST ********** */
Route::resource('/parametros/sistema/modulosSistema', App\Http\Controllers\Parametros\Sistema\ParametrosSisModuloController::class);

// -------------------- Áreas do Sistema -------------------- //

/* ********** Rotas das Áreas do Sistema - Rotas REST ********** */
Route::resource('/parametros/sistema/areasSistema', App\Http\Controllers\Parametros\Sistema\ParametrosSisAreaController::class);

// -------------------- Grupos e Serviços da NFS-e -------------------- //

/* ********** Rota do Home dos Grupos e Serviços da NFS-e ********** */
Route::get('/parametros/sistema/homeParametrosSistemaServicos', [App\Http\Controllers\Parametros\Sistema\HomeParametrosSistemaController::class, 'homeParSisServico'])->name('home.parSisServico');

/* ********** Rotas dos Grupos de Serviços da NFS-e - Rotas REST ********** */
Route::resource('/parametros/sistema/gruposServicosSistema', App\Http\Controllers\Parametros\Sistema\ParametrosSisServicoGrupoController::class);

/* ********** Rotas dos Serviços da NFS-e - Rotas REST ********** */
Route::resource('/parametros/sistema/servicosSistema', App\Http\Controllers\Parametros\Sistema\ParametrosSisServicoController::class);

/*
|------------------------------------------------------------------------------------------
| Área de Parâmetros Gerais
|------------------------------------------------------------------------------------------
|
| Área destina as rotas envolvidas na parâmetrização geral do sistema.
|
*/

// -------------------- Geral da Empresa -------------------- //

/* ********** Rotas do Menu Geral da Empresa - Rotas REST ********** */
Route::resource('/parametros/gerencial/geralEmpresa', App\Http\Controllers\Parametros\Gerencial\ParametrosGerEmpresaController::class);

/* ********** Rotas do Turno da Empresa- Rotas REST ********** */
Route::resource('/parametros/gerencial/turnoEmpresa', App\Http\Controllers\Parametros\Gerencial\ParametrosGerTurnoController::class);

// -------------------- Setores -------------------- //

/* ********** Rotas ligadas aos Setores da Empresa- Rotas REST ********** */
Route::resource('/parametros/gerencial/setorEmpresa', App\Http\Controllers\Parametros\Gerencial\ParametrosSisSetoresController::class);

// -------------------- Motivos de Cancelamento -------------------- //

/* ********** Rotas dos Motivos de Cancelamento - Rotas REST ********** */
Route::resource('/parametros/gerencial/motivoCancelamento', App\Http\Controllers\Parametros\Gerencial\ParametrosSisCanMotivosController::class);

// -------------------- Motivos de Suspensão -------------------- //

/* ********** Rotas dos Motivos de Suspensão - Rotas REST ********** */
Route::resource('/parametros/gerencial/motivoSuspensao', App\Http\Controllers\Parametros\Gerencial\ParametrosSisSusMotivoController::class);

/*
|------------------------------------------------------------------------------------------
| Área de Parâmetros de Faturamento
|------------------------------------------------------------------------------------------
|
| Área destina as rotas envolvidas na parâmetrização do faturamento da NFS-e.
|
*/

// -------------------- Geral da Empresa -------------------- //

/* ********** Rotas ligadas aos Parâmetros Gerais da Empresa do Faturamento - Rotas REST ********** */
Route::resource('/parametros/faturamento/faturamentoGeral', App\Http\Controllers\Parametros\Faturamento\ParametrosFatEmpresasController::class);

/* ***** Emissão de NFS-e ***** */

/* ********** Rota do Home dos Parâmetros de Emissão da NFS-e ********** */
Route::get('/parametros/faturamento/nfs/homeParametroFatNfs', [App\Http\Controllers\Parametros\Faturamento\ParametrosFaturamentoController::class, 'homeFatNfs'])->name('home.parFatNfs');

/* ********** Rotas dos Provedores da NFS-e - Rotas REST ********** */
Route::resource('/parametros/faturamento/nfs/provedorNFSe', App\Http\Controllers\Parametros\Faturamento\Nfs\ParametrosFatNfsProvedoresController::class);

/* ********** Rotas da Parametrização das Conexões da NFS-e - Rotas REST ********** */
Route::resource('/parametros/faturamento/nfs/conexaoNFSe', App\Http\Controllers\Parametros\Faturamento\Nfs\ParametrosFatNfsConexoesController::class);

/* ********** Rotas da Parametrização da Emissão da NFS-e - Rotas REST ********** */
Route::resource('/parametros/faturamento/nfs/emissaoNFSe', App\Http\Controllers\Parametros\Faturamento\Nfs\ParametrosFatNfsController::class);

/*
|------------------------------------------------------------------------------------------
| Área de Parâmetros Financeiro
|------------------------------------------------------------------------------------------
|
| Área destina as rotas envolvidas na parâmetrização financeira do sistema.
|
*/

// -------------------- Cartão Corporativo -------------------- //

/* ********** Rotas do Menu Cartão Corporativo - Rotas REST ********** */
Route::resource('/parametros/financeiro/financeiroCorporativo', App\Http\Controllers\Parametros\Financeiro\ParametrosFinCorporativoCartoesController::class);

/*
|------------------------------------------------------------------------------------------
| Área de Parâmetros de Serviço
|------------------------------------------------------------------------------------------
|
| Área destina as rotas envolvidas na parâmetrização do lançamento de Serviço.
|
*/

// -------------------- Geral da Empresa -------------------- //

/* ********** Rotas da Parametrização Geral sobre Serviço - Rotas REST ********** */
Route::resource('/parametros/servico/geralServico', App\Http\Controllers\Parametros\Servico\ParametrosSrvEmpresasController::class);

// -------------------- Categoria de Atendimento -------------------- //

/* ********** Rotas da Parametrização das Categorias de Atendimento - Rotas REST ********** */
Route::resource('/parametros/servico/categAtendimento', App\Http\Controllers\Parametros\Servico\LancamentoSrvCategoriasController::class);

// -------------------- Etapas de Atendimento -------------------- //

/* ********** Rotas da Parametrização das Etapas de Atendimento - Rotas REST ********** */
Route::resource('/parametros/servico/etapaAtendimento', App\Http\Controllers\Parametros\Servico\LancamentoSrvEtapaAtendimentoController::class);

// -------------------- Tipos de Serviço -------------------- //

/* ********** Rotas da Parametrização dos Tipos de Serviço - Rotas REST ********** */
Route::resource('/parametros/servico/tipoServico', App\Http\Controllers\Parametros\Servico\LancamentoSrvTipoServicoController::class);

// -------------------- Tarefas de Mão de Obra -------------------- //

/* ********** Rotas da Parametrização das Tarefas de Mão de Obra - Rotas REST ********** */
Route::resource('/parametros/servico/servicoTMO', App\Http\Controllers\Parametros\Servico\ParametrosSrvTmoController::class);

