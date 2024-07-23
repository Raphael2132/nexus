<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'usuario_codigo',
        'usuario_status',
        'usuario_tipo',
        'usuario_altera_permissoes_acesso',
        'usuario_acesso_pararametros',
        'usuario_acesso_cadastros',
        'usuario_cpf',
        'usuario_rg',
        'usuario_data_nascimento',
        'usuario_sexo',
        'usuario_tel_residencial',
        'usuario_tel_celular',
        'usuario_tipo_email',
        'usuario_aut_desc',
        'usuario_empresa',
        'usuario_acesso_mod_servicos',
        'usuario_acesso_mod_nf'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function empresa()
    {
        return $this->belongsTo(CadastroEmpresa::class, 'usuario_empresa', 'empresa_codigo');
    }

    public function modulos()
    {
        return $this->belongsTo(ParametrosSistemaModulo::class, 'usuario_empresa', 'modulo_empresa_codigo');
    }
}
