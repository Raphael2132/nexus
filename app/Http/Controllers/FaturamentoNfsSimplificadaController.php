<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\FaturamentoNfsSimplificada;
use stdClass;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers\Helper;

class FaturamentoNfsSimplificadaController extends Controller
{
    protected $nfsSimplificada;
    
    public function __construct(FaturamentoNfsSimplificada $nfsSimplificada)
    {
        $this->nfsSimplificada = $nfsSimplificada;
    }

    //Insere a NFS simplificada
    static function insert($dadosNfsSimp, $empresa, $cliente, $enderecoCli){

        $usuario = Auth::user()->usuario_codigo;

        $nextval=DB::select("SELECT nextval('sq_faturamento_nfs_simp_numero')")[0]->nextval;
        $numNfsSimp = $nextval;

        $data = date('Y-m-d');

        if($dadosNfsSimp['enderecoLocSrv'] == 3){

            $enderecoLocSrv = 'O';
            $nfssim_loc_srv_cep = Helper::limpaCEP($dadosNfsSimp['cep']);
            $nfssim_loc_srv_logradouro = $dadosNfsSimp['logradouro'];
            $nfssim_loc_srv_numero = $dadosNfsSimp['numero'];
            $nfssim_loc_srv_complemento = $dadosNfsSimp['complemento'];
            $nfssim_loc_srv_bairro = $dadosNfsSimp['bairro'];
            $nfssim_loc_srv_cidade = $dadosNfsSimp['cidade'];
            $nfssim_loc_srv_uf = $dadosNfsSimp['uf'];
            $nfssim_loc_srv_pais = $dadosNfsSimp['pais'];
            $nfssim_loc_srv_ibge_cod_mun = $dadosNfsSimp['ibgeCodMun'];

        }elseif($dadosNfsSimp['enderecoLocSrv'] == 2){

            $enderecoLocSrv = 'C';
            $nfssim_loc_srv_cep = null;
            $nfssim_loc_srv_logradouro = null;
            $nfssim_loc_srv_numero = null;
            $nfssim_loc_srv_complemento = null;
            $nfssim_loc_srv_bairro = null;
            $nfssim_loc_srv_cidade = null;
            $nfssim_loc_srv_uf = null;
            $nfssim_loc_srv_pais = null;
            $nfssim_loc_srv_ibge_cod_mun = null;

        }else{

            $enderecoLocSrv = 'E';
            $nfssim_loc_srv_cep = null;
            $nfssim_loc_srv_logradouro = null;
            $nfssim_loc_srv_numero = null;
            $nfssim_loc_srv_complemento = null;
            $nfssim_loc_srv_bairro = null;
            $nfssim_loc_srv_cidade = null;
            $nfssim_loc_srv_uf = null;
            $nfssim_loc_srv_pais = null;
            $nfssim_loc_srv_ibge_cod_mun = null;
        }

        $dados = [
            'nfssim_emp' => $empresa,
            'nfssim_num' => $numNfsSimp,
            'nfssim_data_emi' => $data,
            'nfssim_cli' => $cliente,
            'nfssim_cli_end' => $enderecoCli,
            'nfssim_srv_grp' => $dadosNfsSimp['grupoSrv'],
            'nfssim_srv_cod' => $dadosNfsSimp['codigoSrv'],
            'nfssim_srv_desc' => $dadosNfsSimp['descSrv'],
            'nfssim_inf_com' => $dadosNfsSimp['infoCmp'],
            'nfssim_vlr_nfs' => Helper::limpaValorMonetario($dadosNfsSimp['vlrNFS']),
            'nfssim_vlr_base' => Helper::limpaValorMonetario($dadosNfsSimp['vlrBase']),
            'nfssim_alq_iss' => Helper::limpaPorcentagem($dadosNfsSimp['aliqISS']),
            'nfssim_vlr_inss_ret' => Helper::limpaValorMonetario($dadosNfsSimp['inssRet']),
            'nfssim_vlr_ir_ret' => Helper::limpaValorMonetario($dadosNfsSimp['irrfRet']),
            'nfssim_vlr_csll_ret' => Helper::limpaValorMonetario($dadosNfsSimp['csllRet']),
            'nfssim_vlr_pis_ret' => Helper::limpaValorMonetario($dadosNfsSimp['pisRet']),
            'nfssim_vlr_cofins_ret' => Helper::limpaValorMonetario($dadosNfsSimp['cofinsRet']),
            'nfssim_vlr_imp_rec' => Helper::limpaValorMonetario($dadosNfsSimp['vlrImpRec']),
            'nfssim_loc_srv' => $enderecoLocSrv,
            'nfssim_loc_srv_cep' => $nfssim_loc_srv_cep,
            'nfssim_loc_srv_logradouro' => $nfssim_loc_srv_logradouro,
            'nfssim_loc_srv_numero' => $nfssim_loc_srv_numero,
            'nfssim_loc_srv_complemento' => $nfssim_loc_srv_complemento,
            'nfssim_loc_srv_bairro' => $nfssim_loc_srv_bairro,
            'nfssim_loc_srv_cidade' => $nfssim_loc_srv_cidade,
            'nfssim_loc_srv_uf' => $nfssim_loc_srv_uf,
            'nfssim_loc_srv_pais' => $nfssim_loc_srv_pais,
            'nfssim_loc_srv_ibge_cod_mun' => $nfssim_loc_srv_ibge_cod_mun,
            'nfssim_usu_emi' => $usuario,
            'nfssim_obs' => $dadosNfsSimp['obsNFS']  
        ];
        
        FaturamentoNfsSimplificada::create($dados);
        
        return $numNfsSimp;
    }
}
