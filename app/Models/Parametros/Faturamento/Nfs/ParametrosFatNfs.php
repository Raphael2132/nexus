<?php

namespace App\Models\Parametros\Faturamento\Nfs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametrosFatNfs extends Model
{
    use HasFactory;

    protected $primaryKey = 'parnfs_id';
    
    protected $fillable = [
        'parnfs_empresa',
        'parnfs_utiliza_nfs',
        'parnfs_provedor',
        'parnfs_numeracao',
        'parnfs_serie',
        'parnfs_impressao_nfs',
        'parnfs_impressao_rps'
    ];
}
