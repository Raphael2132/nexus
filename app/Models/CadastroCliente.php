<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CadastroCliente extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'cliente_id';
    
    protected $fillable = [
        'cliente_tipo_cadastro',
        'cliente_codigo',
        'cliente_tipo_pessoa',
        'cliente_nome',
        'cliente_cpf_cnpj',
        'cliente_data_nascimento',
        'cliente_sexo',
        'cliente_email',
        'cliente_tel_residencial',
        'cliente_tel_celular',
        'cliente_tel_comercial',
        'cliente_rg',
        'cliente_insc_estadual',
        'cliente_insc_municipal',
        'cliente_tipo_email',
        'cliente_dt_inc',
        'cliente_dt_fundacao',
        'cliente_micro_emp',
        'cliente_cnae',
        'cliente_pref_contato',
        'cliente_dt_alt',
        'cliente_usu_alt',
        'cliente_ramo_atividade',
        'cliente_org_publico',
        'cliente_con_final',
    ];
}
