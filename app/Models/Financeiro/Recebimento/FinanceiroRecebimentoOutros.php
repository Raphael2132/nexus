<?php

namespace App\Models\Financeiro\Recebimento;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceiroRecebimentoOutros extends Model
{
    use HasFactory;

    protected $primaryKey = 'recout_id';
    
    protected $fillable = [
        'recout_emp',
        'recout_cod_rec',
        'recout_tip_cre',
        'recout_cli',
        'recout_num_com',
        'recout_cmp',
        'recout_vlr',
        'recout_dtp',
        'recout_dtc',
    ];
}
