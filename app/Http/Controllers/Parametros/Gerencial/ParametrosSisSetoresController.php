<?php

namespace App\Http\Controllers\parametros\gerencial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Parametros\Gerencial\ParametrosSisSetores;

class ParametrosSisSetoresController extends Controller
{
    protected $setor;
    
    public function __construct(ParametrosSisSetores $setor)
    {
        $this->setor = $setor;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP Home de Setores da Empresa
    |----------------------------------------------------------------------------------------------------
    */
    public function index()
    {
        $setores = $this->setor->reorder('setor_area', 'asc')->get();

        return view('/parametros/gerencial/homeParametrosGerencialSetor', ['setores'=>$setores]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP de Cadastro de Setores da Empresa
    |----------------------------------------------------------------------------------------------------
    */
    public function create()
    {
        return view('/parametros/gerencial/formularioParametrosGerencialSetor', ['acao' => 'N', 'dadosSetor'=>'']);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a Inclusão de Novo Setor
    |----------------------------------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $nextval=DB::select("SELECT nextval('sq_parametros_sis_setores')")[0]->nextval;
        $codigo = $request->area.str_pad($nextval,3,'0',STR_PAD_LEFT);

        $dados = [
            'setor_codigo' => $codigo,
            'setor_empresa' => $request->empresa,
            'setor_area' => $request->area,
            'setor_desc' => $request->descricao 
        ];
        
        $novoSetor = ParametrosSisSetores::create($dados);

        return redirect(route('setorEmpresa.edit', ['setorEmpresa' => $novoSetor]))->with('success', 'Setor cadastrado com sucesso!');
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
    | Executa a APP de Manutenção da Setores da Empresa
    |----------------------------------------------------------------------------------------------------
    */
    public function edit(ParametrosSisSetores $setorEmpresa)
    {
        $codigo = $setorEmpresa->setor_codigo;
        $empresa = $setorEmpresa->setor_empresa;
        $area = $setorEmpresa->setor_area;

        $setorSelecionado = $this->setor->where('setor_codigo',$codigo)->where('setor_empresa',$empresa)->where('setor_area',$area)->first();

        return view('/parametros/gerencial/formularioParametrosGerencialSetor', ['acao' => 'E', 'dadosSetor'=>$setorSelecionado]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza o Update dos dados do Setor Selecionado
    |----------------------------------------------------------------------------------------------------
    */
    public function update(Request $request, ParametrosSisSetores $setorEmpresa)
    {
        $setorEmpresa->setor_desc = $request->descricao;
    
        $setorEmpresa->save();
        
        return redirect(route('setorEmpresa.edit', ['setorEmpresa' => $setorEmpresa]))->with('success', 'Setor atualizado com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza a Exclusão dos dados do Setor Selecionado
    |----------------------------------------------------------------------------------------------------
    */
    public function destroy(ParametrosSisSetores $setorEmpresa){

        $setorEmpresa->delete();
        
        return redirect(route('setorEmpresa.index'))->with('success', 'Setor excluído com sucesso!');
    }
}
