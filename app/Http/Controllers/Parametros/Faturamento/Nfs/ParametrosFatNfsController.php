<?php

namespace App\Http\Controllers\Parametros\Faturamento\Nfs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Parametros\Faturamento\Nfs\ParametrosFatNfs;

class ParametrosFatNfsController extends Controller
{
    protected $emissao;
    
    public function __construct(ParametrosFatNfs $emissao)
    {
        $this->emissao = $emissao;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP Home de Emissão da NFS-e
    |----------------------------------------------------------------------------------------------------
    */
    public function index()
    {
        // Definir valores padrão da sessão
        session([
            'glo_appOrigem' => 'parametrosNfsEmissao'
        ]);

        $emissoes  = $this->emissao->all();
        
        return view('/parametros/faturamento/nfs/parametrosNfsEmissao', ['emissoes'=>$emissoes]);
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
    | Executa a APP de Manutenção da Emissão da NFS-e
    |----------------------------------------------------------------------------------------------------
    */
    public function edit($emissaoCod)
    {
        $dadosEmissao = $this->emissao->where('parnfs_empresa', $emissaoCod)->first();

        return view('/parametros/faturamento/nfs/editarParametrosNfsEmissao',['dadosEmissao' => $dadosEmissao]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza o Update dos dados da Emissão Selecionada
    |----------------------------------------------------------------------------------------------------
    */
    public function update(Request $request, ParametrosFatNfs $emissaoNFSe)
    {
        $empresa = $emissaoNFSe->parnfs_empresa;

        if($request->imprimeNFS == 'S'){
            $modulos = DB::table('parametros_sis_modulos')->where('modulo_empresa_codigo', $empresa)->first();

            if($modulos->modulo_emissao_nfs == 'N' && $modulos->modulo_emissao_nfs_simp == 'N'){
                return redirect()->back()->with('error', 'Empresa não utiliza o módulo de NFS-e!');
            }
        }

        if($request->imprimeRPS == 'S'){
            $modulos = DB::table('parametros_sis_modulos')->where('modulo_empresa_codigo', $empresa)->first();

            if($modulos->modulo_emissao_rps == 'N'){
                return redirect()->back()->with('error', 'Empresa não utiliza o módulo de RPS!');
            }
        }

        $provedorOld=DB::select("SELECT parnfs_provedor from parametros_fat_nfs where parnfs_empresa = '$empresa'")[0]->parnfs_provedor;

        $emissaoNFSe->update([
            'parnfs_utiliza_nfs' => $request->geraNFS,
            'parnfs_provedor' => $request->provedor,
            'parnfs_numeracao' => $request->numeroNFS,
            'parnfs_serie' => $request->serieNFS,
            'parnfs_impressao_nfs' => $request->imprimeNFS,
            'parnfs_impressao_rps' => $request->imprimeRPS
        ]); 
            
        $msgProv = "";
        $msgTip = "success";

        //Se o provedor foi trocado redefine os dados da conexão
        if($provedorOld != $request->provedor){

            DB::table('parametros_fat_nfs_conexoes')
            ->where('conexao_empresa', $empresa)
            ->update([
                'conexao_provedor' => $request->provedor,
                'conexao_usuario' => null,
                'conexao_senha' => null,
                'conexao_token' => null,
                'conexao_wsdl' => null,
                'conexao_ambiente' => 'H'
            ]);

            $msgProv = "</br></br>O provedor foi alterado. Por isso, precisamos redefinir a parametrização da conexão da empresa. Por favor, informe os novos dados de conexão para continuar!";
            $msgTip = "successCenter";
        }
        
        return redirect(route('emissaoNFSe.edit', ['emissaoNFSe' => $empresa]))->with($msgTip, 'Dados da emissão de NFS-e atualizada com sucesso!'.$msgProv);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
