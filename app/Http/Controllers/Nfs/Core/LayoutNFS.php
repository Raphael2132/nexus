<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Nfs\Layouts\SaoJoaoDaBoaVista;
use App\Http\Controllers\Nfs\Util\Config;

class LayoutNFS{
    
    private static $nfsxml;
    private $documentoXML;
    private $nomeArquivo;
    private static $config;

    public function __construct(Nfsxml $nfsxml){

        $this->nfsxml = $nfsxml;

        $this->config = Config::getInstance($this->nfsxml);
    }

    public function gerarXML(){

        $layout = $this->config->layout;

        switch ($layout) {
            case 1:
                $this->documentoXML = SaoJoaoDaBoaVista::nfsxml($this->nfsxml);
                break;
            default:
                # code...
                break;
        }
        
    }

    public function gravaXML()
    {

        $nomeArquivoDownload = Misc::gerarNomeArquivo($this->nfsxml);
        $path = $this->config->pathDownload;
        $path .= $nomeArquivoDownload;
        $this->documentoXML->save($path);


        $this->nomeArquivo = Misc::gerarNomeArquivo($this->nfsxml);
        $path = $this->config->path;
        $path .= $this->nomeArquivo;
        //echo "<h3 color='red'>$path</h3>";
        $this->documentoXML->save($path);

        return $nomeArquivoDownload;

    }

    public function processaXMLRetorno(){
        $retorno = null;

        libxml_use_internal_errors(true);

        switch ($this->config->layout) {
            case 1:
                $retorno = SaoJoaoDaBoaVista::retorno($this->config->path, $this->nomeArquivo);
                break;
            default:
                # code...
                break;
        }
        
        libxml_use_internal_errors(false);

        return $retorno;

    }
}
