<?php

namespace App\Http\Controllers\Financeiro\Recebimento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use stdClass;
use App\Models\Financeiro\Recebimento\FinanceiroRecebimentoValores;
use App\Http\Helpers\Helper;

class FinanceiroRecebimentoValorController extends Controller
{
    protected $valRec;
    
    public function __construct(FinanceiroRecebimentoValores $valRec)
    {
        $this->valRec = $valRec;
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
    | Executa a APP de Inclusão de Novo Valor de Recebimento
    |----------------------------------------------------------------------------------------------------
    */
    public function create(Request $request)
    {
        $tipoValor = $request->query('tipoValor');

        $idRecebimento = Session::get('glo_recebimento_id');
        $empresa = Session::get('glo_recebimento_empresa');
        $cliente = Session::get('glo_recebimento_cliente');
        $appOrigem = Session::get('glo_recebimento_origem');

        $contaTipo = ''; 
        $contaNum = '';
        $dadosCCT = '';

        if($tipoValor == 'DIN'){

            $estagio = 'REC_DINHEIRO';

            //Se for Dinheiro e ja existir esse tipo de valor recebido não permite continuar, esse tipo de valor não terá mais de um registro
            $existDin = DB::table('financeiro_recebimento_valores')->where('recval_cod_rec', $idRecebimento)->where('recval_emp', $empresa)->where('recval_tipo', 'DIN')->count();

            if($existDin > 0){
                return redirect()->back()->with('info', 'Já foi recebido um valor em dinheiro! Por favor edite o recebimento em dinheiro existente para realizar alteração do valor.');
            }

        }elseif($tipoValor == 'PIX'){

            $estagio = 'REC_PIX';

        }elseif($tipoValor == 'CCR'){

            $estagio = 'REC_C_CREDITO';

        }elseif($tipoValor == 'CDB'){

            $estagio = 'REC_C_DEBITO';

        }elseif($tipoValor == 'CHQ'){

            $estagio = 'REC_CHEQUE';

        }elseif($tipoValor == 'CCT'){

            $estagio = 'REC_CONTA_CORRENTE';

            $contaTipo = $request->conta_tipo; 
            $contaNum = $request->conta_num;

            $dadosCCT = DB::table('financeiro_contas_correntes')
            ->select(
                DB::raw('(conta_valor - conta_val_rec) as conta_saldo'),
                'conta_responsavel',
                'conta_tipo',
                'conta_num_conta',
                'conta_dt_vencimento'
            )
            ->where('conta_empresa', $empresa)
            ->where('conta_tipo', $contaTipo)
            ->where('conta_responsavel', $cliente)
            ->where('conta_num_conta', $contaNum)
            ->first();

        }

        if($appOrigem == 'CCT'){

            //Soma o valor total a ser pago das contas correntes
            $totalRecebimento = DB::table('financeiro_recebimento_contas_correntes')
            ->selectRaw("
                SUM(
                    CASE 
                        WHEN reccct_tcc = 'AC' THEN reccct_valor_rec
                        WHEN reccct_tcc IN ('AF', 'DC') THEN reccct_valor_rec + reccct_acrescimo
                        WHEN reccct_tcc = 'RD' THEN (reccct_valor_rec + reccct_acrescimo) - reccct_valor_iss - reccct_valor_irrf - reccct_desp_banc
                        ELSE 0
                    END
                ) as total_recebimentos
            ")
            ->where('reccct_emp', $empresa)
            ->where('reccct_cod_rec', $idRecebimento)
            ->value('total_recebimentos');

            $totalCCT = DB::table('financeiro_recebimento_contas_correntes')->where('reccct_cod_rec', $idRecebimento)->where('reccct_emp', $empresa)->count();
            $valorRec = DB::table('financeiro_recebimento_valores')->where('recval_cod_rec', $idRecebimento)->where('recval_emp', $empresa)->sum('recval_valor');

            $saldoRest = $totalRecebimento - $valorRec;

            if($saldoRest < 0){
                $saldoRest = 0;
            }

            //Busca os dados dos valores recebidos
            $dadosRec = DB::table('financeiro_recebimento_valores')->where('recval_emp',$empresa)->where('recval_cod_rec',$idRecebimento)->orderby('recval_seq','asc')->get();

            //Busca os dados das contas correntes para recebimento
            $dados = DB::table('financeiro_recebimento_contas_correntes')
            ->where('reccct_emp', $empresa)
            ->where('reccct_cod_rec', $idRecebimento)
            ->get();

            return view('/financeiro/recebimento/fin_pnl001_PainelCCT', [
                'clienteREC' => $cliente,
                'empresaREC' => $empresa,
                'idRecebimento' => $idRecebimento,
                'estagio_app' => $estagio,
                'subEstagioRec' => 'NEW',
                'contasRecebidas' => $dados,
                'dadosRecebimento' => $dadosRec,
                'valorTotalRec' => $totalRecebimento,
                'saldoRestante' => $saldoRest,
                'totalCCT' => $totalCCT,
                'tipoCctSel' => $contaTipo,
                'contaCctSel' => $contaNum,
                'dadosCCT' => $dadosCCT
            ]);

        }elseif($appOrigem == 'NFV'){

            $where_semi = Session::get('glo_emissao_nf_where_semi');

            $valTotNF = DB::table('financeiro_recebimento_notas')->where('recnf_cod_rec', $idRecebimento)->where('recnf_emp', $empresa)->sum('recnf_vlr_sin');
            $valRec = DB::table('financeiro_recebimento_valores')->where('recval_cod_rec', $idRecebimento)->where('recval_emp', $empresa)->sum('recval_valor');

            $saldoRest = $valTotNF - $valRec;

            if($saldoRest < 0){
                $saldoRest = 0;
            }

            $totalNF = DB::table('financeiro_recebimento_notas')->where('recnf_cod_rec', $idRecebimento)->where('recnf_emp', $empresa)->count();
            $dados = DB::select("select * from vi_faturamento_pnl001_sel_notas where nfhdr_sts = 'A' and nfhdr_ori in('01') ".$where_semi." and nfhdr_emp = '".$empresa."' and nfhdr_cli = '".$cliente."' order by nfhdr_num_ped asc");

            return view('/faturamento/notas/fat_pnl001_EmissaoNF',[
                'empresaREC' => $empresa, 
                'clienteREC' => $cliente, 
                'idRecebimento' => $idRecebimento, 
                'dadosCliente' => $dados,
                'estagio_app' => $estagio,
                'subEstagioRec' => 'NEW',
                'valorTotalRec' => $valTotNF,
                'saldoRestante' => $saldoRest,
                'totalNF' => $totalNF,
                'tipoCctSel' => $contaTipo,
                'contaCctSel' => $contaNum,
                'dadosCCT' => $dadosCCT
            ]);
            
        }elseif($appOrigem == 'DUP'){

            //Soma o valor total a ser recebido das contas correntes
            $totalRecebimento = DB::table('financeiro_recebimento_duplicatas')
            ->selectRaw("
                SUM(
                    CASE 
                        WHEN recdup_tip_opr = 'M' THEN recdup_vlr_bxa + recdup_vlr_jmt
                        WHEN recdup_tip_opr IN ('F', 'R') THEN recdup_vlr_bxa - recdup_vlr_des
                        ELSE 0
                    END
                ) as total_recebimentos
            ")
            ->where('recdup_emp', $empresa)
            ->where('recdup_cod_rec', $idRecebimento)
            ->value('total_recebimentos');
            $valRec = DB::table('financeiro_recebimento_valores')->where('recval_cod_rec', $idRecebimento)->where('recval_emp', $empresa)->sum('recval_valor');

            $saldoRest = $totalRecebimento - $valRec;

            if($saldoRest < 0){
                $saldoRest = 0;
            }

            $totalDUP = DB::table('financeiro_recebimento_duplicatas')->where('recdup_cod_rec', $idRecebimento)->where('recdup_emp', $empresa)->count();
            $dados = DB::table('financeiro_recebimento_duplicatas')
            ->where('recdup_emp', $empresa)
            ->where('recdup_cod_rec', $idRecebimento)
            ->get();

            return view('/financeiro/recebimento/fin_pnl002_PainelDUP',[
                'empresaREC' => $empresa, 
                'clienteREC' => $cliente, 
                'idRecebimento' => $idRecebimento, 
                'dupSelecionadas' => $dados,
                'estagio_app' => $estagio,
                'subEstagioRec' => 'NEW',
                'valorTotalRec' => $totalRecebimento,
                'saldoRestante' => $saldoRest,
                'totalDUP' => $totalDUP,
                'tipoCctSel' => $contaTipo,
                'contaCctSel' => $contaNum,
                'dadosCCT' => $dadosCCT
            ]);

        }elseif($appOrigem == 'OUT'){

            //Soma o valor total a ser recebido
            $totalRecebimento = DB::table('financeiro_recebimento_outros')
            ->where('recout_emp', $empresa)
            ->where('recout_cod_rec', $idRecebimento)
            ->value('recout_vlr');

            $valRec = DB::table('financeiro_recebimento_valores')->where('recval_cod_rec', $idRecebimento)->where('recval_emp', $empresa)->sum('recval_valor');

            $saldoRest = $totalRecebimento - $valRec;

            if($saldoRest < 0){
                $saldoRest = 0;
            }

            $totalOUT = 1;

            $dados = DB::table('financeiro_recebimento_outros')
            ->where('recout_emp', $empresa)
            ->where('recout_cod_rec', $idRecebimento)
            ->first();

            return view('/financeiro/recebimento/fin_pnl003_PainelOUT',[
                'empresaREC' => $empresa, 
                'clienteREC' => $cliente, 
                'idRecebimento' => $idRecebimento, 
                'dadosOUT' => $dados,
                'estagio_app' => $estagio,
                'subEstagioRec' => 'NEW',
                'valorTotalRec' => $totalRecebimento,
                'saldoRestante' => $saldoRest,
                'totalOUT' => $totalOUT,
                'tipoCctSel' => $contaTipo,
                'contaCctSel' => $contaNum,
                'dadosCCT' => $dadosCCT
            ]);
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a Inclusão do novo Valor de Recebimento
    |----------------------------------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        //Valores escondidos no request
        $tipoValor = $request->tipoValor;
        
        $idRecebimento = Session::get('glo_recebimento_id');
        $empresa = Session::get('glo_recebimento_empresa');
        $cliente = Session::get('glo_recebimento_cliente');
        $appOrigem = Session::get('glo_recebimento_origem');

        //Pega a sequencia para o recebimento
        $seq = DB::table('financeiro_recebimento_valores')->where('recval_cod_rec', $idRecebimento)->where('recval_emp', $empresa)->max('recval_seq');
        $seq += 1;

        if($tipoValor == 'DIN'){

            $valor = Helper::limpaValorMonetario($request->valDin);

            $dados = [
                'recval_emp' => $empresa,
                'recval_cod_rec' => $idRecebimento,
                'recval_seq' => $seq,
                'recval_tipo' => 'DIN',
                'recval_valor' => $valor,
                'recval_obs' => $request->observacao,    
            ];

            $msg = 'Recebimento em Dinheiro incluído com sucesso!';

        }elseif($tipoValor == 'PIX'){

            $valor = Helper::limpaValorMonetario($request->valPIX);

            $dados = [
                'recval_emp' => $empresa,
                'recval_cod_rec' => $idRecebimento,
                'recval_seq' => $seq,
                'recval_tipo' => 'PIX',
                'recval_valor' => $valor,
                'recval_obs' => $request->observacao,  
                'recval_pix_bco' => $request->codBcoPIX,  
                'recval_pix_doc' => $request->numDocPIX,  
            ];

            $msg = 'Recebimento em PIX incluído com sucesso!';

        }elseif($tipoValor == 'CHQ'){

            $valor = Helper::limpaValorMonetario($request->valCHQ);

            $data = Helper::limpaData($request->dataVctCHQ);

            $dados = [
                'recval_emp' => $empresa,
                'recval_cod_rec' => $idRecebimento,
                'recval_seq' => $seq,
                'recval_tipo' => 'CHQ',
                'recval_valor' => $valor,
                'recval_obs' => $request->observacao,   
                'recval_ch_bco' => $request->codBcoCHQ,
                'recval_ch_age' => $request->numAgeCHQ,
                'recval_ch_age_dv' => $request->numAgeDvCHQ,
                'recval_ch_ccr' => $request->numCcrCHQ,
                'recval_ch_ccr_dv' => $request->numCcrDvCHQ,
                'recval_ch_num' => $request->numCHQ,
                'recval_ch_vct' => $data,
                'recval_ch_res' => $request->resCHQ,
            ];

            $msg = 'Recebimento em Cheque incluído com sucesso!';

        }elseif($tipoValor == 'CCR'){

            $valor = Helper::limpaValorMonetario($request->valCCR);

            $dados = [
                'recval_emp' => $empresa,
                'recval_cod_rec' => $idRecebimento,
                'recval_seq' => $seq,
                'recval_tipo' => 'CCR',
                'recval_valor' => $valor,
                'recval_comp' => $request->compCCR,
                'recval_obs' => $request->observacao,   
                'recval_crt_adm' => $request->admCCR,
                'recval_crt_ban' => $request->bandeiraCCR,
                'recval_crt_com' => $request->numDocCCR,
                'recval_crt_prc' => $request->parcelaCCR,
            ];

            $msg = 'Recebimento em Cartão de Crédito incluído com sucesso!';

        }elseif($tipoValor == 'CDB'){

            $valor = Helper::limpaValorMonetario($request->valCDB);

            $dados = [
                'recval_emp' => $empresa,
                'recval_cod_rec' => $idRecebimento,
                'recval_seq' => $seq,
                'recval_tipo' => 'CDB',
                'recval_valor' => $valor,
                'recval_comp' => $request->compCDB,
                'recval_obs' => $request->observacao,   
                'recval_crt_adm' => $request->admCDB,
                'recval_crt_ban' => $request->bandeiraCDB,
                'recval_crt_com' => $request->numDocCDB,
            ];

            $msg = 'Recebimento em Cartão de Débito incluído com sucesso!';

        }elseif($tipoValor == 'CCT'){

            $valor = Helper::limpaValorMonetario($request->valCCT);

            if($valor > $request->valTotCCT){

                return redirect()->back()->with('info', 'O valor a receber não pode ser maior que o saldo da conta!');
            }

            $dados = [
                'recval_emp' => $empresa,
                'recval_cod_rec' => $idRecebimento,
                'recval_seq' => $seq,
                'recval_tipo' => 'CCT',
                'recval_valor' => $valor,
                'recval_obs' => $request->observacao,
                'recval_cct_tcc' => $request->tipoCCT,
                'recval_cct_res' => $request->resCCT,   
                'recval_cct_ncc' => $request->numCCT,
            ];

            $msg = 'Recebimento com Conta Corrente incluída com sucesso!';

        }

        FinanceiroRecebimentoValores::create($dados);

        return redirect(route('recebimento.painelRecebimento', [
            'empresa' => $empresa, 
            'cliente' => $cliente,
            'idRecebimento' => $idRecebimento
        ]))->with('success', $msg);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Exibe a Lista de Contas Correntes
    |----------------------------------------------------------------------------------------------------
    */
    public function show($tipoValor, Request $request)
    {
        $idRecebimento = Session::get('glo_recebimento_id');
        $empresa = Session::get('glo_recebimento_empresa');
        $cliente = Session::get('glo_recebimento_cliente');
        $appOrigem = Session::get('glo_recebimento_origem');

        if($tipoValor == 'CCT'){

            $etapa = $request->etapa;

            if($etapa == 'SELECAO'){

                $estagio = 'REC_SEL_C_CORRENTE';
                $where = "";

                if(!empty($request->tipoCCTFiltro)){

                    $where .= " AND conta_tipo = '".$request->tipoCCTFiltro."' ";
                }

                if(!empty($request->numCCTFiltro)){
                    
                    $where .= " AND conta_num_conta = '".$request->numCCTFiltro."' ";
                }

                //Data de Inicio / Final
                if(!empty($request->dtIniFiltro) && !empty($request->dtFinFiltro)){

                    $dt_ini = Helper::limpaData($request->dtIniFiltro);
                    $dt_fin = Helper::limpaData($request->dtFinFiltro);

                    if($dt_ini > $dt_fin){
                        return redirect()->back()->with('error', 'A Data Inicial não pode ser maior que a Data Final!');
                    }

                    $where .= " AND conta_dt_vencimento between '".$dt_ini."' and '".$dt_fin."' ";

                }elseif(empty($request->dtIniFiltro) && !empty($request->dtFinFiltro)){

                    $dt_fin = Helper::limpaData($request->dtFinFiltro);
                    
                    $where .= " AND conta_dt_vencimento <= '".$dt_fin."' ";

                }elseif(!empty($request->dtIniFiltro) && empty($request->dtFinFiltro)){

                    $dt_ini = Helper::limpaData($request->dtIniFiltro);
                    
                    $where .= " AND conta_dt_vencimento >= '".$dt_ini."' ";
                }

                //Valor de Inicio / Final
                if(!empty($request->vlrIniFiltro) && !empty($request->vlrFinFiltro)){

                    $vlr_ini = Helper::limpaValorMonetario($request->vlrIniFiltro);
                    $vlr_fin = Helper::limpaValorMonetario($request->vlrFinFiltro);

                    if($vlr_ini > $vlr_fin){
                        return redirect()->back()->with('error', 'O Valor Inicial não pode ser maior que o Valor Final!');
                    }

                    $where .= " AND conta_valor between ".$vlr_ini." and ".$vlr_fin;
                    
                }elseif(empty($request->vlrIniFiltro) && !empty($request->vlrFinFiltro)){

                    $vlr_fin = Helper::limpaValorMonetario($request->vlrFinFiltro);

                    $where .= " AND conta_valor <= ".$vlr_fin;
                    
                }elseif(!empty($request->vlrIniFiltro) && empty($request->vlrFinFiltro)){

                    $vlr_ini = Helper::limpaValorMonetario($request->vlrIniFiltro);

                    $where .= " AND conta_valor >= ".$vlr_ini;                    
                }

                Session::put('glo_recebimento_filtro_cct_where', $where);

            }elseif($etapa == 'SELECAO_VOLTAR'){

                $estagio = 'REC_SEL_C_CORRENTE';
                $where = Session::get('glo_recebimento_filtro_cct_where');

            }else{
                $estagio = 'REC_FIL_C_CORRENTE';
                $where = "";
                Session::put('glo_recebimento_filtro_cct_where', '');
            }

            if($appOrigem == 'CCT'){

                //Soma o valor total a ser recebido das contas correntes
                $totalRecebimento = DB::table('financeiro_recebimento_contas_correntes')
                ->selectRaw("
                    SUM(
                        CASE 
                            WHEN reccct_tcc = 'AC' THEN reccct_valor_rec
                            WHEN reccct_tcc IN ('AF', 'DC') THEN reccct_valor_rec + reccct_acrescimo
                            WHEN reccct_tcc = 'RD' THEN (reccct_valor_rec + reccct_acrescimo) - reccct_valor_iss - reccct_valor_irrf - reccct_desp_banc
                            ELSE 0
                        END
                    ) as total_recebimentos
                ")
                ->where('reccct_emp', $empresa)
                ->where('reccct_cod_rec', $idRecebimento)
                ->value('total_recebimentos');

                $totalCCT = DB::table('financeiro_recebimento_contas_correntes')->where('reccct_cod_rec', $idRecebimento)->where('reccct_emp', $empresa)->count();
                $valorRec = DB::table('financeiro_recebimento_valores')->where('recval_cod_rec', $idRecebimento)->where('recval_emp', $empresa)->sum('recval_valor');

                $saldoRest = $totalRecebimento - $valorRec;

                if($saldoRest < 0){
                    $saldoRest = 0;
                }

                //Busca os dados das contas correntes para recebimento
                $dados = DB::table('financeiro_recebimento_contas_correntes')
                ->where('reccct_emp', $empresa)
                ->where('reccct_cod_rec', $idRecebimento)
                ->get();

                if($etapa == 'SELECAO' || $etapa == 'SELECAO_VOLTAR'){

                    //Busca contas correntes para recebimento
                    $sql = "select 
                                (conta_valor - conta_val_rec) as conta_saldo,
                                conta_num_conta,
                                conta_tipo,
                                conta_responsavel,
                                conta_dt_vencimento
                            from financeiro_contas_correntes
                            where
                                conta_empresa = '".$empresa."' and 
                                conta_responsavel = '".$cliente."' and 
                                conta_situacao not in('L','C') and 
                                not exists (select recval_cct_ncc from financeiro_recebimento_valores where 
                                            recval_cct_ncc = conta_num_conta and 
                                            recval_cct_tcc = conta_tipo and 
                                            recval_cct_res = conta_responsavel and 
                                            recval_emp = conta_empresa and 
                                            recval_cod_rec = ".$idRecebimento." and
                                            recval_tipo = 'CCT' ) 
                                ".$where." 
                            order by conta_tipo, conta_num_conta";
                    $dadosSel = DB::select($sql);

                }else{

                    $dadosSel = '';
                }

                return view('/financeiro/recebimento/fin_pnl001_PainelCCT', [
                    'clienteREC' => $cliente,
                    'empresaREC' => $empresa,
                    'idRecebimento' => $idRecebimento,
                    'estagio_app' => $estagio,
                    'contasRecebidas' => $dados,
                    'valorTotalRec' => $totalRecebimento,
                    'saldoRestante' => $saldoRest,
                    'totalCCT' => $totalCCT,
                    'contasSel' => $dadosSel
                ]);

            }elseif($appOrigem == 'NFV'){

                $where_semi = Session::get('glo_emissao_nf_where_semi');

                $valTotNF = DB::table('financeiro_recebimento_notas')->where('recnf_cod_rec', $idRecebimento)->where('recnf_emp', $empresa)->sum('recnf_vlr_sin');
                $valRec = DB::table('financeiro_recebimento_valores')->where('recval_cod_rec', $idRecebimento)->where('recval_emp', $empresa)->sum('recval_valor');

                $saldoRest = $valTotNF - $valRec;

                if($saldoRest < 0){
                    $saldoRest = 0;
                }

                $totalNF = DB::table('financeiro_recebimento_notas')->where('recnf_cod_rec', $idRecebimento)->where('recnf_emp', $empresa)->count();
                $dados = DB::select("select * from vi_faturamento_pnl001_sel_notas where nfhdr_sts = 'A' and nfhdr_ori in('01') ".$where_semi." and nfhdr_emp = '".$empresa."' and nfhdr_cli = '".$cliente."' order by nfhdr_num_ped asc");

                if($etapa == 'SELECAO' || $etapa == 'SELECAO_VOLTAR'){

                    //Busca contas correntes para recebimento
                    $sql = "select 
                                (conta_valor - conta_val_rec) as conta_saldo,
                                conta_num_conta,
                                conta_tipo,
                                conta_responsavel,
                                conta_dt_vencimento
                            from financeiro_contas_correntes
                            where
                                conta_empresa = '".$empresa."' and 
                                conta_responsavel = '".$cliente."' and 
                                conta_situacao not in('L','C') and 
                                not exists (select recval_cct_ncc from financeiro_recebimento_valores where 
                                            recval_cct_ncc = conta_num_conta and 
                                            recval_cct_tcc = conta_tipo and 
                                            recval_cct_res = conta_responsavel and 
                                            recval_emp = conta_empresa and 
                                            recval_cod_rec = ".$idRecebimento." and
                                            recval_tipo = 'CCT' ) 
                                ".$where." 
                            order by conta_tipo, conta_num_conta";
                    $dadosSel = DB::select($sql);

                }else{

                    $dadosSel = '';
                }

                return view('/faturamento/notas/fat_pnl001_EmissaoNF',[
                    'empresaREC' => $empresa, 
                    'clienteREC' => $cliente, 
                    'idRecebimento' => $idRecebimento, 
                    'estagio_app' => $estagio,
                    'dadosCliente' => $dados,
                    'valorTotalRec' => $valTotNF,
                    'saldoRestante' => $saldoRest,
                    'totalNF' => $totalNF,
                    'contasSel' => $dadosSel
                ]);

            }elseif($appOrigem == 'DUP'){

                //Soma o valor total a ser recebido das contas correntes
                $totalRecebimento = DB::table('financeiro_recebimento_duplicatas')
                ->selectRaw("
                    SUM(
                        CASE 
                            WHEN recdup_tip_opr = 'M' THEN recdup_vlr_bxa + recdup_vlr_jmt
                            WHEN recdup_tip_opr IN ('F', 'R') THEN recdup_vlr_bxa - recdup_vlr_des
                            ELSE 0
                        END
                    ) as total_recebimentos
                ")
                ->where('recdup_emp', $empresa)
                ->where('recdup_cod_rec', $idRecebimento)
                ->value('total_recebimentos');
                $valRec = DB::table('financeiro_recebimento_valores')->where('recval_cod_rec', $idRecebimento)->where('recval_emp', $empresa)->sum('recval_valor');

                $saldoRest = $totalRecebimento - $valRec;

                if($saldoRest < 0){
                    $saldoRest = 0;
                }

                $totalDUP = DB::table('financeiro_recebimento_duplicatas')->where('recdup_cod_rec', $idRecebimento)->where('recdup_emp', $empresa)->count();
                $dados = DB::table('financeiro_recebimento_duplicatas')
                ->where('recdup_emp', $empresa)
                ->where('recdup_cod_rec', $idRecebimento)
                ->get();

                if($etapa == 'SELECAO' || $etapa == 'SELECAO_VOLTAR'){

                    //Busca contas correntes para recebimento
                    $sql = "select 
                                (conta_valor - conta_val_rec) as conta_saldo,
                                conta_num_conta,
                                conta_tipo,
                                conta_responsavel,
                                conta_dt_vencimento
                            from financeiro_contas_correntes
                            where
                                conta_empresa = '".$empresa."' and 
                                conta_responsavel = '".$cliente."' and 
                                conta_situacao not in('L','C') and 
                                not exists (select recval_cct_ncc from financeiro_recebimento_valores where 
                                            recval_cct_ncc = conta_num_conta and 
                                            recval_cct_tcc = conta_tipo and 
                                            recval_cct_res = conta_responsavel and 
                                            recval_emp = conta_empresa and 
                                            recval_cod_rec = ".$idRecebimento." and
                                            recval_tipo = 'CCT' ) 
                                ".$where." 
                            order by conta_tipo, conta_num_conta";
                    $dadosSel = DB::select($sql);

                }else{

                    $dadosSel = '';
                }

                return view('/financeiro/recebimento/fin_pnl002_PainelDUP',[
                    'empresaREC' => $empresa, 
                    'clienteREC' => $cliente, 
                    'idRecebimento' => $idRecebimento, 
                    'estagio_app' => $estagio,
                    'dupSelecionadas' => $dados,
                    'valorTotalRec' => $totalRecebimento,
                    'saldoRestante' => $saldoRest,
                    'totalDUP' => $totalDUP,
                    'contasSel' => $dadosSel
                ]);

            }elseif($appOrigem == 'OUT'){

                //Soma o valor total a ser recebido
                $totalRecebimento = DB::table('financeiro_recebimento_outros')
                ->where('recout_emp', $empresa)
                ->where('recout_cod_rec', $idRecebimento)
                ->value('recout_vlr');

                $valRec = DB::table('financeiro_recebimento_valores')->where('recval_cod_rec', $idRecebimento)->where('recval_emp', $empresa)->sum('recval_valor');

                $saldoRest = $totalRecebimento - $valRec;

                if($saldoRest < 0){
                    $saldoRest = 0;
                }

                $totalOUT = 1;

                $dados = DB::table('financeiro_recebimento_outros')
                ->where('recout_emp', $empresa)
                ->where('recout_cod_rec', $idRecebimento)
                ->first();

                if($etapa == 'SELECAO' || $etapa == 'SELECAO_VOLTAR'){

                    //Busca contas correntes para recebimento
                    $sql = "select 
                                (conta_valor - conta_val_rec) as conta_saldo,
                                conta_num_conta,
                                conta_tipo,
                                conta_responsavel,
                                conta_dt_vencimento
                            from financeiro_contas_correntes
                            where
                                conta_empresa = '".$empresa."' and 
                                conta_responsavel = '".$cliente."' and 
                                conta_situacao not in('L','C') and 
                                not exists (select recval_cct_ncc from financeiro_recebimento_valores where 
                                            recval_cct_ncc = conta_num_conta and 
                                            recval_cct_tcc = conta_tipo and 
                                            recval_cct_res = conta_responsavel and 
                                            recval_emp = conta_empresa and 
                                            recval_cod_rec = ".$idRecebimento." and
                                            recval_tipo = 'CCT' ) 
                                ".$where." 
                            order by conta_tipo, conta_num_conta";
                    $dadosSel = DB::select($sql);

                }else{

                    $dadosSel = '';
                }

                return view('/financeiro/recebimento/fin_pnl003_PainelOUT',[
                    'empresaREC' => $empresa, 
                    'clienteREC' => $cliente, 
                    'idRecebimento' => $idRecebimento, 
                    'estagio_app' => $estagio,
                    'dadosOUT' => $dados,
                    'valorTotalRec' => $totalRecebimento,
                    'saldoRestante' => $saldoRest,
                    'totalOUT' => $totalOUT,
                    'contasSel' => $dadosSel
                ]);
            }
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP de Manutenção do Valor do Recebimento
    |----------------------------------------------------------------------------------------------------
    */
    public function edit($valorRecebimento, Request $request)
    {

        $idRecebimento = Session::get('glo_recebimento_id');
        $empresa = Session::get('glo_recebimento_empresa');
        $cliente = Session::get('glo_recebimento_cliente');
        $appOrigem = Session::get('glo_recebimento_origem');

        $tipoRec = $request->tipoRec;
        
        if($tipoRec == 'DIN'){
            $estagioApp = "REC_DINHEIRO";
        }elseif($tipoRec == 'PIX'){
            $estagioApp = "REC_PIX";
        }elseif($tipoRec == 'CHQ'){
            $estagioApp = "REC_CHEQUE";
        }elseif($tipoRec == 'CCR'){
            $estagioApp = "REC_C_CREDITO";
        }elseif($tipoRec == 'CDB'){
            $estagioApp = "REC_C_DEBITO";
        }elseif($tipoRec == 'CCT'){
            $estagioApp = "REC_CONTA_CORRENTE";
        }

        $dadosValRec = $this->valRec->where('recval_cod_rec', $idRecebimento)->where('recval_emp', $empresa)->where('recval_seq', $valorRecebimento)->first();

        if($tipoRec == 'CCT'){
            $contaTipo =  $dadosValRec->recval_cct_tcc;
            $contaNum =  $dadosValRec->recval_cct_ncc;

            $dadosCCT = DB::table('financeiro_contas_correntes')
            ->select(
                DB::raw('(conta_valor - conta_val_rec) as conta_saldo'),
                'conta_responsavel',
                'conta_tipo',
                'conta_num_conta',
                'conta_dt_vencimento'
            )
            ->where('conta_empresa', $empresa)
            ->where('conta_tipo', $contaTipo)
            ->where('conta_responsavel', $cliente)
            ->where('conta_num_conta', $contaNum)
            ->first();

        }else{

            $contaTipo = '';
            $contaNum = '';
            $dadosCCT = '';
        }

        if($appOrigem == 'CCT'){

            //Soma o valor total a ser recebido das contas correntes
            $totalRecebimento = DB::table('financeiro_recebimento_contas_correntes')
            ->selectRaw("
                SUM(
                    CASE 
                        WHEN reccct_tcc = 'AC' THEN reccct_valor_rec
                        WHEN reccct_tcc IN ('AF', 'DC') THEN reccct_valor_rec + reccct_acrescimo
                        WHEN reccct_tcc = 'RD' THEN (reccct_valor_rec + reccct_acrescimo) - reccct_valor_iss - reccct_valor_irrf - reccct_desp_banc
                        ELSE 0
                    END
                ) as total_recebimentos
            ")
            ->where('reccct_emp', $empresa)
            ->where('reccct_cod_rec', $idRecebimento)
            ->value('total_recebimentos');

            $totalCCT = DB::table('financeiro_recebimento_contas_correntes')->where('reccct_cod_rec', $idRecebimento)->where('reccct_emp', $empresa)->count();
            $valorRec = DB::table('financeiro_recebimento_valores')->where('recval_cod_rec', $idRecebimento)->where('recval_emp', $empresa)->sum('recval_valor');

            $saldoRest = $totalRecebimento - $valorRec;

            if($saldoRest < 0){
                $saldoRest = 0;
            }

            //Busca os dados das contas correntes para recebimento
            $dados = DB::table('financeiro_recebimento_contas_correntes')
            ->where('reccct_emp', $empresa)
            ->where('reccct_cod_rec', $idRecebimento)
            ->get();

            return view('/financeiro/recebimento/fin_pnl001_PainelCCT', [
                'clienteREC' => $cliente,
                'empresaREC' => $empresa,
                'idRecebimento' => $idRecebimento,
                'dadosValRec' => $dadosValRec,
                'estagio_app' => $estagioApp,
                'subEstagioRec' => 'EDIT',
                'contasRecebidas' => $dados,
                'valorTotalRec' => $totalRecebimento,
                'saldoRestante' => $saldoRest,
                'totalCCT' => $totalCCT,
                'tipoCctSel' => $contaTipo,
                'contaCctSel' => $contaNum,
                'dadosCCT' => $dadosCCT
            ]);

        }elseif($appOrigem == 'NFV'){

            $where_semi = Session::get('glo_emissao_nf_where_semi');

            $valTotNF = DB::table('financeiro_recebimento_notas')->where('recnf_cod_rec', $idRecebimento)->where('recnf_emp', $empresa)->sum('recnf_vlr_sin');
            $valRec = DB::table('financeiro_recebimento_valores')->where('recval_cod_rec', $idRecebimento)->where('recval_emp', $empresa)->sum('recval_valor');

            $saldoRest = $valTotNF - $valRec;

            if($saldoRest < 0){
                $saldoRest = 0;
            }

            $totalNF = DB::table('financeiro_recebimento_notas')->where('recnf_cod_rec', $idRecebimento)->where('recnf_emp', $empresa)->count();
            $dados = DB::select("select * from vi_faturamento_pnl001_sel_notas where nfhdr_sts = 'A' and nfhdr_ori in('01') ".$where_semi." and nfhdr_emp = '".$empresa."' and nfhdr_cli = '".$cliente."' order by nfhdr_num_ped asc");

            return view('/faturamento/notas/fat_pnl001_EmissaoNF',[
                'empresaREC' => $empresa, 
                'clienteREC' => $cliente, 
                'idRecebimento' => $idRecebimento, 
                'dadosValRec' => $dadosValRec,
                'estagio_app' => $estagioApp,
                'subEstagioRec' => 'EDIT',
                'dadosCliente' => $dados,
                'valorTotalRec' => $valTotNF,
                'saldoRestante' => $saldoRest,
                'totalNF' => $totalNF,
                'tipoCctSel' => $contaTipo,
                'contaCctSel' => $contaNum,
                'dadosCCT' => $dadosCCT
            ]);
        }elseif($appOrigem == 'DUP'){

            $totalRecebimento = DB::table('financeiro_recebimento_duplicatas')
            ->selectRaw("
                SUM(
                    CASE 
                        WHEN recdup_tip_opr = 'M' THEN recdup_vlr_bxa + recdup_vlr_jmt
                        WHEN recdup_tip_opr IN ('F', 'R') THEN recdup_vlr_bxa - recdup_vlr_des
                        ELSE 0
                    END
                ) as total_recebimentos
            ")
            ->where('recdup_emp', $empresa)
            ->where('recdup_cod_rec', $idRecebimento)
            ->value('total_recebimentos');
            $valRec = DB::table('financeiro_recebimento_valores')->where('recval_cod_rec', $idRecebimento)->where('recval_emp', $empresa)->sum('recval_valor');

            $saldoRest = $totalRecebimento - $valRec;

            if($saldoRest < 0){
                $saldoRest = 0;
            }

            $totalDUP = DB::table('financeiro_recebimento_duplicatas')->where('recdup_cod_rec', $idRecebimento)->where('recdup_emp', $empresa)->count();
            //Gera dados das duplicatas para seleção e os valores de recebimento informados
            $dados = DB::table('financeiro_recebimento_duplicatas')
            ->where('recdup_emp', $empresa)
            ->where('recdup_cod_rec', $idRecebimento)
            ->get();

            return view('/financeiro/recebimento/fin_pnl002_PainelDUP',[
                'empresaREC' => $empresa, 
                'clienteREC' => $cliente, 
                'idRecebimento' => $idRecebimento, 
                'dadosValRec' => $dadosValRec,
                'estagio_app' => $estagioApp,
                'subEstagioRec' => 'EDIT',
                'dupSelecionadas' => $dados,
                'valorTotalRec' => $totalRecebimento,
                'saldoRestante' => $saldoRest,
                'totalDUP' => $totalDUP,
                'tipoCctSel' => $contaTipo,
                'contaCctSel' => $contaNum,
                'dadosCCT' => $dadosCCT
            ]);

        }elseif($appOrigem == 'OUT'){

            $totalRecebimento = DB::table('financeiro_recebimento_outros')
            ->where('recout_emp', $empresa)
            ->where('recout_cod_rec', $idRecebimento)
            ->value('recout_vlr');

            $valRec = DB::table('financeiro_recebimento_valores')->where('recval_cod_rec', $idRecebimento)->where('recval_emp', $empresa)->sum('recval_valor');

            $saldoRest = $totalRecebimento - $valRec;

            if($saldoRest < 0){
                $saldoRest = 0;
            }

            $totalOUT = 1;

            //Gera dados para seleção e os valores de recebimento informados
            $dados = DB::table('financeiro_recebimento_outros')
            ->where('recout_emp', $empresa)
            ->where('recout_cod_rec', $idRecebimento)
            ->first();

            return view('/financeiro/recebimento/fin_pnl003_PainelOUT',[
                'empresaREC' => $empresa, 
                'clienteREC' => $cliente, 
                'idRecebimento' => $idRecebimento, 
                'dadosValRec' => $dadosValRec,
                'estagio_app' => $estagioApp,
                'subEstagioRec' => 'EDIT',
                'dadosOUT' => $dados,
                'valorTotalRec' => $totalRecebimento,
                'saldoRestante' => $saldoRest,
                'totalOUT' => $totalOUT,
                'tipoCctSel' => $contaTipo,
                'contaCctSel' => $contaNum,
                'dadosCCT' => $dadosCCT
            ]);
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza o Update dos dados do Valor do Recebimento
    |----------------------------------------------------------------------------------------------------
    */
    public function update(Request $request, FinanceiroRecebimentoValores $valorRecebimento)
    {
        //Valores escondidos no request
        $tipoValor = $request->tipoValor;

        $idRecebimento = Session::get('glo_recebimento_id');
        $empresa = Session::get('glo_recebimento_empresa');
        $cliente = Session::get('glo_recebimento_cliente');
        $appOrigem = Session::get('glo_recebimento_origem');

        if($tipoValor == 'DIN'){

            $valor = Helper::limpaValorMonetario($request->valDin);

            $valorRecebimento->update([
                'recval_valor' => $valor,
                'recval_obs' => $request->observacao,
            ]);

            $msg = 'Recebimento em Dinheiro alterado com sucesso!';

        }elseif($tipoValor == 'PIX'){

            $valor = Helper::limpaValorMonetario($request->valPIX);

            $valorRecebimento->update([
                'recval_valor' => $valor,
                'recval_obs' => $request->observacao,  
                'recval_pix_bco' => $request->codBcoPIX,  
                'recval_pix_doc' => $request->numDocPIX,  
            ]);

            $msg = 'Recebimento em PIX alterado com sucesso!';

        }elseif($tipoValor == 'CHQ'){

            $valor = Helper::limpaValorMonetario($request->valCHQ);

            $data = Helper::limpaData($request->dataVctCHQ);

            $valorRecebimento->update([
                'recval_valor' => $valor,
                'recval_obs' => $request->observacao,   
                'recval_ch_bco' => $request->codBcoCHQ,
                'recval_ch_age' => $request->numAgeCHQ,
                'recval_ch_age_dv' => $request->numAgeDvCHQ,
                'recval_ch_ccr' => $request->numCcrCHQ,
                'recval_ch_ccr_dv' => $request->numCcrDvCHQ,
                'recval_ch_num' => $request->numCHQ,
                'recval_ch_vct' => $data,
                'recval_ch_res' => $request->resCHQ, 
            ]);

            $msg = 'Recebimento em Cheque alterado com sucesso!';

        }elseif($tipoValor == 'CCR'){

            $valor = Helper::limpaValorMonetario($request->valCCR);

            $valorRecebimento->update([
                'recval_valor' => $valor,
                'recval_comp' => $request->compCCR,
                'recval_obs' => $request->observacao,   
                'recval_crt_adm' => $request->admCCR,
                'recval_crt_ban' => $request->bandeiraCCR,
                'recval_crt_com' => $request->numDocCCR,
                'recval_crt_prc' => $request->parcelaCCR,
            ]);

            $msg = 'Recebimento com Cartão de Crédito alterado com sucesso!';

        }elseif($tipoValor == 'CDB'){

            $valor = Helper::limpaValorMonetario($request->valCDB);

            $valorRecebimento->update([
                'recval_valor' => $valor,
                'recval_comp' => $request->compCDB,
                'recval_obs' => $request->observacao,   
                'recval_crt_adm' => $request->admCDB,
                'recval_crt_ban' => $request->bandeiraCDB,
                'recval_crt_com' => $request->numDocCDB,
            ]);

            $msg = 'Recebimento com Cartão de Débito alterado com sucesso!';

        }elseif($tipoValor == 'CCT'){

            $valor = Helper::limpaValorMonetario($request->valCCT);

            if($valor > $request->valTotCCT){

                return redirect()->back()->with('info', 'O valor a receber não pode ser maior que o saldo da conta!');
            }

            $valorRecebimento->update([
                'recval_valor' => $valor,
                'recval_obs' => $request->observacao,
                'recval_cct_tcc' => $request->tipoCCT,
                'recval_cct_res' => $request->resCCT,   
                'recval_cct_ncc' => $request->numCCT,
            ]);

            $msg = 'Recebimento em Conta Corrente alterada com sucesso!';
        }

        return redirect(route('recebimento.painelRecebimento', [
            'empresa' => $empresa, 
            'cliente' => $cliente,
            'idRecebimento' => $idRecebimento
        ]))->with('success', $msg);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza a Exclusão do Valor de Recebimento
    |----------------------------------------------------------------------------------------------------
    */
    public function destroy(FinanceiroRecebimentoValores $valorRecebimento)
    {
        //Valores globais
        $idRecebimento = Session::get('glo_recebimento_id');
        $empresa = Session::get('glo_recebimento_empresa');
        $cliente = Session::get('glo_recebimento_cliente');

        $valorRecebimento->delete();

        return redirect(route('recebimento.painelRecebimento', [
            'empresa' => $empresa, 
            'cliente' => $cliente,
            'idRecebimento' => $idRecebimento
        ]))->with('success', 'Valor de recebimento excluído com sucesso!');

    }
}
