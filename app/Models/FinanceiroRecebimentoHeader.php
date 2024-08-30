<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceiroRecebimentoHeader extends Model
{
    use HasFactory;

    protected $primaryKey = 'rechdr_id';
    
    protected $fillable = [
        'rechdr_emp',
        'rechdr_sts',
        'rechdr_ori',
        'rechdr_dti',
        'rechdr_usu',
    ];
}
