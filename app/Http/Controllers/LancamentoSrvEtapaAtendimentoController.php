<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LancamentoSrvEtapaAtendimento;
use stdClass;

class LancamentoSrvEtapaAtendimentoController extends Controller
{
    protected $etapa;
    
    public function __construct(LancamentoSrvEtapaAtendimento $etapa)
    {
        $this->etapa = $etapa;
    }

    //Redireciona a app para o cadastro dos setores
    public function cadastro()
    {
        return view('/parametros/servico/formularioLancamentosServicoEtapas', ['acao' => 'N', 'dadosTMO'=>'']);
    }

    //Chama a app de edição dos dados da etapa selecionada
    public function editar($codigo, $empresa)
    {
        $etapaSelecionado = $this->etapa->where('eat_cod','=',$codigo)->where('eat_emp','=',$empresa)->get();

        return view('/parametros/servico/formularioLancamentosServicoEtapas', ['acao' => 'E', 'dadosEAT'=>$etapaSelecionado]);
    }

    //Insere a etapa
    public function insert(Request $request){

        //Verifica se a etapa ja foi cadastrado
        $eat_cnt = $this->etapa->where('eat_emp','=',$request->empresa)->where('eat_cod','=',$request->codigo)->count();

        if($eat_cnt > 0){
            return redirect()->back()->with('error', 'Para a empresa '.$request->empresa.' a Etapa '.$request->codigo.' já foi cadastrada!');
        }

        $dados = [
            'eat_cod' => $request->codigo,
            'eat_emp' => $request->empresa,
            'eat_nom' => $request->descricao,
            'eat_ord' => $request->ordem,
            'eat_cat' => $request->categoria,
            'eat_are' => $request->area
        ];
        
        LancamentoSrvEtapaAtendimento::create($dados);
        
        return redirect(route('lancamentosSrvEtapas.editarCadastro', ['codigo' => $request->codigo, 'empresa' => $request->empresa]))->with('success', 'Etapa de Atendimento cadastrada com sucesso!');
    }

    //Realiza a atualização dos dados da etapa
    public function update(Request $request){

        $atualizaEAT = DB::table('lancamento_srv_etapa_atendimentos')
            ->where('eat_cod', $request->codigo)
            ->where('eat_emp', $request->empresa)
            ->update(['eat_nom' => $request->descricao,
            'eat_ord' => $request->ordem,
            'eat_cat' => $request->categoria,
            'eat_are' => $request->area]);
        
        return redirect(route('lancamentosSrvEtapas.editarCadastro', ['empresa' => $request->empresa, 'codigo' => $request->codigo]))->with('success', 'Etapa atualizada com sucesso!');
    }

    //Exclui os dados da etapa
    public function destroy(LancamentoSrvEtapaAtendimento $etapa, $origem){

        $etapa->delete();

        if($origem == "ajax"){

            return response()->json(['success' => true],200);

        }else{
        
            return redirect(route('home.lancSrvEtapas'))->with('success', 'Etapa excluída com sucesso!');
        }
    }

    //Redireciona a home depois da exclusão do registro via ajax
    public function homeAjax()
    {  
        return redirect(route('home.lancSrvEtapas'))->with('success', 'Etapa excluída com sucesso!');
    }
}
