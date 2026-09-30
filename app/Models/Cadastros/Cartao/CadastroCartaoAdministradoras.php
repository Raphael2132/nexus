<?php

namespace App\Models\Cadastros\Cartao;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CadastroCartaoAdministradoras extends Model
{
    use HasFactory;

    protected $primaryKey = 'administradora_id';
    
    protected $fillable = [
        'administradora_codigo',
        'administradora_nome',
        'administradora_cnpj',
        'administradora_tipo',
        'administradora_parcela'
    ];
}
