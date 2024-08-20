<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use App\Models\ParametrosGerEmpresa;
use App\Http\Helpers\Helper;

class ParametrosGerEmpresaController extends Controller
{
    protected $parEmpresa;
    
    public function __construct(ParametrosGerEmpresa $parEmpresa)
    {
        $this->parEmpresa = $parEmpresa;
    }

    //Redireciona a app para a edição dos parametros da empresa
    public function editar($empresa)
    {
        $parametrosEmp = $this->parEmpresa->where('parger_emp', $empresa)->get();
        return view('/parametros/gerencial/formularioParametrosGerencialEmpresa', ['parametrosEmp' => $parametrosEmp]);
    }

    //Realiza a atualização dos parametros da empresa
    public function update(Request $request, $empresa){

        $hrIni = Helper::limpaHoraMinuto($request->horaIniFun);
        $hrFin = Helper::limpaHoraMinuto($request->horaFinFun);

        //Verifica se o horario final do Funcionamento é menor que o horario inicial
        if($hrIni > $hrFin){
            return redirect()->back()->with('error', 'Hora Ini. Funcionamento não pode ser maior que a Hora Fin. Funcionamento!');
        }

        //Verifica se usa o intervalo de funcionamento
        if($request->usaInt == 'S'){

            $horaIniInt = Helper::limpaHoraMinuto($request->horaIniInt);
            $horaFinInt = Helper::limpaHoraMinuto($request->horaFinInt);

            //Verifica se o horario final é menor que o horario inicial
            if($horaIniInt > $horaFinInt){
                return redirect()->back()->with('error', 'Hora Ini. Intervalo não pode ser maior que a Hora Fin. Intervalo!');
            }
        }else{
            $horaIniInt = 0;
            $horaFinInt = 0;
        }

        //Verifica se usa o Sábado
        if($request->diaFuncionamento == 2 || $request->diaFuncionamento == 3){

            if($request->hrAltSab == 'S'){
                $horaIniSab = Helper::limpaHoraMinuto($request->horaIniSab);
                $horaFinSab = Helper::limpaHoraMinuto($request->horaFinSab);

                //Verifica se o horario final é menor que o horario inicial
                if($horaIniSab > $horaFinSab){
                    return redirect()->back()->with('error', 'Hora Ini. Sábado não pode ser maior que a Hora Fin. Sábado!');
                }
            }else{
                $horaIniSab = 0;
                $horaFinSab = 0;
            }

            //Verifica se usa o intervalo de funcionamento
            if($request->usaIntSab == 'S'){

                $horaIniIntSab = Helper::limpaHoraMinuto($request->horaIniIntSab);
                $horaFinIntSab = Helper::limpaHoraMinuto($request->horaFinIntSab);

                //Verifica se o horario final é menor que o horario inicial
                if($horaIniIntSab > $horaFinIntSab){
                    return redirect()->back()->with('error', 'Hora Ini. Intervalo Sábado não pode ser maior que a Hora Fin. Intervalo Sábado!');
                }
            }else{
                $horaIniIntSab = 0;
                $horaFinIntSab = 0;
            }
        }else{
            $horaIniSab = 0;
            $horaFinSab = 0;
            $horaIniIntSab = 0;
            $horaFinIntSab = 0;
        }

        //Verifica se usa o Domingo
        if($request->diaFuncionamento == 3){

            if($request->hrAltSab == 'S'){
                $horaIniDom = Helper::limpaHoraMinuto($request->horaIniDom);
                $horaFinDom = Helper::limpaHoraMinuto($request->horaFinDom);

                //Verifica se o horario final é menor que o horario inicial
                if($horaIniDom > $horaFinDom){
                    return redirect()->back()->with('error', 'Hora Ini. Domingo não pode ser maior que a Hora Fin. Domingo!');
                }
            }else{
                $horaIniDom = 0;
                $horaFinDom = 0;
            }

            //Verifica se usa o intervalo de funcionamento
            if($request->usaIntDom == 'S'){

                $horaIniIntDom = Helper::limpaHoraMinuto($request->horaIniIntDom);
                $horaFinIntDom = Helper::limpaHoraMinuto($request->horaFinIntDom);

                //Verifica se o horario final é menor que o horario inicial
                if($horaIniIntDom > $horaFinIntDom){
                    return redirect()->back()->with('error', 'Hora Ini. Intervalo Domingo não pode ser maior que a Hora Fin. Intervalo Domingo!');
                }
            }else{
                $horaIniIntDom = 0;
                $horaFinIntDom = 0;
            }
        }else{
            $horaIniDom = 0;
            $horaFinDom = 0;
            $horaIniIntDom = 0;
            $horaFinIntDom = 0;
        }

        $atualizausuario = DB::table('parametros_ger_turnos')
        ->where('partur_emp', $empresa)
        ->where('partur_cod', '1')
        ->update(['partur_dia' => $request->diaFuncionamento]);

        $atualizausuario = DB::table('parametros_ger_empresas')
        ->where('parger_emp', $empresa)
        ->update(['parger_dia_fun' => $request->diaFuncionamento,
            'parger_hr_ini_fun' => $hrIni,
            'parger_hr_fin_fun' => $hrFin,
            'parger_int_fun' => $request->usaInt,
            'parger_hr_ini_int' => $horaIniInt,
            'parger_hr_fin_int' => $horaFinInt,
            'parger_tur_srv' => $request->turSrv,
            'parger_hr_alt_sab' => $request->hrAltSab,
            'parger_hr_ini_sab' => $horaIniSab,
            'parger_hr_fin_sab' => $horaFinSab,
            'parger_int_sab' => $request->usaIntSab,
            'parger_hr_ini_int_sab' => $horaIniIntSab,
            'parger_hr_fin_int_sab' => $horaFinIntSab,
            'parger_hr_alt_dom' => $request->hrAltDom,
            'parger_hr_ini_dom' => $horaIniDom,
            'parger_hr_fin_dom' => $horaFinDom,
            'parger_int_dom' => $request->usaIntDom,
            'parger_hr_ini_int_dom' => $horaIniIntDom,
            'parger_hr_fin_int_dom' => $horaFinIntDom]);
        
        return redirect(route('parametrosGerEmp.editarCadastro', ['empresa' => $empresa]))->with('success', 'Parâmetros Gerencial da Empresa atualizada com sucesso!');
    }
}
