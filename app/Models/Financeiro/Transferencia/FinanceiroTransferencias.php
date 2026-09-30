<?php

namespace App\Models\Financeiro\Transferencia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceiroTransferencias extends Model
{
    use HasFactory;

    protected $primaryKey = 'transf_id';
    
    protected $fillable = [
        'transf_situacao',
        'transf_usuario',
        'transf_tipo',
        'transf_tipo_ori',
        'transf_codigo_ori',
        'transf_tipo_dest',
        'transf_codigo_dest',
        'transf_data',
        'transf_observacao',
        'transf_dt_opr',
        'transf_cod_opr',
        'transf_nr_opr',
    ];
}
