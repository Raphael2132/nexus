<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametrosSistemaModulo extends Model
{
    use HasFactory;

    protected $primaryKey = 'modulo_id';
    
    protected $fillable = [
        'modulo_empresa_codigo',
        'modulo_emissao_nfs',
        'modulo_emissao_nfe'
    ];
}
