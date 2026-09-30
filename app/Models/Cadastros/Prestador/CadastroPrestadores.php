<?php

namespace App\Models\Cadastros\Prestador;

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
        'prestador_usuario_cod',
        'prestador_tur_cod',
        'prestador_int_srv',
        'prestador_hr_ini_int',
        'prestador_hr_fin_int',
        'prestador_int_srv_sab',
        'prestador_hr_ini_int_sab',
        'prestador_hr_fin_int_sab',
        'prestador_int_srv_dom',
        'prestador_hr_ini_int_dom',
        'prestador_hr_fin_int_dom'
    ];
}
