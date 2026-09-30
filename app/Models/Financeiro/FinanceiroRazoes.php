<?php

namespace App\Models\Financeiro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceiroRazoes extends Model
{
    use HasFactory;

    protected $primaryKey = 'razao_id';
    
    protected $fillable = [
        'razao_empresa',
        'razao_codigo',
        'razao_ano',
        'razao_nome',
        'razao_tipo',
        'razao_bco_cod',
        'razao_bco_nom',
        'razao_bco_age',
        'razao_bco_age_dv',
        'razao_bco_ncc',
        'razao_bco_ncc_dv',
        'razao_adm_cod',
        'razao_tip_adm',
        'razao_tip_card',
        'razao_ban_card',
        'razao_bco_adm',
        'razao_dep_on',
        'razao_dia_prl',
        'razao_dia_vst',
        'razao_nom_fan',
        'razao_car',
        'razao_saldo_ini',
        'razao_saldo_jan',
        'razao_saldo_fev',
        'razao_saldo_mar',
        'razao_saldo_abr',
        'razao_saldo_mai',
        'razao_saldo_jun',
        'razao_saldo_jul',
        'razao_saldo_ago',
        'razao_saldo_set',
        'razao_saldo_out',
        'razao_saldo_nov',
        'razao_saldo_dez',
        'razao_saldo_atu',
        'razao_saldo_din',
        'razao_saldo_cc',
        'razao_saldo_ch',
        'razao_dt_ult_mov',
        'razao_hr_ult_mov',
        'razao_opr_ult_mov',
        'razao_ntr_ult_mov',
        'razao_seq_ult_mov',
        'razao_usu_ult_mov',
        'razao_con_aut',
        'razao_dt_con',
        'razao_hr_con',
        'razao_saldo_con'
    ];
}
