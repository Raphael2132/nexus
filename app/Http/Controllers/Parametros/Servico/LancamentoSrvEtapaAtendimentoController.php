<?php

namespace App\Http\Controllers\Parametros\Servico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Parametros\Servico\LancamentoSrvEtapaAtendimento;

class LancamentoSrvEtapaAtendimentoController extends Controller
{
    protected $etapa;
    
    public function __construct(LancamentoSrvEtapaAtendimento $etapa)
    {
        $this->etapa = $etapa;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP Home de Etapas de Atendimento da Empresa
    |----------------------------------------------------------------------------------------------------
    */
    public function index()
    {
        $etapas = $this->etapa->reorder('eat_emp', 'asc')->reorder('eat_cod', 'asc')->reorder('eat_ord', 'asc')->get();

        return view('/parametros/servico/homeLancamentosServicoEtapas', ['etapas' => $etapas]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP de Cadastro de Etapas de Atendimento
    |----------------------------------------------------------------------------------------------------
    */
    public function create()
    {
        return view('/parametros/servico/formularioLancamentosServicoEtapas', ['acao' => 'N', 'dadosTMO' => '']);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a Inclusão de Nova Etapa de Atendimento
    |----------------------------------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        //Verifica se a etapa ja foi cadastrado
        $eat_cnt = $this->etapa->where('eat_emp','=',$request->empresa)->where('eat_cod','=',$request->codigo)->where('eat_ord','=',$request->ordem)->count();

        if($eat_cnt > 0){
            return redirect()->back()->with('error', 'Para a empresa '.$request->empresa.' a Etapa de Grupo: '.$request->codigo.' e Ordem: '.$request->ordem.' já foi cadastrada!');
        }

        $dados = [
            'eat_cod' => $request->codigo,
            'eat_emp' => $request->empresa,
            'eat_nom' => $request->descricao,
            'eat_ord' => $request->ordem,
            'eat_cat' => $request->categoria,
        ];
        
        $etapaAtendimento = LancamentoSrvEtapaAtendimento::create($dados);
        
        return redirect(route('etapaAtendimento.edit', ['etapaAtendimento' => $etapaAtendimento]))->with('success', 'Etapa de Atendimento cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP de Manutenção da Etapa de Atendimento
    |----------------------------------------------------------------------------------------------------
    */
    public function edit(LancamentoSrvEtapaAtendimento $etapaAtendimento)
    {
        $etapaSelecionado = $this->etapa->where('eat_cod',$etapaAtendimento->eat_cod)->where('eat_emp',$etapaAtendimento->eat_emp)->first();

        return view('/parametros/servico/formularioLancamentosServicoEtapas', ['acao' => 'E', 'dadosEAT' => $etapaSelecionado]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza o Update dos dados da Etapa de Atendimento
    |----------------------------------------------------------------------------------------------------
    */
    public function update(Request $request, LancamentoSrvEtapaAtendimento $etapaAtendimento)
    {
        $etapaAtendimento->update([
            'eat_nom' => $request->descricao,
            'eat_ord' => $request->ordem,
            'eat_cat' => $request->categoria
        ]);
        
        return redirect(route('etapaAtendimento.edit', ['etapaAtendimento' => $etapaAtendimento]))->with('success', 'Etapa atualizada com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza a Exclusão dos dados da Etapa de Atendimento
    |----------------------------------------------------------------------------------------------------
    */
    public function destroy(LancamentoSrvEtapaAtendimento $etapaAtendimento)
    {
        $etapaAtendimento->delete();

        return redirect(route('etapaAtendimento.index'))->with('success', 'Etapa excluída com sucesso!');
    }
}
