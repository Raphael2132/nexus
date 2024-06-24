<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CadastroPrestadores extends Model
{
    use HasFactory;

    protected $primaryKey = 'prestador_id';
    
    protected $fillable = [
        'prestador_codigo',
        'prestador_nome',
        'prestador_cpf',
        'prestador_empresa',
        'prestador_data_nascimento',
        'prestador_sexo',
        'prestador_tipo_email',
        'prestador_email',
        'prestador_tel_residencial',
        'prestador_tel_celular',
        'prestador_rg',
        'prestador_status',
        'prestador_data_admissao',
        'prestador_data_demissao',
        'prestador_set',
        'prestador_are',
        'prestador_acesso_sis',
        'prestador_usuario_cod'
    ];
}
