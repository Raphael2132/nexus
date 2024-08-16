<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ParametrosGerTurno;
use stdClass;
use App\Http\Helpers\Helper;

class ParametrosGerTurnoController extends Controller
{
    protected $turno;
    
    public function __construct(ParametrosGerTurno $turno)
    {
        $this->turno = $turno;
    }

    //Insere provedor
    public function inserir(Request $request, $empresa){

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
        
        return redirect(route('parametrosGerEmp.editarCadastro', ['empresa' => $empresa]))->with('success', 'Turno cadastrado com sucesso!');
    }

    public function destroy(ParametrosGerTurno $turno, $empresa){

        $turno->delete();

        return redirect(route('parametrosGerEmp.editarCadastro', ['empresa' => $empresa]))->with('success', 'Turno excluído com sucesso!');
    }
}
