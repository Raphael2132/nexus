<?php

namespace App\Models\Lancamentos\Servico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LancamentoSrvOs extends Model
{
    use HasFactory;

    protected $primaryKey = 'os_id';
    
    protected $fillable = [
        'os_emp',
        'os_nos',
        'os_sts',
        'os_dha',
        'os_res_abr',
        'os_dhf',
        'os_res_fec',
        'os_cli',
        'os_cli_end',
        'os_cli_fatura',
        'os_dt_orc',
        'os_num_orc',
        'os_dpe',
        'os_hpe',
        'os_qtd_hr_pre_ent',
        'os_cal_aut_pre_ent',
        'os_qtd_hr',
        'os_vlt',
        'os_vlr',
        'os_vls',
        'os_vlp',
        'os_tip_des',
        'os_per_des',
        'os_val_des',
        'os_val_des_srv',
        'os_val_des_pro',
        'os_val_fin_des_srv',
        'os_val_fin_des_pro',
        'os_val_fin_des',
        'os_val_dac',
        'os_val_aju',
        'os_val_ent',
        'os_tipo_nf',
        'os_forma_pgt',
        'os_cond_pgt',
        'os_cli_agr',
        'os_cli_avs',
        'os_observacao',
        'os_dtc',
        'os_hrc',
        'os_res_can',
        'os_mot_can',
        'os_obs_can',
        'os_loc_srv',
        'os_loc_srv_cep',
        'os_loc_srv_logradouro',
        'os_loc_srv_numero',
        'os_loc_srv_complemento',
        'os_loc_srv_bairro',
        'os_loc_srv_cidade',
        'os_loc_srv_uf',
        'os_loc_srv_pais',
        'os_loc_srv_ibge_cod_mun',
        'os_loc_srv_ibge_cod_pais'
    ];
}
