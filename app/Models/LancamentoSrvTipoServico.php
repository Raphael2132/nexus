<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LancamentoSrvTipoServico extends Model
{
    use HasFactory;

    protected $primaryKey = 'tipsrv_id';
    
    protected $fillable = [
        'tipsrv_emp',
        'tipsrv_cod',
        'tipsrv_nom',
        'tipsrv_are',
        'tipsrv_cat',
        'tipsrv_pmt_des',
        'tipsrv_pmd',
        'tipsrv_vmd',
        'tipsrv_ahs',
        'tipsrv_avs',
        'tipsrv_sts'
    ];
}
