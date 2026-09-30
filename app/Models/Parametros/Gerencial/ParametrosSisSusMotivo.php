<?php

namespace App\Models\Parametros\Gerencial;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametrosSisSusMotivo extends Model
{
    use HasFactory;

    protected $primaryKey = 'susmot_id';
    
    protected $fillable = [
        'susmot_codigo',
        'susmot_desc'
    ];
}
