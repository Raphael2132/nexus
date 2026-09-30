<?php

namespace App\Models\Parametros\Servico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LancamentoSrvEtapaAtendimento extends Model
{
    use HasFactory;

    protected $primaryKey = 'eat_id';
    
    protected $fillable = [
        'eat_cod',
        'eat_emp',
        'eat_nom',
        'eat_ord',
        'eat_cat'
    ];
}
