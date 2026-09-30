<?php

namespace App\Http\Helpers;
use Illuminate\Support\Facades\DB;
use stdClass;
use Carbon\Carbon;

class HelperFinanceiro
{
    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Helper de Funções do Financeiro
    |----------------------------------------------------------------------------------------------------
    |
    | Helper destinado para funções comuns do projeto da aéra do financeiro.
    |
    |
    ***** */

    /* *****
    |----------------------------------------------------------------------------------------------------
    | Gera o processo pro001 - Geração dos Movimentos de Saldo Inicial dos Razões
    |----------------------------------------------------------------------------------------------------
    ***** */
    public static function geraFinPro001($empresa, $data, $usuario, $app)
    {
        try {
            // Chamada da função PL/pgSQL
            $query = "SELECT * FROM fn_financeiro_pro001(:empresa, :data, :usuario, :app)";

            // Executar a função e capturar o resultado
            $result = DB::select($query, [
                'empresa' => $empresa,
                'data' => $data,
                'usuario' => $usuario,
                'app' => $app,
            ]);

            // Garantir que o resultado seja avaliado
            if (!empty($result)) {
                $retorno = (array) $result[0]; // Converter para array associativo
                $retSts = $retorno['ret_sts'] ?? '';
                $retMsg = $retorno['ret_msg'] ?? '';

                // Caso não haja erro (retornos vazios)
                if ($retSts === '' && $retMsg === '') {
                    return [
                        'ret_sts' => '',
                        'ret_msg' => '',
                    ];
                }

                // Caso de erro (retorno com status '*')
                if ($retSts === '*') {
                    return [
                        'ret_sts' => '*',
                        'ret_msg' => $retMsg,
                    ];
                }
            }

            // Caso o retorno seja inesperado, tratar como erro genérico
            return [
                'ret_sts' => '*',
                'ret_msg' => 'Retorno inesperado da função fn_financeiro_pro001.',
            ];
        } catch (\Exception $e) {
            // Capturar erros da execução
            return [
                'ret_sts' => '*',
                'ret_msg' => $e->getMessage(),
            ];
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método para limpar os recebimentos que ficaram em aberto dos dias anteriores
    |----------------------------------------------------------------------------------------------------
    */
    public static function limpaRecebimentoAberto()
    {
        $dtHJ = date('Y-m-d');

        //Busca todos os recebimentos abertos com a data de inclusão menor que a data de hoje
        $recAbertos = DB::table('financeiro_recebimento_headers')
        ->where('rechdr_dti', '<', $dtHJ)
        ->where('rechdr_sts', 'A')
        ->get();

        //Para cada recebimento encontrado, exclui os dados relacionados das tabelas
        foreach($recAbertos as $recebimento) {
            DB::table('financeiro_recebimento_headers')
            ->where('rechdr_cod_rec', $recebimento->rechdr_cod_rec)
            ->where('rechdr_emp', $recebimento->rechdr_emp)
            ->delete();

            DB::table('financeiro_recebimento_notas')
            ->where('recnf_emp', $recebimento->rechdr_emp)
            ->where('recnf_cod_rec', $recebimento->rechdr_cod_rec)
            ->delete();

            DB::table('financeiro_recebimento_valores')
            ->where('recval_emp', $recebimento->rechdr_emp)
            ->where('recval_cod_rec', $recebimento->rechdr_cod_rec)
            ->delete();

            DB::table('financeiro_recebimento_contas_correntes')
            ->where('reccct_emp', $recebimento->rechdr_emp)
            ->where('reccct_cod_rec', $recebimento->rechdr_cod_rec)
            ->delete();

            DB::table('financeiro_recebimento_duplicatas')
            ->where('recdup_emp', $recebimento->rechdr_emp)
            ->where('recdup_cod_rec', $recebimento->rechdr_cod_rec)
            ->delete();

            DB::table('financeiro_recebimento_outros')
            ->where('recout_emp', $recebimento->rechdr_emp)
            ->where('recout_cod_rec', $recebimento->rechdr_cod_rec)
            ->delete();
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método para limpar os recebimentos que ficaram em aberto do Usuário
    |----------------------------------------------------------------------------------------------------
    */
    public static function limpaRecebimentoUsuario($usuario)
    {
        $dtHJ = date('Y-m-d');

        //Busca todos os recebimentos abertos com a data de inclusão de hoje
        $recAbertos = DB::table('financeiro_recebimento_headers')
        ->where('rechdr_usu', $usuario)
        ->where('rechdr_sts', 'A')
        ->where('rechdr_dti', $dtHJ)
        ->get();

        //Para cada recebimento encontrado, exclui os dados relacionados das tabelas
        foreach($recAbertos as $recebimento) {
            DB::table('financeiro_recebimento_headers')
            ->where('rechdr_cod_rec', $recebimento->rechdr_cod_rec)
            ->where('rechdr_emp', $recebimento->rechdr_emp)
            ->delete();

            DB::table('financeiro_recebimento_notas')
            ->where('recnf_emp', $recebimento->rechdr_emp)
            ->where('recnf_cod_rec', $recebimento->rechdr_cod_rec)
            ->delete();

            DB::table('financeiro_recebimento_valores')
            ->where('recval_emp', $recebimento->rechdr_emp)
            ->where('recval_cod_rec', $recebimento->rechdr_cod_rec)
            ->delete();

            DB::table('financeiro_recebimento_contas_correntes')
            ->where('reccct_emp', $recebimento->rechdr_emp)
            ->where('reccct_cod_rec', $recebimento->rechdr_cod_rec)
            ->delete();

            DB::table('financeiro_recebimento_duplicatas')
            ->where('recdup_emp', $recebimento->rechdr_emp)
            ->where('recdup_cod_rec', $recebimento->rechdr_cod_rec)
            ->delete();

            DB::table('financeiro_recebimento_outros')
            ->where('recout_emp', $recebimento->rechdr_emp)
            ->where('recout_cod_rec', $recebimento->rechdr_cod_rec)
            ->delete();
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método para limpar os recebimentos que ficaram em aberto do Usuário (Reabertura de Seleção)
    |----------------------------------------------------------------------------------------------------
    */
    public static function limpaValoresRecebimento($empresa, $idRecebimento)
    {
        DB::table('financeiro_recebimento_valores')
        ->where('recval_emp', $empresa)
        ->where('recval_cod_rec', $idRecebimento)
        ->delete();
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método para calcular o juros mora e dias de atrazo das duplicatas
    |----------------------------------------------------------------------------------------------------
    */
    public static function calculaMoraDiaAtrasoDuplicata($valorDuplicata, $valorPago, $valorMoraDiaria, $dataVencimento)
    {
        $saldo = $valorDuplicata - $valorPago;
        $moraTotal = 0;
        $diasAtraso = 0;

        $vencimento = Carbon::parse($dataVencimento)->startOfDay();
        $hoje = Carbon::now()->startOfDay();

        if ($hoje->gt($vencimento)) {
            $diasAtraso = $vencimento->diffInDays($hoje);
            $moraTotal = $valorMoraDiaria * $diasAtraso;
        }

        return [
            'saldo' => $saldo,
            'mora_total' => $moraTotal,
            'dias_atraso' => $diasAtraso,
        ];
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método para limpar os Pagamentos que ficaram em aberto dos dias anteriores
    |----------------------------------------------------------------------------------------------------
    */
    public static function limpaPagamentoAberto()
    {
        $dtHJ = date('Y-m-d');

        //Busca todos os Pagamentos abertos com a data de inclusão menor que a data de hoje
        $pagAbertos = DB::table('financeiro_pagamento_headers')
        ->where('paghdr_dti', '<', $dtHJ)
        ->where('paghdr_sts', 'A')
        ->get();

        //Para cada Pagamento encontrado, exclui os dados relacionados das tabelas
        foreach($pagAbertos as $pagamento) {
            DB::table('financeiro_pagamento_headers')
            ->where('paghdr_cod_pag', $pagamento->paghdr_cod_pag)
            ->where('paghdr_emp', $pagamento->paghdr_emp)
            ->delete();

            DB::table('financeiro_pagamento_valores')
            ->where('pagval_emp', $pagamento->paghdr_emp)
            ->where('pagval_cod_pag', $pagamento->paghdr_cod_pag)
            ->delete();

            DB::table('financeiro_pagamento_pequenas_despesas')
            ->where('pagpqd_emp', $pagamento->paghdr_emp)
            ->where('pagpqd_cod_pag', $pagamento->paghdr_cod_pag)
            ->delete();

            DB::table('financeiro_pagamento_contas_correntes')
            ->where('pagcct_emp', $pagamento->paghdr_emp)
            ->where('pagcct_cod_pag', $pagamento->paghdr_cod_pag)
            ->delete();

            DB::table('financeiro_pagamento_adiantamento_fornecedores')
            ->where('pagaf_emp', $pagamento->paghdr_emp)
            ->where('pagaf_cod_pag', $pagamento->paghdr_cod_pag)
            ->delete();
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método para limpar os Pagamentos que ficaram em aberto do Usuário
    |----------------------------------------------------------------------------------------------------
    */
    public static function limpaPagamentoUsuario($usuario)
    {
        $dtHJ = date('Y-m-d');

        //Busca todos os Pagamentos abertos com a data de hoje
        $pagAbertos = DB::table('financeiro_pagamento_headers')
        ->where('paghdr_usu', $usuario)
        ->where('paghdr_sts', 'A')
        ->where('paghdr_dti', $dtHJ)
        ->get();

        //Para cada Pagamento encontrado, exclui os dados relacionados das tabelas
        foreach($pagAbertos as $pagamento) {
            DB::table('financeiro_pagamento_headers')
            ->where('paghdr_cod_pag', $pagamento->paghdr_cod_pag)
            ->where('paghdr_emp', $pagamento->paghdr_emp)
            ->delete();

            DB::table('financeiro_pagamento_valores')
            ->where('pagval_emp', $pagamento->paghdr_emp)
            ->where('pagval_cod_pag', $pagamento->paghdr_cod_pag)
            ->delete();

            DB::table('financeiro_pagamento_pequenas_despesas')
            ->where('pagpqd_emp', $pagamento->paghdr_emp)
            ->where('pagpqd_cod_pag', $pagamento->paghdr_cod_pag)
            ->delete();

            DB::table('financeiro_pagamento_contas_correntes')
            ->where('pagcct_emp', $pagamento->paghdr_emp)
            ->where('pagcct_cod_pag', $pagamento->paghdr_cod_pag)
            ->delete();

            DB::table('financeiro_pagamento_adiantamento_fornecedores')
            ->where('pagaf_emp', $pagamento->paghdr_emp)
            ->where('pagaf_cod_pag', $pagamento->paghdr_cod_pag)
            ->delete();
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Método para limpar as transferências que ficaram em aberto dos dias anteriores
    |----------------------------------------------------------------------------------------------------
    */
    public static function limpaTransferenciaAberto()
    {
        $dtHJ = date('Y-m-d');

        //Busca todos os Pagamentos abertos com a data de inclusão menor que a data de hoje
        $transfAbertas = DB::table('financeiro_transferencias')
        ->where('transf_data', '<', $dtHJ)
        ->where('transf_situacao', 'M')
        ->get();

        //Para cada Transferência encontrada, exclui os dados relacionados das tabelas
        foreach($transfAbertas as $transferencia) {
            DB::table('financeiro_transferencias')
            ->where('transf_id', $transferencia->transf_id)
            ->delete();

            DB::table('financeiro_transferencias_reforcos')
            ->where('tranref_cod_trans', $transferencia->transf_id)
            ->delete();

            DB::table('financeiro_transferencias_itens')
            ->where('tranitm_cod_trans', $transferencia->transf_id)
            ->delete();
        }
    }
}