<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

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

Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'home'])->name('home');

/*
|--------------------------------------------------------------------------
| Contato
|--------------------------------------------------------------------------
|
| Área destina as rotas envolvidas no contato e evio do email de contato.
|
*/
Route::get('/contato', [App\Http\Controllers\HomeController::class, 'contato'])->name('contato');
Route::post('/contato/email', [App\Http\Controllers\ContatoController::class, 'enviarEmailContato'])->name('contato.email');

/*
|--------------------------------------------------------------------------
| Parametros de Sessão do Control Sidebar
|--------------------------------------------------------------------------
|
| Área destina as rotas envolvidas nos parametros do Control Sidebar do sistema.
|
*/
// Rota para armazenar a empresa de visualização das Homes
Route::post('/empresaVisualizacao', function (Illuminate\Http\Request $request) {
    
    /*
    //Vamos definir qual a Home Inicial que o usuário vai usar de acordo com o plano da empresa
    $empresaPlano = DB::table('parametros_sis_modulos')
    ->where('modulo_empresa_codigo', $request->empresaHome)
    ->value('modulo_plano');

    if ($empresaPlano == 'BS01') {
        $home = 'homeNFSeSimplificada';
    } elseif ($empresaPlano == 'BE02') {
        $home = 'homeNFSe';
    }else{
        $home = 'home'; 
    }

    session([
        'glo_empresa_exibicao_home' => $request->empresaHome,  // Loja selecionada
        'glo_tipo_home' => $home  // Tipo de home selecionado
    ]);
    return response()->json(['success' => true]);
    */

    DB::beginTransaction();

    try {

        // Define plano da empresa
        $empresaPlano = DB::table('parametros_sis_modulos')
            ->where('modulo_empresa_codigo', $request->empresaHome)
            ->value('modulo_plano');

        if ($empresaPlano == 'BS01') {
            $home = 'homeNFSeSimplificada';
        } elseif ($empresaPlano == 'BE02') {
            $home = 'homeNFSe';
        } else {
            $home = 'home';
        }

        // Inicializa financeiro da empresa selecionada
        $exec_fn = DB::select(
            "SELECT ret_sts_pro001, ret_msg_pro001
             FROM fn_financeiro_pro001(:empresa, :data, :usuario, :app)",
            [
                'empresa' => $request->empresaHome,
                'data'    => now()->toDateString(),
                'usuario' => Auth::user()->usuario_codigo,
                'app'     => 'TrocaEmpresa',
            ]
        );

        if (!empty($exec_fn) && $exec_fn[0]->ret_sts_pro001 === '*') {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $exec_fn[0]->ret_msg_pro001,
            ], 422);
        }

        // 🔹 Atualiza sessão SOMENTE se init deu certo
        session([
            'glo_empresa_exibicao_home' => $request->empresaHome,
            'glo_tipo_home'             => $home,
        ]);

        DB::commit();

        return response()->json(['success' => true]);

    } catch (\Throwable $e) {

        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'Erro ao trocar empresa.',
        ], 500);
    }
});

// Rota para armazenar seleção
Route::post('/tipoHome', function (Illuminate\Http\Request $request) {
    session([
        'glo_tipo_home' => $request->tipoHome  // Tipo de home selecionado
    ]);
    return response()->json(['success' => true]);
});

/*
|--------------------------------------------------------------------------
| XML de NF
|--------------------------------------------------------------------------
|
| Área destina as rotas envolvidas na exibição do XML enviados de NFS-e e NF-e.
|
*/
Route::get('/nfse/envio/xml/{cnpj}/{filename}', function ($cnpj, $filename) {
    // Construa o caminho completo baseado no CNPJ
    $filePath = '/home/'.$cnpj.'/file/doc/nfsxml/envio/' . $filename;

    // Verifica se o arquivo existe
    if (!file_exists($filePath)) {
        abort(404, 'Arquivo não encontrado.');
    }

    // Retorna o arquivo para download ou exibição no navegador
    return response()->file($filePath);
})->name('nfs.xmlEnvio');

/*
|--------------------------------------------------------------------------
| Imagens do Cliente
|--------------------------------------------------------------------------
|
| Área destina as rotas envolvidas na exibição das Imagens do Cliente
|
*/
// Logo da Empresa Logada
Route::get('/logo/{cnpj}/{filename}', function ($cnpj, $filename) {
    
    $filePath = '/home/' . $cnpj . '/file/img/' . $filename;

    if (File::exists($filePath)) {
        $file = File::get($filePath);
        $type = File::mimeType($filePath);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }

    abort(404, 'Logo não encontrada.');
})->name('logo.file');

// Icone da Empresa Logada
Route::get('/icone/{cnpj}/{filename}', function ($cnpj, $filename) {
    
    $filePath = '/home/' . $cnpj . '/file/img/' . $filename;

    if (File::exists($filePath)) {
        $file = File::get($filePath);
        $type = File::mimeType($filePath);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }

    abort(404, 'Icone não encontrada.');
})->name('logoIco.file');


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
Route::get('/lancamentos/producao/subConsultaPainelOperacao/{empresa}/{numOS}', [App\Http\Controllers\PainelOperadorController::class, 'carregarDadosSubConsultaPainelOperador'])->name('painelOperacao.carregarDadosSubConsultaPainelOperador');

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
