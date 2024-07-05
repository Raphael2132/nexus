<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaturamentoNfTotais extends Model
{
    use HasFactory;

    protected $primaryKey = 'nftot_id';
    
    protected $fillable = [
        'nftot_emp',
        'nftot_num',
        'nftot_tip_reg',
        'nftot_num_ped',
        'nftot_vlr_frt',
        'nftot_vlr_mer',
        'nftot_vlr_liq_mer',
        'nftot_vlr_srv',
        'nftot_vlr_liq_srv',
        'nftot_vlr_ipi',
        'nftot_vlr_sbt',
        'nftot_vlr_tot',
        'nftot_vlr_bc_icms',
        'nftot_vlr_alq_icms',
        'nftot_vlr_icms',
        'nftot_vlr_alq_iss',
        'nftot_vlr_iss',
        'nftot_plq',
        'nftot_vlr_dsc_iss',
        'nftot_vlr_tot_dsc',
        'nftot_ins_est',
        'nftot_ins_mun',
        'nftot_nat',
        'nftot_cod_trp',
        'nftot_tpr',
        'nftot_cnpj_trp',
        'nftot_via',
        'nftot_plc',
        'nftot_uf_plc',
        'nftot_uf_trp',
        'nftot_cep_trp',
        'nftot_log_trp',
        'nftot_mun_trp',
        'nftot_ins_est_trp',
        'nftot_vlr_icms_dfr',
        'nftot_vlr_dac',
        'nftot_vlr_adf',
        'nftot_nop_dac',
        'nftot_obs'
    ];
}
