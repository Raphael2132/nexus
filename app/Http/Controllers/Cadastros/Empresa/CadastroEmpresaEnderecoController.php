<?php

namespace App\Http\Controllers\Cadastros\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cadastros\Empresa\CadastroEmpresaEndereco;

class CadastroEmpresaEnderecoController extends Controller
{
    protected $endereco;
    
    public function __construct(CadastroEmpresaEndereco $endereco)
    {
        $this->endereco = $endereco;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    | Executa a Inclusão do Novo Endereço da Empresa
    |----------------------------------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        //busca a maior sequencia de endereço da empresa
        $seq=DB::select("SELECT coalesce(max(endereco_seq),0) as sequencia from cadastro_empresa_enderecos where endereco_empresa_codigo = '$request->empresa_codigo'")[0]->sequencia+1;

        //Verifica se existe outro endereço cadastrado para a empresa para ver se será o princpal ou não
        $principal=DB::select("SELECT count(*) as principal from cadastro_empresa_enderecos where endereco_empresa_codigo = '$request->empresa_codigo'")[0]->principal;

        if($principal == 0){
            $end_pri = "S";
        }else{
            $end_pri = "N";
        }

        //Verifica se o cep foi informado corretamente
        if(!empty($request->cep)){
            $replace = array("_", "-", " ");
            $cep = str_replace($replace,"",$request->cep);

            if(strlen($cep) < 8){
                return redirect()->back()->with('error', 'Formatro do CEP informado é inválido!');
            }
        }else{
            $cep = null;
        }

        $dadosPais = DB::table('ibge_paises')->where('ibge_pais_codigo', $request->pais)->first();

        $dados = [
            'endereco_seq' => $seq,
            'endereco_empresa_codigo' => $request->empresa_codigo,
            'endereco_principal' => $end_pri,
            'endereco_cep' => $cep,
            'endereco_logradouro' => $request->logradouro,
            'endereco_numero' => $request->numero,
            'endereco_complemento' => $request->complemento,
            'endereco_bairro' => $request->bairro,
            'endereco_cidade' => $request->cidade,
            'endereco_uf' => $request->uf,
            'endereco_pais' => $dadosPais->ibge_pais_nome,
            'endereco_ibge_cod_mun' => $request->ibgeCodMun,
            'endereco_ibge_cod_pais' => $request->pais
        ];

        CadastroEmpresaEndereco::create($dados);

        return redirect(route('cadastroEmpresa.edit', ['cadastroEmpresa' => $request->empresa_codigo]))->with('success', 'Endereço cadastrado com sucesso!');
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

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza o Update dos dados de Endereço do Empresa
    |----------------------------------------------------------------------------------------------------
    */
    public function update(Request $request, CadastroEmpresaEndereco $empresaEndereco)
    {
        $empresaEndereco->update(['endereco_principal' => 'S']);
        
        DB::table('cadastro_empresa_enderecos')
        ->where('endereco_empresa_codigo',$empresaEndereco->endereco_empresa_codigo)
        ->where('endereco_id','<>',$empresaEndereco->endereco_id)
        ->update([
            'endereco_principal'=>'N'
        ]);
        
        return redirect(route('cadastroEmpresa.edit', ['cadastroEmpresa' => $empresaEndereco->endereco_empresa_codigo]))->with('success', 'Endereço principal alterado com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza a Exclusão do Endereço do Usuário
    |----------------------------------------------------------------------------------------------------
    */
    public function destroy(CadastroEmpresaEndereco $empresaEndereco, Request $request)
    {
        $codigo = $empresaEndereco->endereco_empresa_codigo;
        $principal = $empresaEndereco->endereco_principal;

        $empresaEndereco->delete();

        //Se o endereço deletado era o principal realiza verificações
        if($principal == 'S'){

            //Verifica se existem outros endereços cadastrados para o cliente
            $cnt = DB::table('cadastro_empresa_enderecos')->where('endereco_empresa_codigo', $codigo)->count();

            //Se tiver outros endereços pega a menor sequencia e seta como principal
            if($cnt > 0){
                $min_seq = DB::table('cadastro_empresa_enderecos')->where('endereco_empresa_codigo', $codigo)->min('endereco_seq');
                
                DB::table('cadastro_empresa_enderecos')->where('endereco_empresa_codigo', $codigo)->where('endereco_seq',$min_seq)->update([
                    'endereco_principal'=>'S',
                ]);
            }
        }
        
        return redirect(route('cadastroEmpresa.edit', ['cadastroEmpresa' => $codigo]))->with('success', 'Endereço excluido com sucesso!');
    }
}
