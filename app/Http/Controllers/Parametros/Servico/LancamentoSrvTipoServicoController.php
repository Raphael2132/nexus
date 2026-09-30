<?php

namespace App\Http\Controllers\Parametros\Servico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Parametros\Servico\LancamentoSrvTipoServico;

class LancamentoSrvTipoServicoController extends Controller
{
    protected $tipoServico;
    
    public function __construct(LancamentoSrvTipoServico $tipoServico)
    {
        $this->tipoServico = $tipoServico;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP Home de Tipo de Serviço da Empresa
    |----------------------------------------------------------------------------------------------------
    */
    public function index()
    {
        $tipos = $this->tipoServico->reorder('tipsrv_emp', 'asc')->reorder('tipsrv_cod', 'asc')->get();

        return view('/parametros/servico/homeLancamentosServicoTipo', ['tipos' => $tipos]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP de Cadastro de Tipo de Serviço
    |----------------------------------------------------------------------------------------------------
    */
    public function create()
    {
        return view('/parametros/servico/formularioLancamentosServicoTipo', ['acao' => 'N', 'dadosTipo' => '']);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a Inclusão de Novo Tipo de Serviço
    |----------------------------------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $codigo = strtoupper($request->codigo);

        //Verifica se o tipo do serviço ja foi cadastrado
        $tip_cnt = $this->tipoServico->where('tipsrv_cod',$codigo)->where('tipsrv_emp',$request->empresa)->count();

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
        
        return redirect(route('tipoServico.edit', ['tipoServico' => $novoTipo]))->with('success', 'Tipo de Serviço cadastrado com sucesso!');
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
    | Executa a APP de Manutenção do Tipo de Serviço
    |----------------------------------------------------------------------------------------------------
    */
    public function edit(LancamentoSrvTipoServico $tipoServico)
    {
        $tipoSelecionado = $this->tipoServico->where('tipsrv_cod', $tipoServico->tipsrv_cod)->where('tipsrv_emp', $tipoServico->tipsrv_emp)->first();

        return view('/parametros/servico/formularioLancamentosServicoTipo', ['acao' => 'E', 'dadosTipo' => $tipoSelecionado]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza o Update dos dados do Tipo de Serviço
    |----------------------------------------------------------------------------------------------------
    */
    public function update(Request $request, LancamentoSrvTipoServico $tipoServico)
    {
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

        $tipoServico->update([
            'tipsrv_nom' => $request->descricao,
            'tipsrv_pmt_des' => $request->permiteDesc,
            'tipsrv_pmd' => $percentual,
            'tipsrv_vmd' => $valor,
            'tipsrv_ahs' => $request->altHR,
            'tipsrv_avs' => $request->altVLR,
            'tipsrv_sts' => $request->status
        ]);
        
        return redirect(route('tipoServico.edit', ['tipoServico' => $tipoServico]))->with('success', 'Tipo de Serviço atualizado com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza a Exclusão dos dados do Tipo de Serviço
    |----------------------------------------------------------------------------------------------------
    */
    public function destroy(LancamentoSrvTipoServico $tipoServico){

        $tipoServico->delete();

        return redirect(route('tipoServico.index'))->with('success', 'Tipo de Serviço excluído com sucesso!');
    }
}
