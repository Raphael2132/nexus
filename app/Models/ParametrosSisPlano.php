<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametrosSisPlano extends Model
{
    use HasFactory;

    protected $primaryKey = 'plano_id';
    
    protected $fillable = [
        'plano_id',
        'plano_codigo',
        'plano_qtd_usuarios',
        'plano_nome',
        'plano_mod_srv',
        'plano_mod_srv_cp',
        'plano_mod_nfs',
        'plano_mod_nfs_simp',
    ];
}
