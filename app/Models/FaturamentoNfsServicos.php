<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaturamentoNfsServicos extends Model
{
    use HasFactory;

    protected $primaryKey = 'nfssrv_id';
    
    protected $fillable = [
        'nfssrv_emp',
        'nfssrv_num',
        'nfssrv_req',
        'nfssrv_seq',
        'nfssrv_nnfs',
        'nfssrv_snfs',
        'nfssrv_req_dsc',
        'nfssrv_tmo',
        'nfssrv_tmo_dsc',
        'nfssrv_qtd_hr',
        'nfssrv_vlr_hr',
        'nfssrv_vlr_tot',
        'nfssrv_vlr_dsc',
        'nfssrv_vlr_liq'
    ];
}
