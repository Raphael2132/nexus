<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CadastroEmpresaEndereco;
use stdClass;

class CadastroEmpresaEnderecoController extends Controller
{
    protected $endereco;
    
    public function __construct(CadastroEmpresaEndereco $endereco)
    {
        $this->endereco = $endereco;
    }

    //Insere o endereço da empresa
    public function inserir(Request $request){

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
            'endereco_pais' => $request->pais,
        ];

        $novoEndereco = CadastroEmpresaEndereco::create($dados);

        return redirect(route('empresa.editarCadastro', ['dadosEmpresa' => $request->empresa_codigo]))->with('success', 'Endereço cadastrado com sucesso!');
    }

    //Deleta o endereço da empresa
    public function destroy(CadastroEmpresaEndereco $endereco){

        $codigo = $endereco->endereco_empresa_codigo;
        $principal = $endereco->endereco_principal;

        $endereco->delete();

        //Se o endereço deletado era o principal realiza verificações
        if($principal == 'S'){

            //Verifica se existem outros endereços cadastrados para o cliente
            $cnt = DB::table('cadastro_empresa_enderecos')->where('endereco_empresa_codigo', '=', $codigo)->count();

            //Se tiver outros endereços pega a menor sequencia e seta como principal
            if($cnt > 0){
                $min_seq = DB::table('cadastro_empresa_enderecos')->where('endereco_empresa_codigo', '=', $codigo)->min('endereco_seq');
                
                DB::table('cadastro_empresa_enderecos')->where('endereco_empresa_codigo', '=', $codigo)->where('endereco_seq',$min_seq)->update(array(
                    'endereco_principal'=>'S',
                ));
            }
        }
        
        return redirect(route('empresa.editarCadastro', ['dadosEmpresa' => $codigo]))->with('success', 'Endereço excluido com sucesso!');
    }

    //Torna o endereço o principal da empresa
    public function principal(Request $request, $endereco, $empresa_cod){

        DB::table('cadastro_empresa_enderecos')->where('endereco_id',$endereco)->update(array(
            'endereco_principal'=>'S',
        ));
        
        DB::table('cadastro_empresa_enderecos')->where('endereco_empresa_codigo',$empresa_cod)->where('endereco_id','<>',$endereco)->update(array(
            'endereco_principal'=>'N',
        ));
        
        return redirect(route('empresa.editarCadastro', ['dadosEmpresa' => $empresa_cod]))->with('success', 'Endereço principal alterado com sucesso!');
    }
}
