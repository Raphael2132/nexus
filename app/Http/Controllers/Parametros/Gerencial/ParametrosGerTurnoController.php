<?php

namespace App\Http\Controllers\parametros\gerencial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Parametros\Gerencial\ParametrosGerTurno;
use App\Http\Helpers\Helper;

class ParametrosGerTurnoController extends Controller
{
    protected $turno;
    
    public function __construct(ParametrosGerTurno $turno)
    {
        $this->turno = $turno;
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
    | Executa a Inclusão de Novo Turno
    |----------------------------------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $empresa = $request->empresa_turno;

        $horaInicioTurno = Helper::limpaHoraMinuto($request->horaInicioTurno);
        $horaFinalTurno = Helper::limpaHoraMinuto($request->horaFinalTurno);

        if($horaInicioTurno > $horaFinalTurno){
            return redirect()->back()->with('error', 'A Hora Ini. Turno não pode ser maior que a Hora Fin. Turno !');
        }

        $cod = DB::table('parametros_ger_turnos')->where('partur_emp', $empresa)->max('partur_cod');

        $cod += 1;

        $dados = [
            'partur_emp' => $empresa,
            'partur_cod' => $cod,
            'partur_desc' => $request->descTurno,
            'partur_hr_ini' => $horaInicioTurno,
            'partur_hr_fin' => $horaFinalTurno,
            'partur_dia' => $request->diaTurno 
        ];
        
        ParametrosGerTurno::create($dados);
        
        return redirect(route('geralEmpresa.edit', ['geralEmpresa' => $empresa]))->with('success', 'Turno cadastrado com sucesso!');
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

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza a Exclusão dos dados do Turno Selecionado
    |----------------------------------------------------------------------------------------------------
    */
    public function destroy(ParametrosGerTurno $turnoEmpresa){

        $empresa = $turnoEmpresa->partur_emp;

        $turnoEmpresa->delete();

        return redirect(route('geralEmpresa.edit', ['geralEmpresa' => $empresa]))->with('success', 'Turno excluído com sucesso!');
    }
}
