<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaturamentoNfs extends Model
{
    use HasFactory;

    protected $primaryKey = 'nfs_id';
    
    protected $fillable = [
        'nfs_sts',
        'nfs_emp',
        'nfs_nnfs',
        'nfs_snfs',
        'nfs_nfhdr_num',
        'nfs_nfhdr_num_ped',
        'nfs_cli',
        'nfs_dt_emi',
        'nfs_hr_emi',
        'nfs_vlr_srv',
        'nfs_vlr_dsc',
        'nfs_vlr_ded',
        'nfs_vlr_tot',
        'nfs_cod_srv',
        'nfs_vlr_iss',
        'nfs_alq_nfs',
        'nfs_iss_ret',
        'nfs_tip_tom',
        'nfs_cpf_cnpj_tom',
        'nfs_ins_mun_tom',
        'nfs_ins_est_tom',
        'nfs_nom_tom',
        'nfs_end_tom',
        'nfs_num_end_tom',
        'nfs_com_end_tom',
        'nfs_bai_tom',
        'nfs_cid_tom',
        'nfs_ibge_cod_mun_tom',
        'nfs_uf_tom',
        'nfs_cep_tom',
        'nfs_email_tom',
        'nfs_tel_res_tom',
        'nfs_tel_cel_tom',
        'nfs_tel_com_tom',
        'nfs_qtd_itm',
        'nfs_nro_nfe',
        'nfs_cod_ver',
        'nfs_vlr_des_iss_inc',
        'nfs_obs',
        'nfs_pais_tom',
        'nfs_rps_env_email'
    ];
}
