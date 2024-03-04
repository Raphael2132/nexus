<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametrosFatNfsProvedores extends Model
{
    use HasFactory;

    protected $primaryKey = 'provedor_id';
    
    protected $fillable = [
        'provedor_desc'
    ];
}
