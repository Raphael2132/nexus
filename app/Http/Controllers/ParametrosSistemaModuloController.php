<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ParametrosSistemaModulo;
use stdClass;

class ParametrosSistemaModuloController extends Controller
{
    public function __construct(ParametrosSistemaModulo $modulo)
    {
        $this->modulo = $modulo;
    }

    //Redireciona a app para a edição da parametrização da emissão da NFS-e
    public function editar($dadosModulo)
    {
        $resultadoModulo = $this->modulo->where('modulo_empresa_codigo','=',$dadosModulo)->get();

        return view('/parametros/sistema/editarParametrosSistemaModulos',['dadosModulo'=>$resultadoModulo]);
    }

    //Atualiza os dados da emissão da NFS-e e redireciona para a consulta
    public function update(Request $request, $empresa){

        $atualizaEmi = DB::table('parametros_sistema_modulos')
            ->where('modulo_empresa_codigo', $empresa)
            ->update(['modulo_emissao_nfs' => $request->emiNfs,
            'modulo_emissao_nfs_simp' => $request->emiNfsSimp,
            'modulo_servico' => $request->modSrv,
            'modulo_emissao_rps' => $request->emiRps]); 
        
        return redirect(route('home.parSisModulo'))->with('success', 'Dados do Módulos do Sistema atualizado com sucesso!');
    }
}
