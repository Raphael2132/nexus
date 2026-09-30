<?php

namespace App\Models\Faturamento\Nfe;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaturamentoNfeInfoPagamentos extends Model
{
    use HasFactory;

    protected $primaryKey = 'nfepag_id';
    
    protected $fillable = [
        'nfepag_emi',
        'nfepag_num',
        'nfepag_seq',
        'nfepag_tip_pgt',
        'nfepag_val_pgt',
        'nfepag_tip_int',
        'nfepag_cnpj',
        'nfepag_tip_band',
        'nfepag_cod_aut',
        'nfepag_val_trc',
    ];
}
