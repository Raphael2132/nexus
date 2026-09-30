<?php

namespace App\Http\Controllers\Parametros\Servico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Parametros\Servico\ParametrosSrvEmpresas;
use App\Http\Helpers\Helper;

class ParametrosSrvEmpresasController extends Controller
{
    protected $parEmpresa;
    
    public function __construct(ParametrosSrvEmpresas $parEmpresa)
    {
        $this->parEmpresa = $parEmpresa;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP Home de Parâmetros Gerais de Serviço da Empresa
    |----------------------------------------------------------------------------------------------------
    */
    public function index()
    {
        $parametros = $this->parEmpresa->reorder('parsrv_emp', 'asc')->get();

        return view('/parametros/servico/homeParametrosServicoEmpresa', ['dataParSrvEmp' => $parametros]);
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
    | Executa a APP de Manutenção dos Parâmetros Gerais de Serviço da Empresa
    |----------------------------------------------------------------------------------------------------
    */
    public function edit($empresa)
    {
        $parametrosEmp = $this->parEmpresa->where('parsrv_emp', $empresa)->first();

        return view('/parametros/servico/formularioParametrosServicoEmpresa', ['parametrosEmp' => $parametrosEmp]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza o Update dos dados Gerais de Serviço Selecionado
    |----------------------------------------------------------------------------------------------------
    */
    public function update(Request $request, ParametrosSrvEmpresas $geralServico)
    {
        $aliqISS = Helper::limpaPorcentagem($request->aliqISS);

        $geralServico->update([
            'parsrv_alq_iss' => $aliqISS,
            'parsrv_cfop' => $request->srvCFOP,
            'parsrv_grp_srv' => $request->grupoSrv,
            'parsrv_cod_srv' => $request->codigoSrv,
            'parsrv_exg_iss' => $request->exiISS,
            'parsrv_iss_ret' => $request->issRet,
            'parsrv_env_rps_email' => $request->envRpsEmail,
            'parsrv_enc_os_email' => $request->encOsEmail
        ]);
        
        return redirect(route('geralServico.edit', ['geralServico' => $request->empresa]))->with('success', 'Parâmetros Gerais de Serviços atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
