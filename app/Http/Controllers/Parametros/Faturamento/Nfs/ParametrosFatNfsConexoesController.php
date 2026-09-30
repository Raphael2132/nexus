<?php

namespace App\Http\Controllers\Parametros\Faturamento\Nfs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Parametros\Faturamento\Nfs\ParametrosFatNfsConexoes;

class ParametrosFatNfsConexoesController extends Controller
{

    protected $conexao;
    
    public function __construct(ParametrosFatNfsConexoes $conexao)
    {
        $this->conexao = $conexao;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP Home de Conexões da NFS-e
    |----------------------------------------------------------------------------------------------------
    */
    public function index()
    {
        // Definir valores padrão da sessão
        session([
            'glo_appOrigem' => 'parametrosNfsConexao'
        ]);

        $conexoes  = $this->conexao->all();
        
        return view('/parametros/faturamento/nfs/parametrosNfsConexao', ['conexoes'=>$conexoes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    | Executa a APP de Manutenção da Conexão da NFS-e
    |----------------------------------------------------------------------------------------------------
    */
    public function edit($conexaoCod)
    {
        $dadosConexao = $this->conexao->where('conexao_empresa',$conexaoCod)->first();

        return view('/parametros/faturamento/nfs/editarParametrosNfsConexao',['dadosConexao' => $dadosConexao]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza o Update dos dados da Conexão Selecionada
    |----------------------------------------------------------------------------------------------------
    */
    public function update(Request $request, ParametrosFatNfsConexoes $conexaoNFSe)
    {

        // Capturar o valor de appOrigem da requisição
        $appOrigem = $request->query('appOrigem');

        $conexaoNFSe->update([
            'conexao_usuario' => $request->usuario,
            'conexao_senha' => $request->senha,
            'conexao_token' => $request->token,
            'conexao_wsdl' => $request->wsdl,
            'conexao_ambiente' => $request->ambiente,
        ]);
        
        return redirect(route('conexaoNFSe.edit', ['conexaoNFSe' => $conexaoNFSe->conexao_empresa]))->with('success', 'Dados da conexão de NFS-e atualizados com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
