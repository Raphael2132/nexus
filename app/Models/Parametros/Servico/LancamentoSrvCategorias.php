<?php

namespace App\Models\Parametros\Servico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LancamentoSrvCategorias extends Model
{
    use HasFactory;

    protected $primaryKey = 'categoria_id';
    
    protected $fillable = [
        'categoria_codigo',
        'categoria_desc'
    ];
}
