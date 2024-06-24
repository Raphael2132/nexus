<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LancamentoSrvOsServico extends Model
{
    use HasFactory;

    protected $primaryKey = 'srv_id';
    
    protected $fillable = [
        'srv_emp',
        'srv_nos',
        'srv_req',
        'srv_seq',
        'srv_prt',
        'srv_set',
        'srv_are',
        'srv_sts',
        'srv_tmo',
        'srv_dsc',
        'srv_cmp',
        'srv_ths',
        'srv_qhr',
        'srv_vhr',
        'srv_vts',
        'srv_per_des',
        'srv_val_des',
        'srv_vtl',
        'srv_aut_desc',
        'srv_dti',
        'srv_hri',
        'srv_dtf',
        'srv_hrf',
        'srv_for',
        'srv_nft',
        'srv_srt',
        'srv_dtt',
        'srv_tcg',
        'srv_pcg',
        'srv_vcg',
        'srv_dhc',
        'srv_res_can',
        'srv_flg_apr',
        'srv_res_apr',
        'srv_dh_apr',
        'srv_und'
    ];
}
