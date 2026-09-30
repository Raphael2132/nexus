<?php

namespace App\Models\Financeiro\Transferencia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceiroTransferenciasItens extends Model
{
    use HasFactory;

    protected $primaryKey = 'tranitm_id';
    
    protected $fillable = [
        'tranitm_cod_trans',
        'tranitm_sequencia',
        'tranitm_situacao',
        'tranitm_tipo',
        'tranitm_cc_tipo',
        'tranitm_cc_cod',
        'tranitm_cc_res',
        'tranitm_cc_valor',
    ];
}
