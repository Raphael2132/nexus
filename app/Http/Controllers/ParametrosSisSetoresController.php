<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ParametrosSisSetores;
use stdClass;

class ParametrosSisSetoresController extends Controller
{
    protected $setor;
    
    public function __construct(ParametrosSisSetores $setor)
    {
        $this->setor = $setor;
    }

    //Redireciona a app para o cadastro dos setores
    public function cadastro()
    {
        return view('/parametros/servico/formularioParametrosServicoSetor', ['acao' => 'N', 'dadosSetor'=>'']);
    }

    //Chama a app de edição dos dados do setor selecionado
    public function editar($codigo, $empresa, $area)
    {
        $setorSelecionado = $this->setor->where('setor_codigo','=',$codigo)->where('setor_empresa','=',$empresa)->where('setor_area','=',$area)->get();

        return view('/parametros/servico/formularioParametrosServicoSetor', ['acao' => 'E', 'dadosSetor'=>$setorSelecionado]);
    }

    //Insere o setor
    public function insert(Request $request){

        //Verifica se o setor ja foi cadastrada
        //01/03 - agora é possivel a empresa ter varios setores da mesma área
        /*$set_cnt = $this->setor->where('setor_empresa','=',$request->empresa)->where('setor_area','=',$request->area)->count();

        if($set_cnt > 0){
            return redirect()->back()->with('error', 'Setor da empresa '.$request->empresa.' e da Área '.$request->area.' já foi cadastrado!');
        }*/

        $nextval=DB::select("SELECT nextval('sq_parametros_sis_setores')")[0]->nextval;
        $codigo = $request->area.str_pad($nextval,3,'0',STR_PAD_LEFT);

        $dados = [
            'setor_codigo' => $codigo,
            'setor_empresa' => $request->empresa,
            'setor_area' => $request->area,
            'setor_desc' => $request->descricao 
        ];
        
        $novoSetor = ParametrosSisSetores::create($dados);
        
        return redirect(route('parametrosSrvSetor.editarCadastro', ['codigo' => $codigo, 'empresa' => $request->empresa, 'area' => $request->area]))->with('success', 'Setor cadastrado com sucesso!');
    }

    //Realiza a atualização dos dados do setor
    public function update(Request $request){

        $atualizausuario = DB::table('parametros_sis_setores')
            ->where('setor_codigo', $request->codigo)
            ->where('setor_empresa', $request->empresa)
            ->where('setor_area', $request->area)
            ->update(['setor_desc' => $request->descricao]);
        
        return redirect(route('parametrosSrvSetor.editarCadastro', ['codigo' => $request->codigo, 'empresa' => $request->empresa, 'area' => $request->area]))->with('success', 'Setor atualizado com sucesso!');
    }

    //Exclui os dados do setor
    public function destroy(ParametrosSisSetores $setor, $origem){

        $setor->delete();

        if($origem == "ajax"){

            return response()->json(['success' => true],200);

        }else{
        
            return redirect(route('home.parSrvSetor'))->with('success', 'Setor excluído com sucesso!');
        }
    }

    //Redireciona a home depois da exclusão do registro via ajax
    public function homeAjax()
    {  
        return redirect(route('home.parSrvSetor'))->with('success', 'Setor excluído com sucesso!');
    }
}
