<?php

namespace App\Http\Controllers;

use App\Models\CadastroCliente;
use App\Models\User;
use App\Models\CadastroEmpresa;
use App\Models\ParametrosFatNfs;
use App\Models\ParametrosFatNfsConexoes;
use App\Models\ParametrosSistemaModulo;
use App\Models\ParametrosSistemaServicoGrupo;
use App\Models\ParametrosSistemaServico;
use App\Models\ParametrosSistemaArea;
use App\Models\ParametrosSrvSetores;
use App\Models\ParametrosSrvTmo;
use App\Models\LancamentoSrvOs;
use App\Models\LancamentoSrvCategorias;
use App\Models\LancamentoSrvTipoServico;
use App\Models\LancamentoSrvEtapaAtendimento;
use App\Models\CadastroPrestadores;
use Illuminate\Http\Request;
use stdClass;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct(CadastroCliente $cliente, 
                                User $usuario, 
                                CadastroEmpresa $empresa, 
                                ParametrosFatNfs $parametrosNfs, 
                                ParametrosFatNfsConexoes $parametrosNfsConexao,
                                ParametrosSistemaModulo $parametrosSistemaModulo, 
                                ParametrosSistemaServicoGrupo $parametrosGrpServico, 
                                ParametrosSistemaServico $parametrosServico, 
                                ParametrosSistemaArea $parametrosSistemaArea, 
                                ParametrosSrvSetores $parametrosServicoSetor,
                                ParametrosSrvTmo $parametrosServicoTMO,
                                LancamentoSrvOs $lancamentosOS, 
                                LancamentoSrvCategorias $lancamentosServicoCategoria,
                                LancamentoSrvTipoServico $lancamentoSrvTipoServico,
                                LancamentoSrvEtapaAtendimento  $lancamentosServicoEtapas,
                                CadastroPrestadores $prestador)
    {
        $this->middleware('auth');
        $this->cliente = $cliente;
        $this->usuario = $usuario;
        $this->empresa = $empresa;
        $this->parametrosNfs = $parametrosNfs;
        $this->parametrosNfsConexao = $parametrosNfsConexao;
        $this->parametrosSistemaModulo = $parametrosSistemaModulo;
        $this->parametrosGrpServico = $parametrosGrpServico;
        $this->parametrosServico = $parametrosServico;
        $this->parametrosSistemaArea = $parametrosSistemaArea;
        $this->parametrosServicoSetor = $parametrosServicoSetor;
        $this->parametrosServicoTMO = $parametrosServicoTMO;
        $this->lancamentosOS = $lancamentosOS;
        $this->lancamentosServicoCategoria = $lancamentosServicoCategoria;
        $this->lancamentoSrvTipoServico = $lancamentoSrvTipoServico;
        $this->lancamentosServicoEtapas = $lancamentosServicoEtapas;
        $this->prestador = $prestador;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $dadosOS = $this->lancamentosOS->orderby('os_dha', 'desc')->limit(5)->get();
        $dadosNFS = DB::table('faturamento_nfs')->limit(5)->orderby('nfs_dt_emi', 'desc')->orderby('nfs_nnfs', 'desc')->get();

        //Data da semana atual
        $dt1 = date('Y-m-d');
        $dt2 = date('Y-m-d', strtotime('-6 days'));

        //Data da semana passada
        $dt3 = date('Y-m-d', strtotime('-7 days'));
        $dt4 = date('Y-m-d', strtotime('-14 days'));

        $qtdNFSfin = DB::table('faturamento_nfs')->where('nfs_sts', 'G')->whereBetween('nfs_dt_emi', [$dt2, $dt1])->count();
        $qtdNFSini = DB::table('faturamento_nfs')->where('nfs_sts', 'G')->whereBetween('nfs_dt_emi', [$dt4, $dt3])->count();

        if($qtdNFSini > $qtdNFSfin){
            if(!empty($qtdNFSfin)){
                $perNfsSemana = (($qtdNFSfin - $qtdNFSini) / $qtdNFSfin) * 100;
            }else{
                $perNfsSemana = -100;
            }
        }else{
            if(!empty($qtdNFSini)){
                $perNfsSemana = (($qtdNFSfin - $qtdNFSini) / $qtdNFSini) * 100;
            }else{
                $perNfsSemana = 0;
            }
        }

        //Seta a data para português
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese'); 
        //date_default_timezone_set('America/Sao_Paulo');

        $diasSemana = "[";
        $qtdSemAtu = "[";
        $qtdSemPas = "[";

        $semAtual = 6;
        $semPassada = 13;

        for($i = 1; $i < 8; $i++){

            $dtAtu = date('Y-m-d', strtotime('-'.$semAtual.' days'));
            $dtPas = date('Y-m-d', strtotime('-'.$semPassada.' days'));

            //$diaSem = utf8_encode(ucfirst(strftime("%A", strtotime($dtAtu))));
            $diaSem = ucfirst(strftime("%A", strtotime($dtAtu)));

            $nfsSemAtu = DB::table('faturamento_nfs')->where('nfs_sts', 'G')->where('nfs_dt_emi', $dtAtu)->count();
            $nfsSemPas = DB::table('faturamento_nfs')->where('nfs_sts', 'G')->where('nfs_dt_emi', $dtPas)->count();

            if($semAtual == 0){
                $diasSemana .= "'".$diaSem."'";
                $qtdSemAtu .= "'".$nfsSemAtu."'";
                $qtdSemPas .= "'".$nfsSemPas."'";
            }else{
                $diasSemana .= "'".$diaSem."',";
                $qtdSemAtu .= "'".$nfsSemAtu."',";
                $qtdSemPas .= "'".$nfsSemPas."',";
            }

            $semAtual -= 1;
            $semPassada -= 1;
        }

        $diasSemana .= "]";
        $qtdSemAtu .= "]";
        $qtdSemPas .= "]";

        
        //Resumo Geral da Empresa no Mês 
        $dtAtuFin = date('Y-m-d');
        $dtAtuIni = date('Y-m-01');

        $dtPasIni = date('Y-m-01', strtotime("-1 month"));
        $dtPasFin = date("Y-m-t", strtotime("-1 month"));

        $vlrNfsAtu = DB::table('faturamento_nfs')->where('nfs_sts', 'G')->whereBetween('nfs_dt_emi', [$dtAtuIni, $dtAtuFin])->sum('nfs_vlr_tot');
        $vlrNfsPas = DB::table('faturamento_nfs')->where('nfs_sts', 'G')->whereBetween('nfs_dt_emi', [$dtPasIni, $dtPasFin])->sum('nfs_vlr_tot');

        if($vlrNfsPas > $vlrNfsAtu){
            if(!empty($vlrNfsAtu)){
                $perVlrNfsMes = (($vlrNfsAtu - $vlrNfsPas) / $vlrNfsAtu) * 100;
            }else{
                $perVlrNfsMes = -100;
            }
        }else{
            if(!empty($vlrNfsPas)){
                $perVlrNfsMes = (($vlrNfsAtu - $vlrNfsPas) / $vlrNfsPas) * 100;
            }else{
                $perVlrNfsMes = 0;
            }
        }

        $qtdOsAtu = DB::table('lancamento_srv_os')->where('os_sts', 'F')->whereBetween('os_dha', [$dtAtuIni.' 00:00:00', $dtAtuFin.' 23:59:59'])->count();
        $qtdOsPas = DB::table('lancamento_srv_os')->where('os_sts', 'F')->whereBetween('os_dha', [$dtPasIni.' 00:00:00', $dtPasFin.' 23:59:59'])->count();

        if($qtdOsPas > $qtdOsAtu){
            if(!empty($qtdOsAtu)){
                $qtdOsMes = (($qtdOsAtu - $qtdOsPas) / $qtdOsAtu) * 100;
            }else{
                $qtdOsMes = -100;
            }
        }else{
            if(!empty($qtdOsPas)){
                $qtdOsMes = (($qtdOsAtu - $qtdOsPas) / $qtdOsPas) * 100;
            }else{
                $qtdOsMes = 0;
            }
        }

        $qtdCliAtu = DB::table('cadastro_clientes')->whereBetween('created_at', [$dtAtuIni.' 00:00:00', $dtAtuFin.' 23:59:59'])->count();
        $qtdCliPas = DB::table('cadastro_clientes')->whereBetween('created_at', [$dtPasIni.' 00:00:00', $dtPasFin.' 23:59:59'])->count();

        if($qtdCliPas > $qtdCliAtu){
            if(!empty($qtdCliAtu)){
                $qtdCliMes = (($qtdCliAtu - $qtdCliPas) / $qtdCliAtu) * 100;
            }else{
                $qtdCliMes = -100;
            }
        }else{
            if(!empty($qtdCliPas)){
                $qtdCliMes = (($qtdCliAtu - $qtdCliPas) / $qtdCliPas) * 100;
            }else{
                $qtdCliMes = 0;
            }
        }

        return view('home',[
            'dadosOS' => $dadosOS, 
            'dadosNFS' => $dadosNFS, 
            'qtdNFS' => $qtdNFSfin, 
            'perNfsSemana' => $perNfsSemana, 
            'diasSemana' => $diasSemana, 
            'qtdSemAtu' => $qtdSemAtu, 
            'qtdSemPas' => $qtdSemPas,
            'perVlrNfsMes' => $perVlrNfsMes,
            'qtdOsMes' => $qtdOsMes,
            'qtdCliMes' => $qtdCliMes]);
    }

    public function contato()
    {
        return view('contato');
    }

    public function fiscal()
    {
        return view('fiscal');
    }

    public function homeClientes()
    {
        //Monta variaveis dos cards
        $cliJuridico = $this->cliente->where('cliente_tipo_pessoa','=','J')->count();
        $cliFisico = $this->cliente->where('cliente_tipo_pessoa','=','F')->count();
        $cliTot = $this->cliente->count();
        
        //Seta a data para português
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese'); 
        //date_default_timezone_set('America/Sao_Paulo');

        $meses = "[";
        $grafJ = "[";
        $grafF = "[";

        //Monta as variaveis para o JS dos ultimos seis meses do gráfico
        for ($i = 6; $i > 1; $i--) {
            $dt_ini = date('Y-m-01', strtotime("-$i month"));
            $dt_fin = date("Y-m-t", strtotime("-$i month"));
            $mes_nom = utf8_encode(ucfirst(strftime("%B", strtotime($dt_ini))));

            $meses .= "'".$mes_nom."',";

            $cntJ = $this->cliente->where('cliente_tipo_pessoa','=','J')->whereBetween('created_at', [$dt_ini, $dt_fin])->count();
            $grafJ .= $cntJ.",";

            $cntF = $this->cliente->where('cliente_tipo_pessoa','=','F')->whereBetween('created_at', [$dt_ini, $dt_fin])->count();
            $grafF .= $cntF.",";
        }

        //Pega o mês atual para o gráfico js
        $dt_ini = date('Y-m-01');
        $dt_fin = date("Y-m-t");
        $mes_nom = ucfirst(strftime("%B", strtotime($dt_ini)));

        $meses .= "'".$mes_nom."']";
        
        $cntJ = $this->cliente->where('cliente_tipo_pessoa','=','J')->whereBetween('created_at', [$dt_ini, $dt_fin])->count();
        $grafJ .= $cntJ."]";

        $cntF = $this->cliente->where('cliente_tipo_pessoa','=','F')->whereBetween('created_at', [$dt_ini, $dt_fin])->count();
        $grafF .= $cntF."]";
      
        return view('/cadastros/cliente/homeClientes',['cliJuridico'=>$cliJuridico,'cliFisico'=>$cliFisico,'cliTot'=>$cliTot,'meses'=>$meses,'grafJ'=>$grafJ,'grafF'=>$grafF]);
    }

    public function bancos()
    {
        return view('bancos');
    }

    public function homeUsuarios()
    {      
        //Monta variaveis dos cards
        $usuAtivo = $this->usuario->where('usuario_status','=','A')->count();
        $usuDesat = $this->usuario->where('usuario_status','=','D')->count();
        $usuTot = $this->usuario->count();
        $usuAdm = $this->usuario->where('usuario_tipo','=','A')->count();
        $usuPdr = $this->usuario->where('usuario_tipo','=','P')->count();

        $usuarios = $this->usuario->reorder('usuario_codigo', 'asc')->get();

        return view('/cadastros/usuario/homeUsuarios',['usuarios'=>$usuarios,'usuAtivo'=>$usuAtivo,'usuDesat'=>$usuDesat,'usuTot'=>$usuTot,'usuAdm'=>$usuAdm,'usuPdr'=>$usuPdr]);
    }

    public function homePrestadores()
    {      

        $totalPrestadores = $this->prestador->count();
        $prestadoresAtivos = $this->prestador->where('prestador_status','=','A')->count();
        $prestadoresDemitidos = $this->prestador->where('prestador_status','=','D')->count();

        $prestadores = $this->prestador->where('prestador_status', 'A')->reorder('prestador_codigo', 'asc')->get();

        return view('/cadastros/prestador/homePrestadores',['prestadores'=>$prestadores,'totalPrestadores'=>$totalPrestadores,'prestadoresAtivos'=>$prestadoresAtivos,'prestadoresDemitidos'=>$prestadoresDemitidos]);
    }

    //Redireciona a app para o home de cadastro de empresas
    public function homeEmpresa()
    {    
        $empresas = $this->empresa->all();

        return view('/cadastros/empresa/homeEmpresa', ['empresas'=>$empresas]);
    }

    //Redireciona a app para a parametrização do faturamento da NFS-e
    public function homeParFatNfs()
    {    
        $emiNfs  = $this->parametrosNfs->all();
        $conNfs  = $this->parametrosNfsConexao->all();

        return view('/parametros/faturamento/nfs/homeParametroFatNfs', ['emiNfs'=>$emiNfs, 'conNfs'=>$conNfs]);
    }

    //Redireciona a app para a parametrização dos modulos do sistema
    public function homeParSisModulo()
    {    
        $modulos  = $this->parametrosSistemaModulo->all();

        return view('/parametros/sistema/homeParametrosSistemaModulos', ['modulos'=>$modulos]);
    }

    //Redireciona a app para a parametrização dos serviços do sistema
    public function homeParSisServico()
    {    
        $grupos_srv = $this->parametrosGrpServico->reorder('grupo_codigo', 'asc')->get();
        $servicos = $this->parametrosServico->reorder('servico_grupo', 'asc')->reorder('servico_codigo', 'asc')->get();

        return view('/parametros/sistema/homeParametrosSistemaServicos', ['grupos'=>$grupos_srv, 'servicos'=>$servicos]);
    }

    //Redireciona a app para a parametrização das áreas do sistema
    public function homeParSisArea()
    {    
        $areas = $this->parametrosSistemaArea->reorder('area_desc', 'asc')->get();

        return view('/parametros/sistema/homeParametrosSistemaAreas', ['areas'=>$areas]);
    }

    //Redireciona a app para a parametrização dos setores sistema
    public function homeParSrvSetor()
    {    
        $setores = $this->parametrosServicoSetor->reorder('setor_area', 'asc')->get();

        return view('/parametros/servico/homeParametrosServicoSetor', ['setores'=>$setores]);
    }

    //Redireciona a app para a parametrização das TMO do serviço
    public function homeParSrvTMO()
    {    
        $tarefas = $this->parametrosServicoTMO->reorder('tmo_cod', 'asc')->get();

        return view('/parametros/servico/homeParametrosServicoTMO', ['tarefas'=>$tarefas]);
    }

    //Redireciona a app para a lançamento de os
    public function homeEmissaoOS()
    {    
        return view('/lancamentos/servico/homeEmissaoOS');
    }

    //Redireciona a app para a lançamento de os
    public function homeSituacaoOS()
    {    
        $osTot = $this->lancamentosOS->count();
        $osAberta = $this->lancamentosOS->where('os_sts', 'A')->count();
        $osFinalizada = $this->lancamentosOS->where('os_sts', 'F')->count();
        $osCancelada = $this->lancamentosOS->where('os_sts', 'C')->count();

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
            $mes_nom = utf8_encode(ucfirst(strftime("%B", strtotime($dt_ini))));

            //monta a data inicial dos 6 meses que estão sendo buscados
            if($i == 5){
                $dt_inicial_mes6 = $dt_ini;
            }

            $meses .= "'".$mes_nom."',";

            //Pega o valor total das os do mes
            $cntTot = $this->lancamentosOS->where('os_sts','F')->whereBetween('os_dhf', [$dt_ini.' 00:00:00', $dt_fin.' 23:59:59'])->sum('os_vlt');
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
            $cntSer = $this->lancamentosOS->where('os_sts','F')->whereBetween('os_dhf', [$dt_ini.' 00:00:00', $dt_fin.' 23:59:59'])->sum('os_vls');
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
            $cntFin = $this->lancamentosOS->where('os_sts','F')->whereBetween('os_dhf', [$dt_ini.' 00:00:00', $dt_fin.' 23:59:59'])->count();
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
            $cntCan = $this->lancamentosOS->where('os_sts','C')->whereBetween('os_dtc', [$dt_ini, $dt_fin])->count();
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
        $cntTot = $this->lancamentosOS->where('os_sts','F')->whereBetween('os_dhf', [$dt_ini.' 00:00:00', $dt_fin.' 23:59:59'])->sum('os_vlt');
        if(!empty($cntTot)){
            $linTot .= "'".$cntTot."']";

            $valTotMesNovo = $cntTot;
        }else{
            $linTot .= "'00.0']";

            $valTotMesNovo = 0;
        }
        //Soma o valor total das os durante todo o periodo
        $sumTot = $this->lancamentosOS->where('os_sts','F')->whereBetween('os_dhf', [$dt_inicial_mes6.' 00:00:00', $dt_fin.' 23:59:59'])->sum('os_vlt');
        if(!empty($sumTot)){
            $valSumTot = $sumTot;
        }else{
            $valSumTot = 0;
        }

        //Soma o valor total dos servicos da os no mes atual
        $cntSer = $this->lancamentosOS->where('os_sts','F')->whereBetween('os_dhf', [$dt_ini.' 00:00:00', $dt_fin.' 23:59:59'])->sum('os_vls');
        if(!empty($cntSer)){
            $linSer .= "'".$cntSer."']";

            $valServMesNovo = $cntSer;
        }else{
            $linSer .= "'00.0']";

            $valServMesNovo = 0;
        }
        //Soma o valor total dos servicos das os durante todo o periodo
        $sumServ = $this->lancamentosOS->where('os_sts','F')->whereBetween('os_dhf', [$dt_inicial_mes6.' 00:00:00', $dt_fin.' 23:59:59'])->sum('os_vls');
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
        $cntFin = $this->lancamentosOS->where('os_sts','F')->whereBetween('os_dhf', [$dt_ini.' 00:00:00', $dt_fin.' 23:59:59'])->count();
        if(!empty($cntFin)){
            $barFin .= $cntFin."]";

            $qtdFinMesNovo = $cntFin;
        }else{
            $barFin .= "0]";

            $qtdFinMesNovo = 0;
        }
        //Soma o total de os finalizadas durante todo o periodo
        $sumFin = $this->lancamentosOS->where('os_sts','F')->whereBetween('os_dhf', [$dt_inicial_mes6.' 00:00:00', $dt_fin.' 23:59:59'])->count();
        if(!empty($sumFin)){
            $valSumFin = $sumFin;
        }else{
            $valSumFin = 0;
        }

        //Soma o total de os canceladas no mes atual
        $cntCan = $this->lancamentosOS->where('os_sts','C')->whereBetween('os_dtc', [$dt_ini, $dt_fin])->count();
        if(!empty($cntCan)){
            $barCan .= $cntCan."]";

            $qtdCanMesNovo = $cntCan;
        }else{
            $barCan .= "0]";

            $qtdCanMesNovo = 0;
        }
        //Soma o total de os canceladas durante todo o periodo
        $sumCan = $this->lancamentosOS->where('os_sts','C')->whereBetween('os_dhf', [$dt_inicial_mes6.' 00:00:00', $dt_fin.' 23:59:59'])->count();
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

        return view('/lancamentos/servico/homeSituacaoOS', ['osTot'=>$osTot, 
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
                                                            'perQtdCan'=>$perQtdCan]);
    }

    //Redireciona a app para a parametrização das categorias de atendimento do lançamento de serviços
    public function homeLancSrvCategoria()
    {    
        $categorias = $this->lancamentosServicoCategoria->reorder('categoria_codigo', 'asc')->get();

        return view('/parametros/servico/homeLancamentosServicoCategoria', ['categorias'=>$categorias]);
    }

    //Redireciona a app para a parametrização de tipo de serviço do lançamento de serviços
    public function homeLancSrvTipo()
    {    
        $tipos = $this->lancamentoSrvTipoServico->reorder('tipsrv_emp', 'asc')->reorder('tipsrv_cod', 'asc')->get();

        return view('/parametros/servico/homeLancamentosServicoTipo', ['tipos'=>$tipos]);
    }

    //Redireciona a app para a parametrização das etapas de atendimento do lançamento de serviços
    public function homeLancSrvEtapas()
    {    
        $etapas = $this->lancamentosServicoEtapas->reorder('eat_cod', 'asc')->get();

        return view('/parametros/servico/homeLancamentosServicoEtapas', ['etapas'=>$etapas]);
    }

    //Redireciona a app para o faturamento da emissão de nf
    public function emissaoNF()
    {    
        return view('/faturamento/notas/controleEmissaoNF');
    }

    //Redireciona a app para o faturamento da reemissão de nf
    public function reemissaoNF()
    {    
        return view('/faturamento/notas/controleReemissaoNF');
    }
}
