<?php

namespace App\Http\Controllers\parametros\gerencial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Parametros\Gerencial\ParametrosSisCanMotivos;

class ParametrosSisCanMotivosController extends Controller
{
    protected $motivo;
    
    public function __construct(ParametrosSisCanMotivos $motivo)
    {
        $this->motivo = $motivo;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP Home de Motivos de Cancelamento
    |----------------------------------------------------------------------------------------------------
    */
    public function index()
    {
        $motivos = $this->motivo->reorder('canmot_codigo', 'asc')->get();

        return view('/parametros/gerencial/homeParametrosSistemaMotivosCancelamento', ['motivos' => $motivos]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP de Cadastro de Motivos de Cancelamento
    |----------------------------------------------------------------------------------------------------
    */
    public function create()
    {
        return view('/parametros/gerencial/formularioParametrosSisMotCancelamento',['acao' => 'N', 'dadosMotCan' => '']);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a Inclusão de Novo Motivo de Cancelamento
    |----------------------------------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        //Verifica se já existe motivo cadastrado com o mesmo codigo
        $motivos = DB::table('parametros_sis_can_motivos')->where('canmot_codigo', $request->codigo)->count();

        if($motivos > 0){
            return redirect()->back()->with('error', 'Já existe motivo cadstrado com o código '.$request->codigo.'!');
        }

        $dados = [
            'canmot_codigo' => $request->codigo,
            'canmot_desc' => $request->descricao,    
        ];
        
        ParametrosSisCanMotivos::create($dados);
        
        return redirect(route('motivoCancelamento.edit', ['motivoCancelamento' => $request->codigo]))->with('success', 'Motivo de Cancelamento cadastrado com sucesso!');
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
    | Executa a APP de Manutenção da Motivos de Cancelamento
    |----------------------------------------------------------------------------------------------------
    */
    public function edit($codigo)
    {
        $dadosMotCan = $this->motivo->where('canmot_codigo', $codigo)->first();

        return view('/parametros/gerencial/formularioParametrosSisMotCancelamento',['acao'=> 'E', 'dadosMotCan' => $dadosMotCan]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza o Update dos dados do Motivo de Cancelamento Selecionado
    |----------------------------------------------------------------------------------------------------
    */
    public function update(Request $request, ParametrosSisCanMotivos $motivoCancelamento)
    {
        $motivoCancelamento->canmot_desc = $request->descricao;

        $motivoCancelamento->save();
    
        return redirect(route('motivoCancelamento.edit', ['motivoCancelamento' => $motivoCancelamento->canmot_codigo]))->with('success', 'Motivo de Cancelamento atualizado com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza a Exclusão dos dados do Motivo de Cancelamento Selecionado
    |----------------------------------------------------------------------------------------------------
    */
    public function destroy(ParametrosSisCanMotivos $motivoCancelamento){

        $motivoCancelamento->delete();
        
        return redirect(route('motivoCancelamento.index'))->with('success', 'Motivo de Cancelamento excluído com sucesso!');
    }
}
