<?php

namespace App\Http\Controllers\Parametros\Sistema;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Parametros\Sistema\ParametrosSisArea;

class ParametrosSisAreaController extends Controller
{
    protected $area;

    public function __construct(ParametrosSisArea $area)
    {
        $this->area = $area;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP Home de Áreas do Sistema
    |----------------------------------------------------------------------------------------------------
    */
    public function index()
    {
        $areas = $this->area->reorder('area_desc', 'asc')->get();

        return view('/parametros/sistema/homeParametrosSistemaAreas', ['areas'=>$areas]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP de Cadastro de Áreas do Sistema
    |----------------------------------------------------------------------------------------------------
    */
    public function create()
    {
        return view('/parametros/sistema/cadastroParametrosSistemaAreas');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a Inclusão de Nova Área
    |----------------------------------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        //Força o código para letras maiusculas
        $codSist = strtoupper($request->codigo);

        //Verifica se o area ja foi cadastrada
        $are_cnt = $this->area->where('area_codigo','=',$codSist)->count();

        if($are_cnt > 0){
            return redirect()->back()->with('error', 'Área '.$codSist.' já foi cadastrada!');
        }

        $dados = [
            'area_codigo' => $codSist,
            'area_desc' => $request->descricao
        ];
        
        ParametrosSisArea::create($dados);
        
        return redirect(route('areasSistema.index'))->with('success', 'Área cadastrada com sucesso!');
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
    | Executa a APP de Manutenção da Áreas do Sistema
    |----------------------------------------------------------------------------------------------------
    */
    public function edit($codArea)
    {
        $areaSelecionada = $this->area->where('area_codigo',$codArea)->first();

        return view('/parametros/sistema/editarParametrosSistemaAreas',['dadosArea'=>$areaSelecionada]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza o Update dos dados da Área Selecionada
    |----------------------------------------------------------------------------------------------------
    */
    public function update(Request $request, ParametrosSisArea $areasSistema)
    {
        $areasSistema->area_desc = $request->descricao;
        $areasSistema->save();
        
        return redirect(route('areasSistema.edit', ['areasSistema' => $areasSistema->area_codigo]))->with('success', 'Área atualizada com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza a Exclusão dos dados da Área Selecionada
    |----------------------------------------------------------------------------------------------------
    */
    public function destroy(ParametrosSisArea $areasSistema)
    {
        $areasSistema->delete();
        
        return redirect(route('areasSistema.index'))->with('success', 'Área excluída com sucesso!');
    }
}
