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

        //Verifica se a cidade existe na tabela do IBGE
        $cnt_ibge = DB::table('ibge_municipios')->where('ibge_mun_nome', $request->cidade)->count();

        if($cnt_ibge == 0){
            return redirect()->back()->with('error', 'Não foi encontrado na tabela do IBGE a cidade '.$request->cidade.'!');
        }

        //Verifica se a cidade é realmente da UF
        $uf_ibge = DB::table('ibge_estados')->where('ibge_sigla', $request->uf)->get();

        $cnt_ibge_uf = DB::table('ibge_municipios')->where('ibge_mun_nome', $request->cidade)->where('ibge_mun_uf_codigo', $uf_ibge[0]->ibge_codigo)->count();

        if($cnt_ibge_uf == 0){
            return redirect()->back()->with('error', 'A Cidade informada não faz parte da UF informada!');
        }

        //Verifica se já existe provedor cadastrado com o mesmo nome
        $provedores = DB::table('parametros_fat_nfs_provedores')->select('provedor_desc')->get();

        foreach($provedores as $provedor){
            if($request->descricao == $provedor->provedor_desc){
                return redirect()->back()->with('error', 'Já existe provedor cadstrado com o nome '.$request->descricao.'!');
            }
        }

        //Busca o codigo do ibge da cidade
        $ibge_cod = DB::table('ibge_municipios')->where('ibge_mun_nome', $request->cidade)->where('ibge_mun_uf_codigo', $uf_ibge[0]->ibge_codigo)->get();

        $dados = [
            'provedor_desc' => $request->cidade, 
            'provedor_ibge' => $ibge_cod[0]->ibge_mun_codigo,
            'provedor_uf' => $request->uf   
        ];
        
        ParametrosFatNfsProvedores::create($dados);

        $provedores = $this->provedor->get();
        
        return redirect(route('parametrosNfsProvedor', ['provedores' => $provedores]))->with('success', 'Provedor cadastrado com sucesso!');
    }

    //Carrega os dados do IBGE pelo estado selecionado
    public function carregaCidAjax($uf)
    {  
        $uf = DB::table('ibge_estados')->where('ibge_sigla', $uf)->get();
        $cidades = DB::table('ibge_municipios')->select('ibge_mun_codigo', 'ibge_mun_nome')->where('ibge_mun_uf_codigo', $uf[0]->ibge_codigo)->orderBy('ibge_mun_codigo', 'asc')->get();

        if(!empty($cidades[0])){

            foreach($cidades as $cidade) {
                $cidade_ajax[] = array(
                    'ibge'	=> $cidade->ibge_mun_codigo,
                    'cidade' => $cidade->ibge_mun_nome,
                );
            }  

            return response()->json(['success' => true, 'cidade_ajax' => $cidade_ajax, 'cidade_ajax_existe' => 'S']);

        }else{

            return response()->json(['success' => true, 'cidade_ajax' => null, 'cidade_ajax_existe' => 'N']);
        }
    }
}
