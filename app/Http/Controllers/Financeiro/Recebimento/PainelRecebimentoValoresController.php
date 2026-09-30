<?php

namespace App\Http\Controllers\Financeiro\Recebimento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PainelRecebimentoValoresController extends Controller
{
    /*
    |----------------------------------------------------------------------------------------------------
    | Executa o painel principal do recebimento
    |----------------------------------------------------------------------------------------------------
    */
    public function painelRecebimento($empresa, $cliente, $idRecebimento)
    {

        $tipoRecebimento = Session::get('glo_recebimento_origem');

        //Validação de acordo com o tipo do recebimento
        if($tipoRecebimento == 'CCT'){
        
            $cntContas = DB::table('financeiro_recebimento_contas_correntes')
            ->where('reccct_emp', $empresa)
            ->where('reccct_cod_rec', $idRecebimento)
            ->count();

            if($cntContas == 0){
                return redirect()->route('recebimentoCCT.painelPrincipalCCT',['empresa' => $empresa, 'cliente' => $cliente, 'idRecebimento' => $idRecebimento])->with('info', ' Não há contas selecionadas para recebimento!');
            }

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

            $valorRec = DB::table('financeiro_recebimento_valores')->where('recval_cod_rec', $idRecebimento)->where('recval_emp', $empresa)->sum('recval_valor');

            $saldoRest = $totalRecebimento - $valorRec;

            if($saldoRest < 0){
                $saldoRest = 0;
                $troco = $valorRec - $totalRecebimento;
            }else{
                $troco = 0;
            }

            //Busca os dados dos valores recebidos
            $dadosRec = DB::table('financeiro_recebimento_valores')->where('recval_emp',$empresa)->where('recval_cod_rec',$idRecebimento)->orderby('recval_seq','asc')->get();

            //Busca os dados das contas correntes para recebimento
            $dadosCCT = DB::table('financeiro_recebimento_contas_correntes')
            ->where('reccct_emp', $empresa)
            ->where('reccct_cod_rec', $idRecebimento)
            ->get();

            return view('/financeiro/recebimento/fin_pnl001_PainelCCT', [
                'clienteREC' => $cliente,
                'empresaREC' => $empresa,
                'idRecebimento' => $idRecebimento,
                'estagio_app' => 'PAINEL_RECEBIMENTO',
                'appOrigem' => 'CCT',
                'contasRecebidas' => $dadosCCT,
                'dadosRecebimento' => $dadosRec,
                'valorTotalRec' => $totalRecebimento,
                'valorRecebido' => $valorRec,
                'saldoRestante' => $saldoRest,
                'trocoRecebimento' => $troco,
                'observacoes' => ''
            ]);

        }elseif($tipoRecebimento == 'NFV'){

            $where_semi = Session::get('glo_emissao_nf_where_semi');

            //Verifica se alguma NF foi selecionada para Faturamento
            $notaReceb = DB::table('financeiro_recebimento_notas')->where('recnf_cod_rec', $idRecebimento)->where('recnf_emp', $empresa)->count();

            if($notaReceb == 0){
                return redirect()->back()->with('info', 'Nenhuma nota foi selecionada para ser faturada!');
            }

            $valTotNF = DB::table('financeiro_recebimento_notas')->where('recnf_cod_rec', $idRecebimento)->where('recnf_emp', $empresa)->sum('recnf_vlr_sin');

            //Se não existir valor de entrada/sinal o recebimento é a prazo
            if($valTotNF == 0){

                return redirect()->route('emissaoNF.opcaoNF', [
                    'empresa' => $empresa,
                    'cliente' => $cliente,
                    'origemOpc' => 'PRAZO'
                ]);

            }else{
                $valorRec = DB::table('financeiro_recebimento_valores')->where('recval_cod_rec', $idRecebimento)->where('recval_emp', $empresa)->sum('recval_valor');

                $saldoRest = $valTotNF - $valorRec;

                if($saldoRest < 0){
                    $saldoRest = 0;
                    $troco = $valorRec - $valTotNF;
                }else{
                    $troco = 0;
                }

                $observacao = DB::table('financeiro_recebimento_headers')->where('rechdr_cod_rec', $idRecebimento)->where('rechdr_emp', $empresa)->value('rechdr_obs');

                //Gera dados das NF em aberto para seleção e os valores de recebimento informados
                $dados = DB::select("select * from vi_faturamento_pnl001_sel_notas where nfhdr_sts = 'A' and nfhdr_ori in('01') ".$where_semi." and nfhdr_emp = '".$empresa."' and nfhdr_cli = '".$cliente."' order by nfhdr_num_ped asc");
                $dadosRec = DB::table('financeiro_recebimento_valores')->where('recval_emp',$empresa)->where('recval_cod_rec',$idRecebimento)->orderby('recval_seq','asc')->get();

                return view('/faturamento/notas/fat_pnl001_EmissaoNF',[
                    'empresaREC' => $empresa, 
                    'clienteREC' => $cliente, 
                    'idRecebimento' => $idRecebimento, 
                    'estagio_app' => 'PAINEL_RECEBIMENTO',
                    'appOrigem' => 'NFV',
                    'dadosCliente'=>$dados, 
                    'dadosRecebimento' => $dadosRec,
                    'valorTotalRec' => $valTotNF,
                    'valorRecebido' => $valorRec,
                    'saldoRestante' => $saldoRest,
                    'trocoRecebimento' => $troco,
                    'observacoes' => $observacao
                ]);
            }

        }elseif($tipoRecebimento == 'DUP'){

            //Verifica se alguma Nduplicata foi selecionada para recebimento
            $dupRecebida = DB::table('financeiro_recebimento_duplicatas')->where('recdup_cod_rec', $idRecebimento)->where('recdup_emp', $empresa)->count();

            if($dupRecebida == 0){
                return redirect()->back()->with('info', 'Nenhuma Duplicata foi selecionada para ser recebida!');
            }

            //Soma o valor total a ser recebido das duplicatas
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

            $valorRec = DB::table('financeiro_recebimento_valores')->where('recval_cod_rec', $idRecebimento)->where('recval_emp', $empresa)->sum('recval_valor');

            $saldoRest = $totalRecebimento - $valorRec;

            if($saldoRest < 0){
                $saldoRest = 0;
                $troco = $valorRec - $totalRecebimento;
            }else{
                $troco = 0;
            }

            $observacao = DB::table('financeiro_recebimento_headers')->where('rechdr_cod_rec', $idRecebimento)->where('rechdr_emp', $empresa)->value('rechdr_obs');

            //Gera dados das duplicatas para seleção e os valores de recebimento informados
            $dados = DB::table('financeiro_recebimento_duplicatas')
            ->where('recdup_emp', $empresa)
            ->where('recdup_cod_rec', $idRecebimento)
            ->get();
            $dadosRec = DB::table('financeiro_recebimento_valores')->where('recval_emp',$empresa)->where('recval_cod_rec',$idRecebimento)->orderby('recval_seq','asc')->get();

            return view('/financeiro/recebimento/fin_pnl002_PainelDUP',[
                'empresaREC' => $empresa, 
                'clienteREC' => $cliente, 
                'idRecebimento' => $idRecebimento, 
                'estagio_app' => 'PAINEL_RECEBIMENTO',
                'appOrigem' => 'DUP',
                'dupSelecionadas'=>$dados, 
                'dadosRecebimento' => $dadosRec,
                'valorTotalRec' => $totalRecebimento,
                'valorRecebido' => $valorRec,
                'saldoRestante' => $saldoRest,
                'trocoRecebimento' => $troco,
                'observacoes' => $observacao
            ]);
        }elseif($tipoRecebimento == 'OUT'){

            //Soma o valor total a ser recebido das duplicatas
            $totalRecebimento = DB::table('financeiro_recebimento_outros')
            ->where('recout_emp', $empresa)
            ->where('recout_cod_rec', $idRecebimento)
            ->value('recout_vlr');

            $valorRec = DB::table('financeiro_recebimento_valores')->where('recval_cod_rec', $idRecebimento)->where('recval_emp', $empresa)->sum('recval_valor');

            $saldoRest = $totalRecebimento - $valorRec;

            if($saldoRest < 0){
                $saldoRest = 0;
                $troco = $valorRec - $totalRecebimento;
            }else{
                $troco = 0;
            }

            $observacao = DB::table('financeiro_recebimento_headers')->where('rechdr_cod_rec', $idRecebimento)->where('rechdr_emp', $empresa)->value('rechdr_obs');

            //Gera dados do recebimento para seleção e os valores de recebimento informados
            $dados = DB::table('financeiro_recebimento_outros')
            ->where('recout_emp', $empresa)
            ->where('recout_cod_rec', $idRecebimento)
            ->first();
            $dadosRec = DB::table('financeiro_recebimento_valores')->where('recval_emp',$empresa)->where('recval_cod_rec',$idRecebimento)->orderby('recval_seq','asc')->get();

            return view('/financeiro/recebimento/fin_pnl003_PainelOUT', [
                'empresaREC' => $empresa,
                'clienteREC' => $cliente, 
                'idRecebimento' => $idRecebimento,
                'estagio_app' => 'PAINEL_RECEBIMENTO',
                'appOrigem' => 'OUT',
                'dadosOUT' => $dados,
                'dadosRecebimento' => $dadosRec,
                'valorTotalRec' => $totalRecebimento,
                'valorRecebido' => $valorRec,
                'saldoRestante' => $saldoRest,
                'trocoRecebimento' => $troco,
                'observacoes' => $observacao
            ]);
        }
    }
}
