<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CadastroClienteEndereco;
use stdClass;

class CadastroClienteEnderecoController extends Controller
{
    protected $endereco;
    
    public function __construct(CadastroClienteEndereco $endereco)
    {
        $this->endereco = $endereco;
    }

    public function inserir(Request $request, $tipo){

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

        $novoEndereco = CadastroClienteEndereco::create($dados);

        return redirect(route('cliente.editarCadastro', ['dadosCliente' => $novoEndereco->endereco_cliente_codigo, 'tipo' => $tipo]))->with('success', 'Endereço cadastrado com sucesso!');
    }

    public function destroy(CadastroClienteEndereco $endereco, $tipo){

        $codigo = $endereco->endereco_cliente_codigo;
        $principal = $endereco->endereco_principal;

        $endereco->delete();

        //Se o endereço deletado era o principal realiza verificações
        if($principal == 'S'){

            //Verifica se existem outros endereços cadastrados para o cliente
            $cnt = DB::table('cadastro_cliente_enderecos')->where('endereco_cliente_codigo', '=', $codigo)->count();

            //Se tiver outros endereços pega a menor sequencia e seta como principal
            if($cnt > 0){
                $min_seq = DB::table('cadastro_cliente_enderecos')->where('endereco_cliente_codigo', '=', $codigo)->min('endereco_seq');
                
                DB::table('cadastro_cliente_enderecos')->where('endereco_cliente_codigo', '=', $codigo)->where('endereco_seq',$min_seq)->update(array(
                    'endereco_principal'=>'S',
                ));
            }
        }
        
        return redirect(route('cliente.editarCadastro', ['dadosCliente' => $codigo, 'tipo' => $tipo]))->with('success', 'Endereço excluido com sucesso!');
    }

    public function principal(Request $request, $endereco, $cliente_cod, $tipo){

        DB::table('cadastro_cliente_enderecos')->where('endereco_id',$endereco)->update(array(
            'endereco_principal'=>'S',
        ));
        
        DB::table('cadastro_cliente_enderecos')->where('endereco_cliente_codigo',$cliente_cod)->where('endereco_id','<>',$endereco)->update(array(
            'endereco_principal'=>'N',
        ));
        
        return redirect(route('cliente.editarCadastro', ['dadosCliente' => $cliente_cod, 'tipo' => $tipo]))->with('success', 'Endereço principal alterado com sucesso!');
    }
}
