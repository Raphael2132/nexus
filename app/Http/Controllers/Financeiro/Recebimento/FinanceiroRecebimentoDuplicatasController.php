<?php

namespace App\Http\Controllers\Financeiro\Recebimento;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Http\Helpers\HelperFinanceiro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Financeiro\Recebimento\FinanceiroRecebimentoDuplicatas;

class FinanceiroRecebimentoDuplicatasController extends Controller
{
    protected $dupRecebimento;
    
    public function __construct(FinanceiroRecebimentoDuplicatas $dupRecebimento)
    {
        $this->dupRecebimento = $dupRecebimento;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Insere a duplicata pela inclusão da abertura do recebimento
    |----------------------------------------------------------------------------------------------------
    */
    public static function insert($empresa, $cliente, $numDUP, $seqDUP){

        $dadosDUP = DB::table('financeiro_contas_receber_clientes')
        ->where('conrec_empresa', $empresa)
        ->where('conrec_codigo', $numDUP)
        ->where('conrec_sequencia', $seqDUP)
        ->where('conrec_cliente', $cliente)
        ->first();

        $idRecebimento = Session::get('glo_recebimento_id');

        $calcJur = HelperFinanceiro::calculaMoraDiaAtrasoDuplicata($dadosDUP->conrec_val_dup, $dadosDUP->conrec_val_pag, $dadosDUP->conrec_val_mora, $dadosDUP->conrec_dt_vencimento);

        $dados = [
            'recdup_emp' => $empresa,
            'recdup_cod_rec' => $idRecebimento,
            'recdup_cod' => $numDUP,
            'recdup_seq' => $seqDUP,
            'recdup_cli' => $cliente,
            'recdup_dte' => $dadosDUP->conrec_dt_emissao,
            'recdup_dtv' => $dadosDUP->conrec_dt_vencimento,
            'recdup_vlr_dup' => $dadosDUP->conrec_val_dup,
            'recdup_vlr_pag' => $dadosDUP->conrec_val_pag,
            'recdup_vlr_bxa' => $calcJur['saldo'],
            'recdup_vlr_jmd' => $dadosDUP->conrec_val_mora,
            'recdup_vlr_jmt' => $calcJur['mora_total'],
            'recdup_dia_atr' => $calcJur['dias_atraso']
        ];
        
        // Cria o registro e obtém a instância do modelo
        FinanceiroRecebimentoDuplicatas::create($dados);
        
        return;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Insere a duplicata pelo modal de seleção
    |----------------------------------------------------------------------------------------------------
    */
    public function insertModal($empresa, $cliente, $idRecebimento, Request $request)
    {
        $dadosDUP = DB::table('financeiro_contas_receber_clientes')
        ->where('conrec_empresa', $empresa)
        ->where('conrec_codigo', $request->numDUP)
        ->where('conrec_sequencia', $request->seqDUP)
        ->where('conrec_cliente', $cliente)
        ->first();

        $valRec = Helper::limpaValorMonetario($request->valRec);
        $moraDia = $dadosDUP->conrec_val_mora;

        $diaAtraso = $request->diaAtraso;

        if($request->tipoOpr == 'MORA'){

            if(!empty($request->jurMoraRec)){
                $jurMoraRec = Helper::limpaValorMonetario($request->jurMoraRec);
            }else{
                $jurMoraRec = 0;
            }

            $tipoOpr = 'M';

            $valDesc = 0;
            $valPIS = 0;
            $valCOFINS = 0;
            $valCSLL = 0;
            $valISSQN = 0;
            $valIRRF = 0;
            $valIRRFNS = 0;

        }else{

            if(!empty($request->valDesc)){
                $valDesc = Helper::limpaValorMonetario($request->valDesc);
            }else{
                $valDesc = 0;
            }

            if($request->tipoDesc == 'FIN'){

                $tipoOpr = 'F';

                $jurMoraRec = 0;

                $valPIS = 0;
                $valCOFINS = 0;
                $valCSLL = 0;
                $valISSQN = 0;
                $valIRRF = 0;
                $valIRRFNS = 0;
                
            }else{

                $tipoOpr = 'R';

                $jurMoraRec = 0;

                if(!empty($request->valPIS)){
                    $valPIS = Helper::limpaValorMonetario($request->valPIS);
                }else{
                    $valPIS = 0;
                }

                if(!empty($request->valCOFINS)){
                    $valCOFINS = Helper::limpaValorMonetario($request->valCOFINS);
                }else{
                    $valCOFINS = 0;
                }

                if(!empty($request->valCSLL)){
                    $valCSLL = Helper::limpaValorMonetario($request->valCSLL);
                }else{
                    $valCSLL = 0;
                }

                if(!empty($request->valISSQN)){
                    $valISSQN = Helper::limpaValorMonetario($request->valISSQN);
                }else{
                    $valISSQN = 0;
                }

                if(!empty($request->valIRRF)){
                    $valIRRF = Helper::limpaValorMonetario($request->valIRRF);
                }else{
                    $valIRRF = 0;
                }

                if(!empty($request->valIRRFNS)){
                    $valIRRFNS = Helper::limpaValorMonetario($request->valIRRFNS);
                }else{
                    $valIRRFNS = 0;
                }
            }
        }

        $dados = [
            'recdup_emp' => $empresa,
            'recdup_cod_rec' => $idRecebimento,
            'recdup_cod' => $request->numDUP,
            'recdup_seq' => $request->seqDUP,
            'recdup_cli' => $cliente,
            'recdup_dte' => $dadosDUP->conrec_dt_emissao,
            'recdup_dtv' => $dadosDUP->conrec_dt_vencimento,
            'recdup_vlr_dup' => $dadosDUP->conrec_val_dup,
            'recdup_vlr_pag' => $dadosDUP->conrec_val_pag,
            'recdup_vlr_bxa' => $valRec,
            'recdup_vlr_des' => $valDesc,
            'recdup_vlr_jmd' => $moraDia,
            'recdup_vlr_jmt' => $jurMoraRec,
            'recdup_dia_atr' => $diaAtraso,
            'recdup_tip_opr' => $tipoOpr,
            'recdup_vlr_pis' => $valPIS,
            'recdup_vlr_cofins' => $valCOFINS,
            'recdup_vlr_csll' => $valCSLL,
            'recdup_vlr_irrf' => $valIRRF,
            'recdup_vlr_irrf_ns' => $valIRRFNS,
            'recdup_vlr_issqn' => $valISSQN,
        ];
        
        // Cria o registro e obtém a instância do modelo
        FinanceiroRecebimentoDuplicatas::create($dados);
        
        // Retorna o ID do registro criado
        return redirect(route('recebimentoDUP.painelPrincipalDUP', [
            'empresa' => $empresa, 
            'cliente' => $cliente,
            'idRecebimento' => $idRecebimento
        ]))->with('success', 'Duplicata adicionada com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Atualiza a duplicata pelo modal de seleção
    |----------------------------------------------------------------------------------------------------
    */
    public function updateModal($empresa, $cliente, $idRecebimento, Request $request)
    {

        $valRec = Helper::limpaValorMonetario($request->valRec);
        
        $jurMoraRec = 0;
        $valPIS = 0;
        $valCOFINS = 0;
        $valCSLL = 0;
        $valISSQN = 0;
        $valIRRF = 0;
        $valIRRFNS = 0;
        $valDesc = 0;

        if($request->tipoOpr == 'MORA'){

            if(!empty($request->jurMoraRec)){
                $jurMoraRec = Helper::limpaValorMonetario($request->jurMoraRec);
            }else{
                $jurMoraRec = 0;
            }

            $tipoOpr = 'M';

        }else{

            if(!empty($request->valDesc)){
                $valDesc = Helper::limpaValorMonetario($request->valDesc);
            }

            if($request->tipoDesc == 'FIN'){

                $tipoOpr = 'F';
                
            }else{

                $tipoOpr = 'R';

                if(!empty($request->valPIS)){
                    $valPIS = Helper::limpaValorMonetario($request->valPIS);
                }

                if(!empty($request->valCOFINS)){
                    $valCOFINS = Helper::limpaValorMonetario($request->valCOFINS);
                }

                if(!empty($request->valCSLL)){
                    $valCSLL = Helper::limpaValorMonetario($request->valCSLL);
                }

                if(!empty($request->valISSQN)){
                    $valISSQN = Helper::limpaValorMonetario($request->valISSQN);
                }

                if(!empty($request->valIRRF)){
                    $valIRRF = Helper::limpaValorMonetario($request->valIRRF);
                }

                if(!empty($request->valIRRFNS)){
                    $valIRRFNS = Helper::limpaValorMonetario($request->valIRRFNS);
                }
            }
        }

        $dados = [
            'recdup_vlr_bxa' => $valRec,
            'recdup_vlr_des' => $valDesc,
            'recdup_vlr_jmt' => $jurMoraRec,
            'recdup_tip_opr' => $tipoOpr,
            'recdup_vlr_pis' => $valPIS,
            'recdup_vlr_cofins' => $valCOFINS,
            'recdup_vlr_csll' => $valCSLL,
            'recdup_vlr_irrf' => $valIRRF,
            'recdup_vlr_irrf_ns' => $valIRRFNS,
            'recdup_vlr_issqn' => $valISSQN,
        ];
    
        $duplicata = $this->dupRecebimento
        ->where('recdup_cod_rec', $idRecebimento)
        ->where('recdup_emp', $empresa)
        ->where('recdup_cli', $cliente)
        ->where('recdup_cod', $request->numDUP)
        ->where('recdup_seq', $request->seqDUP)
        ->first();
    
        if (!$duplicata) {
            return redirect()->back()->with('error', 'Duplicata não encontrada!');
        }
    
        $duplicata->update($dados);    
        
        // Retorna o ID do registro criado
        return redirect(route('recebimentoDUP.painelPrincipalDUP', [
            'empresa' => $empresa, 
            'cliente' => $cliente,
            'idRecebimento' => $idRecebimento
        ]))->with('success', 'Duplicata alterada com sucesso!');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Exclui a duplicata da seleção
    |----------------------------------------------------------------------------------------------------
    */
    public function delete($empresa, $cliente, $numDUP, $seqDUP){

        $idRecebimento = Session::get('glo_recebimento_id');

        $this->dupRecebimento->where('recdup_emp', $empresa)->where('recdup_cod_rec', $idRecebimento)->where('recdup_cli', $cliente)->where('recdup_cod', $numDUP)->where('recdup_seq', $seqDUP)->delete();

        // Redireciona após a operação
        return redirect(route('recebimentoDUP.painelPrincipalDUP', [
            'empresa' => $empresa, 
            'cliente' => $cliente,
            'idRecebimento' => $idRecebimento
        ]))->with('success', 'Duplicata excluída com sucesso!');
    }
}
