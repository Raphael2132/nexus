<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LancamentoSrvCategorias;
use stdClass;

class LancamentoSrvCategoriasController extends Controller
{
    protected $categoria;
    
    public function __construct(LancamentoSrvCategorias $categoria)
    {
        $this->categoria = $categoria;
    }

    //Redireciona a app para o cadastro das categorias
    public function cadastro()
    {
        return view('/parametros/servico/formularioLancamentosServicoCategoria', ['acao' => 'N', 'dadosCat'=>'']);
    }

    //Chama a app de edição dos dados da categoria selecionada
    public function editar($codigo)
    {
        $categoriaSelecionada = $this->categoria->where('categoria_codigo','=',$codigo)->get();

        return view('/parametros/servico/formularioLancamentosServicoCategoria', ['acao' => 'E', 'dadosCat'=>$categoriaSelecionada]);
    }

    //Insere a categoria
    public function insert(Request $request){

        //Verifica se a categoria ja foi cadastrada
        $cat_cnt = $this->categoria->where('categoria_codigo','=',$request->codigo)->count();

        if($cat_cnt > 0){
            return redirect()->back()->with('error', 'A Categoria '.$request->codigo.' já foi cadastrada!');
        }

        $dados = [
            'categoria_codigo' => $request->codigo,
            'categoria_desc' => $request->descricao
        ];
        
        LancamentoSrvCategorias::create($dados);
        
       return redirect(route('lancamentosSrvCategoria.editarCadastro', ['codigo' => $request->codigo]))->with('success', 'Categoria de Atendimento cadastrada com sucesso!');
    }

    //Realiza a atualização dos dados da categoria
    public function update(Request $request){

        $atualizausuario = DB::table('lancamento_srv_categorias')
            ->where('categoria_codigo', $request->codigo)
            ->update(['categoria_desc' => $request->descricao]);
        
        return redirect(route('lancamentosSrvCategoria.editarCadastro', ['codigo' => $request->codigo]))->with('success', 'Categoria de Atendimento atualizada com sucesso!');
    }

    //Exclui os dados da categoria
    public function destroy(LancamentoSrvCategorias $categoria, $origem){

        $categoria->delete();

        if($origem == "ajax"){

            return response()->json(['success' => true],200);

        }else{
        
            return redirect(route('home.lancSrvCategoria'))->with('success', 'Categoria de Atendimento excluída com sucesso!');
        }
    }

    //Redireciona a home depois da exclusão do registro via ajax
    public function homeAjax()
    {  
        return redirect(route('home.lancSrvCategoria'))->with('success', 'Categoria de Atendimento excluída com sucesso!');
    }
}
