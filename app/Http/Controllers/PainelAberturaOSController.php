<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LancamentoSrvOs;
use App\Models\LancamentoSrvOsRequisicoes;
use stdClass;
use Barryvdh\DomPDF\Facade\Pdf;

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

    //Metodo de abertura da edição do serviço da requisição
    public function previsaoEntregaOS($empresa, $numOS)
    {
        $empresaEndereco = DB::table('cadastro_empresa_enderecos')->where('endereco_empresa_codigo', $empresa)->where('endereco_principal', 'S')->get();
        $requisicoesOS = DB::table('lancamento_srv_os_requisicoes')->where('req_emp', $empresa)->where('req_nos', $numOS)->orderby('req_seq', 'asc')->get();
        $servicosOS = DB::table('lancamento_srv_os_servicos')->where('srv_emp', $empresa)->where('srv_nos', $numOS)->orderby('srv_req', 'asc')->orderby('srv_seq', 'asc')->get();
        $dadosOS = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->where('os_nos', $numOS)->get();

        session(['glo_os_dadosEmpresaEndereco' => $empresaEndereco]);
        session(['glo_os_dadosRequisicoes' => $requisicoesOS]);
        session(['glo_os_dadosOS' => $dadosOS]);
        session(['glo_os_dadosServicos' => $servicosOS]);
        session(['glo_os_estagioAPP' => 'PREVISAO_ENTREGA']);
        session(['glo_os_subEstagioRequisicao' => '']);
        session(['glo_os_dadosServicoSelecionado' => '']);
        session(['glo_os_dadosTMO' => '']);
        session(['glo_os_dadosTmoSelecionada' => '']);
        
        return view('/lancamentos/servico/painelAberturaOS');
    }

    //Metodo de abertura da edição do serviço da requisição
    public function orcamentoOS($empresa, $numOS)
    {
        $empresaEndereco = DB::table('cadastro_empresa_enderecos')->where('endereco_empresa_codigo', $empresa)->where('endereco_principal', 'S')->get();
        $requisicoesOS = DB::table('lancamento_srv_os_requisicoes')->where('req_emp', $empresa)->where('req_nos', $numOS)->orderby('req_seq', 'asc')->get();
        $servicosOS = DB::table('lancamento_srv_os_servicos')->where('srv_emp', $empresa)->where('srv_nos', $numOS)->orderby('srv_req', 'asc')->orderby('srv_seq', 'asc')->get();
        $dadosOS = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->where('os_nos', $numOS)->get();

        session(['glo_os_dadosEmpresaEndereco' => $empresaEndereco]);
        session(['glo_os_dadosRequisicoes' => $requisicoesOS]);
        session(['glo_os_dadosOS' => $dadosOS]);
        session(['glo_os_dadosServicos' => $servicosOS]);
        session(['glo_os_estagioAPP' => 'ORCAMENTO_OS']);
        session(['glo_os_subEstagioRequisicao' => '']);
        session(['glo_os_dadosServicoSelecionado' => '']);
        session(['glo_os_dadosTMO' => '']);
        session(['glo_os_dadosTmoSelecionada' => '']);
        
        return view('/lancamentos/servico/painelAberturaOS');
    }

    public function orcamentoGerarPDF($empresa, $numOS)
    {
        $empresaEndereco = DB::table('cadastro_empresa_enderecos')->where('endereco_empresa_codigo', $empresa)->where('endereco_principal', 'S')->get();
        $requisicoesOS = DB::table('lancamento_srv_os_requisicoes')->where('req_emp', $empresa)->where('req_nos', $numOS)->orderby('req_seq', 'asc')->get();
        $servicosOS = DB::table('lancamento_srv_os_servicos')->where('srv_emp', $empresa)->where('srv_nos', $numOS)->orderby('srv_req', 'asc')->orderby('srv_seq', 'asc')->get();
        $dadosOS = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->where('os_nos', $numOS)->get();
        $dadosEmpresa = DB::table('cadastro_empresas')->where('empresa_codigo',$empresa)->get();
        $dadosCliente = DB::table('cadastro_clientes')->where('cliente_codigo',$dadosOS[0]->os_cli)->get();
        $dadosClienteEndereco = DB::table('cadastro_cliente_enderecos')->where('endereco_cliente_codigo',$dadosOS[0]->os_cli)->where('endereco_seq',$dadosOS[0]->os_cli_end)->get();

        // Carregar a string com o HTML/conteúdo e determinar a orientação e o tamanho do arquivo
        //$pdf = PDF::loadView('/lancamentos/servico/impressao/orcamentoPDF', ['glo_os_empresa' => $empresa, 'glo_os_dadosClienteEndereco' => $dadosClienteEndereco, 'glo_os_dadosCliente' => $dadosCliente, 'glo_os_dadosEmpresa' => $dadosEmpresa, 'glo_os_dadosEmpresaEndereco' => $empresaEndereco, 'glo_os_dadosRequisicoes' => $requisicoesOS, 'glo_os_dadosOS' => $dadosOS, 'glo_os_dadosServicos' => $servicosOS])->setPaper('a4', 'portrait');
        $html = response()->view('/lancamentos/servico/impressao/orcamentoPDF', ['glo_os_empresa' => $empresa, 'glo_os_dadosClienteEndereco' => $dadosClienteEndereco, 'glo_os_dadosCliente' => $dadosCliente, 'glo_os_dadosEmpresa' => $dadosEmpresa, 'glo_os_dadosEmpresaEndereco' => $empresaEndereco, 'glo_os_dadosRequisicoes' => $requisicoesOS, 'glo_os_dadosOS' => $dadosOS, 'glo_os_dadosServicos' => $servicosOS])->getContent();

        // pass html to pdf
        $pdf = PDF::loadHTML($html)->setPaper('a4', 'portrait');

        // Fazer o download do arquivo
        //return $pdf->download('orcamento.pdf');
        return $pdf->stream(); 
    }
}
