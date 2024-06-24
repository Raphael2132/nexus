<?php

namespace App\Http\Controllers\Nfs\Model;

use Illuminate\Support\Facades\DB;
use stdClass;

class Tomador {

    private $inscMunicipal;
    private $inscEstadual;
    private $tipoTomador;
    private $cpfCnpj;
    private $nomeTomador;
    private $telComercial;
    private $telCelular;
    private $telResidencial;
    private $logradouro;
    private $numero;
    private $complemento;
    private $bairro;
    private $cidade;
    private $uf;
    private $cep;
    private $pais;
    private $email;
    private $codigoCidadeIBGE;

    public function __construct(string $empresa, int $numero, int $numControle){

        echo "<br> Entrei Model Tomador <br>";

        $sql = "SELECT  
                    insc_municipal, 
                    insc_estadual,
                    tipo_tomador,
                    cpf_cnpj_tomador, 
                    nome_tomador, 
                    tel_comercial, 
                    tel_celular, 
                    tel_residencial, 
                    logradouro, 
                    numero, 
                    complemento, 
                    bairro,  
                    cidade, 
                    uf,
                    cep, 
                    email, 
                    cod_ibge_mun_tomador,
                    pais
                FROM vi_nfsxml_tomador
                WHERE 
                    empresa = '".$empresa."' and 
                    num_controle = ".$numControle." and 
                    num_nf =".$numero;
        $rs = DB::select($sql);
        $dados = $rs[0];
        
        $this->inscMunicipal = $dados->insc_municipal;
        $this->inscEstadual = $dados->insc_estadual;
        $this->tipoTomador = $dados->tipo_tomador;
        $this->cpfCnpj = $dados->cpf_cnpj_tomador;
        $this->nomeTomador = $dados->nome_tomador;
        $this->telComercial = $dados->tel_comercial;
        $this->telCelular = $dados->tel_celular;
        $this->telResidencial = $dados->tel_residencial;
        $this->logradouro = $dados->logradouro;
        $this->numero = $dados->numero;
        $this->complemento = $dados->complemento;
        $this->bairro = $dados->bairro;
        $this->cidade = $dados->cidade;
        $this->uf = $dados->uf;
        $this->cep = $dados->cep;
        $this->pais = $dados->pais;
        $this->email = $dados->email;
        $this->codigoCidadeIBGE = $dados->cod_ibge_mun_tomador;
        
        echo "<br> Teste dados Tomador: ".$this->nomeTomador." <br>";

        unset($rs);
        unset($dados);
    }

    /* Sobrescrve o método mágico __get */
    public function __get($atributo){
        return $this->$atributo;
    }
}
