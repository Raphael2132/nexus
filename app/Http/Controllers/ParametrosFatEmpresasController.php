<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use App\Models\ParametrosFatEmpresas;
use App\Http\Helpers\Helper;

class ParametrosFatEmpresasController extends Controller
{
    protected $parEmpresa;
    
    public function __construct(ParametrosFatEmpresas $parEmpresa)
    {
        $this->parEmpresa = $parEmpresa;
    }

    //Redireciona a app para a edição dos parametros da empresa
    public function editar($empresa)
    {
        $parametrosEmp = $this->parEmpresa->where('parfat_emp', $empresa)->get();
        return view('/parametros/faturamento/formularioParametrosFatEmpresa', ['parametrosEmp' => $parametrosEmp]);
    }

    //Realiza a atualização dos parametros da empresa
    public function update(Request $request){

        $atualizausuario = DB::table('parametros_fat_empresas')
        ->where('parfat_emp', $request->empresa)
        ->update(['parfat_sim' => $request->optSimples]);
        
        return redirect(route('parametrosFatEmp.editarCadastro', ['empresa' => $request->empresa]))->with('success', 'Parâmetros Gerais de Faturamento atualizado com sucesso!');
    }
}
