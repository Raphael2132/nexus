<?php

namespace App\Models\Financeiro\Pagamento;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceiroPagamentoContasCorrentes extends Model
{
    use HasFactory;

    protected $primaryKey = 'pagcct_id';
    
    protected $fillable = [
        'pagcct_emp',
        'pagcct_cod_pag',
        'pagcct_cli',
        'pagcct_tip_cct',
        'pagcct_num_cct',
        'pagcct_tip_opr',
        'pagcct_dte',
        'pagcct_dtp',
        'pagcct_dtv',
        'pagcct_vlr_cct',
        'pagcct_vlr_acr',
        'pagcct_vlr_des',
        'pagcct_vlr_mul',
        'pagcct_vlr_pag',
        'pagcct_vlr_vrm',
        'pagcct_vlr_tot',
        'pagcct_cmp',    
        'pagcct_obs'  
    ];
}
