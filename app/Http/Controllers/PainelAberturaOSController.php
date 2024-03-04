<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LancamentoSrvOs;
use App\Models\LancamentoSrvOsRequisicoes;
use stdClass;

class PainelAberturaOSController extends Controller
{
    /* *****
        |--------------------------------------------------------------------------
        | Controler responsavel pelos comandos de navegação do painel de abertura de OS
        |--------------------------------------------------------------------------
    ***** */

    protected $requisicaoOS;
    
    public function __construct(LancamentoSrvOsRequisicoes $requisicaoOS)
    {
        $this->requisicaoOS = $requisicaoOS;
    }

    /* *************** Área dos comandos de navegação da abertura/consulta da Requisição *************** */

    //Metodo de carregamento da etapa de inclusão de nova requisição
    public function abreRequisicao($empresa, $nos, $estagioAPP, $glo_eat_cod)
    {
        if($glo_eat_cod != 0){
            $etapa = DB::table('lancamento_srv_etapa_atendimentos')->select('eat_cat', 'eat_are')->where('eat_emp', $empresa)->where('eat_cod', $glo_eat_cod)->get();
            
            $glo_eat_are = $etapa[0]->eat_are;
            $glo_eat_cat = $etapa[0]->eat_cat;
        }else{
            $glo_eat_are = '';
            $glo_eat_cat = '';
        }
        
        session(['glo_os_estagioAPP' => $estagioAPP]);
        session(['glo_os_req_eat_cod' => $glo_eat_cod]);
        session(['glo_os_req_eat_cat' => $glo_eat_cat]);
        session(['glo_os_req_eat_are' => $glo_eat_are]);

        return view('/lancamentos/servico/painelAberturaOS');
    }

    //Metodo de carregamento da etapa de consulta dos dados da requisição
    public function consultaRequisicao($empresa, $nos, $estagioAPP, $requisicao)
    {
        $dadosRequisicao = $this->requisicaoOS->where('req_emp', $empresa)->where('req_seq', $requisicao)->where('req_nos', $nos)->get();

        $dadosServico = DB::table('lancamento_srv_os_servicos')->where('srv_emp',$empresa)->where('srv_nos',$nos)->where('srv_req',$requisicao)->orderby('srv_seq','asc')->get();

        session(['glo_os_dadosRequisicoes' => $dadosRequisicao]);
        session(['glo_os_dadosServico' => $dadosServico]);
        session(['glo_os_estagioAPP' => $estagioAPP]);
        session(['glo_os_eat_cod' => $dadosRequisicao[0]->req_eat]);
        session(['glo_os_dadosServicoSelecionado' => '']);
        session(['glo_os_dadosTMO' => '']);
        session(['glo_os_dadosTmoSelecionada' => '']);
        session(['glo_os_subEstagioRequisicao' => '']);

        return view('/lancamentos/servico/painelAberturaOS');
    }

    //Metodo de abertura de um novo servico para a requisição
    public function abrirServicoRequisicao($empresa, $setor, $estagioAPP, $subEstagioRequisicao)
    {
        $dadosTMO = DB::table('parametros_srv_tmos')->where('tmo_emp', $empresa)->where('tmo_set', $setor)->where('tmo_sts', 'A')->orderby('tmo_cod','asc')->get();

        session(['glo_os_estagioAPP' => $estagioAPP]);
        session(['glo_os_subEstagioRequisicao' => $subEstagioRequisicao]);
        session(['glo_os_dadosTMO' => $dadosTMO]);
        session(['glo_os_dadosTmoSelecionada' => '']);
        
        return view('/lancamentos/servico/painelAberturaOS');
    }

    //Metodo de seleção da TMO do serviço para inclusão na requisição
    public function selecionarTMO($empresa, $setor, $codigo, $estagioAPP, $subEstagioRequisicao)
    {
        $dadosTmoSelecionada = DB::table('parametros_srv_tmos')->where('tmo_emp', $empresa)->where('tmo_set', $setor)->where('tmo_cod', $codigo)->orderby('tmo_cod','asc')->get();

        session(['glo_os_estagioAPP' => $estagioAPP]);
        session(['glo_os_subEstagioRequisicao' => $subEstagioRequisicao]);
        session(['glo_os_dadosTmoSelecionada' => $dadosTmoSelecionada]);
        
        return view('/lancamentos/servico/painelAberturaOS');
    }

    //Metodo de abertura da edição do serviço da requisição
    public function consultaServicoRequisicao($empresa, $numOS, $requisicao, $sequencia, $codTMO, $estagioAPP, $subEstagioRequisicao)
    {
        $dadosServico = DB::table('lancamento_srv_os_servicos')->where('srv_emp', $empresa)->where('srv_nos', $numOS)->where('srv_req', $requisicao)->where('srv_seq', $sequencia)->where('srv_tmo', $codTMO)->get();

        session(['glo_os_estagioAPP' => $estagioAPP]);
        session(['glo_os_subEstagioRequisicao' => $subEstagioRequisicao]);
        session(['glo_os_dadosServicoSelecionado' => $dadosServico]);
        session(['glo_os_dadosTMO' => '']);
        session(['glo_os_dadosTmoSelecionada' => '']);
        
        return view('/lancamentos/servico/painelAberturaOS');
    }
}
