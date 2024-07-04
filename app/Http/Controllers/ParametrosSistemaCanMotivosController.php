<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ParametrosSistemaCanMotivos;
use stdClass;

class ParametrosSistemaCanMotivosController extends Controller
{
    protected $motivo;
    
    public function __construct(ParametrosSistemaCanMotivos $motivo)
    {
        $this->motivo = $motivo;
    }

    //Redireciona a app para o formulario
    public function cadastroMotCan($acao, $dadosMotCan)
    {
        if(!empty(trim($dadosMotCan))){
            $dadosMotCan = DB::table('parametros_sistema_can_motivos')->where('canmot_codigo', $dadosMotCan)->get();
        }else{
            $dadosMotCan = ' ';
        }

        return view('/parametros/sistema/formularioParametrosSisMotCancelamento',['acao'=>$acao, 'dadosMotCan' => $dadosMotCan]);
    }

    //Insere o motivo
    public function insert(Request $request){

        //Verifica se já existe motivo cadastrado com o mesmo codigo
        $motivos = DB::table('parametros_sistema_can_motivos')->where('canmot_codigo', $request->codigo)->count();

        if($motivos > 0){
            return redirect()->back()->with('error', 'Já existe motivo cadstrado com o código '.$request->codigo.'!');
        }

        $dados = [
            'canmot_codigo' => $request->codigo,
            'canmot_desc' => $request->descricao,    
        ];
        
        ParametrosSistemaCanMotivos::create($dados);
        
        return redirect(route('home.parMotCan'))->with('success', 'Motivo de Cancelamento cadastrado com sucesso!');
    }

    //Altera o motivo
    public function update(Request $request){

        $atualizausuario = DB::table('parametros_sistema_can_motivos')
        ->where('canmot_codigo', $request->codigo)
        ->update(['canmot_desc' => $request->descricao]);
    
        return redirect(route('home.parMotCan'))->with('success', 'Motivo de Cancelamento atualizado com sucesso!');
    }

    //Exclui os dados do motivo
    public function destroy(ParametrosSistemaCanMotivos $motivo, $origem){

        $motivo->delete();

        if($origem == "ajax"){

            return response()->json(['success' => true],200);

        }else{
        
            return redirect(route('home.parMotCan'))->with('success', 'Motivo de Cancelamento excluído com sucesso!');
        }
    }

    //Redireciona a home depois da exclusão do registro via ajax
    public function homeAjax()
    {  
        return redirect(route('home.parMotCan'))->with('success', 'Motivo de Cancelamento excluído com sucesso!');
    }
}
