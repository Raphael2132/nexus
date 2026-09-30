<?php

namespace App\Http\Controllers\Parametros\Sistema;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Http\Helpers\HelperDataSelect;
use App\Models\Parametros\Sistema\ParametrosSisModulo;

class ParametrosSisModuloController extends Controller
{
    protected $modulo;

    public function __construct(ParametrosSisModulo $modulo)
    {
        $this->modulo = $modulo;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP Home de Módulos do Sistema
    |----------------------------------------------------------------------------------------------------
    */
    public function index()
    {
        $modulos  = $this->modulo->all();

        return view('/parametros/sistema/homeParametrosSistemaModulos', ['modulos'=>$modulos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP de Manutenção do Módulo da Empresa
    |----------------------------------------------------------------------------------------------------
    */
    public function edit($dadosModulo)
    {
        $resultadoModulo = $this->modulo->where('modulo_empresa_codigo','=',$dadosModulo)->get();

        return view('/parametros/sistema/formularioParametrosSistemaModulos',['dadosModulo'=>$resultadoModulo]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza o Update dos dados do Módulo da Empresa Selecionada
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

                $dadosEmp = HelperDataSelect::buscaDadosEmpresa($empresa);

                return redirect()->back()->with('info', 'A nova quantidade de Usuários ativos permitidos para a empresa '.$empresa.' - '.$dadosEmp->empresa_nome.' não é suportada! </br></br>Existem mais usuarios ativos do que a nova quantidade, entre em contato com a Empresa para realizar a desativação de usuários sobresalentes.');
            }
        }

        $dataValidade = Helper::limpaData($request->dataValidade);

        DB::table('parametros_sis_modulos')
        ->where('modulo_empresa_codigo', $empresa)
        ->update(['modulo_emissao_nfs' => $request->emiNfs,
            'modulo_emissao_nfs_simp' => $request->emiNfsSimp,
            'modulo_servico' => $request->modSrv,
            'modulo_emissao_rps' => $request->emiRps,
            'modulo_qtd_usuarios' => $request->qtdUsu,
            'modulo_dt_validade' => $dataValidade,
            'modulo_controle_producao' => $request->contProd,
            'modulo_plano' => $request->plano,
            'modulo_qtd_usuarios_ext' => $request->qtdUsuEx
        ]); 
        
        return redirect(route('modulosSistema.edit',['modulosSistema' => $empresa]))->with('success', 'Dados do Módulo do Sistema atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
