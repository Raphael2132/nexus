<?php

namespace App\Models\Parametros\Sistema;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametrosSisServicoGrupo extends Model
{
    use HasFactory;

    protected $primaryKey = 'grupo_id';
    
    protected $fillable = [
        'grupo_codigo',
        'grupo_desc'
    ];
}
