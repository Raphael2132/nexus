<?php

namespace App\Models\Financeiro\Recebimento;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceiroRecebimentoNota extends Model
{
    use HasFactory;

    protected $primaryKey = 'recnf_id';
    
    protected $fillable = [
        'recnf_emp',
        'recnf_cod_rec',
        'recnf_seq',
        'recnf_num',
        'recnf_num_ped',
        'recnf_num_nf',
        'recnf_ser_nf',
        'recnf_cli',
        'recnf_cme',
        'recnf_vlr_tot',
        'recnf_vlr_sin',
    ];
}
