<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use App\Http\Helpers\Helper;
use App\Models\FinanceiroRecebimentoNota;

class FinanceiroRecebimentoNotaController extends Controller
{
    protected $notaRecebimento;
    
    public function __construct(FinanceiroRecebimentoNota $notaRecebimento)
    {
        $this->notaRecebimento = $notaRecebimento;
    }

    //Insere o a nota do recebimento
    //Vamos deixar uma Observação Aqui:
    //Quando tiver produtos o insert aqui vai ter q ser por um foreach e vamos mudar para numero do pedido e origem comoi where no lugar do numero de controle
    //O que vai acontecer é que quando selecionar uma NFS ou NF se o pedido tiver os dois tem que fazer uma seleção dupla mesmo que a outra não foi selecionada ela deve ser tambem
    //Não vamos poder receber separado, como é mesmo pedido/os tem que ser recebido junto obrigatóriamente
    //Por hora não temos NF e apenas NFS então vamos manter assim que é mais facil por que não tem como testar isso ainda.
    public function insert($empresa, $numNF, $cliente){

        $dadosNF = DB::table('faturamento_nf_headers')->where('nfhdr_emp', $empresa)->where('nfhdr_num', $numNF)->first();
        $idRecebimento = session('glo_id_recebimento');

        $dados = [
            'recnf_id_rec' => $idRecebimento,
            'recnf_emp' => $empresa,
            'recnf_num' => $numNF,
            'recnf_num_ped' => $dadosNF->nfhdr_num_ped,
            'recnf_num_nf' => $dadosNF->nfhdr_num_nf,
            'recnf_ser_nf' => $dadosNF->nfhdr_ser_nf,
            'recnf_dt_nf' => $dadosNF->nfhdr_dt_nf,
            'recnf_hr_nf' => $dadosNF->nfhdr_hr_nf,
            'recnf_cfop' => $dadosNF->nfhdr_cfop,
            'recnf_cme' => $dadosNF->nfhdr_cme,
            'recnf_tor' => $dadosNF->nfhdr_tor,
            'recnf_ori' => $dadosNF->nfhdr_ori,
            'recnf_vlr_tot' => $dadosNF->nfhdr_vlr_tot_nf,
        ];
        
        // Cria o registro e obtém a instância do modelo
        FinanceiroRecebimentoNota::create($dados);
        
        // Retorna o ID do registro criado
        return redirect(route('emissaoNF.painelNfAberto', ['empresa' => $empresa, 'cliente' => $cliente,'estagio_app' => 'SELECAO_NF']))->with('success', 'Nota Fiscal selecionada com sucesso!');
    }

    //Desmarca a nota do recebimento
    //Vamos deixar uma Observação Aqui:
    //Quando tiver produtos o DELETE aqui vai ter q ser por um foreach e vamos mudar para numero do pedido e origem comoi where no lugar do numero de controle
    //O que vai acontecer é que quando selecionar uma NFS ou NF se o pedido tiver os dois tem que fazer uma seleção dupla mesmo que a outra não foi selecionada ela deve ser tambem
    //Não vamos poder receber separado, como é mesmo pedido/os tem que ser recebido junto obrigatóriamente
    //Por hora não temos NF e apenas NFS então vamos manter assim que é mais facil por que não tem como testar isso ainda.
    public function delete($empresa, $numNF, $cliente){

        // Obtém os registros que correspondem às condições
        $registrosParaDeletar = FinanceiroRecebimentoNota::where('recnf_emp', $empresa)
        ->where('recnf_num', $numNF)
        ->get();

        // Verifica se há registros para deletar
        if ($registrosParaDeletar->isEmpty()) {
            return redirect()->back()->with('error', 'Nenhum registro encontrado para remover da seleção!');
        }

        // Deleta os registros encontrados
        FinanceiroRecebimentoNota::where('recnf_emp', $empresa)
            ->where('recnf_num', $numNF)
            ->delete();

        // Redireciona após a operação
        return redirect(route('emissaoNF.painelNfAberto', ['empresa' => $empresa, 'cliente' => $cliente,'estagio_app' => 'SELECAO_NF']))
            ->with('success', 'Nota Fiscal removida da seleção com sucesso!');
    }
}
