<?php

namespace App\Models\Parametros\Sistema;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametrosSisServico extends Model
{
    use HasFactory;

    protected $primaryKey = 'servico_id';
    
    protected $fillable = [
        'servico_grupo',
        'servico_codigo',
        'servico_desc',
    ];
}
