<?php

namespace App\Models\Parametros\Servico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametrosSrvEmpresas extends Model
{
    use HasFactory;

    protected $primaryKey = 'parsrv_id';
    
    protected $fillable = [
        'parsrv_emp',
        'parsrv_alq_iss',
        'parsrv_cfop',
        'parsrv_grp_srv',
        'parsrv_cod_srv',
        'parsrv_exg_iss',
        'parsrv_iss_ret',
        'parsrv_env_rps_email',
        'parsrv_enc_os_email'
    ];
}
