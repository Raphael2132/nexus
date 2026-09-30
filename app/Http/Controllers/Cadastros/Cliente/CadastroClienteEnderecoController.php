<?php

namespace App\Http\Controllers\Cadastros\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cadastros\Cliente\CadastroClienteEndereco;

class CadastroClienteEnderecoController extends Controller
{
    protected $endereco;
    
    public function __construct(CadastroClienteEndereco $endereco)
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
    | Executa a Inclusão do Novo Endereço do Cliente
    |----------------------------------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $seq=DB::select("SELECT coalesce(max(endereco_seq),0) as sequencia from cadastro_cliente_enderecos where endereco_cliente_codigo = '$request->cliente_codigo'")[0]->sequencia+1;

        $principal=DB::select("SELECT count(*) as principal from cadastro_cliente_enderecos where endereco_cliente_codigo = '$request->cliente_codigo'")[0]->principal;

        if($principal == 0){
            $end_pri = "S";
        }else{
            $end_pri = "N";
        }

        if(!empty($request->cep)){
            $replace = array("-", " ");
            $cep = str_replace($replace,"",$request->cep);
        }else{
            $cep = null;
        }

        $dadosPais = DB::table('ibge_paises')->where('ibge_pais_codigo', $request->pais)->first();

        $dados = [
            'endereco_seq' => $seq,
            'endereco_cliente_codigo' => $request->cliente_codigo,
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

        CadastroClienteEndereco::create($dados);

        return redirect(route('cadastroCliente.edit', ['cadastroCliente' => $request->cliente_codigo, 'tipo' => $request->tipo]))->with('success', 'Endereço cadastrado com sucesso!');
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
    | Realiza o Update dos dados de Endereço do Cliente
    |----------------------------------------------------------------------------------------------------
    */
    public function update(Request $request, CadastroClienteEndereco $clienteEndereco)
    {
        $tipo = $request->tipo;

        $clienteEndereco->update(['endereco_principal' => 'S']);
        
        DB::table('cadastro_cliente_enderecos')
        ->where('endereco_cliente_codigo',$clienteEndereco->endereco_cliente_codigo)
        ->where('endereco_id','<>',$clienteEndereco->endereco_id)
        ->update([
            'endereco_principal' => 'N',
        ]);
        
        return redirect(route('cadastroCliente.edit', ['cadastroCliente' => $clienteEndereco->endereco_cliente_codigo, 'tipo' => $tipo]))->with('success', 'Endereço principal alterado com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza a Exclusão do Endereço do Cliente
    |----------------------------------------------------------------------------------------------------
    */
    public function destroy(CadastroClienteEndereco $clienteEndereco, Request $request){

        $tipo = $request->tipo;
        $codigo = $clienteEndereco->endereco_cliente_codigo;
        $principal = $clienteEndereco->endereco_principal;

        $clienteEndereco->delete();

        //Se o endereço deletado era o principal realiza verificações
        if($principal == 'S'){

            //Verifica se existem outros endereços cadastrados para o cliente
            $cnt = DB::table('cadastro_cliente_enderecos')->where('endereco_cliente_codigo', $codigo)->count();

            //Se tiver outros endereços pega a menor sequencia e seta como principal
            if($cnt > 0){
                $min_seq = DB::table('cadastro_cliente_enderecos')->where('endereco_cliente_codigo', $codigo)->min('endereco_seq');
                
                DB::table('cadastro_cliente_enderecos')->where('endereco_cliente_codigo', $codigo)->where('endereco_seq',$min_seq)->update(['endereco_principal' => 'S']);
            }
        }
        
        return redirect(route('cadastroCliente.edit', ['cadastroCliente' => $codigo, 'tipo' => $tipo]))->with('success', 'Endereço excluído com sucesso!');
    }
}
