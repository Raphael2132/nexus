<?php

namespace App\Http\Controllers\parametros\faturamento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Parametros\Faturamento\ParametrosFatEmpresas;

class ParametrosFatEmpresasController extends Controller
{

    protected $parEmpresa;
    
    public function __construct(ParametrosFatEmpresas $parEmpresa)
    {
        $this->parEmpresa = $parEmpresa;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP Home de Parametros Gerais de Faturamento
    |----------------------------------------------------------------------------------------------------
    */
    public function index()
    {
        $parametros = $this->parEmpresa->reorder('parfat_emp', 'asc')->get();

        return view('/parametros/faturamento/homeParametrosFatEmpresa', ['dataParFatEmp' => $parametros]);
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
    | Executa a APP de Manutenção dos Parametros Gerais de Faturamento da Empresa
    |----------------------------------------------------------------------------------------------------
    */
    public function edit($empresa)
    {
        $parametrosEmp = $this->parEmpresa->where('parfat_emp', $empresa)->first();

        return view('/parametros/faturamento/formularioParametrosFatEmpresa', ['parametrosEmp' => $parametrosEmp]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza o Update dos dados dos Parâmetros Gerais da Empresa Selecionada
    |----------------------------------------------------------------------------------------------------
    */
    public function update(Request $request, ParametrosFatEmpresas $faturamentoGeral)
    {
        $faturamentoGeral->parfat_sim = $request->optSimples;
        $faturamentoGeral->parfat_env_nfs_email = $request->envNfse;

        $faturamentoGeral->save();
        
        return redirect(route('faturamentoGeral.edit', ['faturamentoGeral' => $faturamentoGeral->parfat_emp]))->with('success', 'Parâmetros atualizados com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
