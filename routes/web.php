<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('/home', function () {
    return view('home');
})->middleware(['auth', 'verified'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/contato', [App\Http\Controllers\HomeController::class, 'contato'])->name('contato');

/*
|--------------------------------------------------------------------------
| Área de Parâmetros do Sistema
|--------------------------------------------------------------------------
|
| Área destina as rotas envolvidas na parâmetrização do sistema.
|
*/

/* ********** Rotas ligadas a parte de módulos do sistema ********** */
Route::get('/parametros/sistema/homeParametrosSistemaModulos', [App\Http\Controllers\HomeController::class, 'homeParSisModulo'])->name('home.parSisModulo');
Route::get('/parametros/sistema/homeParametrosSistemaModulos/{dadosModulo}', [App\Http\Controllers\ParametrosSistemaModuloController::class, 'editar'])->name('parametrosSistemaModulos.editarCadastro');
Route::post('/parametros/sistema/editarParametrosSistemaModulos/{empresa}', [App\Http\Controllers\ParametrosSistemaModuloController::class, 'update'])->name('parametrosSistemaModulos.atualizar');

/* ********** Rotas ligadas a parte de áreas do sistema ********** */
Route::get('/parametros/sistema/homeParametrosSistemaAreas', [App\Http\Controllers\HomeController::class, 'homeParSisArea'])->name('home.parSisArea');
Route::get('/parametros/sistema/cadastroParametrosSistemaAreas', [App\Http\Controllers\ParametrosSistemaAreaController::class, 'cadastro'])->name('parametrosSistemaAreas.cadastro');
Route::get('/parametros/sistema/editarParametrosSistemaAreas/{area}', [App\Http\Controllers\ParametrosSistemaAreaController::class, 'editar'])->name('parametrosSistemaAreas.editarCadastro');
Route::post('/parametros/sistema/editarParametrosSistemaAreas/{area}', [App\Http\Controllers\ParametrosSistemaAreaController::class, 'update'])->name('parametrosSistemaAreas.atualizar');
Route::post('/parametros/sistema/editarParametrosSistemaAreas', [App\Http\Controllers\ParametrosSistemaAreaController::class, 'inserir'])->name('parametrosSistemaAreas.inserir');
Route::delete('/parametros/sistema/{area}/destroy', [App\Http\Controllers\ParametrosSistemaAreaController::class, 'destroy'])->name('parametrosSistemaAreas.destroy');

/* ********** Rotas ligadas a parte de grupos e serviços da nfs-e ********** */
Route::get('/parametros/sistema/homeParametrosSistemaServicos', [App\Http\Controllers\HomeController::class, 'homeParSisServico'])->name('home.parSisServico');

/* Grupo do Serviço */
Route::get('/parametros/sistema/editarParametrosSistemaGrpServicos/{dadosGrupo}', [App\Http\Controllers\ParametrosSistemaServicoGrupoController::class, 'editar'])->name('parametrosSistemaGrpServico.editarCadastro');
Route::get('/parametros/sistema/cadastroGrpServicos', [App\Http\Controllers\ParametrosSistemaServicoGrupoController::class, 'cadastro'])->name('parametrosSistemaGrpServico.cadastro');
Route::post('/parametros/sistema/editarParametrosSistemaGrpServicos/{grupo}', [App\Http\Controllers\ParametrosSistemaServicoGrupoController::class, 'update'])->name('parametrosSistemaGrpServico.atualizar');
Route::post('/parametros/sistema/editarParametrosSistemaGrpServicos', [App\Http\Controllers\ParametrosSistemaServicoGrupoController::class, 'inserir'])->name('parametrosSistemaGrpServico.inserir');
Route::delete('/parametros/sistema/servicoGrupo/{grupo}/destroy', [App\Http\Controllers\ParametrosSistemaServicoGrupoController::class, 'destroy'])->name('parametrosSistemaGrpServico.destroy');

/* Serviço */
Route::get('/parametros/sistema/editarParametrosSistemaServicos/{grupo}/{servico}', [App\Http\Controllers\ParametrosSistemaServicoController::class, 'editar'])->name('parametrosSistemaServico.editarCadastro');
Route::get('/parametros/sistema/cadastroServicos', [App\Http\Controllers\ParametrosSistemaServicoController::class, 'cadastro'])->name('parametrosSistemaServico.cadastro');
Route::post('/parametros/sistema/editarParametrosSistemaServicos/{grupo}/{servico}', [App\Http\Controllers\ParametrosSistemaServicoController::class, 'update'])->name('parametrosSistemaServico.atualizar');
Route::post('/parametros/sistema/editarParametrosSistemaServicos', [App\Http\Controllers\ParametrosSistemaServicoController::class, 'inserir'])->name('parametrosSistemaServico.inserir');
Route::delete('/parametros/sistema/servico/{servico}/destroy', [App\Http\Controllers\ParametrosSistemaServicoController::class, 'destroy'])->name('parametrosSistemaServico.destroy');

/*
|--------------------------------------------------------------------------
| Área de Parâmetros Gerais
|--------------------------------------------------------------------------
|
| Área destina as rotas envolvidas na parâmetrização geral do sistema.
|
*/

/* ********** Rotas ligadas a parte Gerencial ********** */

/* Geral da Empresa */
Route::get('/parametros/gerencial/homeParametrosGerencialEmpresa', [App\Http\Controllers\HomeController::class, 'homeParametroGerEmp'])->name('home.parametrosGerEmp');
Route::get('/parametros/gerencial/formularioParametrosGerEmpresa/{empresa}', [App\Http\Controllers\ParametrosGerEmpresaController::class, 'editar'])->name('parametrosGerEmp.editarCadastro');

Route::post('/parametros/gerencial/formularioParametrosGerEmpresa/update/{empresa}', [App\Http\Controllers\ParametrosGerEmpresaController::class, 'update'])->name('parametrosGerEmp.update');
Route::post('/parametros/gerencial/formularioParametrosGerEmpresa/turno/insert/{empresa}', [App\Http\Controllers\ParametrosGerTurnoController::class, 'inserir'])->name('parametrosGerEmp.insertTurno');
Route::delete('/parametros/gerencial/formularioParametrosGerEmpresa/turno/{turno}/{empresa}/destroy', [App\Http\Controllers\ParametrosGerTurnoController::class, 'destroy'])->name('parametrosGerEmp.destroy');

/* Setor */
Route::get('/parametros/servico/homeParametrosServicoSetor', [App\Http\Controllers\HomeController::class, 'homeParSrvSetor'])->name('home.parSrvSetor');
Route::get('/parametros/servico/homeParametrosServicoSetor/ajax', [App\Http\Controllers\ParametrosSrvSetoresController::class, 'homeAjax'])->name('parametrosSrvSetor.homeAjax');

/* Motivo de Cancelamento */
Route::get('/parametros/sistema/homeParametrosSistemaMotivosCancelamento', [App\Http\Controllers\HomeController::class, 'homeParMotCan'])->name('home.parMotCan');
Route::get('/parametros/servico/formularioParametrosSisMotCancelamento/ajax', [App\Http\Controllers\ParametrosSistemaCanMotivosController::class, 'homeAjax'])->name('parametrosSisMotCan.homeAjax');
Route::get('/parametros/sistema/formularioParametrosSisMotCancelamento/{acao}/{dadosMotCan}', [App\Http\Controllers\ParametrosSistemaCanMotivosController::class, 'cadastroMotCan'])->name('parametrosSisMotCan.cadastroMotCan');

Route::post('/parametros/sistema/formularioParametrosSisMotCancelamento/insert', [App\Http\Controllers\ParametrosSistemaCanMotivosController::class, 'insert'])->name('parametrosSisMotCan.insert');
Route::post('/parametros/sistema/formularioParametrosSisMotCancelamento/update', [App\Http\Controllers\ParametrosSistemaCanMotivosController::class, 'update'])->name('parametrosSisMotCan.update');
Route::delete('/parametros/sistema/motivoCancelamento/{motivo}/{origem}/destroy', [App\Http\Controllers\ParametrosSistemaCanMotivosController::class, 'destroy'])->name('parametrosSisMotCan.destroy');

/* Motivo de Suspensão */
Route::get('/parametros/sistema/homeParametrosSistemaMotivosSuspensao', [App\Http\Controllers\HomeController::class, 'homeParMotSus'])->name('home.parMotSus');
Route::get('/parametros/servico/formularioParametrosSisMotSuspensao/ajax', [App\Http\Controllers\ParametrosSisSusMotivoController::class, 'homeAjax'])->name('parametrosSisMotSus.homeAjax');
Route::get('/parametros/sistema/formularioParametrosSisMotSuspensao/{acao}/{dadosMotSus}', [App\Http\Controllers\ParametrosSisSusMotivoController::class, 'cadastroMotSus'])->name('parametrosSisMotSus.cadastroMotSus');

Route::post('/parametros/sistema/formularioParametrosSisMotSuspensao/insert', [App\Http\Controllers\ParametrosSisSusMotivoController::class, 'insert'])->name('parametrosSisMotSus.insert');
Route::post('/parametros/sistema/formularioParametrosSisMotSuspensao/update', [App\Http\Controllers\ParametrosSisSusMotivoController::class, 'update'])->name('parametrosSisMotSus.update');
Route::delete('/parametros/sistema/motivoSuspensao/{motivo}/{origem}/destroy', [App\Http\Controllers\ParametrosSisSusMotivoController::class, 'destroy'])->name('parametrosSisMotSus.destroy');

/* ********** Rotas ligadas a parte de faturamento de nfs ********** */

/* Geral da Empresa */
Route::get('/parametros/faturamento/homeParametrosFatEmpresa', [App\Http\Controllers\HomeController::class, 'homeParametroFatEmp'])->name('home.parametrosFatEmp');
Route::get('/parametros/faturamento/formularioParametrosFatEmpresa/{empresa}', [App\Http\Controllers\ParametrosFatEmpresasController::class, 'editar'])->name('parametrosFatEmp.editarCadastro');

Route::post('/parametros/faturamento/formularioParametrosFatEmpresa/update', [App\Http\Controllers\ParametrosFatEmpresasController::class, 'update'])->name('parametrosFatEmp.update');

/* Parâmetros da NFS-e */
Route::get('/parametros/faturamento/nfs/homeParametroFatNfs', [App\Http\Controllers\HomeController::class, 'homeParFatNfs'])->name('home.parFatNfs');

/* Provedor */
Route::get('/parametros/faturamento/nfs/parametrosNfsProvedor', [App\Http\Controllers\ParametrosFatNfsProvedoresController::class, 'provedor'])->name('parametrosNfsProvedor');
Route::get('/parametros/faturamento/nfs/parametrosNfsProvedor/ajax/cidade/{uf}', [App\Http\Controllers\ParametrosFatNfsProvedoresController::class, 'carregaCidAjax'])->name('parametrosNfsProvedor.carregaCidAjax');
Route::post('/parametros/faturamento/nfs/parametrosNfsProvedor', [App\Http\Controllers\ParametrosFatNfsProvedoresController::class, 'inserir'])->name('parametrosNfsProvedor.inserir');

/* Conexão */
Route::get('/parametros/faturamento/nfs/parametrosNfsConexao', [App\Http\Controllers\ParametrosFatNfsConexoesController::class, 'conexao'])->name('parametrosNfsConexao');
Route::get('/parametros/faturamento/nfs/editarParametrosNfsConexao/{dadosConexao}/{appOrigem}', [App\Http\Controllers\ParametrosFatNfsConexoesController::class, 'editar'])->name('parmetrosNfsCon.editarCadastro');
Route::post('/parametros/faturamento/nfs/editarParametrosNfsConexao/{empresa}', [App\Http\Controllers\ParametrosFatNfsConexoesController::class, 'update'])->name('parmetrosNfsCon.atualizar');

/* Emissao */
Route::get('/parametros/faturamento/nfs/parametrosNfsEmissao', [App\Http\Controllers\ParametrosFatNfsController::class, 'emissao'])->name('parametrosNfsEmissao');
Route::get('/parametros/faturamento/nfs/editarParametrosNfsEmissao/{dadosEmissao}/{appOrigem}', [App\Http\Controllers\ParametrosFatNfsController::class, 'editar'])->name('parmetrosNfsEmi.editarCadastro');
Route::post('/parametros/faturamento/nfs/editarParametrosNfsEmissao/{empresa}', [App\Http\Controllers\ParametrosFatNfsController::class, 'update'])->name('parmetrosNfsEmi.atualizar');

/* ********** Rotas ligadas a parte de lançamento de serviços ********** */

/* Geral da Empresa */
Route::get('/parametros/servico/homeParametrosServicoEmpresa', [App\Http\Controllers\HomeController::class, 'homeParametroSrvEmp'])->name('home.parametrosSrvEmp');
Route::get('/parametros/servico/formularioParametrosServicoEmpresa/{empresa}', [App\Http\Controllers\ParametrosSrvEmpresasController::class, 'editar'])->name('parametrosSrvEmp.editarCadastro');
Route::get('/parametros/servico/formularioParametrosServicoEmpresa/ajax/{codigo}', [App\Http\Controllers\ParametrosSrvEmpresasController::class, 'carregaCodSrvAjax'])->name('parametrosSrvEmp.carregaCodSrvAjax');

Route::post('/parametros/servico/formularioParametrosServicoEmpresa/update', [App\Http\Controllers\ParametrosSrvEmpresasController::class, 'update'])->name('parametrosSrvEmp.update');

/* Categorias de Atendimento */
Route::get('/parametros/servico/homeLancamentosServicoCategoria', [App\Http\Controllers\HomeController::class, 'homeLancSrvCategoria'])->name('home.lancSrvCategoria');
Route::get('/parametros/servico/homeLancamentosServicoCategoria/ajax', [App\Http\Controllers\LancamentoSrvCategoriasController::class, 'homeAjax'])->name('lancamentosSrvCategoria.homeAjax');

Route::get('/parametros/servico/formularioLancamentosServicoCategoria', [App\Http\Controllers\LancamentoSrvCategoriasController::class, 'cadastro'])->name('lancamentosSrvCategoria.cadastro');
Route::get('/parametros/servico/formularioLancamentosServicoCategoria/{codigo}', [App\Http\Controllers\LancamentoSrvCategoriasController::class, 'editar'])->name('lancamentosSrvCategoria.editarCadastro');
Route::post('/parametros/servico/formularioLancamentosServicoCategoria/insert', [App\Http\Controllers\LancamentoSrvCategoriasController::class, 'insert'])->name('lancamentosSrvCategoria.insert');
Route::post('/parametros/servico/formularioLancamentosServicoCategoria/update', [App\Http\Controllers\LancamentoSrvCategoriasController::class, 'update'])->name('lancamentosSrvCategoria.update');
Route::delete('/parametros/servico/categoria/{categoria}/{origem}/destroy', [App\Http\Controllers\LancamentoSrvCategoriasController::class, 'destroy'])->name('lancamentosSrvCategoria.destroy');

/* Etapas de Atendimento */
Route::get('/parametros/servico/homeLancamentosServicoEtapas', [App\Http\Controllers\HomeController::class, 'homeLancSrvEtapas'])->name('home.lancSrvEtapas');
Route::get('/parametros/servico/homeLancamentosServicoEtapas/ajax', [App\Http\Controllers\LancamentoSrvEtapaAtendimentoController::class, 'homeAjax'])->name('lancamentosSrvEtapas.homeAjax');

Route::get('/parametros/servico/formularioLancamentosServicoEtapas/ajax/{empresa}/{categoria}', [App\Http\Controllers\LancamentoSrvEtapaAtendimentoController::class, 'carregaTipoServAjax'])->name('lancamentosSrvEtapas.carregaTipoServAjax');
Route::get('/parametros/servico/formularioLancamentosServicoEtapas/ajax/{empresa}/{categoria}/{tipo}', [App\Http\Controllers\LancamentoSrvEtapaAtendimentoController::class, 'carregaSetAreAjax'])->name('lancamentosSrvEtapas.carregaSetAreAjax');

Route::get('/parametros/servico/formularioLancamentosServicoEtapas', [App\Http\Controllers\LancamentoSrvEtapaAtendimentoController::class, 'cadastro'])->name('lancamentosSrvEtapas.cadastro');
Route::get('/parametros/servico/formularioLancamentosServicoEtapas/{codigo}/{empresa}', [App\Http\Controllers\LancamentoSrvEtapaAtendimentoController::class, 'editar'])->name('lancamentosSrvEtapas.editarCadastro');
Route::post('/parametros/servico/formularioLancamentosServicoEtapas/insert', [App\Http\Controllers\LancamentoSrvEtapaAtendimentoController::class, 'insert'])->name('lancamentosSrvEtapas.insert');
Route::post('/parametros/servico/formularioLancamentosServicoEtapas/update', [App\Http\Controllers\LancamentoSrvEtapaAtendimentoController::class, 'update'])->name('lancamentosSrvEtapas.update');
Route::delete('/parametros/servico/etapas/{etapa}/{origem}/destroy', [App\Http\Controllers\LancamentoSrvEtapaAtendimentoController::class, 'destroy'])->name('lancamentosSrvEtapas.destroy');

/* Tipo de Serviço */
Route::get('/parametros/servico/homeLancamentosServicoTipo', [App\Http\Controllers\HomeController::class, 'homeLancSrvTipo'])->name('home.lancSrvTipo');
Route::get('/parametros/servico/homeLancamentosServicoTipo/ajax', [App\Http\Controllers\LancamentoSrvTipoServicoController::class, 'homeAjax'])->name('lancamentosSrvTipo.homeAjax');

Route::get('/parametros/servico/formularioLancamentosServicoTipo', [App\Http\Controllers\LancamentoSrvTipoServicoController::class, 'cadastro'])->name('lancamentosSrvTipo.cadastro');
Route::get('/parametros/servico/formularioLancamentosServicoTipo/{codigo}/{empresa}', [App\Http\Controllers\LancamentoSrvTipoServicoController::class, 'editar'])->name('lancamentosSrvTipo.editarCadastro');
Route::post('/parametros/servico/formularioLancamentosServicoTipo/insert', [App\Http\Controllers\LancamentoSrvTipoServicoController::class, 'insert'])->name('lancamentosSrvTipo.insert');
Route::post('/parametros/servico/formularioLancamentosServicoTipo/update', [App\Http\Controllers\LancamentoSrvTipoServicoController::class, 'update'])->name('lancamentosSrvTipo.update');
Route::delete('/parametros/servico/tipo/{tipo}/{origem}/destroy', [App\Http\Controllers\LancamentoSrvTipoServicoController::class, 'destroy'])->name('lancamentosSrvTipo.destroy');

Route::get('/parametros/servico/formularioParametrosServicoSetor', [App\Http\Controllers\ParametrosSrvSetoresController::class, 'cadastro'])->name('parametrosSrvSetor.cadastro');
Route::get('/parametros/servico/formularioParametrosServicoSetor/{codigo}/{empresa}/{area}', [App\Http\Controllers\ParametrosSrvSetoresController::class, 'editar'])->name('parametrosSrvSetor.editarCadastro');
Route::post('/parametros/servico/formularioParametrosServicoSetor/insert', [App\Http\Controllers\ParametrosSrvSetoresController::class, 'insert'])->name('parametrosSrvSetor.insert');
Route::post('/parametros/servico/formularioParametrosServicoSetor/update', [App\Http\Controllers\ParametrosSrvSetoresController::class, 'update'])->name('parametrosSrvSetor.update');
Route::delete('/parametros/servico/setor/{setor}/{origem}/destroy', [App\Http\Controllers\ParametrosSrvSetoresController::class, 'destroy'])->name('parametrosSrvSetor.destroy');

/* Tarefas de mão de obra */
Route::get('/parametros/servico/homeParametrosServicoTMO', [App\Http\Controllers\HomeController::class, 'homeParSrvTMO'])->name('home.parSrvTMO');
Route::get('/parametros/servico/homeParametrosServicoTMO/ajax', [App\Http\Controllers\ParametrosSrvTmoController::class, 'homeAjax'])->name('parametrosSrvTMO.homeAjax');

Route::get('/parametros/servico/formularioParametrosServicoTMO', [App\Http\Controllers\ParametrosSrvTmoController::class, 'cadastro'])->name('parametrosSrvTMO.cadastro');
Route::get('/parametros/servico/formularioParametrosServicoTMO/{empresa}/{setor}/{codigo}', [App\Http\Controllers\ParametrosSrvTmoController::class, 'editar'])->name('parametrosSrvTMO.editarCadastro');
Route::get('/parametros/servico/formularioParametrosServicoTMO/ajax/{codigo}', [App\Http\Controllers\ParametrosSrvTmoController::class, 'carregaCodSrvAjax'])->name('parametrosSrvTMO.carregaCodSrvAjax');
Route::get('/parametros/servico/formularioParametrosServicoTMO/ajax/set/{area}/{empresa}', [App\Http\Controllers\ParametrosSrvTmoController::class, 'carregaSetAjax'])->name('parametrosSrvTMO.carregaSetAjax');
Route::get('/parametros/servico/formularioParametrosServicoTMO/ajax/resp/{area}/{setor}/{empresa}', [App\Http\Controllers\ParametrosSrvTmoController::class, 'carregaRespAjax'])->name('parametrosSrvTMO.carregaRespAjax');
Route::post('/parametros/servico/formularioParametrosServicoTMO/insert', [App\Http\Controllers\ParametrosSrvTmoController::class, 'insert'])->name('parametrosSrvTMO.insert');
Route::post('/parametros/servico/formularioParametrosServicoTMO/update', [App\Http\Controllers\ParametrosSrvTmoController::class, 'update'])->name('parametrosSrvTMO.update');
Route::delete('/parametros/servico/tmo/{tarefa}/{origem}/destroy', [App\Http\Controllers\ParametrosSrvTmoController::class, 'destroy'])->name('parametrosSrvTMO.destroy');

/*
|--------------------------------------------------------------------------
| Área de Cadastros do Sistema
|--------------------------------------------------------------------------
|
| Área destina as rotas envolvidas no cadastramento de informações de empresa, clientes, usuarios, bancos e produtos.
|
*/

/* ********** Rotas de Cadastro de Clientes ********** */
Route::get('/cadastros/cliente/homeClientes', [App\Http\Controllers\HomeController::class, 'homeClientes'])->name('home.clientes');
Route::get('/cadastros/cliente/clientes/{tipo}', [App\Http\Controllers\CadastroClienteController::class, 'clientes'])->name('clientes');
Route::get('/cadastros/cliente/cadastroCliente', [App\Http\Controllers\CadastroClienteController::class, 'cadastro'])->name('cliente.cadastro');
Route::get('/cadastros/cliente/editarCadastroCliente/{dadosCliente}/{tipo}', [App\Http\Controllers\CadastroClienteController::class, 'editar'])->name('cliente.editarCadastro');
Route::get('/cadastros/cliente/editarCadastroCliente/{endereco}/{cliente_cod}/{tipo}', [App\Http\Controllers\CadastroClienteEnderecoController::class, 'principal'])->name('enderecoCliente.principal');

Route::post('/cadastros/cliente/cadastroCliente', [App\Http\Controllers\CadastroClienteController::class, 'inserir'])->name('cliente.inserir');
Route::post('/cadastros/cliente/editarCadastroCliente/{cliente}/{cliente_cod}/{atualiza}/{tipo}', [App\Http\Controllers\CadastroClienteController::class, 'update'])->name('cliente.atualizar');
Route::post('/cadastros/cliente/editarCadastroCliente/{tipo}', [App\Http\Controllers\CadastroClienteEnderecoController::class, 'inserir'])->name('enderecoCliente.inserir');

Route::delete('/cliente/{cliente}/destroy', [App\Http\Controllers\CadastroClienteController::class, 'destroy'])->name('cliente.destroy');
Route::delete('/cliente/editarCadastroCliente/{endereco}/{tipo}/destroy', [App\Http\Controllers\CadastroClienteEnderecoController::class, 'destroy'])->name('enderecoCliente.destroy');

/* ********** Rotas de Cadastro de Usuarios ********** */
Route::get('/cadastros/usuario/homeUsuarios', [App\Http\Controllers\HomeController::class, 'homeUsuarios'])->name('home.usuarios');
Route::get('/cadastros/usuario/usuarios/{tipo}', [App\Http\Controllers\CadastroUsuarioController::class, 'usuarios'])->name('usuarios');
Route::get('/cadastros/usuario/cadastroUsuario/{tipo}', [App\Http\Controllers\CadastroUsuarioController::class, 'cadastro'])->name('usuario.cadastro');
Route::get('/cadastros/usuario/editarCadastroUsuario/{dadosUsuario}/{tipo}', [App\Http\Controllers\CadastroUsuarioController::class, 'editar'])->name('usuario.editarCadastro');
Route::get('/cadastros/usuario/editarCadastroUsuario/{endereco}/{usuario_cod}/{tipo}', [App\Http\Controllers\CadastroUsuarioEnderecoController::class, 'principal'])->name('enderecoUsuario.principal');

Route::post('/cadastros/usuario/cadastroUsuario/{tipo}', [App\Http\Controllers\CadastroUsuarioController::class, 'inserir'])->name('usuario.inserir');
Route::post('/cadastros/usuario/editarCadastroUsuario/{usuario}/{usuario_cod}/{atualiza}/{tipo}', [App\Http\Controllers\CadastroUsuarioController::class, 'update'])->name('usuario.atualizar');
Route::post('/cadastros/usuario/editarCadastroUsuario/{tipo}', [App\Http\Controllers\CadastroUsuarioEnderecoController::class, 'inserir'])->name('enderecoUsuario.inserir');

Route::delete('/usuario/{usuario}/destroy', [App\Http\Controllers\CadastroUsuarioController::class, 'destroy'])->name('usuario.destroy');
Route::delete('/usuario/editarCadastroUsuario/{endereco}/{tipo}/destroy', [App\Http\Controllers\CadastroUsuarioEnderecoController::class, 'destroy'])->name('enderecoUsuario.destroy');

/* ********** Rotas de Cadastro de Empresas ********** */
Route::get('/cadastros/empresa/homeEmpresa', [App\Http\Controllers\HomeController::class, 'homeEmpresa'])->name('home.empresa');
Route::get('/cadastros/empresa/cadastroEmpresa', [App\Http\Controllers\CadastroEmpresaController::class, 'cadastro'])->name('empresa.cadastro');
Route::get('/cadastros/empresa/editarCadastroEmpresa/{dadosEmpresa}', [App\Http\Controllers\CadastroEmpresaController::class, 'editar'])->name('empresa.editarCadastro');
Route::get('/cadastros/empresa/editarCadastroEmpresa/{endereco}/{empresa_cod}', [App\Http\Controllers\CadastroEmpresaEnderecoController::class, 'principal'])->name('enderecoEmpresa.principal');

Route::post('/cadastros/empresa/cadastroEmpresa', [App\Http\Controllers\CadastroEmpresaController::class, 'inserir'])->name('empresa.inserir');
Route::post('/cadastros/empresa/editarCadastroEmpresa/{empresa}/{empresa_cod}/{atualiza}', [App\Http\Controllers\CadastroEmpresaController::class, 'update'])->name('empresa.atualizar');
Route::post('/cadastros/empresa/editarCadastroEmpresa', [App\Http\Controllers\CadastroEmpresaEnderecoController::class, 'inserir'])->name('enderecoEmpresa.inserir');

Route::delete('/empresa/editarCadastroEmpresa/{endereco}/destroy', [App\Http\Controllers\CadastroEmpresaEnderecoController::class, 'destroy'])->name('enderecoEmpresa.destroy');
Route::delete('/empresa/{empresa}/destroy', [App\Http\Controllers\CadastroEmpresaController::class, 'destroy'])->name('empresa.destroy');

/* ********** Rotas de Cadastro de Prestadores ********** */
Route::get('/cadastros/prestador/homePrestadores', [App\Http\Controllers\HomeController::class, 'homePrestadores'])->name('home.prestadores');
Route::get('/cadastros/prestador/consultaPrestador/{tipo}', [App\Http\Controllers\CadastroPrestadoresController::class, 'prestadorConsulta'])->name('prestador.consulta');
Route::get('/cadastros/prestador/formularioPrestador/novo', [App\Http\Controllers\CadastroPrestadoresController::class, 'cadastro'])->name('prestador.cadastro');
Route::get('/cadastros/prestador/formularioPrestador/ajaxSetor/{area}/{empresa}', [App\Http\Controllers\CadastroPrestadoresController::class, 'carregaSetAjax'])->name('prestador.carregaSetAjax');
Route::get('/cadastros/prestador/formularioPrestador/ajaxTurno/{empresa}', [App\Http\Controllers\CadastroPrestadoresController::class, 'carregaTurAjax'])->name('prestador.carregaTurAjax');
Route::get('/cadastros/prestador/formularioPrestador/ajaxTurnoSel/{empresa}', [App\Http\Controllers\CadastroPrestadoresController::class, 'carregaTurSelAjax'])->name('prestador.carregaTurSelAjax');
Route::get('/cadastros/prestador/formularioPrestador/ajaxUsuario', [App\Http\Controllers\CadastroPrestadoresController::class, 'carregaUsuAjax'])->name('prestador.carregaUsuAjax');
Route::get('/cadastros/prestador/formularioPrestador/{dadosPrestador}/{empresa}/{tipo}', [App\Http\Controllers\CadastroPrestadoresController::class, 'editar'])->name('prestador.editarCadastro');
Route::get('/cadastros/prestador/formularioPrestador/enderecoPrincipal/{endereco}/{prestador_cod}/{empresa}/{tipo}', [App\Http\Controllers\CadastroPrestadoresEnderecoController::class, 'principal'])->name('enderecoPrestador.principal');

Route::post('/cadastros/prestador/formularioPrestador', [App\Http\Controllers\CadastroPrestadoresController::class, 'inserir'])->name('prestador.inserir');
Route::post('/cadastros/prestador/formularioPrestador/{prestador}/{prestador_cod}/{atualiza}/{empresa}/{tipo}', [App\Http\Controllers\CadastroPrestadoresController::class, 'update'])->name('prestador.atualizar');
Route::post('/cadastros/prestador/formularioPrestador/endereco/{empresa}/{tipo}', [App\Http\Controllers\CadastroPrestadoresEnderecoController::class, 'inserir'])->name('enderecoPrestador.inserir');

Route::delete('/cadastros/prestador/formularioPrestador/{prestador}/destroy', [App\Http\Controllers\CadastroPrestadoresController::class, 'destroy'])->name('prestador.destroy');
Route::delete('/cadastros/prestador/formularioPrestador/{endereco}/{empresa}/{tipo}/destroy', [App\Http\Controllers\CadastroPrestadoresEnderecoController::class, 'destroy'])->name('enderecoPrestador.destroy');

/*
|--------------------------------------------------------------------------
| Área de Serviços
|--------------------------------------------------------------------------
|
| Área destina as rotas envolvidas no lançamento de informações de serviço.
|
*/

/* ********** Rotas de Emissao de OS ********** */
Route::get('/lancamentos/servico/homeEmissaoOS', [App\Http\Controllers\HomeController::class, 'homeEmissaoOS'])->name('home.emissaoOS');
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
Route::get('/lancamentos/servico/painelAberturaOS/requisicao/consulta/{empresa}/{nos}/{estagioAPP}/{requisicao}', [App\Http\Controllers\PainelAberturaOSController::class, 'consultaRequisicao'])->name('painelOS.consultaRequisicao');
Route::get('/lancamentos/servico/painelAberturaOS/requisicao/abrir/{empresa}/{nos}/{estagioAPP}/{glo_eat_cod}/{glo_eat_ord}', [App\Http\Controllers\PainelAberturaOSController::class, 'abreRequisicao'])->name('painelOS.abreRequisicao');
Route::get('/lancamentos/servico/painelAberturaOS/requisicao/servico/abrir/{empresa}/{area}/{setor}/{estagioAPP}/{subEstagioRequisicao}/{requisicao}', [App\Http\Controllers\PainelAberturaOSController::class, 'abrirServicoRequisicao'])->name('painelOS.abrirServicoRequisicao');
Route::get('/lancamentos/servico/painelAberturaOS/requisicao/servico/consulta/{empresa}/{numOS}/{requisicao}/{sequencia}/{codTMO}/{estagioAPP}/{subEstagioRequisicao}', [App\Http\Controllers\PainelAberturaOSController::class, 'consultaServicoRequisicao'])->name('painelOS.consultaServicoRequisicao');
Route::get('/lancamentos/servico/painelAberturaOS/requisicao/servico/selecionar/{empresa}/{area}/{setor}/{codigo}/{estagioAPP}/{subEstagioRequisicao}/{requisicao}', [App\Http\Controllers\PainelAberturaOSController::class, 'selecionarTMO'])->name('painelOS.selecionarTMO');
Route::get('/lancamentos/servico/painelAberturaOS/previsaoEntrega/{empresa}/{numOS}', [App\Http\Controllers\PainelAberturaOSController::class, 'previsaoEntregaOS'])->name('painelOS.previsaoEntregaOS');
Route::get('/lancamentos/servico/painelAberturaOS/trocaLocSrv/{empresa}/{numOS}', [App\Http\Controllers\PainelAberturaOSController::class, 'trocaLocSrv'])->name('painelOS.trocaLocSrv');
Route::get('/lancamentos/servico/painelAberturaOS/orcamento/{empresa}/{numOS}', [App\Http\Controllers\PainelAberturaOSController::class, 'orcamentoOS'])->name('painelOS.orcamentoOS');
Route::get('/lancamentos/servico/painelAberturaOS/total/{empresa}/{numOS}', [App\Http\Controllers\PainelAberturaOSController::class, 'totalOS'])->name('painelOS.totalOS');
Route::get('/lancamentos/servico/painelAberturaOS/encerraOS/{empresa}/{numOS}', [App\Http\Controllers\PainelAberturaOSController::class, 'encerraOS'])->name('painelOS.encerraOS');
Route::get('/lancamentos/servico/painelAberturaOS/orcamento/impressao/pdf/{empresa}/{numOS}', [App\Http\Controllers\PainelAberturaOSController::class, 'orcamentoGerarPDF'])->name('painelOS.orcamentoPDF');
Route::get('/lancamentos/servico/painelAberturaOS/previsaoEntrega/ajax/{empresa}/{numOS}/{qtdHoras}', [App\Http\Controllers\PainelAberturaOSController::class, 'atualizaPrevEntregaAjax'])->name('painelOS.atualizaPrevEntregaAjax');

Route::post('/lancamentos/servico/painelAberturaOS/os/cancelar/{empresa}/{numOS}', [App\Http\Controllers\PainelAberturaOSController::class, 'cancelarOS'])->name('requisicaoOS.cancelarOS');

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

/*
|--------------------------------------------------------------------------
| Área de Serviços - Controle de Produção
|--------------------------------------------------------------------------
|
| Área destina as rotas envolvidas no Controle de Produção.
|
*/

/* ********** Rotas do Painel de Agendamento ********** */
Route::get('/lancamentos/producao/homeAgendamentoPrestador', [App\Http\Controllers\HomeController::class, 'homeAgendamentoPrt'])->name('home.agendamentoPrt');

Route::post('/lancamentos/producao/calendarioAgendamentoPrestador/ajaxSetAge', [App\Http\Controllers\LancamentoSrvExeTarefaController::class, 'updateAgeAjax'])->name('agenda.updateAgeAjax');
Route::post('/lancamentos/producao/calendarioAgendamentoPrestador', [App\Http\Controllers\PainelAgendamentoController::class, 'agendaPrestador'])->name('agenda.calendarioPrt');

/* ********** Rotas do Painel do Operador ********** */
Route::get('/lancamentos/producao/homePainelOperador', [App\Http\Controllers\HomeController::class, 'homePainelOperador'])->name('home.painelOperador');
Route::get('/lancamentos/producao/modalPainelOperadorAgendaSrvOS/{empresa}/{numOS}', [App\Http\Controllers\PainelOperadorController::class, 'carregarDadosModalAgendaSrvOS'])->name('painelOperacao.carregarDadosModalAgendaSrvOS');
Route::get('/lancamentos/producao/modalPainelOperadorAddAuxiliarTMO/{empresa}/{numOS}/{requisicao}/{servico}', [App\Http\Controllers\PainelOperadorController::class, 'carregarDadosModalAddAxuTMO'])->name('painelOperacao.carregarDadosModalAddAxuTMO');
Route::get('/lancamentos/producao/modalPainelOperadorAddChangePrt/{empresa}/{numOS}/{requisicao}/{servico}', [App\Http\Controllers\PainelOperadorController::class, 'carregarDadosModalAddChangePrt'])->name('painelOperacao.carregarDadosModalAddChangePrt');
Route::get('/lancamentos/producao/modalPainelOperadorStartService/{empresa}/{numOS}/{requisicao}/{servico}', [App\Http\Controllers\PainelOperadorController::class, 'carregarDadosModalStartService'])->name('painelOperacao.carregarDadosModalStartService');
Route::get('/lancamentos/producao/modalPainelOperadorFinishService/{empresa}/{numOS}/{requisicao}/{servico}', [App\Http\Controllers\PainelOperadorController::class, 'carregarDadosModalFinishService'])->name('painelOperacao.carregarDadosModalFinishService');
Route::get('/lancamentos/producao/modalPainelOperadorCancelService/{empresa}/{numOS}/{requisicao}/{servico}', [App\Http\Controllers\PainelOperadorController::class, 'carregarDadosModalCancelService'])->name('painelOperacao.carregarDadosModalCancelService');
Route::get('/lancamentos/producao/modalPainelOperadorSuspendService/{empresa}/{numOS}/{requisicao}/{servico}', [App\Http\Controllers\PainelOperadorController::class, 'carregarDadosModalSuspendService'])->name('painelOperacao.carregarDadosModalSuspendService');
Route::get('/lancamentos/producao/modalPainelOperadorReopenService/{empresa}/{numOS}/{requisicao}/{servico}', [App\Http\Controllers\PainelOperadorController::class, 'carregarDadosModalReopenService'])->name('painelOperacao.carregarDadosModalReopenService');
Route::get('/lancamentos/producao/modalPainelOperadorFinishRequisicao/{empresa}/{numOS}/{requisicao}', [App\Http\Controllers\PainelOperadorController::class, 'carregarDadosModalFinishRequisicao'])->name('painelOperacao.carregarDadosModalFinishRequisicao');
Route::get('/lancamentos/producao/modalPainelOperadorReopenRequisicao/{empresa}/{numOS}/{requisicao}', [App\Http\Controllers\PainelOperadorController::class, 'carregarDadosModalReopenRequisicao'])->name('painelOperacao.carregarDadosModalReopenRequisicao');

Route::post('/lancamentos/producao/consultaPainelOperacao', [App\Http\Controllers\PainelOperadorController::class, 'consultaPainelOperacao'])->name('painelOperacao.consultaPainel');
Route::get('/lancamentos/producao/consultaPainelOperacao/{empresa}/{where}', [App\Http\Controllers\PainelOperadorController::class, 'consultaPainelOperacaoGET'])->name('painelOperacao.consultaPainelGET');

Route::post('/lancamentos/producao/modalPainelOperadorStartService/{empresa}/{numOS}/{requisicao}/{servico}', [App\Http\Controllers\LancamentoSrvExeTarefaController::class, 'iniciarTMO'])->name('painelOperacao.iniciarTMO');
Route::post('/lancamentos/producao/modalPainelOperadorFinishService/{empresa}/{numOS}/{requisicao}/{servico}', [App\Http\Controllers\LancamentoSrvExeTarefaController::class, 'finalizarTMO'])->name('painelOperacao.finalizarTMO');
Route::post('/lancamentos/producao/modalPainelOperadorCancelService/{empresa}/{numOS}/{requisicao}/{servico}', [App\Http\Controllers\LancamentoSrvExeTarefaController::class, 'cancelarTMO'])->name('painelOperacao.cancelarTMO');
Route::post('/lancamentos/producao/modalPainelOperadorSuspendService/{empresa}/{numOS}/{requisicao}/{servico}', [App\Http\Controllers\LancamentoSrvExeTarefaController::class, 'suspenderTMO'])->name('painelOperacao.suspenderTMO');
Route::post('/lancamentos/producao/modalPainelOperadorReopenService/{empresa}/{numOS}/{requisicao}/{servico}', [App\Http\Controllers\LancamentoSrvExeTarefaController::class, 'reabrirTMO'])->name('painelOperacao.reabrirTMO');
Route::post('/lancamentos/producao/modalPainelOperadorAddChangePrt/{empresa}/{numOS}/{requisicao}/{servico}', [App\Http\Controllers\LancamentoSrvExeTarefaController::class, 'addChangePrtTMO'])->name('painelOperacao.addChangePrtTMO');
Route::post('/lancamentos/producao/modalPainelOperadorAddAuxiliarTMO/{empresa}/{numOS}/{requisicao}/{servico}', [App\Http\Controllers\LancamentoSrvExeTarefaController::class, 'addPrtAuxTMO'])->name('painelOperacao.addPrtAuxTMO');
Route::post('/lancamentos/producao/modalPainelOperadorFinishRequisicao/finalizar/{empresa}/{numOS}/{requisicao}', [App\Http\Controllers\LancamentoSrvOsRequisicoesController::class, 'finalizarRequisicaoPO'])->name('painelOperacao.finalizarReqPO');
Route::post('/lancamentos/producao/modalPainelOperadorFinishRequisicao/reabrir/{empresa}/{numOS}/{requisicao}', [App\Http\Controllers\LancamentoSrvOsRequisicoesController::class, 'reabrirRequisicaoPO'])->name('painelOperacao.reabrirReqPO');

/* ********** Rotas do Painel de Produção ********** */
Route::get('/lancamentos/producao/homePainelProducao', [App\Http\Controllers\HomeController::class, 'homePainelProducao'])->name('home.painelProducao');

/* Rotas do Painel de Produção por Prestador */
Route::get('/lancamentos/producao/homePainelProducao/ajax/set/{empresa}', [App\Http\Controllers\PainelProducaoController::class, 'carregaSetAjax'])->name('painelProducao.carregaSetAjax');
Route::get('/lancamentos/producao/homePainelProducao/ajax/tur/{empresa}', [App\Http\Controllers\PainelProducaoController::class, 'carregaTurAjax'])->name('painelProducao.carregaTurAjax');

Route::get('/lancamentos/producao/homePainelProducao/carregaEventosTMO/{empresa}/{setor}/{data}', [App\Http\Controllers\PainelProducaoController::class, 'carregaEventosTMO'])->name('painelProducao.carregaEventosTMO');
Route::get('/lancamentos/producao/homePainelProducao/carregaOsAndamento/{empresa}/{setor}/{data}', [App\Http\Controllers\PainelProducaoController::class, 'getOsEmAndamento'])->name('painelProducao.carregaOsAndamento');
Route::get('/lancamentos/producao/homePainelProducao/carregaOsFinalizadas/{empresa}/{setor}/{data}', [App\Http\Controllers\PainelProducaoController::class, 'getOsFinalizadas'])->name('painelProducao.carregaOsFinalizadas');
Route::get('/lancamentos/producao/homePainelProducao/carregaOsProximas/{empresa}/{setor}/{data}', [App\Http\Controllers\PainelProducaoController::class, 'getProximasOs'])->name('painelProducao.carregaOsProximas');

Route::post('/lancamentos/producao/painelProducao', [App\Http\Controllers\PainelProducaoController::class, 'abrePainel'])->name('painelProducao.painelProducao');

/* Rotas do Painel de Produção por OS */
Route::get('/lancamentos/producao/painelProducaoOS/carregaOs/{empresa}/{data}', [App\Http\Controllers\PainelProducaoController::class, 'carregaOs'])->name('painelProducao.carregaOs');

/*
|--------------------------------------------------------------------------
| Área de Faturamento
|--------------------------------------------------------------------------
|
| Área destina as rotas envolvidas no lançamento de informações de faturamento.
|
*/

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

Route::get('/faturamento/notas/simplificada/consultaReemissaoSimpNF', [App\Http\Controllers\FaturamentoNfsSimplificadaController::class, 'consultaReemissaoSimpNF'])->name('reemissaoSimpNF.consultaReemissaoSimpNF');

/* ********** Rotas de Impressão de NFS-e ********** */
Route::get('/faturamento/notas/impressao/nfse/{empresa}/{numControle}', [App\Http\Controllers\FaturamentoNotasImpressaoController::class, 'nfseGerarPDF'])->name('impresaoNF.nfsePDF');

/* ********** Rotas de Emissao de NF ********** */
Route::get('/faturamento/notas/controleEmissaoNF', [App\Http\Controllers\HomeController::class, 'emissaoNF'])->name('home.emissaoNF');

Route::get('/faturamento/notas/consultaEmissaoNF', [App\Http\Controllers\FaturamentoNfHeaderController::class, 'consultaNF'])->name('emissaoNF.consultaNF');
Route::get('/faturamento/notas/painelEmissaoNF/{empresa}/{cliente}/{nfSelecionada}', [App\Http\Controllers\FaturamentoNfHeaderController::class, 'painelNF'])->name('emissaoNF.painelNF');

Route::get('/faturamento/notas/controleGeracaoNF/{empresa}/{cliente}/{nfSelecionada}/{origem}', [App\Http\Controllers\FaturamentoGeracaoNfController::class, 'gerarNF'])->name('emissaoNF.gerarNF');

/* ********** Rotas de Reemissao de NF ********** */
Route::get('/faturamento/notas/controleReemissaoNF', [App\Http\Controllers\HomeController::class, 'reemissaoNF'])->name('home.reemissaoNF');

Route::get('/faturamento/notas/consultaReemissaoNF', [App\Http\Controllers\FaturamentoNfHeaderController::class, 'consultaReemissaoNF'])->name('reemissaoNF.consultaReemissaoNF');