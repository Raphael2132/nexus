<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ParametrosFatNfs;
use stdClass;

class ParametrosFatNfsController extends Controller
{
    protected $emissao;
    
    public function __construct(ParametrosFatNfs $emissao)
    {
        $this->emissao = $emissao;
    }

    //Redireciona a app para a consulta da parametrização da emissão da NFS-e
    public function emissao()
    {    
        $emissoes  = $this->emissao->all();
        
        return view('/parametros/faturamento/nfs/parametrosNfsEmissao', ['emissoes'=>$emissoes]);
    }

    //Redireciona a app para a edição da parametrização da emissão da NFS-e
    public function editar($dadosEmissao, $appOrigem)
    {
        $resultadoEmissao = $this->emissao->where('parnfs_empresa','=',$dadosEmissao)->get();

        return view('/parametros/faturamento/nfs/editarParametrosNfsEmissao',['dadosEmissao'=>$resultadoEmissao, 'appOrigem'=>$appOrigem]);
    }

    //Atualiza os dados da emissão da NFS-e e redireciona para a consulta
    public function update(Request $request, $empresa){

        if($request->imprimeNFS == 'S'){
            $modulos = DB::table('parametros_sistema_modulos')->where('modulo_empresa_codigo', $empresa)->first();

            if($modulos->modulo_emissao_nfs == 'N' && $modulos->modulo_emissao_nfs_simp == 'N'){
                return redirect()->back()->with('error', 'Empresa não utiliza o módulo de NFS-e!');
            }
        }

        if($request->imprimeRPS == 'S'){
            $modulos = DB::table('parametros_sistema_modulos')->where('modulo_empresa_codigo', $empresa)->first();

            if($modulos->modulo_emissao_rps == 'N'){
                return redirect()->back()->with('error', 'Empresa não utiliza o módulo de RPS!');
            }
        }

        $provedorOld=DB::select("SELECT parnfs_provedor from parametros_fat_nfs where parnfs_empresa = '$empresa'")[0]->parnfs_provedor;

        $atualizaEmi = DB::table('parametros_fat_nfs')
            ->where('parnfs_empresa', $empresa)
            ->update(['parnfs_utiliza_nfs' => $request->geraNFS,
            'parnfs_provedor' => $request->provedor,
            'parnfs_numeracao' => $request->numeroNFS,
            'parnfs_serie' => $request->serieNFS,
            'parnfs_impressao_nfs' => $request->imprimeNFS,
            'parnfs_impressao_rps' => $request->imprimeRPS]); 
            
        //Se o provedor foi trocado redefine os dados da conexão
        if($provedorOld != $request->provedor){
            $atualizaCon = DB::table('parametros_fat_nfs_conexoes')
            ->where('conexao_empresa', $empresa)
            ->update(['conexao_provedor' => $request->provedor,
            'conexao_usuario' => null,
            'conexao_senha' => null,
            'conexao_token' => null,
            'conexao_wsdl' => null,
            'conexao_ambiente' => 'H']);
        }
        
        return redirect(route('parametrosNfsEmissao', ['dadosEmissao' => $empresa]))->with('success', 'Dados da emissão de NFS-e atualizada com sucesso!');
    }
}
