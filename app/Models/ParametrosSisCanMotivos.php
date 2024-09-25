<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametrosSisCanMotivos extends Model
{
    use HasFactory;

    protected $primaryKey = 'canmot_id';
    
    protected $fillable = [
        'canmot_codigo',
        'canmot_desc'
    ];
}
