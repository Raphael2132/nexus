<?php

namespace App\Models\Parametros\Sistema;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cadastros\Usuario\User;

class ParametrosSisModulo extends Model
{
    use HasFactory;

    protected $primaryKey = 'modulo_id';
    
    protected $fillable = [
        'modulo_empresa_codigo',
        'modulo_emissao_nfs',
        'modulo_emissao_nfe',
        'modulo_emissao_nfs_simp',
        'modulo_servico',
        'modulo_emissao_rps',
        'modulo_qtd_usuarios',
        'modulo_dt_validade',
        'modulo_controle_producao',
        'modulo_plano',
        'modulo_qtd_usuarios_ext',
    ];

    protected $table = 'parametros_sis_modulos';

    public function usuarios()
    {
        return $this->hasMany(User::class, 'usuario_empresa', 'modulo_empresa_codigo');
    }
}
