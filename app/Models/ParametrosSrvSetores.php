<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametrosSrvSetores extends Model
{
    use HasFactory;

    protected $primaryKey = 'setor_id';
    
    protected $fillable = [
        'setor_empresa',
        'setor_codigo',
        'setor_area',
        'setor_desc'
    ];
}
