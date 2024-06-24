<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaturamentoNfsXmlEnviados extends Model
{
    use HasFactory; 

    protected $primaryKey = 'nfsenv_id';
    
    protected $fillable = [
        'nfsenv_emp',
        'nfsenv_num',
        'nfsenv_nfhdr_num',
        'nfsenv_cnpj',
        'nfsenv_pro',
        'nfsenv_sts',
        'nfsenv_xml_env',
        'nfsenv_xml_rcb',
        'nfsenv_xml_cns',
        'nfsenv_dt_atu',
        'nfsenv_dt_inc',
        'nfsenv_num_nfs',
        'nfsenv_obs'
    ];
}
