<?php

namespace App\Models\Parametros\Sistema;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametrosSisArea extends Model
{
    use HasFactory;

    protected $primaryKey = 'area_id';
    
    protected $fillable = [
        'area_codigo',
        'area_desc'
    ];
}
