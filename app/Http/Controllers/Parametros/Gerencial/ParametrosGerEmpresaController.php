<?php

namespace App\Http\Controllers\Parametros\Gerencial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Parametros\Gerencial\ParametrosGerEmpresa;
use App\Http\Helpers\Helper;

class ParametrosGerEmpresaController extends Controller
{
    protected $parEmpresa;

    public function __construct(ParametrosGerEmpresa $parEmpresa)
    {
        $this->parEmpresa = $parEmpresa;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP Home de Parâmetros Gerencial da Empresa
    |----------------------------------------------------------------------------------------------------
    */
    public function index()
    {
        $parametros = $this->parEmpresa->reorder('parger_emp', 'asc')->get();

        return view('/parametros/gerencial/homeParametrosGerencialEmpresa', ['dataParGerEmp' => $parametros]);
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
    | Executa a APP de Manutenção dos Parâmetros Gerais da Empresa
    |----------------------------------------------------------------------------------------------------
    */
    public function edit($empresa)
    {
        $parametrosEmp = $this->parEmpresa->where('parger_emp', $empresa)->first();

        return view('/parametros/gerencial/formularioParametrosGerencialEmpresa', ['parametrosEmp' => $parametrosEmp]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza o Update dos dados da Empresa Selecionada
    |----------------------------------------------------------------------------------------------------
    */
    public function update(Request $request, ParametrosGerEmpresa $geralEmpresa)
    {
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

        DB::table('parametros_ger_turnos')
        ->where('partur_emp', $geralEmpresa->parger_emp)
        ->where('partur_cod', '1')
        ->update(['partur_dia' => $request->diaFuncionamento]);

        //Atualiza os dados
        $geralEmpresa->parger_dia_fun = $request->diaFuncionamento;
        $geralEmpresa->parger_hr_ini_fun = $hrIni;
        $geralEmpresa->parger_hr_fin_fun = $hrFin;
        $geralEmpresa->parger_int_fun = $request->usaInt;
        $geralEmpresa->parger_hr_ini_int = $horaIniInt;
        $geralEmpresa->parger_hr_fin_int = $horaFinInt;
        $geralEmpresa->parger_tur_srv = $request->turSrv;
        $geralEmpresa->parger_hr_alt_sab = $request->hrAltSab;
        $geralEmpresa->parger_hr_ini_sab = $horaIniSab;
        $geralEmpresa->parger_hr_fin_sab = $horaFinSab;
        $geralEmpresa->parger_int_sab = $request->usaIntSab;
        $geralEmpresa->parger_hr_ini_int_sab = $horaIniIntSab;
        $geralEmpresa->parger_hr_fin_int_sab = $horaFinIntSab;
        $geralEmpresa->parger_hr_alt_dom = $request->hrAltDom;
        $geralEmpresa->parger_hr_ini_dom = $horaIniDom;
        $geralEmpresa->parger_hr_fin_dom = $horaFinDom;
        $geralEmpresa->parger_int_dom = $request->usaIntDom;
        $geralEmpresa->parger_hr_ini_int_dom = $horaIniIntDom;
        $geralEmpresa->parger_hr_fin_int_dom = $horaFinIntDom;
    
        $geralEmpresa->save();
        
        return redirect(route('geralEmpresa.edit', ['geralEmpresa' => $geralEmpresa->parger_emp]))->with('success', 'Parâmetros atualizados com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
