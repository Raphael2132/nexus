<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LancamentoSrvOs;
use App\Models\LancamentoSrvOsRequisicoes;
use Illuminate\Support\Facades\Auth;
use stdClass;
use App\Http\Helpers\Helper;

class LancamentoSrvOsController extends Controller
{
    protected $lancamentoOS;
    protected $requisicoesOS;
    
    public function __construct(LancamentoSrvOs $lancamentoOS, LancamentoSrvOsRequisicoes $requisicoesOS)
    {
        $this->lancamentoOS = $lancamentoOS;
        $this->requisicoesOS = $requisicoesOS;
    }

    //Chama a app de controle de pré abertura de OS
    public function inicio(Request $request)
    {
        return view('/lancamentos/servico/controleAberturaOS',['empresa'=>$request->empresa,'cliente'=>$request->cliente]);
    }

    //Chama a app de controle de pré abertura de OS
    public function consultaSituacaoOS($statusOS)
    {
        if($statusOS == 'T'){
            //$dadosOS = $this->lancamentoOS->reorder('os_nos', 'desc')->all();
            $dadosOS = DB::table('lancamento_srv_os')->orderby('os_nos', 'desc')->get();
        }elseif($statusOS == 'A'){
            //$dadosOS = $this->lancamentoOS->where('os_sts','A')->reorder('os_nos', 'desc')->get();
            $dadosOS = DB::table('lancamento_srv_os')->where('os_sts','A')->orderby('os_nos', 'desc')->get();
        }elseif($statusOS == 'F'){
            //$dadosOS = $this->lancamentoOS->where('os_sts','F')->reorder('os_nos', 'desc')->get();
            $dadosOS = DB::table('lancamento_srv_os')->where('os_sts','F')->orderby('os_nos', 'desc')->get();
        }else{
            //$dadosOS = $this->lancamentoOS->where('os_sts','C')->reorder('os_nos', 'desc')->get();
            $dadosOS = DB::table('lancamento_srv_os')->where('os_sts','C')->orderby('os_nos', 'desc')->get();
        }

        return view('/lancamentos/servico/consultaSituacaoOS',['dadosOS'=>$dadosOS,'statusOS'=>$statusOS]);
    }

    //Chama a app de controle de pré abertura de OS
    public function abreOS(Request $request, $empresa, $cliente)
    {
        $data_abertura = date('Y-m-d H:i:s');

        $usuario = Auth::user()->usuario_codigo;

        $nextval=DB::select("SELECT nextval('sq_lancamento_srv_numero_os')")[0]->nextval;
        $numOS = $nextval;

        $dados = [

            'os_nos' => $numOS,
            'os_emp' => $empresa,
            'os_cli' => $cliente,
            'os_cli_end' => $request->enderecoCliOS,
            'os_dha' => $data_abertura,
            'os_res_abr' => $usuario,
            'os_vlt' => '0',
            'os_vos' => '0',
            'os_vls' => '0',
            'os_vlp' => '0',
            'os_per_des' => '0',
            'os_val_des' => '0',
            'os_sts' => 'A'
        ];
        
        $novaOS = LancamentoSrvOs::create($dados);

        return redirect(route('situacaoOS.carregaOS', ['empresa' => $request->empresa, 'cliente' => $request->cliente, 'nos' => $numOS, 'estagioAPP' => 'PRINCIPAL']))->with('success', 'OS Aberta com sucesso!');
    }

