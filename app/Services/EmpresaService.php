<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\CadastroEmpresa;

class EmpresaService
{
    public function getEmpresa()
    {
        $user = Auth::user();
        return $user ? $user->empresa : null;
    }
}
