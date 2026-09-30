<?php

namespace App\Http\Controllers\Parametros\Sistema;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Parametros\Sistema\ParametrosSisServico;

class ParametrosSisServicoController extends Controller
{
    protected $servico;
    
    public function __construct(ParametrosSisServico $servico)
    {
        $this->servico = $servico;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP de Cadastro de Serviço
    |----------------------------------------------------------------------------------------------------
    */
    public function create()
    {
        return view('/parametros/sistema/cadastroServicos');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a Inclusão de Novo Serviço
    |----------------------------------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        //Verifica se o servico ja foi cadastrada
        $srv_cnt = $this->servico->where('servico_codigo','=',$request->codigo)->where('servico_grupo','=',$request->grupo)->count();

        if($srv_cnt > 0){
            return redirect()->back()->with('error', 'Serviço '.$request->codigo.' do Grupo '.$request->grupo.' já foi cadastrado!');
        }

        $dados = [
            'servico_grupo' => $request->grupo,
            'servico_codigo' => $request->codigo,
            'servico_desc' => $request->descricao 
        ];
        
        $novoServico = ParametrosSisServico::create($dados);
        
        return redirect(route('servicosSistema.edit', ['servicosSistema' => $novoServico]))->with('success', 'Serviço cadastrado com sucesso!');
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
    | Executa a APP de Manutenção do Serviço
    |----------------------------------------------------------------------------------------------------
    */
    public function edit(ParametrosSisServico $servicosSistema)
    {
        return view('/parametros/sistema/editarParametrosSistemaServicos',['dadosServico'=>$servicosSistema]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza o Update dos dados do Serviço Selecionado
    |----------------------------------------------------------------------------------------------------
    */
    public function update(Request $request, ParametrosSisServico $servicosSistema)
    {
        $servicosSistema->servico_desc = $request->descricao;
        $servicosSistema->save();
        
        return redirect(route('servicosSistema.edit', ['servicosSistema' => $servicosSistema]))->with('success', 'Serviço atualizado com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza a Exclusão dos dados do Serviço Selecionado
    |----------------------------------------------------------------------------------------------------
    */
    public function destroy(ParametrosSisServico $servicosSistema)
    {
        $servicosSistema->delete();
        
        return redirect(route('home.parSisServico'))->with('success', 'Serviço excluído com sucesso!');
    }
}
