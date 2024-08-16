<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class PainelOperadorController extends Controller
{
    public function consultaPainelOperacao(Request $request)
    {
        $where = "";

        //Empresa
        if(!empty($request->empresa)){
            $where = " where os_emp = '".$request->empresa."' ";
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
                $where .= " and os_cli = '".$cliente."' ";
            }
        }

        //Numero da OS / Pedido
        if(!empty($request->numOS)){
            $where .= " and os_nos = '".$request->numOS."' ";
        }

        //Situação da OS / Pedido
        if(!empty($request->status)){
            $where .= " and os_sts = '".$request->status."' ";
        }

        //Data de Inicio / Final
        if(!empty($request->dtIniOS) && !empty($request->dtFinOS)){

            $dt_ini = Helper::limpaData($request->dtIniOS);
            $dt_fin = Helper::limpaData($request->dtFinOS);

            if($dt_ini > $dt_fin){
                return redirect()->back()->with('error', 'A Data Inicial não pode ser maior que a Data Final!');
            }

            $where .= " and date(os_dha) between '".$dt_ini."' and '".$dt_fin."' ";
        }elseif(empty($request->dtIniOS) && !empty($request->dtFinOS)){
            $dt_fin = Helper::limpaData($request->dtFinOS);
            $where .= " and date(os_dha) <= '".$dt_fin."' ";
        }elseif(!empty($request->dtIniOS) && empty($request->dtFinOS)){
            $dt_ini = Helper::limpaData($request->dtIniOS);
            $where .= " and date(os_dha) >= '".$dt_ini."' ";
        }

        //Valor de Inicio / Final
        if(!empty($request->vlrIniOS) && !empty($request->vlrFinOS)){
            $vlr_ini = Helper::limpaValorMonetario($request->vlrIniOS);
            $vlr_fin = Helper::limpaValorMonetario($request->vlrFinOS);
            if($vlr_ini > $vlr_fin){
                return redirect()->back()->with('error', 'O Valor Inicial não pode ser maior que o Valor Final!');
            }
            $where .= " and os_vlt between ".$vlr_ini." and ".$vlr_fin;
        }elseif(empty($request->vlrIniOS) && !empty($request->vlrFinOS)){
            $vlr_fin = Helper::limpaValorMonetario($request->vlrFinOS);
            $where .= " and os_vlt <= ".$vlr_fin;
        }elseif(!empty($request->vlrIniOS) && empty($request->vlrFinOS)){
            $vlr_ini = Helper::limpaValorMonetario($request->vlrIniOS);
            $where .= " and os_vlt >= ".$vlr_ini;
        }
            
        $dados = DB::select("select * from lancamento_srv_os ".$where." order by os_nos desc");

        //Adiciona o where na sessão para usar nos redir da app de operador
        session(['where_consulta_painelOperador' => $where]);
        session(['empresaOS_consulta_painelOperador' => $request->empresa]);

        return view('/lancamentos/producao/consultaPainelOperacao',['dadosOS' => $dados, 'empresa_os' => $request->empresa, 'where_app' => $where]);
    }

    public function consultaPainelOperacaoGET($empresa, $where)
    {
    
        $dados = DB::select("select * from lancamento_srv_os ".$where." order by os_nos desc");

        return view('/lancamentos/producao/consultaPainelOperacao',['dadosOS' => $dados, 'empresa_os' => $empresa, 'where_app' => $where]);
    }

    //Carrega os dados do Modal de Adição de Auxiliar na TMO
    public function carregarDadosModalAddAxuTMO($empresa, $numOS, $requisicao, $servico)
    {
        $dadosOS = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->where('os_nos', $numOS)->first();
        $dadosSrv = DB::table('lancamento_srv_exe_tarefas')->where('exetrf_emp', $empresa)->where('exetrf_nos', $numOS)->where('exetrf_req', $requisicao)->where('exetrf_seq', $servico)->first();
        $dadosCli = DB::table('cadastro_clientes')->where('cliente_codigo', $dadosOS->os_cli)->first();
        $dadosUsu = DB::table('users')->where('usuario_codigo', $dadosOS->os_res_abr)->first();
        $dadosPrtTMO = DB::table('cadastro_prestadores')->where('prestador_codigo', $dadosSrv->exetrf_prt)->first();

        $cntAux = DB::table('lancamento_srv_prt_auxiliares')
        ->where('prtaux_emp', $dadosSrv->exetrf_emp)
        ->where('prtaux_nos', $dadosSrv->exetrf_nos)
        ->where('prtaux_req', $dadosSrv->exetrf_req)
        ->where('prtaux_srv', $dadosSrv->exetrf_seq)
        ->get();

        if(!empty($dadosSrv->exetrf_prt)){
            $notIn = [$dadosSrv->exetrf_prt]; // Inicia o array com o valor exetrf_prt
        }

        foreach ($cntAux as $auxiliar) {
            $notIn[] = $auxiliar->prtaux_prt; // Adiciona cada valor de prtaux_prt ao array
        }

        $dadosListaPrt = DB::table('cadastro_prestadores')
        ->where('prestador_empresa', $empresa)
        ->whereNotIn('prestador_codigo', $notIn) // Passa o array diretamente
        ->where('prestador_are', $dadosSrv->exetrf_are)
        ->where('prestador_set', $dadosSrv->exetrf_set)
        ->get();

        return view('/lancamentos/producao/modalPainelOperadorAddAuxiliarTMO', ['dadosOS' => $dadosOS, 'dadosSrv' => $dadosSrv, 'dadosCli' => $dadosCli, 'dadosUsu' => $dadosUsu, 'dadosPrtTMO' => $dadosPrtTMO, 'dadosListaPrt' => $dadosListaPrt])->render();
    }

    //Carrega os dados do Modal de Adição de Auxiliar na TMO
    public function carregarDadosModalAddChangePrt($empresa, $numOS, $requisicao, $servico)
    {
        $dadosOS = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->where('os_nos', $numOS)->first();
        $dadosSrv = DB::table('lancamento_srv_exe_tarefas')->where('exetrf_emp', $empresa)->where('exetrf_nos', $numOS)->where('exetrf_req', $requisicao)->where('exetrf_seq', $servico)->first();
        $dadosCli = DB::table('cadastro_clientes')->where('cliente_codigo', $dadosOS->os_cli)->first();
        $dadosUsu = DB::table('users')->where('usuario_codigo', $dadosOS->os_res_abr)->first();
        $dadosPrtTMO = DB::table('cadastro_prestadores')->where('prestador_codigo', $dadosSrv->exetrf_prt)->first();
        $dadosListaPrt = DB::table('cadastro_prestadores')
                            ->where('prestador_empresa', $empresa)
                            ->where('prestador_codigo', '<>', $dadosSrv->exetrf_prt)
                            ->where('prestador_are', $dadosSrv->exetrf_are)
                            ->where('prestador_set', $dadosSrv->exetrf_set)
                            ->where('prestador_status', 'A')
                            ->get();
        
        return view('/lancamentos/producao/modalPainelOperadorAddChangePrt', ['dadosOS' => $dadosOS, 'dadosSrv' => $dadosSrv, 'dadosCli' => $dadosCli, 'dadosUsu' => $dadosUsu, 'dadosPrtTMO' => $dadosPrtTMO, 'dadosListaPrt' => $dadosListaPrt])->render();
    }

    //Carrega os dados do Modal de Agenda dos Serviços da OS
    public function carregarDadosModalAgendaSrvOS($empresa, $numOS)
    {
        $dadosOS = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->where('os_nos', $numOS)->first();
        $dadosTMOAgenda = DB::table('vi_lancamento_srv_agenda')
                            ->where('age_empresa', $empresa)
                            ->where('age_nos', $numOS)
                            ->orderby('age_req', 'asc')
                            ->orderby('age_tipo', 'asc')
                            ->orderby('age_seq', 'asc')
                            ->get();
        $dadosCli = DB::table('cadastro_clientes')->where('cliente_codigo', $dadosOS->os_cli)->first();
        $dadosUsu = DB::table('users')->where('usuario_codigo', $dadosOS->os_res_abr)->first();
        
        return view('/lancamentos/producao/modalPainelOperadorAgendaSrvOS', ['dadosOS' => $dadosOS, 'dadosTMOAgenda' => $dadosTMOAgenda, 'dadosCli' => $dadosCli, 'dadosUsu' => $dadosUsu])->render();
    }

    //Carrega os dados do Modal de iniciar o serviço
    public function carregarDadosModalStartService($empresa, $numOS, $requisicao, $servico)
    {
        $dadosOS = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->where('os_nos', $numOS)->first();
        $dadosSrv = DB::table('lancamento_srv_exe_tarefas')->where('exetrf_emp', $empresa)->where('exetrf_nos', $numOS)->where('exetrf_req', $requisicao)->where('exetrf_seq', $servico)->first();
        $dadosCli = DB::table('cadastro_clientes')->where('cliente_codigo', $dadosOS->os_cli)->first();
        $dadosUsu = DB::table('users')->where('usuario_codigo', $dadosOS->os_res_abr)->first();
        $dadosPrtTMO = DB::table('cadastro_prestadores')->where('prestador_codigo', $dadosSrv->exetrf_prt)->first();
        $dadosSetor = DB::table('parametros_srv_setores')->where('setor_codigo', $dadosSrv->exetrf_set)->where('setor_empresa', $dadosSrv->exetrf_emp)->where('setor_area', $dadosSrv->exetrf_are)->first();
        
        return view('/lancamentos/producao/modalPainelOperadorStartService', ['dadosOS' => $dadosOS, 'dadosSrv' => $dadosSrv, 'dadosCli' => $dadosCli, 'dadosUsu' => $dadosUsu, 'dadosPrtTMO' => $dadosPrtTMO, 'dadosSetor' => $dadosSetor])->render();
    }

    //Carrega os dados do Modal de finalizar
    public function carregarDadosModalFinishService($empresa, $numOS, $requisicao, $servico)
    {
        $dadosOS = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->where('os_nos', $numOS)->first();
        $dadosSrv = DB::table('lancamento_srv_exe_tarefas')->where('exetrf_emp', $empresa)->where('exetrf_nos', $numOS)->where('exetrf_req', $requisicao)->where('exetrf_seq', $servico)->first();
        $dadosCli = DB::table('cadastro_clientes')->where('cliente_codigo', $dadosOS->os_cli)->first();
        $dadosUsu = DB::table('users')->where('usuario_codigo', $dadosOS->os_res_abr)->first();
        $dadosPrtTMO = DB::table('cadastro_prestadores')->where('prestador_codigo', $dadosSrv->exetrf_prt)->first();
        $dadosSetor = DB::table('parametros_srv_setores')->where('setor_codigo', $dadosSrv->exetrf_set)->where('setor_empresa', $dadosSrv->exetrf_emp)->where('setor_area', $dadosSrv->exetrf_are)->first();
        
        return view('/lancamentos/producao/modalPainelOperadorFinishService', ['dadosOS' => $dadosOS, 'dadosSrv' => $dadosSrv, 'dadosCli' => $dadosCli, 'dadosUsu' => $dadosUsu, 'dadosPrtTMO' => $dadosPrtTMO, 'dadosSetor' => $dadosSetor])->render();
    }

    //Carrega os dados do Modal de cancelar
    public function carregarDadosModalCancelService($empresa, $numOS, $requisicao, $servico)
    {
        $dadosOS = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->where('os_nos', $numOS)->first();
        $dadosSrv = DB::table('lancamento_srv_exe_tarefas')->where('exetrf_emp', $empresa)->where('exetrf_nos', $numOS)->where('exetrf_req', $requisicao)->where('exetrf_seq', $servico)->first();
        $dadosCli = DB::table('cadastro_clientes')->where('cliente_codigo', $dadosOS->os_cli)->first();
        $dadosUsu = DB::table('users')->where('usuario_codigo', $dadosOS->os_res_abr)->first();
        $dadosPrtTMO = DB::table('cadastro_prestadores')->where('prestador_codigo', $dadosSrv->exetrf_prt)->first();
        $dadosSetor = DB::table('parametros_srv_setores')->where('setor_codigo', $dadosSrv->exetrf_set)->where('setor_empresa', $dadosSrv->exetrf_emp)->where('setor_area', $dadosSrv->exetrf_are)->first();
        
        return view('/lancamentos/producao/modalPainelOperadorCancelService', ['dadosOS' => $dadosOS, 'dadosSrv' => $dadosSrv, 'dadosCli' => $dadosCli, 'dadosUsu' => $dadosUsu, 'dadosPrtTMO' => $dadosPrtTMO, 'dadosSetor' => $dadosSetor])->render();
    }

    //Carrega os dados do Modal de suspender
    public function carregarDadosModalSuspendService($empresa, $numOS, $requisicao, $servico)
    {
        $dadosOS = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->where('os_nos', $numOS)->first();
        $dadosSrv = DB::table('lancamento_srv_exe_tarefas')->where('exetrf_emp', $empresa)->where('exetrf_nos', $numOS)->where('exetrf_req', $requisicao)->where('exetrf_seq', $servico)->first();
        $dadosCli = DB::table('cadastro_clientes')->where('cliente_codigo', $dadosOS->os_cli)->first();
        $dadosUsu = DB::table('users')->where('usuario_codigo', $dadosOS->os_res_abr)->first();
        $dadosPrtTMO = DB::table('cadastro_prestadores')->where('prestador_codigo', $dadosSrv->exetrf_prt)->first();
        $dadosSetor = DB::table('parametros_srv_setores')->where('setor_codigo', $dadosSrv->exetrf_set)->where('setor_empresa', $dadosSrv->exetrf_emp)->where('setor_area', $dadosSrv->exetrf_are)->first();
        
        return view('/lancamentos/producao/modalPainelOperadorSuspendService', ['dadosOS' => $dadosOS, 'dadosSrv' => $dadosSrv, 'dadosCli' => $dadosCli, 'dadosUsu' => $dadosUsu, 'dadosPrtTMO' => $dadosPrtTMO, 'dadosSetor' => $dadosSetor])->render();
    }

    //Carrega os dados do Modal de reabrir
    public function carregarDadosModalReopenService($empresa, $numOS, $requisicao, $servico)
    {
        $dadosOS = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->where('os_nos', $numOS)->first();
        $dadosSrv = DB::table('lancamento_srv_exe_tarefas')->where('exetrf_emp', $empresa)->where('exetrf_nos', $numOS)->where('exetrf_req', $requisicao)->where('exetrf_seq', $servico)->first();
        $dadosCli = DB::table('cadastro_clientes')->where('cliente_codigo', $dadosOS->os_cli)->first();
        $dadosUsu = DB::table('users')->where('usuario_codigo', $dadosOS->os_res_abr)->first();
        $dadosPrtTMO = DB::table('cadastro_prestadores')->where('prestador_codigo', $dadosSrv->exetrf_prt)->first();
        $dadosSetor = DB::table('parametros_srv_setores')->where('setor_codigo', $dadosSrv->exetrf_set)->where('setor_empresa', $dadosSrv->exetrf_emp)->where('setor_area', $dadosSrv->exetrf_are)->first();
        
        return view('/lancamentos/producao/modalPainelOperadorReopenService', ['dadosOS' => $dadosOS, 'dadosSrv' => $dadosSrv, 'dadosCli' => $dadosCli, 'dadosUsu' => $dadosUsu, 'dadosPrtTMO' => $dadosPrtTMO, 'dadosSetor' => $dadosSetor])->render();
    }

    //Carrega os dados do Modal de finalizar a requisição
    public function carregarDadosModalFinishRequisicao($empresa, $numOS, $requisicao)
    {
        $dadosOS = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->where('os_nos', $numOS)->first();
        $dadosReq = DB::table('lancamento_srv_os_requisicoes')->where('req_emp', $empresa)->where('req_nos', $numOS)->where('req_seq', $requisicao)->first();
        $dadosCli = DB::table('cadastro_clientes')->where('cliente_codigo', $dadosOS->os_cli)->first();
        $dadosUsu = DB::table('users')->where('usuario_codigo', $dadosOS->os_res_abr)->first();
        
        return view('/lancamentos/producao/modalPainelOperadorFinishRequisicao', ['dadosOS' => $dadosOS, 'dadosCli' => $dadosCli, 'dadosUsu' => $dadosUsu, 'dadosReq' => $dadosReq])->render();
    }

    //Carrega os dados do Modal de reabrir a requisição
    public function carregarDadosModalReopenRequisicao($empresa, $numOS, $requisicao)
    {
        $dadosOS = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->where('os_nos', $numOS)->first();
        $dadosReq = DB::table('lancamento_srv_os_requisicoes')->where('req_emp', $empresa)->where('req_nos', $numOS)->where('req_seq', $requisicao)->first();
        $dadosCli = DB::table('cadastro_clientes')->where('cliente_codigo', $dadosOS->os_cli)->first();
        $dadosUsu = DB::table('users')->where('usuario_codigo', $dadosOS->os_res_abr)->first();
        
        return view('/lancamentos/producao/modalPainelOperadorReopenRequisicao', ['dadosOS' => $dadosOS, 'dadosCli' => $dadosCli, 'dadosUsu' => $dadosUsu, 'dadosReq' => $dadosReq])->render();
    }
}
