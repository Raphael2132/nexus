<?php

namespace App\Models\Financeiro\Recebimento;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceiroRecebimentoContasCorrentes extends Model
{
    use HasFactory;

    protected $primaryKey = 'reccct_id';
    
    protected $fillable = [
        'reccct_emp',           
        'reccct_cod_rec',       
        'reccct_tcc',           
        'reccct_res',           
        'reccct_ncc',           
        'reccct_emi',           
        'reccct_ori',           
        'reccct_scc',           
        'reccct_valor',         
        'reccct_valor_rec',     
        'reccct_acrescimo',
        'reccct_desconto',     
        'reccct_valor_iss',     
        'reccct_valor_irrf',    
        'reccct_desp_banc',     
        'reccct_dt_emissao',    
        'reccct_dt_recebimento',
        'reccct_dt_vencimento', 
        'reccct_observacao',    
        'reccct_complemento',   
        'reccct_flag',
    ];
}
