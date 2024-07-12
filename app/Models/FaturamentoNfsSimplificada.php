<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaturamentoNfsSimplificada extends Model
{
    use HasFactory;

    protected $primaryKey = 'nfssim_id';
    
    protected $fillable = [
        'nfssim_emp',
        'nfssim_num',
        'nfssim_data_emi',
        'nfssim_cli',
        'nfssim_cli_end',
        'nfssim_srv_grp',
        'nfssim_srv_cod',
        'nfssim_srv_desc',
        'nfssim_inf_com',
        'nfssim_vlr_nfs',
        'nfssim_vlr_base',
        'nfssim_alq_iss',
        'nfssim_vlr_inss_ret',
        'nfssim_vlr_ir_ret',
        'nfssim_vlr_csll_ret',
        'nfssim_vlr_pis_ret',
        'nfssim_vlr_cofins_ret',
        'nfssim_vlr_out_ret',
        'nfssim_vlr_imp_rec',
        'nfssim_loc_srv',
        'nfssim_loc_srv_cep',
        'nfssim_loc_srv_logradouro',
        'nfssim_loc_srv_numero',
        'nfssim_loc_srv_complemento',
        'nfssim_loc_srv_bairro',
        'nfssim_loc_srv_cidade',
        'nfssim_loc_srv_uf',
        'nfssim_loc_srv_pais',
        'nfssim_loc_srv_ibge_cod_mun',
        'nfssim_usu_emi',
        'nfssim_obs'
    ];
}
