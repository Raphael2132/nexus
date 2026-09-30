<?php

namespace App\Models\Financeiro\Pagamento;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceiroPagamentoPequenasDespesas extends Model
{
    use HasFactory;

    protected $primaryKey = 'pagpqd_id';
    
    protected $fillable = [
        'pagpqd_emp',
        'pagpqd_cod_pag',
        'pagpqd_tip_opr',
        'pagpqd_cod_des',
        'pagpqd_cli',
        'pagpqd_num_com',
        'pagpqd_cmp',
        'pagpqd_dtp',
        'pagpqd_vlr',
        'pagpqd_bco'
    ];
}
