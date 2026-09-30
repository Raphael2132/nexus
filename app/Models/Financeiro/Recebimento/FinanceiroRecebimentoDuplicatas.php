<?php

namespace App\Models\Financeiro\Recebimento;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceiroRecebimentoDuplicatas extends Model
{
    use HasFactory;

    protected $primaryKey = 'recdup_id';
    
    protected $fillable = [
        'recdup_emp',
        'recdup_cod_rec',
        'recdup_cod',
        'recdup_seq',
        'recdup_cli',
        'recdup_dte',
        'recdup_dtv',
        'recdup_vlr_dup',
        'recdup_vlr_pag',
        'recdup_vlr_bxa',
        'recdup_vlr_acr',
        'recdup_vlr_des',
        'recdup_vlr_aba',
        'recdup_vlr_jmd',
        'recdup_vlr_jmt',
        'recdup_dia_atr',
        'recdup_tip_opr',
        'recdup_vlr_pis',
        'recdup_vlr_cofins',
        'recdup_vlr_csll',
        'recdup_vlr_irrf',
        'recdup_vlr_irrf_ns',
        'recdup_vlr_issqn',
    ];
}
