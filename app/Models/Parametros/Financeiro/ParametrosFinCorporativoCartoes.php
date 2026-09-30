<?php

namespace App\Models\Parametros\Financeiro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametrosFinCorporativoCartoes extends Model
{
    use HasFactory;

    protected $primaryKey = 'parcco_id';
    
    protected $fillable = [
        'parcco_sts',
        'parcco_emp',
        'parcco_adm',
        'parcco_num',
        'parcco_nom',
        'parcco_dif',
        'parcco_div',
        'parcco_par'
    ];
}
