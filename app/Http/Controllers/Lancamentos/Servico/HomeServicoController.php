<?php

namespace App\Http\Controllers\Lancamentos\Servico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeServicoController extends Controller
{
    /*
    |------------------------------------------------------------------------------------------
    | Home da Emissão de OS
    |------------------------------------------------------------------------------------------
    */
    public function homeEmissaoOS()
    {    
        return view('/lancamentos/servico/homeEmissaoOS');
    }
}
