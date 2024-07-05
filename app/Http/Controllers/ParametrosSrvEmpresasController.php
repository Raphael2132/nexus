<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use App\Models\ParametrosSrvEmpresas;
use App\Http\Helpers\Helper;

class ParametrosSrvEmpresasController extends Controller
{
    protected $parEmpresa;
    
    public function __construct(ParametrosSrvEmpresas $parEmpresa)
    {
        $this->parEmpresa = $parEmpresa;
    }

    //Redireciona a app para a edição dos parametros da empresa
    public function editar($empresa)
    {
        $parametrosEmp = $this->parEmpresa->where('parsrv_emp', $empresa)->get();
        return view('/parametros/servico/formularioParametrosServicoEmpresa', ['parametrosEmp' => $parametrosEmp]);
    }

    //Realiza a atualização dos parametros da empresa
    public function update(Request $request){

        $aliqISS = Helper::limpaPorcentagem($request->aliqISS);
        $hrIni = Helper::limpaHoraMinuto($request->horaIniEx);
        $hrFin = Helper::limpaHoraMinuto($request->horaFinEx);

        $atualizausuario = DB::table('parametros_srv_empresas')
        ->where('parsrv_emp', $request->empresa)
        ->update(['parsrv_alq_iss' => $aliqISS,
            'parsrv_cfop' => $request->srvCFOP,
            'parsrv_hr_ini_ex' => $hrIni,
            'parsrv_hr_fin_ex' => $hrFin]);
        
        return redirect(route('parametrosSrvEmp.editarCadastro', ['empresa' => $request->empresa]))->with('success', 'Parâmetros Gerais de Serviços atualizado com sucesso!');
    }
}
