<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LancamentoSrvOs extends Model
{
    use HasFactory;

    protected $primaryKey = 'os_id';
    
    protected $fillable = [
        'os_nos',
        'os_emp',
        'os_cli',
        'os_cli_end',
        'os_dha',
        'os_res_abr',
        'os_dhf',
        'os_res_fec',
        'os_dt_orc',
        'os_num_orc',
        'os_dtc',
        'os_res_can',
        'os_dpe',
        'os_hpe',
        'os_qtd_hr',
        'os_vlt',
        'os_vos',
        'os_vls',
        'os_vlp',
        'os_tip_des',
        'os_per_des',
        'os_val_des',
        'os_val_des_req',
        'os_cli_agr',
        'os_cli_avs',
        'os_sts',
        'os_cli_fatura',
        'os_observacao',
        'os_loc_srv',
        'os_loc_srv_cep',
        'os_loc_srv_logradouro',
        'os_loc_srv_numero',
        'os_loc_srv_complemento',
        'os_loc_srv_bairro',
        'os_loc_srv_cidade',
        'os_loc_srv_uf',
        'os_loc_srv_pais',
        'os_loc_srv_ibge_cod_mun'
    ];
}
