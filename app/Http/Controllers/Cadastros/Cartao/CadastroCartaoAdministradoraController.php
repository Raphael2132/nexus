<?php

namespace App\Http\Controllers\Cadastros\Cartao;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cadastros\Cartao\CadastroCartaoAdministradoras;

class CadastroCartaoAdministradoraController extends Controller
{

    protected $cartao;
    
    public function __construct(CadastroCartaoAdministradoras $cartao)
    {
        $this->cartao = $cartao;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP Home do Cadastro de Adm. de Cartão
    |----------------------------------------------------------------------------------------------------
    */
    public function index()
    {
        $cartoes = $this->cartao->all();

        return view('/cadastros/cartao/cad_hom005_HomeCartao', ['cartoes' => $cartoes]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP de Cadastro daAdministradora
    |----------------------------------------------------------------------------------------------------
    */
    public function create()
    {
        return view('/cadastros/cartao/cad_frm005_FormularioCadCartao',['tipoCad' => 'N']);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a Inclusão do Nova Administradora
    |----------------------------------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        if(!empty($request->cnpjAdm)){

            $cnpj = Helper::limpaCNPJ($request->cnpjAdm);

            if(strlen($cnpj) < 14){
                return redirect()->back()->with('error', 'Formatro do CNPJ inválido!');
            }

        }else{

            return redirect()->back()->with('error', 'Por Favor informe o CNPJ da Administradora!');
        }

        $nextval=DB::select("SELECT nextval('sq_cadastro_cartao_administradora')")[0]->nextval;
        $codigo = 'ADM'.str_pad($nextval,3,'0',STR_PAD_LEFT);

        $dados = [
            'administradora_codigo' => $codigo,
            'administradora_nome' => $request->nome,
            'administradora_cnpj' => $cnpj,
            'administradora_tipo' => $request->tipoAdm,
            'administradora_parcela' => $request->parAdm,
        ];

        CadastroCartaoAdministradoras::create($dados);

        return redirect(route('cadastroCartao.index'))->with('success', 'Administradora cadastrada com sucesso!');
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
    | Executa a APP de Manutenção daAdministradora
    |----------------------------------------------------------------------------------------------------
    */
    public function edit($codigo)
    {
        $cartao = $this->cartao->where('administradora_codigo', $codigo)->first();

        return view('/cadastros/cartao/cad_frm005_FormularioCadCartao',['tipoCad' => 'M', 'dadosCartao' => $cartao]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza o Update dos dados da Administradora
    |----------------------------------------------------------------------------------------------------
    */
    public function update(Request $request, CadastroCartaoAdministradoras $cadastroCartao)
    {

        if(!empty($request->cnpjAdm)){

            $cnpj = Helper::limpaCNPJ($request->cnpjAdm);

            if(strlen($cnpj) < 14){
                return redirect()->back()->with('error', 'Formatro do CNPJ inválido!');
            }

        }else{

            return redirect()->back()->with('error', 'Por Favor informe o CNPJ da Administradora!');
        }

        $cadastroCartao->update([
            'administradora_nome' => $request->nome,
            'administradora_cnpj' => $cnpj,
            'administradora_tipo' => $request->tipoAdm,
            'administradora_parcela' => $request->parAdm,
        ]);

        return redirect(route('cadastroCartao.edit', ['cadastroCartao' => $cadastroCartao->administradora_codigo]))->with('success', 'Dados atualizados com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza a Exclusão da Administradora
    |----------------------------------------------------------------------------------------------------
    */
    public function destroy(CadastroCartaoAdministradoras $cadastroCartao)
    {

        $cntRazao = DB::table('financeiro_razoes')->where('razao_tipo', 'CC')->where('razao_adm_cod', $cadastroCartao->administradora_codigo)->count();

        if($cntRazao > 0){

            return redirect()->back()->with('error', 'Não é possível excluir a administradora, pois ela está associada a um razão!');

        }

        $cadastroCartao->delete();
        
        return redirect(route('cadastroCartao.index'))->with('success', 'Administradora excluída com sucesso!');
    }
}
