<?php 

namespace App\Http\Controllers\Nfs\Model;

use Illuminate\Support\Facades\DB;
use stdClass;

class Prestador {

    private $inscMunicipal;
    private $razaoPrestador;
    private $codAtividade;
    private $codIbgeMunPrestador;
    private $ibgeMunPrestador;
    private $telComercial;
    private $telCelular;
    private $cnae;
    private $optSimples;
    private $logradouro;
    private $numero;
    private $complemento;
    private $bairro;
    private $municipioo;
    private $uf;
    private $cep;
    private $pais;

    public function __construct(string $empresa, int $numero, int $numControle){
        
        echo "<br> Entrei Model Prestador <br>";

        $sql = "SELECT 
                    insc_municipal, 
                    razao_prestador, 
                    cod_atividade,
                    cod_ibge_mun_prestador,
                    ibge_mun_prestador, 
                    tel_comercial, 
                    tel_celular,
                    cnae, 
                    opt_simples,
                    logradouro,
                    numero,
                    complemento,
                    bairro,
                    municipio,
                    uf,
                    cep,
                    pais 
                FROM vi_nfsxml_prestador 
                WHERE 
                    empresa = '".$empresa."' and 
                    num_controle = ".$numControle." and 
                    num_nf = ".$numero;
        $rs = DB::select($sql);

        $dados = $rs[0];

        $this->inscMunicipal = $dados->insc_municipal;
        $this->razaoPrestador = $dados->razao_prestador;
        $this->codAtividade = $dados->cod_atividade;
        $this->codIbgeMunPrestador = $dados->cod_ibge_mun_prestador;
        $this->ibgeMunPrestador = $dados->ibge_mun_prestador;
        $this->telComercial = $dados->tel_comercial;
        $this->telCelular = $dados->tel_celular;
        $this->cnae = $dados->cnae;
        $this->optSimples = $dados->opt_simples;
        $this->logradouro = $dados->logradouro;
        $this->numero = $dados->numero;
        $this->complemento = $dados->complemento;
        $this->bairro = $dados->bairro;
        $this->municipio = $dados->municipio;
        $this->uf = $dados->uf;
        $this->cep = $dados->cep;
        $this->pais = $dados->pais;

        echo "<br> Teste dados Prestador: ".$this->razaoPrestador." <br>";

        unset($rs);
        unset($dados);
    }

    /* Sobrescrve o método mágico __get */
    public function __get($atributo){
        return $this->$atributo;
    }

}
?>
