<?php

namespace App\Http\Controllers\Nfs\Core;

use Illuminate\Support\Facades\DB;
use stdClass;
use App\Http\Controllers\Nfs\Model\NFS;

class Nfsxml {

    public $empresa;
    public $numControle;

    public $cabecalho;
    public $nfs;
    private $layout;
    public $arquivo;

    /* ***** __construct *****
        @param String $empresa Código da empresa emissora
        @param int $numControle Número de Controle da NF
        @param int $nfs Número da NFS-e
    ***** */
    public function __construct($empresa, $numControle){

        $this->empresa = $empresa;
        $this->numControle = $numControle;

        echo "<br> iniciado o Nfsxml <br>";
        //$this->cabecalho = new Cabecalho($empresa, $nfs);//????????????

        $this->getNFS();

        echo 'aki';
        var_dump($this->nfs);
        exit;
        $this->layout = new LayoutNFS($this);

        $this->layout->gerarXML();

    }

    //Pega os dados da NFS-e
    private function getNFS() {

        echo "<br> metodo getNFS <br>";
        $this->lote = array();

        $sql = "SELECT nfs_nnfs,
                       nfs_snfs
                from faturamento_nfs
                where 
                    nfs_emp = '".$this->empresa."' and 
                    nfs_nfhdr_num = '".$this->numControle."'";

        $rs = DB::select($sql);
        $dados = $rs[0];
    
        echo "<br> dados: ".$this->empresa.' / '.$dados->nfs_nnfs.' / '.$dados->nfs_snfs." <br>";
        $this->nfs = new NFS($this->empresa, $dados->nfs_nnfs, $dados->nfs_snfs, $this->numControle);

        $this->nfs->empresa;
    }

    public function emitirRPS(){
        $this->nomeArquivo = $this->layout->gravaXML();

        Misc::startLNX();
    }

    public function getRetorno(){
        $retorno = $this->layout->processaXMLRetorno();

        return $retorno;
    }
}