<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\FaturamentoNfHeader;
use stdClass;
use App\Http\Helpers\Helper;
use App\Http\Controllers\FinanceiroRecebimentoHeaderController;

class PainelEmissaoNfController extends Controller
{
    //Chama a consulta de NF a serem emitidas pelo filtro
    public function filtroConsultaNF(Request $request)
    {
        $where = "";
        $where_semi = "";

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

        //Numero da OS/Pedido
        if(!empty($request->numOS)){
            $where .= " and nfhdr_num_ped = '".$request->numOS."' ";
            $where_semi .= " and nfhdr_num_ped = '".$request->numOS."' ";
        }

        //Data de Inicio / Final
        if(!empty($request->dtIniOS) && !empty($request->dtFinOS)){
            $dt_ini = Helper::limpaData($request->dtIniOS);
            $dt_fin = Helper::limpaData($request->dtFinOS);
            if($dt_ini > $dt_fin){
                return redirect()->back()->with('error', 'A Data Inicial não pode ser maior que a Data Final!');
            }
            $where .= " and nfhdr_dt_ped between '".$dt_ini."' and '".$dt_fin."' ";
            $where_semi .= " and nfhdr_dt_ped between '".$dt_ini."' and '".$dt_fin."' ";
        }elseif(empty($request->dtIniOS) && !empty($request->dtFinOS)){
            $dt_fin = Helper::limpaData($request->dtFinOS);
            $where .= " and nfhdr_dt_ped <= '".$dt_fin."' ";
            $where_semi .= " and nfhdr_dt_ped <= '".$dt_fin."' ";
        }elseif(!empty($request->dtIniOS) && empty($request->dtFinOS)){
            $dt_ini = Helper::limpaData($request->dtIniOS);
            $where .= " and nfhdr_dt_ped >= '".$dt_ini."' ";
            $where_semi .= " and nfhdr_dt_ped >= '".$dt_ini."' ";
        }

        //Valor de Inicio / Final
        if(!empty($request->vlrIniOS) && !empty($request->vlrFinOS)){
            $vlr_ini = Helper::limpaValorMonetario($request->vlrIniOS);
            $vlr_fin = Helper::limpaValorMonetario($request->vlrFinOS);
            if($vlr_ini > $vlr_fin){
                return redirect()->back()->with('error', 'O Valor Inicial não pode ser maior que o Valor Final!');
            }
            $where .= " and nfhdr_vlr_tot_nf between ".$vlr_ini." and ".$vlr_fin;
            $where_semi .= " and nfhdr_vlr_tot_nf between ".$vlr_ini." and ".$vlr_fin;
        }elseif(empty($request->vlrIniOS) && !empty($request->vlrFinOS)){
            $vlr_fin = Helper::limpaValorMonetario($request->vlrFinOS);
            $where .= " and nfhdr_vlr_tot_nf <= ".$vlr_fin;
            $where_semi .= " and nfhdr_vlr_tot_nf <= ".$vlr_fin;
        }elseif(!empty($request->vlrIniOS) && empty($request->vlrFinOS)){
            $vlr_ini = Helper::limpaValorMonetario($request->vlrIniOS);
            $where .= " and nfhdr_vlr_tot_nf >= ".$vlr_ini;
            $where_semi .= " and nfhdr_vlr_tot_nf >= ".$vlr_ini;
        }

        session(['glo_where_emissao_nf_completo' => $where]);
        session(['glo_where_emissao_nf_semi' => $where_semi]);

        $dados = DB::select("select nfhdr_emp, nfhdr_cli, count(nfhdr_num_ped) as qtd_reg, sum(nfhdr_vlr_tot_nf) as total_pedidos from faturamento_nf_headers where nfhdr_sts = 'A' and nfhdr_ori in('01') ".$where." group by nfhdr_emp, nfhdr_cli order by nfhdr_cli asc");

        return view('/faturamento/notas/consultaEmissaoNF',['dadosHeader'=>$dados, 'glo_where_emissao_nf_completo' => $where, 'glo_where_emissao_nf_semi' => $where_semi]);
    }

    //Chama a consulta de NF a serem emitidas pelos redir que não vem de filtro
    public function consultaNF()
    {
        //Vamos passar como variavel no redirect por que depois de 2 redirect elas são destruidas
        $where = session('glo_where_emissao_nf_completo'); 
        $where_semi = session('glo_where_emissao_nf_semi');

        $dados = DB::select("select nfhdr_emp, nfhdr_cli, count(nfhdr_num_ped) as qtd_reg, sum(nfhdr_vlr_tot_nf) as total_pedidos from faturamento_nf_headers where nfhdr_sts = 'A' and nfhdr_ori in('01') ".$where." group by nfhdr_emp, nfhdr_cli order by nfhdr_cli asc");

        return view('/faturamento/notas/consultaEmissaoNF',['dadosHeader'=>$dados, 'glo_where_emissao_nf_completo' => $where, 'glo_where_emissao_nf_semi' => $where_semi]);
    }

