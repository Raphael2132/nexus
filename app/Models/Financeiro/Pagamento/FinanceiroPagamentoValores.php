<?php

namespace App\Models\Financeiro\Pagamento;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceiroPagamentoValores extends Model
{
    use HasFactory;

    protected $primaryKey = 'pagval_id';
    
    protected $fillable = [
        'pagval_emp',
        'pagval_cod_pag',
        'pagval_seq',
        'pagval_tipo',
        'pagval_ch_raz',
        'pagval_ch_bco',
        'pagval_ch_age',
        'pagval_ch_age_dv',
        'pagval_ch_ccr',
        'pagval_ch_ccr_dv',
        'pagval_ch_num',
        'pagval_ch_vct',
        'pagval_ch_res',
        'pagval_crt_adm',
        'pagval_crt_com',
        'pagval_crt_prc',
        'pagval_cct_tcc',
        'pagval_cct_res',
        'pagval_cct_ncc',
        'pagval_pix_bco',
        'pagval_pix_doc',
        'pagval_comp',
        'pagval_obs',
        'pagval_valor',
    ];
}
