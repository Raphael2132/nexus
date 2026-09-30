<?php

namespace App\Models\Financeiro\Recebimento;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceiroRecebimentoHeader extends Model
{
    use HasFactory;

    protected $primaryKey = 'rechdr_id';
    
    protected $fillable = [
        'rechdr_emp',
        'rechdr_cod_rec',
        'rechdr_sts',
        'rechdr_ori',
        'rechdr_dti',
        'rechdr_usu',
        'rechdr_tip_raz',
        'rechdr_raz',
        'rechdr_obs',
    ];
}
