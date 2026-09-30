<?php

namespace App\Models\Parametros\Faturamento\Fatura;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametrosFatFaturaBancos extends Model
{
    use HasFactory;

    protected $primaryKey = 'fatban_id';
    
    protected $fillable = [
        'fatban_status',     
        'fatban_empresa',    
        'fatban_banco',      
        'fatban_razao',      
        'fatban_age',        
        'fatban_age_dv',     
        'fatban_ncc',        
        'fatban_ncc_dv',    
        'fatban_sts_boleto', 
        'fatban_sts_remessa',
        'fatban_instrucao_1',
        'fatban_instrucao_2',
        'fatban_instrucao_3',
        'fatban_instrucao_4',
        'fatban_instrucao_5',
        'fatban_instrucao_6',
        'fatban_instrucao_7',
        'fatban_msg_1',      
        'fatban_msg_2',      
        'fatban_msg_3',      
        'fatban_num_seq',    
        'fatban_num_car',    
        'fatban_tip_cob',    
        'fatban_loc_pgt',    
        'fatban_tip_moeda',  
        'fatban_convenio',   
        'fatban_cod_cliente',
        'fatban_espec_doc',  
        'fatban_cnab',       
        'fatban_posto',      
        'fatban_byte',       
        'fatban_seq_rem',    
        'fatban_desc_pgt',   
        'fatban_com_per',    
        'fatban_tarifa',     
        'fatban_aceite',     
        'fatban_cod_trans'
    ];
}
