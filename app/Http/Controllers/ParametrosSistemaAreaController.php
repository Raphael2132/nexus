<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ParametrosSistemaArea;
use stdClass;

class ParametrosSistemaAreaController extends Controller
{
    protected $area;
    
    public function __construct(ParametrosSistemaArea $area)
    {
        $this->area = $area;
    }

    //Chama a app de edição dos dados do serviço selecionado
    public function editar($area)
    {
        $areaSelecionada = $this->area->where('area_codigo','=',$area)->get();

        return view('/parametros/sistema/editarParametrosSistemaAreas',['dadosArea'=>$areaSelecionada]);
    }

    //Realiza a atualização dos dados da área
    public function update(Request $request, $area){

        $atualizausuario = DB::table('parametros_sistema_areas')
            ->where('area_codigo', $area)
            ->update(['area_desc' => $request->descricao]);
        
        return redirect(route('parametrosSistemaAreas.editarCadastro', ['area' => $area]))->with('success', 'Área atualizado com sucesso!');
    }

    //Redireciona a app para o cadastro dos serviços
    public function cadastro()
    {
        return view('/parametros/sistema/cadastroParametrosSistemaAreas');
    }

    //Insere a área
    public function inserir(Request $request){

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
        
        $novaArea = ParametrosSistemaArea::create($dados);
        
        return redirect(route('parametrosSistemaAreas.editarCadastro', ['area' => $codSist]))->with('success', 'Área cadastrada com sucesso!');
    }

    //Exclui os dados da area
    public function destroy(ParametrosSistemaArea $area){

        $area->delete();
        
        return redirect(route('home.parSisArea'))->with('success', 'Área excluída com sucesso!');
    }
}
