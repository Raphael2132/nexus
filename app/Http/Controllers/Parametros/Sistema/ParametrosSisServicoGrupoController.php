<?php

namespace App\Http\Controllers\Parametros\Sistema;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Parametros\Sistema\ParametrosSisServicoGrupo;

class ParametrosSisServicoGrupoController extends Controller
{
    protected $grupo;
    
    public function __construct(ParametrosSisServicoGrupo $grupo)
    {
        $this->grupo = $grupo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP de Cadastro de Grupos de Serviço
    |----------------------------------------------------------------------------------------------------
    */
    public function create()
    {
        return view('/parametros/sistema/cadastroGrpServicos');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a Inclusão de Novo Grupo de Serviço
    |----------------------------------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        //Verifica se o grupo ja foi cadastrada
        $grp_cnt = $this->grupo->where('grupo_codigo', $request->codigo)->count();

        if($grp_cnt > 0){
            return redirect()->back()->with('error', 'Grupo '.$request->codigo.' já foi cadastrado!');
        }

        $dados = [
            'grupo_codigo' => $request->codigo,
            'grupo_desc' => $request->descricao,    
        ];
        
        ParametrosSisServicoGrupo::create($dados);
        
        return redirect(route('gruposServicosSistema.edit', ['gruposServicosSistema' => $request->codigo]))->with('success', 'Grupo de Serviço cadastrado com sucesso!');
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
    | Executa a APP de Manutenção do Grupo de Serviço
    |----------------------------------------------------------------------------------------------------
    */
    public function edit($dadosGrupo)
    {
        $grupoSelecionado = $this->grupo->where('grupo_codigo', $dadosGrupo)->first();

        return view('/parametros/sistema/editarParametrosSistemaGrpServicos',['dadosGrupo'=>$grupoSelecionado]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza o Update dos dados do Grupo Selecionada
    |----------------------------------------------------------------------------------------------------
    */
    public function update(Request $request, ParametrosSisServicoGrupo $gruposServicosSistema){

        $gruposServicosSistema->grupo_desc = $request->descricao;
        $gruposServicosSistema->save();
        
        return redirect(route('gruposServicosSistema.edit', ['gruposServicosSistema' => $gruposServicosSistema->grupo_codigo]))->with('success', 'Grupo do Serviço atualizado com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza a Exclusão dos dados do Grupo Selecionado Selecionada
    |----------------------------------------------------------------------------------------------------
    */
    public function destroy(ParametrosSisServicoGrupo $gruposServicosSistema){

        $gruposServicosSistema->delete();
        
        return redirect(route('home.parSisServico'))->with('success', 'Grupo do Serviço excluído com sucesso!');
    }
}