    //Metodo de controle de carregamento da OS na pagina principal
    public function carregaOS($empresa, $cliente, $nos, $estagioAPP)
    {
        $dadosOS = $this->lancamentoOS->where('os_emp', $empresa)->where('os_cli', $cliente)->where('os_nos', $nos)->get();

        $dadosRequisicoes = $this->requisicoesOS->where('req_emp', $empresa)->where('req_nos', $nos)->get();

        $dadosEmpresa = DB::table('cadastro_empresas')->where('empresa_codigo',$empresa)->get();

        $dadosCliente = DB::table('cadastro_clientes')->where('cliente_codigo',$cliente)->get();

        $dadosClienteEndereco = DB::table('cadastro_cliente_enderecos')->where('endereco_cliente_codigo',$cliente)->where('endereco_seq',$dadosOS[0]['os_cli_end'])->get();

        //Gera as variaveis globais do painel de OS
        session(['glo_os_dadosOS' => $dadosOS]);
        session(['glo_os_dadosRequisicoes' => $dadosRequisicoes]);
        session(['glo_os_dadosEmpresa' => $dadosEmpresa]);
        session(['glo_os_dadosCliente' => $dadosCliente]);
        session(['glo_os_dadosClienteEndereco' => $dadosClienteEndereco]);
        session(['glo_os_empresa' => $empresa]);
        session(['glo_os_cliente' => $cliente]);
        session(['glo_os_nos' => $nos]);
        session(['glo_os_estagioAPP' => $estagioAPP]);

        return view('/lancamentos/servico/painelAberturaOS');
    }

    //Metodo de controle de carregamento da OS na pagina principal
    public function abrirOrcamento(Request $request, $empresa, $numOS)
    {
        if($request->novoOrcamento == 'S' || ($request->novoOrcamento == 'N' && $request->orcamento == 'Não Gerado')){
            $nextval=DB::select("SELECT nextval('sq_num_orcamento')")[0]->nextval;
            $num = $nextval;

            $data = date('Y-m-d');

            $atualiaServico = DB::table('lancamento_srv_os')
            ->where('os_emp', $empresa)
            ->where('os_nos', $numOS)
            ->update(['os_dt_orc' => $data,
            'os_num_orc' => $num]);  
        }
        
        $empresaEndereco = DB::table('cadastro_empresa_enderecos')->where('endereco_empresa_codigo', $empresa)->where('endereco_principal', 'S')->get();
        $requisicoesOS = DB::table('lancamento_srv_os_requisicoes')->where('req_emp', $empresa)->where('req_nos', $numOS)->orderby('req_seq', 'asc')->get();
        $servicosOS = DB::table('lancamento_srv_os_servicos')->where('srv_emp', $empresa)->where('srv_nos', $numOS)->orderby('srv_req', 'asc')->orderby('srv_seq', 'asc')->get();
        $dadosOS = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->where('os_nos', $numOS)->get();

        session(['glo_os_dadosEmpresaEndereco' => $empresaEndereco]);
        session(['glo_os_dadosRequisicoes' => $requisicoesOS]);
        session(['glo_os_dadosOS' => $dadosOS]);
        session(['glo_os_dadosServicos' => $servicosOS]);
        session(['glo_os_estagioAPP' => 'ORCAMENTO_OS_IMPRESSAO']);
        session(['glo_os_subEstagioRequisicao' => '']);
        session(['glo_os_dadosServicoSelecionado' => '']);
        session(['glo_os_dadosTMO' => '']);
        session(['glo_os_dadosTmoSelecionada' => '']);
        
        return view('/lancamentos/servico/painelAberturaOS');
    }

    //Metodo de controle de carregamento da OS na pagina principal
    public function atualizaPrevEntrega(Request $request, $empresa, $numOS, $cliente)
    {

        $data = Helper::limpaData($request->dataPrevEnt);
        $hora = Helper::limpaHoraMinuto($request->horaPrevEnt);

        $atualiaServico = DB::table('lancamento_srv_os')
        ->where('os_emp', $empresa)
        ->where('os_nos', $numOS)
        ->update(['os_qtd_hr' => $request->qtdHoraOS,
        'os_dpe' => $data,
        'os_hpe' => $hora,
        'os_cli_agr' => $request->clienteAguardaTermino,
        'os_cli_avs' => $request->avisaClienteTermino]);  
        
        return redirect(route('situacaoOS.carregaOS', ['empresa' => $empresa, 'cliente' => $cliente, 'nos' => $numOS, 'estagioAPP' => 'PRINCIPAL']))->with('success', 'Previsão de Entrega Atualizada com Sucesso!');
    }
}
