<?php

namespace App\Models\Financeiro\Recebimento;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceiroRecebimentoValores extends Model
{
    use HasFactory;

    protected $primaryKey = 'recval_id';
    
    protected $fillable = [
        'recval_emp',
        'recval_cod_rec',
        'recval_seq',
        'recval_tipo',
        'recval_ch_bco',
        'recval_ch_age',
        'recval_ch_age_dv', 
        'recval_ch_ccr',
        'recval_ch_ccr_dv', 
        'recval_ch_num',
        'recval_ch_vct',
        'recval_ch_res',
        'recval_crt_adm',
        'recval_crt_ban',
        'recval_crt_com',
        'recval_crt_prc',
        'recval_cct_tcc',
        'recval_cct_res',
        'recval_cct_ncc',
        'recval_pix_bco',
        'recval_pix_doc',
        'recval_nf_num_ped',
        'recval_nf_num',
        'recval_nf_nnf',
        'recval_nf_nsr',
        'recval_nf_cli',
        'recval_comp',
        'recval_obs',
        'recval_valor',
    ];
}
