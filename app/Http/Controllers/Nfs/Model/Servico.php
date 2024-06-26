<?php

namespace App\Http\Controllers\Nfs\Model;

use Illuminate\Support\Facades\DB;
use stdClass;

class Servico {

    private $discriminacaoServico;
    private $quantidade;
    private $valorUnitario;
    private $valorTotal;
    private $valorDesconto;
    private $valorTotalLiquido;

    public function carregaServicos($empresa, $numero, $numControle)
    {
        //echo "<br> Entrei Model Servico <br>";

        $itens = array();

        $sql = "SELECT 
                    discriminacao_servico, 
                    quantidade, 
                    valor_unitario, 
                    valor_total,
                    valor_desconto,
                    valor_total_liquido
                FROM vi_nfsxml_servicos 
                WHERE 
                    empresa = '".$empresa."' and 
                    num_controle = ".$numControle." and 
                    num_nf =".$numero;
        $rs = DB::select($sql);

        foreach ($rs as $dados) {
            $item = new Servico();

            $item->discriminacaoServico = $dados->discriminacao_servico;
            $item->quantidade = $dados->quantidade;
            $item->valorUnitario = $dados->valor_unitario;
            $item->valorTotal= $dados->valor_total;
            $item->valorDesconto= $dados->valor_desconto;
            $item->valorTotalLiquido= $dados->valor_total_liquido;

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
