<?php

namespace App\Http\Controllers\parametros\gerencial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Parametros\Gerencial\ParametrosSisSusMotivo;

class ParametrosSisSusMotivoController extends Controller
{
    protected $motivo;
    
    public function __construct(ParametrosSisSusMotivo $motivo)
    {
        $this->motivo = $motivo;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP Home de Motivos de Suspensão
    |----------------------------------------------------------------------------------------------------
    */
    public function index()
    {
        $motivos = $this->motivo->reorder('susmot_codigo', 'asc')->get();

        return view('/parametros/gerencial/homeParametrosSistemaMotivosSuspensao', ['motivos' => $motivos]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP de Cadastro de Motivos de Suspensão
    |----------------------------------------------------------------------------------------------------
    */
    public function create()
    {
        return view('/parametros/gerencial/formularioParametrosSisMotSuspensao',['acao' => 'N', 'dadosMotSus' => '']);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a Inclusão de Novo Motivo de Suspensão
    |----------------------------------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        //Verifica se já existe motivo cadastrado com o mesmo codigo
        $motivos = DB::table('parametros_sis_sus_motivos')->where('susmot_codigo', $request->codigo)->count();

        if($motivos > 0){
            return redirect()->back()->with('error', 'Já existe motivo cadstrado com o código '.$request->codigo.'!');
        }

        $dados = [
            'susmot_codigo' => $request->codigo,
            'susmot_desc' => $request->descricao,    
        ];
        
        ParametrosSisSusMotivo::create($dados);
        
        return redirect(route('motivoSuspensao.edit',['motivoSuspensao' => $request->codigo]))->with('success', 'Motivo de Suspensão cadastrado com sucesso!');
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
    | Executa a APP de Manutenção da Motivos de Suspensão
    |----------------------------------------------------------------------------------------------------
    */
    public function edit($codigo)
    {
        $dadosMotSus = $this->motivo->where('susmot_codigo', $codigo)->first();

        return view('/parametros/gerencial/formularioParametrosSisMotSuspensao',['acao'=> 'E', 'dadosMotSus' => $dadosMotSus]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza o Update dos dados do Motivo de Suspensão Selecionado
    |----------------------------------------------------------------------------------------------------
    */
    public function update(Request $request, ParametrosSisSusMotivo $motivoSuspensao)
    {
        $motivoSuspensao->susmot_desc = $request->descricao;

        $motivoSuspensao->save();
    
        return redirect(route('motivoSuspensao.edit', ['motivoSuspensao' => $motivoSuspensao->susmot_codigo]))->with('success', 'Motivo de Suspensão atualizado com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza a Exclusão dos dados do Motivo de Suspensão Selecionado
    |----------------------------------------------------------------------------------------------------
    */
    public function destroy(ParametrosSisSusMotivo $motivoSuspensao){

        $motivoSuspensao->delete();
        
        return redirect(route('motivoSuspensao.index'))->with('success', 'Motivo de Suspensão excluído com sucesso!');
    }
}
