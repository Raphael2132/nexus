<?php

namespace App\Http\Controllers\Parametros\Faturamento\Fatura;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Helpers\Helper;
use App\Models\Parametros\Faturamento\Fatura\ParametrosFatFaturaBancos;

class ParametrosFatFaturaBancosController extends Controller
{
    protected $banco;
    
    public function __construct(ParametrosFatFaturaBancos $banco)
    {
        $this->banco = $banco;
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
    | Executa a Inclusão do Novo Banco para Fatura (Boleto)
    |----------------------------------------------------------------------------------------------------
    */
    public function store($empresa, $razao)
    {
        $ano = date('Y');

        $dadosRazao = DB::table('financeiro_razoes')->where('razao_empresa', $empresa)->where('razao_codigo', $razao)->where('razao_ano', $ano)->first();

        $dados = [
            'fatban_empresa' => $empresa,
            'fatban_banco' => $dadosRazao->razao_bco_cod,
            'fatban_razao' => $razao,
            'fatban_age' => $dadosRazao->razao_bco_age,
            'fatban_age_dv' => $dadosRazao->razao_bco_age_dv,
            'fatban_ncc' => $dadosRazao->razao_bco_ncc,
            'fatban_ncc_dv' => $dadosRazao->razao_bco_ncc_dv    
        ];
        
        ParametrosFatFaturaBancos::create($dados);
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
