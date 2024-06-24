<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CadastroPrestadoresEndereco extends Model
{
    use HasFactory;

    protected $primaryKey = 'endereco_id';
    
    protected $fillable = [
        'endereco_seq',
        'endereco_prestador_codigo',
        'endereco_principal',
        'endereco_cep',
        'endereco_logradouro',
        'endereco_numero',
        'endereco_complemento',
        'endereco_bairro',
        'endereco_cidade',
        'endereco_uf',
        'endereco_pais',
        'endereco_ibge_cod_mun'
    ];
}
