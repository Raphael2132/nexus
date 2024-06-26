<?php

namespace App\Http\Controllers\Nfs\Model;

use Illuminate\Support\Facades\DB;
use stdClass;

class Tributacao {
    
    private $aliqAtividade;
    private $issRetido;
    private $situacao;
    private $valorPIS;
    private $valorCOFINS;
    private $valorINSS;
    private $valorIR;
    private $valorCSLL;
    private $aliqPIS;
    private $aliqCOFINS;
    private $aliqINSS;
    private $aliqIR;
    private $aliqCSLL;
    private $codTribMunicipio;
    private $valorISS;
    private $valorISSRetido;
    private $aliqISS;

    public function __construct(string $empresa, int $numero, int $numControle){
        
        //echo "<br> Entrei Model Tributacao <br>";

        $sql = "SELECT  
                    aliq_atividade, 
                    iss_retido, 
                    situacao, 
                    valor_pis, 
                    valor_cofins, 
                    valor_inss, 
                    valor_ir, 
                    valor_csll, 
                    aliq_pis, 
                    aliq_cofins, 
                    aliq_inss, 
                    aliq_ir, 
                    aliq_csll,
                    cod_trib_municipio, 
                    valor_iss, 
                    valor_iss_retido,
                    aliq_iss
                FROM vi_nfsxml_tributacao 
                WHERE 
                    empresa = '".$empresa."' and 
                    num_controle = ".$numControle." and 
                    num_nf =".$numero;
        $rs = DB::select($sql);
        $dados = $rs[0];

        $this->aliqAtividade = $dados->aliq_atividade;
        $this->issRetido = $dados->iss_retido;
        $this->situacao = $dados->situacao;
        $this->valorPIS = $dados->valor_pis;
        $this->valorCOFINS = $dados->valor_cofins;
        $this->valorINSS = $dados->valor_inss;
        $this->valorIR = $dados->valor_ir;
        $this->valorCSLL = $dados->valor_csll;
        $this->aliqPIS = $dados->aliq_pis;
        $this->aliqCOFINS = $dados->aliq_cofins;
        $this->aliqINSS = $dados->aliq_inss;
        $this->aliqIR = $dados->aliq_ir;
        $this->aliqCSLL = $dados->aliq_csll;
        $this->codTribMunicipio = $dados->cod_trib_municipio;
        $this->valorISS = $dados->valor_iss;
        $this->valorISSRetido = $dados->valor_iss_retido;
        $this->aliqISS = $dados->aliq_iss;

        //echo "<br> Teste dados Tributacao: ".$this->issRetido." <br>";

        unset($rs);
        unset($dados);
        
    }

    /* Sobrescrve o método mágico __get */
    public function __get($atributo){
        return $this->$atributo;
    }
}
