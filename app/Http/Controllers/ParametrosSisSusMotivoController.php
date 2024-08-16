<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ParametrosSisSusMotivo;
use stdClass;

class ParametrosSisSusMotivoController extends Controller
{
    protected $motivo;
    
    public function __construct(ParametrosSisSusMotivo $motivo)
    {
        $this->motivo = $motivo;
    }

    //Redireciona a app para o formulario
    public function cadastroMotSus($acao, $dadosMotSus)
    {
        if(!empty(trim($dadosMotSus))){
            $dadosMotSus = DB::table('parametros_sis_sus_motivos')->where('susmot_codigo', $dadosMotSus)->get();
        }else{
            $dadosMotSus = ' ';
        }

        return view('/parametros/sistema/formularioParametrosSisMotSuspensao',['acao'=>$acao, 'dadosMotSus' => $dadosMotSus]);
    }

    //Insere o motivo
    public function insert(Request $request){

        //Verifica se já existe motivo cadastrado com o mesmo codigo
        $motivos = DB::table('parametros_sis_sus_motivos')->where('susmot_codigo', $request->codigo)->count();

        if($motivos > 0){
            return redirect()->back()->with('error', 'Já existe motivo cadstrado com o código '.$request->codigo.'!');
        }

        $dados = [
            'susmot_codigo' => $request->codigo,
            'susmot_desc' => $request->descricao,    
        ];
        
        ParametrosSisSusMotivo::create($dados);
        
        return redirect(route('home.parMotSus'))->with('success', 'Motivo de Suspensão cadastrado com sucesso!');
    }

    //Altera o motivo
    public function update(Request $request){

        $atualizausuario = DB::table('parametros_sis_sus_motivos')
        ->where('susmot_codigo', $request->codigo)
        ->update(['susmot_desc' => $request->descricao]);
    
        return redirect(route('home.parMotSus'))->with('success', 'Motivo de Suspensão atualizado com sucesso!');
    }

    //Exclui os dados do motivo
    public function destroy(ParametrosSisSusMotivo $motivo, $origem){

        $motivo->delete();

        if($origem == "ajax"){

            return response()->json(['success' => true],200);

        }else{
        
            return redirect(route('home.parMotSus'))->with('success', 'Motivo de Suspensão excluído com sucesso!');
        }
    }

    //Redireciona a home depois da exclusão do registro via ajax
    public function homeAjax()
    {  
        return redirect(route('home.parMotSus'))->with('success', 'Motivo de Suspensão excluído com sucesso!');
    }
}
