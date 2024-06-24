<?php

namespace App\Http\Controllers\Nfs\Core;

use App\Http\Controllers\Nfs\Layouts\SaoJoaoDaBoaVista;
use App\Http\Controllers\Nfs\Util\Config;

class LayoutNFS{
    
    private static $nfsxml;
    public $documentoXML;
    public $documentoRetornoXML;
    private $nomeArquivo;
    public $config;

    public function __construct(Nfsxml $nfs){

        echo "<br> Entrei no LayoutNFS <br>";

        static::$nfsxml = $nfs;
        $this->config = Config::getInstance(static::$nfsxml);
    }

    public function gerarXML(){

        echo "<br> Entrei no gerarXML <br>";
        
        $layout = $this->config->layout;

        switch ($layout) {
            case 1:
                $this->documentoXML = SaoJoaoDaBoaVista::nfsxml(static::$nfsxml);
                break;
            default:
                # code...
                break;
        }
        
        echo "<br> Finalizei o xml <br>";
    }

    public function gravaXML()
    {

        echo "<br> Entrei no gravaXML <br>";

        $nomeArquivoDownload = Config::gerarNomeArquivo(static::$nfsxml);
        $path = $this->config->pathDownload;
        $path .= $nomeArquivoDownload;
        $this->documentoXML->save($path);

        return $nomeArquivoDownload;

    }

    public function enviarXML()
    {

        echo "<br> Entrei no enviarXML <br>";

        $layout = $this->config->layout;

        echo "<pre>".htmlentities($this->documentoXML->saveHTML())."</pre>";

        switch ($layout) {
            case 1:
                $this->documentoRetornoXML = SaoJoaoDaBoaVista::startConnection(static::$nfsxml, $this->documentoXML);
                break;
            default:
                # code...
                break;
        }

    }
}
