<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ParametrosSistemaServico;
use stdClass;

class ParametrosSistemaServicoController extends Controller
{
    protected $servico;
    
    public function __construct(ParametrosSistemaServico $servico)
    {
        $this->servico = $servico;
    }

    //Chama a app de edição dos dados do serviço selecionado
    public function editar($grupo, $servico)
    {
        $servicoSelecionado = $this->servico->where('servico_grupo','=',$grupo)->where('servico_codigo','=',$servico)->get();

        return view('/parametros/sistema/editarParametrosSistemaServicos',['dadosServico'=>$servicoSelecionado]);
    }

    //Realiza a atualização dos dados do grupo do serviço
    public function update(Request $request, $grupo, $servico){

        $atualizausuario = DB::table('parametros_sistema_servicos')
            ->where('servico_grupo', $grupo)
            ->where('servico_codigo', $servico)
            ->update(['servico_desc' => $request->descricao]);
        
        return redirect(route('parametrosSistemaServico.editarCadastro', ['grupo' => $grupo, 'servico' => $servico]))->with('success', 'Serviço atualizado com sucesso!');
    }

    //Redireciona a app para o cadastro dos serviços
    public function cadastro()
    {
        return view('/parametros/sistema/cadastroServicos');
    }

    //Insere o grupo de serviço
    public function inserir(Request $request){

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
        
        $novoServico = ParametrosSistemaServico::create($dados);
        
        return redirect(route('parametrosSistemaServico.editarCadastro', ['grupo' => $request->grupo, 'servico' => $request->codigo]))->with('success', 'Serviço cadastrado com sucesso!');
    }

    //Exclui os dados de um serviço
    public function destroy(ParametrosSistemaServico $servico){

        $servico->delete();
        
        return redirect(route('home.parSisServico'))->with('success', 'Serviço excluído com sucesso!');
    }
}