    //Chama o painel de Nota Fiscal pela consulta de notas inserindo o header do recebimento
    public function painelNF($empresa, $cliente)
    {
        //Vamos passar como variavel no redirect por que depois de 2 redirect elas são destruidas
        $where = session('glo_where_emissao_nf_completo');
        $where_semi = session('glo_where_emissao_nf_semi');

        // Chama o método que faz a exclusão de todos os recebimentos com data menor que a do dia atual
        $this->limpaRecebimentoAberto();

        //Insere o Header da tabela de recebimentos
        $idRecebimento = FinanceiroRecebimentoHeaderController::insert($empresa,'NFV');

        $dados = DB::select("select * from faturamento_nf_headers where nfhdr_sts = 'A' and nfhdr_ori in('01') ".$where_semi." and nfhdr_emp = '".$empresa."' and nfhdr_cli = '".$cliente."' order by nfhdr_num_ped asc");
        //$dados = $this->headerNF->where('nfhdr_emp', $empresa)->where('nfhdr_cli', $cliente)->where('nfhdr_sts', 'A')->where('nfhdr_ori',['01'])->orderby('nfhdr_num_ped', 'asc')->get();

        return view('/faturamento/notas/painelEmissaoNF',[
            'dadosHeader'=>$dados, 
            'glo_id_recebimento' => $idRecebimento, 
            'empresaNF' => $empresa, 
            'clienteNF' => $cliente, 
            'glo_where_emissao_nf_completo' => $where, 
            'glo_where_emissao_nf_semi' => $where_semi,
            'estagio_app' => 'SELECAO_NF'
        ]);
    }

    //Chama o painel de Nota Fiscal pelo proprio painel de emissão quando já abertp - Apenas Atualização de Dados do Painel
    public function painelNfAberto($empresa, $cliente, $estagio_app)
    {
        //Vamos passar como variavel no redirect por que depois de 2 redirect elas são destruidas
        $where = session('glo_where_emissao_nf_completo');
        $where_semi = session('glo_where_emissao_nf_semi');
        $idRecebimento = session('glo_id_recebimento');

        if($estagio_app == 'GERACAO_NF'){
            $notaReceb = DB::table('financeiro_recebimento_notas')->where('recnf_id_rec', $idRecebimento)->where('recnf_emp', $empresa)->count();

            if($notaReceb == 0){
                return redirect()->back()->with('error', 'Nenhuma nota foi selecionada para ser faturada!');
            }
        }

        $dados = DB::select("select * from faturamento_nf_headers where nfhdr_sts = 'A' and nfhdr_ori in('01') ".$where_semi." and nfhdr_emp = '".$empresa."' and nfhdr_cli = '".$cliente."' order by nfhdr_num_ped asc");

        return view('/faturamento/notas/painelEmissaoNF',[
            'dadosHeader'=>$dados, 
            'glo_id_recebimento' => $idRecebimento, 
            'empresaNF' => $empresa, 
            'clienteNF' => $cliente, 
            'glo_where_emissao_nf_completo' => $where, 
            'glo_where_emissao_nf_semi' => $where_semi,
            'estagio_app' => $estagio_app
        ]);
    }

    //Chama a consulta de reemissão vinda do Filtro
    public function consultaReemissaoNF(Request $request)
    { 
        $where = "";

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
        if(!empty($request->numOS)){
            $where .= " and nfhdr_num_ped = '".$request->numOS."' ";
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

        $dados = DB::select("select * from faturamento_nf_headers where nfhdr_sts not in('A','C') and nfhdr_ori in('01') ".$where." order by nfhdr_dt_nf desc, nfhdr_num_nf desc");

        return view('/faturamento/notas/consultaReemissaoNF',['dadosHeader'=>$dados, 'glo_where_reemissao_nf' => $where]);
    }

    //Chama a consulta vinda de redirs mantendo o where do filtro original
    public function redirConsultaReemissaoNF()
    { 
        //Vamos passar como variavel no redirect por que depois de 2 redirect elas são destruidas
        $where = session('glo_where_reemissao_nf');

        $dados = DB::select("select * from faturamento_nf_headers where nfhdr_sts not in('A','C') and nfhdr_ori in('01') ".$where." order by nfhdr_dt_nf desc, nfhdr_num_nf desc");

        return view('/faturamento/notas/consultaReemissaoNF',['dadosHeader'=>$dados, 'glo_where_reemissao_nf' => $where]);
    }

    // Limpa os recebimentos que ficaram em aberto dos dias anteriores
    public function limpaRecebimentoAberto()
    {
        $dtHJ = date('Y-m-d');

        // Busca todos os recebimentos com data menor que a data de hoje
        $recebimentosAbertos = DB::table('financeiro_recebimento_headers')
            ->where('rechdr_dti', '<', $dtHJ)
            ->where('rechdr_sts', 'A')
            ->get();

        // Para cada recebimento encontrado, exclui o cabeçalho e as notas associadas
        foreach($recebimentosAbertos as $recebimento) {
            DB::table('financeiro_recebimento_headers')
                ->where('rechdr_id', $recebimento->rechdr_id)
                ->delete();

            DB::table('financeiro_recebimento_notas')
                ->where('recnf_id_rec', $recebimento->rechdr_id)
                ->delete();
        }
    }
}
