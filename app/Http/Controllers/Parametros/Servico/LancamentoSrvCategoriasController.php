<?php

namespace App\Http\Controllers\Parametros\Servico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Parametros\Servico\LancamentoSrvCategorias;

class LancamentoSrvCategoriasController extends Controller
{

    protected $categoria;
    
    public function __construct(LancamentoSrvCategorias $categoria)
    {
        $this->categoria = $categoria;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP Home de Categorias de Atendimento da Empresa
    |----------------------------------------------------------------------------------------------------
    */
    public function index()
    {
        $categorias = $this->categoria->all();

        return view('/parametros/servico/homeLancamentosServicoCategoria', ['categorias'=>$categorias]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP de Cadastro de Categorias de Atendimento
    |----------------------------------------------------------------------------------------------------
    */
    public function create()
    {
        return view('/parametros/servico/formularioLancamentosServicoCategoria', ['acao' => 'N', 'dadosCat'=>'']);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a Inclusão de Nova Categoria de Atendimento
    |----------------------------------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        //Verifica se a categoria ja foi cadastrada
        $cat_cnt = $this->categoria->where('categoria_codigo',$request->codigo)->count();

        if($cat_cnt > 0){
            return redirect()->back()->with('error', 'A Categoria '.$request->codigo.' já foi cadastrada!');
        }

        $dados = [
            'categoria_codigo' => $request->codigo,
            'categoria_desc' => $request->descricao
        ];
        
        LancamentoSrvCategorias::create($dados);
        
       return redirect(route('categAtendimento.edit', ['categAtendimento' => $request->codigo]))->with('success', 'Categoria de Atendimento cadastrada com sucesso!');
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
    | Executa a APP de Manutenção da Categoria de Atendimento
    |----------------------------------------------------------------------------------------------------
    */
    public function edit($codigo)
    {
        $categoriaSelecionada = $this->categoria->where('categoria_codigo', $codigo)->first();

        return view('/parametros/servico/formularioLancamentosServicoCategoria', ['acao' => 'E', 'dadosCat'=>$categoriaSelecionada]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza o Update dos dados da Categoria de Atendimento
    |----------------------------------------------------------------------------------------------------
    */
    public function update(Request $request, LancamentoSrvCategorias $categAtendimento)
    {
        $categAtendimento->update([
            'categoria_desc' => $request->descricao
        ]);
    
        return redirect(route('categAtendimento.edit', ['categAtendimento' => $request->codigo]))->with('success', 'Categoria de Atendimento atualizada com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza a Exclusão dos dados da Categoria de Atendimento
    |----------------------------------------------------------------------------------------------------
    */
    public function destroy(LancamentoSrvCategorias $categAtendimento)
    {
        $categAtendimento->delete();

        return redirect(route('categAtendimento.index'))->with('success', 'Categoria de Atendimento excluída com sucesso!');
    }
}
