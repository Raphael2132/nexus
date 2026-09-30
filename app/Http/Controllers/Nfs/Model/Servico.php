<?php

namespace App\Http\Controllers\Nfs\Model;

use Illuminate\Support\Facades\DB;
use stdClass;

class Servico {

    private $discriminacaoServico;
    //private $quantidade;
    //private $valorUnitario;
    //private $valorTotal;
    //private $valorDesconto;
    //private $valorTotalLiquido;
    //private $emiSimplificada;
    //private $descServico;
    //private $infoComplementar;

    public function carregaServicos($empresa, $numero, $numControle)
    {
        //echo "<br> Entrei Model Servico <br>";

        $itens = array();

        $sql = "SELECT 
                    discriminacao_servico
                FROM vi_nfsxml_servicos 
                WHERE 
                    empresa = '".$empresa."' and 
                    num_controle = ".$numControle." and 
                    num_rps =".$numero;
        $rs = DB::select($sql);

        foreach ($rs as $dados) {
            $item = new Servico();

            $item->discriminacaoServico = $dados->discriminacao_servico;
            //$item->quantidade = $dados->quantidade;
            //$item->valorUnitario = $dados->valor_unitario;
            //$item->valorTotal= $dados->valor_total;
            //$item->valorDesconto= $dados->valor_desconto;
            //$item->valorTotalLiquido= $dados->valor_total_liquido;
            //$item->emiSimplificada= $dados->emissao_simplificada;
            //$item->descServico= $dados->descricao_servico;
            //$item->infoComplementar= $dados->informacoes_complementares;

            $itens[] = $item;
        }

        //echo "<br> Teste Model Servico <br>";
        //var_dump($itens);
        //echo "<br>";

        unset($rs);

        return $itens;
    }

    /* Sobrescrve o método mágico __get */
    public function __get($atributo){
        return $this->$atributo;
    }
}
