<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ParametrosFatNfsConexoes;
use stdClass;

class ParametrosFatNfsConexoesController extends Controller
{
    protected $conexao;
    
    public function __construct(ParametrosFatNfsConexoes $conexao)
    {
        $this->conexao = $conexao;
    }

    //Redireciona a app para a consulta de parametrização de conexões da NFS-e
    public function conexao()
    {    
        $conexoes  = $this->conexao->all();
        
        return view('/parametros/faturamento/nfs/parametrosNfsConexao', ['conexoes'=>$conexoes]);
    }

    //Redireciona a app para a manutenção parametrização de conexões da NFS-e
    public function editar($dadosConexao, $appOrigem)
    {
        $resultadoConexao = $this->conexao->where('conexao_empresa','=',$dadosConexao)->get();

        return view('/parametros/faturamento/nfs/editarParametrosNfsConexao',['dadosConexao'=>$resultadoConexao, 'appOrigem'=>$appOrigem]);
    }

    //Atualiza os dados da conexão e redireciona a app para consulta de conexões da NFS-e
    public function update(Request $request, $empresa){

        $atualizaCliente = DB::table('parametros_fat_nfs_conexoes')
            ->where('conexao_empresa', $empresa)
            ->update(['conexao_usuario' => $request->usuario,
            'conexao_senha' => $request->senha,
            'conexao_token' => $request->token,
            'conexao_wsdl' => $request->wsdl,
            'conexao_ambiente' => $request->ambiente]);   
        
        return redirect(route('parametrosNfsConexao', ['dadosConexao' => $empresa]))->with('success', 'Dados da conexão de NFS-e atualizados com sucesso!');
    }
}
