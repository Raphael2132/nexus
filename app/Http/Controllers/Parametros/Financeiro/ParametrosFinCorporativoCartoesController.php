<?php

namespace App\Http\Controllers\Parametros\Financeiro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Parametros\Financeiro\ParametrosFinCorporativoCartoes;
use App\Http\Helpers\Helper;

class ParametrosFinCorporativoCartoesController extends Controller
{

    protected $parCardCorp;

    public function __construct(ParametrosFinCorporativoCartoes $parCardCorp)
    {
        $this->parCardCorp = $parCardCorp;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP Home de Parâmetros do Cartão Corporativo
    |----------------------------------------------------------------------------------------------------
    */
    public function index()
    {
        $parametros = $this->parCardCorp->get();

        return view('/parametros/financeiro/par_cnt001_HomeCartaoCorporativo', ['dataParFinCardCorp' => $parametros]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP de Cadastro de Cartão Corporativo
    |----------------------------------------------------------------------------------------------------
    */
    public function create()
    {
        return view('/parametros/financeiro/par_frm001_CartaoCorporativo',['acao' => 'N', 'dadosCardCorp' => '']);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a Inclusão do Novo Cartão
    |----------------------------------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        //Verifica se já existe cartão com a administradora cadastrada
        $cntCard = DB::table('parametros_fin_corporativo_cartoes')->where('parcco_emp', $request->empresa)->where('parcco_adm', $request->administradora)->count();

        if($cntCard > 0){
            return redirect()->back()->with('error', 'Já existe Cartão Corporativo cadstrado com a administradora selecionada!');
        }

        $dados = [
            'parcco_sts' => $request->situacao,
            'parcco_emp' => $request->empresa,    
            'parcco_adm' => $request->administradora,  
            'parcco_num' => $request->numCard,  
            'parcco_nom' => $request->nomCard,  
            'parcco_dif' => $request->diaFec,  
            'parcco_div' => $request->diaVct, 
            'parcco_par' => $request->qtdPar, 
        ];
        
        ParametrosFinCorporativoCartoes::create($dados);
        
        return redirect(route('financeiroCorporativo.index'))->with('success', 'Cartão corporativo cadastrado com sucesso!');
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
    | Executa a APP de Manutenção dos Cartões Corporativos
    |----------------------------------------------------------------------------------------------------
    */
    public function edit(ParametrosFinCorporativoCartoes $financeiroCorporativo)
    {
        return view('/parametros/financeiro/par_frm001_CartaoCorporativo',['acao'=> 'E', 'dadosCardCorp' => $financeiroCorporativo]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza o Update dos dados do Cartão Corporativo
    |----------------------------------------------------------------------------------------------------
    */
    public function update(Request $request, ParametrosFinCorporativoCartoes $financeiroCorporativo)
    {
        $financeiroCorporativo->parcco_sts = $request->situacao;
        $financeiroCorporativo->parcco_num = $request->numCard;
        $financeiroCorporativo->parcco_nom = $request->nomCard;
        $financeiroCorporativo->parcco_dif = $request->diaFec;
        $financeiroCorporativo->parcco_div = $request->diaVct;
        $financeiroCorporativo->parcco_par = $request->qtdPar;

        $financeiroCorporativo->save();
    
        return redirect(route('financeiroCorporativo.edit', ['financeiroCorporativo' => $financeiroCorporativo]))->with('success', 'Cartão Corporativo atualizado com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza a Exclusão dos dados do Cartão Corporativo Selecionado
    |----------------------------------------------------------------------------------------------------
    */
    public function destroy(ParametrosFinCorporativoCartoes $financeiroCorporativo)
    {
        $financeiroCorporativo->delete();
        
        return redirect(route('financeiroCorporativo.index'))->with('success', 'Cartão Corporativo excluído com sucesso!');
    }
}
