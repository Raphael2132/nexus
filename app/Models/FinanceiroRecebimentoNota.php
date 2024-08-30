<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceiroRecebimentoNota extends Model
{
    use HasFactory;

    protected $primaryKey = 'recnf_id';
    
    protected $fillable = [
        'recnf_id_rec',
        'recnf_emp',
        'recnf_num',
        'recnf_num_ped',
        'recnf_num_nf',
        'recnf_ser_nf',
        'recnf_dt_nf',
        'recnf_hr_nf',
        'recnf_cfop',
        'recnf_cme',
        'recnf_tor',
        'recnf_ori',
        'recnf_vlr_tot',
    ];
}
