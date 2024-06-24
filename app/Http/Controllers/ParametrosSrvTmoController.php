<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ParametrosSrvTmo;
use stdClass;

class ParametrosSrvTmoController extends Controller
{
    protected $tarefa;
    
    public function __construct(ParametrosSrvTmo $tarefa)
    {
        $this->tarefa = $tarefa;
    }

    //Redireciona a app para o cadastro dos setores
    public function cadastro()
    {
        return view('/parametros/servico/formularioParametrosServicoTMO', ['acao' => 'N', 'dadosTMO'=>'']);
    }

    //Chama a app de edição dos dados da tarefa selecionada
    public function editar($empresa, $setor, $codigo)
    {
        $tarefaSelecionada = $this->tarefa->where('tmo_emp','=',$empresa)->where('tmo_set','=',$setor)->where('tmo_cod','=',$codigo)->get();

        return view('/parametros/servico/formularioParametrosServicoTMO', ['acao' => 'E', 'dadosTMO'=>$tarefaSelecionada]);
    }

    //Insere a TMO
    public function insert(Request $request){

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
            'tmo_srv_grp' => $request->grpSrv,
            'tmo_srv_cod' => $request->codSrv,
            'tmo_for_cgt' => $request->fornecedor,
            'tmo_tip_val_cgt' => $request->tipValCGT,
            'tmo_val_cgt' => $valor_cgt,
            'tmo_per_cgt' => $per_cgt,
            'tmo_res' => $prestador,
            'tmo_sts' => $request->status
        ];
        
        $novaTMO = ParametrosSrvTmo::create($dados);
        
       return redirect(route('parametrosSrvTMO.editarCadastro', ['empresa' => $request->empresa, 'setor' => $request->setor, 'codigo' => $request->codigo]))->with('success', 'Tarefa de Mão de Obra cadastrada com sucesso!');
    }

    //Realiza a atualização dos dados da tarefa
    public function update(Request $request){

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

        $atualizausuario = DB::table('parametros_srv_tmos')
            ->where('tmo_cod', $request->codigo)
            ->where('tmo_emp', $request->empresa)
            ->where('tmo_set', $request->setor)
            ->update(['tmo_dsc' => $request->descricao,
            'tmo_cmp' => $request->complemento,
            'tmo_tip' => $request->tipoTMO,
            'tmo_qtd_hr' => $qtd_hora,
            'tmo_val_hr' => $valor_hora,
            'tmo_val_tot' => $valor_tot,
            'tmo_srv_grp' => $request->grpSrv,
            'tmo_srv_cod' => $request->codSrv,
            'tmo_for_cgt' => $request->fornecedor,
            'tmo_tip_val_cgt' => $request->tipValCGT,
            'tmo_val_cgt' => $valor_cgt,
            'tmo_per_cgt' => $per_cgt,
            'tmo_res' => $prestador,
            'tmo_sts' => $request->status]);
        
        return redirect(route('parametrosSrvTMO.editarCadastro', ['empresa' => $request->empresa, 'setor' => $request->setor, 'codigo' => $request->codigo]))->with('success', 'Tarefa de Mão de Obra atualizada com sucesso!');
    }

    //Exclui os dados do setor
    public function destroy(ParametrosSrvTmo $tarefa, $origem){

        $tarefa->delete();

        if($origem == "ajax"){

            return response()->json(['success' => true],200);

        }else{
        
            return redirect(route('home.parSrvTMO'))->with('success', 'Tarefa de Mão de Obra excluída com sucesso!');
        }
    }

    //Redireciona a home depois da exclusão do registro via ajax
    public function homeAjax()
    {  
        return redirect(route('home.parSrvTMO'))->with('success', 'Tarefa de Mão de Obra excluída com sucesso!');
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

    //Redireciona a home depois da exclusão do registro via ajax
    public function carregaSetAjax($area, $empresa)
    {  
        $setores = DB::table('parametros_srv_setores')->select('setor_codigo', 'setor_desc')->where('setor_empresa', $empresa)->where('setor_area', $area)->orderby('setor_codigo', 'asc')->get();

        if(!empty($setores[0])){

            foreach($setores as $setor) {
                $setores_ajax[] = array(
                    'id'	=> $setor->setor_codigo,
                    'cod_setor' => $setor->setor_codigo.' - '.$setor->setor_desc,
                );
            }  

            return response()->json(['success' => true, 'setores_ajax' => $setores_ajax, 'setores_ajax_existe' => 'S']);

        }else{

            return response()->json(['success' => true, 'setores_ajax' => null, 'setores_ajax_existe' => 'N']);
        }
    }

    //Redireciona a home depois da exclusão do registro via ajax
    public function carregaRespAjax($area, $setor, $empresa)
    {  
        $prestadores = DB::table('cadastro_prestadores')->select('prestador_codigo', 'prestador_nome')->where('prestador_empresa',$empresa)->where('prestador_set',$setor)->where('prestador_are',$area)->where('prestador_status','A')->orderBy('prestador_codigo', 'asc')->get();

        if(!empty($prestadores[0])){

            foreach($prestadores as $prestador) {
                $prestadores_ajax[] = array(
                    'id' => $prestador->prestador_codigo.' - '.$prestador->prestador_nome,
                );
            }  

            return response()->json(['success' => true, 'prestadores_ajax' => $prestadores_ajax, 'prestadores_ajax_existe' => 'S']);

        }else{
            
            return response()->json(['error' => true, 'prestadores_ajax' => null, 'prestadores_ajax_existe' => 'N']);
        }
    }
}
