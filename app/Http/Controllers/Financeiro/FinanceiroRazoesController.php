<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Financeiro\FinanceiroIniciaRazaoController;
use App\Http\Controllers\Parametros\Faturamento\Fatura\ParametrosFatFaturaBancosController;
use App\Http\Helpers\Helper;
use App\Http\Helpers\HelperFormatSelect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Financeiro\FinanceiroRazoes;
use App\Models\Parametros\Faturamento\Fatura\ParametrosFatFaturaBancos;

class FinanceiroRazoesController extends Controller
{
    protected $razao;
    
    public function __construct(FinanceiroRazoes $razao)
    {
        $this->razao = $razao;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP Home do Cadastro de Razões
    |----------------------------------------------------------------------------------------------------
    */
    public function index()
    {
        $razoes = $this->razao->all();

        $qtdBA = DB::table('financeiro_razoes')->where('razao_tipo', 'BA')->count();
        $qtdCX = DB::table('financeiro_razoes')->where('razao_tipo', 'CX')->count();
        $qtdTE = DB::table('financeiro_razoes')->where('razao_tipo', 'TE')->count();
        $qtdCC = DB::table('financeiro_razoes')->where('razao_tipo', 'CC')->count();
        $qtdCO = DB::table('financeiro_razoes')->where('razao_tipo', 'CO')->count();

        return view('/cadastros/financeiro/fin001HomeRazao', ['razoes' => $razoes, 'qtdBA' => $qtdBA, 'qtdCX' => $qtdCX, 'qtdTE' => $qtdTE, 'qtdCC' => $qtdCC, 'qtdCO' => $qtdCO]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP de Cadastro de Empresa
    |----------------------------------------------------------------------------------------------------
    */
    public function create()
    {
        return view('/cadastros/financeiro/fin001FormularioCadastroRazao',['tipoCad' => 'N']);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a Inclusão do Novo Razão
    |----------------------------------------------------------------------------------------------------
    */
    public function store(Request $request)
    {

        //Verifica se a relação do tipo do razão pode ser multipla ou é única, se for única não pode permitir gravar mais de um razão do tipo
        $dadosConta = DB::table('financeiro_tab_contas')->where('tabcon_codigo', $request->tipoRazao)->first();

        //Se for Única verifica se ja tem para a empresa a conta criada
        if($dadosConta->tabcon_relacao == 'U'){
            $cntRaz = DB::table('financeiro_razoes')->where('razao_tipo', $request->tipoRazao)->where('razao_empresa',$request->empresa)->count();

            $empresaFormat = HelperFormatSelect::formataEmpresaCodigoNome($request->empresa);

            if($cntRaz >= 1){
                return redirect()->back()->with('error', 'O Razão do tipo '.$dadosConta->tabcon_nome.' deve ser único e já foi cadastrado para a empresa '.$empresaFormat.'!');
            }
        }

        //Se for BA verifica a data da primeira conciliação que não pode ser maior que a data atual
        if($request->tipoRazao == 'BA'){

            $dataCon = Helper::limpaData($request->dataConc);
            $dataAtu = date('Y-m-d');

            if($dataCon > $dataAtu){
                return redirect()->back()->with('info', 'A Data da Primeira Conciliação não pode ser maior que a data atual!');
            }

        }

        //Saldo Inicial
        if(empty($request->valIni)){
            $valorIni = 0;
        }else{
            $valorIni = Helper::limpaValorMonetario($request->valIni);
        }

        //Inicia a transação
        DB::beginTransaction();

        //Dados extras de cada tipo de razão
        if($request->tipoRazao == 'BA'){
            
            $nextval=DB::select("SELECT nextval('sq_financeiro_razao_seq_ba')")[0]->nextval;
            $codigo = 'BA'.str_pad($nextval,4,'0',STR_PAD_LEFT);

            //Código e Nome do Banco
            $dadosBanco = DB::table('financeiro_tab_bancos')->where('tabban_codigo',$request->codBanco)->first();
            $bancoNome = $dadosBanco->tabban_nome;

            $dados = [
                'razao_empresa' => $request->empresa,
                'razao_codigo' => $codigo,
                'razao_ano' => date('Y'),
                'razao_nome' => $request->nome,
                'razao_tipo' => $request->tipoRazao,
                'razao_bco_cod' => $request->codBanco,
                'razao_bco_nom' => $bancoNome,
                'razao_bco_age' => $request->agencia,
                'razao_bco_age_dv' => $request->agenciaDV,
                'razao_bco_ncc' => $request->conta,
                'razao_bco_ncc_dv' => $request->contaDV,
                'razao_nom_fan' => $request->nomeFan,
                'razao_car' => 'D',
                'razao_saldo_ini' => $valorIni,
                'razao_saldo_atu' => $valorIni,
                'razao_dt_con' => Helper::limpaData($request->dataConc),
                'razao_hr_con' => date('Hi'),
                'razao_saldo_con' => $valorIni,
            ];

        }elseif($request->tipoRazao == 'CC'){

            $dataAdm = DB::table('cadastro_cartao_administradoras')->where('administradora_codigo', $request->codAdmCC)->first();

            if($dataAdm->administradora_tipo == 'D' && $request->tipoCard == 'C'){

                return redirect()->back()->with('info', 'A Administradora selecionada opera apenas Cartão de Débito!');

            }elseif($dataAdm->administradora_tipo == 'C' && $request->tipoCard == 'D'){

                return redirect()->back()->with('info', 'A Administradora selecionada opera apenas Cartão de Crédito!');
            }

            if($request->tipoCard == 'C'){

                $nextval=DB::select("SELECT nextval('sq_financeiro_razao_seq_cc_cred')")[0]->nextval;
                $codigo = 'ADMC'.str_pad($nextval,2,'0',STR_PAD_LEFT);

            }else{

                $nextval=DB::select("SELECT nextval('sq_financeiro_razao_seq_cc_deb')")[0]->nextval;
                $codigo = 'ADMD'.str_pad($nextval,2,'0',STR_PAD_LEFT);
            }

            $dados = [
                'razao_empresa' => $request->empresa,
                'razao_codigo' => $codigo,
                'razao_ano' => date('Y'),
                'razao_nome' => $request->nome,
                'razao_tipo' => $request->tipoRazao,
                'razao_adm_cod' => $request->codAdmCC,
                'razao_tip_adm' => $request->tipoAdm,
                'razao_tip_card' => $request->tipoCard,
                'razao_ban_card' => $request->bandeiraCard,
                'razao_bco_adm' => $request->bancoAdm,
                'razao_dep_on' => 'S',
                'razao_dia_prl' => $request->diaParcela,
                'razao_dia_vst' => $request->diaVista,
                'razao_nom_fan' => $request->nomeFan,
                'razao_saldo_ini' => $valorIni,
                'razao_saldo_atu' => $valorIni
            ];

        }elseif($request->tipoRazao == 'CX'){

            $nextval=DB::select("SELECT nextval('sq_financeiro_razao_seq_cx')")[0]->nextval;
            $codigo = 'CX'.str_pad($nextval,4,'0',STR_PAD_LEFT);

            $dados = [
                'razao_empresa' => $request->empresa,
                'razao_codigo' => $codigo,
                'razao_ano' => date('Y'),
                'razao_nome' => $request->nome,
                'razao_tipo' => $request->tipoRazao,
                'razao_saldo_ini' => $valorIni,
                'razao_saldo_atu' => $valorIni
            ];

        }elseif($request->tipoRazao == 'TE'){

            $nextval=DB::select("SELECT nextval('sq_financeiro_razao_seq_te')")[0]->nextval;
            $codigo = 'TE'.str_pad($nextval,4,'0',STR_PAD_LEFT);
            
            $dados = [
                'razao_empresa' => $request->empresa,
                'razao_codigo' => $codigo,
                'razao_ano' => date('Y'),
                'razao_nome' => $request->nome,
                'razao_tipo' => $request->tipoRazao,
                'razao_saldo_ini' => $valorIni,
                'razao_saldo_atu' => $valorIni
            ];
        }elseif($request->tipoRazao == 'CO'){

            $nextval=DB::select("SELECT nextval('sq_financeiro_razao_seq_c_corp')")[0]->nextval;
            $codigo = 'ADCO'.str_pad($nextval,2,'0',STR_PAD_LEFT);

            $dados = [
                'razao_empresa' => $request->empresa,
                'razao_codigo' => $codigo,
                'razao_ano' => date('Y'),
                'razao_nome' => $request->nome,
                'razao_tipo' => $request->tipoRazao,
                'razao_bco_adm' => $request->bancoAdmCorp,
                'razao_dep_on' => 'S',
                'razao_nom_fan' => $request->nomeFan,
                'razao_saldo_ini' => $valorIni,
                'razao_saldo_atu' => $valorIni
            ];

        }
        
        FinanceiroRazoes::create($dados);

        //Se for Razão BA devemos realizar o saldo inicial do dia na tabela de movimentos
        if($request->tipoRazao == 'BA'){

            //Gera o Saldo Inicial do Razão
            FinanceiroIniciaRazaoController::inciaRazao($request->empresa, $codigo, $valorIni, Helper::limpaData($request->dataConc));

            // Chama o método store
            $controller = app(ParametrosFatFaturaBancosController::class);
            $response = $controller->store($request->empresa, $codigo);
        }

        //Grava as alterações do banco
        DB::commit();

        return redirect(route('cadastroRazao.index'))->with('success', 'Razão cadastrado com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Exibe a Lista de Razões
    |----------------------------------------------------------------------------------------------------
    */
    public function show($tipoRazao)
    {
        $razoes = $this->razao->where('razao_tipo',$tipoRazao)->get();

        return view('/cadastros/financeiro/fin001ConsultaRazao', ['razoes' => $razoes, 'tipoRazao' => $tipoRazao]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP de Manutenção do Razão
    |----------------------------------------------------------------------------------------------------
    */
    public function edit($codigo)
    {
        $razao = $this->razao->where('razao_codigo', $codigo)->first();

        return view('/cadastros/financeiro/fin001FormularioCadastroRazao',['tipoCad' => 'M', 'dadosRazao' => $razao]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza o Update dos dados do Razão
    |----------------------------------------------------------------------------------------------------
    */
    public function update(Request $request, FinanceiroRazoes $cadastroRazao)
    {
        if($request->tipoRazao == 'BA'){

            $cadastroRazao->update([
                'razao_nome' => $request->nome,
                'razao_nom_fan' => $request->nomeFan,
            ]);

        }elseif($request->tipoRazao == 'CC'){

            $cadastroRazao->update([
                'razao_nome' => $request->nome,
                'razao_nom_fan' => $request->nomeFan,
                'razao_adm_cod' => $request->codAdmCC,
                'razao_bco_adm' => $request->bancoAdm,
                'razao_dia_prl' => $request->diaParcela,
                'razao_dia_vst' => $request->diaVista,
            ]);

        }elseif($request->tipoRazao == 'CO'){

            $cadastroRazao->update([
                'razao_nome' => $request->nome,
                'razao_nom_fan' => $request->nomeFan,
                'razao_bco_adm' => $request->bancoAdmCorp,
            ]);

        }else{

            $cadastroRazao->update([
                'razao_nome' => $request->email,
            ]);
        }

        return redirect(route('cadastroRazao.edit', ['cadastroRazao' => $cadastroRazao->razao_codigo]))->with('success', 'Dados atualizados com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza a Exclusão do Razão
    |----------------------------------------------------------------------------------------------------
    */
    public function destroy(FinanceiroRazoes $cadastroRazao)
    {
        $cadastroRazao->delete();
        
        return redirect(route('cadastroRazao.index'))->with('success', 'Razão excluído com sucesso!');
    }
}
