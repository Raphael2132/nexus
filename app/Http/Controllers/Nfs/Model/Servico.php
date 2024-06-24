<?php

namespace App\Http\Controllers\Nfs\Model;

use Illuminate\Support\Facades\DB;
use stdClass;

class Servico {

    private $discriminacaoServico;
    private $quantidade;
    private $valorUnitario;
    private $valorTotal;

    public function loadItens($empresa, $numero, $serie)
    {
        $itens = array();

        $sql = "SELECT DISCRIMINACAOSERVICO, QUANTIDADE, VALORUNITARIO, VALORTOTAL 
                FROM vi_rpsxml_itens 
                WHERE empresa = '$empresa' and 
                      numerorps = $numero and 
                      trim(serierps) = trim('$serie')";
        
        $rs = $this->Select($sql);

        foreach ($rs as $dados) {
            $item = new Servico();

            $item->discriminacaoServico = trim($dados['DISCRIMINACAOSERVICO']);
            $item->quantidade = $dados['QUANTIDADE'];
            $item->valorUnitario = $dados['VALORUNITARIO'];
            $item->valorTotal= $dados['VALORTOTAL'];

            $itens[] = $item;
        }

        unset($rs);

        return $itens;
    }

    /* Sobrescrve o método mágico __get */
    public function __get($atributo){
        return $this->$atributo;
    }
}
