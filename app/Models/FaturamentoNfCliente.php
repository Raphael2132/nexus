<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaturamentoNfCliente extends Model
{
    use HasFactory;

    protected $primaryKey = 'nfcli_id';
    
    protected $fillable = [
        'nfcli_emp',
        'nfcli_num',
        'nfcli_tip_reg',
        'nfcli_cod',
        'nfcli_nom',
        'nfcli_tps',
        'nfcli_cpf_cnpj',
        'nfcli_rg',
        'nfcli_tel_res',
        'nfcli_tel_cel',
        'nfcli_tel_com',
        'nfcli_cep',
        'nfcli_logradouro',
        'nfcli_numero',
        'nfcli_complemento',
        'nfcli_bai',
        'nfcli_cid',
        'nfcli_uf',
        'nfcli_cod_mun_ibge',
        'nfcli_cod_pais_ibge',
        'nfcli_ins_est',
        'nfcli_ins_mun',
        'nfcli_email',
        'nfcli_pais'
    ];
}
