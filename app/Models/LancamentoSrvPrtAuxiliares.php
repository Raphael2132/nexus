<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LancamentoSrvPrtAuxiliares extends Model
{
    use HasFactory;

    protected $primaryKey = 'prtaux_id';
    
    protected $fillable = [
        'prtaux_emp',
        'prtaux_nos',
        'prtaux_req',
        'prtaux_srv',
        'prtaux_seq',
        'prtaux_prt',
        'prtaux_dt_inc',
        'prtaux_hr_inc'
    ];
}
