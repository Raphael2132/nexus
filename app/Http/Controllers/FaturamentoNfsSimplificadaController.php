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

            $dadosPais = DB::table('ibge_paises')->where('ibge_pais_codigo', $dadosNfsSimp['pais'])->first();

            $enderecoLocSrv = 'O';
            $nfssim_loc_srv_cep = Helper::limpaCEP($dadosNfsSimp['cep']);
            $nfssim_loc_srv_logradouro = $dadosNfsSimp['logradouro'];
            $nfssim_loc_srv_numero = $dadosNfsSimp['numero'];
            $nfssim_loc_srv_complemento = $dadosNfsSimp['complemento'];
            $nfssim_loc_srv_bairro = $dadosNfsSimp['bairro'];
            $nfssim_loc_srv_cidade = $dadosNfsSimp['cidade'];
            $nfssim_loc_srv_uf = $dadosNfsSimp['uf'];
            $nfssim_loc_srv_pais = $dadosPais->ibge_pais_nome;
            $nfssim_loc_srv_ibge_cod_mun = $dadosNfsSimp['ibgeCodMun'];
            $nfssim_loc_srv_ibge_cod_pais = $dadosNfsSimp['pais'];

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
            $nfssim_loc_srv_ibge_cod_pais = null;

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
            $nfssim_loc_srv_ibge_cod_pais = null;
        }

        $inssRet = empty($dadosNfsSimp['inssRet']) ? 0.00 : Helper::limpaValorMonetario($dadosNfsSimp['inssRet']);
        $irrfRet = empty($dadosNfsSimp['irrfRet']) ? 0.00 : Helper::limpaValorMonetario($dadosNfsSimp['irrfRet']);
        $csllRet = empty($dadosNfsSimp['csllRet']) ? 0.00 : Helper::limpaValorMonetario($dadosNfsSimp['csllRet']);
        $pisRet = empty($dadosNfsSimp['pisRet']) ? 0.00 : Helper::limpaValorMonetario($dadosNfsSimp['pisRet']);
        $cofinsRet = empty($dadosNfsSimp['cofinsRet']) ? 0.00 : Helper::limpaValorMonetario($dadosNfsSimp['cofinsRet']);


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
            'nfssim_vlr_inss_ret' => $inssRet,
            'nfssim_vlr_ir_ret' => $irrfRet,
            'nfssim_vlr_csll_ret' => $csllRet,
            'nfssim_vlr_pis_ret' => $pisRet,
            'nfssim_vlr_cofins_ret' => $cofinsRet,
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
            'nfssim_loc_srv_ibge_cod_pais' => $nfssim_loc_srv_ibge_cod_pais,
            'nfssim_usu_emi' => $usuario,
            'nfssim_obs' => $dadosNfsSimp['obsNFS']  
        ];
        
        FaturamentoNfsSimplificada::create($dados);
        
        return $numNfsSimp;
    }

    public function consultaReemissaoSimpNF(Request $request, $appOrigem)
    { 
        $where = "";

        if($appOrigem == 'REEMISSAO'){
            //Empresa
            if(!empty($request->empresa)){
                $where = " and nfhdr_emp = '".$request->empresa."' ";
            }

            //Cliente
            if(!empty($request->cliente)){
                $cliente = substr($request->cliente, 0, 10);

                if(strlen($cliente) < 10){
                    return redirect()->back()->with('error', 'Código do cliente '.$cliente.' é inválido!');
                }

                $cnt_cli = DB::table('cadastro_clientes')->where('cliente_codigo', $cliente)->count();
            
                if($cnt_cli == 0){
                    return redirect()->back()->with('error', 'Código do cliente '.$cliente.' não existe!');
                }else{
                    $where .= " and nfhdr_cli = '".$cliente."' ";
                }
            }

            //Numero da OS / Pedido
            if(!empty($request->numES)){
                $where .= " and nfhdr_num_ped = '".$request->numES."' ";
            }

            //Data de Inicio / Final
            if(!empty($request->dtIniOS) && !empty($request->dtFinOS)){

                $dt_ini = Helper::limpaData($request->dtIniOS);
                $dt_fin = Helper::limpaData($request->dtFinOS);

                if($dt_ini > $dt_fin){
                    return redirect()->back()->with('error', 'A Data Inicial não pode ser maior que a Data Final!');
                }

                $where .= " and nfhdr_dt_nf between '".$dt_ini."' and '".$dt_fin."' ";
            }elseif(empty($request->dtIniOS) && !empty($request->dtFinOS)){
                $dt_fin = Helper::limpaData($request->dtFinOS);
                $where .= " and nfhdr_dt_nf <= '".$dt_fin."' ";
            }elseif(!empty($request->dtIniOS) && empty($request->dtFinOS)){
                $dt_ini = Helper::limpaData($request->dtIniOS);
                $where .= " and nfhdr_dt_nf >= '".$dt_ini."' ";
            }

            //Valor de Inicio / Final
            if(!empty($request->vlrIniOS) && !empty($request->vlrFinOS)){
                $vlr_ini = Helper::limpaValorMonetario($request->vlrIniOS);
                $vlr_fin = Helper::limpaValorMonetario($request->vlrFinOS);
                if($vlr_ini > $vlr_fin){
                    return redirect()->back()->with('error', 'O Valor Inicial não pode ser maior que o Valor Final!');
                }
                $where .= " and nfhdr_vlr_tot_nf between ".$vlr_ini." and ".$vlr_fin;
            }elseif(empty($request->vlrIniOS) && !empty($request->vlrFinOS)){
                $vlr_fin = Helper::limpaValorMonetario($request->vlrFinOS);
                $where .= " and nfhdr_vlr_tot_nf <= ".$vlr_fin;
            }elseif(!empty($request->vlrIniOS) && empty($request->vlrFinOS)){
                $vlr_ini = Helper::limpaValorMonetario($request->vlrIniOS);
                $where .= " and nfhdr_vlr_tot_nf >= ".$vlr_ini;
            }

        }else if($appOrigem == 'HOME_MES'){

            //Vamos definir a empresa da visualização da Home
            $empresa = session('glo_empresa_exibicao_home');
            $data = date('Y-m-01');
            $where .= " and nfhdr_emp = '".$empresa."' and nfhdr_sts = 'G' and nfhdr_dt_nf >= '".$data."' ";

        }else{
            
            //Vamos definir a empresa da visualização da Home
            $empresa = session('glo_empresa_exibicao_home');
            $where .= " and nfhdr_emp = '".$empresa."' ";
        }

        $dados = DB::select("select * from faturamento_nf_headers where nfhdr_sts not in('C') and nfhdr_ori in('02') ".$where." order by nfhdr_dt_nf desc, nfhdr_num_nf desc");

        return view('/faturamento/notas/simplificada/consultaReemissaoSimpNF',['dadosHeader'=>$dados, 'glo_where_reemissao_nf' => $where]);
    }

    public function redirConsultaReemissaoSimpNF(Request $request)
    { 
        //Vamos passar como variavel no redirect por que depois de 2 redirect elas são destruidas
        $where = session('glo_where_reemissao_nf');

        $dados = DB::select("select * from faturamento_nf_headers where nfhdr_sts not in('C') and nfhdr_ori in('02') ".$where." order by nfhdr_dt_nf desc, nfhdr_num_nf desc");

        return view('/faturamento/notas/simplificada/consultaReemissaoSimpNF',['dadosHeader'=>$dados, 'glo_where_reemissao_nf' => $where]);
    }
}
