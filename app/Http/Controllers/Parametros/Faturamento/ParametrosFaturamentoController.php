<?php

namespace App\Http\Controllers\parametros\faturamento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Parametros\Faturamento\Nfs\ParametrosFatNfs;
use App\Models\Parametros\Faturamento\Nfs\ParametrosFatNfsConexoes;

class ParametrosFaturamentoController extends Controller
{
    protected $parametrosNfs;
    protected $parametrosNfsConexao;

    public function __construct(ParametrosFatNfs $parametrosNfs, ParametrosFatNfsConexoes $parametrosNfsConexao)
    {
        $this->parametrosNfs = $parametrosNfs;
        $this->parametrosNfsConexao = $parametrosNfsConexao;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a APP Home de Parâmetros de Faturamento da Emissão de NFS-e
    |----------------------------------------------------------------------------------------------------
    */
    public function homeFatNfs()
    {    
        $emiNfs  = $this->parametrosNfs->all();
        $conNfs  = $this->parametrosNfsConexao->all();

        // Definir valores padrão da sessão
        session([
            'glo_appOrigem' => 'homeParametrosFatNfs'
        ]);

        return view('/parametros/faturamento/nfs/homeParametroFatNfs', ['emiNfs'=>$emiNfs, 'conNfs'=>$conNfs]);
    }
}
