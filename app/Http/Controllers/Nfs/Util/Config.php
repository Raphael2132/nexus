<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use stdClass;

class Config{
    
    public static $layout;
    public static $path;
    public static $pathDownload;

    private static $instance;
    
    /* *****
        Retorna uma instância única de uma classe.
     
        @staticvar Config $instance A instância única dessa classe.
     
        @return Config A Instância única.
     ***** */
    public static function getInstance($nfsxml)
    {
        $instance = null;
        if (null === self::$instance) {
            self::$instance = new Config();
            self::$instance->setLayout($nfsxml->empresa);
            self::$instance->setXMLPath($nfsxml->cabecalho->cnpj);//ver depois como vai fazer aki para baixo
            self::$instance->pathDownload =  (empty($_SESSION['dir_doc']) ? $_SERVER['DOCUMENT_ROOT'] : str_ireplace("/file/doc", "", $_SESSION['dir_doc']) ).'/file/rpsxml/';
            self::$instance->checkPathDownload();
        }

        return self::$instance;
    }

    private function setLayout($empresa){

        $sql = "select parnfs_provedor from parametros_fat_nfs where parnfs_empresa = '".$empresa."'";

        $rs = DB::select($sql);

        if(empty($rs)){
            throw new Exception('Config:: Layout nao parametrizado!');
        }
		else{
            $this->layout = $rs[0]['parnfs_provedor'];
        }
        
    }

    private function setXMLPath($cnpj){
        
        $erro = 'N';
        $arquivo_conf = "/etc/serconsvr/nfsemgrd.conf";

        if(!is_file($arquivo_conf)){
            
            $arquivo_conf = "/etc/extsvr/nfsemgrd.conf";

            if(!is_file($arquivo_conf)){
                $erro = 'S';
            }
        }

        if($erro == 'S'){

            throw new Exception('Config:: Arquivo de configuracao do Servico de RPS nao encontrado');

        }else{
            $confs = Misc::iniParser($arquivo_conf);
            
            foreach ($confs as $conf) {
                if($conf['CNPJ'] == $cnpj){

                    $dir = $conf['DIR'];

                    if(substr($dir, -1) != '/')
                        $dir .= '/';

                    $this->path = $dir;
                }
            }
        }
    }

    /* Verifica se o diretório onde é gravado o XML para download existe e caso nõa exista cria-lo */
    private function checkPathDownload(){
        
        if(!file_exists($this->pathDownload)){
            $status = mkdir($this->pathDownload, 0775);

            if(!$status){
                throw new Exception('Config:: Falha ao criar o diretório RPSXML');
            }
        }
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
    private function __wakeup()
    {
    }
}
