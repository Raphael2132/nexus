<?php

namespace App\Models;

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
        'parsrv_hr_ini_ex',
        'parsrv_hr_fin_ex',
        'parsrv_grp_srv',
        'parsrv_cod_srv',
        'parsrv_exg_iss',
        'parsrv_iss_ret'
    ];
}
