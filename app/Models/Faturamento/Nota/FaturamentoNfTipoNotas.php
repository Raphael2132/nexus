<?php

namespace App\Models\Faturamento\Nota;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaturamentoNfTipoNotas extends Model
{
    use HasFactory;

    protected $primaryKey = 'nftnt_id';
    
    protected $fillable = [
        'nftnt_emp',
        'nftnt_num',
        'nftnt_tipo',
    ];
}
