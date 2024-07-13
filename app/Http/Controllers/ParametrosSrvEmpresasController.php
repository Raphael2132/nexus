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
            'parsrv_hr_fin_ex' => $hrFin,
            'parsrv_grp_srv' => $request->grupoSrv,
            'parsrv_cod_srv' => $request->codigoSrv,
            'parsrv_exg_iss' => $request->exiISS,
            'parsrv_iss_ret' => $request->issRet]);
        
        return redirect(route('parametrosSrvEmp.editarCadastro', ['empresa' => $request->empresa]))->with('success', 'Parâmetros Gerais de Serviços atualizado com sucesso!');
    }

    //Redireciona a home depois da exclusão do registro via ajax
    public function carregaCodSrvAjax($codigo)
    {  
        $servicos = DB::table('parametros_sistema_servicos')->select('servico_codigo', 'servico_desc')->where('servico_grupo', $codigo)->orderby('servico_codigo', 'asc')->get();
       
        foreach($servicos as $servico) {
            
            $servicos_ajax[] = array(
                'id'	=> $servico->servico_codigo,
                'cod_servico' => $servico->servico_codigo.' - '.$servico->servico_desc,
            );
        }  

        return response()->json(['success' => true, 'servicos_ajax' => $servicos_ajax]);
    }
}
