<?php

namespace App\Http\Controllers\parametros\faturamento\Nfs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Parametros\Faturamento\Nfs\ParametrosFatNfsProvedores;

class ParametrosFatNfsProvedoresController extends Controller
{
    protected $provedor;
    
    public function __construct(ParametrosFatNfsProvedores $provedor)
    {
        $this->provedor = $provedor;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP Principal de Consulta e Cadastro dos Provedores
    |----------------------------------------------------------------------------------------------------
    */
    public function index()
    {
        $provedores  = $this->provedor->all();
        
        return view('/parametros/faturamento/nfs/parametrosNfsProvedor', ['provedores'=>$provedores]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a Inclusão de Novo Provedor
    |----------------------------------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
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

        //Verifica se já existe provedor cadastrado com o mesmo nome
        $cod_prov = DB::table('parametros_fat_nfs_provedores')->where('provedor_codigo', $request->codigo)->count();

        if($cod_prov > 0){
            return redirect()->back()->with('error', 'Já existe provedor cadstrado com o código '.$request->codigo.'!');
        }

        //Busca o codigo do ibge da cidade
        $ibge_cod = DB::table('ibge_municipios')->where('ibge_mun_nome', $request->cidade)->where('ibge_mun_uf_codigo', $uf_ibge[0]->ibge_codigo)->get();

        $dados = [
            'provedor_codigo' => $request->codigo, 
            'provedor_desc' => $request->cidade, 
            'provedor_ibge' => $ibge_cod[0]->ibge_mun_codigo,
            'provedor_uf' => $request->uf   
        ];
        
        ParametrosFatNfsProvedores::create($dados);

        return redirect(route('provedorNFSe.index'))->with('success', 'Provedor cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
