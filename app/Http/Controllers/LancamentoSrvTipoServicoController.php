<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LancamentoSrvTipoServico;
use stdClass;

class LancamentoSrvTipoServicoController extends Controller
{
    protected $tipoServico;
    
    public function __construct(LancamentoSrvTipoServico $tipoServico)
    {
        $this->tipoServico = $tipoServico;
    }

    //Redireciona a app para o cadastro dos tipo do serviço
    public function cadastro()
    {
        return view('/parametros/servico/formularioLancamentosServicoTipo', ['acao' => 'N', 'dadosTipo'=>'']);
    }

    //Chama a app de edição dos dados do tipo do serviço selecionado
    public function editar($codigo, $empresa)
    {
        $tipoSelecionado = $this->tipoServico->where('tipsrv_cod','=',$codigo)->where('tipsrv_emp','=',$empresa)->get();

        return view('/parametros/servico/formularioLancamentosServicoTipo', ['acao' => 'E', 'dadosTipo'=>$tipoSelecionado]);
    }

    //Insere o tipo do serviço
    public function insert(Request $request){

        $codigo = strtoupper($request->codigo);

        //Verifica se o tipo do serviço ja foi cadastrado
        $tip_cnt = $this->tipoServico->where('tipsrv_cod','=',$request->empresa)->where('tipsrv_emp','=',$codigo)->count();

        if($tip_cnt > 0){
            return redirect()->back()->with('error', 'Para a empresa '.$request->empresa.' o Tipo de Serviço '.$codigo.' já foi cadastrado!');
        }

        //Verifica se permite descon se os valores foram informados
        if($request->permiteDesc == 'S'){
        
            if(empty($request->perMaxDes) || empty($request->valMaxDes)){
                return redirect()->back()->with('error', 'Quando permite desconto na TMO é obrigatório informar o percentual e o valor máximo do desconto!');
            }
        }

        if(!empty($request->perMaxDes)){
            $percentual = str_replace(".","",$request->perMaxDes);
            $percentual = str_replace(",",".",$percentual);
        }else{
            $percentual = '0.00';
        }
        
        if(!empty($request->valMaxDes)){
            $valor = str_replace(".","",$request->valMaxDes);
            $valor = str_replace(",",".",$valor);
        }else{
            $valor = '0.00';
        }

        $dados = [
            'tipsrv_emp' => $request->empresa,
            'tipsrv_cod' => $codigo,
            'tipsrv_nom' => $request->descricao,
            'tipsrv_are' => $request->area,
            'tipsrv_cat' => $request->categoria,
            'tipsrv_pmt_des' => $request->permiteDesc,
            'tipsrv_pmd' => $percentual,
            'tipsrv_vmd' => $valor,
            'tipsrv_ahs' => $request->altHR,
            'tipsrv_avs' => $request->altVLR,
            'tipsrv_sts' => $request->status
        ];
        
        $novoTipo = LancamentoSrvTipoServico::create($dados);
        
        return redirect(route('lancamentosSrvTipo.editarCadastro', ['codigo' => $request->codigo, 'empresa' => $request->empresa]))->with('success', 'Tipo de Serviço cadastrado com sucesso!');
    }

    //Realiza a atualização dos dados do tipo de serviço
    public function update(Request $request){

        if($request->permiteDesc == 'S'){
        
            if(empty($request->perMaxDes) || empty($request->valMaxDes)){
                return redirect()->back()->with('error', 'Quando permite desconto na TMO é obrigatório informar o percentual e o valor máximo do desconto!');
            }
        }

        if(!empty($request->perMaxDes)){
            $percentual = str_replace(".","",$request->perMaxDes);
            $percentual = str_replace(",",".",$percentual);
        }else{
            $percentual = '0.00';
        }
        
        if(!empty($request->valMaxDes)){
            $valor = str_replace(".","",$request->valMaxDes);
            $valor = str_replace(",",".",$valor);
        }else{
            $valor = '0.00';
        }

        $atualizaTipoSrv = DB::table('lancamento_srv_tipo_servicos')
            ->where('tipsrv_cod', $request->codigo)
            ->where('tipsrv_emp', $request->empresa)
            ->update(['tipsrv_nom' => $request->descricao,
                    'tipsrv_pmt_des' => $request->permiteDesc,
                    'tipsrv_pmd' => $percentual,
                    'tipsrv_vmd' => $valor,
                    'tipsrv_ahs' => $request->altHR,
                    'tipsrv_avs' => $request->altVLR,
                    'tipsrv_sts' => $request->status]);
        
        return redirect(route('lancamentosSrvTipo.editarCadastro', ['codigo' => $request->codigo, 'empresa' => $request->empresa]))->with('success', 'Tipo de Serviço atualizado com sucesso!');
    }

    //Exclui os dados do tipo de serviço
    public function destroy(LancamentoSrvTipoServico $tipo, $origem){

        $tipo->delete();

        if($origem == "ajax"){

            return response()->json(['success' => true],200);

        }else{
        
            return redirect(route('home.lancSrvTipo'))->with('success', 'Tipo de Serviço excluído com sucesso!');
        }
    }

    //Redireciona a home depois da exclusão do registro via ajax
    public function homeAjax()
    {  
        return redirect(route('home.lancSrvTipo'))->with('success', 'Tipo de Serviço excluído com sucesso!');
    }
}
