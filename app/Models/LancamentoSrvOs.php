<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LancamentoSrvOs extends Model
{
    use HasFactory;

    protected $primaryKey = 'os_id';
    
    protected $fillable = [
        'os_nos',
        'os_emp',
        'os_cli',
        'os_cli_end',
        'os_dha',
        'os_res_abr',
        'os_dhf',
        'os_res_fec',
        'os_apr',
        'os_dt_apr',
        'os_res_apr',
        'os_dt_orc',
        'os_num_orc',
        'os_dtc',
        'os_res_can',
        'os_vlt',
        'os_vos',
        'os_vls',
        'os_vlp',
        'os_tip_des',
        'os_per_des',
        'os_val_des',
        'os_sts'
    ];
}
