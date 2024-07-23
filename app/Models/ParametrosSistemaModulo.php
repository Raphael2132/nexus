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
        'modulo_emissao_nfe',
        'modulo_emissao_nfs_simp',
        'modulo_servico',
        'modulo_emissao_rps'
    ];

    protected $table = 'parametros_sistema_modulos';

    public function usuarios()
    {
        return $this->hasMany(User::class, 'usuario_empresa', 'modulo_empresa_codigo');
    }
}
