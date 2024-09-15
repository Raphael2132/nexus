<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CadastroEmpresa extends Model
{
    use HasFactory;

    protected $primaryKey = 'empresa_id';
    
    protected $fillable = [
        'empresa_codigo',
        'empresa_nome',
        'empresa_cnpj',
        'empresa_email',
        'empresa_tel_celular',
        'empresa_tel_comercial',
        'empresa_insc_estadual',
        'empresa_insc_municipal',
        'empresa_nome_logo',
        'empresa_smtp_host',
        'empresa_smtp_port',
        'empresa_smtp_username',
        'empresa_smtp_password',
        'empresa_smtp_encryption',
        'empresa_smtp_from_address'
    ];

    protected $table = 'cadastro_empresas';

    public function usuarios()
    {
        return $this->hasMany(User::class, 'usuario_empresa', 'empresa_codigo');
    }
}
