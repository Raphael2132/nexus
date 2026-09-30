<?php

namespace App\Http\Controllers\Financeiro\Pagamento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use stdClass;
use App\Models\Financeiro\Pagamento\FinanceiroPagamentoValores;
use App\Http\Helpers\Helper;
use App\Http\Helpers\HelperDataSelect;

class FinanceiroPagamentoValorController extends Controller
{
    protected $valPag;
    
    public function __construct(FinanceiroPagamentoValores $valPag)
    {
        $this->valPag = $valPag;
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
    | Executa a APP de Inclusão de Novo Valor de Pagamento
    |----------------------------------------------------------------------------------------------------
    */
    public function create($appOrigem, Request $request)
    {

        $tipoValor = $request->query('tipoValor');

        $idPagamento = Session::get('glo_pagamento_id');
        $empresa = Session::get('glo_pagamento_empresa');
        $cliente = Session::get('glo_pagamento_cliente');

        $contaTipo = ''; 
        $contaNum = '';
        $dadosCCT = '';

        if($tipoValor == 'DIN'){

            $estagio = 'PAG_DINHEIRO';

            //Se for Dinheiro e ja existir esse tipo de valor pagado não permite continuar, esse tipo de valor não terá mais de um registro
            $existDin = DB::table('financeiro_pagamento_valores')->where('pagval_cod_pag', $idPagamento)->where('pagval_emp', $empresa)->where('pagval_tipo', 'DIN')->count();

            if($existDin > 0){
                return redirect()->back()->with('info', 'Já foi pago um valor em dinheiro! Por favor edite o pagamento em dinheiro existente para realizar alteração do valor.');
            }

        }elseif($tipoValor == 'PIX'){

            $estagio = 'PAG_PIX';

        }elseif($tipoValor == 'CCO'){

            $estagio = 'PAG_C_CORPORATIVO';

        }elseif($tipoValor == 'CHQ'){

            $estagio = 'PAG_CHEQUE';

        }elseif($tipoValor == 'CCT'){

            $estagio = 'PAG_CONTA_CORRENTE';

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

        if($appOrigem == 'LOTE' || $appOrigem == 'PAG_AF'){

            //Soma o valor total a ser pago das contas correntes
            $totalPag = DB::table('financeiro_pagamento_contas_correntes')
            ->selectRaw("
                SUM(pagcct_vlr_tot) as total_pag
            ")
            ->where('pagcct_emp', $empresa)
            ->where('pagcct_cod_pag', $idPagamento)
            ->value('total_pag');

            $valorPgt = DB::table('financeiro_pagamento_valores')->where('pagval_cod_pag', $idPagamento)->where('pagval_emp', $empresa)->sum('pagval_valor');

            $saldoRest = $totalPag - $valorPgt;

            $totalCC = DB::table('financeiro_pagamento_contas_correntes')->where('pagcct_cod_pag', $idPagamento)->where('pagcct_emp', $empresa)->count();

            //Busca os dados das contas do lote
            $dadosContasLote = DB::table('financeiro_pagamento_contas_correntes')
            ->where('pagcct_emp', $empresa)
            ->where('pagcct_cod_pag', $idPagamento)
            ->get();

            $dadosPagamento = DB::table('financeiro_pagamento_valores')
            ->where('pagval_emp', $empresa)
            ->where('pagval_cod_pag', $idPagamento)
            ->get();

            return view('/financeiro/pagamento/fin_pnl006_PainelLote', [
                'clienteLote' => $cliente,
                'empresaLote' => $empresa,
                'idPagamento' => $idPagamento,
                'estagio_app' => $estagio,
                'subEstagioPag' => 'NEW',
                'dadosContasLote' => $dadosContasLote,
                'dadosPagamento' => $dadosPagamento,
                'saldoRestante' => $saldoRest,
                'totalCC' => $totalCC,
                'valorTotalPag' => $totalPag,
                'tipoCctSel' => $contaTipo,
                'contaCctSel' => $contaNum,
                'dadosCCT' => $dadosCCT,
                'appOrigem' => $appOrigem
            ]);
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a Inclusão do novo Valor de Pagamento
    |----------------------------------------------------------------------------------------------------
    */
    public function store($appOrigem, Request $request)
    {
        //Valores escondidos no request
        $tipoValor = $request->tipoValor;

        $idPagamento = Session::get('glo_pagamento_id');
        $empresa = Session::get('glo_pagamento_empresa');
        $cliente = Session::get('glo_pagamento_cliente');
        $appOrigem = Session::get('glo_pagamento_origem');

        //Pega a sequencia para o pagamento
        $seq = DB::table('financeiro_pagamento_valores')->where('pagval_cod_pag', $idPagamento)->where('pagval_emp', $empresa)->max('pagval_seq');
        $seq += 1;

        if($tipoValor == 'DIN'){

            $valor = Helper::limpaValorMonetario($request->valDin);

            $dados = [
                'pagval_emp' => $empresa,
                'pagval_cod_pag' => $idPagamento,
                'pagval_seq' => $seq,
                'pagval_tipo' => 'DIN',
                'pagval_valor' => $valor,
                'pagval_obs' => $request->observacao,    
            ];

            $msg = 'Pagamento em Dinheiro incluído com sucesso!';

        }elseif($tipoValor == 'PIX'){

            $valor = Helper::limpaValorMonetario($request->valPIX);

            $dados = [
                'pagval_emp' => $empresa,
                'pagval_cod_pag' => $idPagamento,
                'pagval_seq' => $seq,
                'pagval_tipo' => 'PIX',
                'pagval_valor' => $valor,
                'pagval_comp' => $request->complemento,  
                'pagval_pix_bco' => $request->codBcoPIX,  
                'pagval_pix_doc' => $request->numDocPIX,  
            ];

            $msg = 'Pagamento em PIX incluído com sucesso!';

        }elseif($tipoValor == 'CHQ'){

            $valor = Helper::limpaValorMonetario($request->valCHQ);

            $dataVct = Helper::limpaData($request->dataVctCHQ);
            
            $ano = date('Y');
            $dadosRaz = HelperDataSelect::buscaDadosRazao($request->codBcoCHQ, 'BA', $ano);

            // Agência
            if (!empty($dadosRaz->razao_bco_age_dv)) {
                $ageDV = $dadosRaz->razao_bco_age_dv;
            }else{
                $ageDV = null;
            }

            // Conta Corrente
            if (!empty($dadosRaz->razao_bco_ncc_dv)) {
                $contaDV = $dadosRaz->razao_bco_ncc_dv;
            }else{
                $contaDV = null;
            }

            $dadosEmp = HelperDataSelect::buscaDadosEmpresa($dadosRaz->razao_empresa);
            $emitente = $dadosEmp->empresa_nome;

            $dados = [
                'pagval_emp' => $empresa,
                'pagval_cod_pag' => $idPagamento,
                'pagval_seq' => $seq,
                'pagval_tipo' => 'CHQ',
                'pagval_valor' => $valor,
                'pagval_obs' => $request->observacao,   
                'pagval_ch_raz' => $request->codBcoCHQ, 
                'pagval_ch_bco' => $dadosRaz->razao_bco_cod,
                'pagval_ch_age' => $dadosRaz->razao_bco_age,
                'pagval_ch_age_dv' => $ageDV,
                'pagval_ch_ccr' => $dadosRaz->razao_bco_ncc,
                'pagval_ch_ccr_dv' => $contaDV,
                'pagval_ch_num' => $request->numCHQ,
                'pagval_ch_vct' => $dataVct,
                'pagval_ch_res' => $emitente,
            ];
            
            $msg = 'Pagamento em Cheque incluído com sucesso!';

        }elseif($tipoValor == 'CCT'){

            $valor = Helper::limpaValorMonetario($request->valCCT);

            if($valor > $request->valTotCCT){

                return redirect()->back()->with('info', 'O valor a pagar não pode ser maior que o saldo da conta!');
            }

            $dados = [
                'pagval_emp' => $empresa,
                'pagval_cod_pag' => $idPagamento,
                'pagval_seq' => $seq,
                'pagval_tipo' => 'CCT',
                'pagval_valor' => $valor,
                'pagval_obs' => $request->observacao,
                'pagval_cct_tcc' => $request->tipoCCT,
                'pagval_cct_res' => $request->resCCT,   
                'pagval_cct_ncc' => $request->numCCT,
            ];

            $msg = 'Pagamento com Conta Corrente incluída com sucesso!';

        }elseif($tipoValor == 'CCO'){

            $valor = Helper::limpaValorMonetario($request->valCCO);

            $dados = [
                'pagval_emp' => $empresa,
                'pagval_cod_pag' => $idPagamento,
                'pagval_seq' => $seq,
                'pagval_tipo' => 'CCO',
                'pagval_valor' => $valor,
                'pagval_obs' => $request->observacao,   
                'pagval_crt_adm' => $request->admCCO,
                'pagval_crt_com' => $request->numCCO,
                'pagval_crt_prc' => $request->parcelaCCO,
            ];

            $msg = 'Recebimento em Cartão de Crédito incluído com sucesso!';

        }

        FinanceiroPagamentoValores::create($dados);

        return redirect(route('pagamentoPCC.pagarLote',['appOrigem' => $appOrigem]))->with('success', $msg);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Exibe a Lista de Contas Correntes
    |----------------------------------------------------------------------------------------------------
    */
    public function show($appOrigem, $tipoValor, Request $request)
    {
        
        $idPagamento = Session::get('glo_pagamento_id');
        $empresa = Session::get('glo_pagamento_empresa');
        $cliente = Session::get('glo_pagamento_cliente');

        if($tipoValor == 'CCT'){

            $etapa = $request->etapa;

            if($etapa == 'SELECAO'){

                $estagio = 'PAG_SEL_C_CORRENTE';
                $where = "";

                if($appOrigem == 'LOTE'){

                    if(!empty($request->tipoCCTFiltro)){

                        $where .= " AND conta_tipo = '".$request->tipoCCTFiltro."' ";
                    }else{

                        $where .= " AND conta_tipo in('AF','DC') ";
                    }
                    
                }else{

                    $where .= " AND conta_tipo = 'DC' ";
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

                Session::put('glo_pagamento_filtro_cct_where', $where);

            }elseif($etapa == 'SELECAO_VOLTAR'){

                $estagio = 'PAG_SEL_C_CORRENTE';
                $where = Session::get('glo_pagamento_filtro_cct_where');

            }else{
                $estagio = 'PAG_FIL_C_CORRENTE';
                $where = "";
                Session::put('glo_pagamento_filtro_cct_where', '');
            }

            if($appOrigem == 'LOTE' || $appOrigem == 'PAG_AF'){

                //Soma o valor total a ser pago das contas correntes
                $totalPag = DB::table('financeiro_pagamento_contas_correntes')
                ->selectRaw("
                    SUM(pagcct_vlr_tot) as total_pag
                ")
                ->where('pagcct_emp', $empresa)
                ->where('pagcct_cod_pag', $idPagamento)
                ->value('total_pag');

                $dadosValPag = DB::table('financeiro_pagamento_valores')->where('pagval_cod_pag', $idPagamento)->where('pagval_emp', $empresa)->get();

                $valorPgt = DB::table('financeiro_pagamento_valores')->where('pagval_cod_pag', $idPagamento)->where('pagval_emp', $empresa)->sum('pagval_valor');

                $saldoRest = $totalPag - $valorPgt;

                $totalCC = DB::table('financeiro_pagamento_contas_correntes')->where('pagcct_cod_pag', $idPagamento)->where('pagcct_emp', $empresa)->count();

                //Busca os dados das contas do lote
                $dadosContasLote = DB::table('financeiro_pagamento_contas_correntes')
                ->where('pagcct_emp', $empresa)
                ->where('pagcct_cod_pag', $idPagamento)
                ->get();

                if($etapa == 'SELECAO' || $etapa == 'SELECAO_VOLTAR'){

                    //Busca contas correntes para pagamento
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
                                not exists (select pagval_cct_ncc from financeiro_pagamento_valores where 
                                            pagval_cct_ncc = conta_num_conta and 
                                            pagval_cct_tcc = conta_tipo and 
                                            pagval_cct_res = conta_responsavel and 
                                            pagval_emp = conta_empresa and 
                                            pagval_cod_pag = ".$idPagamento." and
                                            pagval_tipo = 'CCT' ) 
                                ".$where." 
                            order by conta_tipo, conta_num_conta";
                    $dadosSel = DB::select($sql);

                }else{

                    $dadosSel = '';
                }

                return view('/financeiro/pagamento/fin_pnl006_PainelLote', [
                    'clienteLote' => $cliente,
                    'empresaLote' => $empresa,
                    'idPagamento' => $idPagamento,
                    'dadosValPag' => $dadosValPag,
                    'estagio_app' => $estagio,
                    'subEstagioPag' => 'EDIT',
                    'dadosContasLote' => $dadosContasLote,
                    'saldoRestante' => $saldoRest,
                    'totalCC' => $totalCC,
                    'valorTotalPag' => $totalPag,
                    'contasSel' => $dadosSel,
                    'appOrigem' => $appOrigem
                ]);

            }
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP de Manutenção do Valor do Pagamento
    |----------------------------------------------------------------------------------------------------
    */
    public function edit($appOrigem, $valorPagamento, Request $request)
    {
        $idPagamento = Session::get('glo_pagamento_id');
        $empresa = Session::get('glo_pagamento_empresa');
        $cliente = Session::get('glo_pagamento_cliente');

        $tipoPag = $request->tipoPag;

        if($tipoPag == 'DIN'){
            $estagioApp = "PAG_DINHEIRO";
        }elseif($tipoPag == 'PIX'){
            $estagioApp = "PAG_PIX";
        }elseif($tipoPag == 'CHQ'){
            $estagioApp = "PAG_CHEQUE";
        }elseif($tipoPag == 'CCO'){
            $estagioApp = "PAG_C_CORPORATIVO";
        }elseif($tipoPag == 'CCT'){
            $estagioApp = "PAG_CONTA_CORRENTE";
        }

        $dadosValPag = $this->valPag->where('pagval_cod_pag', $idPagamento)->where('pagval_emp', $empresa)->where('pagval_seq', $valorPagamento)->first();

        if($tipoPag == 'CCT'){
            $contaTipo =  $dadosValPag->pagval_cct_tcc;
            $contaNum =  $dadosValPag->pagval_cct_ncc;

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

        if($appOrigem == 'LOTE' || $appOrigem == 'PAG_AF'){

            //Soma o valor total a ser pago das contas correntes
            $totalPag = DB::table('financeiro_pagamento_contas_correntes')
            ->selectRaw("
                SUM(pagcct_vlr_tot) as total_pag
            ")
            ->where('pagcct_emp', $empresa)
            ->where('pagcct_cod_pag', $idPagamento)
            ->value('total_pag');

            $valorPgt = DB::table('financeiro_pagamento_valores')->where('pagval_cod_pag', $idPagamento)->where('pagval_emp', $empresa)->sum('pagval_valor');

            $saldoRest = $totalPag - $valorPgt;

            $totalCC = DB::table('financeiro_pagamento_contas_correntes')->where('pagcct_cod_pag', $idPagamento)->where('pagcct_emp', $empresa)->count();

            //Busca os dados das contas do lote
            $dadosContasLote = DB::table('financeiro_pagamento_contas_correntes')
            ->where('pagcct_emp', $empresa)
            ->where('pagcct_cod_pag', $idPagamento)
            ->get();

            return view('/financeiro/pagamento/fin_pnl006_PainelLote', [
                'clienteLote' => $cliente,
                'empresaLote' => $empresa,
                'idPagamento' => $idPagamento,
                'dadosValPag' => $dadosValPag,
                'estagio_app' => $estagioApp,
                'subEstagioPag' => 'EDIT',
                'dadosContasLote' => $dadosContasLote,
                'saldoRestante' => $saldoRest,
                'totalCC' => $totalCC,
                'valorTotalPag' => $totalPag,
                'tipoCctSel' => $contaTipo,
                'contaCctSel' => $contaNum,
                'dadosCCT' => $dadosCCT,
                'appOrigem' => $appOrigem
            ]);
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza o Update dos dados do Valor do Pagamento
    |----------------------------------------------------------------------------------------------------
    */
    public function update($appOrigem, Request $request, FinanceiroPagamentoValores $valorPagamento)
    {
        //Valores escondidos no request
        $tipoValor = $request->tipoValor;

        $idPagamento = Session::get('glo_pagamento_id');
        $empresa = Session::get('glo_pagamento_empresa');
        $cliente = Session::get('glo_pagamento_cliente');
        $appOrigem = Session::get('glo_pagamento_origem');

        if($tipoValor == 'DIN'){

            $valor = Helper::limpaValorMonetario($request->valDin);

            $valorPagamento->update([
                'pagval_valor' => $valor,
                'pagval_obs' => $request->observacao,
            ]);

            $msg = 'Pagamento em Dinheiro alterado com sucesso!';

        }elseif($tipoValor == 'PIX'){

            $valor = Helper::limpaValorMonetario($request->valPIX);

            $valorPagamento->update([
                'pagval_valor' => $valor,
                'pagval_comp' => $request->complemento,  
                'pagval_pix_bco' => $request->codBcoPIX,  
                'pagval_pix_doc' => $request->numDocPIX,  
            ]);

            $msg = 'Pagamento em PIX alterado com sucesso!';

        }elseif($tipoValor == 'CHQ'){

            $valor = Helper::limpaValorMonetario($request->valCHQ);

            $dataVct = Helper::limpaData($request->dataVctCHQ);

            $ano = date('Y');
            $dadosRaz = HelperDataSelect::buscaDadosRazao($request->codBcoCHQ, 'BA', $ano);

            // Agência
            if (!empty($dadosRaz->razao_bco_age_dv)) {
                $ageDV = $dadosRaz->razao_bco_age_dv;
            }else{
                $ageDV = null;
            }

            // Conta Corrente
            if (!empty($dadosRaz->razao_bco_ncc_dv)) {
                $contaDV = $dadosRaz->razao_bco_ncc_dv;
            }else{
                $contaDV = null;
            }

            $dadosEmp = HelperDataSelect::buscaDadosEmpresa($dadosRaz->razao_empresa);
            $emitente = $dadosEmp->empresa_nome;

            $valorPagamento->update([
                'pagval_valor' => $valor,
                'pagval_obs' => $request->observacao,   
                'pagval_ch_raz' => $request->codBcoCHQ,
                'pagval_ch_bco' => $dadosRaz->razao_bco_cod,
                'pagval_ch_age' => $dadosRaz->razao_bco_age,
                'pagval_ch_age_dv' => $ageDV,
                'pagval_ch_ccr' => $dadosRaz->razao_bco_ncc,
                'pagval_ch_ccr_dv' => $contaDV,
                'pagval_ch_num' => $request->numCHQ,
                'pagval_ch_vct' => $dataVct,
                'pagval_ch_res' => $emitente, 
            ]);

            $msg = 'Pagamento em Cheque alterado com sucesso!';

        }elseif($tipoValor == 'CCT'){

            $valor = Helper::limpaValorMonetario($request->valCCT);

            if($valor > $request->valTotCCT){

                return redirect()->back()->with('info', 'O valor a receber não pode ser maior que o saldo da conta!');
            }

            $valorPagamento->update([
                'pagval_valor' => $valor,
                'pagval_obs' => $request->observacao,
                'pagval_cct_tcc' => $request->tipoCCT,
                'pagval_cct_res' => $request->resCCT,   
                'pagval_cct_ncc' => $request->numCCT,
            ]);

            $msg = 'Pagamento em Conta Corrente alterada com sucesso!';

        }elseif($tipoValor == 'CCO'){

            $valor = Helper::limpaValorMonetario($request->valCCO);

            $valorPagamento->update([
                'pagval_valor' => $valor,
                'pagval_obs' => $request->observacao,   
                'pagval_crt_adm' => $request->admCCO,
                'pagval_crt_com' => $request->numCCO,
                'pagval_crt_prc' => $request->parcelaCCO,
            ]);

            $msg = 'Pagamento com Cartão Corporativo alterado com sucesso!';

        }

        return redirect(route('pagamentoPCC.pagarLote',['appOrigem' => $appOrigem]))->with('success', $msg);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Realiza a Exclusão do Valor de Pagamento
    |----------------------------------------------------------------------------------------------------
    */
    public function destroy($appOrigem, FinanceiroPagamentoValores $valorPagamento)
    {
        $valorPagamento->delete();

        return redirect(route('pagamentoPCC.pagarLote',['appOrigem' => $appOrigem]))->with('success', 'Valor de pagamento excluído com sucesso!');
    }
}
