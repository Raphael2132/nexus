<?php

namespace App\Http\Controllers\Nfs\Model;

use Illuminate\Support\Facades\DB;
use stdClass;

class Parametros {

    private $provedorNFS;
    private $usuarioConexao;
    private $senhaConexao;
    private $wsdlConexao;
    private $tokenConexao;
    private $ambiente;

    public function __construct(string $empresa){

        //echo "<br> Entrei Model Parametros <br>";

        $sql = "SELECT  
                    conexao_provedor,
                    conexao_usuario,
                    conexao_senha,
                    conexao_token,
                    conexao_wsdl,
                    conexao_ambiente
                FROM parametros_fat_nfs_conexoes
                WHERE 
                    conexao_empresa = '".$empresa."'";

        $rs = DB::select($sql);
        $dados = $rs[0];
        
        $this->provedorNFS = $dados->conexao_provedor;
        $this->usuarioConexao = $dados->conexao_usuario;
        $this->senhaConexao = $dados->conexao_senha;
        $this->wsdlConexao = $dados->conexao_wsdl;
        $this->tokenConexao = $dados->conexao_token;
        $this->ambiente = $dados->conexao_ambiente;

        //echo "<br> Teste dados Parametros: ".$this->ambiente." <br>";

        unset($rs);
        unset($dados);
    }

    /* Sobrescrve o método mágico __get */
    public function __get($atributo){
        return $this->$atributo;
    }
}
