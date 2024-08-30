<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\FaturamentoNfHeader;
use stdClass;
use App\Http\Helpers\Helper;

class FaturamentoNfHeaderController extends Controller
{
    protected $headerNF;
    
    public function __construct(FaturamentoNfHeader $headerNF)
    {
        $this->headerNF = $headerNF;
    }
}
