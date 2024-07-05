<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LancamentoSrvOsOrcamentos extends Model
{
    use HasFactory;

    protected $primaryKey = 'orc_id';
    
    protected $fillable = [
        'orc_emp',
        'orc_nos',
        'orc_dt_orc',
        'orc_num_orc',
        'orc_cli',
        'orc_dha'
    ];
}
