<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LancamentoSrvExeTarefa extends Model
{
    use HasFactory;

    protected $primaryKey = 'exetrf_id';
    
    protected $fillable = [
        'exetrf_emp',
        'exetrf_nos',
        'exetrf_req',
        'exetrf_seq',
        'exetrf_tmo',
        'exetrf_desc',
        'exetrf_cmp',
        'exetrf_are',
        'exetrf_set',
        'exetrf_ths',
        'exetrf_qhr',
        'exetrf_prt',
        'exetrf_sts',
        'exetrf_dt_inc',
        'exetrf_dt_ini_srv',
        'exetrf_hr_ini_srv',
        'exetrf_dt_fin_srv',
        'exetrf_hr_fin_srv',
        'exetrf_qhr_real',
        'exetrf_qhr_saldo',
        'exetrf_dt_can_srv',
        'exetrf_hr_can_srv',
        'exetrf_mot_can_srv',
        'exetrf_dt_sus_srv',
        'exetrf_hr_sus_srv',
        'exetrf_mot_sus_srv',
        'exetrf_obs',
        'exetrf_usu',
        'exetrf_dt_prev_ent',
        'exetrf_hr_prev_ent',
        'exetrf_age',
        'exetrf_dt_age_tmo',
        'exetrf_hr_age_tmo',
        'exetrf_dt_age_fin_tmo',
        'exetrf_hr_age_fin_tmo'
    ];
}
