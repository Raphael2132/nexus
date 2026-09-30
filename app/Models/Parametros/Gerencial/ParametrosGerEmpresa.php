<?php

namespace App\Models\Parametros\Gerencial;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametrosGerEmpresa extends Model
{
    use HasFactory;

    protected $primaryKey = 'parger_id';
    
    protected $fillable = [
        'parger_emp',
        'parger_dia_fun',
        'parger_hr_ini_fun',
        'parger_hr_fin_fun',
        'parger_int_fun',
        'parger_hr_ini_int',
        'parger_hr_fin_int',
        'parger_tur_srv',
        'parger_hr_alt_sab',
        'parger_hr_ini_sab',
        'parger_hr_fin_sab',
        'parger_int_sab',
        'parger_hr_ini_int_sab',
        'parger_hr_fin_int_sab',
        'parger_hr_alt_dom',
        'parger_hr_ini_dom',
        'parger_hr_fin_dom',
        'parger_int_dom',
        'parger_hr_ini_int_dom',
        'parger_hr_fin_int_dom'
    ];
}
