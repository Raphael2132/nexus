<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ParametrosFatNfsProvedores;
use stdClass;

class ParametrosFatNfsProvedoresController extends Controller
{
    protected $provedor;
    
    public function __construct(ParametrosFatNfsProvedores $provedor)
    {
        $this->provedor = $provedor;
    }

    //Redireciona a app para a parametrização dos provedores da NFS-e
    public function provedor()
    {    
        $provedores  = $this->provedor->all();
        
        return view('/parametros/faturamento/nfs/parametrosNfsProvedor', ['provedores'=>$provedores]);
    }

    //Insere provedor
    public function inserir(Request $request){

        //Verifica se já existe provedor cadastrado com o mesmo nome
        $provedores = DB::table('parametros_fat_nfs_provedores')->selectRaw('provedor_desc')->get();

        foreach($provedores as $provedor){
            if($request->descricao == $provedor->provedor_desc){
                return redirect()->back()->with('error', 'Já existe provedor cadstrado com o nome '.$request->descricao.'!');
            }
        }

        $dados = [
            'provedor_desc' => $request->descricao,    
        ];
        
        $novoProvedor = ParametrosFatNfsProvedores::create($dados);

        $provedores = $this->provedor->get();
        
        return redirect(route('parametrosNfsProvedor', ['provedores' => $provedores]))->with('success', 'Provedor cadastrado com sucesso!');
    }
}
