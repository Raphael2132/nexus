<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Http\Helpers\HelperFinanceiro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Financeiro\FinanceiroRazoes;

class FinanceiroIniciaRazaoController extends Controller
{
    protected $razao;
    
    public function __construct(FinanceiroRazoes $razao)
    {
        $this->razao = $razao;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Gera o movimento do saldo inicial do Razão = BA
    |----------------------------------------------------------------------------------------------------
    */
    public static function inciaRazao($empresa, $codRazao, $saldoIni, $dataConc)
    {

        $dadosTrans = DB::table('financeiro_tab_numero_trans')->first();

        $ultData = $dadosTrans->tabnum_data;
        $dataHoje = date('Y-m-d');

        //Verifica se a data da última transação é igual da data de hoje (inclusão do BA)
        if($ultData == $dataHoje){

            //Gera os Movimetos de Saldo Inicial do Razão
            self::geraMov00($empresa, $codRazao, $saldoIni, $dataConc);

        }elseif($ultData > $dataHoje){

            //Falha, desfaz as alterações no banco de dados
            DB::rollBack();
            return redirect()->back()->with('error', 'A data da última transação é maior que a data atual!');

        }else{

            //Gera o processo de criação de saldo inicial
            $retorno = HelperFinanceiro::geraFinPro001($empresa, $dataHoje, Auth::user()->usuario_codigo, 'FIN001');

            // Verificar os retornos do processo PRO001
            if ($retorno['ret_sts'] == '*') {
                //Falha, desfaz as alterações no banco de dados
                DB::rollBack();
                return redirect()->back()->with('error', $retorno['ret_msg']);
            }

            //Gera os Movimetos de Saldo Inicial do Razão
            self::geraMov00($empresa, $codRazao, $saldoIni, $dataConc);
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Gera os Movimentos de Saldo Inicial do Razão
    |----------------------------------------------------------------------------------------------------
    */
    public static function geraMov00($empresa, $codRazao, $saldoIni, $dataConc)
    {  
        $dataHoje = date('Y-m-d');

        //Insere apenas o Movimento de saldo inicial do razão do dia que foi criado
        self::insereMov00($empresa, $codRazao, $saldoIni, $dataHoje);

        //Se a data da primeira conciliação for menor que a data da inserção do BA gera os saldos iniciais retroativos
        if($dataConc < $dataHoje) {

            echo "<br>entrei".$dataConc.' '.$dataHoje;
            
            $dataMov = $dataConc;

            echo "<br> data adicionada +1 dia ".$dataMov;

            // Enquanto a data de conciliação for menor que a data de hoje
            while ($dataMov < $dataHoje) {

                // Insere os movimentos retroativos para a data atual
                self::insereMov00($empresa, $codRazao, $saldoIni, $dataMov);

                // Incrementa a data para o próximo dia
                $dataMov = date('Y-m-d', strtotime($dataMov . ' +1 day'));
                echo "<br> data adicionada +1 dia final while ".$dataMov;
            }
        }

        //Atualiza o saldo do dia da conciliação
        DB::table('financeiro_movimentos')
        ->where('movimento_operacao', '00')
        ->where('movimento_tipo_conta', 'BA')
        ->where('movimento_dt_opr', $dataConc)
        ->where('movimento_responsavel', $codRazao)
        ->update([
            'movimento_val_abe' => $saldoIni,
        ]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Faz o insert do Movimento de Saldo Inicial do Razão
    |----------------------------------------------------------------------------------------------------
    */
    public static function insereMov00($empresa, $codRazao, $saldoIni, $dataOpr)
    {  
        
        $dataExt = '0001-01-01';
        $hora = date('Hi');

        // Busca a maior sequencia da opr = '00' do dia, caso não encontrar retorna null
        $sequencia = DB::table('financeiro_movimentos')
        ->where('movimento_operacao', '00')
        ->where('movimento_dt_opr', $dataOpr)
        ->max('movimento_sequencia');

        // Incrementa a sequência caso existam registros, senão inicia com 1
        $sequencia = $sequencia ? $sequencia + 1 : 1;

        //Montagem do saldo inicial e do sinal do movimento
        if($saldoIni > 0){
            $valor = $saldoIni;
            $sinal = 'D';
        }else{
            $valor = $saldoIni * -1;
            $sinal = 'C';
        }

        DB::table('financeiro_movimentos')->insert([
            'movimento_status' => 'A',
            'movimento_operacao' => '00',
            'movimento_dt_opr' => $dataOpr,
            'movimento_num_trans' => 0,
            'movimento_sequencia' => $sequencia,
            'movimento_tipo_conta' => 'BA',
            'movimento_linha' => 'R',
            'movimento_responsavel' => $codRazao,
            'movimento_dt_contabil' => $dataOpr,
            'movimento_tipo_mov' => 'A',
            'movimento_dt_extrato' => $dataExt,
            'movimento_cod_mov' => '00',
            'movimento_tipo_valor' => 'DI',
            'movimento_comprovante' => '',
            'movimento_complemento' => 'Gerado pela APP FIN001',
            'movimento_tipo_mand' => 'BA',
            'movimento_mand' => $codRazao,
            'movimento_valor' => $valor,
            'movimento_sinal' => $sinal,
            'movimento_usuario' => Auth::user()->usuario_codigo,
            'movimento_hora' => $hora,
            'movimento_val_abe' => 0,
            'movimento_val_mf' => 0,
            'movimento_empresa' => $empresa,
            'movimento_app_ger' => 'FIN001'
        ]);
    }
}
