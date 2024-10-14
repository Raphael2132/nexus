<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaturamentoNfLocalServico extends Model
{
    use HasFactory;

    protected $primaryKey = 'nflocsrv_id';
    
    protected $fillable = [
        'nflocsrv_emp',
        'nflocsrv_num',
        'nflocsrv_tip_reg',
        'nflocsrv_loc_srv',
        'nflocsrv_cep',
        'nflocsrv_logradouro',
        'nflocsrv_numero',
        'nflocsrv_complemento',
        'nflocsrv_bairro',
        'nflocsrv_cidade',
        'nflocsrv_uf',
        'nflocsrv_pais',
        'nflocsrv_ibge_cod_mun',
        'nflocsrv_ibge_cod_pais'
    ];
}
