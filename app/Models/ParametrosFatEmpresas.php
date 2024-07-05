<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametrosFatEmpresas extends Model
{
    use HasFactory;

    protected $primaryKey = 'parfat_id';
    
    protected $fillable = [
        'parfat_emp',
        'parfat_sim'
    ];
}
