<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Empresa extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'empresa';
    }
}