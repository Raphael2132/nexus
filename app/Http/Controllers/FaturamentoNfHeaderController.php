<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\FaturamentoNfHeader;
use stdClass;
use App\Http\Helpers\Helper;

class FaturamentoNfHeaderController extends Controller
{
    protected $headerNF;
    
    public function __construct(FaturamentoNfHeader $headerNF)
    {
        $this->headerNF = $headerNF;
    }

    public function consultaNF(Request $request)
    {
        $where = "";

        if(!empty($request->empresa)){
            $where = " and nfhdr_emp = '".$request->empresa."' ";
        }

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

        if(!empty($request->numOS)){
            $where .= " and nfhdr_num_ped = '".$request->numOS."' ";
        }

        if(!empty($request->dtIniOS) && !empty($request->dtFinOS)){
            $dt_ini = Helper::limpaData($request->dtIniOS);
            $dt_fin = Helper::limpaData($request->dtFinOS);
            $where .= " and nfhdr_dt_ped between '".$dt_ini."' and '".$dt_fin."' ";
        }elseif(empty($request->dtIniOS) && !empty($request->dtFinOS)){
            $dt_fin = Helper::limpaData($request->dtFinOS);
            $where .= " and nfhdr_dt_ped <= '".$dt_fin."' ";
        }elseif(!empty($request->dtIniOS) && empty($request->dtFinOS)){
            $dt_ini = Helper::limpaData($request->dtIniOS);
            $where .= " and nfhdr_dt_ped >= '".$dt_ini."' ";
        }

        if(!empty($request->vlrIniOS) && !empty($request->vlrFinOS)){
            $vlr_ini = Helper::limpaValorMonetario($request->vlrIniOS);
            $vlr_fin = Helper::limpaValorMonetario($request->vlrFinOS);
            $where .= " and nfhdr_vlr_tot_nf between ".$vlr_ini." and ".$vlr_fin;
        }elseif(empty($request->vlrIniOS) && !empty($request->vlrFinOS)){
            $vlr_fin = Helper::limpaValorMonetario($request->vlrFinOS);
            $where .= " and nfhdr_vlr_tot_nf <= ".$vlr_fin;
        }elseif(!empty($request->vlrIniOS) && empty($request->vlrFinOS)){
            $vlr_ini = Helper::limpaValorMonetario($request->vlrIniOS);
            $where .= " and nfhdr_vlr_tot_nf >= ".$vlr_ini;
        }

        $dados = DB::select("select nfhdr_emp, nfhdr_cli, count(nfhdr_num_ped) as qtd_reg, sum(nfhdr_vlr_tot_nf) as total_pedidos from faturamento_nf_headers where nfhdr_sts = 'A' ".$where." group by nfhdr_emp, nfhdr_cli order by nfhdr_cli");

        return view('/faturamento/notas/consultaEmissaoNF',['dadosHeader'=>$dados]);
    }

    public function painelNF($empresa, $cliente, $nfSelecionada)
    {
        $dados = $this->headerNF->where('nfhdr_emp', $empresa)->where('nfhdr_cli', $cliente)->where('nfhdr_sts', 'A')->orderby('nfhdr_num_ped', 'asc')->get();

        return view('/faturamento/notas/painelEmissaoNF',['dadosHeader'=>$dados, 'nfSelecionada' => $nfSelecionada, 'empresaNF' => $empresa, 'clienteNF' => $cliente]);
    }

    public function consultaReemissaoNF()
    {
        $dados = $this->headerNF->where('nfhdr_sts','<>', 'A')->orderby('nfhdr_dt_nf', 'desc')->orderby('nfhdr_num_nf', 'desc')->get();

        return view('/faturamento/notas/consultaReemissaoNF',['dadosHeader'=>$dados]);
    }
}
