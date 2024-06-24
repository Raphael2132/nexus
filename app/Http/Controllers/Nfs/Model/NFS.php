<?php 

namespace App\Http\Controllers\Nfs\Model;

use Illuminate\Support\Facades\DB;
use stdClass;

class NFS {


    public $empresa;
    public $numero;
    public $serie;
    private $numControle;

    private $dataEmissao;
    private $situacao;
    private $descricao;

    private $valorTotalServicos;
    private $valorTotalDeducoes;

    private $cabecalho;
    private $prestador;
    private $tomador;
    private $tributacao;
    private $parametros;
    private $itens;


    public function __construct(string $empresa, int $numero, string $serie, int $numControle){

        $this->empresa = $empresa;
        $this->numero = $numero;
        $this->serie = $serie;
        $this->numControle = $numControle;
        echo "<br> Iniciando NFS <br>";
        $this->iniciaNFS();

    }

    /* Sobrescrve o método mágico __get */
    public function __get($atributo){
        return $this->$atributo;
    }

    public function iniciaNFS(){

        echo "<br> metodo iniciaNFS <br>";
        
        echo "<br> dados: ".$this->empresa." / ".$this->numero." / ".$this->serie." / ".$this->numControle." <br>";

        $sql = "SELECT 
                    nfs_dt_emi, 
                    nfs_hr_emi, 
                    nfs_sts, 
                    nfs_vlr_srv, 
                    nfs_vlr_ded 
                FROM faturamento_nfs  
                WHERE 
                    nfs_emp = '".$this->empresa."' and 
                    nfs_nnfs = ".$this->numero." and
                    trim(nfs_snfs) = trim('".$this->serie."') and  
                    nfs_nfhdr_num = ".$this->numControle;

        $rs = DB::select($sql);

        $dados = $rs[0];

        $this->dataEmissao = $dados->nfs_dt_emi;
        
        $this->horaEmissao = substr_replace($dados->nfs_hr_emi, ':', 2, 0);

        $this->situacao = $dados->nfs_sts;

        $this->valorTotalServicos = $dados->nfs_vlr_srv;

        $this->valorTotalDeducoes = $dados->nfs_vlr_ded;

        echo "<br> dados: ".$this->dataEmissao." / ".$this->horaEmissao." / ".$this->situacao." / ".$this->valorTotalServicos." / ".$this->valorTotalDeducoes." <br>";

        unset($rs);
        unset($dados);

        /*
        $sql = "SELECT RPD_TXTSER  
                FROM TB_FATRPD  
                WHERE rpd_codemi = '".$this->empresa."' and 
                      trim(rpd_serrps) = trim('".$this->serie."') and  
                      rpd_numrps = ".$this->numero." and RPD_SEQUEN  >= 900";

        $rs = DB::select($sql);

        $qtdLinhas = count($rs);
        $i = 1;
        foreach ($rs as $linha) {
            $this->descricao .= trim($linha['RPD_TXTSER']);

            if($i < $qtdLinhas){
                $this->descricao .= ' ';
            }
        }

        unset($rs);
        */


        echo "<br> Vou começar a montar os Models <br>";
        
        $this->cabecalho = new Cabecalho($this->empresa, $this->numero, $this->numControle);

        $this->prestador = new Prestador($this->empresa, $this->numero, $this->numControle);
        
        $this->tomador = new Tomador($this->empresa, $this->numero, $this->numControle);
        
        $this->tributacao = new Tributacao($this->empresa, $this->numero, $this->numControle);
        
        $this->parametros = new Parametros($this->empresa);
        
        $item = new Servico();
        
        $this->itens = $item->carregaServicos($this->empresa, $this->numero, $this->numControle);
    }

}
?>
