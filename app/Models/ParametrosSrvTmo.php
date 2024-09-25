<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametrosSrvTmo extends Model
{
    use HasFactory;

    protected $primaryKey = 'tmo_id';
    
    protected $fillable = [
        'tmo_emp',
        'tmo_are',
        'tmo_set',
        'tmo_cod',
        'tmo_dsc',
        'tmo_cmp',
        'tmo_tip',
        'tmo_qtd_hr',
        'tmo_val_hr',
        'tmo_val_tot',
        'tmo_for_cgt',
        'tmo_tip_val_cgt',
        'tmo_val_cgt',
        'tmo_per_cgt',
        'tmo_res',
        'tmo_sts'
    ];
}
