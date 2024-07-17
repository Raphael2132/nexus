<?php

namespace App\Http\Controllers\Nfs\Util;

use Illuminate\Support\Facades\DB;
use stdClass;
use App\Http\Controllers\Nfs\Core\Nfsxml;

class Config{
    
    public $layout;
    public $path;
    public $pathDownload;
    public $pathDownloadRetorno;

    private static $instance;
    
    /* *****
        Retorna uma instância única de uma classe.
     
        @staticvar Config $instance A instância única dessa classe.
     
        @return Config A Instância única.
     ***** */
    public static function getInstance($nfsxml)
    {

        //echo "<br> Entrei no Config <br>";

        $instance = null;
        if (null === self::$instance) {
            self::$instance = new Config();
            self::$instance->setLayout($nfsxml->empresa);
            self::$instance->setXMLPath($nfsxml->nfs->cabecalho->cnpjEmp);
            self::$instance->checkPathDownload();

        }

        return self::$instance;
    }

    private function setLayout($empresa){

        //echo "<br> Entrei no setLayout <br>";

        $sql = "select parnfs_provedor from parametros_fat_nfs where parnfs_empresa = '".$empresa."'";

        $rs = DB::select($sql);

        if(empty($rs)){
            throw new Exception('Config:: Layout nao parametrizado!');
        }
		else{
            $this->layout = $rs[0]->parnfs_provedor;
        }
       // echo "<br> Provedor: ".$this->layout." <br>";
    }

    private function setXMLPath($cnpj){
        
        //echo "<br> Entrei no setXMLPath <br>";

        $this->path = $_SERVER['DOCUMENT_ROOT'];//Por hora vai no root quando estiver em servidor ver como vai ficar
        $this->pathDownload = $_SERVER['DOCUMENT_ROOT'].'/'.$cnpj.'/file/doc/nfsxml/envio/';
        $this->pathDownloadRetorno = $_SERVER['DOCUMENT_ROOT'].'/'.$cnpj.'/file/doc/nfsxml/retorno/';

        // Obter o endereço IP do servidor
        if(!empty($_SERVER['SERVER_ADDR'])){
            $serverIP = $_SERVER['SERVER_ADDR'];
        }else{
            $serverIP = '';
        }

        // Obter o nome do host do servidor
        if(!empty($_SERVER['SERVER_NAME'])){
            $serverName = $_SERVER['SERVER_NAME'];
        }else{
            $serverName = '';
        }

        // Verificar se está rodando no localhost
        if ($serverIP == '127.0.0.1' || $serverIP == '::1' || stripos($serverName, 'localhost') !== false) {
            echo "O servidor está rodando no localhost.";
        } else {
            echo "O servidor está rodando em um IP: " . $serverIP;
        }

        echo "<br> serverIP: ".$serverIP." serverName: ".$serverName."<br>";
        echo "<br> Document Root: path: ".$_SERVER['DOCUMENT_ROOT']."<br>";
        echo "<br> Teste setXMLPath: path D: ".$this->path.' / pathDownload D: '.$this->pathDownload."<br>";
        echo "<br> Teste setXMLPath: path R: ".$this->path.' / pathDownload R: '.$this->pathDownloadRetorno."<br>";exit;
    }

    /* Verifica se o diretório onde é gravado o XML para download existe e caso nõa exista cria-lo */
    private function checkPathDownload(){
        
        //echo "<br> Entrei no checkPathDownload <br>";

        if(!file_exists($this->pathDownload)){
            $status = mkdir($this->pathDownload, 0775, true);

            if(!$status){
                throw new Exception('Config:: Falha ao criar o diretório NFSXML/ENVIO');
            }
        }

        if(!file_exists($this->pathDownloadRetorno)){
            $status = mkdir($this->pathDownloadRetorno, 0775, true);

            if(!$status){
                throw new Exception('Config:: Falha ao criar o diretório NFSXML/RETORNO');
            }
        }
    }

    public static function gerarNomeArquivo(Nfsxml $nfsxml)
    {

        //echo "<br> Entrei no gerarNomeArquivo <br>";
        $nomeArquivo = "nfs_".$nfsxml->empresa."_".$nfsxml->nfs->numero.".xml";

        //echo "<br> Nome Arquivo: ".$nomeArquivo."<br>";

        return $nomeArquivo;
    }

    public static function gerarNomeArquivoRetorno(Nfsxml $nfsxml)
    {

        //echo "<br> Entrei no gerarNomeArquivo <br>";
        $nomeArquivo = "retorno_nfs_".$nfsxml->empresa."_".$nfsxml->nfs->numero.".xml";

        //echo "<br> Nome Arquivo Retorno: ".$nomeArquivo."<br>";

        return $nomeArquivo;
    }


    /**
     * Construtor do tipo protegido previne que uma nova instância da
     * Classe seja criada através do operador `new` de fora dessa classe.
     */
    protected function __construct()
    {
    }

    /**
     * Método clone do tipo privado previne a clonagem dessa instância
     * da classe
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Método unserialize do tipo privado para prevenir a desserialização
     * da instância dessa classe.
     *
     * @return void
     */
    public function __wakeup()
    {
    }
}
