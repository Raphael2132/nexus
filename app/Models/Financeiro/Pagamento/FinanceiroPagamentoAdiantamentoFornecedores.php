<?php

namespace App\Models\Financeiro\Pagamento;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceiroPagamentoAdiantamentoFornecedores extends Model
{
    use HasFactory;

    protected $primaryKey = 'pagaf_id';
    
    protected $fillable = [
        'pagaf_emp',
        'pagaf_cod_pag',
        'pagaf_for',
        'pagaf_tip_cct',
        'pagaf_num_cct',
        'pagaf_dte',
        'pagaf_dtv',
        'pagaf_vlr',
        'pagaf_cmp',
        'pagaf_scc',
        'pagaf_obs'  
    ];
}
