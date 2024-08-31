<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use App\Http\Helpers\Helper;

class LancamentoSrvOsOrcamentosController extends Controller
{
    //Chama a consulta dos orçamentos emitidas
    public function consultaOrcamentoOS(Request $request)
    {        
        $where = "";

        //Empresa
        if(!empty($request->empresa)){
            $where = " and orc_emp = '".$request->empresa."' ";
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
                $where .= " and orc_cli = '".$cliente."' ";
            }
        }

        //Numero da OS / Pedido
        if(!empty($request->numOS)){
            $where .= " and orc_nos = '".$request->numOS."' ";
        }

        //Numero da OS / Pedido
        if(!empty($request->numOrc)){
            $where .= " and orc_num_orc = '".$request->numOrc."' ";
        }

        //Data de Inicio / Final Orçamento
        if(!empty($request->dtIniOrc) && !empty($request->dtFinOrc)){

            $dt_ini = Helper::limpaData($request->dtIniOrc);
            $dt_fin = Helper::limpaData($request->dtFinOrc);

            if($dt_ini > $dt_fin){
                return redirect()->back()->with('error', 'A Data Inicial do Orçamento não pode ser maior que a Data Final!');
            }

            $where .= " and orc_dt_orc between '".$dt_ini."' and '".$dt_fin."' ";
        }elseif(empty($request->dtIniOrc) && !empty($request->dtFinOrc)){
            $dt_fin = Helper::limpaData($request->dtFinOrc);
            $where .= " and orc_dt_orc <= '".$dt_fin."' ";
        }elseif(!empty($request->dtIniOrc) && empty($request->dtFinOrc)){
            $dt_ini = Helper::limpaData($request->dtIniOrc);
            $where .= " and orc_dt_orc >= '".$dt_ini."' ";
        }

        //Data de Inicio / Final OS
        if(!empty($request->dtIniOS) && !empty($request->dtFinOS)){

            $dt_ini = Helper::limpaData($request->dtIniOS);
            $dt_fin = Helper::limpaData($request->dtFinOS);

            if($dt_ini > $dt_fin){
                return redirect()->back()->with('error', 'A Data Inicial da OS não pode ser maior que a Data Final!');
            }

            $where .= " and CAST(orc_dha AS DATE) between '".$dt_ini."' and '".$dt_fin."' ";
        }elseif(empty($request->dtIniOS) && !empty($request->dtFinOS)){
            $dt_fin = Helper::limpaData($request->dtFinOS);
            $where .= " and CAST(orc_dha AS DATE) <= '".$dt_fin."' ";
        }elseif(!empty($request->dtIniOS) && empty($request->dtFinOS)){
            $dt_ini = Helper::limpaData($request->dtIniOS);
            $where .= " and CAST(orc_dha AS DATE) >= '".$dt_ini."' ";
        }

        $dadosOrc = DB::select("select * from lancamento_srv_os_orcamentos where 1 = 1 ".$where." order by orc_emp asc, orc_nos desc");

        return view('/lancamentos/servico/consultaOrcamentoOS',['dadosOrc' => $dadosOrc]);
    }
}
