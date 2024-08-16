<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametrosGerTurno extends Model
{
    use HasFactory;

    protected $primaryKey = 'partur_id';
    
    protected $fillable = [
        'partur_emp',
        'partur_cod',
        'partur_desc',
        'partur_hr_ini',
        'partur_hr_fin',
        'partur_dia'
    ];
}
