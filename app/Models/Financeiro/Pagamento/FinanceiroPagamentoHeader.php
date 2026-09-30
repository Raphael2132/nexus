<?php

namespace App\Models\Financeiro\Pagamento;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceiroPagamentoHeader extends Model
{
    use HasFactory;

    protected $primaryKey = 'paghdr_id';
    
    protected $fillable = [
        'paghdr_emp',
        'paghdr_cod_pag',
        'paghdr_sts',
        'paghdr_ori',
        'paghdr_dti',
        'paghdr_usu',
        'paghdr_tip_raz',
        'paghdr_raz',
        'paghdr_num_rcb',
        'paghdr_obs'
    ];
}
