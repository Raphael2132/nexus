<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\ParametrosSistemaModulo;

class ModulosService
{
    public function getModulos()
    {
        $user = Auth::user();
        return $user ? $user->modulos : null;
    }
}
