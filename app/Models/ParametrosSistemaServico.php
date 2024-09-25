<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametrosSistemaServico extends Model
{
    use HasFactory;

    protected $primaryKey = 'servico_id';
    
    protected $fillable = [
        'servico_grupo',
        'servico_codigo',
        'servico_desc',
    ];
}
