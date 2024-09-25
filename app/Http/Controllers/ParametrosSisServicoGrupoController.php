<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ParametrosSisServicoGrupo;
use stdClass;

class ParametrosSisServicoGrupoController extends Controller
{
    protected $grupo;
    
    public function __construct(ParametrosSisServicoGrupo $grupo)
    {
        $this->grupo = $grupo;
    }

    //Chama a app de edição dos dados do grupo de serviço selecionado
    public function editar($dadosGrupo)
    {
        $grupoSelecionado = $this->grupo->where('grupo_codigo','=',$dadosGrupo)->get();

        return view('/parametros/sistema/editarParametrosSistemaGrpServicos',['dadosGrupo'=>$grupoSelecionado]);
    }

    //Realiza a atualização dos dados do grupo do serviço
    public function update(Request $request, $grupo){

        $atualizausuario = DB::table('parametros_sis_servico_grupos')
            ->where('grupo_codigo', $grupo)
            ->update(['grupo_desc' => $request->descricao]);
        
        return redirect(route('parametrosSistemaGrpServico.editarCadastro', ['dadosGrupo' => $grupo]))->with('success', 'Grupo do Serviço atualizado com sucesso!');
    }

    //Redireciona a app para o cadastro dos grupos de serviço
    public function cadastro()
    {
        return view('/parametros/sistema/cadastroGrpServicos');
    }

    //Insere o grupo de serviço
    public function inserir(Request $request){

        //Verifica se o grupo ja foi cadastrada
        $grp_cnt = $this->grupo->where('grupo_codigo','=',$request->codigo)->count();

        if($grp_cnt > 0){
            return redirect()->back()->with('error', 'Grupo '.$request->codigo.' já foi cadastrado!');
        }

        $dados = [
            'grupo_codigo' => $request->codigo,
            'grupo_desc' => $request->descricao,    
        ];
        
        $novoGrupo = ParametrosSisServicoGrupo::create($dados);
        
        return redirect(route('parametrosSistemaGrpServico.editarCadastro', ['dadosGrupo' => $request->codigo]))->with('success', 'Grupo de Serviço cadastrado com sucesso!');
    }

    //Exclui os dados de um grupo
    public function destroy(ParametrosSisServicoGrupo $grupo){

        $grupo->delete();
        
        return redirect(route('home.parSisServico'))->with('success', 'Grupo do Serviço excluído com sucesso!');
    }
}
