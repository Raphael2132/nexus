<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Helper;
use App\Http\Helpers\HelperDataSelect;
use App\Models\Lancamentos\Servico\LancamentoSrvOs;
use Illuminate\Http\Request;
use stdClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Facades\Modulos;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct(LancamentoSrvOs $lancamentosOS)
    {
        $this->middleware('auth');
        $this->lancamentosOS = $lancamentosOS;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    /*
    |----------------------------------------------------------------------------------------------------
    | Home Principal do Sistema
    |----------------------------------------------------------------------------------------------------
    */
    public function home()
    {
        //Vamos definir a empresa da visualização da Home
        $empresa = session('glo_empresa_exibicao_home');
abort(405);
        /*
        |--------------------------------------------------------------------------
        | Small Box
        |--------------------------------------------------------------------------
        |
        | Variaveis destinadas as Small Box.
        |
        */

        $data = date('Y-m-01');

        /* ***** Quantidade de Clientes incluidos no Mês Atual ***** */
        $cliMes = DB::table('cadastro_clientes')->where('cliente_dt_inc','>=',$data)->count();

        /* ***** Quantidade de OS Finalizadas no Mês Atual ***** */
        $osMes = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->whereDate('os_dhf','>=',$data)->where('os_sts','F')->count();

        /* ***** Quantidade de NFS-e de OS emitidas com sucesso no Mês Atual ***** */
        $nfsMes = DB::table('faturamento_nfs')->where('nfs_emp', $empresa)->whereDate('nfs_dt_emi','>=',$data)->where('nfs_sts','G')->where('nfs_origem','OS')->count();

        /* ***** Quantidade de NFS-e de ES emitidas com sucesso no Mês Atual ***** */
        $nfsSimpMes = DB::table('faturamento_nfs')->where('nfs_emp', $empresa)->whereDate('nfs_dt_emi','>=',$data)->where('nfs_sts','G')->where('nfs_origem','ES')->count();

        /*
        |--------------------------------------------------------------------------
        | Datatable
        |--------------------------------------------------------------------------
        |
        | Variaveis destinadas aos Datatables.
        |
        */

        /* ***** Dados das OS emitidas no Mês Atual *****/
        $dadosOS = $this->lancamentosOS->where('os_emp', $empresa)->orderby('os_dha', 'desc')->limit(5)->get();

        /* ***** Dados das de NFS-e emitidas no Mês Atual *****/
        $dadosNFS = DB::table('faturamento_nfs')->where('nfs_emp', $empresa)->where('nfs_origem','OS')->orderby('nfs_dt_emi', 'desc')->orderby('nfs_hr_emi', 'desc')->limit(5)->get();

        /* ***** Dados das de NFS-e Simplificadas Emitidas no Mês Atual *****/
        $dadosNFSSimp = DB::table('faturamento_nfs')->where('nfs_emp', $empresa)->where('nfs_origem','ES')->orderby('nfs_dt_emi', 'desc')->orderby('nfs_hr_emi', 'desc')->limit(5)->get();

        /*
        |--------------------------------------------------------------------------
        | Validação da Licença da Empresa
        |--------------------------------------------------------------------------
        |
        | Validações e menssagens do Vencimento da Liçença.
        |
        */

        //As menssagens vão aparecer apenas para Usuários Master e Administradores
        if (Auth::user()->usuario_tipo == 'ADM' || Auth::user()->usuario_tipo == 'M') {
            
            //$modulos = Modulos::getModulos();
            
            //Vamos buscar a data da licença da empresa da exibição da Home
            $validade = DB::table('parametros_sis_modulos')->where('modulo_empresa_codigo', $empresa)->value('modulo_dt_validade');
            $dadosEmpresa = HelperDataSelect::buscaDadosEmpresa($empresa);
        
            $dataValidade = Carbon::parse($validade);
            $dataHoje = Carbon::today();
        
            $diferencaDias = $dataHoje->diffInDays($dataValidade, false);
        
            if ($diferencaDias > 0 && $diferencaDias <= 5) {
                $menssagem = "Faltam ".$diferencaDias." dias para a licença da empresa ".$empresa." - ".$dadosEmpresa->empresa_nome." expirar!</br></br>Data de Validade da Licença: ".Carbon::parse($dataValidade)->format('d/m/Y')."</br></br>Fique atento para não perder o acesso ao sistema.";
                session()->flash('warning', $menssagem);  // Salva a mensagem na sessão
            } elseif ($diferencaDias == 0) {
                $menssagem = "A licença da empresa ".$empresa." - ".$dadosEmpresa->empresa_nome." expira hoje!</br>Fique atento para não perder o acesso ao sistema.";
                session()->flash('warning', $menssagem);  // Salva a mensagem na sessão
            }
        }

        return view('home',[
            'dadosOS' => $dadosOS, 
            'dadosNFS' => $dadosNFS, 
            'cliMes' => $cliMes,
            'osMes' => $osMes,
            'nfsMes' => $nfsMes,
            'nfsSimpMes' => $nfsSimpMes,
            'dadosNFSSimp' => $dadosNFSSimp 
        ]);
    }

    public function contato()
    {
        return view('contato');
    }

    //Redireciona a app para a lançamento de os
    public function homeEmissaoSimpNFS()
    {    
        return view('/faturamento/notas/simplificada/homeEmissaoSimplificadaNFS');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Home da Consulta de Situação das OS
    |----------------------------------------------------------------------------------------------------
    */
    public function homeSituacaoOS()
    {    
        //Definimos a empresa que terá os dados exibidos pela selecionada para exibição
        $empresa = session('glo_empresa_exibicao_home');

        //Gera os dados das Small Box da Home
        $osTot = $this->lancamentosOS->where('os_emp', $empresa)->count();
        $osAberta = $this->lancamentosOS->where('os_emp', $empresa)->where('os_sts', 'A')->count();
        $osFinalizada = $this->lancamentosOS->where('os_emp', $empresa)->where('os_sts', 'F')->count();
        $osCancelada = $this->lancamentosOS->where('os_emp', $empresa)->where('os_sts', 'C')->count();

        //Seta a data para português
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese'); 
        //date_default_timezone_set('America/Sao_Paulo');

        $meses = "[";
        $linTot = "[";
        $linSer = "[";
        $barFin = "[";
        $barCan = "[";
        $valTotMesAntigo = 0;
        $valServMesAntigo = 0;
        $qtdFinMesAntigo = 0;
        $qtdCanMesAntigo = 0;

        $mesAtual = date('m');

        //Pega os 5 meses anteriores ao mes atual
        for ($i = 5; $i > 0; $i--) {

            $dt_ini = date('Y-m-01', strtotime("-$i month"));
            $dt_fin = date("Y-m-t", strtotime("-$i month"));
            $mes_nom = ucfirst(strftime("%B", strtotime($dt_ini)));

            //monta a data inicial dos 6 meses que estão sendo buscados
            if($i == 5){
                $dt_inicial_mes6 = $dt_ini;
            }

            $meses .= "'".$mes_nom."',";

            //Pega o valor total das os do mes
            $cntTot = $this->lancamentosOS->where('os_emp', $empresa)->where('os_sts','F')->whereBetween('os_dhf', [$dt_ini.' 00:00:00', $dt_fin.' 23:59:59'])->sum('os_vlt');
            if(!empty($cntTot)){
                $linTot .= "'".$cntTot."',";

                //verifica se o mes mais antigo que teve valor ja foi registrado ou não
                if($valTotMesAntigo == 0){
                    $valTotMesAntigo = $cntTot;
                }
            }else{
                $linTot .= "'00.0',";
            }

            //Pega o valor total dos serviços da os do mes
            $cntSer = $this->lancamentosOS->where('os_emp', $empresa)->where('os_sts','F')->whereBetween('os_dhf', [$dt_ini.' 00:00:00', $dt_fin.' 23:59:59'])->sum('os_vls');
            if(!empty($cntSer)){
                $linSer .= "'".$cntSer."',";

                //verifica se o mes mais antigo que teve valor ja foi registrado ou não
                if($valServMesAntigo == 0){
                    $valServMesAntigo = $cntSer;
                }
            }else{
                $linSer .= "'00.0',";
            }

            //Pega o valor total de os finalizadas
            $cntFin = $this->lancamentosOS->where('os_emp', $empresa)->where('os_sts','F')->whereBetween('os_dhf', [$dt_ini.' 00:00:00', $dt_fin.' 23:59:59'])->count();
            if(!empty($cntFin)){
                $barFin .= $cntFin.",";

                //verifica se o mes mais antigo que teve valor ja foi registrado ou não
                if($qtdFinMesAntigo == 0){
                    $qtdFinMesAntigo = $cntFin;
                }
            }else{
                $barFin .= "0,";
            }

            //Pega o valor total de os canceladas
            $cntCan = $this->lancamentosOS->where('os_emp', $empresa)->where('os_sts','C')->whereBetween('os_dtc', [$dt_ini, $dt_fin])->count();
            if(!empty($cntCan)){
                $barCan .= $cntCan.",";

                //verifica se o mes mais antigo que teve valor ja foi registrado ou não
                if($qtdCanMesAntigo == 0){
                    $qtdCanMesAntigo = $cntCan;
                }
            }else{
                $barCan .= "0,";
            }
        }

        //Pega o mês atual para o gráfico js
        $dt_ini = date('Y-m-01');
        $dt_fin = date("Y-m-t");
        $mes_nom = ucfirst(strftime("%B", strtotime($dt_ini)));

        $meses .= "'".$mes_nom."']";

        //Soma o valor total das os no mes atual
        $cntTot = $this->lancamentosOS->where('os_emp', $empresa)->where('os_sts','F')->whereBetween('os_dhf', [$dt_ini.' 00:00:00', $dt_fin.' 23:59:59'])->sum('os_vlt');
        if(!empty($cntTot)){
            $linTot .= "'".$cntTot."']";

            $valTotMesNovo = $cntTot;
        }else{
            $linTot .= "'00.0']";

            $valTotMesNovo = 0;
        }
        //Soma o valor total das os durante todo o periodo
        $sumTot = $this->lancamentosOS->where('os_emp', $empresa)->where('os_sts','F')->whereBetween('os_dhf', [$dt_inicial_mes6.' 00:00:00', $dt_fin.' 23:59:59'])->sum('os_vlt');
        if(!empty($sumTot)){
            $valSumTot = $sumTot;
        }else{
            $valSumTot = 0;
        }

        //Soma o valor total dos servicos da os no mes atual
        $cntSer = $this->lancamentosOS->where('os_emp', $empresa)->where('os_sts','F')->whereBetween('os_dhf', [$dt_ini.' 00:00:00', $dt_fin.' 23:59:59'])->sum('os_vls');
        if(!empty($cntSer)){
            $linSer .= "'".$cntSer."']";

            $valServMesNovo = $cntSer;
        }else{
            $linSer .= "'00.0']";

            $valServMesNovo = 0;
        }
        //Soma o valor total dos servicos das os durante todo o periodo
        $sumServ = $this->lancamentosOS->where('os_emp', $empresa)->where('os_sts','F')->whereBetween('os_dhf', [$dt_inicial_mes6.' 00:00:00', $dt_fin.' 23:59:59'])->sum('os_vls');
        if(!empty($sumServ)){
            $valSumServ = $sumServ;
        }else{
            $valSumServ = 0;
        }

        //Calcula a porcentagem de aumento/decréscimo entre o mes atual e o mes mais antigo com dados
        if($valTotMesNovo > 0 && $valTotMesAntigo > 0){
            $perTot = (($valTotMesNovo / $valTotMesAntigo) - 1) * 100;
        }else{
            $perTot = 0;
        }

        //Calcula a porcentagem de aumento/decréscimo entre o mes atual e o mes mais antigo com dados
        if($valServMesNovo > 0 && $valServMesAntigo > 0){
            $perServ = (($valServMesNovo / $valServMesAntigo) - 1) * 100;
        }else{
            $perServ = 0;
        }

        //Soma o total de os finalizadas no mes atual
        $cntFin = $this->lancamentosOS->where('os_emp', $empresa)->where('os_sts','F')->whereBetween('os_dhf', [$dt_ini.' 00:00:00', $dt_fin.' 23:59:59'])->count();
        if(!empty($cntFin)){
            $barFin .= $cntFin."]";

            $qtdFinMesNovo = $cntFin;
        }else{
            $barFin .= "0]";

            $qtdFinMesNovo = 0;
        }
        //Soma o total de os finalizadas durante todo o periodo
        $sumFin = $this->lancamentosOS->where('os_emp', $empresa)->where('os_sts','F')->whereBetween('os_dhf', [$dt_inicial_mes6.' 00:00:00', $dt_fin.' 23:59:59'])->count();
        if(!empty($sumFin)){
            $valSumFin = $sumFin;
        }else{
            $valSumFin = 0;
        }

        //Soma o total de os canceladas no mes atual
        $cntCan = $this->lancamentosOS->where('os_emp', $empresa)->where('os_sts','C')->whereBetween('os_dtc', [$dt_ini, $dt_fin])->count();
        if(!empty($cntCan)){
            $barCan .= $cntCan."]";

            $qtdCanMesNovo = $cntCan;
        }else{
            $barCan .= "0]";

            $qtdCanMesNovo = 0;
        }
        //Soma o total de os canceladas durante todo o periodo
        $sumCan = $this->lancamentosOS->where('os_emp', $empresa)->where('os_sts','C')->whereBetween('os_dhf', [$dt_inicial_mes6.' 00:00:00', $dt_fin.' 23:59:59'])->count();
        if(!empty($sumCan)){
            $valSumCan = $sumCan;
        }else{
            $valSumCan = 0;
        }

        //Calcula a porcentagem de aumento/decréscimo entre o mes atual e o mes mais antigo com dados
        if($qtdFinMesNovo > 0 && $qtdFinMesAntigo > 0){
            $perQtdFin = (($qtdFinMesNovo / $qtdFinMesAntigo) - 1) * 100;
        }else{
            $perQtdFin = 0;
        }

        //Calcula a porcentagem de aumento/decréscimo entre o mes atual e o mes mais antigo com dados
        if($qtdCanMesNovo > 0 && $qtdCanMesAntigo > 0){
            $perQtdCan = (($qtdCanMesNovo / $qtdCanMesAntigo) - 1) * 100;
        }else{
            $perQtdCan = 0;
        }

        return view('/lancamentos/servico/homeSituacaoOS', [
            'osTot'=>$osTot, 
            'osAberta'=>$osAberta, 
            'osFinalizada'=>$osFinalizada, 
            'osCancelada'=>$osCancelada, 
            'meses'=>$meses,
            'linTot'=>$linTot,
            'linSer'=>$linSer,
            'barFin'=>$barFin,
            'barCan'=>$barCan, 
            'perTot'=>$perTot,
            'perServ'=>$perServ,
            'valSumTot'=>$valSumTot,
            'valSumServ'=>$valSumServ,
            'valSumFin'=>$valSumFin,
            'valSumCan'=>$valSumCan,
            'perQtdFin'=>$perQtdFin,
            'perQtdCan'=>$perQtdCan
        ]);
    }

    //Redireciona a app para o faturamento da reemissão de nf
    public function reemissaoNF()
    {    
        return view('/faturamento/notas/controleReemissaoNF');
    }

    //Redireciona a app para o faturamento da reemissão simplificada de nf
    public function reemissaoSimpNF()
    {    
        return view('/faturamento/notas/simplificada/controleReemissaoSimpNF');
    }

    //Redireciona a app para o home do painel de agendamento do prestador
    public function homeAgendamentoPrt()
    {    
        return view('/lancamentos/producao/homeAgendamentoPrestador');
    }

    //Redireciona a app para o home do painel de produção
    public function homePainelProducao()
    {    
        return view('/lancamentos/producao/homePainelProducao');
    }

    //Redireciona a app para o home do painel do operador
    public function homePainelOperador()
    {    
        session()->forget('where_consulta_painelOperador');
        session()->forget('empresaOS_consulta_painelOperador');

        return view('/lancamentos/producao/homePainelOperador');
    }

    //Redireciona a app para o home do painel de produção
    public function homeOrcamentoOS()
    {    
        return view('/lancamentos/servico/homeOrcamentoOS');
    }
}
