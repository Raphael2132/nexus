<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CadastroPrestadoresEndereco;
use stdClass;
use App\Http\Helpers\Helper;

class CadastroPrestadoresEnderecoController extends Controller
{
    protected $endereco;
    
    public function __construct(CadastroPrestadoresEndereco $endereco)
    {
        $this->endereco = $endereco;
    }

    //Insere o endereço da empresa
    public function inserir(Request $request, $empresa){

        //busca a maior sequencia de endereço do prestador
        $seq=DB::select("SELECT coalesce(max(endereco_seq),0) as sequencia from cadastro_prestadores_enderecos where endereco_prestador_codigo = '$request->prestador_codigo'")[0]->sequencia+1;

        //Verifica se existe outro endereço cadastrado para o prestador para ver se será o princpal ou não
        $principal=DB::select("SELECT count(*) as principal from cadastro_prestadores_enderecos where endereco_prestador_codigo = '$request->prestador_codigo'")[0]->principal;

        if($principal == 0){
            $end_pri = "S";
        }else{
            $end_pri = "N";
        }

        //Verifica se o cep foi informado corretamente
        if(!empty($request->cep)){
            $cep = Helper::limpaCEP($request->cep);

            if(strlen($cep) < 8){
                return redirect()->back()->with('error', 'Formatro do CEP informado é inválido!');
            }
        }else{
            $cep = null;
        }

        $dados = [
            'endereco_seq' => $seq,
            'endereco_prestador_codigo' => $request->prestador_codigo,
            'endereco_principal' => $end_pri,
            'endereco_cep' => $cep,
            'endereco_logradouro' => $request->logradouro,
            'endereco_numero' => $request->numero,
            'endereco_complemento' => $request->complemento,
            'endereco_bairro' => $request->bairro,
            'endereco_cidade' => $request->cidade,
            'endereco_uf' => $request->uf,
            'endereco_pais' => $request->pais,
            'endereco_ibge_cod_mun' => $request->ibgeCodMun,
        ];

        $novoEndereco = CadastroPrestadoresEndereco::create($dados);

        return redirect(route('prestador.editarCadastro', ['dadosPrestador' => $request->prestador_codigo, 'empresa' => $empresa]))->with('success', 'Endereço cadastrado com sucesso!');
    }

    //Deleta o endereço do prestador
    public function destroy(CadastroPrestadoresEndereco $endereco, $empresa){

        $codigo = $endereco->endereco_prestador_codigo;
        $principal = $endereco->endereco_principal;

        $endereco->delete();

        //Se o endereço deletado era o principal realiza verificações
        if($principal == 'S'){

            //Verifica se existem outros endereços cadastrados para o prestador
            $cnt = DB::table('cadastro_prestadores_enderecos')->where('endereco_prestador_codigo', '=', $codigo)->count();

            //Se tiver outros endereços pega a menor sequencia e seta como principal
            if($cnt > 0){
                $min_seq = DB::table('cadastro_prestadores_enderecos')->where('endereco_prestador_codigo', '=', $codigo)->min('endereco_seq');
                
                DB::table('cadastro_prestadores_enderecos')->where('endereco_prestador_codigo', '=', $codigo)->where('endereco_seq',$min_seq)->update(array(
                    'endereco_principal'=>'S',
                ));
            }
        }
        
        return redirect(route('prestador.editarCadastro', ['dadosPrestador' => $codigo, 'empresa' => $empresa]))->with('success', 'Endereço excluido com sucesso!');
    }

    //Torna o endereço o principal do prestador
    public function principal(Request $request, $endereco, $prestador_cod, $empresa){

        DB::table('cadastro_prestadores_enderecos')->where('endereco_id',$endereco)->update(array(
            'endereco_principal'=>'S',
        ));
        
        DB::table('cadastro_prestadores_enderecos')->where('endereco_prestador_codigo',$prestador_cod)->where('endereco_id','<>',$endereco)->update(array(
            'endereco_principal'=>'N',
        ));
        
        return redirect(route('prestador.editarCadastro', ['dadosPrestador' => $prestador_cod, 'empresa' => $empresa]))->with('success', 'Endereço principal alterado com sucesso!');
    }
}
