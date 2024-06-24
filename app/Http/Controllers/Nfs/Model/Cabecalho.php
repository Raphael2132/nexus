<?php

namespace App\Http\Controllers\Nfs\Model;

use Illuminate\Support\Facades\DB;
use stdClass;

class Cabecalho {

    private $codMunEmp;
    private $cnpjEmp;
    private $razaoEmp;
    private $dataEmi;
    private $qtdNFS;
    private $valorTotalServicos;
    private $valorTotalDeducoes;
    private $valorTotalNota;
    private $observacaoNota;

    public function __construct(string $empresa, int $numero, int $numControle){

        echo "<br> Entrei Model Cabecalho <br>";

        $sql = "SELECT 
                    cod_mun_empresa, 
                    cnpj_empresa, 
                    razao_empresa, 
                    data_emissao, 
                    qtd_nfs, 
                    valor_tot_servico,
                    valor_tot_deducoes,
                    valor_tot_nota,
                    observacao_nfs
                FROM vi_nfsxml_cabecalho 
                WHERE 
                    empresa = '".$empresa."' and 
                    num_controle = ".$numControle." and 
                    num_nf = ".$numero;
        $rs = DB::select($sql);

        $dados = $rs[0];

        $this->codMunEmp = $dados->cod_mun_empresa;
        $this->cnpjEmp = $dados->cnpj_empresa;
        $this->razaoEmp = $dados->razao_empresa;
        $this->dataEmi = $dados->data_emissao;
        $this->qtdNFS = $dados->qtd_nfs;
        $this->valorTotalServicos = $dados->valor_tot_servico;
        $this->valorTotalDeducoes = $dados->valor_tot_deducoes;
        $this->valorTotalNota = $dados->valor_tot_nota;
        $this->observacaoNota = $dados->observacao_nfs;
        
        echo "<br> Teste dados Cabecalho: ".$this->razaoEmp." <br>";
        
        unset($rs);
        unset($dados);
    }

    /* Sobrescrve o método mágico __get */
    public function __get($atributo){
        return $this->$atributo;
    }
}
