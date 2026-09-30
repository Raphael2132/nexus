<?php

namespace App\Http\Controllers\Parametros\Sistema;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Parametros\Sistema\ParametrosSisServicoGrupo;
use App\Models\Parametros\Sistema\ParametrosSisServico;

class HomeParametrosSistemaController extends Controller
{
    protected $parametrosGrpServico;
    protected $parametrosServico;

    public function __construct(ParametrosSisServicoGrupo $parametrosGrpServico, ParametrosSisServico $parametrosServico)
    {
        $this->parametrosGrpServico = $parametrosGrpServico;
        $this->parametrosServico = $parametrosServico;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Home da Parametrização dos Grupos e Serviços da NFS-e
    |----------------------------------------------------------------------------------------------------
    */
    public function homeParSisServico()
    {    
        $grupos_srv = $this->parametrosGrpServico->reorder('grupo_codigo', 'asc')->get();
        $servicos = $this->parametrosServico->reorder('servico_grupo', 'asc')->reorder('servico_codigo', 'asc')->get();

        return view('/parametros/sistema/homeParametrosSistemaServicos', ['grupos'=>$grupos_srv, 'servicos'=>$servicos]);
    }
}
