<?php

namespace App\Http\Controllers\Parametros\Servico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Parametros\Servico\ParametrosSrvTmo;

class ParametrosSrvTmoController extends Controller
{
    protected $tarefa;
    
    public function __construct(ParametrosSrvTmo $tarefa)
    {
        $this->tarefa = $tarefa;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP Home de Tarefa de Mão de Obra da Empresa
    |----------------------------------------------------------------------------------------------------
    */
    public function index()
    {
        $tarefas = $this->tarefa->reorder('tmo_emp', 'asc')->reorder('tmo_cod', 'asc')->get();

        return view('/parametros/servico/homeParametrosServicoTMO', ['tarefas'=>$tarefas]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP de Cadastro de Tarefa de Mão de Obra
    |----------------------------------------------------------------------------------------------------
    */
    public function create()
    {
        return view('/parametros/servico/formularioParametrosServicoTMO', ['acao' => 'N', 'dadosTMO' => '']);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a Inclusão de Nova Tarefa de Mão de Obra
    |----------------------------------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        //Verifica se o setor ja foi cadastrada
        $tmo_cnt = $this->tarefa->where('tmo_emp','=',$request->empresa)->where('tmo_set','=',$request->setor)->where('tmo_cod','=',$request->codigo)->count();

        if($tmo_cnt > 0){
            return redirect()->back()->with('error', 'A tarefa '.$request->codigo.' do setor '.$request->setor.' já foi cadastrada para a empresa '.$request->empresa.'!');
        }

        if(!empty($request->prestResp)){
            $prestador = substr($request->prestResp, 0, 6);

            if(strlen($prestador) < 6){
                return redirect()->back()->with('error', 'Código do prestador responsável '.$prestador.' é inválido!');
            }

            $cnt_prest = DB::table('cadastro_prestadores')->where('prestador_empresa', $request->empresa)->where('prestador_are', $request->area)->where('prestador_set', $request->setor)->where('prestador_codigo', $prestador)->count();
        
            if($cnt_prest == 0){
                return redirect()->back()->with('error', 'Código do prestador responsável '.$prestador.' não existe!');
            }
        
        }else{
            $prestador = null;
        }

        if(!empty($request->fornecedor)){
            $fornecedor = substr($request->fornecedor, 0, 10);

            if(strlen($fornecedor) < 10){
                return redirect()->back()->with('error', 'Código do fornecedor '.$fornecedor.' é inválido!');
            }

            $cnt_prest = DB::table('cadastro_clientes')->where('cliente_tipo_cadastro', 'F')->where('cliente_codigo', $fornecedor)->count();
        
            if($cnt_prest == 0){
                return redirect()->back()->with('error', 'Código do fornecedor '.$fornecedor.' não existe!');
            }
        
        }else{
            $fornecedor = null;
        }

        if(!empty($request->qtdHora)){
            $qtd_hora = str_replace(",",".",$request->qtdHora);
        }else{
            $qtd_hora = '0.00';
        }
        
        if(!empty($request->valHora)){
            $valor_hora = str_replace(".","",$request->valHora);
            $valor_hora = str_replace(",",".",$valor_hora);
        }else{
            $valor_hora = '0.00';
        }

        if(!empty($request->valTot)){
            $valor_tot = str_replace(".","",$request->valTot);
            $valor_tot = str_replace(",",".",$valor_tot);
        }else{
            $valor_tot = '0.00';
        }

        if(!empty($request->valCGT)){
            $valor_cgt = str_replace(".","",$request->valCGT);
            $valor_cgt = str_replace(",",".",$valor_cgt);
        }else{
            $valor_cgt = '0.00';
        }

        if(!empty($request->perCGT)){
            $per_cgt = str_replace(".","",$request->perCGT);
            $per_cgt = str_replace(",",".",$per_cgt);
        }else{
            $per_cgt = '0.00';
        }

        $dados = [
            'tmo_emp' => $request->empresa,
            'tmo_set' => $request->setor,
            'tmo_are' => $request->area,
            'tmo_cod' => $request->codigo,
            'tmo_dsc' => $request->descricao,
            'tmo_cmp' => $request->complemento,
            'tmo_tip' => $request->tipoTMO,
            'tmo_qtd_hr' => $qtd_hora,
            'tmo_val_hr' => $valor_hora,
            'tmo_val_tot' => $valor_tot,
            'tmo_for_cgt' => $fornecedor,
            'tmo_tip_val_cgt' => $request->tipValCGT,
            'tmo_val_cgt' => $valor_cgt,
            'tmo_per_cgt' => $per_cgt,
            'tmo_res' => $prestador,
            'tmo_sts' => $request->status
        ];
        
        $novaTMO = ParametrosSrvTmo::create($dados);
        
       return redirect(route('servicoTMO.edit', ['servicoTMO' => $novaTMO]))->with('success', 'Tarefa de Mão de Obra cadastrada com sucesso!');
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
    | Executa a APP de Manutenção da Tarefa de Mão de Obra
    |----------------------------------------------------------------------------------------------------
    */
    public function edit(ParametrosSrvTmo $servicoTMO)
    {
        $tarefaSelecionada = $this->tarefa->where('tmo_emp', $servicoTMO->tmo_emp)->where('tmo_set', $servicoTMO->tmo_set)->where('tmo_cod', $servicoTMO->tmo_cod)->first();

        return view('/parametros/servico/formularioParametrosServicoTMO', ['acao' => 'E', 'dadosTMO' => $tarefaSelecionada]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza o Update dos dados da Tarefa de Mão de Obra
    |----------------------------------------------------------------------------------------------------
    */
    public function update(Request $request, ParametrosSrvTmo $servicoTMO)
    {
        if(!empty($request->prestResp)){
            $prestador = substr($request->prestResp, 0, 6);

            if(strlen($prestador) < 6){
                return redirect()->back()->with('error', 'Código do prestador responsável '.$prestador.' é inválido!');
            }

            $cnt_prest = DB::table('cadastro_prestadores')->where('prestador_empresa', $request->empresa)->where('prestador_are', $request->area)->where('prestador_set', $request->setor)->where('prestador_codigo', $prestador)->count();
        
            if($cnt_prest == 0){
                return redirect()->back()->with('error', 'Código do prestador responsável '.$prestador.' não existe!');
            }
        
        }else{
            $prestador = null;
        }

        if(!empty($request->fornecedor)){
            $fornecedor = substr($request->fornecedor, 0, 10);

            if(strlen($fornecedor) < 10){
                return redirect()->back()->with('error', 'Código do fornecedor '.$fornecedor.' é inválido!');
            }

            $cnt_prest = DB::table('cadastro_clientes')->where('cliente_tipo_cadastro', 'F')->where('cliente_codigo', $fornecedor)->count();
        
            if($cnt_prest == 0){
                return redirect()->back()->with('error', 'Código do fornecedor '.$fornecedor.' não existe!');
            }
        
        }else{
            $fornecedor = null;
        }

        if(!empty($request->qtdHora)){
            $qtd_hora = str_replace(",",".",$request->qtdHora);
        }else{
            $qtd_hora = '0.00';
        }
        
        if(!empty($request->valHora)){
            $valor_hora = str_replace(".","",$request->valHora);
            $valor_hora = str_replace(",",".",$valor_hora);
        }else{
            $valor_hora = '0.00';
        }

        if(!empty($request->valTot)){
            $valor_tot = str_replace(".","",$request->valTot);
            $valor_tot = str_replace(",",".",$valor_tot);
        }else{
            $valor_tot = '0.00';
        }

        if(!empty($request->valCGT)){
            $valor_cgt = str_replace(".","",$request->valCGT);
            $valor_cgt = str_replace(",",".",$valor_cgt);
        }else{
            $valor_cgt = '0.00';
        }

        if(!empty($request->perCGT)){
            $per_cgt = str_replace(".","",$request->perCGT);
            $per_cgt = str_replace(",",".",$per_cgt);
        }else{
            $per_cgt = '0.00';
        }

        $servicoTMO->update([
            'tmo_dsc' => $request->descricao,
            'tmo_cmp' => $request->complemento,
            'tmo_tip' => $request->tipoTMO,
            'tmo_qtd_hr' => $qtd_hora,
            'tmo_val_hr' => $valor_hora,
            'tmo_val_tot' => $valor_tot,
            'tmo_for_cgt' => $fornecedor,
            'tmo_tip_val_cgt' => $request->tipValCGT,
            'tmo_val_cgt' => $valor_cgt,
            'tmo_per_cgt' => $per_cgt,
            'tmo_res' => $prestador,
            'tmo_sts' => $request->status
        ]);
        
        return redirect(route('servicoTMO.edit', ['servicoTMO' => $servicoTMO]))->with('success', 'Tarefa de Mão de Obra atualizada com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza a Exclusão dos dados da Tarefa de Mão de Obra
    |----------------------------------------------------------------------------------------------------
    */
    public function destroy(ParametrosSrvTmo $servicoTMO){

        $servicoTMO->delete();

        return redirect(route('servicoTMO.index'))->with('success', 'Tarefa de Mão de Obra excluída com sucesso!');
    }
}
