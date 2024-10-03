<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Helpers\Helper;
use App\Models\ParametrosSisModulo;
use stdClass;

class ParametrosSisModuloController extends Controller
{
    public function __construct(ParametrosSisModulo $modulo)
    {
        $this->modulo = $modulo;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP de Manutenção do Módulo da Empresa
    |----------------------------------------------------------------------------------------------------
    */
    public function editar($dadosModulo)
    {
        $resultadoModulo = $this->modulo->where('modulo_empresa_codigo','=',$dadosModulo)->get();

        return view('/parametros/sistema/editarParametrosSistemaModulos',['dadosModulo'=>$resultadoModulo]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Atualiza os Dados do Módulo da Empresa
    |----------------------------------------------------------------------------------------------------
    */
    public function update(Request $request, $empresa){

        //Faz a soma atual e anterior da quantidade de usuarios total para verificar se a nova quantidade de usuarios é menor
        $dadosModOld = DB::table('parametros_sis_modulos')->where('modulo_empresa_codigo', $empresa)->first();
        $qtdUsuModOld = $dadosModOld->modulo_qtd_usuarios + $dadosModOld->modulo_qtd_usuarios_ext;
        $qtdUsuModNew = $request->qtdUsuEx + $request->qtdUsu;

        //Se a quantidade nova de usuarios for menor verifica se a quantidade de usuarios ativos atual é suportada para a nova quantidade
        if($qtdUsuModOld > $qtdUsuModNew){
        
            $qtdUser = DB::table('users')->where('usuario_tipo', '<>', 'M')->where('usuario_status', 'A')->where('usuario_empresa', $empresa)->count();

            if($qtdUser > $qtdUsuModNew){

                $dadosEmp = Helper::buscaDadosEmpresa($empresa);

                return redirect()->back()->with('info', 'A nova quantidade de Usuários ativos permitidos para a empresa '.$empresa.' - '.$dadosEmp->empresa_nome.' não é suportada! </br></br>Existem mais usuarios ativos do que a nova quantidade, entre em contato com a Empresa para realizar a desativação de usuários sobresalentes.');
            }
        }

        $dataValidade = Helper::limpaData($request->dataValidade);

        $atualizaEmi = DB::table('parametros_sis_modulos')
            ->where('modulo_empresa_codigo', $empresa)
            ->update(['modulo_emissao_nfs' => $request->emiNfs,
            'modulo_emissao_nfs_simp' => $request->emiNfsSimp,
            'modulo_servico' => $request->modSrv,
            'modulo_emissao_rps' => $request->emiRps,
            'modulo_qtd_usuarios' => $request->qtdUsu,
            'modulo_dt_validade' => $dataValidade,
            'modulo_controle_producao' => $request->contProd,
            'modulo_plano' => $request->plano,
            'modulo_qtd_usuarios_ext' => $request->qtdUsuEx]); 
        
        return redirect(route('home.parSisModulo'))->with('success', 'Dados do Módulos do Sistema atualizado com sucesso!');
    }
}
