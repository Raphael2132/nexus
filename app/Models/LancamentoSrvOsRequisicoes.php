<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LancamentoSrvOsRequisicoes extends Model
{
    use HasFactory;

    protected $primaryKey = 'req_id';
    
    protected $fillable = [
        'req_emp',
        'req_nos',
        'req_seq',
        'req_dsc',
        'req_tos',
        'req_cat',
        'req_set',
        'req_are',
        'req_eat',
        'req_dhi',
        'req_dhc',
        'req_dt_apr',
        'req_res_apr',
        'req_vlr',
        'req_vls',
        'req_vlp',
        'req_tvd',
        'req_per_des',
        'req_val_des',
        'req_sts'
    ];
}
